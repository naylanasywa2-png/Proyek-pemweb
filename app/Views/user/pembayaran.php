<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MBook | Pembayaran 💳</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f7fd; font-family: 'Plus Jakarta Sans', sans-serif; }
        .payment-card { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .purple-text { color: #a594f9; }
        .btn-pay { background: #a594f9; color: white; border-radius: 12px; font-weight: bold; transition: 0.3s; }
        .btn-pay:hover { background: #8e7cf0; transform: scale(1.02); }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card payment-card p-4">
                    <h4 class="fw-bold mb-4 purple-text"><i class="fas fa-wallet me-2"></i> Detail Pembayaran</h4>
                    <hr>
                    <div class="mb-3 d-flex justify-content-between">
                        <span>Tema Terpilih:</span>
                        <span class="fw-bold">Vintage Class 🎀</span>
                    </div>
                    <div class="mb-3 d-flex justify-content-between">
                        <span>Total Tagihan:</span>
                        <span class="fw-bold text-danger">Rp 150.000</span>
                    </div>
                    
                    <div class="alert alert-info border-0" style="background: #f3f0ff; color: #6e5edb;">
                        <small><i class="fas fa-info-circle me-1"></i> Silakan transfer ke rekening <b>BCA 12345678 a/n MemoriesBook</b></small>
                    </div>

                    <form action="#" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Unggah Bukti Transfer</label>
                            <input type="file" class="form-control rounded-pill">
                        </div>
                        <button type="submit" class="btn btn-pay w-100 py-2">Konfirmasi Pembayaran ✨</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="<?= base_url('katalog') ?>" class="text-muted small text-decoration-none">Kembali ke Katalog</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>