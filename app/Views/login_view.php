<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Digital Memories Book ✨</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Desain Estetik Aulia & Vanti */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Fredoka', sans-serif; }
        
        body { 
            background: linear-gradient(135deg, #FFDEE9 0%, #B5FFFC 100%); 
            height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            overflow: hidden; 
            position: relative; 
        }

        /* Animasi Lingkaran Latar Belakang */
        .circles { position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: 1; }
        .circles li { position: absolute; display: block; list-style: none; width: 20px; height: 20px; background: rgba(255, 255, 255, 0.5); animation: animate 25s linear infinite; bottom: -150px; border-radius: 50%; }
        .circles li:nth-child(1){ left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .circles li:nth-child(2){ left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .circles li:nth-child(3){ left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .circles li:nth-child(4){ left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .circles li:nth-child(5){ left: 65%; width: 20px; height: 20px; animation-delay: 0s; }

        @keyframes animate {
            0%{ transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 0; }
            100%{ transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
        }
        
        .login-container { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(10px);
            padding: 50px 40px; 
            border-radius: 30px; 
            width: 90%; 
            max-width: 420px; 
            text-align: center; 
            box-shadow: 10px 10px 30px rgba(0,0,0,0.05); 
            border: 5px solid #fff; 
            z-index: 10; 
            animation: container_pop 0.6s ease-out;
        }

        @keyframes container_pop {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        h2 { font-weight: 600; font-size: 2.2rem; color: #FF85A1; margin-bottom: 10px; }
        .icon-area { font-size: 3rem; margin-bottom: 10px; }
        
        input { width: 100%; padding: 15px; margin-bottom: 15px; border-radius: 12px; border: 2px solid #f0f0f0; outline: none; transition: 0.3s; font-size: 1rem; }
        input:focus { border-color: #FFAFBD; background-color: #fffafb; }
        
        button { width: 100%; padding: 18px; border: none; border-radius: 15px; background: linear-gradient(135deg, #FFC3A0 0%, #FFAFBD 100%); color: #fff; font-weight: 600; font-size: 1.1rem; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        button:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(255, 175, 189, 0.4); opacity: 0.9; }
        
        .error-msg { 
            background-color: #ffe5e5; 
            color: #ff4d4d; 
            padding: 10px; 
            border-radius: 10px; 
            margin-bottom: 20px; 
            font-size: 0.9rem; 
            border: 1px solid #ffcccc;
        }
    </style>
</head>
<body>
    <ul class="circles">
        <li></li><li></li><li></li><li></li><li></li>
    </ul>

    <div class="login-container">
        <div class="icon-area">📸💖</div>
        <h2>MemoriesBook</h2>
        <p style="margin-bottom: 20px; color: #888;">Silahkan masuk untuk memulai sesi ~</p>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="error-msg">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('auth/login_action') ?>" method="post">
            <input type="text" name="username" placeholder="Username kamu" required>
            <input type="password" name="password" placeholder="Password rahasia" required>
            <button type="submit">Let's Go! ✨</button>
        </form>
        
        <p style="margin-top:20px; font-size: 0.9rem; color: #888;">
            Belum punya akun? <a href="<?= site_url('register') ?>" style="color: #FF85A1; text-decoration: none; font-weight: 600;">Daftar di sini</a>
        </p>
    </div>
</body>
</html>