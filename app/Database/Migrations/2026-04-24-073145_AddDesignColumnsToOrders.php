<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDesignColumnsToOrders extends Migration
{
    public function up()
    {
        $fields = [
            'file_desain' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'status_pesanan'
            ],
            'link_preview' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'file_desain'
            ],
            'catatan_revisi' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'link_preview'
            ],
        ];
        $this->forge->addColumn('orders', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', ['file_desain', 'link_preview', 'catatan_revisi']);
    }
}