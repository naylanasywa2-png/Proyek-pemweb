<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users'; // Menunjuk ke tabel users
    protected $primaryKey       = 'id_user'; // Kunci utamanya
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Kolom mana saja yang boleh diisi (PENTING!)
    protected $allowedFields    = ['email', 'password', 'role'];

    // Otomatis mencatat waktu buat/update
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; // kita kosongkan dulu karena di tabel belum ada updated_at
}