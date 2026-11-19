<?php

namespace App\Libraries;

/**
 * DataValidator
 * 
 * Validates financial data completeness and quality before calculation.
 * Shows clear messages about which data is available/missing and which
 * indicators can be calculated.
 */
class DataValidator
{
    /**
     * Validate financial data for all indicators
     *
     * @param array      $data          Financial data fields
     * @param array|null $applicability Which indicators are applicable (from tax profile)
     * @return array     Validation results with checks, warnings, and calculable indicators
     */
    public function validateFinancialData(array $data, ?array $applicability = null): array
    {
        $checks = [];
        $warnings = [];
        $calculable = [];

        // ── ETR fields ──
        $hasLaba  = $this->fieldOk($data, 'laba_sebelum_pajak');
        $hasBeban = $this->fieldOk($data, 'beban_pajak');
        
        $checks[] = [
            'field'   => 'laba_sebelum_pajak',
            'label'   => 'Laba Sebelum Pajak',
            'group'   => 'ETR',
            'status'  => $hasLaba ? 'ok' : 'missing',
            'message' => $hasLaba ? 'Tersedia' : 'Belum tersedia',
        ];
        $checks[] = [
            'field'   => 'beban_pajak',
            'label'   => 'Beban Pajak',
            'group'   => 'ETR',
            'status'  => $hasBeban ? 'ok' : 'missing',
            'message' => $hasBeban ? 'Tersedia' : 'Belum tersedia',
        ];

        $etrApplicable = !$applicability || ($applicability['etr'] ?? true);
        if ($etrApplicable && $hasLaba && $hasBeban) {
            $calculable[] = 'etr';
            if (($data['laba_sebelum_pajak'] ?? 0) == 0) {
                $warnings[] = [
                    'indicator' => 'ETR',
                    'message'   => 'Laba Sebelum Pajak bernilai 0. ETR tidak dapat dihitung (division by zero).',
                    'severity'  => 'warning',
                ];
            }
        } elseif ($etrApplicable) {
            $warnings[] = [
                'indicator' => 'ETR',
                'message'   => 'ETR tidak dapat dihitung karena data belum lengkap.',
                'severity'  => 'error',
            ];
        }

        // ── RPT Ratio fields ──
        $hasPiutangBerelasi = $this->fieldOk($data, 'piutang_berelasi');
        $hasTotalPiutang    = $this->fieldOk($data, 'total_piutang');
        
        $checks[] = [
            'field'   => 'total_piutang',
            'label'   => 'Total Piutang',
            'group'   => 'RPT',
            'status'  => $hasTotalPiutang ? 'ok' : 'missing',
            'message' => $hasTotalPiutang ? 'Tersedia' : 'Belum tersedia',
        ];
        $checks[] = [
            'field'   => 'piutang_berelasi',
            'label'   => 'Piutang Pihak Berelasi',
            'group'   => 'RPT',
            'status'  => $hasPiutangBerelasi ? 'ok' : 'missing',
            'message' => $hasPiutangBerelasi ? 'Tersedia' : 'Belum tersedia',
        ];

        $rptApplicable = !$applicability || ($applicability['rpt'] ?? true);
        if ($rptApplicable && $hasPiutangBerelasi && $hasTotalPiutang) {
            $calculable[] = 'rpt';
            if (($data['total_piutang'] ?? 0) == 0) {
                $warnings[] = [
                    'indicator' => 'RPT Ratio',
                    'message'   => 'Total Piutang bernilai 0. RPT Ratio tidak dapat dihitung.',
                    'severity'  => 'warning',
                ];
            }
        }

        // ── DER fields ──
        $hasHutang   = $this->fieldOk($data, 'total_hutang');
        $hasEkuitas  = $this->fieldOk($data, 'total_ekuitas');
        
        $checks[] = [
            'field'   => 'total_hutang',
            'label'   => 'Total Hutang (Liabilitas)',
            'group'   => 'DER',
            'status'  => $hasHutang ? 'ok' : 'missing',
            'message' => $hasHutang ? 'Tersedia' : 'Belum tersedia',
        ];
        $checks[] = [
            'field'   => 'total_ekuitas',
            'label'   => 'Total Ekuitas',
            'group'   => 'DER',
            'status'  => $hasEkuitas ? 'ok' : 'missing',
            'message' => $hasEkuitas ? 'Tersedia' : 'Belum tersedia',
        ];

        $derApplicable = !$applicability || ($applicability['der'] ?? true);
        if ($derApplicable && $hasHutang && $hasEkuitas) {
            $calculable[] = 'der';
            if (($data['total_ekuitas'] ?? 0) == 0) {
                $warnings[] = [
                    'indicator' => 'DER',
                    'message'   => 'Total Ekuitas bernilai 0. DER tidak dapat dihitung.',
                    'severity'  => 'warning',
                ];
            }
        }

        // ── Sales Growth fields ──
        $hasSalesIni  = $this->fieldOk($data, 'penjualan_tahun_ini');
        $hasSalesLalu = $this->fieldOk($data, 'penjualan_tahun_lalu');
        
        $checks[] = [
            'field'   => 'penjualan_tahun_ini',
            'label'   => 'Penjualan Tahun Berjalan',
            'group'   => 'Sales Growth',
            'status'  => $hasSalesIni ? 'ok' : 'missing',
            'message' => $hasSalesIni ? 'Tersedia' : 'Belum tersedia',
        ];
        $checks[] = [
            'field'   => 'penjualan_tahun_lalu',
            'label'   => 'Penjualan Tahun Sebelumnya',
            'group'   => 'Sales Growth',
            'status'  => $hasSalesLalu ? 'ok' : 'missing',
            'message' => $hasSalesLalu ? 'Tersedia' : 'Belum tersedia',
        ];

        $growthApplicable = !$applicability || ($applicability['growth'] ?? true);
        if ($growthApplicable && $hasSalesIni && $hasSalesLalu) {
            $calculable[] = 'sales_growth';
            if (($data['penjualan_tahun_lalu'] ?? 0) == 0) {
                $warnings[] = [
                    'indicator' => 'Sales Growth',
                    'message'   => 'Penjualan Tahun Sebelumnya bernilai 0. Sales Growth tidak dapat dihitung.',
                    'severity'  => 'warning',
                ];
            }
        } elseif ($growthApplicable && $hasSalesIni && !$hasSalesLalu) {
            $warnings[] = [
                'indicator' => 'Sales Growth',
                'message'   => 'Sales Growth tidak dapat dihitung karena data penjualan tahun sebelumnya belum tersedia.',
                'severity'  => 'error',
            ];
        }

        $okCount   = count(array_filter($checks, fn($c) => $c['status'] === 'ok'));
        $totalReq  = count($checks);
        $allValid  = $okCount === $totalReq && empty($warnings);

        return [
            'valid'       => $allValid,
            'checks'      => $checks,
            'warnings'    => $warnings,
            'calculable'  => $calculable,
            'ok_count'    => $okCount,
            'total_count' => $totalReq,
            'status_text' => $allValid ? 'Data siap dihitung' : 'Data tidak lengkap',
            'status_icon' => $allValid ? '✓' : '⚠',
        ];
    }

    /**
     * Check if a field exists and is not null
     */
    private function fieldOk(array $data, string $field): bool
    {
        return isset($data[$field]) && $data[$field] !== '' && $data[$field] !== null;
    }
}
