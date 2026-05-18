<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yearbook Digital | Elevate Your Memories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-pink: #ff8fa3;
            --soft-bg: #fff5f7;
            --dark-text: #2d3436;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fcfcfc;
            color: var(--dark-text);
        }

        /* --- NAVBAR --- */
        .navbar {
            background: white;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 800;
            color: var(--primary-pink) !important;
        }

        /* --- FLIPBOOK CONTAINER --- */
        #flipbook-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 0;
            background: radial-gradient(circle, #fff 0%, #f3f3f3 100%);
        }

        #flipbook {
            width: 900px;
            height: 550px;
        }

        .page {
            background-color: white;
            box-shadow: inset -5px 0 10px rgba(0,0,0,0.02);
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* --- HERO STYLE --- */
        .hero-title {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .hero-title span { color: var(--primary-pink); }

        .btn-main {
            background-color: var(--primary-pink);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
            border: none;
        }

        .btn-main:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 143, 163, 0.4);
            color: white;
        }

        /* --- FEATURE CARD --- */
        .feature-card {
            border-radius: 20px;
            padding: 25px;
            background: #fff;
            border: 1px solid #f0f0f0;
            transition: 0.3s;
            text-align: center;
            height: 100%;
        }

        .feature-card:hover {
            border-color: var(--primary-pink);
            transform: scale(1.02);
        }

        .controls {
            text-align: center;
            margin-bottom: 50px;
        }

        .btn-control {
            border: 1px solid #ddd;
            background: white;
            padding: 8px 20px;
            border-radius: 50px;
            margin: 0 5px;
            transition: 0.3s;
        }

        .btn-control:hover {
            background: var(--primary-pink);
            color: white;
            border-color: var(--primary-pink);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">MemoriesBook📖</a>
            <div class="ms-auto">
                <a href="<?= base_url('login') ?>" class="text-decoration-none text-dark fw-bold me-4">Masuk</a>
                <a href="<?= base_url('register') ?>" class="btn-main py-2">Daftar</a>
            </div>
        </div>
    </nav>

    <div id="flipbook-wrapper">
        <div id="flipbook">
            <div class="page">
                <h1 class="hero-title">Abadikan Momen <span>Sekolahmu</span> Secara Digital.</h1>
                <p class="text-secondary mb-4">Platform yearbook interaktif dengan efek flip buku nyata yang membuat kenanganmu lebih hidup.</p>
                <img src="https://img.freepik.com/free-vector/digital-lifestyle-concept-illustration_114360-7307.jpg" class="img-fluid rounded-4 shadow-sm mb-4">
            </div>

            <div class="page">
                <div class="text-center mb-4">
                    <h4 class="fw-bold">Kenapa Pilih Kami?</h4>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="feature-card">
                            <div class="fs-2">✨</div>
                            <h6 class="fw-bold">Efek Flip Nyata</h6>
                            <p class="small text-secondary m-0">Sensasi membaca buku fisik yang halus dan responsif.</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="feature-card">
                            <div class="fs-2">🎨</div>
                            <h6 class="fw-bold">Desain Eksklusif</h6>
                            <p class="small text-secondary m-0">Akses ribuan template yearbook yang estetik.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page text-center">
                <div class="mb-4">
                    <div class="fs-1">📱</div>
                    <h4 class="fw-bold">Akses Dimana Saja</h4>
                    <p class="text-secondary">Buka kenanganmu lewat HP atau Laptop kapan saja.</p>
                </div>
                
                <hr class="my-4" style="opacity: 0.1;">
                
                <h5 class="fw-bold mb-3">Siap Membuat Kenangan?</h5>
                <a href="<?= base_url('katalog') ?>" class="btn-main">Mulai Buat Yearbook ✨</a>
            </div>
        </div>
    </div>

    <div class="controls">
        <button id="prev" class="btn-control">← Prev</button>
        <button id="next" class="btn-control">Next →</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.rawgit.com/blasten/turn.js/master/turn.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#flipbook").turn({
                width: 900,
                height: 550,
                autoCenter: true,
                gradients: true,
                acceleration: true
            });

            $("#prev").click(function() {
                $("#flipbook").turn("previous");
            });

            $("#next").click(function() {
                $("#flipbook").turn("next");
            });
        });
    </script>
</body>
</html>