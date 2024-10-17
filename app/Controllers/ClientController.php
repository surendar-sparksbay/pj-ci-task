<?php

namespace App\Controllers;

use App\Models\AnswerModel;
use App\Models\QuestionModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Exception\Exception;

class ClientController extends BaseController
{
    public function questionnaire()
    {
        // Connect to the default database (Database 1)
        $db1 = \Config\Database::connect();
        $questionModel = new QuestionModel();

        // Connect to the second database (Database 2)
        $db2 = \Config\Database::connect('second_db');

        // Fetch questions from Database 1
        $questions = $questionModel->findAll();

        // Fetch answers from Database 1
        $answerModel = new AnswerModel();
        $userId = session()->get('id');  // Assuming user_id is stored in session
        $answers = $answerModel->where('user_id', $userId)->findAll();

        // Fetch link extractions from Database 2
        $linkExtractions = $db2->table('link_extractions')
                               ->where('user_id', $userId)
                               ->select('question_id, screenshot_path, url')
                               ->get()
                               ->getResultArray();

        // Fetch file uploads from Database 2
        $uploads = $db2->table('uploads')
                       ->where('user_id', $userId)
                       ->select('question_id, file_path')
                       ->get()
                       ->getResultArray();

        // Prepare answers array to index by question_id
        $answersByQuestionId = [];
        foreach ($answers as $answer) {
            $answersByQuestionId[$answer['question_id']] = $answer;
        }

        // Add file paths and link screenshots to the same answer array by question_id
        foreach ($uploads as $upload) {
            // if (isset($answersByQuestionId[$upload['question_id']])) {
                $answersByQuestionId[$upload['question_id']]['file_path'] = $upload['file_path'];
            // }
        }

        foreach ($linkExtractions as $link) {
            // if (isset($answersByQuestionId[$link['question_id']])) {
                $answersByQuestionId[$link['question_id']]['url'] = $link['url'];
                $answersByQuestionId[$link['question_id']]['link_screenshot_path'] = $link['screenshot_path'];
            // }
        }

        // Pass all the data to the view
        $data = [
            'questions' => $questions,
            'answersByQuestionId' => $answersByQuestionId  // Ensure this is passed to the view
        ];
        // echo '<pre>'; print_r($data); exit;
        return view('client/questionnaire', $data);
    }

    public function submitAnswers()
    {
        $answerModel = new AnswerModel();
        $userId = session()->get('id');

        // Get second database connection
        $db2 = \Config\Database::connect('second_db');

        foreach ($this->request->getPost() as $questionId => $answer) {
            $question = (new QuestionModel())->find($questionId);

            if ($question['answer_type'] == 'link') {
                // Handle link extractions and save in second database
                $screenshotPath = $this->captureScreenshot($answer);  // Capture screenshot
                $screenshotPath = str_replace('\\', '/', $screenshotPath);

                // Check if the record exists in `link_extractions` table
                $existingLink = $db2->table('link_extractions')
                                    ->where('user_id', $userId)
                                    ->where('question_id', $questionId)
                                    ->get()
                                    ->getRow();

                $linkData = [
                    'user_id' => $userId,
                    'url' => $answer,
                    'screenshot_path' => $screenshotPath,
                    'question_id' => $questionId,
                ];

                if ($existingLink) {
                    // If the record exists, update it
                    $db2->table('link_extractions')
                        ->where('user_id', $userId)
                        ->where('question_id', $questionId)
                        ->update($linkData);
                } else {
                    // If no record exists, insert a new one
                    $db2->table('link_extractions')->insert($linkData);
                }

            } elseif ($question['answer_type'] == 'file') {
                // Handle file uploads and save in second database
                $file = $this->request->getFile($questionId);
                if ($file->isValid()) {
                    $extension = $file->getClientExtension();
                    $fileData = [];
                    
                    if ($extension == 'doc' || $extension == 'docx') {
                        // Set the PDF renderer (TCPDF in this case)
                        Settings::setPdfRendererName(Settings::PDF_RENDERER_TCPDF);
                        Settings::setPdfRendererPath(VENDORPATH . 'tecnickcom/tcpdf');
                        
                        // Convert Word file to PDF
                        try {
                            $pdfPath = $this->convertWordToPDF($file);
                            $fileData = [
                                'user_id' => $userId,
                                'question_id' => $questionId,
                                'file_path' => $pdfPath,
                            ];
                        } catch (InvalidImageException $e) {
                            return redirect()->back()->with('error', 'Invalid image in the Word document.');
                        }

                    } else {
                        $newFileName = $file->getRandomName();
                        $file->move(FCPATH . 'uploads', $newFileName);
                        $fileData = [
                            'user_id' => $userId,
                            'file_path' => 'uploads/' . $newFileName,
                            'question_id' => $questionId,
                        ];
                    }

                    // Check if the record exists in `uploads` table
                    $existingFile = $db2->table('uploads')
                                        ->where('user_id', $userId)
                                        ->where('question_id', $questionId)
                                        ->get()
                                        ->getRow();

                    if ($existingFile) {
                        // If the record exists, update it
                        $db2->table('uploads')
                            ->where('user_id', $userId)
                            ->where('question_id', $questionId)
                            ->update($fileData);
                    } else {
                        // If no record exists, insert a new one
                        $db2->table('uploads')->insert($fileData);
                    }
                }

            } else {
                // For other types of answers (e.g., text, date, yes/no)
                $existingAnswer = $answerModel->where('user_id', $userId)
                                            ->where('question_id', $questionId)
                                            ->get()
                                            ->getRow();

                $answerData = [
                    'question_id' => $questionId,
                    'user_id' => $userId,
                    'answer' => $answer,
                ];

                if ($existingAnswer) {
                    // If the record exists, update it
                    $answerModel->where('user_id', $userId)
                                ->where('question_id', $questionId)
                                ->set('answer', $answer)
                                ->update();
                } else {
                    // Insert new answer
                    $answerModel->insert($answerData);
                }
            }
        }

        return redirect()->to('/client/questionnaire')->with('success', 'Answers submitted successfully');
    }


    private function convertWordToPDF($file)
    {
         // Load the Word document
         $phpWord = IOFactory::load($file->getTempName());

         // Generate PDF file path
         $pdfFileName = $file->getRandomName() . '.pdf';
         $pdfFilePath = FCPATH . 'uploads/pdf/' . $pdfFileName;
 
         // Create the PDF writer
         $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
         
         // Save the PDF file
         $pdfWriter->save($pdfFilePath);
 
         return 'uploads/pdf/' . $pdfFileName;
    }

    private function captureScreenshot($url)
    {
        // Define the output file path for the screenshot
        $uniqueIDName = uniqid() . '.png';
        $screenshotPath = FCPATH . 'uploads/screenshots/' . $uniqueIDName;

        // Command to run the Node.js Puppeteer script
        $command = escapeshellcmd("node ".WRITEPATH."node/screenshot.js $url $screenshotPath");

        // Execute the command
        $output = shell_exec($command);

        // echo "node ".FCPATH."node/screenshot.js $url $screenshotPath"; exit;

        // Check if the screenshot was successfully created
        if (file_exists($screenshotPath)) {
            return $uniqueIDName;
        }

        return null;  // Return null if the screenshot failed
    }

    public function captureScreenshotAjax()
    {
        // Ensure it's an AJAX request
        if ($this->request->isAJAX()) {
            $request = $this->request->getJSON();

            $url = $request->url;
            $questionId = $request->question_id;

            // Capture the screenshot
            $screenshotPath = $this->captureScreenshot($url);

            if ($screenshotPath) {
                // Return the screenshot path to the frontend
                return $this->response->setJSON(['screenshot' => base_url('uploads/screenshots/' . $screenshotPath)]);
            } else {
                return $this->response->setJSON(['error' => 'Failed to capture screenshot']);
            }
        }

        // If not an AJAX request, return error
        return $this->response->setStatusCode(400, 'Bad Request');
    }

}
