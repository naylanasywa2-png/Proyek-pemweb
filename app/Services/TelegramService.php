<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Membuat tabel payments untuk sistem pembayaran.
 *
 * Fitur yang dicakup:
 * - Upload bukti transfer / QRIS
 * - Status konfirmasi oleh admin
 * - Metode bayar (transfer bank / QRIS)
 * - Relasi ke tabel orders
 */
class CreatePaymentsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id_payment' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_order' => [
                'type'     => 'INT',
                'unsigned' => false,
                'null'     => false,
                'comment'  => 'Relasi ke tabel orders',
            ],
            'metode_bayar' => [
                'type'       => 'ENUM',
                'constraint' => ['transfer_bank', 'qris', 'cod'],
                'default'    => 'transfer_bank',
            ],
            'nominal' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => false,
                'default'    => '0.00',
                'comment'    => 'Jumlah yang dibayarkan',
            ],
            'file_bukti' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'comment'    => 'Path file bukti pembayaran (relatif dari public/)',
            ],
            'catatan_user' => [
                'type' => 'TEXT',
                'null' => true,
                'default' => null,
                'comment' => 'Catatan dari user saat upload bukti',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['menunggu', 'dikonfirmasi', 'ditolak'],
                'default'    => 'menunggu',
                'comment'    => 'menunggu=belum dikonfirmasi admin, dikonfirmasi=lunas, ditolak=bukti tidak valid',
            ],
            'catatan_admin' => [
                'type' => 'TEXT',
                'null' => true,
                'default' => null,
                'comment' => 'Catatan admin saat konfirmasi atau tolak',
            ],
            'dikonfirmasi_oleh' => [
                'type'    => 'INT',
                'null'    => true,
                'default' => null,
                'comment' => 'id_user admin yang melakukan konfirmasi',
            ],
            'dikonfirmasi_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id_payment', true);
        $this->forge->addKey('id_order');
        $this->forge->addKey('status');

        $this->forge->createTable('payments', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('payments', true);
    }
}