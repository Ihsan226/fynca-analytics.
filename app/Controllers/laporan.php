<?php
namespace App\Controllers;

use App\Models\PerusahaanModel;

class Laporan extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $perusahaanModel = new PerusahaanModel();
        
        // Ambil data untuk dropdown filter
        $data['perusahaan'] = $perusahaanModel->findAll();

        // Ambil filter dari query parameter
        $dari    = $this->request->getGet('dari');
        $sampai  = $this->request->getGet('sampai');
        $emiten  = $this->request->getGet('emiten');

        // Query JOIN untuk mengambil semua data (Mentah + Hasil Hitung)
        $builder = $db->table('hasil_analisis');
        $builder->select('hasil_analisis.*, laporan_keuangan.*, perusahaan.nama_perusahaan, perusahaan.kode_saham');
        $builder->join('laporan_keuangan', 'laporan_keuangan.id_laporan = hasil_analisis.id_laporan');
        $builder->join('perusahaan', 'perusahaan.id_perusahaan = laporan_keuangan.id_perusahaan');

        // Terapkan filter jika ada
        if (!empty($dari)) {
            $builder->where('laporan_keuangan.tahun >=', (int)$dari);
        }
        if (!empty($sampai)) {
            $builder->where('laporan_keuangan.tahun <=', (int)$sampai);
        }
        if (!empty($emiten)) {
            $builder->where('perusahaan.id_perusahaan', (int)$emiten);
        }

        $builder->orderBy('laporan_keuangan.tahun', 'DESC');
        
        $data['laporan_lengkap'] = $builder->get()->getResultArray();

        // Pass filter values back to view for pre-filling
        $data['filter_dari']   = $dari;
        $data['filter_sampai'] = $sampai;
        $data['filter_emiten'] = $emiten;

        return view('laporan/index', $data);
    }
}