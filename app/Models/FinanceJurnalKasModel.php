<?php

namespace App\Models;

use CodeIgniter\Model;

class FinanceJurnalKasModel extends Model
{
    protected $table            = 'finance_jurnal_kas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal', 'tipe', 'nominal', 
        'deskripsi', 'referensi_tipe', 'referensi_id'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getBalance()
    {
        $db = \Config\Database::connect();
        // Sum debit, sum kredit
        $query = $db->query("SELECT 
            SUM(CASE WHEN tipe = 'debit' THEN nominal ELSE 0 END) as total_debit,
            SUM(CASE WHEN tipe = 'kredit' THEN nominal ELSE 0 END) as total_kredit
        FROM finance_jurnal_kas")->getRow();
        
        $debit = $query->total_debit ?? 0;
        $kredit = $query->total_kredit ?? 0;
        
        // Asumsi Debit = Uang masuk, Kredit = Uang keluar (atau sebaliknya tergantung preferensi akuntansi, umumnya Kas bertambah di Debit)
        return $debit - $kredit;
    }
}
