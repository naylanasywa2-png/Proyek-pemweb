<?php

namespace App\Controllers;

class Home extends BaseController
{
    // 1. Menampilkan Halaman Utama (Home)
    public function index()
    {
        return view('user/index');
    }

    // 2. Menampilkan Halaman Katalog Utama
    public function katalog()
    {
        return view('user/katalog');
    }

    // 3. Fungsi untuk tiap Tema (Agar kartu bisa dipencet dan masuk)
    public function game() { return view('user/themes/game'); }
    public function scrapbook() { return view('user/themes/scrapbook'); }
    public function vintage() { return view('user/themes/vintage'); }
    public function mafia() { return view('user/themes/mafia'); }
}