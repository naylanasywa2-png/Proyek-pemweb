<?php

namespace App\Controllers;

class Auth extends BaseController
{
    // 1. LANDING PAGE UTAMA
    public function index()
    {
        return view('user/index'); 
    }

    // 2. HALAMAN LOGIN
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('katalog'));
        }
        return view('login_view');
    }

    // 3. HALAMAN REGISTER
    public function register()
    {
        return view('register_view'); 
    }

    // 4. DASHBOARD KATALOG (SUDAH DIPERBAIKI)
    public function getKatalog()
    {
        $db = \Config\Database::connect();
        
        // Mengambil semua data dari tabel portfolio agar muncul di katalog user
        $data['portfolios'] = $db->table('portfolio')->get()->getResultArray();

        // Mengirim data portfolios ke view user/katalog
        return view('user/katalog', $data);
    }

    // --- TEMA-TEMA KATALOG ---
    public function getScrapbook() { return view('user/themes/scrapbook'); }
    public function getVintage() { return view('user/themes/vintage'); }
    public function getMafia() { return view('user/themes/mafia'); }
    public function getUrban() { return view('user/themes/urban'); }
    public function getGrandAcademy() { return view('user/themes/academy'); }
    public function getFormal() { return view('user/themes/formal'); }
    public function getGame() { return view('user/themes/game'); }

    // 5. HALAMAN PEMBAYARAN
    public function getPembayaran()
    {
        return view('user/pembayaran');
    }

    // 6. HALAMAN PENGATURAN
    public function getPengaturan()
    {
        return view('user/pengaturan');
    }

    // 7. PROSES PEMBAYARAN
    public function prosesPembayaran()
    {
        return redirect()->to(base_url('katalog'))->with('success', 'Bukti pembayaran berhasil dikirim! 🎀');
    }

    // 8. PROSES LOGIN ACTION
    public function login_action()
    {
        $session = session();
        $db = \Config\Database::connect();
        
        $usernameInput = $this->request->getPost('username');
        $passwordInput = $this->request->getPost('password');

        $user = $db->table('users')->where('email', $usernameInput)->get()->getRowArray();

        if ($user && $passwordInput == $user['password']) { 
            
            $namaUser = explode('@', $user['email'])[0];

            $session->set([
                'id_user'   => $user['id_user'], 
                'username'  => $user['email'], 
                'nama'      => $namaUser,      
                'logged_in' => true,           
                'role'      => $user['role']
            ]);
            
            // Jika role vendor, arahkan ke dashboard vendor
            if ($user['role'] == 'vendor') {
                return redirect()->to(base_url('vendor/dashboard'));
            }
            
            // Jika role desainer (lainnya)
            if ($user['role'] == 'desainer') {
                return redirect()->to(base_url('desainer/dashboard'));
            }
            
            return redirect()->to(base_url('katalog'));
            
        } else {
            return redirect()->to(base_url('login'))->with('error', 'Username atau password salah! 🎀');
        }
    }

    // 9. PROSES DAFTAR ACTION
    public function register_action()
    {
        $db = \Config\Database::connect();
        $email    = $this->request->getPost('email'); 
        $password = $this->request->getPost('password');

        if (empty($email)) {
            $email = $this->request->getPost('username');
        }

        $data = [
            'email'    => $email,
            'password' => $password, 
            'role'     => 'user'
        ];

        $db->table('users')->insert($data);
        return redirect()->to(base_url('login'))->with('success', 'Akun berhasil dibuat! ✨');
    }

    // 10. PROSES LOGOUT
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'))->with('msg', 'Berhasil keluar!');
    }
}