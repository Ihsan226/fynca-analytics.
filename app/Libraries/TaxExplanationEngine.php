<?php

namespace App\Libraries;

/**
 * TaxExplanationEngine
 * 
 * Generates human-readable explanations for each tax indicator result.
 * Results are analytical indicators, NOT final legal/tax decisions.
 * 
 * Disclaimer: Hasil yang dihasilkan merupakan analisis/indikasi dan alat bantu,
 * bukan keputusan hukum atau pajak final.
 */
class TaxExplanationEngine
{
    /**
     * Generate explanation for ETR (Effective Tax Rate)
     */
    public function explainETR(float $value, float $benchmark = 0.22): array
    {
        $percentage = $value * 100;
        $formatted  = number_format($percentage, 2, ',', '.') . '%';
        $benchPct   = $benchmark * 100;
        
        $deviation = $percentage - $benchPct;
        $devFormatted = number_format(abs($deviation), 2, ',', '.') . '%';

        if ($deviation <= -2) {
            return [
                'level'       => 'high',
                'label'       => 'Perlu Perhatian',
                'color'       => '#e11d48',
                'bg'          => '#fff1f2',
                'icon'        => '🔴',
                'formatted'   => $formatted,
                'explanation' => "ETR sebesar {$formatted} berada {$devFormatted} di bawah benchmark yang digunakan ({$benchPct}%). Terdapat deviasi yang cukup besar yang memerlukan perhatian lebih lanjut.",
                'deviation'   => $deviation
            ];
        } elseif ($deviation < 0) {
            return [
                'level'       => 'moderate',
                'label'       => 'Moderat',
                'color'       => '#d97706',
                'bg'          => '#fffbeb',
                'icon'        => '🟡',
                'formatted'   => $formatted,
                'explanation' => "ETR sebesar {$formatted} berada sedikit di bawah benchmark ({$benchPct}%). Selisih sebesar {$devFormatted} masih dalam batas wajar.",
                'deviation'   => $deviation
            ];
        } else {
            return [
                'level'       => 'low',
                'label'       => 'Normal',
                'color'       => '#16a34a',
                'bg'          => '#f0fdf4',
                'icon'        => '🟢',
                'formatted'   => $formatted,
                'explanation' => "ETR sebesar {$formatted} berada di atas atau sama dengan benchmark ({$benchPct}%). Kondisi ETR normal.",
                'deviation'   => $deviation
            ];
        }
    }

    /**
     * Generate explanation for RPT Ratio (Related Party Receivable Ratio)
     */
    public function explainRPT(float $value, ?array $perusahaan = null): array
    {
        $percentage = $value * 100;
        $formatted  = number_format($percentage, 2, ',', '.') . '%';

        // Check if RPT is applicable
        if ($perusahaan && empty($perusahaan['has_related_party'])) {
            return [
                'level'       => 'na',
                'label'       => 'N/A',
                'color'       => '#94a3b8',
                'bg'          => '#f8fafc',
                'icon'        => '—',
                'formatted'   => '—',
                'explanation' => 'Indikator ini tidak berlaku karena perusahaan tidak memiliki pihak berelasi.',
            ];
        }

        if ($value > 0.50) {
            return [
                'level'       => 'high',
                'label'       => 'Tinggi',
                'color'       => '#e11d48',
                'bg'          => '#fff1f2',
                'icon'        => '🔴',
                'formatted'   => $formatted,
                'explanation' => "{$formatted} dari total piutang merupakan piutang pihak berelasi. Proporsi ini tergolong tinggi dan perlu mendapat perhatian lebih dalam analisis.",
            ];
        } elseif ($value > 0.20) {
            return [
                'level'       => 'moderate',
                'label'       => 'Moderat',
                'color'       => '#d97706',
                'bg'          => '#fffbeb',
                'icon'        => '🟡',
                'formatted'   => $formatted,
                'explanation' => "{$formatted} dari total piutang merupakan piutang pihak berelasi. Proporsi ini masih dalam batas wajar namun sebaiknya diperhatikan.",
            ];
        } else {
            return [
                'level'       => 'low',
                'label'       => 'Rendah',
                'color'       => '#16a34a',
                'bg'          => '#f0fdf4',
                'icon'        => '🟢',
                'formatted'   => $formatted,
                'explanation' => "{$formatted} dari total piutang merupakan piutang pihak berelasi. Proporsi ini tergolong rendah.",
            ];
        }
    }

    /**
     * Generate explanation for DER (Debt-to-Equity Ratio)
     */
    public function explainDER(float $value, array $thresholds = []): array
    {
        $formatted = number_format($value, 2, ',', '.') . 'x';

        $lowMax  = $thresholds['low_max'] ?? 1.0;
        $highMin = $thresholds['high_min'] ?? 2.0;
        $taxRef  = $thresholds['tax_ref'] ?? 4.0;  // PMK 169/2015 batas 4:1

        if ($value > $highMin) {
            $level = 'high';
            $label = 'Tinggi';
            $color = '#e11d48';
            $bg    = '#fff1f2';
            $icon  = '🔴';
            $explanation = "DER sebesar {$formatted} menunjukkan proporsi utang yang signifikan terhadap ekuitas. Rasio ini berada pada kategori risiko analitik tinggi berdasarkan threshold yang dikonfigurasi.";
        } elseif ($value > $lowMax) {
            $level = 'moderate';
            $label = 'Moderat';
            $color = '#d97706';
            $bg    = '#fffbeb';
            $icon  = '🟡';
            $explanation = "DER sebesar {$formatted} menunjukkan utang lebih besar dari ekuitas. Rasio ini berada pada kategori risiko analitik moderat.";
        } else {
            $level = 'low';
            $label = 'Rendah';
            $color = '#16a34a';
            $bg    = '#f0fdf4';
            $icon  = '🟢';
            $explanation = "DER sebesar {$formatted} menunjukkan ekuitas masih lebih besar dari utang. Rasio ini berada pada kategori risiko analitik rendah.";
        }

        return [
            'level'       => $level,
            'label'       => $label,
            'color'       => $color,
            'bg'          => $bg,
            'icon'        => $icon,
            'formatted'   => $formatted,
            'explanation' => $explanation,
            'tax_ref'     => [
                'label'    => 'Referensi Regulasi Pajak',
                'value'    => number_format($taxRef, 0) . ':1',
                'source'   => 'PMK 169/PMK.010/2015',
                'note'     => 'Batas DER untuk tujuan penghitungan PPh. Perhitungan pajak menggunakan saldo rata-rata dengan ketentuan tersendiri.',
            ],
        ];
    }

    /**
     * Generate explanation for Sales Growth
     */
    public function explainSalesGrowth(float $value, array $data = []): array
    {
        $percentage = $value * 100;
        $formatted  = ($value >= 0 ? '+' : '') . number_format($percentage, 2, ',', '.') . '%';

        $selisih = '';
        if (!empty($data['penjualan_tahun_ini']) && !empty($data['penjualan_tahun_lalu'])) {
            $diff = $data['penjualan_tahun_ini'] - $data['penjualan_tahun_lalu'];
            $selisih = ' (' . ($diff >= 0 ? '+' : '') . 'Rp ' . number_format(abs($diff), 0, ',', '.') . ')';
        }

        if ($value > 0.10) {
            return [
                'level'       => 'positive',
                'label'       => 'Positif',
                'color'       => '#16a34a',
                'bg'          => '#f0fdf4',
                'icon'        => '🟢',
                'formatted'   => $formatted,
                'explanation' => "Penjualan meningkat {$formatted}{$selisih} dibandingkan periode sebelumnya. Pertumbuhan ini tergolong signifikan.",
            ];
        } elseif ($value >= 0) {
            return [
                'level'       => 'stable',
                'label'       => 'Stabil',
                'color'       => '#3b82f6',
                'bg'          => '#eff6ff',
                'icon'        => '🔵',
                'formatted'   => $formatted,
                'explanation' => "Penjualan relatif stabil dengan perubahan {$formatted}{$selisih} dibandingkan periode sebelumnya.",
            ];
        } else {
            return [
                'level'       => 'negative',
                'label'       => 'Negatif',
                'color'       => '#e11d48',
                'bg'          => '#fff1f2',
                'icon'        => '🔴',
                'formatted'   => $formatted,
                'explanation' => "Penjualan mengalami penurunan {$formatted}{$selisih} dibandingkan periode sebelumnya.",
            ];
        }
    }

    /**
     * Generate overall tax analytics summary for a company
     */
    public function generateSummary(array $analysis, array $perusahaan, array $explanations): string
    {
        $nama = $perusahaan['nama_perusahaan'] ?? 'Perusahaan';

        $parts = [];
        
        if (isset($explanations['etr'])) {
            $parts[] = "ETR sebesar " . $explanations['etr']['formatted'];
        }
        if (isset($explanations['rpt']) && $explanations['rpt']['level'] !== 'na') {
            $parts[] = "rasio piutang pihak berelasi sebesar " . $explanations['rpt']['formatted'];
        }
        if (isset($explanations['der'])) {
            $parts[] = "DER sebesar " . $explanations['der']['formatted'];
        }
        if (isset($explanations['growth'])) {
            $parts[] = "pertumbuhan penjualan " . $explanations['growth']['formatted'];
        }

        $summary = "{$nama} menunjukkan " . implode(', dengan ', $parts) . '.';

        // Add attention items
        $attentions = [];
        foreach ($explanations as $key => $exp) {
            if (in_array($exp['level'], ['high', 'negative'])) {
                $attentions[] = $exp;
            }
        }

        if (!empty($attentions)) {
            $summary .= ' Terdapat ' . count($attentions) . ' indikator yang memerlukan perhatian lebih lanjut.';
        }

        return $summary;
    }

    /**
     * Format a decimal as percentage string
     */
    private function pct(float $val): string
    {
        return number_format($val * 100, 0) . '%';
    }
}
