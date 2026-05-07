<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yearbook Themes | @author AULIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #fff5f8;
            font-family: 'Quicksand', sans-serif;
            margin: 0;
            padding: 40px 20px;
            color: #5d5d5d;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
        }

        .header h1 {
            color: #ff69b4;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            width: 100%;
            max-width: 1100px;
        }

        .card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .card {
            background: white;
            border-radius: 30px;
            padding: 30px;
            text-align: center;
            border: 4px dashed #ffb6c1;
            transition: all 0.3s ease;
            min-height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .card:hover {
            transform: translateY(-10px) rotate(2deg);
            background-color: #fff0f5;
            border-style: solid;
            box-shadow: 0 15px 30px rgba(255, 182, 193, 0.4);
        }

        .icon {
            font-size: 55px;
            margin-bottom: 15px;
        }

        .card h3 {
            color: #ff69b4;
            margin: 10px 0;
            font-size: 1.4rem;
        }

        .card p {
            font-size: 0.95rem;
            line-height: 1.5;
            color: #777;
        }

        /* Perbaikan Style Tombol Back */
        .btn-back {
            display: inline-block;
            margin-top: 50px;
            text-decoration: none;
            color: #ff69b4;
            font-weight: bold;
            padding: 12px 30px;
            border: 2px solid #ff69b4;
            border-radius: 50px;
            transition: 0.3s;
            background: white;
            cursor: pointer;
        }

        .btn-back:hover {
            background: #ff69b4;
            color: white;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="header">
    <h1>🎨 Yearbook Themes 🎨</h1>
    <p>Pilih konsep yang paling cocok buat karakter kelasmu!</p>
</div>

<div class="grid-container">
    <a href="<?= site_url('katalog/game'); ?>" class="card-link">
        <div class="card">
            <span class="icon">🎮</span>
            <h3>Level Up: Game</h3>
            <p>Konsep retro 8-bit atau esport modern yang penuh energi!</p>
        </div>
    </a>

    <a href="<?= site_url('katalog/scrapbook'); ?>" class="card-link">
        <div class="card">
            <span class="icon">✂️</span>
            <h3>DIY Scrapbook</h3>
            <p>Sentuhan potongan kertas, stiker, dan memori yang estetik.</p>
        </div>
    </a>

    <a href="<?= site_url('katalog/vintage'); ?>" class="card-link">
        <div class="card">
            <span class="icon">🎞️</span>
            <h3>Classic Vintage</h3>
            <p>Nuansa nostalgia tahun 90-an dengan filter kamera film.</p>
        </div>
    </a>

    <a href="<?= site_url('katalog/mafia'); ?>" class="card-link">
        <div class="card">
            <span class="icon">🎩</span>
            <h3>The Mafia World</h3>
            <p>Tampil elegan dan misterius dengan jas hitam dan konsep noir.</p>
        </div>
    </a>

    <a href="<?= site_url('katalog/streetwear'); ?>" class="card-link">
        <div class="card">
            <span class="icon"> Skateboarding 🛹</span>
            <h3>Urban Streetwear</h3>
            <p>Gaya santai, grafiti, dan vibes anak muda kota yang keren.</p>
        </div>
    </a>

    <a href="<?= site_url('katalog/formal'); ?>" class="card-link">
        <div class="card">
            <span class="icon">🏛️</span>
            <h3>Grand Academy</h3>
            <p>Konsep sekolah elit dengan seragam rapi dan latar megah.</p>
        </div>
    </a>
</div>

<?php if (session()->get('logged_in')): ?>
    <a href="<?= site_url('user/home'); ?>" class="btn-back">⬅ Back to Dashboard</a>
<?php else: ?>
    <a href="<?= site_url('/'); ?>" class="btn-back">⬅ Back to Home</a>
<?php endif; ?>

</body>
</html>