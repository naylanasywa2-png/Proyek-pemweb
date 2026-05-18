<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Vendor extends BaseController
{
    // 1. Halaman Dashboard
    public function index()
    {
        return view('vendor/dashboard');
    }

    public function dashboard()
    {
        return view('vendor/dashboard');
    }

    // 2. Halaman Daftar Pesanan (Read)
    public function pesanan()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('orders');
        $builder->select('orders.*, users.email as email_pembeli');
        $builder->join('users', 'users.id_user = orders.id_user');
        $builder->orderBy('orders.id_order', 'DESC');
        
        $data['orders'] = $builder->get()->getResultArray();

        return view('vendor/pesanan', $data);
    }

    // 3. Fungsi Update Status Pesanan
    public function updateStatus($id)
    {
        $db = \Config\Database::connect();
        
        $db->table('orders')
           ->where('id_order', $id)
           ->update(['status_pesanan' => 'diproses']);

        return redirect()->to(base_url('vendor/pesanan'))->with('message', 'Pesanan berhasil dikonfirmasi!');
    }

    // 4. Halaman Portofolio
    public function portfolio()
    {
        $db = \Config\Database::connect();
        // Mengambil data portfolio dari database untuk ditampilkan di halaman
        $data['portfolios'] = $db->table('portfolio')->get()->getResultArray();

        return view('vendor/portfolio', $data);
    }

    // --- TAMBAHAN: FUNGSI SIMPAN DESAIN BARU ---
    public function savePortfolio()
    {
        $db = \Config\Database::connect();

        // 1. Ambil file gambar yang diupload
        $fileGambar = $this->request->getFile('gambar');

        // 2. Olah upload gambar
        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            // Beri nama acak agar tidak ada nama file yang sama di server
            $namaGambar = $fileGambar->getRandomName();
            // Pindahkan ke folder public/uploads/portfolio
            $fileGambar->move('uploads/portfolio/', $namaGambar);
        } else {
            $namaGambar = 'default.jpg'; // Jika gagal upload gunakan gambar default
        }

        // 3. Siapkan data untuk dimasukkan ke database
        $data = [
            'nama_tema' => $this->request->getPost('nama_tema'),
            'harga'     => $this->request->getPost('harga'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar
        ];

        // 4. Proses Insert ke tabel 'portfolio'
        $db->table('portfolio')->insert($data);

        // 5. Kembali ke halaman portfolio dengan notifikasi sukses
        return redirect()->to(base_url('vendor/portfolio'))->with('message', 'Desain baru berhasil ditambahkan ke portfolio!');
    }
}