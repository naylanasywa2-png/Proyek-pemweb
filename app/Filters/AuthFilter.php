<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * Filter AuthFilter
 *
 * Melindungi halaman yang memerlukan login.
 * Jika user belum login, redirect ke /login.
 *
 * Cara daftarkan di app/Config/Filters.php:
 *   'auth' => \App\Filters\AuthFilter::class,
 *
 * Cara pakai di app/Config/Routes.php:
 *   $routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
 *   Atau group:
 *   $routes->group('', ['filter' => 'auth'], function($routes) { ... });
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika session user_id tidak ada, berarti belum login
        if (! session()->get('user_id')) {
            // Simpan URL yang dituju agar bisa redirect balik setelah login
            session()->setFlashdata('redirect_url', current_url());
            session()->setFlashdata('info', 'Silakan login terlebih dahulu.');
            return redirect()->to(base_url('login'));
        }

        // Jika ada $arguments (misal: 'admin'), cek role
        if (! empty($arguments)) {
            $requiredRole = $arguments[0] ?? null;
            $userRole     = session()->get('role');

            if ($requiredRole && $userRole !== $requiredRole) {
                // Role tidak sesuai -> tolak akses
                session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
                return redirect()->to(base_url('dashboard'));
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu tindakan setelah response
    }
}