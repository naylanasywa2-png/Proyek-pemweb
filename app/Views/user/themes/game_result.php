<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Digital Album | Result</title>
    <style>
        body { background: #0d0221; color: #00ff41; font-family: 'Courier New', Courier, monospace; text-align: center; padding: 20px; }
        .arcade-frame { border: 10px double #00ff41; padding: 20px; display: inline-block; background: #1a1a2e; box-shadow: 0 0 20px #00ff41; border-radius: 10px; width: 100%; max-width: 400px; }
        h1 { text-shadow: 2px 2px #ff0055; letter-spacing: 2px; font-size: 1.5rem; }
        
        /* CSS Slider Shopee Style */
        .slider-container { display: flex; overflow-x: auto; scroll-snap-type: x mandatory; gap: 15px; padding-bottom: 15px; scrollbar-width: thin; scrollbar-color: #ff0055 #1a1a2e; }
        .slider-img { flex: 0 0 90%; scroll-snap-align: center; border: 3px solid #fff; border-radius: 5px; object-fit: contain; max-height: 450px; }
        
        .btn-download { display: inline-block; margin-top: 20px; padding: 12px 25px; color: white; background: #ff0055; text-decoration: none; font-weight: bold; border-radius: 5px; box-shadow: 0 0 10px #ff0055; }
    </style>
</head>
<body>
    <div class="arcade-frame">
        <h1>CONGRATULATIONS!</h1>
        <p>[ Digital Album Generated ]</p>
        
        <div class="slider-container">
            <img src="<?= base_url('uploads/templates/tema-game/1.jpg'); ?>" class="slider-img">
            <img src="<?= base_url('uploads/templates/tema-game/2.jpg'); ?>" class="slider-img">
            
            <img src="<?= base_url('uploads/results/' . $foto_user_diedit); ?>" class="slider-img" style="border-color: #ff0055;">
            
            <img src="<?= base_url('uploads/templates/tema-game/4.jpg'); ?>" class="slider-img">
            <img src="<?= base_url('uploads/templates/tema-game/5.jpg'); ?>" class="slider-img">
        </div>

        <p style="color: #ff0055; font-size: 0.8rem;">⬅ SWIPE TO SEE YOUR PHOTOS ➡</p>

        <div style="margin-top: 20px;">
            <a href="<?= base_url('otomasi/downloadAlbum/' . $foto_user_diedit); ?>" class="btn-download">
                💾 DOWNLOAD FULL ALBUM
            </a>
            <br><br>
            <a href="<?= base_url('katalog/game'); ?>" style="color: #00ff41; text-decoration: none;">[ Create Another ]</a>
        </div>
    </div>
</body>
</html>