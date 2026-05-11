<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6 text-center">
        <div class="card shadow">
            <div class="card-body">
                <h4>Selesaikan Pembayaran</h4>
                <p class="text-muted">Silahkan scan QRIS di bawah ini atau transfer manual</p>
                
                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" width="200" class="mb-3">
                
                <div class="alert alert-info">
                    <strong>Transfer Bank:</strong><br>
                    Bank BCA: 123456789 (A.N. Digital Memories)
                </div>

                <div class="form-group text-left mt-4">
                    <label>Upload Bukti Transfer</label>
                    <input type="file" class="form-control">
                </div>
                <button class="btn btn-success btn-block">Konfirmasi Pembayaran</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>