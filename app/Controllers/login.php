<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        // Menampilkan halaman login
        return view('login_view');
    }

    public function auth()
    {
        // 1. Ambil data dari inputan form login_view
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        // 2. Logika sederhana (sementara sebelum ke database)
        if ($username == 'admin' && $password == '12345') {
            return "Selamat Datang, " . $username . "! Anda berhasil masuk.";
        } else {
            return "Login Gagal! Username atau password salah.";
        }
    }
}