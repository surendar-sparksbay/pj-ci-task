<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class RegistrationController extends Controller
{
    public function register()
    {
        helper(['form']);
        echo view('register');  // Display registration form
    }

    public function processRegistration()
    {
        $model = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $role = $this->request->getVar('role');  // Example: 'Admin' or 'Client'

        // Register the user using UserModel
        $success = $model->registerUser($email, $password, $role);

        if ($success) {
            return redirect()->to('/');  // Redirect to login after successful registration
        } else {
            return redirect()->to('/register')->with('error', 'Failed to register user.');
        }
    }
}
