<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
// Import filter sistem yang dibutuhkan
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    /**
     * Daftar alias filter.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        // TAMBAHKAN DUA BARIS INI BIAR NGAK ERROR LAGI:
        'forcehttps'    => \CodeIgniter\Filters\ForceHTTPS::class,
        'pagecache'     => \CodeIgniter\Filters\PageCache::class,
        // Filter login kamu
        'auth'          => \App\Filters\AuthFilter::class,
    ];

    /**
     * List filter yang WAJIB dijalankan.
     */
    public array $required = [
        'before' => [
            'forcehttps', // Sistem butuh alias ini terdaftar di atas
            'pagecache',
        ],
        'after' => [
            'toolbar',
            'pagecache',
        ],
    ];

    /**
     * Filter global (kosongkan before agar landing page aman)
     */
    public array $globals = [
        'before' => [],
        'after'  => ['toolbar'],
    ];

    /**
     * Filter khusus halaman tertentu
     */
    public array $filters = [
        'auth' => ['before' => [
            'user/history', // Biar menu "Pesanan Saya" aman
            'order/*', 
            'admin/*',
            'desainer/*'
        ]],
    ];
}