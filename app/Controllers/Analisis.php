<?php
namespace App\Controllers;

use App\Libraries\TaxExplanationEngine;

class Analisis extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Build JOIN query
        $builder = $db->table('hasil_analisis');
        $builder->select('
            hasil_analisis.*, 
            laporan_keuangan.tahun,
            laporan_keuangan.laba_sebelum_pajak,
            laporan_keuangan.beban_pajak,
            laporan_keuangan.piutang_berelasi,
            laporan_keuangan.total_piutang,
            laporan_keuangan.total_hutang,
            laporan_keuangan.total_ekuitas,
            laporan_keuangan.penjualan_tahun_ini,
            laporan_keuangan.penjualan_tahun_lalu,
            perusahaan.nama_perusahaan, 
            perusahaan.kode_saham,
            perusahaan.sektor,
            perusahaan.has_related_party,
            perusahaan.has_rpt_transaction
        ');
        $builder->join('laporan_keuangan', 'laporan_keuangan.id_laporan = hasil_analisis.id_laporan');
        $builder->join('perusahaan', 'perusahaan.id_perusahaan = laporan_keuangan.id_perusahaan');
        
        $builder->orderBy('laporan_keuangan.tahun', 'DESC');
        $builder->orderBy('perusahaan.nama_perusahaan', 'ASC');
        
        $query = $builder->get();
        $results = $query->getResultArray();

        // Generate explanations for each result
        $engine = new TaxExplanationEngine();
        foreach ($results as &$row) {
            $row['explain_etr'] = $engine->explainETR((float)($row['val_etr'] ?? 0));
            $row['explain_rpt'] = $engine->explainRPT((float)($row['val_rpt_ratio'] ?? 0), $row);
            $row['explain_der'] = $engine->explainDER((float)($row['val_der'] ?? 0));
            $row['explain_growth'] = $engine->explainSalesGrowth(
                (float)($row['val_sales_growth'] ?? 0),
                $row
            );

            // Calculate risk score
            $scoreTotal = 0;
            $explanations = ['etr' => $row['explain_etr'], 'rpt' => $row['explain_rpt'], 'der' => $row['explain_der'], 'growth' => $row['explain_growth']];
            foreach ($explanations as $exp) {
                if (in_array($exp['level'], ['high', 'negative'])) {
                    $scoreTotal += 10;
                } elseif (in_array($exp['level'], ['low', 'positive', 'stable', 'normal'])) {
                    $scoreTotal += 25;
                } else {
                    $scoreTotal += 15;
                }
            }
            $row['risk_score'] = min(100, max(0, $scoreTotal));
            if ($row['risk_score'] >= 80) $row['risk_label'] = 'EXCELLENT';
            elseif ($row['risk_score'] >= 60) $row['risk_label'] = 'MODERATE';
            else $row['risk_label'] = 'HIGH RISK';
        }

        $data = [
            'hasil_analisis' => $results
        ];
        
        return view('analisis/index', $data);
    }

    /**
     * Per-company detail view with full analysis insight
     */
    public function detail($id)
    {
        $db = \Config\Database::connect();
        
        $row = $db->table('hasil_analisis')
            ->select('
                hasil_analisis.*, 
                laporan_keuangan.tahun,
                laporan_keuangan.laba_sebelum_pajak,
                laporan_keuangan.beban_pajak,
                laporan_keuangan.piutang_berelasi,
                laporan_keuangan.total_piutang,
                laporan_keuangan.total_hutang,
                laporan_keuangan.total_ekuitas,
                laporan_keuangan.penjualan_tahun_ini,
                laporan_keuangan.penjualan_tahun_lalu,
                perusahaan.nama_perusahaan, 
                perusahaan.kode_saham,
                perusahaan.sektor,
                perusahaan.has_related_party,
                perusahaan.has_rpt_transaction,
                perusahaan.has_interest_debt,
                perusahaan.is_public_company
            ')
            ->join('laporan_keuangan', 'laporan_keuangan.id_laporan = hasil_analisis.id_laporan')
            ->join('perusahaan', 'perusahaan.id_perusahaan = laporan_keuangan.id_perusahaan')
            ->where('hasil_analisis.id_analisis', $id)
            ->get()
            ->getRowArray();

        if (!$row) {
            return redirect()->to('/analisis')->with('error', 'Data analisis tidak ditemukan.');
        }

        $engine = new TaxExplanationEngine();
        
        $explanations = [
            'etr'    => $engine->explainETR((float)($row['val_etr'] ?? 0)),
            'rpt'    => $engine->explainRPT((float)($row['val_rpt_ratio'] ?? 0), $row),
            'der'    => $engine->explainDER((float)($row['val_der'] ?? 0)),
            'growth' => $engine->explainSalesGrowth((float)($row['val_sales_growth'] ?? 0), $row),
        ];

        $summary = $engine->generateSummary($row, $row, $explanations);

        // Separate attention and positive items
        $attentions = [];
        $positives  = [];
        $scoreTotal = 0;
        foreach ($explanations as $key => $exp) {
            if (in_array($exp['level'], ['high', 'negative'])) {
                $attentions[$key] = $exp;
                $scoreTotal += 10; // Bad score
            } elseif (in_array($exp['level'], ['low', 'positive', 'stable', 'normal'])) {
                $positives[$key] = $exp;
                $scoreTotal += 25; // Good score
            } else {
                $scoreTotal += 15; // Moderate score
            }
        }

        // Calculate risk score text
        $riskScore = min(100, max(0, $scoreTotal));
        if ($riskScore >= 80) $riskLabel = 'EXCELLENT';
        elseif ($riskScore >= 60) $riskLabel = 'MODERATE';
        else $riskLabel = 'HIGH RISK';

        // Get applicable regulations
        $regulasi = $db->table('regulasi_referensi')
            ->where('status', 'active')
            ->orderBy('indikator_terkait', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'row'          => $row,
            'explanations' => $explanations,
            'summary'      => $summary,
            'attentions'   => $attentions,
            'positives'    => $positives,
            'regulasi'     => $regulasi,
            'riskScore'    => $riskScore,
            'riskLabel'    => $riskLabel,
        ];
        
        return view('analisis/detail', $data);
    }
}