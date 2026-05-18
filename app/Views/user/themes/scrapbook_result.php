<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body { background: #fefae0; text-align: center; font-family: 'Arial', sans-serif; padding: 20px; }
        .box { border: 5px solid #bc6c25; background: white; padding: 20px; display: inline-block; border-radius: 15px; max-width: 400px; }
        .slider { display: flex; overflow-x: auto; gap: 10px; margin: 20px 0; }
        .slider img { width: 300px; border-radius: 10px; border: 2px solid #ddd; }
    </style>
</head>
<body>
    <div class="box">
        <h2 style="color: #283618;">✨ YOUR SCRAPBOOK ✨</h2>
        <div class="slider">
            <img src="<?= base_url('uploads/templates/tema-scrapbook/1.jpg'); ?>">
            <img src="<?= base_url('uploads/templates/tema-scrapbook/2.jpg'); ?>">
            <img src="<?= base_url('uploads/results/hasil_scrapbook_3_' . $suffix . '.jpg'); ?>">
            <img src="<?= base_url('uploads/results/hasil_scrapbook_4_' . $suffix . '.jpg'); ?>">
        </div>
        <a href="<?= base_url('otomasi/downloadScrapbook/' . $suffix); ?>" style="background: #283618; color: white; padding: 15px; text-decoration: none; border-radius: 5px; display: block; font-weight: bold;">
            💾 DOWNLOAD FULL ALBUM (ZIP)
        </a>
    </div>
</body>
</html>