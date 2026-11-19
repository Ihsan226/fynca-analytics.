<?php

namespace App\Models;

use CodeIgniter\Model;

class SektorModel extends Model
{
    protected $table            = 'sektor';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'nama', 'kode', 'status', 'deskripsi',
        'enable_etr', 'enable_rpt', 'enable_der', 'enable_sales_growth',
        'special_rules'
    ];
    protected $useTimestamps    = true;
}
