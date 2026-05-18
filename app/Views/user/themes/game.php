<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Mode | @author AULIA</title>
    <style>
        /* Desain Latar Belakang & Font */
        body { 
            background: #0d0221; 
            color: #00ff41; 
            font-family: 'Courier New', Courier, monospace; 
            text-align: center; 
            padding: 20px; 
            overflow-x: hidden; 
        }

        /* Bingkai Utama Arcade */
        .arcade-frame { 
            border: 10px double #00ff41; 
            padding: 20px; 
            display: inline-block; 
            background: #1a1a2e; 
            box-shadow: 0 0 20px #00ff41; 
            border-radius: 10px; 
            max-width: 90%;
        }

        /* Efek Kedip Judul */
        h1 { 
            text-shadow: 2px 2px #ff0055; 
            letter-spacing: 5px; 
            animation: blink 1s infinite; 
        }

        @keyframes blink { 
            50% { opacity: 0.5; } 
        }

        /* Pengaturan Gambar Desain Vanti */
        .design-img { 
            width: 100%; 
            max-width: 600px; 
            border: 3px solid #fff; 
            margin: 15px 0; 
            filter: contrast(1.2); 
            border-radius: 5px;
        }

        /* Tombol Kembali (Hijau) */
        .btn-back { 
            display: inline-block; 
            margin-top: 20px; 
            padding: 10px 25px; 
            color: #0d0221; 
            background: #00ff41; 
            text-decoration: none; 
            font-weight: bold; 
            border-radius: 5px; 
            transition: 0.3s; 
            border: none;
            cursor: pointer;
        }

        .btn-back:hover { 
            background: #fff; 
            transform: scale(1.05); 
        }

        /* Tombol Pesan/Order (Pink Neon) */
        .btn-order { 
            display: inline-block; 
            margin-top: 15px; 
            padding: 12px 30px; 
            color: white; 
            background: #ff0055; 
            text-shadow: 1px 1px 2px black;
            text-decoration: none; 
            font-weight: bold; 
            font-size: 1.1rem;
            border-radius: 5px; 
            border: 2px solid #fff;
            box-shadow: 0 0 15px #ff0055;
            transition: 0.3s; 
            cursor: pointer;
        }

        .btn-order:hover { 
            background: #00ff41; 
            color: #0d0221; 
            box-shadow: 0 0 25px #00ff41;
            transform: translateY(-5px);
            border-color: #0d0221;
        }

        .container-buttons {
            margin-top: 20px;
        }
        .slider-wrapper {
            width: 100%;
            max-width: 350px;
            margin: 15px auto;
        }
        .slider-container {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 15px;
            padding-bottom: 15px;
            /* Styling Scrollbar biar bernuansa Game/Cyberpunk */
            scrollbar-width: thin;
            scrollbar-color: #00ff41 #1a1a2e;
        }
        /* Khusus untuk browser Chrome/Edge/Safari */
        .slider-container::-webkit-scrollbar {
            height: 10px;
        }
        .slider-container::-webkit-scrollbar-thumb {
            background: #00ff41;
            border-radius: 10px;
        }
        .slider-container::-webkit-scrollbar-track {
            background: #1a1a2e;
            border-radius: 10px;
        }
        .slider-img {
            flex: 0 0 90%; /* Ukuran 85% supaya ujung gambar sebelahnya sedikit terlihat */
            scroll-snap-align: center;
            border: 3px solid #00ff41;
            border-radius: 5px;
            object-fit: contain;
            max-height: 400px;
        }
    </style>
</head>
<body>
    <div class="arcade-frame">
        <h1>INSERT COIN - LEVEL 1</h1>
        <p>[ System Status: Loaded Successfully ]</p>
        <p>@author AULIA - Semester 2 Sistem Informasi</p>
        
        <!-- Menampilkan Aset Gambar dari Folder Uploads Vanti -->
        <p style="font-size: 0.9em; color: #ff0055;">[ SWIPE / GESER KANAN KIRI UNTUK MELIHAT FULL ALBUM ]</p>
        <div class="slider-wrapper">
            <div class="slider-container">
                <img src="<?= base_url('uploads/templates/tema-game/1.jpg'); ?>" class="slider-img" alt="Halaman 1">
                <img src="<?= base_url('uploads/templates/tema-game/2.jpg'); ?>" class="slider-img" alt="Halaman 2">
                <img src="<?= base_url('uploads/templates/tema-game/3.jpg'); ?>" class="slider-img" alt="Halaman 3">
                <img src="<?= base_url('uploads/templates/tema-game/4.jpg'); ?>" class="slider-img" alt="Halaman 4">
                <img src="<?= base_url('uploads/templates/tema-game/5.jpg'); ?>" class="slider-img" alt="Halaman 5">
            </div>
        </div>
        
        <div class="container-buttons">
            <a href="<?= base_url('katalog'); ?>" class="btn-back">⬅ INSERT COIN TO EXIT</a>
            
            <br><br>
            
            <form action="<?= base_url('otomasi/prosesGame'); ?>" method="post" enctype="multipart/form-data" style="margin-bottom: 20px; border: 2px dashed #ff0055; padding: 15px; border-radius: 10px; display: inline-block; max-width: 400px; text-align: center;">
                <p style="color: #ff0055; margin-bottom: 10px; font-weight: bold;">[ INSERT YOUR AVATAR / FOTO ]</p>
                
                <input type="file" name="foto_user" accept="image/*" required style="margin-bottom: 15px; color: #fff;">
                <br>
                
                <button type="submit" class="btn-order" style="background: #00ff41; color: #0d0221; box-shadow: 0 0 15px #00ff41; border: none; width: 100%;">
                    ⚡ GENERATE MY ALBUM ⚡
                </button>
            </form>

            <br>
            
            <a href="<?= base_url('order/create'); ?>" class="btn-order">🔥 LEVEL UP: ORDER NOW 🔥</a>
        </div>
    </div>
</body>
</html>