<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home User 🌸 | @author AULIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Quicksand', sans-serif; background: #fef1f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .welcome-card { background: white; padding: 40px; border-radius: 30px; border: 3px solid #ffccd5; text-align: center; box-shadow: 0 10px 25px rgba(255, 182, 193, 0.3); }
        h1 { color: #ff758f; }
        .btn-menu { display: inline-block; padding: 12px 20px; background: #ff8fa3; color: white; text-decoration: none; border-radius: 50px; margin: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="welcome-card">
        <h1>Selamat Datang, <?= $username ?>! ✨</h1>
        <p>Mau lihat koleksi desain atau cek pesanan kamu?</p>
        <a href="<?= base_url('katalog') ?>" class="btn-menu">Lihat Katalog 🛍️</a>
        <a href="<?= base_url('user/history') ?>" class="btn-menu">Riwayat Pesanan 🎀</a>
        <br>
        <a href="<?= base_url('logout') ?>" style="color: #888; font-size: 12px;">Logout</a>
    </div>
</body>
</html>