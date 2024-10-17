<?php

namespace App\Models;

use CodeIgniter\Model;

class AnswerModel extends Model
{
    protected $table = 'answers';             // The table this model refers to
    protected $primaryKey = 'id';             // Primary key of the table
    protected $allowedFields = ['question_id', 'user_id', 'answer', 'file_path', 'link_screenshot_path'];  // Fields allowed for insertion
    protected $useTimestamps = true;          // Automatically handle timestamps
    protected $createdField  = 'created_at';  // Track when answers were created

    /**
     * Get answers by user ID.
     * 
     * @param int $userId The ID of the user.
     * @return array Returns the list of answers provided by the user.
     */
    public function getAnswersByUserId($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }

    /**
     * Get answers by question ID.
     * 
     * @param int $questionId The ID of the question.
     * @return array Returns the list of answers for the question.
     */
    public function getAnswersByQuestionId($questionId)
    {
        return $this->where('question_id', $questionId)->findAll();
    }
}
