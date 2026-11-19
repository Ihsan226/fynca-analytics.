<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Field yang diizinkan untuk diisi secara manual
    protected $allowedFields    = ['id_organization', 'nama_lengkap', 'username', 'password', 'role', 'avatar'];

    // Aktifkan timestamp otomatis untuk created_at & updated_at
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