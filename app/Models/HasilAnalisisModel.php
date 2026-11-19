<?php
namespace App\Models;
use CodeIgniter\Model;

class HasilAnalisisModel extends Model
{
    protected $table            = 'hasil_analisis';
    protected $primaryKey       = 'id_analisis';
    protected $allowedFields    = [
        'id_laporan', 
        'val_etr', 'val_rpt_ratio', 
        'val_der', 'val_sales_growth'
    ];
    protected $useTimestamps    = true;
}