<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Membuat tabel vendors yang lengkap.
 *
 * Tabel ini menggantikan / melengkapi tabel vendor yang sudah ada
 * di yearbook.sql (yang hanya punya nama_vendor, kontak, alamat).
 *
 * Jalankan: php spark migrate
 */
class CreateVendorsTable extends Migration
{
    public function up(): void
    {
        // Cek apakah kolom sudah ada (agar migration aman dijalankan ulang)
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('vendor');

        // Tambah kolom baru ke tabel vendor yang sudah ada
        $newFields = [];

        if (! in_array('kota', $fields)) {
            $newFields['kota'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
                'after'      => 'alamat',
            ];
        }

        if (! in_array('harga_cetak', $fields)) {
            $newFields['harga_cetak'] = [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => false,
                'default'    => '80000.00',
                'comment'    => 'Harga cetak per unit (Rupiah)',
                'after'      => 'kota',
            ];
        }

        if (! in_array('is_aktif', $fields)) {
            $newFields['is_aktif'] = [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'null'    => false,
                'default' => 1,
                'comment' => '1=buka, 0=tutup',
                'after'   => 'harga_cetak',
            ];
        }

        if (! in_array('jam_buka', $fields)) {
            $newFields['jam_buka'] = [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => '08:00',
                'comment'    => 'Jam operasional mulai',
                'after'      => 'is_aktif',
            ];
        }

        if (! in_array('jam_tutup', $fields)) {
            $newFields['jam_tutup'] = [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => '17:00',
                'comment'    => 'Jam operasional selesai',
                'after'      => 'jam_buka',
            ];
        }

        if (! in_array('telegram_chat_id', $fields)) {
            $newFields['telegram_chat_id'] = [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => null,
                'comment'    => 'Chat ID Telegram vendor untuk notifikasi',
                'after'      => 'jam_tutup',
            ];
        }

        if (! in_array('updated_at', $fields)) {
            $newFields['updated_at'] = [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
                'after' => 'created_at',
            ];
        }

        if (! empty($newFields)) {
            $this->forge->addColumn('vendor', $newFields);
        }

        // Update data vendor awal agar punya kota
        $db->table('vendor')->where('id_vendor', 1)->update(['kota' => 'Surabaya']);
        $db->table('vendor')->where('id_vendor', 2)->update(['kota' => 'Jakarta']);
    }

    public function down(): void
    {
        $dropCols = ['kota', 'harga_cetak', 'is_aktif', 'jam_buka', 'jam_tutup', 'telegram_chat_id', 'updated_at'];
        $this->forge->dropColumn('vendor', $dropCols);
    }
}