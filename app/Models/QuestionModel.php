<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table = 'questions';  // The name of your database table
    protected $primaryKey = 'id';    // Primary key of the table
    protected $allowedFields = ['question', 'answer_type'];  // Fields that can be inserted or updated
    
    // Optionally define any validation rules
    protected $validationRules = [
        'question' => 'required|min_length[3]|max_length[255]',
        'answer_type' => 'required|in_list[text,date,link,file,yes_no]',
    ];
    
    // Automatically use created_at field
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
