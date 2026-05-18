<?php

namespace App\Controllers;

class DiagnostikAPI extends BaseController
{
    public function index()
    {
        // =========================================================
        // BAGIAN 1: Validasi API Key dari .env
        // =========================================================
        $apiKeyRaw = env('KOMERCE_SHIPPING_API_KEY', '');
        $apiKeyTrim = trim($apiKeyRaw);

        echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8">
              <title>Diagnostik API Komerce</title>
              <style>
                body { font-family: monospace; max-width: 900px; margin: 40px auto; padding: 0 20px; background: #f5f5f5; }
                h1 { color: #333; } h3 { margin-bottom: 5px; }
                .box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
                .ok { color: green; font-weight: bold; }
                .err { color: red; font-weight: bold; }
                .warn { color: orange; font-weight: bold; }
                pre { background: #eee; padding: 10px; border-radius: 4px; overflow: auto; white-space: pre-wrap; }
                table { width:100%; border-collapse: collapse; }
                td, th { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
                th { background: #333; color: white; }
                tr:nth-child(even) { background: #f9f9f9; }
              </style></head><body>';

        echo '<h1>🔍 Diagnostik API Komerce</h1>';

        // --- Cek API Key ---
        echo '<div class="box">';
        echo '<h3>📋 Bagian 1: Validasi API Key dari .env</h3>';
        echo '<table>';
        echo '<tr><th>Item</th><th>Nilai</th><th>Status</th></tr>';
        echo '<tr><td>Raw (dari env())</td><td>[' . htmlspecialchars($apiKeyRaw) . ']</td><td>' . (empty($apiKeyRaw) ? '<span class="err">❌ KOSONG!</span>' : '<span class="ok">✅ Ada</span>') . '</td></tr>';
        echo '<tr><td>Setelah trim()</td><td>[' . htmlspecialchars($apiKeyTrim) . ']</td><td>' . ($apiKeyRaw !== $apiKeyTrim ? '<span class="err">⚠️ ADA WHITESPACE!</span>' : '<span class="ok">✅ Bersih</span>') . '</td></tr>';
        echo '<tr><td>Panjang (raw)</td><td>' . strlen($apiKeyRaw) . ' karakter</td><td>' . (strlen($apiKeyRaw) > 20 ? '<span class="ok">✅ Normal</span>' : '<span class="err">❌ Terlalu pendek</span>') . '</td></tr>';
        echo '<tr><td>Panjang (setelah trim)</td><td>' . strlen($apiKeyTrim) . ' karakter</td><td>-</td></tr>';
        echo '</table>';
        echo '</div>';

        // =========================================================
        // BAGIAN 2: Test semua kombinasi URL + Header
        // =========================================================
        echo '<div class="box">';
        echo '<h3>🌐 Bagian 2: Test Kombinasi URL & Header</h3>';

        $params = http_build_query([
            'shipper_destination_id'  => 12043,
            'receiver_destination_id' => 4982,
            'weight'    => 1,
            'item_value'=> 50000,
            'cod'       => 'no',
        ]);

        $searchParams = 'keyword=surabaya';

        $kombinasi = [
            [
                'label'  => 'Collaborator (URL lama) + x-api-key',
                'url'    => 'https://api.collaborator.komerce.my.id/tariff/api/v1/destination/search?' . $searchParams,
                'header' => ['x-api-key: ' . $apiKeyTrim, 'Accept: application/json'],
            ],
            [
                'label'  => 'Collaborator (URL lama) + Authorization Bearer',
                'url'    => 'https://api.collaborator.komerce.my.id/tariff/api/v1/destination/search?' . $searchParams,
                'header' => ['Authorization: Bearer ' . $apiKeyTrim, 'Accept: application/json'],
            ],
            [
                'label'  => 'api-sandbox.collaborator.komerce.id + x-api-key',
                'url'    => 'https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search?' . $searchParams,
                'header' => ['x-api-key: ' . $apiKeyTrim, 'Accept: application/json'],
            ],
            [
                'label'  => 'api-sandbox.collaborator.komerce.id + Bearer',
                'url'    => 'https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search?' . $searchParams,
                'header' => ['Authorization: Bearer ' . $apiKeyTrim, 'Accept: application/json'],
            ],
            [
                'label'  => 'api.collaborator.komerce.id + x-api-key',
                'url'    => 'https://api.collaborator.komerce.id/tariff/api/v1/destination/search?' . $searchParams,
                'header' => ['x-api-key: ' . $apiKeyTrim, 'Accept: application/json'],
            ],
            [
                'label'  => 'api.komerce.id (sandbox path) + x-api-key',
                'url'    => 'https://api.komerce.id/sandbox/tariff/api/v1/destination/search?' . $searchParams,
                'header' => ['x-api-key: ' . $apiKeyTrim, 'Accept: application/json'],
            ],
        ];

        echo '<table>';
        echo '<tr><th>#</th><th>Label</th><th>HTTP Code</th><th>Status</th><th>Response (100 char)</th></tr>';

        $berhasil = null;
        foreach ($kombinasi as $i => $k) {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $k['url'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => $k['header'],
                CURLOPT_TIMEOUT        => 8,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
            ]);
            $resp    = curl_exec($ch);
            $code    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlErr = curl_error($ch);
            curl_close($ch);

            $statusLabel = match(true) {
                (bool)$curlErr            => '<span class="err">❌ cURL Error</span>',
                $code === 200             => '<span class="ok">✅ BERHASIL!</span>',
                $code === 401             => '<span class="err">🔐 401 Invalid Key</span>',
                $code === 403             => '<span class="err">🚫 403 Forbidden</span>',
                $code === 404             => '<span class="warn">🔍 404 Not Found</span>',
                $code === 0               => '<span class="err">⚠️ No Response</span>',
                default                   => "<span class=\"warn\">⚠️ HTTP $code</span>",
            };

            $preview = $curlErr ?: htmlspecialchars(substr(str_replace(["\r","\n"], '', $resp), 0, 100));

            echo "<tr>";
            echo "<td>" . ($i+1) . "</td>";
            echo "<td>" . htmlspecialchars($k['label']) . "</td>";
            echo "<td><b>$code</b></td>";
            echo "<td>$statusLabel</td>";
            echo "<td style='font-size:0.8em'>$preview</td>";
            echo "</tr>";

            if ($code === 200 && !$berhasil) {
                $berhasil = $k;
            }
        }

        echo '</table>';
        echo '</div>';

        // =========================================================
        // BAGIAN 3: Kesimpulan & Rekomendasi
        // =========================================================
        echo '<div class="box">';
        echo '<h3>💡 Bagian 3: Kesimpulan</h3>';

        if ($berhasil) {
            echo '<p class="ok">✅ Kombinasi yang berhasil ditemukan!</p>';
            echo '<p>Gunakan konfigurasi ini di OngkirService.php:</p>';
            echo '<pre>protected string $baseUrl = \'' . rtrim(preg_replace('/\/destination\/search.*/', '', $berhasil['url']), '/') . '\';</pre>';
        } else {
            echo '<p class="err">❌ Tidak ada kombinasi yang berhasil (semua 401/error).</p>';
            echo '<p>Kemungkinan penyebab:</p>';
            echo '<ul>';
            echo '<li><b>API Key belum aktif</b> — Cek dashboard Komerce: Developer → API Key → pastikan status "Active"</li>';
            echo '<li><b>Perlu subscribe layanan Shipping</b> — Di dashboard Komerce, cek menu "Langganan" atau "Subscribe"</li>';
            echo '<li><b>API Key terbatas fitur</b> — Shipping Cost API mungkin memerlukan paket berbeda dari Payment API</li>';
            echo '</ul>';
            echo '<p>Sementara itu, halaman Anda tetap berjalan dengan data <b>Demo</b>.</p>';
        }
        echo '</div>';
        echo '</body></html>';
    }
}
