<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class LoginController extends Controller
{
    public function login()
    {
        helper(['form']); // Load form helper for validation
        echo view('login');  // Display the login form
    }

    public function authenticate()
    {
        $session = session();  // Start session
        $model = new UserModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // Use the UserModel to verify credentials
        $user = $model->verifyUser($email, $password);

        if ($user) {
            // Set session data on successful authentication
            $session->set([
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'isLoggedIn' => true,
            ]);
            if(strtolower($user['role']) == 'admin'){
                return redirect()->to('/'.strtolower($user['role']).'/dashboard');
            }
            else {
                return redirect()->to('/'.strtolower($user['role']).'/questionnaire');
            }
        } else {
            $session->setFlashdata('msg', 'Invalid login credentials.');
            return redirect()->to('/login');  // Redirect back to login with error message
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();  // Destroy session
        return redirect()->to('/');  // Redirect to login
    }
}
