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
        $this->forge->addField([
            'id_vendor' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_vendor' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'kontak' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kota' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
            ],
            'harga_cetak' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => false,
                'default'    => '80000.00',
                'comment'    => 'Harga cetak per unit (Rupiah)',
            ],
            'is_aktif' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
                'comment'    => '1=buka, 0=tutup',
            ],
            'jam_buka' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => '08:00',
                'comment'    => 'Jam operasional mulai',
            ],
            'jam_tutup' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => '17:00',
                'comment'    => 'Jam operasional selesai',
            ],
            'telegram_chat_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => null,
                'comment'    => 'Chat ID Telegram vendor untuk notifikasi',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id_vendor', true);
        
        // Kita gunakan nama tabel 'vendors' (plural) untuk konsistensi dengan nama kelas
        $this->forge->createTable('vendors', true);

        // Insert default data jika diperlukan
        $db = \Config\Database::connect();
        $db->table('vendors')->insertBatch([
            [
                'nama_vendor' => 'Percetakan Surabaya',
                'kota'        => 'Surabaya',
                'created_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama_vendor' => 'Percetakan Jakarta',
                'kota'        => 'Jakarta',
                'created_at'  => date('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function down(): void
    {
        $this->forge->dropTable('vendors', true);
    }
}