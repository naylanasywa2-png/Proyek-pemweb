<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\OngkirService;

/**
 * Controller Dashboard
 *
 * Mengarahkan user ke dashboard sesuai role (admin, user, vendor, desainer).
 * Dilindungi oleh filter 'auth'.
 */
class Dashboard extends BaseController
{
    public function index()
    {
        $role = session()->get('role');

        return match ($role) {
            'admin'    => $this->adminDashboard(),
            'vendor'   => $this->vendorDashboard(),
            'desainer' => $this->desainerDashboard(),
            default    => $this->userDashboard(),
        };
    }

    // =========================================================
    // DASHBOARD ADMIN (Akses Penuh)
    // =========================================================
    private function adminDashboard()
    {
        $db = \Config\Database::connect();

        $data = [
            'total_user'      => $db->table('users')->where('role', 'user')->countAllResults(),
            'total_pesanan'   => $db->table('orders')->countAllResults(),
            'pesanan_pending' => $db->table('orders')->where('status_pesanan', 'pending')->countAllResults(),
            'total_desain'    => $db->table('desain')->countAllResults(),
            'pesanan_terbaru' => $db->table('orders')->orderBy('created_at', 'DESC')->limit(5)->get()->getResultArray(),
        ];

        return view('dashboard/admin', $data);
    }

    // =========================================================
    // DASHBOARD VENDOR (Akses Pesanan Masuk)
    // =========================================================
    private function vendorDashboard()
    {
        $db     = \Config\Database::connect();
        $userId = session()->get('user_id');

        // Asumsi: id_vendor di tabel orders merujuk pada vendor tertentu
        // Untuk tahap ini, kita tampilkan pesanan yang ditujukan ke vendor (contoh id_vendor 1)
        $data = [
            'pesanan_masuk' => $db->table('orders')
                ->where('id_vendor', 1) // Sesuaikan mapping user ke vendor nantinya
                ->orderBy('created_at', 'DESC')
                ->get()
                ->getResultArray(),
        ];

        return view('dashboard/vendor', $data);
    }

    // =========================================================
    // DASHBOARD DESAINER (Akses Manajemen Desain)
    // =========================================================
    private function desainerDashboard()
    {
        $db = \Config\Database::connect();

        $data = [
            'karya_saya' => $db->table('desain')->get()->getResultArray(),
        ];

        return view('dashboard/desainer', $data);
    }

    // =========================================================
    // DASHBOARD USER (Customer)
    // =========================================================
    private function userDashboard()
    {
        $db     = \Config\Database::connect();
        $userId = session()->get('user_id');

        $data = [
            'pesanan_saya' => $db->table('orders')->where('id_user', $userId)->orderBy('created_at', 'DESC')->limit(10)->get()->getResultArray(),
            'katalog'      => $db->table('desain')->orderBy('id_desain', 'ASC')->get()->getResultArray(),
        ];

        // Contoh slip data API (Ongkir/Shipping) bisa dilakukan di sini sebelum return view
        // $ongkirService = new OngkirService();
        // $data['api_status'] = $ongkirService->getSomeStatus();

        return view('dashboard/user', $data);
    }
}
