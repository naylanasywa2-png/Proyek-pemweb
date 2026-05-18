<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Digital Memories Book</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #fff0f5 0%, #fce4ec 50%, #f8bbd0 100%);
            padding: 20px;
        }
        .card {
            background: white; border-radius: 28px; padding: 32px 36px;
            width: 100%; max-width: 440px;
            box-shadow: 0 20px 60px rgba(255, 77, 125, 0.15);
        }
        .logo { text-align: center; margin-bottom: 22px; }
        .logo .icon { font-size: 2.5rem; margin-bottom: 4px; }
        .logo h1 { color: #ff4d7d; font-size: 1.4rem; font-weight: 800; margin-bottom: 2px; }
        .logo p { color: #aaa; font-size: 0.85rem; }

        .alert { padding: 10px 14px; border-radius: 10px; margin-bottom: 16px; font-size: 0.85rem; font-weight: 500; }
        .alert-error   { background: #fff0f3; color: #cc3366; border: 1px solid #ffb3cc; }
        .alert-success { background: #f0fff4; color: #16a34a; border: 1px solid #86efac; }

        .form-group { margin-bottom: 14px; }
        .form-group label {
            display: block; margin-bottom: 5px;
            color: #d63384; font-weight: 700; font-size: 0.75rem;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .input-wrap { position: relative; }
        .input-wrap .icon-left {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            font-size: 1rem; pointer-events: none;
        }
        input[type=text], input[type=email], input[type=password] {
            width: 100%; padding: 10px 14px 10px 40px;
            border: 2px solid #ffdae3; border-radius: 12px;
            font-size: 0.9rem; font-family: inherit; outline: none; color: #333;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input.error { border-color: #ff4d7d !important; }
        input:focus { border-color: #ff85a2; box-shadow: 0 0 0 3px rgba(255,133,162,0.2); }
        .field-error { color: #ff4d7d; font-size: 0.76rem; margin-top: 4px; }

        .btn-register {
            width: 100%; padding: 12px; margin-top: 6px;
            background: linear-gradient(135deg, #ff85a2, #ff4d7d);
            color: white; border: none; border-radius: 12px;
            font-size: 0.95rem; font-weight: 700; font-family: inherit;
            cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(255,77,125,0.35); }

        .footer-link { text-align: center; margin-top: 18px; font-size: 0.85rem; color: #aaa; }
        .footer-link a { color: #ff4d7d; font-weight: 700; text-decoration: none; }
        .footer-link a:hover { text-decoration: underline; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        @media (max-width: 480px) { .form-row { grid-template-columns: 1fr; } .card { padding: 24px 20px; } }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">
        <div class="icon">📸</div>
        <h1>Buat Akun Baru</h1>
        <p>Bergabung dengan Digital Memories</p>
    </div>

    <?php if ($e = session()->getFlashdata('error')): ?>
        <div class="alert alert-error">❌ <?= esc($e) ?></div>
    <?php endif; ?>
    <?php if ($s = session()->getFlashdata('sukses')): ?>
        <div class="alert alert-success">✅ <?= esc($s) ?></div>
    <?php endif; ?>

    <?php
    $errors   = session()->getFlashdata('errors')   ?? [];
    $oldInput = session()->getFlashdata('old_input') ?? [];
    ?>

    <form action="<?= base_url('register') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <div class="input-wrap">
                <span class="icon-left">👤</span>
                <input type="text" name="nama" id="nama"
                       placeholder="Nama lengkap kamu"
                       value="<?= esc($oldInput['nama'] ?? '') ?>"
                       class="<?= isset($errors['nama']) ? 'error' : '' ?>">
            </div>
            <?php if (isset($errors['nama'])): ?>
                <div class="field-error">⚠️ <?= esc($errors['nama']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-wrap">
                <span class="icon-left">📧</span>
                <input type="email" name="email" id="email"
                       placeholder="email@contoh.com"
                       value="<?= esc($oldInput['email'] ?? '') ?>"
                       class="<?= isset($errors['email']) ? 'error' : '' ?>">
            </div>
            <?php if (isset($errors['email'])): ?>
                <div class="field-error">⚠️ <?= esc($errors['email']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <span class="icon-left">🔒</span>
                    <input type="password" name="password" id="password"
                           placeholder="Min. 6 karakter"
                           class="<?= isset($errors['password']) ? 'error' : '' ?>">
                </div>
                <?php if (isset($errors['password'])): ?>
                    <div class="field-error">⚠️ <?= esc($errors['password']) ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="konfirmasi_password">Konfirmasi</label>
                <div class="input-wrap">
                    <span class="icon-left">🔑</span>
                    <input type="password" name="konfirmasi_password" id="konfirmasi_password"
                           placeholder="Ulangi password"
                           class="<?= isset($errors['konfirmasi_password']) ? 'error' : '' ?>">
                </div>
                <?php if (isset($errors['konfirmasi_password'])): ?>
                    <div class="field-error">⚠️ <?= esc($errors['konfirmasi_password']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <button type="submit" class="btn-register">Buat Akun ✨</button>
    </form>

    <div class="footer-link">
        Sudah punya akun? <a href="<?= base_url('login') ?>">Masuk di sini</a>
    </div>
</div>
</body>
</html>
