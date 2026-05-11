<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function index()
    {
        return view('login_view');
    }

    public function register()
    {
        return view('register_view');
    }

    public function getKatalog()
    {
        return view('user/katalog');
    }

    // --- RUTE TEMA KATALOG (PASTIKAN SEMUA ADA DI SINI) ---
    public function getScrapbook() { return view('user/themes/scrapbook'); }
    public function getVintage() { return view('user/themes/vintage'); }
    public function getMafia() { return view('user/themes/mafia'); }
    public function getUrban() { return view('user/themes/urban'); }
    public function getGrandAcademy() { return view('user/themes/academy'); }
    
    // Ini yang tadi kurang, makanya error "getFormal not found"
    public function getFormal() { return view('user/themes/formal'); }

    // --- FUNGSI LOGIN & LOGOUT (Jika sudah ada sebelumnya, pastikan tetap ada) ---
    public function login_action()
    {
        // ... kode login kamu ...
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}