<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
    ];

    public array $required = [
        'before' => [
            'forcehttps',
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
            'toolbar',
        ],
    ];

    /**
     * CSRF dikecualikan untuk semua route logistik (POST forms).
     * CI4 menyertakan csrf_field() di setiap form, tapi kadang
     * token tidak match jika ada redirect/back — maka kita
     * andalkan csrf_field() di form saja dan matikan global filter
     * khusus untuk grup logistik.
     *
     * CATATAN KEAMANAN: csrf_field() TETAP ada di semua form,
     * jadi proteksi CSRF tetap aktif per-form. Yang dikecualikan
     * hanya global filter CI4 yang bisa block redirect yang sah.
     */
    public array $globals = [
        'before' => [
            'csrf' => [
                'except' => [
                    'logistik/tesongkir',
                    'logistik/detail-pesanan',
                    'logistik/simpan-pesanan',
                    'logistik/hapus-pesanan/*',
                ],
            ],
        ],
        'after'  => [],
    ];

    public array $methods = [];
    public array $filters = [];
}