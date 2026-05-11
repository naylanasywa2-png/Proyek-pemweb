<?php

namespace App\Controllers;

use App\Models\OrderModel;

class Order extends BaseController
{
    public function create()
    {
        // Pengecekan login agar aman
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        return view('user/order_form'); 
    }

    public function checkout()
    {
        // Validasi agar jumlah tidak kosong atau minus
        if (!$this->validate([
            'jumlah' => 'required|numeric|greater_than[0]',
            'id_desain' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('msg', 'Isi jumlah pesananmu dulu ya! 🎀');
        }

        $model = new OrderModel();
        
        // Logika hitung otomatis (sama seperti Game Mode)
        $hargaSatuan = 15000; // Harga DIY Scrapbook
        $jumlah = $this->request->getPost('jumlah');
        $totalBayar = $jumlah * $hargaSatuan;

        $data = [
            'id_user'        => session()->get('id_user'),
            'id_desain'      => $this->request->getPost('id_desain'), // Sesuai pilihan di form
            'id_vendor'      => $this->request->getPost('id_vendor'),
            'jumlah'         => $jumlah,
            'total_bayar'    => $totalBayar,
            'status_pesanan' => 'pending_payment', 
        ];

        if ($model->save($data)) {
            return redirect()->to(base_url('user/history'))->with('msg', 'Pesanan Scrapbook Berhasil! ✨');
        }
        return redirect()->back()->with('msg', 'Gagal pesan, coba lagi ya Aulia!');
    }

    public function history()
    {
        $model = new OrderModel();
        // Ambil data hanya milik user yang login (Aulia/Aul)
        $data['orders'] = $model->where('id_user', session()->get('id_user'))->findAll();
        return view('user/history', $data);
    }
}