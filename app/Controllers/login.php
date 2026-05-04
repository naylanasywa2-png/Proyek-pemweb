<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        // Menampilkan halaman login buatan Vanti
        return view('login_view');
    }

    public function auth()
    {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        // Gunakan logika yang lebih rapi dengan session
        if ($username == 'admin' && $password == '12345') {
            // Menyimpan session agar fungsi Order::create() tahu kamu sudah login
            session()->set([
                'id_user'  => 1, // Sementara hardcode untuk tes
                'username' => $username,
                'logged_in'=> true,
            ]);

            return redirect()->to('/katalog')->with('msg', 'Selamat Datang, ' . $username);
        } else {
            return redirect()->back()->with('msg', 'Login Gagal! Username atau password salah.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}