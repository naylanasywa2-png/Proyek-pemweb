<?php

namespace App\Services;

/**
 * OngkirService - Integrasi dengan Komerce Collaborator Shipping Cost API
 *
 * Base URL yang TERBUKTI BEKERJA (200 OK dari DiagnostikAPI):
 * https://api-sandbox.collaborator.komerce.id
 * Auth: x-api-key header
 */
class OngkirService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api-sandbox.collaborator.komerce.id/tariff/api/v1';

    public function __construct()
    {
        $this->apiKey = trim(env('KOMERCE_SHIPPING_API_KEY', ''));
    }

    /**
     * Cari destination ID berdasarkan keyword
     */
    public function searchDestination(string $keyword): array
    {
        $url = "{$this->baseUrl}/destination/search?keyword=" . urlencode($keyword);
        return $this->doGet($url);
    }

    /**
     * Hitung biaya ongkir
     */
    public function cekOngkir(
        int $shipperId,
        int $receiverId,
        float $weight = 1,
        int $itemValue = 50000,
        string $cod = 'no'
    ): array {
        // Fallback ke demo jika API key kosong
        if (empty($this->apiKey)) {
            log_message('warning', '[OngkirService] API key kosong, pakai demo data.');
            return $this->getDemoData($weight);
        }

        // Komerce menerima berat dalam GRAM (bukan kg)
        // Konversi: $weight (kg) → gram
        $beratGram = (int) round($weight * 1000);

        $params = http_build_query([
            'shipper_destination_id'  => $shipperId,
            'receiver_destination_id' => $receiverId,
            'weight'                  => $beratGram,   // ← dalam GRAM
            'item_value'              => $itemValue,
            'cod'                     => $cod,
        ]);

        $url = "{$this->baseUrl}/calculate?{$params}";

        log_message('debug', "[OngkirService::cekOngkir] URL: {$url}");
        log_message('debug', "[OngkirService::cekOngkir] API Key (8 char): " . substr($this->apiKey, 0, 8) . '...');

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER     => [
                'x-api-key: ' . $this->apiKey,
                'Accept: application/json',
            ],
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ]);

        $response = curl_exec($curl);
        $error    = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        log_message('debug', "[OngkirService::cekOngkir] HTTP Code: {$httpCode}");
        log_message('debug', "[OngkirService::cekOngkir] Response: {$response}");

        // cURL error → demo
        if ($error) {
            log_message('error', '[OngkirService::cekOngkir] cURL error: ' . $error);
            $demo = $this->getDemoData($weight);
            $demo['is_demo']     = true;
            $demo['debug_error'] = $error;
            return $demo;
        }

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', '[OngkirService::cekOngkir] JSON parse error. Raw: ' . $response);
            return ['status' => 'error', 'message' => 'Respons tidak valid dari server.', 'data' => []];
        }

        // ✅ Format Komerce: calculate_reguler + calculate_cargo
        if (
            isset($decoded['meta']['code']) &&
            (int) $decoded['meta']['code'] === 200 &&
            isset($decoded['data']['calculate_reguler'])
        ) {
            $formattedCosts = [];

            foreach ($decoded['data']['calculate_reguler'] as $item) {
                $formattedCosts[] = $this->formatItem($item);
            }

            foreach (($decoded['data']['calculate_cargo'] ?? []) as $item) {
                $row          = $this->formatItem($item);
                $row['note']  = 'Cargo';
                $formattedCosts[] = $row;
            }

            return [
                'status'  => 'success',
                'message' => 'Berhasil',
                'is_demo' => false,
                'data'    => $formattedCosts,
            ];
        }

        // ✅ Format flat array (fallback)
        if (
            isset($decoded['meta']['code']) &&
            (int) $decoded['meta']['code'] === 200 &&
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
                        'price'     => (int) ($svc['shipping_cost'] ?? $svc['price'] ?? $svc['cost'] ?? 0),
                        'price_net' => (int) ($svc['shipping_cost_net'] ?? 0),
                        'estimated' => $svc['etd'] ?? $svc['estimated'] ?? '-',
                        'is_cod'    => $svc['is_cod'] ?? false,
                        'note'      => $svc['note'] ?? '',
                    ];
                }
            }
            return ['status' => 'success', 'message' => 'Berhasil', 'is_demo' => false, 'data' => $formattedCosts];
        }

        // 401/403 → demo + pesan jelas
        if (in_array($httpCode, [401, 403])) {
            $errMsg = $decoded['meta']['message'] ?? "HTTP {$httpCode} Unauthorized";
            log_message('error', "[OngkirService::cekOngkir] AUTH FAILED: {$errMsg}");
            $demo = $this->getDemoData($weight);
            $demo['is_demo']    = true;
            $demo['auth_error'] = $errMsg;
            return $demo;
        }

        $errorMsg = $decoded['meta']['message'] ?? $decoded['message'] ?? "Terjadi kesalahan: HTTP $httpCode";
        return ['status' => 'error', 'message' => $errorMsg, 'data' => []];
    }

    // -------------------------------------------------------
    // Helper: format satu item tarif dari API
    // -------------------------------------------------------
    private function formatItem(array $item): array
    {
        return [
            'courier'   => strtoupper($item['shipping_name'] ?? 'UNKNOWN'),
            'service'   => $item['service_name']      ?? '-',
            'desc'      => $item['service_name']      ?? '-',
            'price'     => (int) ($item['shipping_cost']     ?? 0),
            'price_net' => (int) ($item['shipping_cost_net'] ?? 0),
            'estimated' => $item['etd']               ?? '-',
            'is_cod'    => $item['is_cod']            ?? false,
            'note'      => '',
        ];
    }

    // -------------------------------------------------------
    // Helper: GET request
    // -------------------------------------------------------
    private function doGet(string $url): array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER     => [
                'x-api-key: ' . $this->apiKey,
                'Accept: application/json',
            ],
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ]);

        $response = curl_exec($curl);
        $error    = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($error || $httpCode !== 200) {
            log_message('error', "[OngkirService::doGet] HTTP {$httpCode} | cURL: {$error} | URL: {$url}");
            return [];
        }

        $decoded = json_decode($response, true);
        return $decoded['data'] ?? [];
    }

    // -------------------------------------------------------
    // Demo data (fallback)
    // -------------------------------------------------------
    private function getDemoData(float $weight): array
    {
        $baseRate = max(1, $weight);

        $services = [
            ['courier' => 'JNE',     'service' => 'OKE',          'desc' => 'Ongkos Kirim Ekonomis',  'price' => (int)(8000  * $baseRate), 'estimated' => '5-7'],
            ['courier' => 'JNE',     'service' => 'REG',          'desc' => 'Reguler',                'price' => (int)(11000 * $baseRate), 'estimated' => '2-3'],
            ['courier' => 'JNE',     'service' => 'YES',          'desc' => 'Yakin Esok Sampai',      'price' => (int)(20000 * $baseRate), 'estimated' => '1'],
            ['courier' => 'JNT',     'service' => 'REG',          'desc' => 'J&T Regular',            'price' => (int)(10000 * $baseRate), 'estimated' => '2-3'],
            ['courier' => 'SICEPAT', 'service' => 'BEST',         'desc' => 'Best',                   'price' => (int)(9500  * $baseRate), 'estimated' => '2-3'],
            ['courier' => 'TIKI',    'service' => 'ECO',          'desc' => 'Economy',                'price' => (int)(9000  * $baseRate), 'estimated' => '5-7'],
            ['courier' => 'POS',     'service' => 'Kilat Khusus', 'desc' => 'Pos Kilat Khusus',       'price' => (int)(8500  * $baseRate), 'estimated' => '3-5'],
        ];

        $data = array_map(fn ($svc) => [
            'courier'   => $svc['courier'],
            'service'   => $svc['service'],
            'desc'      => $svc['desc'],
            'price'     => $svc['price'],
            'price_net' => 0,
            'estimated' => $svc['estimated'],
            'is_cod'    => false,
            'note'      => '',
        ], $services);

        return [
            'status'  => 'success',
            'message' => 'Data demo berhasil dimuat.',
            'is_demo' => true,
            'data'    => $data,
        ];
    }
}