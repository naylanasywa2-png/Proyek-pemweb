<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Intervention\Image\ImageManagerStatic as Image; // Ini memanggil senjata Vanti

class Otomasi extends BaseController
{
    public function index()
    {
        // 1. Tentukan lokasi foto (Nanti ini diambil dari database/upload)
        $templatePath = FCPATH . 'uploads/templates/frame_album.png'; // File bingkai
        $userPhotoPath = FCPATH . 'uploads/user_photos/foto_vanti.jpg'; // Foto user

        // 2. Load gambar menggunakan library Intervention
        // Ibaratnya Vanti sedang mengambil kertas foto dan bingkai
        $img = Image::make($userPhotoPath);

        // 3. LOGIKA: Resize foto user agar pas di dalam bingkai
        // Kita paksa ukurannya jadi 500x500 pixel
        $img->resize(500, 500);

        // 4. LOGIKA: Tempelkan foto user ke Template
        // Kita tempel foto user di koordinat X=100, Y=150
        $template = Image::make($templatePath);
        $template->insert($img, 'top-left', 100, 150);

        // 5. Simpan hasilnya ke folder results
        $template->save(FCPATH . 'uploads/results/hasil_album.jpg');

        echo "Hore! Logika Vanti berhasil dijalankan. Cek folder uploads/results!";
    }
}