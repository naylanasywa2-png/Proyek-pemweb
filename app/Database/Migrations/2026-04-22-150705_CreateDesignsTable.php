<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDesignsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_desain'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_desainer'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nama_desain'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'file_template'  => ['type' => 'VARCHAR', 'constraint' => 255],
            
            // --- AWAL TAMBAHAN DARI VANTI ---
            'x_pos' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
            ],
            'y_pos' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
            ],
            'width_slot' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
            ],
            'height_slot' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
            ],
            // --- AKHIR TAMBAHAN ---

            'harga_desain'   => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_desain', true);
        $this->forge->createTable('designs');
    }

    public function down()
    {
        $this->forge->dropTable('designs');
    }
}