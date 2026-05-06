<!DOCTYPE html>
<html lang="id">
<head>
    <title>Urban Streetwear | @AULIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Oswald:wght@700&display=swap" rel="stylesheet">
    <style>
        body { background: #121212; color: #fff; font-family: 'Oswald', sans-serif; margin: 0; overflow-x: hidden; }
        .wall { 
            padding: 50px; background: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            min-height: 100vh;
        }
        h1 { font-family: 'Bangers', cursive; color: #00ffcc; font-size: 60px; transform: rotate(-2deg); }
        .card-urban { 
            background: #222; border-left: 10px solid #00ffcc; padding: 20px; 
            max-width: 500px; margin: auto; text-align: left;
        }
        .tag { background: #ff0055; color: white; padding: 5px 10px; font-size: 12px; }
        .btn-order { 
            background: #00ffcc; color: #000; padding: 15px; display: block; 
            text-align: center; margin-top: 20px; text-decoration: none; font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="wall">
        <h1>URBAN VIBES 🛹</h1>
        <div class="card-urban">
            <span class="tag">NEW STREET CULTURE</span>
            <p>Gaya santai, grafiti, dan vibes anak muda kota yang keren.</p>
            <img src="<?= base_url('uploads/urban_sample.jpg') ?>" width="100%">
            <a href="<?= base_url('order/create') ?>" class="btn-order">CHECKOUT SKRG!</a>
        </div>
    </div>
</body>
</html>