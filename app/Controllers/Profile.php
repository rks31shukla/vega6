<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Profile',
            'user' => session()->get(),
        ];
        
        return view('profile/index', $data);
    }
    
    public function update()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email,id,'.session()->get('id').']',
        ];
        
        if ($this->request->getFile('profile_image')->isValid()) {
            $rules['profile_image'] = 'uploaded[profile_image]|max_size[profile_image,1024]|is_image[profile_image]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $userData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];
        
        // Handle password update if provided
        if ($this->request->getPost('password')) {
            if ($this->request->getPost('password') !== $this->request->getPost('confirm_password')) {
                return redirect()->back()->with('error', 'Passwords do not match');
            }
            $userData['password'] = $this->request->getPost('password');
        }
        
        // Handle file upload if provided
        $file = $this->request->getFile('profile_image');
        if ($file->isValid() && !$file->hasMoved()) {
            // Delete old profile image if exists
            if (session()->get('profile_image')) {
                $oldImagePath = WRITEPATH . 'uploads/' . session()->get('profile_image');
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads', $newName);
            $userData['profile_image'] = $newName;
        }
        
        // Update user
        $userModel = new UserModel();
        $userModel->update(session()->get('id'), $userData);
        
        // Update session data
        $user = $userModel->find(session()->get('id'));
        session()->set([
            'name' => $user['name'],
            'email' => $user['email'],
            'profile_image' => $user['profile_image'],
        ]);
        
        return redirect()->to('/profile')->with('success', 'Profile updated successfully');
    }
}
