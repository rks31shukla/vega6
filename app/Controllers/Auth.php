<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        return view('auth/register');
    }
    
    public function attemptRegister()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'matches[password]',
            'profile_image' => 'uploaded[profile_image]|max_size[profile_image,4096]|is_image[profile_image]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Handle file upload
        $file = $this->request->getFile('profile_image');
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads', $newName);
            // $file->move(WRITEPATH . 'uploads', $newName);
        }
        
        // Create user
        $userModel = new UserModel();
        $userModel->save([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'profile_image' => $newName ?? null,
        ]);
        
        return redirect()->to('/login')->with('success', 'Registration successful. Please login.');
    }
    
    public function login()
    {
        return view('auth/login');
    }
    
    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
        
        if ($user && password_verify($password, $user['password'])) {
            // Set session
            $session = session();
            $userData = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'profile_image' => $user['profile_image'],
                'logged_in' => true,
            ];
            $session->set($userData);
            
            return redirect()->to('/dashboard');
        }
        
        return redirect()->back()->withInput()->with('error', 'Invalid login credentials');
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
