<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCatatanToOrders extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('orders', [
            'catatan' => [
                'type'       => 'TEXT',
                'null'       => true,
                'default'    => null,
                'after'      => 'status_pesanan',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('orders', 'catatan');
    }
}
