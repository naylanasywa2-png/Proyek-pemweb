<?php

namespace App\Controllers;

class User extends BaseController
{
    public function history()
    {
        $db = \Config\Database::connect();
        $id_user = session()->get('id_user');

        // Ambil data pesanan milik user yang sedang login
        $data['pesanan'] = $db->table('orders')
                             ->where('id_user', $id_user)
                             ->get()->getResultArray();

        return view('user/history_view', $data);
    }
}