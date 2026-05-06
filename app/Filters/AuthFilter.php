<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $path = $request->getUri()->getPath();

        // 1. Jika BELUM login
        if (!$session->get('logged_in')) {
            // Jangan redirect jika user memang mau ke halaman login/register
            if ($path !== 'login' && $path !== 'auth' && $path !== 'register') {
                return redirect()->to('/login')->with('msg', 'Sesi berakhir, silakan login kembali ya! 🎀');
            }
        } 
        
        // 2. Jika SUDAH login tapi malah akses halaman login lagi
        else {
            if ($path === 'login' || $path === 'auth') {
                // Lempar balik ke katalog scrapbook biar tidak muter-muter
                return redirect()->to('/katalog/scrapbook');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosongkan saja sesuai standar CI4
    }
}