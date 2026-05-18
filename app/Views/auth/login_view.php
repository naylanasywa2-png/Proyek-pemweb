<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Digital Memories Book</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #fff0f5 0%, #fce4ec 50%, #f8bbd0 100%);
            padding: 20px;
        }
        .card {
            background: white; border-radius: 28px; padding: 36px 40px;
            width: 100%; max-width: 420px;
            box-shadow: 0 20px 60px rgba(255, 77, 125, 0.15);
        }
        .logo { text-align: center; margin-bottom: 24px; }
        .logo .icon { font-size: 2.5rem; margin-bottom: 6px; }
        .logo h1 { color: #ff4d7d; font-size: 1.5rem; font-weight: 800; margin-bottom: 2px; }
        .logo p { color: #aaa; font-size: 0.85rem; }

        /* Alerts */
        .alert { padding: 10px 14px; border-radius: 10px; margin-bottom: 16px; font-size: 0.85rem; font-weight: 500; }
        .alert-error { background: #fff0f3; color: #cc3366; border: 1px solid #ffb3cc; }
        .alert-success { background: #f0fff4; color: #16a34a; border: 1px solid #86efac; }
        .alert-info { background: #f0f4ff; color: #3355cc; border: 1px solid #b3c6ff; }

        /* Form */
        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block; margin-bottom: 6px;
            color: #d63384; font-weight: 700; font-size: 0.75rem;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .input-wrap { position: relative; }
        .input-wrap .icon-left {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            font-size: 1rem; pointer-events: none;
        }
        input[type=email], input[type=password] {
            width: 100%; padding: 10px 14px 10px 40px;
            border: 2px solid #ffdae3; border-radius: 12px;
            font-size: 0.9rem; font-family: inherit; outline: none; color: #333;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus { border-color: #ff85a2; box-shadow: 0 0 0 3px rgba(255,133,162,0.2); }

        /* Tombol Login */
        .btn-login {
            width: 100%; padding: 12px;
            background: linear-gradient(135deg, #ff85a2, #ff4d7d);
            color: white; border: none; border-radius: 12px;
            font-size: 0.95rem; font-weight: 700; font-family: inherit;
            cursor: pointer; margin-top: 4px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(255,77,125,0.35); }
        .btn-login:active { transform: translateY(0); }

        /* Divider */
        .divider { text-align: center; margin: 18px 0; color: #ddd; font-size: 0.8rem; position: relative; }
        .divider::before, .divider::after {
            content: ''; position: absolute; top: 50%; width: 42%; height: 1px; background: #f0e0e5;
        }
        .divider::before { left: 0; }
        .divider::after  { right: 0; }

        /* Demo accounts */
        .demo-box {
            background: #fff8fa; border: 1.5px solid #ffdae3; border-radius: 12px;
            padding: 12px 14px; margin-bottom: 18px;
        }
        .demo-box p { font-size: 0.75rem; color: #ff4d7d; font-weight: 700; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .demo-account {
            display: flex; align-items: center; justify-content: space-between;
            padding: 6px 8px; border-radius: 8px; cursor: pointer;
            transition: background 0.15s; margin-bottom: 2px;
        }
        .demo-account:last-child { margin-bottom: 0; }
        .demo-account:hover { background: #fff0f3; }
        .demo-account .role { font-size: 0.7rem; font-weight: 700; color: #ff4d7d; background: #ffe4e9; padding: 2px 6px; border-radius: 10px; }
        .demo-account .cred { font-size: 0.75rem; color: #888; }

        .footer-link { text-align: center; margin-top: 20px; font-size: 0.85rem; color: #aaa; }
        .footer-link a { color: #ff4d7d; font-weight: 700; text-decoration: none; }
        .footer-link a:hover { text-decoration: underline; }

    </style>
</head>
<body>
<div class="card">
    <div class="logo">
        <div class="icon">📸</div>
        <h1>Digital Memories</h1>
        <p>Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <?php if ($e = session()->getFlashdata('error')): ?>
        <div class="alert alert-error">❌ <?= esc($e) ?></div>
    <?php endif; ?>
    <?php if ($s = session()->getFlashdata('sukses')): ?>
        <div class="alert alert-success">✅ <?= esc($s) ?></div>
    <?php endif; ?>
    <?php if ($i = session()->getFlashdata('info')): ?>
        <div class="alert alert-info">ℹ️ <?= esc($i) ?></div>
    <?php endif; ?>

    <!-- Demo Accounts Helper -->
    <div class="demo-box">
        <p>🔑 Akun Demo (klik untuk isi otomatis)</p>
        <div class="demo-account" onclick="fillLogin('admin@memoriesbook.com','admin123')">
            <span><span class="role">Admin</span></span>
            <span class="cred">admin@memoriesbook.com / admin123</span>
        </div>
        <div class="demo-account" onclick="fillLogin('user@memoriesbook.com','user123')">
            <span><span class="role">User</span></span>
            <span class="cred">user@memoriesbook.com / user123</span>
        </div>
    </div>

    <form action="<?= base_url('login') ?>" method="post" id="formLogin">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-wrap">
                <span class="icon-left">📧</span>
                <input type="email" name="email" id="email"
                       placeholder="email@contoh.com"
                       value="<?= esc(session()->getFlashdata('old_email') ?? '') ?>"
                       required autocomplete="email">
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrap">
                <span class="icon-left">🔒</span>
                <input type="password" name="password" id="password"
                       placeholder="Masukkan password"
                       required autocomplete="current-password">
            </div>
        </div>

        <button type="submit" class="btn-login">Masuk →</button>
    </form>

    <div class="footer-link">
        Belum punya akun? <a href="<?= base_url('register') ?>">Daftar sekarang</a>
    </div>
</div>

<script>
function fillLogin(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
    document.getElementById('formLogin').submit();
}
</script>
</body>
</html>
