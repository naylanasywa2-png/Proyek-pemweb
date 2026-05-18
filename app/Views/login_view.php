<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | MBook ✨</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #fdf2f8 0%, #f3f0ff 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background: white;
            padding: 45px;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(165, 148, 249, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .icon-header {
            font-size: 50px;
            margin-bottom: 20px;
            display: block;
        }
        .brand-name {
            font-weight: 800;
            color: #ff8fa3;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .subtitle {
            color: #888;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .form-control-custom {
            background: #f8f7fd;
            border: 2px solid transparent;
            border-radius: 15px;
            padding: 12px 20px;
            margin-bottom: 15px;
            width: 100%;
            transition: 0.3s;
        }
        .form-control-custom:focus {
            border-color: #ffabe1;
            background: white;
            outline: none;
        }
        .btn-letsgo {
            background: linear-gradient(135deg, #ffabe1 0%, #ff8fa3 100%);
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 50px;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(255, 171, 225, 0.3);
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-letsgo:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(255, 171, 225, 0.4);
        }
        .footer-link {
            margin-top: 25px;
            font-size: 13px;
            color: #777;
        }
        .footer-link a {
            color: #ff8fa3;
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <span class="icon-header">📸❤️</span>
        <div class="brand-name">MemoriesBook</div>
        <p class="subtitle">Silakan masuk untuk memulai sesi yearbook-mu ✨</p>
        
        <form action="<?= base_url('auth/login_action') ?>" method="POST">
            <input type="text" name="username" class="form-control-custom" placeholder="Username" required>
            <input type="password" name="password" class="form-control-custom" placeholder="Password" required>
            <button type="submit" class="btn-letsgo">Let's Go! ✨</button>
        </form>

        <div class="footer-link">
            Belum punya akun? <a href="<?= base_url('register') ?>">Daftar di sini</a>
        </div>
    </div>
</body>
</html>