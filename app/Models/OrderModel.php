<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id_order';
    protected $useAutoIncrement = true;
    
    // Kolom-kolom yang boleh diisi (Mass Assignment)
    protected $allowedFields    = [
        'id_user', 
        'id_desain', 
        'id_vendor', 
        'jumlah', 
        'total_bayar', 
        'ongkir', 
        'status_pesanan', 
        'bukti_bayar', 
        'file_desain',    // <-- Tambahin ini (Hasil kerja Desainer)
        'link_preview',   // <-- Tambahin ini (Link buat User intip)
        'catatan_revisi', // <-- Tambahin ini (Catatan dari User)
        'created_at'
    ];

    // Mengaktifkan fitur otomatis isi waktu dibuat
    protected $useTimestamps = false; // Karena kamu pakai manual created_at di migration
}