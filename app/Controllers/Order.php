<?php

namespace App\Controllers;

use App\Models\OrderModel;

class Order extends BaseController
{
    public function create()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        return view('user/order_form'); 
    }

    public function checkout()
    {
        $idUser = session()->get('id_user');

        // Pengaman jika session hilang
        if (!$idUser) {
            return redirect()->to('/login')->with('error', 'Login ulang dulu ya Aulia! 🎀');
        }

        // Validasi input
        if (!$this->validate([
            'jumlah'    => 'required|numeric|greater_than[0]',
            'id_desain' => 'required',
            'id_vendor' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('msg', 'Lengkapi datanya dulu ya! ✨');
        }

        $model = new OrderModel();
        
        $hargaSatuan = 15000; 
        $jumlah      = $this->request->getPost('jumlah');
        $totalBayar  = $jumlah * $hargaSatuan;

        $data = [
            'id_user'        => $idUser,
            'id_desain'      => $this->request->getPost('id_desain'),
            'id_vendor'      => $this->request->getPost('id_vendor'),
            'jumlah'         => $jumlah,
            'total_bayar'    => $totalBayar,
            'status_pesanan' => 'pending_payment', 
        ];

        try {
            if ($model->save($data)) {
                // DIUBAH: Menggunakan base_url agar pemanggilan rute redirect lebih aman dan konsisten
                return redirect()->to(base_url('order/history'))->with('msg', 'Pesanan Berhasil Dibuat! ✨');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('msg', 'Cek XAMPP kamu, MySQL mungkin mati! 🛠️');
        }

        return redirect()->back()->with('msg', 'Gagal pesan, coba lagi ya!');
    }

    public function history()
    {
        $idUser = session()->get('id_user');
        
        if (!$idUser) {
            return redirect()->to('/login');
        }

        $model = new OrderModel();
        // Ambil data milik user yang sedang login
        $data['orders'] = $model->where('id_user', $idUser)->orderBy('id_order', 'DESC')->findAll();
        
        // Memastikan view mengarah ke folder user/history
        return view('user/history', $data);
    }
}