<?php
namespace App\Models;
use CodeIgniter\Model;

class LaporanKeuanganModel extends Model
{
    protected $table            = 'laporan_keuangan';
    protected $primaryKey       = 'id_laporan';
    protected $allowedFields    = [
        'id_perusahaan', 'tahun', 'prepared_by', 'status',
        'laba_sebelum_pajak', 'beban_pajak', 
        'piutang_berelasi', 'total_piutang', 
        'total_hutang', 'total_ekuitas', 
        'penjualan_tahun_ini', 'penjualan_tahun_lalu'
    ];
    protected $useTimestamps    = true;
}