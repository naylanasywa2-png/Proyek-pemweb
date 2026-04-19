<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        // Fungsi ini gunanya untuk memanggil tampilan login_view tadi
        return view('login_view');
    }

    public function auth()
    {
        // Nanti di sini kita buat logika cek username & password ke database
        return "Proses login sedang dibangun...";
    }
}