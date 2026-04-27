<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_order'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_user'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_desain'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_vendor'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jumlah'         => ['type' => 'INT', 'constraint' => 5],
            'total_bayar'    => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'ongkir'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'status_pesanan' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'dibayar', 'proses_desain', 'cetak', 'dikirim', 'selesai'],
                'default'    => 'pending'
            ],
            'bukti_bayar'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_order', true);
        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}