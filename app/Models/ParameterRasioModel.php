<?php

namespace App\Models;

use CodeIgniter\Model;

class ParameterRasioModel extends Model
{
    protected $table            = 'parameter_rasio';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'nama', 'variabel', 'batas_bawah', 'batas_atas', 'keterangan', 'warna',
        'tipe', 'regulasi_ref', 'regulasi_url', 'berlaku_sejak', 'berlaku_hingga'
    ];
    protected $useTimestamps    = true;
}
