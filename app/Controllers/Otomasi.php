<?php

namespace App\Controllers;

// Memanggil library Intervention Image
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Otomasi extends BaseController
{
    // =========================================================================
    // 1. FUNGSI BARU: OTOMASI DARI FORM UPLOAD (TEMA GAME)
    // =========================================================================
    public function prosesGame()
    {
        // 1. Tangkap foto yang diupload user dari form
        $file = $this->request->getFile('foto_user');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            
            // 2. Simpan foto user sementara dengan nama acak
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/user_photos', $newName);

            // 3. Mulai proses tempel (Intervention Image)
            $manager = new ImageManager(new Driver());
            
            // Kita proses halaman 3 khusus untuk tema game
            $templatePath = FCPATH . "uploads/templates/tema-game/3.jpg"; 
            
            if (file_exists($templatePath)) {
                $template = $manager->read($templatePath);
                $userPhoto = $manager->read(FCPATH . "uploads/user_photos/" . $newName);

                // --- ATUR UKURAN & KOORDINAT SESUAI CATATANMU ---
                $userPhoto->cover(850, 550); 
                $template->place($userPhoto, 'top-left', 380, 680);

                // 4. Simpan hasilnya dengan nama unik biar tidak tertimpa user lain
                $fileName = "hasil_game_" . time() . ".jpg";
                $template->save(FCPATH . "uploads/results/" . $fileName);

                // 5. TAMPILKAN HASILNYA KE HALAMAN SLIDER RESULT
                // Mengirimkan nama file hasil editan ke View game_result.php
                return view('user/themes/game_result', [
                    'foto_user_diedit' => $fileName
                ]);
            } else {
                return "Oops! File template 3.jpg tidak ditemukan di folder tema-game.";
            }
        }

        return "Oops! Gagal memproses foto. Pastikan format fotonya benar dan tidak rusak.";
    }

    // --- FUNGSI PROSES SCRAPBOOK ---
    public function prosesScrapbook()
    {
        // 1. Tangkap DUA foto dari form
        $file1 = $this->request->getFile('foto_user_1'); // Untuk halaman 3
        $file2 = $this->request->getFile('foto_user_2'); // Untuk halaman 4
        
        if ($file1 && $file1->isValid() && !$file1->hasMoved() && 
            $file2 && $file2->isValid() && !$file2->hasMoved()) {
            
            // 2. Simpan kedua foto dengan nama acak ke folder
            $newName1 = $file1->getRandomName();
            $newName2 = $file2->getRandomName();
            $file1->move(FCPATH . 'uploads/user_photos', $newName1);
            $file2->move(FCPATH . 'uploads/user_photos', $newName2);

            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $timeStr = time(); // Penanda waktu unik untuk nama file hasil

            // --- 3A. PROSES TEMPEL HALAMAN 3 ---
            $templatePath3 = FCPATH . "uploads/templates/tema-scrapbook/3.jpg";
            if (file_exists($templatePath3)) {
                $template3 = $manager->read($templatePath3);
                $userPhoto1 = $manager->read(FCPATH . "uploads/user_photos/" . $newName1);
                
                $userPhoto1->cover(450, 600); // Ukuran potret
                $template3->place($userPhoto1, 'top-left', 250, 400); // Koordinat Halaman 3
                $template3->save(FCPATH . "uploads/results/hasil_scrapbook_3_{$timeStr}.jpg");
            }

            // --- 3B. PROSES TEMPEL HALAMAN 4 ---
            $templatePath4 = FCPATH . "uploads/templates/tema-scrapbook/4.jpg";
            if (file_exists($templatePath4)) {
                $template4 = $manager->read($templatePath4);
                $userPhoto2 = $manager->read(FCPATH . "uploads/user_photos/" . $newName2);
                
                $userPhoto2->cover(450, 600); // Ukuran potret
                $template4->place($userPhoto2, 'top-left', 550, 400); // Koordinat Halaman 4
                $template4->save(FCPATH . "uploads/results/hasil_scrapbook_4_{$timeStr}.jpg");
            }

            // 4. Arahkan ke halaman Result
            return view('user/themes/scrapbook_result', ['suffix' => $timeStr]);
        }
        
        return "Oops! Gagal upload. Pastikan kamu memasukkan dua foto dengan format yang benar.";
    }

    // =========================================================================
    // 2. FUNGSI LAMA: OTOMASI MASSAL / MANUAL (CADANGAN)
    // =========================================================================
    // Fungsi ini tetap aku simpan kalau kamu mau ngetes koordinat foto secara 
    // manual tanpa harus bolak-balik upload gambar lewat form.
    public function index()
    {
        $manager = new ImageManager(new Driver());
        
        // Pastikan nama tema di sini SAMA PERSIS dengan nama folder
        $semua_tema = ["tema-game", "tema-scrapbook"]; 
        $userPhotoName = 'foto_vanti.jpg'; // File foto cadangan untuk testing

        foreach ($semua_tema as $tema) {
            $totalHalaman = ($tema == "tema-game") ? 5 : 4;

            for ($i = 1; $i <= $totalHalaman; $i++) {
                $templatePath = FCPATH . "uploads/templates/$tema/$i.jpg";
                
                if (file_exists($templatePath)) {
                    $template = $manager->read($templatePath);

                    // --- LOGIKA TEMPEL FOTO TEMA GAME ---
                    if ($tema == "tema-game" && $i == 3) {
                        $userPhotoPath = FCPATH . "uploads/user_photos/$userPhotoName";
                        if(file_exists($userPhotoPath)){
                            $userPhoto = $manager->read($userPhotoPath);
                            $userPhoto->cover(850, 550); 
                            $template->place($userPhoto, 'top-left', 380, 680);
                        }
                    }

                    // --- LOGIKA TEMPEL FOTO TEMA SCRAPBOOK ---
                    if ($tema == "tema-scrapbook" && ($i == 3 || $i == 4)) {
                        $userPhotoPath = FCPATH . "uploads/user_photos/$userPhotoName";
                        if(file_exists($userPhotoPath)){
                            $userPhoto = $manager->read($userPhotoPath);
                            $userPhoto->cover(450, 600); // Ukuran potret untuk polaroid
                            
                            $posX = ($i == 3) ? 250 : 550; 
                            $template->place($userPhoto, 'top-left', $posX, 400);
                        }
                    }

                    // SIMPAN DENGAN NAMA TEMANNYA
                    $fileName = $tema . "_halaman_" . $i . ".jpg";
                    $template->save(FCPATH . "uploads/results/" . $fileName);
                }
            }
        }

        echo "<h2>Proses Manual Selesai, Vanti! ✨</h2>";
        echo "Sekarang cek folder <b>results</b>.";
    }
    // =========================================================================
    // 3. FUNGSI DOWNLOAD FULL ALBUM (ZIP)
    // =========================================================================
    public function downloadAlbum($fotoDiedit)
    {
        $zip = new \ZipArchive();
        $zipFileName = "Digital_Album_Game_" . time() . ".zip";
        $zipFilePath = FCPATH . "uploads/results/" . $zipFileName;

        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            // Tambahkan template asli
            $zip->addFile(FCPATH . 'uploads/templates/tema-game/1.jpg', 'Halaman_1.jpg');
            $zip->addFile(FCPATH . 'uploads/templates/tema-game/2.jpg', 'Halaman_2.jpg');
            
            // Tambahkan file hasil editan Vanti (Halaman 3)
            $zip->addFile(FCPATH . 'uploads/results/' . $fotoDiedit, 'Halaman_3.jpg');
            
            // Tambahkan template sisa
            $zip->addFile(FCPATH . 'uploads/templates/tema-game/4.jpg', 'Halaman_4.jpg');
            $zip->addFile(FCPATH . 'uploads/templates/tema-game/5.jpg', 'Halaman_5.jpg');
            
            $zip->close();

            // Paksa browser untuk mengunduh file ZIP tersebut
            return $this->response->download($zipFilePath, null)->setFileName($zipFileName);
        } else {
            return "Oops! Gagal membuat file ZIP. Pastikan ekstensi ZipArchive aktif di PHP kamu.";
        }
    }
}