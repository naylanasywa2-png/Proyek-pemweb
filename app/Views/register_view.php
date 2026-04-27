<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yearbook AI | Join Us! ✨</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* CSS-nya sama persis kayak login tadi biar konsisten */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Fredoka', sans-serif; }
        body { background: linear-gradient(135deg, #B5FFFC 0%, #FFDEE9 100%); height: 100vh; display: flex; justify-content: center; align-items: center; overflow: hidden; }
        .login-container { background: #fff; padding: 50px 40px; border-radius: 30px; width: 90%; max-width: 420px; text-align: center; box-shadow: 10px 10px 30px rgba(0,0,0,0.05); border: 5px solid #fff; }
        .icon-area { font-size: 4rem; margin-bottom: 20px; }
        h2 { font-weight: 600; font-size: 2.2rem; color: #FF85A1; margin-bottom: 10px; }
        p { font-size: 1rem; color: #888; margin-bottom: 35px; }
        input { width: 100%; padding: 18px 25px; background: #FDFDFD; border: none; border-radius: 15px; color: #555; font-size: 1rem; outline: none; margin-bottom: 15px; box-shadow: inset 2px 2px 5px rgba(0,0,0,0.03); }
        button { width: 100%; padding: 18px; border: none; border-radius: 15px; background: linear-gradient(135deg, #FFAFBD 0%, #FFC3A0 100%); color: #fff; font-weight: 600; font-size: 1.1rem; cursor: pointer; transition: 0.3s; }
        button:hover { transform: translateY(-4px); box-shadow: 0 8px 15px rgba(255, 175, 189, 0.3); }
        .footer-text { margin-top: 30px; font-size: 0.9rem; color: #AAA; }
        .footer-text a { color: #FF85A1; font-weight: 600; text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="icon-area">📝💖</div>
        <h2>Join Us!</h2>
        <p>Create an account to start your journey ~</p>

        <?php if(session()->getFlashdata('msg')): ?>
        <div style="color: white; background-color: #ff4d4d; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
        <?= session()->getFlashdata('msg') ?>
        </div>
        <?php endif; ?>
        
        <form action="<?= base_url('auth/register_action') ?>" method="post">
            <input type="email" name="email" placeholder="Your best email" required>
            <input type="password" name="password" placeholder="Create a secret password" required>
            <button type="submit">Sign Me Up! ✨</button>
        </form>

        <div class="footer-text">
            Already have an account? <a href="<?= base_url('auth') ?>">Log in here!</a>
        </div>
    </div>
</body>
</html>