<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\DesignModel;

class Order extends BaseController
{
    // 1. Fungsi untuk proses pemesanan (Checkout)
    public function checkout()
    {
        $model = new OrderModel();
        
        // Logika: Ambil data dari form pesanan
        $data = [
            'id_user'        => session()->get('id_user'),
            'id_desain'      => $this->request->getPost('id_desain'),
            'id_vendor'      => $this->request->getPost('id_vendor'),
            'jumlah'         => $this->request->getPost('jumlah'),
            'total_bayar'    => $this->request->getPost('total_bayar'),
            'status_pesanan' => 'pending_payment', // Status awal: Menunggu dibayar
        ];

        $model->save($data);
        return redirect()->to('/user/history')->with('msg', 'Pesanan dibuat, silakan bayar!');
    }

    // 2. Fungsi Escrow (Konfirmasi Pembayaran)
    public function bayar($id_order)
    {
        $model = new OrderModel();
        // Logika: User upload bukti, status berubah jadi 'dikemas' (dana ditahan sistem)
        $model->update($id_order, ['status_pesanan' => 'paid_escrow']);
        
        return redirect()->back()->with('msg', 'Pembayaran diterima! Dana ditahan sistem (Escrow).');
    }
    // 3. Fungsi saat Vendor mengirim barang
    public function kirim($id_order)
    {
        $model = new OrderModel();
        // Status berubah: Dana masih di sistem, barang dalam perjalanan
        $model->update($id_order, ['status_pesanan' => 'shipped']);
        
        return redirect()->back()->with('msg', 'Status: Barang sedang dikirim oleh Vendor.');
    }

    // 4. Fungsi Konfirmasi Terima (DANA CAIR!) -> Ini Inti Escrow
    public function konfirmasi_selesai($id_order)
    {
        $model = new OrderModel();
        // User klik terima, status jadi 'completed', dana baru dianggap hak vendor
        $model->update($id_order, ['status_pesanan' => 'completed']);
        
        return redirect()->back()->with('msg', 'Pesanan Selesai! Dana telah diteruskan ke Vendor.');
    }
}