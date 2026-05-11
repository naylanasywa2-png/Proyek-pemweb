<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | MBook ✨</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #fdf2f8 0%, #f3f0ff 100%);
            height: 100vh;
            display: flex; align-items: center; justify-content: center; margin: 0;
        }
        .register-card {
            background: white;
            padding: 40px;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(165, 148, 249, 0.1);
            width: 100%; max-width: 400px; text-align: center;
        }
        .brand-name { font-weight: 800; color: #ff8fa3; font-size: 24px; margin-bottom: 25px; }
        .form-control-custom {
            background: #f8f7fd; border: 2px solid transparent; border-radius: 15px;
            padding: 12px 20px; margin-bottom: 15px; width: 100%; transition: 0.3s;
        }
        .form-control-custom:focus { border-color: #ffabe1; background: white; outline: none; }
        .btn-register {
            background: linear-gradient(135deg, #ffabe1 0%, #ff8fa3 100%);
            color: white; border: none; width: 100%; padding: 12px;
            border-radius: 50px; font-weight: 700; cursor: pointer; transition: 0.3s;
        }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(255, 171, 225, 0.4); }
        .footer-link { margin-top: 20px; font-size: 13px; color: #777; }
        .footer-link a { color: #ff8fa3; text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="brand-name">Buat Akun Baru ✨</div>
        
        <form action="<?= base_url('auth/register_action') ?>" method="POST">
            <input type="text" name="email" class="form-control-custom" placeholder="Username / Email" required>
            <input type="password" name="password" class="form-control-custom" placeholder="Password Baru" required>
            <button type="submit" class="btn-register">Daftar Sekarang ✨</button>
        </form>

        <div class="footer-link">
            Sudah punya akun? <a href="<?= base_url('login') ?>">Masuk di sini</a>
        </div>
    </div>
</body>
</html>