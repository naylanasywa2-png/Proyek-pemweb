<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scrapbook Gallery | @author AULIA</title>
    <style>
        body { background-color: #f4ece1; background-image: radial-gradient(#dcc5a1 1px, transparent 1px); background-size: 20px 20px; font-family: 'Segoe Script', cursive; color: #5d4037; }
        .scrapbook-page { max-width: 800px; margin: 50px auto; background: #fffdf9; padding: 40px; box-shadow: 5px 5px 15px rgba(0,0,0,0.1); border-left: 20px solid #8d6e63; position: relative; }
        h1 { border-bottom: 2px dashed #8d6e63; display: inline-block; margin-bottom: 10px; }
        .btn-back { position: absolute; top: 10px; right: 20px; color: #8d6e63; text-decoration: none; font-weight: bold; font-family: sans-serif; }
        
        /* Style Tambahan untuk Tombol Biar Senada */
        .btn-order {
            background-color: #ff6b81; color: white; padding: 12px 30px; border-radius: 25px;
            text-decoration: none; font-family: 'Quicksand', sans-serif; font-weight: bold;
            box-shadow: 0 4px 15px rgba(255, 107, 129, 0.3); display: inline-block; transition: 0.3s; border: none; cursor: pointer;
        }
        .btn-order:hover { transform: scale(1.05); background-color: #ff4d6d; }

        /* --- STYLE SLIDER (GAYA SHOPEE) DENGAN NUANSA SCRAPBOOK --- */
        .slider-wrapper { width: 100%; max-width: 400px; margin: 20px auto; }
        .slider-container { display: flex; overflow-x: auto; scroll-snap-type: x mandatory; gap: 20px; padding-bottom: 15px; }
        .slider-item { flex: 0 0 85%; scroll-snap-align: center; }
        
        /* Bingkai Foto Polaroid */
        .photo-frame-slide { 
            background: white; padding: 15px 15px 40px 15px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.2); 
        }
        .photo-frame-slide img { width: 100%; height: auto; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="scrapbook-page">
        <a href="<?= base_url('katalog'); ?>" class="btn-back">✖ Close</a>
        
        <div style="text-align: center;">
            <h1>Our Little Stories</h1>
            <p style="font-size: 14px; font-family: sans-serif;">[ GESER KANAN-KIRI UNTUK MELIHAT ALBUM ]</p>
        </div>

        <div class="slider-wrapper">
            <div class="slider-container">
                <div class="slider-item">
                    <div class="photo-frame-slide" style="transform: rotate(-2deg);">
                        <img src="<?= base_url('uploads/templates/tema-scrapbook/1.jpg'); ?>" alt="Halaman 1">
                    </div>
                </div>
                <div class="slider-item">
                    <div class="photo-frame-slide" style="transform: rotate(2deg);">
                        <img src="<?= base_url('uploads/templates/tema-scrapbook/2.jpg'); ?>" alt="Halaman 2">
                    </div>
                </div>
                <div class="slider-item">
                    <div class="photo-frame-slide" style="transform: rotate(-1deg);">
                        <img src="<?= base_url('uploads/templates/tema-scrapbook/3.jpg'); ?>" alt="Halaman 3">
                    </div>
                </div>
                <div class="slider-item">
                    <div class="photo-frame-slide" style="transform: rotate(3deg);">
                        <img src="<?= base_url('uploads/templates/tema-scrapbook/4.jpg'); ?>" alt="Halaman 4">
                    </div>
                </div>
            </div>
        </div>

        <form action="<?= base_url('otomasi/prosesScrapbook'); ?>" method="post" enctype="multipart/form-data" style="margin-top: 30px; text-align: center; border: 2px dashed #8d6e63; padding: 20px; background: #fffdf9;">
            <p style="font-family: sans-serif; font-weight: bold; color: #8d6e63;">📸 UPLOAD 2 FOTO KENANGANMU 📸</p>
            
            <div style="margin-bottom: 15px; text-align: left; display: inline-block;">
                <label style="font-family: sans-serif; font-size: 14px; color: #8d6e63; font-weight: bold;">Foto untuk Halaman 3:</label><br>
                <input type="file" name="foto_user_1" accept="image/*" required style="font-family: sans-serif; margin-top: 5px;">
            </div>
            <br>
            <div style="margin-bottom: 20px; text-align: left; display: inline-block;">
                <label style="font-family: sans-serif; font-size: 14px; color: #8d6e63; font-weight: bold;">Foto untuk Halaman 4:</label><br>
                <input type="file" name="foto_user_2" accept="image/*" required style="font-family: sans-serif; margin-top: 5px;">
            </div>
            <br>
            <button type="submit" class="btn-order" style="background-color: #8d6e63;">⚡ GENERATE ALBUM DIGITAL ⚡</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <p style="font-size: 14px; font-style: italic; font-family: sans-serif;">Ingin mencetak momenmu ini? ✨</p>
            <a href="<?= base_url('order/create') ?>" class="btn-order">
                ✂️ PESAN FISIK SEKARANG ✂️
            </a>
        </div>

    </div>
</body>
</html>