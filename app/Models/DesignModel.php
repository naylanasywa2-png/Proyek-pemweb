<?php

namespace App\Models;

use CodeIgniter\Model;

class DesignModel extends Model
{
    protected $table            = 'designs';
    protected $primaryKey       = 'id_desain';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_desainer', 'nama_desain', 'file_template', 'harga_desain', 'created_at'];
}