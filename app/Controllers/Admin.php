<?php

namespace App\Controllers;

use App\Models\OrderModel;

class Admin extends BaseController
{
    /**
     * Fungsi utama Dashboard Admin
     * Menampilkan semua data transaksi yang masuk ke sistem
     */
    public function index()
    {
        $orderModel = new OrderModel();
        
        // Mengambil seluruh data pesanan untuk dipantau (Escrow Monitor)
        $data['semua_pesanan'] = $orderModel->findAll();

        return view('admin_dashboard', $data);
    }
}