<?php

namespace App\Controllers;

use App\Models\QuestionModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Database\Exceptions\DatabaseException;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $data = [
            'title' => 'Admin Dashboard',
            // Add more data here if required
        ];
        return view('admin/dashboard', $data);
    }
    // Display the settings page
    public function settings()
    {
        $model = new QuestionModel();
        $data['questions'] = $model->findAll();
        return view('admin/settings', $data);
    }

    // Add a new question
    public function addQuestion()
    {
        $model = new QuestionModel();
        $data = [
            'question' => $this->request->getPost('question'),
            'answer_type' => $this->request->getPost('answer_type'),
        ];
        $model->insert($data);
        return redirect()->to('/admin/settings');
    }

    // Edit an existing question
    public function editQuestion($id)
    {
        $model = new QuestionModel();
        $data['question'] = $model->find($id);
        
        return view('admin/editQuestion', $data);  // Pass question data to edit view
    }

    // Update the question after editing
    public function updateQuestion($id)
    {
        $model = new QuestionModel();
        $data = [
            'question' => $this->request->getPost('question'),
            'answer_type' => $this->request->getPost('answer_type'),
        ];
        $model->update($id, $data);  // Update the question by ID
        return redirect()->to('/admin/settings')->with('success', 'Question updated successfully');
    }

    // Delete a question
    public function deleteQuestion($id)
    {
        $model = new \App\Models\QuestionModel();
        
        try {
            // Attempt to delete the question
            if (!$model->delete($id)) {
                throw new PageNotFoundException('Question not found');
            }

            // Set success message
            return redirect()->to('/admin/settings')->with('success', 'Question deleted successfully');

        } catch (DatabaseException $e) {
            // Catch foreign key constraint violation error
            return redirect()->to('/admin/settings')->with('error', 'Cannot delete the question because it has related answers. Please delete the answers first.');
        }
    }
}
