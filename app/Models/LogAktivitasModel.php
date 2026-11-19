<?php

namespace App\Models;

use CodeIgniter\Model;

class LogAktivitasModel extends Model
{
    protected $table            = 'log_aktivitas';
    protected $primaryKey       = 'id_log';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = ['user', 'aksi', 'detail', 'tipe', 'created_at'];

    // Kita tidak butuh updated_at untuk log, cukup insert timestamp saat insert
    protected $useTimestamps    = false; 
}
