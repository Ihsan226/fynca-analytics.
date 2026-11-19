<?php

namespace App\Models;

use CodeIgniter\Model;

class FinancePayrollModel extends Model
{
    protected $table            = 'finance_payroll';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user', 'periode_bulan', 'periode_tahun', 
        'gaji_pokok', 'tunjangan', 'bonus', 
        'potongan_bpjs', 'potongan_lain', 'potongan_pph21', 'total_bersih',
        'tax_gross_monthly', 'tax_gross_annual', 'tax_ptkp', 'tax_pkp', 'tax_rule_version',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
