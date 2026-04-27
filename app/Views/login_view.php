<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yearbook AI | Hi There! ✨</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* ... (CSS tetap sama seperti kode temanmu, tidak saya ubah) ... */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Fredoka', sans-serif; }
        body { background: linear-gradient(135deg, #FFDEE9 0%, #B5FFFC 100%); height: 100vh; display: flex; justify-content: center; align-items: center; overflow: hidden; position: relative; }
        .circles { position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: -1; }
        .circles li { position: absolute; display: block; list-style: none; width: 20px; height: 20px; background: rgba(255, 255, 255, 0.5); animation: animate_bg 25s linear infinite; bottom: -150px; border-radius: 50%; }
        .circles li:nth-child(1){ left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .circles li:nth-child(2){ left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .circles li:nth-child(3){ left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .circles li:nth-child(4){ left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .circles li:nth-child(5){ left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
        @keyframes animate_bg { 0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 0; } 100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; } }
        .login-container { background: #fff; padding: 50px 40px; border-radius: 30px; width: 90%; max-width: 420px; text-align: center; box-shadow: 10px 10px 30px rgba(0,0,0,0.05); border: 5px solid #fff; transition: 0.3s; animation: container_pop 0.5s ease-out; }
        @keyframes container_pop { 0% { transform: scale(0.8); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
        .icon-area { font-size: 4rem; margin-bottom: 20px; animation: emoji_wiggle 2s ease-in-out infinite; }
        @keyframes emoji_wiggle { 0%, 100% { transform: rotate(-5deg); } 50% { transform: rotate(5deg); } }
        h2 { font-weight: 600; font-size: 2.2rem; color: #FF85A1; margin-bottom: 10px; letter-spacing: -1px; }
        p { font-size: 1rem; color: #888; margin-bottom: 35px; }
        .input-group { margin-bottom: 18px; position: relative; }
        input { width: 100%; padding: 18px 25px; background: #FDFDFD; border: none; border-radius: 15px; color: #555; font-size: 1rem; outline: none; transition: 0.3s; box-shadow: inset 2px 2px 5px rgba(0,0,0,0.03), inset -2px -2px 5px rgba(255,255,255,0.8); }
        input::placeholder { color: #BBB; }
        input:focus { box-shadow: 0 0 10px rgba(255, 133, 161, 0.2); transform: translateY(-2px); }
        button { width: 100%; padding: 18px; margin-top: 15px; border: none; border-radius: 15px; background: linear-gradient(135deg, #FFC3A0 0%, #FFAFBD 100%); color: #fff; font-weight: 600; font-size: 1.1rem; cursor: pointer; transition: 0.3s; box-shadow: 0 8px 15px rgba(255, 175, 189, 0.3); }
        button:hover { transform: translateY(-4px) scale(1.02); box-shadow: 0 12px 20px rgba(255, 175, 189, 0.5); }
        button:active { transform: translateY(-1px) scale(0.98); }
        .footer-text { margin-top: 30px; font-size: 0.9rem; color: #AAA; }
        .footer-text a { color: #FF85A1; font-weight: 600; text-decoration: none; transition: 0.2s; }
        .footer-text a:hover { opacity: 0.7; text-decoration: underline; }
        .alert { color: white; background: #ff85a1; padding: 10px; border-radius: 10px; margin-bottom: 15px; font-size: 0.8rem; }
    </style>
</head>
<body>

    <ul class="circles">
        <li></li> <li></li> <li></li> <li></li> <li></li>
    </ul>

    <div class="login-container">
        <div class="icon-area">📸💖</div>
        
        <h2>Hey there!</h2>
        <p>Log in to create your super cute yearbook! ~</p>

        <?php if(session()->getFlashdata('msg')):?>
            <div class="alert"><?= session()->getFlashdata('msg') ?></div>
        <?php endif;?>
        
        <form action="<?= base_url('auth/login_action') ?>" method="post">
            <div class="input-group">
                <input type="email" name="email" placeholder="Your cute email" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Shh! Your secret password" required>
            </div>
            <button type="submit">Let's Go! ✨</button>
        </form>

        <div class="footer-text">
            New here? <a href="<?= base_url('register') ?>">Make an account!</a>
        </div>
    </div>

</body>
</html>