<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function index()
    {
        // Kalau sudah login, jangan kasih halaman login lagi, lempar ke katalog
        if (session()->get('logged_in')) {
            return redirect()->to(site_url('katalog'));
        }
        return view('login_view');
    }

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

    public function login_action()
    {
        $session = session();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Simulasi database sederhana
        if ($username == 'aulia' && $password == '123') { 
            $session->set([
                'id_user'   => 1, // Angka 1 inilah yang dicari database kamu
                'username'  => $username,
                'logged_in' => true,
                'role'      => 'user'
            ]);
            
            // Cek apakah ada tujuan halaman sebelumnya (misal mau ke checkout tapi disuruh login dulu)
            return redirect()->to(site_url('order/create'));
        } else {
            return redirect()->back()->with('error', 'Username atau password salah ya Aulia! 🎀');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('katalog'))->with('msg', 'Berhasil keluar! Sampai jumpa lagi~');
    }
}