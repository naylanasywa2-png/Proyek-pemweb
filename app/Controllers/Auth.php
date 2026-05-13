<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * Controller Auth
 *
 * Menangani login, logout, dan registrasi user.
 *
 * CATATAN: Password di database disimpan sebagai SHA256 (sesuai data awal).
 * Untuk project baru, ganti ke password_hash() / password_verify() yang lebih aman.
 *
 * Route yang dibutuhkan (tambahkan ke app/Config/Routes.php):
 *   $routes->get('login',    'Auth::login');
 *   $routes->post('login',   'Auth::loginProcess');
 *   $routes->get('logout',   'Auth::logout');
 *   $routes->get('register', 'Auth::register');
 *   $routes->post('register','Auth::registerProcess');
 */
class Auth extends BaseController
{
    // =========================================================
    // HALAMAN LOGIN
    // =========================================================
    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('user_id')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/login_view');
    }

    // =========================================================
    // PROSES LOGIN
    // =========================================================
    public function loginProcess()
    {
        helper(['url']);

        // Ambil data dari form
        $email    = trim($this->request->getPost('email') ?? '');
        $password = trim($this->request->getPost('password') ?? '');

        // Validasi input kosong
        if (empty($email) || empty($password)) {
            session()->setFlashdata('error', 'Email dan password wajib diisi.');
            return redirect()->to(base_url('login'));
        }

        // Cari user di database berdasarkan email
        $db   = \Config\Database::connect();
        $user = $db->table('users')
            ->where('email', $email)
            ->get()
            ->getRowArray();

        if (! $user) {
            session()->setFlashdata('error', 'Email atau password salah.');
            return redirect()->to(base_url('login'));
        }

        // Verifikasi password
        // Database seed menggunakan SHA256 — sesuaikan jika ganti ke password_hash
        $passwordHashed = hash('sha256', $password);
        if ($user['password'] !== $passwordHashed) {
            session()->setFlashdata('error', 'Email atau password salah.');
            return redirect()->to(base_url('login'));
        }

        // Login berhasil — simpan data user ke session
        session()->set([
            'user_id'   => $user['id_user'],
            'nama'      => $user['nama'],
            'email'     => $user['email'],
            'role'      => $user['role'],
            'logged_in' => true,
        ]);

        session()->setFlashdata('sukses', 'Selamat datang, ' . $user['nama'] . '!');

        // Redirect ke URL sebelumnya (jika ada) atau ke dashboard
        $redirectUrl = session()->getFlashdata('redirect_url') ?? base_url('dashboard');
        return redirect()->to($redirectUrl);
    }

    // =========================================================
    // LOGOUT
    // =========================================================
    public function logout()
    {
        session()->destroy();
        session()->setFlashdata('info', 'Anda telah berhasil logout.');
        return redirect()->to(base_url('login'));
    }

    // =========================================================
    // HALAMAN REGISTER
    // =========================================================
    public function register()
    {
        if (session()->get('user_id')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/register_view');
    }

    // =========================================================
    // PROSES REGISTER
    // =========================================================
    public function registerProcess()
    {
        helper(['url']);

        $nama     = trim($this->request->getPost('nama')     ?? '');
        $email    = trim($this->request->getPost('email')    ?? '');
        $password = trim($this->request->getPost('password') ?? '');
        $konfirm  = trim($this->request->getPost('konfirmasi_password') ?? '');

        // --- Validasi manual ---
        $errors = [];

        if (empty($nama) || strlen($nama) < 2) {
            $errors['nama'] = 'Nama minimal 2 karakter.';
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid.';
        }

        if (strlen($password) < 6) {
            $errors['password'] = 'Password minimal 6 karakter.';
        }

        if ($password !== $konfirm) {
            $errors['konfirmasi_password'] = 'Konfirmasi password tidak cocok.';
        }

        if (! empty($errors)) {
            session()->setFlashdata('errors', $errors);
            session()->setFlashdata('old_input', ['nama' => $nama, 'email' => $email]);
            return redirect()->to(base_url('register'));
        }

        // --- Cek email duplikat ---
        $db = \Config\Database::connect();
        $existing = $db->table('users')->where('email', $email)->countAllResults();

        if ($existing > 0) {
            session()->setFlashdata('error', 'Email sudah terdaftar. Silakan gunakan email lain.');
            session()->setFlashdata('old_input', ['nama' => $nama, 'email' => $email]);
            return redirect()->to(base_url('register'));
        }

        // --- Simpan user baru ---
        try {
            $berhasil = $db->table('users')->insert([
                'nama'       => htmlspecialchars($nama),
                'email'      => $email,
                'password'   => hash('sha256', $password),
                'role'       => 'user',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if ($berhasil) {
                session()->setFlashdata('sukses', 'Akun berhasil dibuat! Silakan login.');
                return redirect()->to(base_url('login'));
            }

            session()->setFlashdata('error', 'Gagal membuat akun. Coba lagi.');
            return redirect()->to(base_url('register'));

        } catch (\Throwable $e) {
            log_message('error', '[Auth::registerProcess] ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
            return redirect()->to(base_url('register'));
        }
    }
}