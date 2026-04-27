<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary">Upload Foto Kamu</div>
            <div class="card-body">
                <div class="form-group">
                    <label>Pilih Foto (Maks 15 Foto)</label>
                    <input type="file" id="uploadFoto" class="form-control" multiple onchange="previewImage(event)">
                    <small class="text-danger">*Pastikan ukuran file di atas 500KB agar tidak pecah.</small>
                </div>
                <hr>
                <h5>Pilih Metode:</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="metode" value="softfile" checked>
                    <label class="form-check-label">Hanya Softfile (Kirim via Gmail)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="metode" value="cetak">
                    <label class="form-check-label">Cetak Fisik (Hardfile)</label>
                </div>
                <button class="btn btn-success btn-block mt-4">Lanjut ke Pembayaran</button>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-dark">Preview Kasar</div>
            <div class="card-body text-center">
                <div id="previewContainer" style="position: relative; width: 100%; height: 400px; border: 1px dashed #ccc; overflow: hidden;">
                    <p class="mt-5 text-muted">Pratinjau akan muncul di sini setelah foto diunggah</p>
                    </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const container = document.getElementById('previewContainer');
    container.innerHTML = ""; // Bersihkan preview lama
    
    const file = event.target.files[0];
    if (file.size < 500000) {
        alert("Peringatan: Ukuran file foto Anda kecil (di bawah 500KB). Hasil cetak mungkin akan buram.");
    }

    const reader = new FileReader();
    reader.onload = function() {
        const img = document.createElement('img');
        img.src = reader.result;
        img.style.width = "100%";
        container.appendChild(img);
    }
    reader.readAsDataURL(file);
}
</script>
<?= $this->endSection() ?>