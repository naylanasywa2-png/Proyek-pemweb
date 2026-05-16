<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MBook | Pembayaran 💳</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f7fd; font-family: 'Plus Jakarta Sans', sans-serif; }
        .payment-card { border-radius: 25px; border: none; box-shadow: 0 10px 30px rgba(165, 148, 249, 0.15); }
        .purple-text { color: #a594f9; }
        .btn-pay { background: #a594f9; color: white; border-radius: 50px; font-weight: bold; transition: 0.3s; }
        .btn-pay:hover { background: #8e7cf0; transform: scale(1.02); color: white; }
        .form-control:focus { border-color: #a594f9; box-shadow: 0 0 0 0.25rem rgba(165, 148, 249, 0.25); }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card payment-card p-4 bg-white">
                    <h4 class="fw-bold mb-4 purple-text text-center"><i class="fas fa-wallet me-2"></i> Detail Pembayaran</h4>
                    <hr class="text-muted opacity-25">
                    
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Tema Terpilih:</span>
                        <span class="fw-bold text-dark">Vintage Class 🎀</span>
                    </div>
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Total Tagihan:</span>
                        <span class="fw-bold text-danger fs-5">Rp 150.000</span>
                    </div>
                    
                    <div class="alert border-0 p-3 mb-4" style="background: #f3f0ff; color: #6e5edb; border-radius: 15px;">
                        <small class="d-flex gap-2">
                            <i class="fas fa-info-circle mt-1"></i> 
                            <span>Silakan transfer tepat sesuai nominal ke rekening resmi berikut:<br><b>BCA 12345678 a/n MemoriesBook</b></span>
                        </small>
                    </div>

                    <form action="<?= base_url('pembayaran/konfirmasi') ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Unggah Bukti Transfer Resmi</label>
                            <input type="file" name="bukti_transfer" class="form-control rounded-pill" required accept="image/*">
                            <div class="form-text small text-muted px-2" style="font-size: 11px;">Format dokumen berupa foto (.jpg, .png) maksimal 2MB.</div>
                        </div>
                        
                        <button type="submit" class="btn btn-pay w-100 py-2.5 shadow-sm">
                            <i class="fas fa-circle-check me-1"></i> Konfirmasi Pembayaran ✨
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <a href="<?= base_url('katalog') ?>" class="text-muted small text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>