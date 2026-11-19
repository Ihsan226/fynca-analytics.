<?php

namespace App\Models;

use CodeIgniter\Model;

class RawFinancialDataModel extends Model
{
    protected $table            = 'raw_financial_data';
    protected $primaryKey       = 'id_raw';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'id_perusahaan',
        'tahun',
        'source_file',
        'page',
        'label',
        'value',
        'unit',
        'source_type',
        'mapped_to',
        'created_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
