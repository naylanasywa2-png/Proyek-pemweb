<?php

namespace App\Controllers;

use App\Models\OrderModel;

class User extends BaseController
{
    // Halaman Dashboard User
    public function index()
    {
        // Cek apakah user sudah login, jika belum lempar ke login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Dashboard User',
            'username' => session()->get('username')
        ];

        return view('user/home', $data);
    }

    // Fungsi untuk menampilkan Riwayat Pesanan (History)
    // Ini untuk memperbaiki error "getHistory not found" tadi
    public function history()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $model = new OrderModel();
        
        // Ambil data pesanan berdasarkan ID User yang sedang login
        $id_user = session()->get('id_user');
        $data['orders'] = $model->where('id_user', $id_user)
                                ->orderBy('id_order', 'DESC')
                                ->findAll();

        return view('user/history', $data);
    }

    // Fungsi Logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}