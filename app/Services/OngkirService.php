<?php

namespace App\Services;

/**
 * OngkirService
 * Menghubungkan aplikasi ke API ongkir Komerce Sandbox.
 * Jika API key belum diset, otomatis pakai data demo.
 */
class OngkirService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api-sandbox.collaborator.komerce.id/tariff/api/v1';
    protected int    $timeout = 15;

    public function __construct()
    {
        $this->apiKey = trim(env('KOMERCE_SHIPPING_API_KEY', ''));
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Cek ongkir utama
    // -------------------------------------------------------------------------

    public function cekOngkir(
        int    $shipperId,
        int    $receiverId,
        float  $weight    = 1.0,
        int    $itemValue = 50000,
        string $cod       = 'no'
    ): array {
        // Fallback langsung jika API key kosong
        if (empty($this->apiKey)) {
            return $this->demoData($weight);
        }

        $query = http_build_query([
            'shipper_destination_id'  => $shipperId,
            'receiver_destination_id' => $receiverId,
            'weight'                  => $weight,
            'item_value'              => $itemValue,
            'cod'                     => $cod,
        ]);

        $url      = "{$this->baseUrl}/calculate?{$query}";
        $response = $this->get($url);

        if ($response === null) {
            $demo            = $this->demoData($weight);
            $demo['message'] = 'Koneksi ke API gagal. Menampilkan data simulasi.';
            return $demo;
        }

        $data = json_decode($response['body'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['status' => 'error', 'message' => 'Respons API tidak valid.', 'is_demo' => false, 'data' => []];
        }

        $code = $response['http_code'];

        if (in_array($code, [401, 403])) {
            $demo            = $this->demoData($weight);
            $demo['message'] = 'API Key tidak valid. Menampilkan data simulasi.';
            return $demo;
        }

        if ($code === 200 && isset($data['data']['calculate_reguler'])) {
            return $this->formatKomerce($data, $weight);
        }

        if ($code === 200 && isset($data['data']) && is_array($data['data'])) {
            return $this->formatFlat($data);
        }

        $msg = $data['meta']['message'] ?? $data['message'] ?? "Error HTTP {$code}";
        return ['status' => 'error', 'message' => $msg, 'is_demo' => false, 'data' => []];
    }

    // -------------------------------------------------------------------------
    // PRIVATE: HTTP GET via cURL
    // -------------------------------------------------------------------------

    private function get(string $url): ?array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER     => [
                'x-api-key: ' . $this->apiKey,
                'Accept: application/json',
            ],
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $body  = curl_exec($ch);
        $code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            log_message('error', "[OngkirService] cURL error: {$error}");
            return null;
        }

        return ['body' => $body, 'http_code' => $code];
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Format response Komerce (calculate_reguler + calculate_cargo)
    // -------------------------------------------------------------------------

    private function formatKomerce(array $data, float $weight): array
    {
        $list = [];

        foreach ($data['data']['calculate_reguler'] as $item) {
            $list[] = $this->normalize($item);
        }

        foreach (($data['data']['calculate_cargo'] ?? []) as $item) {
            $row         = $this->normalize($item);
            $row['note'] = 'Cargo';
            $list[]      = $row;
        }

        // Normalisasi harga jika API mengembalikan nilai dalam satuan terkecil
        foreach ($list as &$item) {
            if (isset($item['price']) && $item['price'] > 1000000) {
                $item['price'] = (int) round($item['price'] / 1000);
            }
            if (isset($item['price_net']) && $item['price_net'] > 1000000) {
                $item['price_net'] = (int) round($item['price_net'] / 1000);
            }
        }
        unset($item);

        // Fallback jika hasil dari API 0 semua (kemungkinan format tidak dikenali)
        $allZero = array_sum(array_column($list, 'price')) === 0;
        if (empty($list) || $allZero) {
            return $this->demoData($weight);
        }

        return ['status' => 'success', 'message' => 'Berhasil.', 'is_demo' => false, 'data' => $list];
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Format response flat array
    // -------------------------------------------------------------------------

    private function formatFlat(array $data): array
    {
        $list = [];
        foreach ($data['data'] as $item) {
            $courier  = $item['shipping_name'] ?? $item['courier'] ?? 'UNKNOWN';
            $services = $item['services'] ?? [$item];
            foreach ($services as $svc) {
                $list[] = [
                    'courier'   => strtoupper($courier),
                    'service'   => $svc['service_name'] ?? $svc['service']    ?? '-',
                    'desc'      => $svc['description']  ?? $svc['service_name'] ?? '-',
                    'price'     => (int) ($svc['shipping_cost'] ?? $svc['price'] ?? $svc['cost'] ?? 0),
                    'price_net' => (int) ($svc['shipping_cost_net'] ?? 0),
                    'estimated' => $svc['etd'] ?? $svc['estimated'] ?? '-',
                    'is_cod'    => (bool) ($svc['is_cod'] ?? false),
                    'note'      => '',
                ];
            }
        }
        return ['status' => 'success', 'message' => 'Berhasil.', 'is_demo' => false, 'data' => $list];
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Normalisasi satu item
    // -------------------------------------------------------------------------

    private function normalize(array $item): array
    {
        return [
            'courier'   => strtoupper($item['shipping_name'] ?? 'UNKNOWN'),
            'service'   => $item['service_name']     ?? '-',
            'desc'      => $item['description']      ?? $item['service_name'] ?? '-',
            'price'     => (int) ($item['shipping_cost']     ?? 0),
            'price_net' => (int) ($item['shipping_cost_net'] ?? 0),
            'estimated' => $item['etd']               ?? '-',
            'is_cod'    => (bool) ($item['is_cod']    ?? false),
            'note'      => '',
        ];
    }

    // -------------------------------------------------------------------------
    // PRIVATE: Demo data (fallback)
    // -------------------------------------------------------------------------

    private function demoData(float $weight): array
    {
        $r = max(1.0, $weight);

        $items = [
            ['courier' => 'JNE',      'service' => 'OKE',          'desc' => 'Ongkos Kirim Ekonomis', 'price' => 8000,  'etd' => '5-7'],
            ['courier' => 'JNE',      'service' => 'REG',          'desc' => 'Reguler',               'price' => 11000, 'etd' => '2-3'],
            ['courier' => 'JNE',      'service' => 'YES',          'desc' => 'Yakin Esok Sampai',     'price' => 20000, 'etd' => '1'],
            ['courier' => 'JNT',      'service' => 'REG',          'desc' => 'J&T Reguler',           'price' => 10000, 'etd' => '2-3'],
            ['courier' => 'JNT',      'service' => 'EZ',           'desc' => 'J&T Economy',           'price' => 8500,  'etd' => '4-5'],
            ['courier' => 'SICEPAT',  'service' => 'BEST',         'desc' => 'Best',                  'price' => 9500,  'etd' => '2-3'],
            ['courier' => 'SICEPAT',  'service' => 'GOKIL',        'desc' => 'Go Kilat',              'price' => 7500,  'etd' => '1-2'],
            ['courier' => 'ANTERAJA', 'service' => 'REG',          'desc' => 'Regular',               'price' => 9000,  'etd' => '3-4'],
            ['courier' => 'TIKI',     'service' => 'ECO',          'desc' => 'Economy',               'price' => 9000,  'etd' => '5-7'],
            ['courier' => 'POS',      'service' => 'Kilat Khusus', 'desc' => 'Pos Kilat Khusus',      'price' => 8500,  'etd' => '3-5'],
        ];

        $data = array_map(fn($s) => [
            'courier'   => $s['courier'],
            'service'   => $s['service'],
            'desc'      => $s['desc'],
            'price'     => (int) ($s['price'] * $r),
            'price_net' => 0,
            'estimated' => $s['etd'],
            'is_cod'    => false,
            'note'      => '',
        ], $items);

        return [
            'status'  => 'success',
            'message' => 'Data simulasi (API key belum diset).',
            'is_demo' => true,
            'data'    => $data,
        ];
    }
}