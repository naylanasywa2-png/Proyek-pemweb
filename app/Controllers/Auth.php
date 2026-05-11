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
        // Jika sudah login, langsung lempar ke katalog
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

    // 4. DASHBOARD KATALOG
    public function getKatalog()
    {
        return view('user/katalog');
    }

    // --- TEMA-TEMA KATALOG ---
    public function getScrapbook() { return view('user/themes/scrapbook'); }
    public function getVintage() { return view('user/themes/vintage'); }
    public function getMafia() { return view('user/themes/mafia'); }
    public function getUrban() { return view('user/themes/urban'); }
    public function getGrandAcademy() { return view('user/themes/academy'); }
    public function getFormal() { return view('user/themes/formal'); }
    public function getGame() { return view('user/themes/game'); }

    // 5. PROSES LOGIN ACTION
    public function login_action()
    {
        $session = session();
        $db = \Config\Database::connect();
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Cari user berdasarkan email
        $user = $db->table('users')->where('email', $username)->get()->getRowArray();

        if ($user && $password == $user['password']) { 
            // SET SESSION DENGAN BENAR
            $session->set([
                'id_user'   => $user['id'], 
                'username'  => $user['email'],
                'logged_in' => true,
                'role'      => $user['role']
            ]);
            
            if ($user['role'] == 'desainer') {
                return redirect()->to(base_url('desainer/dashboard'));
            }
            
            // Redirect ke katalog (tombol login akan hilang jika view benar)
            return redirect()->to(base_url('katalog'));
            
        } else {
            return redirect()->to(base_url('login'))->with('error', 'Username atau password salah! 🎀');
        }
    }

    // 6. PROSES DAFTAR ACTION
    public function register_action()
    {
        $db = \Config\Database::connect();
        
        $email    = $this->request->getPost('email'); 
        $password = $this->request->getPost('password');

        // Backup jika form pakai name="username"
        if (empty($email)) {
            $email = $this->request->getPost('username');
        }

        $data = [
            'email'    => $email,
            'password' => $password,
            'role'     => 'user'
        ];

        $db->table('users')->insert($data);

        return redirect()->to(base_url('login'))->with('success', 'Akun berhasil dibuat! Silahkan login ✨');
    }

    // 7. PROSES LOGOUT
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'))->with('msg', 'Berhasil keluar!');
    }
}