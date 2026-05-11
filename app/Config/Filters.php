<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    /**
     * Daftar alias filter yang bisa digunakan.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'auth'          => \App\Filters\AuthFilter::class, // Satpam login kita
    ];

    /**
     * List filter yang WAJIB dijalankan.
     * Kita kosongkan 'before' agar tidak ada error "forcehttps" lagi.
     */
    public array $required = [
        'before' => [
            // Kosongkan ini lul, biar nggak error FilterException lagi
        ],
        'after' => [
            'toolbar', // Biar bar debug di bawah tetap muncul
        ],
    ];

    /**
     * Filter yang berjalan secara global.
     */
    public array $globals = [
        'before' => [
            // 'auth' sengaja dimatikan (dikomentar) agar Landing Page bisa dibuka bebas
            // 'auth' => ['except' => ['/', 'login', 'auth/*', 'katalog', 'katalog/*']],
        ],
        'after' => [
            'toolbar',
        ],
    ];

    /**
     * Filter berdasarkan metode HTTP (GET, POST, dll).
     */
    public array $methods = [];

    /**
     * Filter khusus untuk halaman tertentu.
     */
    public array $filters = [
        // Jika nanti ingin mengunci halaman order, baru aktifkan ini:
        // 'auth' => ['before' => ['order/*', 'admin/*']],
    ];
}