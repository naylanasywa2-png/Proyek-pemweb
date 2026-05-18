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
    public function getFormal() { return view('user/themes/formal'); }
    public function getGame() { return view('user/themes/game'); }

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