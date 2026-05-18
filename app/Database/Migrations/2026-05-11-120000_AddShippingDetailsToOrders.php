<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShippingDetailsToOrders extends Migration
{
    public function up(): void
    {
        // Tambah kolom detail pengiriman ke tabel orders
        $fields = [
            'kurir' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => null,
                'after'      => 'jumlah',
            ],
            'layanan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
                'after'      => 'kurir',
            ],
            'kota_tujuan' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'default'    => null,
                'after'      => 'layanan',
            ],
            'berat' => [
                'type'    => 'INT',
                'null'    => true,
                'default' => null,
                'comment' => 'dalam gram',
                'after'   => 'kota_tujuan',
            ],
        ];

        $this->forge->addColumn('orders', $fields);
    }

    public function down(): void
    {
        $this->forge->dropColumn('orders', ['kurir', 'layanan', 'kota_tujuan', 'berat']);
    }
}
