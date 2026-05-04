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
    </style>
</head>
<body>
    <div class="arcade-frame">
        <h1>INSERT COIN - LEVEL 1</h1>
        <p>[ System Status: Loaded Successfully ]</p>
        <p>@author AULIA - Semester 2 Sistem Informasi</p>
        
        <!-- Menampilkan Aset Gambar dari Folder Uploads Vanti -->
        <div class="gallery">
            <img src="<?= base_url('uploads/templates/tema-game/hal1.jpg'); ?>" class="design-img" alt="Halaman 1">
            <br>
            <img src="<?= base_url('uploads/templates/tema-game/hal2.jpg'); ?>" class="design-img" alt="Halaman 2">
        </div>
        
        <div class="container-buttons">
            <!-- Tombol Kembali ke Katalog -->
            <a href="<?= base_url('katalog'); ?>" class="btn-back">⬅ INSERT COIN TO EXIT</a>
            
            <br>
            
            <!-- Tombol Order/Pesan ke Sistem Vanti -->
            <a href="<?= base_url('order/create'); ?>" class="btn-order">🔥 LEVEL UP: ORDER NOW 🔥</a>
        </div>
    </div>
</body>
</html>