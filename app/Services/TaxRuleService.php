<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use InvalidArgumentException;

class TaxRuleService
{
    private BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
    }

    /**
     * @return array{tax: float, gross_monthly: float, gross_annual: float, ptkp: float, pkp: float, rule_version: string}
     */
    public function calculatePph21(float $grossMonthly, string $maritalStatus = 'TK/0', ?int $year = null): array
    {
        if ($grossMonthly < 0) {
            throw new InvalidArgumentException('Penghasilan bruto tidak boleh negatif.');
        }

        $ptkp = [
            'TK/0' => 54000000,
            'K/0' => 58500000,
            'TK/1' => 58500000,
            'K/1' => 63000000,
            'TK/2' => 63000000,
            'K/2' => 67500000,
            'TK/3' => 67500000,
            'K/3' => 72000000,
        ][$maritalStatus] ?? 54000000;

        $grossAnnual = $grossMonthly * 12;
        $pkp = max(0, $grossAnnual - $ptkp);
        $annualTax = min($pkp, 60000000) * 0.05;
        if ($pkp > 60000000) {
            $annualTax += min($pkp - 60000000, 190000000) * 0.15;
        }
        if ($pkp > 250000000) {
            $annualTax += min($pkp - 250000000, 250000000) * 0.25;
        }
        if ($pkp > 500000000) {
            $annualTax += ($pkp - 500000000) * 0.30;
        }

        return [
            'tax' => $annualTax / 12,
            'gross_monthly' => $grossMonthly,
            'gross_annual' => $grossAnnual,
            'ptkp' => (float) $ptkp,
            'pkp' => (float) $pkp,
            'rule_version' => 'pph21-pasal17-' . ($year ?? date('Y')),
        ];
    }
}
