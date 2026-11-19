<?php
namespace App\Models;
use CodeIgniter\Model;

class AnalystReportModel extends Model
{
    protected $table            = 'analyst_reports';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_user', 'id_perusahaan', 'id_laporan', 'id_analisis',
        'tahun', 'judul', 'jenis_analisis',
        'analyst_insight', 'simulation_data', 'recommendation',
        'note_etr', 'note_rpt', 'note_der', 'note_growth',
        'status', 'reviewer_id', 'reviewer_comment', 'reviewed_at'
    ];
    protected $useTimestamps    = true;
}
