<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scrapbook Gallery | @author AULIA</title>
    <style>
        body { background-color: #f4ece1; background-image: radial-gradient(#dcc5a1 1px, transparent 1px); background-size: 20px 20px; font-family: 'Segoe Script', cursive; color: #5d4037; }
        .scrapbook-page { max-width: 800px; margin: 50px auto; background: #fffdf9; padding: 40px; box-shadow: 5px 5px 15px rgba(0,0,0,0.1); border-left: 20px solid #8d6e63; position: relative; }
        h1 { border-bottom: 2px dashed #8d6e63; display: inline-block; margin-bottom: 30px; }
        .photo-frame { background: white; padding: 15px 15px 40px 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.2); transform: rotate(-2deg); margin: 20px; display: inline-block; transition: 0.5s; }
        .photo-frame:nth-child(even) { transform: rotate(3deg); }
        .photo-frame:hover { transform: rotate(0deg) scale(1.05); z-index: 10; }
        .design-img { width: 300px; height: auto; border: 1px solid #ddd; }
        .btn-back { position: absolute; top: 10px; right: 20px; color: #8d6e63; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="scrapbook-page">
        <a href="<?= base_url('katalog'); ?>" class="btn-back">✖ Close</a>
        <h1>Our Little Stories</h1>
        <p>Memori-memori yang kita kumpulkan bersama...</p>

        <div class="photo-frame">
            <img src="<?= base_url('uploads/templates/tema-scrapbook/hal1.jpg'); ?>" class="design-img">
            <p>Moment #1</p>
        </div>

        <div class="photo-frame">
            <img src="<?= base_url('uploads/templates/tema-scrapbook/hal2.jpg'); ?>" class="design-img">
            <p>Moment #2</p>
        </div>
    </div>
</body>
</html>