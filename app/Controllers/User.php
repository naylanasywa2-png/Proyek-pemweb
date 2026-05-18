<?php

namespace App\Controllers;

class User extends BaseController
{
    public function history()
    {
        // Koneksi Database
        $db = \Config\Database::connect();
        
        // Ambil ID User dari session
        $id_user = session()->get('id_user');

        // Proteksi: Jika user belum login, arahkan ke login
        if (!$id_user) {
            return redirect()->to(base_url('login'));
        }

        /**
         * PERBAIKAN JOIN:
         * 1. Menggunakan 'id_desain' (sesuai tabel orders kamu)
         * 2. Menghubungkan ke 'portfolio.id' (sesuai tabel portfolio kamu)
         */
        $data['pesanan'] = $db->table('orders')
                             ->select('orders.*, portfolio.nama_tema') 
                             ->join('portfolio', 'portfolio.id = orders.id_desain')
                             ->where('orders.id_user', $id_user)
                             ->orderBy('orders.created_at', 'DESC') // Agar pesanan terbaru muncul paling atas
                             ->get()
                             ->getResultArray();

        // Kirim data ke view
        return view('user/history_view', $data);
    }
}