<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'email'    => 'admin@gmail.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role'     => 'admin',
            ],
            [
                'email'    => 'user@gmail.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role'     => 'user',
            ],
        ];

        // Memasukkan data ke tabel users
        $this->db->table('users')->insertBatch($data);
    }
}