<?php

namespace App\Controllers;

class Admin extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Ambil data statistik riil dari database
        $data['total_user'] = $this->db->table('users')->countAllResults(); 
        $data['total_pesanan'] = $this->db->table('orders')->countAllResults();
        
        // Deteksi nama kolom harga/bayar di tabel orders agar tidak error 1054
        $kolomHarga = 'total_harga';
        if (!$this->db->fieldExists('total_harga', 'orders') && $this->db->fieldExists('total_bayar', 'orders')) {
            $kolomHarga = 'total_bayar';
        }

        // PERBAIKAN: Menghitung omset secara aman tanpa menggunakan variabel properti ganda
        $queryOmset = $this->db->table('orders')->selectSum($kolomHarga)->get()->getRowArray();
        $data['total_pendapatan'] = $queryOmset[$kolomHarga] ?? 0;

        // Ambil data pesanan untuk ditampilkan di dalam tabel dashboard
        $data['semua_pesanan'] = $this->db->table('orders')->get()->getResultArray();

        return view('admin/dashboard_baru', $data);
    }

    // ✨ FUNGSI MENAMPILKAN TRANSAKSI
    public function transaksi()
    {
        $data['orders'] = $this->db->table('orders')
                                   ->orderBy('id_order', 'DESC')
                                   ->get()->getResultArray();

        return view('admin/transaksi_view', $data);
    }

    // ✨ FUNGSI AKSI SETUJUI PEMBAYARAN (SUPER AMAN & ANTI REFRESH LOOP 🚀)
    public function setujui($id_order)
    {
        // 1. Cari tahu nama kolom status pesanan yang digunakan di database secara dinamis
        $kolomStatus = 'status_pesanan';
        if (!$this->db->fieldExists('status_pesanan', 'orders') && $this->db->fieldExists('status', 'orders')) {
            $kolomStatus = 'status';
        }

        // 2. Langsung eksekusi update ke database tanpa syarat pengecekan yang membekukan halaman
        $this->db->table('orders')
                 ->where('id_order', $id_order)
                 ->update([
                     $kolomStatus => 'diproses'
                 ]);

        // 3. Kembalikan ke halaman transaksi dengan membawa flashdata sukses
        return redirect()->to(base_url('admin/transaksi'))->with('success', 'Pembayaran ID #' . $id_order . ' berhasil disetujui!');
    }

    // ==========================================
    // TAMBAHAN FITUR MANAJEMEN USER & VENDOR
    // ==========================================

    public function users()
    {
        $data['total_user'] = $this->db->table('users')->countAllResults();
        $data['semua_user'] = $this->db->table('users')->get()->getResultArray();

        return view('admin/users_view', $data);
    }

    public function bannedUser($id_user)
    {
        $this->db->table('users')->where('id_user', $id_user)->delete();

        return redirect()->to(base_url('admin/users'))->with('success', 'Akun pengguna berhasil dihapus dari sistem!');
    }
}