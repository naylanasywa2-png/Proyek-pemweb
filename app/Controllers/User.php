<?php

namespace App\Controllers;

use App\Models\OrderModel;

class User extends BaseController
{
    // Halaman Dashboard User
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Dashboard User',
            'username' => session()->get('username')
        ];

        return view('user/home', $data);
    }

    // --- TAMBAHKAN KODE DI BAWAH INI ---

    // Fungsi untuk memanggil halaman Katalog Game
    public function game()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        return view('user/themes/game'); 
    }

    // Fungsi untuk memanggil halaman Katalog Formal (Scrapbook)
    public function formal()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        return view('user/themes/formal'); 
    }

    // --- SAMPAI DI SINI ---

    public function history()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $model = new OrderModel();
        $id_user = session()->get('id_user');
        $data['orders'] = $model->where('id_user', $id_user)
                                ->orderBy('id_order', 'DESC')
                                ->findAll();

        return view('user/history', $data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}