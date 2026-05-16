<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class User extends BaseController
{
    public function history()
    {
        // 1. Koneksi Database
        $db = \Config\Database::connect();
        
        // 2. Ambil ID User dari session login
        $id_user = session()->get('id_user');

        // 3. Proteksi Sesi: Jika user belum login, lempar kembali ke halaman login
        if (!$id_user) {
            return redirect()->to(base_url('login'))->with('message', 'Silakan login terlebih dahulu.');
        }

        /**
         * 4. PROSES AMBIL DATA RIWAYAT (JOIN TABEL)
         * Mengambil data pesanan dan mencocokkan id_desain dengan id portfolio
         */
        $data['pesanan'] = $db->table('orders')
                             ->select('orders.*, portfolio.nama_tema') 
                             ->join('portfolio', 'portfolio.id = orders.id_desain')
                             ->where('orders.id_user', $id_user)
                             ->orderBy('orders.created_at', 'DESC') // Pesanan terbaru tampil paling atas
                             ->get()
                             ->getResultArray();

        // 5. KOREKSI: Diarahkan ke 'user/history_view' agar sesuai dengan nama file fisik 'history_view.php'
        return view('user/history_view', $data);
    }
}