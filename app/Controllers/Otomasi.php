<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Otomasi extends BaseController
{
    public function index()
    {
        $manager = new ImageManager(new Driver());
        
        // Sesuaikan nama file dengan yang ada di folder public kamu
        $templatePath = FCPATH . 'uploads/templates/frame_album.jpg'; 
        $userPhotoPath = FCPATH . 'uploads/user_photos/foto_vanti.jpg'; 

        // Proses penggabungan
        $img = $manager->read($userPhotoPath);
        $img->resize(500, 500);

        $template = $manager->read($templatePath);
        $template->place($img, 'top-left', 100, 150);

        // Simpan hasil ke folder results
        $template->save(FCPATH . 'uploads/results/hasil_album.jpg');

        echo "Hore! Logika Vanti berhasil dijalankan. Cek folder uploads/results/hasil_album.jpg!";
    }

    public function test()
    {
        $manager = new ImageManager(new Driver());
        $userPhotoPath = FCPATH . 'uploads/user_photos/foto_vanti.jpg'; 
        
        $img = $manager->read($userPhotoPath)->resize(300, 200);
        
        // Menggunakan cara paling aman untuk menampilkan gambar langsung
        header('Content-Type: image/jpeg');
        echo $img->toJpeg();
        exit;
    }
}