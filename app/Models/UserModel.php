<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';             // The table this model refers to
    protected $primaryKey = 'id';           // The primary key for this table
    protected $allowedFields = ['email', 'password', 'role'];  // Fields that are allowed to be inserted/updated
    protected $useTimestamps = true;        // Automatically handle created_at and updated_at
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    /**
     * Verify user credentials by checking the email and password.
     * 
     * @param string $email The user's email.
     * @param string $password The user's password (plain text).
     * @return array|null Returns user data if successful, otherwise null.
     */
    public function verifyUser($email, $password)
    {
        // Fetch user by email
        $user = $this->where('email', $email)->first();

        // If user exists, verify password
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }

    /**
     * Registers a new user.
     * 
     * @param string $email The user's email.
     * @param string $password The user's password (plain text).
     * @param string $role The user's role ('Admin' or 'Client').
     * @return bool Returns true on success, false on failure.
     */
    public function registerUser($email, $password, $role)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);  // Hash the password

        // Insert the new user into the database
        return $this->insert([
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role,
        ]);
    }
}
    