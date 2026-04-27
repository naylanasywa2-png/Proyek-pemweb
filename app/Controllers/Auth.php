<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        // Menampilkan halaman login
        return view('login_view');
    }

    public function register()
    {
        // Menampilkan halaman pendaftaran
        return view('register_view');
    }

    public function register_action()
    {
        $session = session();
        $model = new UserModel();
        
        $email = $this->request->getPost('email');

        // --- VALIDASI EMAIL GANDA ---
        $cekUser = $model->where('email', $email)->first();

        if ($cekUser) {
            $session->setFlashdata('msg', 'Ups! Email ini sudah terdaftar. Gunakan email lain ya! ✨');
            return redirect()->to('/register');
        }

        // Simpan data
        $data = [
            'email'    => $email,
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'user',
        ];

        $model->save($data);

        $session->setFlashdata('msg', 'Pendaftaran Berhasil! Silakan Login ✨');
        return redirect()->to('/auth');
    }

    public function login_action()
    {
        $session = session();
        $model = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                
                $session->set([
                    'id_user'   => $user['id_user'],
                    'email'     => $user['email'],
                    'role'      => $user['role'],
                    'logged_in' => true,
                ]);

                // Redirect berdasarkan ROLE
                switch ($user['role']) {
                    case 'admin':
                        return redirect()->to('/admin/dashboard');
                    case 'desainer':
                        return redirect()->to('/desainer/dashboard');
                    case 'vendor':
                        return redirect()->to('/vendor/dashboard');
                    default:
                        return redirect()->to('/user/home');
                }
            } else {
                $session->setFlashdata('msg', 'Password Salah!');
                return redirect()->to('/auth');
            }
        } else {
            $session->setFlashdata('msg', 'Email tidak terdaftar!');
            return redirect()->to('/auth');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth');
    }
}