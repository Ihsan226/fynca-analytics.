<?php

namespace App\Models;

use CodeIgniter\Model;

class FinanceGajiKomponenModel extends Model
{
    protected $table            = 'finance_gaji_komponen';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_user', 'gaji_pokok', 'tunjangan', 'potongan_bpjs', 'status_pernikahan'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get user along with their salary components
     */
    public function getUsersWithSalary()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users u');
        $builder->select('u.id_user, u.nama_lengkap, u.username, u.role,
                          g.gaji_pokok, g.tunjangan, g.potongan_bpjs, g.status_pernikahan');
        $builder->join('finance_gaji_komponen g', 'g.id_user = u.id_user', 'left');
        // Filter out admin and owner, usually only staff, analyst, and bendahara get payroll
        $builder->whereIn('u.role', ['staff', 'analyst', 'bendahara']);
        return $builder->get()->getResultArray();
    }
}
