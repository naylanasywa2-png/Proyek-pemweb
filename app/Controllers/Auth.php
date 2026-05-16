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

    // 4. DASHBOARD KATALOG
    public function getKatalog()
    {
        $db = \Config\Database::connect();
        $data['portfolios'] = $db->table('portfolio')->get()->getResultArray();
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

    // 5. HALAMAN TAMPILAN FORM PEMBAYARAN
    public function getPembayaran()
    {
        return view('user/pembayaran');
    }

    // 6. HALAMAN PENGATURAN
    public function getPengaturan()
    {
        return view('user/pengaturan');
    }

    // 7. PROSES PEMBAYARAN (SUDAH DISINKRONKAN DENGAN FOLDER BUKTI_BAYAR ✨)
    public function prosesPembayaran()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $db = \Config\Database::connect();
        $fileBukti = $this->request->getFile('bukti_transfer');

        // Memperbaiki typo variabel pengecekan hasMoved() sebelumnya
        if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
            
            // Generate nama file acak yang aman
            $namaFileBaru = $fileBukti->getRandomName();
            
            // DISESUAIKAN: Mengarah ke folder 'bukti_bayar' sesuai struktur asli proyekmu
            $pathTujuan = ROOTPATH . 'public/uploads/bukti_bayar';
            if (!is_dir($pathTujuan)) {
                mkdir($pathTujuan, 0777, true);
            }

            // Pindahkan file gambar ke folder bukti_bayar
            $fileBukti->move($pathTujuan, $namaFileBaru);

            $idUser = session()->get('id_user');
            
            // Ambil pesanan terakhir milik user yang sedang login
            $pesananTerakhir = $db->table('orders')
                                  ->where('id_user', $idUser)
                                  ->where('status_pesanan', 'pending_payment')
                                  ->orderBy('id_order', 'DESC')
                                  ->get()
                                  ->getRowArray();

            if ($pesananTerakhir) {
                
                // Fitur Cerdas: Cek otomatis nama kolom yang ada di tabel orders kamu!
                $kolomDitemukan = null;
                $kandidatKolom  = ['bukti_transfer', 'bukti_bayar', 'bukti', 'image', 'bukti_pembayaran'];

                foreach ($kandidatKolom as $kolom) {
                    if ($db->fieldExists($kolom, 'orders')) {
                        $kolomDitemukan = $kolom;
                        break;
                    }
                }

                if ($kolomDitemukan !== null) {
                    // Update data ke tabel orders
                    $db->table('orders')
                       ->where('id_order', $pesananTerakhir['id_order'])
                       ->update([
                           'status_pesanan' => 'waiting_verification',
                           $kolomDitemukan  => $namaFileBaru
                       ]);

                    return redirect()->to(base_url('katalog'))->with('success', 'Bukti transfer berhasil dikirim! Admin akan segera memverifikasi ya~ 💖');
                } else {
                    return redirect()->to(base_url('katalog'))->with('error', 'Kolom upload pada tabel orders tidak ditemukan di database.');
                }
            }
        }

        return redirect()->back()->with('msg', 'Gagal mengunggah bukti, pastikan file berupa foto yang valid ya! 😢');
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
            
            if ($user['role'] == 'vendor') {
                return redirect()->to(base_url('vendor/dashboard'));
            }
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