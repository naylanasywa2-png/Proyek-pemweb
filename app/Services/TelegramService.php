<?php

namespace App\Services;

/**
 * TelegramService
 * Mengirim notifikasi ke vendor via Telegram Bot API.
 *
 * Konfigurasi di .env:
 *   telegram.botToken = 123456:ABCdefGHIjklMNOpqrSTUvwxYZ
 */
class TelegramService
{
    protected string $botToken;
    protected string $apiUrl;

    public function __construct()
    {
        $this->botToken = trim(env('telegram.botToken', ''));
        $this->apiUrl   = "https://api.telegram.org/bot{$this->botToken}/";
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Kirim pesan teks biasa
    // -------------------------------------------------------------------------

    /**
     * Kirim pesan HTML ke chat ID tertentu.
     *
     * @param  string $chatId  Chat ID tujuan (user atau grup)
     * @param  string $message Isi pesan (HTML mode)
     * @return array           Respon dari Telegram API
     */
    public function sendMessage(string $chatId, string $message): array
    {
        if (empty($this->botToken) || $this->botToken === 'YOUR_BOT_TOKEN_HERE') {
            log_message('info', '[TelegramService] Bot token belum diset, pesan dilewati.');
            return ['ok' => false, 'description' => 'Bot token not configured.'];
        }

        return $this->post('sendMessage', [
            'chat_id'    => $chatId,
            'text'       => $message,
            'parse_mode' => 'HTML',
        ]);
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Notifikasi pembayaran dikonfirmasi → vendor
    // -------------------------------------------------------------------------

    /**
     * Kirim notifikasi ke vendor bahwa pembayaran pesanan sudah dikonfirmasi admin.
     * Vendor diminta segera memproses cetakan.
     *
     * @param  array  $order   Data baris dari tabel orders
     * @param  string $chatId  Telegram Chat ID vendor
     */
    public function kirimNotifikasiPembayaranDikonfirmasi(array $order, string $chatId): array
    {
        $idOrder    = $order['id_order']   ?? '-';
        $tujuan     = $order['kota_tujuan'] ?? '-';
        $kurir      = strtoupper($order['kurir']    ?? '-');
        $layanan    = $order['layanan']    ?? '-';
        $total      = 'Rp ' . number_format($order['total_bayar'] ?? 0, 0, ',', '.');
        $tanggal    = date('d M Y H:i');

        $pesan = "🎉 <b>PEMBAYARAN DIKONFIRMASI!</b>\n\n"
               . "📦 <b>Detail Pesanan #ORD-{$idOrder}</b>\n"
               . "────────────────────\n"
               . "🏙️ Tujuan Kirim  : {$tujuan}\n"
               . "🚚 Kurir          : {$kurir} {$layanan}\n"
               . "💰 Total Bayar    : {$total}\n"
               . "────────────────────\n"
               . "✅ Pembayaran telah dikonfirmasi pada {$tanggal}\n\n"
               . "⚡ Mohon segera <b>proses cetak dan pengiriman</b>.\n"
               . "Terima kasih! 🌸";

        return $this->sendMessage($chatId, $pesan);
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Notifikasi pesanan baru masuk → vendor
    // -------------------------------------------------------------------------

    /**
     * Kirim notifikasi ke vendor bahwa ada pesanan baru yang masuk.
     *
     * @param  array  $order   Data baris dari tabel orders
     * @param  string $chatId  Telegram Chat ID vendor
     */
    public function kirimNotifikasiPesanan(array $order, string $chatId): array
    {
        $idOrder = $order['id_order']    ?? '-';
        $tujuan  = $order['kota_tujuan'] ?? '-';
        $kurir   = strtoupper($order['kurir']   ?? '-');
        $layanan = $order['layanan']   ?? '-';
        $berat   = number_format($order['berat'] ?? 0, 0, ',', '.') . ' gr';
        $ongkir  = 'Rp ' . number_format($order['ongkir']     ?? 0, 0, ',', '.');
        $total   = 'Rp ' . number_format($order['total_bayar'] ?? 0, 0, ',', '.');
        $tanggal = date('d M Y H:i');

        $pesan = "🆕 <b>PESANAN BARU MASUK!</b>\n\n"
               . "📦 <b>Pesanan #ORD-{$idOrder}</b>\n"
               . "────────────────────\n"
               . "🏙️ Tujuan Kirim  : {$tujuan}\n"
               . "🚚 Kurir          : {$kurir} {$layanan}\n"
               . "⚖️  Berat Paket   : {$berat}\n"
               . "📮 Ongkir         : {$ongkir}\n"
               . "💰 Total Bayar    : {$total}\n"
               . "────────────────────\n"
               . "📅 Dipesan pada   : {$tanggal}\n\n"
               . "⏳ Menunggu konfirmasi pembayaran dari customer.\n"
               . "Terima kasih! 🌸";

        return $this->sendMessage($chatId, $pesan);
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Notifikasi pesanan ditolak/batal → vendor
    // -------------------------------------------------------------------------

    /**
     * Kirim notifikasi ke vendor bahwa pembayaran ditolak.
     *
     * @param  array  $order   Data baris dari tabel orders
     * @param  string $chatId  Telegram Chat ID vendor
     * @param  string $alasan  Alasan penolakan
     */
    public function kirimNotifikasiTolak(array $order, string $chatId, string $alasan = '-'): array
    {
        $idOrder = $order['id_order'] ?? '-';

        $pesan = "❌ <b>PEMBAYARAN DITOLAK</b>\n\n"
               . "Pesanan #ORD-{$idOrder} pembayarannya ditolak admin.\n"
               . "Alasan: {$alasan}\n\n"
               . "Customer akan upload ulang bukti pembayaran.";

        return $this->sendMessage($chatId, $pesan);
    }

    // -------------------------------------------------------------------------
    // PRIVATE: HTTP POST via cURL ke Telegram API
    // -------------------------------------------------------------------------

    private function post(string $method, array $data): array
    {
        $url = $this->apiUrl . $method;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($error) {
            log_message('error', "[TelegramService] cURL error: {$error}");
            return ['ok' => false, 'description' => $error];
        }

        $result = json_decode($response, true);
        if (! ($result['ok'] ?? false)) {
            log_message('warning', '[TelegramService] API error: ' . ($result['description'] ?? 'unknown'));
        }

        return $result ?? ['ok' => false, 'description' => 'Invalid JSON response'];
    }
}