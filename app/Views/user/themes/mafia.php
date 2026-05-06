<!DOCTYPE html>
<html lang="id">
<head>
    <title>The Mafia World | @AULIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@300&display=swap" rel="stylesheet">
    <style>
        body { background: #000; color: #fff; font-family: 'Montserrat', sans-serif; margin: 0; text-align: center; }
        .container { padding: 50px; background: linear-gradient(to bottom, #1a1a1a, #000); min-height: 100vh; }
        h1 { font-family: 'Playfair Display', serif; color: #d4af37; font-size: 45px; letter-spacing: 5px; }
        .noir-frame { 
            border: 5px solid #d4af37; padding: 20px; display: inline-block; 
            box-shadow: 0 0 30px rgba(212, 175, 55, 0.2); background: #0a0a0a;
        }
        .noir-frame img { width: 400px; filter: grayscale(100%) contrast(1.2); }
        .btn-join { 
            display: inline-block; margin-top: 30px; padding: 15px 40px; 
            border: 2px solid #d4af37; color: #d4af37; text-decoration: none; font-weight: bold;
            transition: 0.3s;
        }
        .btn-join:hover { background: #d4af37; color: #000; }
    </style>
</head>
<body>
    <div class="container">
        <h1>THE MAFIA WORLD</h1>
        <p>Elegansi dalam bayang-bayang.</p>
        <div class="noir-frame">
            <img src="<?= base_url('uploads/mafia_sample.jpg') ?>" alt="Mafia Concept">
            <p style="color: #d4af37; margin-top: 15px;">CLASSY • MYSTERIOUS • POWER</p>
        </div><br>
        <a href="<?= base_url('order/create') ?>" class="btn-join">AMBIL KONTRAK SEKARANG</a>
    </div>
</body>
</html>