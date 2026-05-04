<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Digital Memories Book ✨</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Desain Estetik Milik Vanti */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Fredoka', sans-serif; }
        body { background: linear-gradient(135deg, #FFDEE9 0%, #B5FFFC 100%); height: 100vh; display: flex; justify-content: center; align-items: center; overflow: hidden; position: relative; }
        .circles { position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: -1; }
        .circles li { position: absolute; display: block; list-style: none; width: 20px; height: 20px; background: rgba(255, 255, 255, 0.5); animation: animate_bg 25s linear infinite; bottom: -150px; border-radius: 50%; }
        /* ... animasi lingkaran tetap dipertahankan ... */
        @keyframes animate_bg { 0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 0; } 100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; } }
        .login-container { background: #fff; padding: 50px 40px; border-radius: 30px; width: 90%; max-width: 420px; text-align: center; box-shadow: 10px 10px 30px rgba(0,0,0,0.05); border: 5px solid #fff; animation: container_pop 0.5s ease-out; }
        h2 { font-weight: 600; font-size: 2.2rem; color: #FF85A1; margin-bottom: 10px; }
        button { width: 100%; padding: 18px; margin-top: 15px; border: none; border-radius: 15px; background: linear-gradient(135deg, #FFC3A0 0%, #FFAFBD 100%); color: #fff; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>
    <ul class="circles">
        <li></li><li></li><li></li><li></li><li></li>
    </ul>

    <div class="login-container">
        <div class="icon-area">📸💖</div>
        <h2>MemoriesBook</h2>
        <p>Silahkan masuk untuk memulai sesi ~</p>

        <form action="<?= base_url('auth/login_action') ?>" method="post">
            <input type="email" name="email" placeholder="Email kamu" required style="width:100%; padding:15px; margin-bottom:10px; border-radius:10px; border:1px solid #ddd;">
            <input type="password" name="password" placeholder="Password rahasia" required style="width:100%; padding:15px; margin-bottom:10px; border-radius:10px; border:1px solid #ddd;">
            <button type="submit">Let's Go! ✨</button>
        </form>
        <p style="margin-top:20px;">Belum punya akun? <a href="/register">Daftar di sini</a></p>
    </div>
</body>
</html>