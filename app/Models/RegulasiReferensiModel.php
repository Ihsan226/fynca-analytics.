<?php

namespace App\Models;

use CodeIgniter\Model;

class RegulasiReferensiModel extends Model
{
    protected $table            = 'regulasi_referensi';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'kode_regulasi', 'judul', 'ringkasan', 'url',
        'indikator_terkait', 'status', 'berlaku_sejak', 'berlaku_hingga'
    ];
    protected $useTimestamps    = true;
}
