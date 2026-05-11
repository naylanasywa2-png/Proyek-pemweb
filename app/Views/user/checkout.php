<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">Detail Pengiriman</div>
            <div class="card-body">
                <div class="form-group">
                    <label>Nama Penerima</label>
                    <input type="text" class="form-control" placeholder="Nama Lengkap">
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea class="form-control" rows="3" placeholder="Nama jalan, Nomor rumah, RT/RW"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kota/Kabupaten</label>
                            <select class="form-control">
                                <option>Surabaya</option>
                                <option>Gresik</option>
                                <option>Mojokerto</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pilih Vendor Percetakan</label>
                            <select class="form-control">
                                <option>Vendor A (Surabaya) - Rp 80.000</option>
                                <option>Vendor B (Gresik) - Rp 90.000</option>
                                <option>Vendor C (Mojokerto) - Rp 85.000</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-dark text-white">Ringkasan Pesanan</div>
            <div class="card-body">
                <p>Harga Desain: <span class="float-right">Rp 50.000</span></p>
                <p>Harga Cetak: <span class="float-right">Rp 80.000</span></p>
                <p>Ongkir: <span class="float-right">Rp 15.000</span></p>
                <hr>
                <h5>Total: <span class="float-right text-primary">Rp 145.000</span></h5>
                <button class="btn btn-primary btn-block mt-4">Proses Pembayaran</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>