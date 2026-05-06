<?php

namespace App\Controllers;

use App\Controllers\BaseController;
// Import library Intervention Image
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Otomasi extends BaseController
{
    public function index()
{
    $manager = new ImageManager(new Driver());
    
    // 1. Pastikan nama tema di sini SAMA PERSIS dengan nama folder
    $semua_tema = ["tema-game", "tema-scrapbook"]; 
    $userPhotoName = 'foto_vanti.jpg';

    foreach ($semua_tema as $tema) {
        // Game ada 5 hal, Scrapbook ada 4 hal sesuai screenshot kamu
        $totalHalaman = ($tema == "tema-game") ? 5 : 4;

        for ($i = 1; $i <= $totalHalaman; $i++) {
            $templatePath = FCPATH . "uploads/templates/$tema/$i.jpg";
            
            if (file_exists($templatePath)) {
                $template = $manager->read($templatePath);

                // --- LOGIKA TEMPEL FOTO TEMA GAME ---
                if ($tema == "tema-game" && $i == 3) {
                    $userPhoto = $manager->read(FCPATH . "uploads/user_photos/$userPhotoName");
                    $userPhoto->cover(850, 550); 
                    $template->place($userPhoto, 'top-left', 380, 680);
                }

                // --- LOGIKA TEMPEL FOTO TEMA SCRAPBOOK ---
                // Sesuai PDF-mu, slot ada di halaman 3 & 4
                if ($tema == "tema-scrapbook" && ($i == 3 || $i == 4)) {
                    $userPhoto = $manager->read(FCPATH . "uploads/user_photos/$userPhotoName");
                    $userPhoto->cover(450, 600); // Ukuran potret untuk polaroid
                    
                    // Koordinat polaroid di scrapbook
                    $posX = ($i == 3) ? 250 : 550; 
                    $template->place($userPhoto, 'top-left', $posX, 400);
                }

                // 2. SIMPAN DENGAN NAMA TEMANNYA JUGA
                // Biar tidak tertimpa, namanya jadi: tema-game_halaman_1.jpg
                $fileName = $tema . "_halaman_" . $i . ".jpg";
                $template->save(FCPATH . "uploads/results/" . $fileName);
            }
        }
    }

    echo "<h2>Proses Selesai, Vanti! ✨</h2>";
    echo "Sekarang cek folder <b>results</b>. Harus ada file dengan awalan 'tema-game_' DAN 'tema-scrapbook_'.";
}
}