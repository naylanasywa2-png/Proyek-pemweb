<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Vendor extends BaseController
{
    public function index()
    {
        // Tampilan sementara sebelum tim Frontend setor desain
        return "
        <div style='text-align: center; margin-top: 50px; font-family: sans-serif;'>
            <h1>Selamat Datang, Mitra Vendor! 🏭</h1>
            <p>Halaman Dashboard Vendor sedang dalam tahap integrasi desain oleh tim Frontend.</p>
            <p>Status: <strong>Pipa Data Backend Siap ✅</strong></p>
            <hr style='width: 50%;'>
            <a href='" . base_url('/logout') . "' style='color: red; text-decoration: none; font-weight: bold;'>Logout</a>
        </div>
        ";
    }
}