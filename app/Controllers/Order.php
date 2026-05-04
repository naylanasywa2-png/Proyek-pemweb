<?php

namespace App\Controllers;

use App\Models\OrderModel;

class Order extends BaseController
{
    // FUNGSI INI YANG HILANG/ERROR
    public function create()
    {
        // Pastikan user sudah login sebelum memesan
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Menampilkan form pemesanan yang ada di folder Views/user/
        return view('user/order_form'); 
    }

    public function checkout()
    {
        $model = new OrderModel();
        
        $data = [
            'id_user'        => session()->get('id_user'),
            'id_desain'      => $this->request->getPost('id_desain'),
            'id_vendor'      => $this->request->getPost('id_vendor'),
            'jumlah'         => $this->request->getPost('jumlah'),
            'total_bayar'    => $this->request->getPost('total_bayar'),
            'status_pesanan' => 'pending_payment', 
        ];

        $model->save($data);

        // Setelah sukses, lempar ke halaman history
        return redirect()->to(base_url('user/history'))->with('msg', 'Pesanan berhasil dibuat! ✨');
    }
}