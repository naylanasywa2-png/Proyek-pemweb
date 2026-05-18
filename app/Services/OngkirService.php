<?php

namespace App\Services;

/**
 * OngkirService - Integrasi dengan Komerce Collaborator Shipping Cost API
 *
 * Dokumentasi : https://documenter.getpostman.com/view/26725217/2s9YeD8Dbr
 * Base URL    : https://api-sandbox.collaborator.komerce.id  (Sandbox)
 * Auth Header : x-api-key: <Shipping Cost Key dari dashboard>
 */
class OngkirService
{
    protected string $apiKey;

    // ✅ URL BENAR: api-sandbox.collaborator.komerce.id (bukan .my.id)
    protected string $baseUrl = 'https://api-sandbox.collaborator.komerce.id/tariff/api/v1';

    public function __construct()
    {
        $this->apiKey = env('KOMERCE_SHIPPING_API_KEY', '');
    }

    /**
     * Cari destination ID berdasarkan keyword (nama kota/kecamatan/kode pos)
     */
    public function searchDestination(string $keyword): array
    {
        $url = "{$this->baseUrl}/destination/search?keyword=" . urlencode($keyword);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Content-Type: application/json',
                'x-api-key: ' . $this->apiKey,
            ],
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ]);

        $response = curl_exec($curl);
        $error    = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($error) {
            log_message('error', '[OngkirService::searchDestination] cURL error: ' . $error);
            return [];
        }

        if ($httpCode !== 200) {
            log_message('warning', "[OngkirService::searchDestination] HTTP {$httpCode} — Response: {$response}");
            return [];
        }

        $decoded = json_decode($response, true);
        return $decoded['data'] ?? [];
    }

    /**
     * Hitung biaya ongkir via Komerce Shipping Cost API
     *
     * @param int    $shipperId   Destination ID kota asal
     * @param int    $receiverId  Destination ID kota tujuan
     * @param float  $weight      Berat dalam kg
     * @param int    $itemValue   Nilai barang (untuk kalkulasi asuransi/COD)
     * @param string $cod         Status COD ('yes' atau 'no')
     * @return array
     */
    public function cekOngkir(int $shipperId, int $receiverId, float $weight = 1, int $itemValue = 50000, string $cod = 'no'): array
    {
        if (empty($this->apiKey) || $this->apiKey === 'GANTI_DENGAN_API_KEY_KOMERCE') {
            return $this->getDemoData($weight);
        }

        $params = http_build_query([
            'shipper_destination_id'  => $shipperId,
            'receiver_destination_id' => $receiverId,
            'weight'                  => $weight,
            'item_value'              => $itemValue,
            'cod'                     => $cod,
        ]);

        $url = "{$this->baseUrl}/calculate?{$params}";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Content-Type: application/json',
                'x-api-key: ' . $this->apiKey,
            ],
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ]);

        $response = curl_exec($curl);
        $error    = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // Log setiap request untuk debugging
        log_message('debug', "[OngkirService::cekOngkir] URL: {$url}");
        log_message('debug', "[OngkirService::cekOngkir] HTTP Code: {$httpCode} | API Key (8 char): " . substr($this->apiKey, 0, 8) . '...');
        log_message('debug', "[OngkirService::cekOngkir] Response: {$response}");

        if ($error) {
            log_message('error', '[OngkirService::cekOngkir] cURL error: ' . $error);
            $demo = $this->getDemoData($weight);
            $demo['is_demo'] = true;
            $demo['debug_error'] = $error;
            return $demo;
        }

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'status'  => 'error',
                'message' => 'Respons tidak valid dari server.',
                'data'    => []
            ];
        }

        // ✅ Format AKTUAL Komerce API:
        // {"meta":{"code":200,"status":"success"}, "data":{"calculate_reguler":[...], "calculate_cargo":[...]}}
        if (
            isset($decoded['meta']['code']) &&
            $decoded['meta']['code'] == 200 &&
            isset($decoded['data']['calculate_reguler'])
        ) {
            $formattedCosts = [];
            foreach ($decoded['data']['calculate_reguler'] as $item) {
                $formattedCosts[] = [
                    'courier'   => strtoupper($item['shipping_name'] ?? 'UNKNOWN'),
                    'service'   => $item['service_name']  ?? '-',
                    'desc'      => $item['service_name']  ?? '-',
                    'price'     => (int)($item['shipping_cost']     ?? 0),
                    'price_net' => (int)($item['shipping_cost_net'] ?? 0),
                    'estimated' => $item['etd'] ?? '-',
                    'is_cod'    => $item['is_cod'] ?? false,
                    'note'      => '',
                ];
            }
            // Tambahkan juga cargo jika ada
            foreach (($decoded['data']['calculate_cargo'] ?? []) as $item) {
                $formattedCosts[] = [
                    'courier'   => strtoupper($item['shipping_name'] ?? 'UNKNOWN') . ' (CARGO)',
                    'service'   => $item['service_name']  ?? '-',
                    'desc'      => $item['service_name']  ?? '-',
                    'price'     => (int)($item['shipping_cost']     ?? 0),
                    'price_net' => (int)($item['shipping_cost_net'] ?? 0),
                    'estimated' => $item['etd'] ?? '-',
                    'is_cod'    => $item['is_cod'] ?? false,
                    'note'      => 'Cargo',
                ];
            }

            return [
                'status'  => 'success',
                'message' => 'Berhasil',
                'is_demo' => false,
                'data'    => $formattedCosts
            ];
        }

        // Format lama / fallback (flat array dengan key 'data' langsung)
        if (
            isset($decoded['meta']['code']) &&
            $decoded['meta']['code'] == 200 &&
            isset($decoded['data']) &&
            is_array($decoded['data'])
        ) {
            $formattedCosts = [];
            foreach ($decoded['data'] as $item) {
                $courierName = $item['shipping_name'] ?? $item['courier'] ?? 'UNKNOWN';
                $services    = $item['services'] ?? [$item];
                foreach ($services as $svc) {
                    $formattedCosts[] = [
                        'courier'   => strtoupper($courierName),
                        'service'   => $svc['service_name'] ?? $svc['service'] ?? '-',
                        'desc'      => $svc['description']  ?? $svc['service_name'] ?? '-',
                        'price'     => (int)($svc['shipping_cost'] ?? $svc['price'] ?? $svc['cost'] ?? 0),
                        'price_net' => (int)($svc['shipping_cost_net'] ?? 0),
                        'estimated' => $svc['etd'] ?? $svc['estimated'] ?? '-',
                        'is_cod'    => $svc['is_cod'] ?? false,
                        'note'      => $svc['note'] ?? '',
                    ];
                }
            }
            return ['status' => 'success', 'message' => 'Berhasil', 'is_demo' => false, 'data' => $formattedCosts];
        }

        // 401/403: API key belum aktif → tampilkan demo + pesan error jelas
        if (in_array($httpCode, [401, 403])) {
            log_message('error', "[OngkirService::cekOngkir] AUTH FAILED (HTTP {$httpCode}). Periksa: (1) API Key di .env, (2) Status Subscribe di dashboard Komerce, (3) Base URL.");
            $demo = $this->getDemoData($weight);
            $demo['is_demo'] = true;
            $demo['auth_error'] = $decoded['meta']['message'] ?? "HTTP {$httpCode} Unauthorized";
            return $demo;
        }

        $errorMsg = $decoded['meta']['message'] ?? $decoded['message'] ?? "Terjadi kesalahan: HTTP $httpCode";
        return [
            'status'  => 'error',
            'message' => $errorMsg,
            'data'    => []
        ];
    }

    /**
     * Data demo realistis (fallback saat API belum dikonfigurasi atau tidak tersedia)
     */
    private function getDemoData(float $weight): array
    {
        $baseRate = max(1, $weight);

        $services = [
            ['courier' => 'JNE', 'service' => 'OKE',  'desc' => 'Ongkos Kirim Ekonomis', 'price' => (int)(8000  * $baseRate), 'estimated' => '5-7'],
            ['courier' => 'JNE', 'service' => 'REG',  'desc' => 'Reguler',               'price' => (int)(11000 * $baseRate), 'estimated' => '2-3'],
            ['courier' => 'JNE', 'service' => 'YES',  'desc' => 'Yakin Esok Sampai',     'price' => (int)(20000 * $baseRate), 'estimated' => '1'],
            ['courier' => 'TIKI','service' => 'ECO',  'desc' => 'Economy',                'price' => (int)(9000  * $baseRate), 'estimated' => '5-7'],
            ['courier' => 'TIKI','service' => 'REG',  'desc' => 'Regular',                'price' => (int)(12000 * $baseRate), 'estimated' => '3-4'],
            ['courier' => 'POS', 'service' => 'Kilat Khusus', 'desc' => 'Pos Kilat Khusus', 'price' => (int)(8500  * $baseRate), 'estimated' => '3-5'],
        ];

        $data = array_map(function ($svc) {
            return [
                'courier'   => $svc['courier'],
                'service'   => $svc['service'],
                'desc'      => $svc['desc'],
                'price'     => $svc['price'],
                'estimated' => $svc['estimated'],
                'note'      => '',
            ];
        }, $services);

        return [
            'status'  => 'success',
            'message' => 'Data demo berhasil dimuat.',
            'is_demo' => true,
            'data'    => $data,
        ];
    }
}
