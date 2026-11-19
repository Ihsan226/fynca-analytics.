<?php
namespace App\Models;
use CodeIgniter\Model;

class PerusahaanModel extends Model
{
    protected $table            = 'perusahaan';
    protected $primaryKey       = 'id_perusahaan';
    protected $allowedFields    = [
        'id_organization', 'assigned_analyst', 'kode_saham', 'nama_perusahaan', 'sektor', 'tahun_ipo',
        'has_related_party', 'has_rpt_transaction', 'has_cross_border',
        'has_interest_debt', 'is_public_company'
    ];
    protected $useTimestamps    = true;

    protected $beforeFind       = ['filterByOrganization'];

    protected function filterByOrganization(array $data)
    {
        if (session()->has('id_organization')) {
            // Apply where clause to builder
            $this->builder()->where('id_organization', session()->get('id_organization'));
        }
        return $data;
    }
}