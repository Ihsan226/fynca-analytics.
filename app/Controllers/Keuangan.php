<?php
namespace App\Controllers;

use App\Models\LaporanKeuanganModel;
use App\Models\HasilAnalisisModel;
use App\Models\PerusahaanModel;
use App\Libraries\DataValidator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Keuangan extends BaseController
{
    protected $laporanModel;
    protected $analisisModel;
    protected $perusahaanModel;
    protected $rawModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanKeuanganModel();
        $this->analisisModel = new HasilAnalisisModel();
        $this->perusahaanModel = new PerusahaanModel();
        $this->rawModel = new \App\Models\RawFinancialDataModel();
    }

    // =========================================================
    // 1. TAMPILAN DATA & FORM
    // =========================================================

    public function index()
    {
        $laporanQuery = $this->laporanModel
            ->select('laporan_keuangan.*, perusahaan.nama_perusahaan, perusahaan.assigned_analyst, users.nama_lengkap as prepared_by_name, report_workflows.analyst_status, report_workflows.accounting_status')
            ->join('perusahaan', 'perusahaan.id_perusahaan = laporan_keuangan.id_perusahaan')
            ->join('users', 'users.id_user = laporan_keuangan.prepared_by', 'left')
            ->join('report_workflows', 'report_workflows.id_laporan = laporan_keuangan.id_laporan', 'left');

        $laporan = $laporanQuery->orderBy('perusahaan.nama_perusahaan', 'ASC')
                                ->orderBy('laporan_keuangan.tahun', 'DESC')
                                ->findAll();

        return view('keuangan/index', ['laporan' => $laporan]);
    }

    public function create()
    {
        if (session('role') === 'owner') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Owner hanya memiliki hak akses Read-Only.');
        }

        if (session('role') === 'analyst') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Analyst tidak bertugas menginput data mentah.');
        }

        $perusahaanQuery = $this->perusahaanModel;

        $data = [
            'perusahaan' => $perusahaanQuery->findAll()
        ];
        return view('keuangan/create', $data);
    }

    public function edit($id)
    {
        if (session('role') === 'owner') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Owner hanya memiliki hak akses Read-Only.');
        }

        $laporan = $this->laporanModel
            ->select('laporan_keuangan.*, perusahaan.assigned_analyst')
            ->join('perusahaan', 'perusahaan.id_perusahaan = laporan_keuangan.id_perusahaan')
            ->where('id_laporan', $id)->first();

        if (!$laporan) {
            return redirect()->to('/keuangan')->with('error', 'Data keuangan tidak ditemukan.');
        }

        if (session('role') === 'analyst') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Analyst tidak memiliki hak akses untuk mengubah data mentah/sumber.');
        }

        $data = [
            'laporan' => $laporan
        ];

        return view('keuangan/edit', $data);
    }

    // =========================================================
    // 2. CALCULATION ENGINE — NEW DATA (STORE)
    // =========================================================

    public function store()
    {
        if (session('role') === 'owner') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Owner hanya memiliki hak akses Read-Only.');
        }

        if (session('role') === 'analyst') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Analyst tidak bertugas menginput data mentah.');
        }

        $dataLaporan = [
            'id_perusahaan'        => $this->request->getPost('id_perusahaan'),
            'tahun'                => $this->request->getPost('tahun'),
            'prepared_by'          => session('id_user'),
            'status'               => 'Ready for Analysis', // Kita set default ready setelah diinput
            'laba_sebelum_pajak'   => (float)($this->request->getPost('profit_before_tax') ?? 0),
            'beban_pajak'          => (float)($this->request->getPost('tax_expense') ?? 0),
            'piutang_berelasi'     => (float)($this->request->getPost('related_party_receivables') ?? 0),
            'total_piutang'        => (float)($this->request->getPost('total_receivables') ?? 0),
            'total_hutang'         => (float)($this->request->getPost('total_liabilities') ?? 0),
            'total_ekuitas'        => (float)($this->request->getPost('total_equity') ?? 0),
            'penjualan_tahun_ini'  => (float)($this->request->getPost('revenue_current') ?? 0),
            'penjualan_tahun_lalu' => (float)($this->request->getPost('revenue_previous') ?? 0)
        ];

        // ── Data Validation ──
        $validator = new DataValidator();
        $validation = $validator->validateFinancialData($dataLaporan);

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $this->laporanModel->insert($dataLaporan);
            $id_laporan = $this->laporanModel->getInsertID(); 
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Data tahun ini untuk perusahaan tersebut sudah ada!');
        }

        // =========================================================
        // CALCULATION ENGINE (Rasio Analis)
        // =========================================================
        $val_etr = ($dataLaporan['laba_sebelum_pajak'] != 0) ? ($dataLaporan['beban_pajak'] / $dataLaporan['laba_sebelum_pajak']) : null;
        $val_rpt_ratio = ($dataLaporan['total_piutang'] != 0) ? ($dataLaporan['piutang_berelasi'] / $dataLaporan['total_piutang']) : null;
        $val_der = ($dataLaporan['total_ekuitas'] != 0) ? ($dataLaporan['total_hutang'] / $dataLaporan['total_ekuitas']) : null;
        $val_sales_growth = ($dataLaporan['penjualan_tahun_lalu'] != 0) ? (($dataLaporan['penjualan_tahun_ini'] - $dataLaporan['penjualan_tahun_lalu']) / $dataLaporan['penjualan_tahun_lalu']) : null;

        $dataAnalisis = [
            'id_laporan'       => $id_laporan,
            'val_etr'          => $val_etr,
            'val_rpt_ratio'    => $val_rpt_ratio,
            'val_der'          => $val_der,
            'val_sales_growth' => $val_sales_growth
        ];
        $this->analisisModel->insert($dataAnalisis);

        // =========================================================
        // INTEGRASI JURNAL AKUNTANSI & AUTO-DETECT PAJAK
        // =========================================================
        
        // 1. Ambil data mentah yang di-extract
        $id_perusahaan = $dataLaporan['id_perusahaan'];
        $tahun = $dataLaporan['tahun'];
        $rawData = $this->rawModel->where('id_perusahaan', $id_perusahaan)->where('tahun', $tahun)->findAll();

        if (!empty($rawData)) {
            // Setup COA (Chart of Accounts) dasar jika belum ada
            $coaQuery = $db->table('acc_coa');
            $getCoaId = function($kode, $nama, $tipe, $saldo) use ($coaQuery) {
                $row = $coaQuery->where('kode_akun', $kode)->get()->getRow();
                if ($row) return $row->id_coa;
                $coaQuery->insert(['kode_akun'=>$kode, 'nama_akun'=>$nama, 'tipe_akun'=>$tipe, 'saldo_normal'=>$saldo]);
                return $coaQuery->db()->insertID();
            };
            
            $idCoaAsset   = $getCoaId('1000', 'Aset', 'asset', 'debit');
            $idCoaLiab    = $getCoaId('2000', 'Liabilitas', 'liability', 'credit');
            $idCoaEq      = $getCoaId('3000', 'Ekuitas', 'equity', 'credit');
            $idCoaRev     = $getCoaId('4000', 'Pendapatan', 'revenue', 'credit');
            $idCoaExp     = $getCoaId('5000', 'Beban', 'expense', 'debit');

            // 2. Buat Header Jurnal
            $db->table('acc_journals')->insert([
                'id_perusahaan' => $id_perusahaan,
                'tanggal' => date('Y-m-d'),
                'deskripsi' => 'Data Import Awal Tahun ' . $tahun,
                'status' => 'draft'
            ]);
            $id_journal = $db->insertID();

            // 3. Loop raw data, petakan ke item jurnal & deteksi pajak transaksional
            foreach ($rawData as $raw) {
                $lblLower = strtolower($raw['label']);
                
                // Heuristik Pemetaan Akun (Mapping)
                $id_coa = $idCoaAsset; // default
                $tipe_akun = 'asset';
                
                if (strpos($raw['mapped_to'], 'revenue') !== false || strpos($lblLower, 'pendapatan') !== false || strpos($lblLower, 'penjualan') !== false) {
                    $id_coa = $idCoaRev; $tipe_akun = 'revenue';
                } elseif (strpos($raw['mapped_to'], 'expense') !== false || strpos($raw['mapped_to'], 'tax') !== false || strpos($lblLower, 'beban') !== false) {
                    $id_coa = $idCoaExp; $tipe_akun = 'expense';
                } elseif (strpos($raw['mapped_to'], 'liabilities') !== false || strpos($lblLower, 'utang') !== false || strpos($lblLower, 'hutang') !== false) {
                    $id_coa = $idCoaLiab; $tipe_akun = 'liability';
                } elseif (strpos($raw['mapped_to'], 'equity') !== false || strpos($lblLower, 'modal') !== false || strpos($lblLower, 'laba') !== false) {
                    $id_coa = $idCoaEq; $tipe_akun = 'equity';
                }

                $debit = 0; $credit = 0;
                if ($tipe_akun === 'revenue' || $tipe_akun === 'liability' || $tipe_akun === 'equity') {
                    $credit = $raw['value'];
                } else {
                    $debit = $raw['value'];
                }

                // Insert ke acc_journal_items
                $db->table('acc_journal_items')->insert([
                    'id_journal' => $id_journal,
                    'id_coa' => $id_coa,
                    'debit' => $debit,
                    'credit' => $credit
                ]);
                $id_item = $db->insertID();

                // 4. String Matching Engine (Pajak Transaksional)
                // Cek kata kunci 'sewa', 'jasa', 'pph 24'
                $jenis_pajak = null;
                $tarif = 0;
                if (strpos($lblLower, 'sewa') !== false || strpos($lblLower, 'gedung') !== false) {
                    $jenis_pajak = 'PPh Pasal 4(2) / PPh 23 (Sewa)';
                    $tarif = 10; // 10%
                } elseif (strpos($lblLower, 'jasa') !== false) {
                    $jenis_pajak = 'PPh Pasal 23 (Jasa)';
                    $tarif = 2; // 2%
                } elseif (strpos($lblLower, 'pph 24') !== false || strpos($lblLower, 'luar negeri') !== false) {
                    $jenis_pajak = 'Kredit Pajak PPh 24';
                    $tarif = 100; // 100% full nominal dikreditkan
                }

                if ($jenis_pajak) {
                    $potongan = $raw['value'] * ($tarif / 100);
                    $db->table('potongan_pajak')->insert([
                        'id_journal_item' => $id_item,
                        'jenis_pajak' => $jenis_pajak,
                        'tarif' => $tarif,
                        'nominal_potongan' => $potongan,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
        
        $db->transComplete();
        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menyimpan dan mendistribusikan data ke berbagai modul. Transaksi dibatalkan.');
        }

        // --- NOTIFICATION ---
        $perusahaan = $this->perusahaanModel->find($dataLaporan['id_perusahaan']);
        if ($perusahaan && !empty($perusahaan['assigned_analyst'])) {
            $notifModel = new \App\Models\NotificationModel();
            $notifModel->insert([
                'target_role'    => null,
                'target_user_id' => $perusahaan['assigned_analyst'],
                'title'          => 'Data Baru Siap Dianalisis',
                'message'        => 'Data keuangan baru untuk klien ' . $perusahaan['nama_perusahaan'] . ' (' . $dataLaporan['tahun'] . ') telah ditambahkan dan siap untuk dianalisis.',
                'link'           => '/workspace'
            ]);
        }

        // Build result message with data quality info
        $msg = 'Data berhasil disimpan dan indikator otomatis dikalkulasi.';
        if (!empty($validation['warnings'])) {
            $warnCount = count($validation['warnings']);
            $msg .= " ({$warnCount} peringatan validasi data)";
        }
        
        log_activity('Input Laporan', "Menambahkan Laporan Keuangan untuk klien ID: " . $dataLaporan['id_perusahaan'] . " Tahun: " . $dataLaporan['tahun'], 'create');

        return redirect()->to('/keuangan')->with('success', $msg);
    }

    // =========================================================
    // 3. CALCULATION ENGINE — EDIT DATA (UPDATE)
    // =========================================================

    public function update($id)
    {
        if (session('role') === 'owner') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Owner hanya memiliki hak akses Read-Only.');
        }

        $laporanCheck = $this->laporanModel
            ->select('laporan_keuangan.*, perusahaan.assigned_analyst')
            ->join('perusahaan', 'perusahaan.id_perusahaan = laporan_keuangan.id_perusahaan')
            ->where('id_laporan', $id)->first();

        if (!$laporanCheck) {
            return redirect()->to('/keuangan')->with('error', 'Laporan tidak ditemukan.');
        }

        if (session('role') === 'analyst') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Analyst tidak memiliki hak akses untuk mengubah data mentah/sumber.');
        }

        $dataUpdate = [
            'laba_sebelum_pajak'   => (float)($this->request->getPost('profit_before_tax') ?? $this->request->getPost('laba_sebelum_pajak') ?? 0),
            'beban_pajak'          => (float)($this->request->getPost('tax_expense') ?? $this->request->getPost('beban_pajak') ?? 0),
            'piutang_berelasi'     => (float)($this->request->getPost('related_party_receivables') ?? $this->request->getPost('piutang_berelasi') ?? 0),
            'total_piutang'        => (float)($this->request->getPost('total_receivables') ?? $this->request->getPost('total_piutang') ?? 0),
            'total_hutang'         => (float)($this->request->getPost('total_liabilities') ?? $this->request->getPost('total_hutang') ?? 0),
            'total_ekuitas'        => (float)($this->request->getPost('total_equity') ?? $this->request->getPost('total_ekuitas') ?? 0),
            'penjualan_tahun_ini'  => (float)($this->request->getPost('revenue_current') ?? $this->request->getPost('penjualan_tahun_ini') ?? 0),
            'penjualan_tahun_lalu' => (float)($this->request->getPost('revenue_previous') ?? $this->request->getPost('penjualan_tahun_lalu') ?? 0)
        ];

        // Only allow status update if specified, otherwise keep it
        if ($this->request->getPost('status')) {
            $dataUpdate['status'] = $this->request->getPost('status');
        }

        $this->laporanModel->update($id, $dataUpdate);

        // =========================================================
        // RECALCULATION ENGINE
        // =========================================================
        
        // ETR — Effective Tax Rate
        $val_etr = ($dataUpdate['laba_sebelum_pajak'] != 0) 
            ? ($dataUpdate['beban_pajak'] / $dataUpdate['laba_sebelum_pajak']) 
            : null;

        // RPT Ratio — Related Party Receivable Ratio
        $val_rpt_ratio = ($dataUpdate['total_piutang'] != 0) 
            ? ($dataUpdate['piutang_berelasi'] / $dataUpdate['total_piutang']) 
            : null;

        // DER — Debt-to-Equity Ratio
        $val_der = ($dataUpdate['total_ekuitas'] != 0) 
            ? ($dataUpdate['total_hutang'] / $dataUpdate['total_ekuitas']) 
            : null;

        // Sales Growth
        $val_sales_growth = ($dataUpdate['penjualan_tahun_lalu'] != 0) 
            ? (($dataUpdate['penjualan_tahun_ini'] - $dataUpdate['penjualan_tahun_lalu']) / $dataUpdate['penjualan_tahun_lalu']) 
            : null;

        $dataAnalisisUpdate = [
            'val_etr'          => $val_etr,
            'val_rpt_ratio'    => $val_rpt_ratio,
            'val_der'          => $val_der,
            'val_sales_growth' => $val_sales_growth
        ];

        $this->analisisModel->where('id_laporan', $id)->set($dataAnalisisUpdate)->update();

        log_activity('Update Laporan', 'Memperbarui data laporan keuangan ID: ' . $id, 'update');

        return redirect()->to('/keuangan')->with('success', 'Data diperbarui dan indikator berhasil dikalkulasi ulang.');
    }

    // =========================================================
    // 4. PIPELINE IMPORT & RAW DATA PROCESSING
    // =========================================================

    public function import()
    {
        if (session('role') === 'owner') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Owner hanya memiliki hak akses Read-Only.');
        }

        if (session('role') === 'analyst') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Analyst tidak bertugas meng-import data mentah.');
        }

        $perusahaanQuery = $this->perusahaanModel;

        $data = [
            'perusahaan' => $perusahaanQuery->findAll()
        ];
        return view('keuangan/import', $data);
    }
    
    public function extractData()
    {
        if (session('role') === 'owner') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Owner hanya memiliki hak akses Read-Only.');
        }

        if (session('role') === 'analyst') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Analyst tidak bertugas mengekstrak data mentah.');
        }

        $id_perusahaan = $this->request->getPost('id_perusahaan');
        $tahun = $this->request->getPost('tahun');
        $bulan = $this->request->getPost('bulan');
        $file = $this->request->getFile('laporan_file');
        
        // Save to session so we can retrieve it in reviewMapping and simpanMapping
        session()->set('temp_bulan_laporan', $bulan);
        
        if (empty($id_perusahaan) || empty($tahun)) {
            return redirect()->back()->with('error', 'Perusahaan dan Tahun wajib diisi.');
        }

        if ($file === null || !$file->isValid()) {
            return redirect()->back()->with('error', 'Upload gagal. Pastikan file dipilih.');
        }
        
        $ext = strtolower($file->getClientExtension());
        if (!in_array($ext, ['pdf', 'xls', 'xlsx', 'csv', 'xbrl'])) {
            return redirect()->back()->with('error', 'Format file tidak didukung.');
        }
        
        if (in_array($ext, ['pdf', 'xbrl'], true)) {
            return redirect()->back()->with('error', 'Format ' . strtoupper($ext) . ' memerlukan parser khusus dan belum dapat diproses tanpa kehilangan data sumber.');
        }

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal membaca file laporan keuangan: {message}', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'File tidak dapat dibaca. Pastikan format dan struktur file valid.');
        }

        $rawFound = [];
        foreach ($rows as $rowNumber => $row) {
            $label = null;
            $value = null;

            foreach ($row as $cell) {
                if ($label === null && is_string($cell) && trim($cell) !== '') {
                    $label = trim($cell);
                    continue;
                }

                if ($value === null && is_numeric($cell)) {
                    $value = (float) $cell;
                }
            }

            if ($label !== null && $value !== null) {
                $rawFound[] = [
                    'label' => $label,
                    'value' => $value,
                    'page' => null,
                    'row' => $rowNumber
                ];
            }
        }

        if (empty($rawFound)) {
            return redirect()->back()->with('error', 'Tidak ada pasangan label dan nilai numerik yang dapat diproses dari file.');
        }

        // ── ACCOUNT MAPPING DICTIONARY ──
        $dictionary = [
            'profit_before_tax'         => ['laba sebelum pajak', 'profit before tax', 'income before tax', 'laba sebelum beban pajak penghasilan'],
            'tax_expense'               => ['beban pajak', 'pajak penghasilan', 'tax expense', 'beban pajak penghasilan'],
            'total_liabilities'         => ['total liabilitas', 'jumlah liabilitas', 'total hutang', 'total liabilities'],
            'total_equity'              => ['total ekuitas', 'jumlah ekuitas', 'total equity'],
            'revenue_current'           => ['pendapatan', 'penjualan', 'pendapatan usaha', 'revenue', 'net sales', 'penjualan bersih'],
            'revenue_previous'          => ['pendapatan tahun sebelumnya', 'penjualan tahun lalu', 'penjualan tahun sebelumnya', 'previous revenue'],
            'total_receivables'         => ['total piutang', 'jumlah piutang', 'total aset lancar'], // Simplified fallback for mock
            'related_party_receivables' => ['piutang berelasi', 'piutang pihak berelasi', 'related party receivables']
        ];

        // Clear existing raw data for this company/year to prevent duplicates during testing
        $this->rawModel->where('id_perusahaan', $id_perusahaan)->where('tahun', $tahun)->delete();

        $mappedCount = 0;
        foreach ($rawFound as $raw) {
            $mapped_to = null;
            $lblLower = strtolower($raw['label']);
            
            // Map against dictionary
            foreach ($dictionary as $field => $keywords) {
                foreach ($keywords as $keyword) {
                    if (strpos($lblLower, $keyword) !== false) {
                        $mapped_to = $field;
                        break 2;
                    }
                }
            }
            if ($mapped_to) $mappedCount++;

            $this->rawModel->insert([
                'id_perusahaan' => $id_perusahaan,
                'tahun'         => $tahun,
                'source_file'   => $file->getClientName(),
                'page'          => $raw['page'],
                'label'         => $raw['label'],
                'value'         => $raw['value'],
                'unit'          => 'IDR',
                'source_type'   => strtoupper($ext),
                'mapped_to'     => $mapped_to
            ]);
        }

        return redirect()->to("/keuangan/review-mapping/$id_perusahaan/$tahun")->with('success', count($rawFound) . " data mentah berhasil diekstrak, $mappedCount berhasil dipetakan otomatis.");
    }

    public function reviewMapping($id_perusahaan, $tahun)
    {
        $perusahaan = $this->perusahaanModel->find($id_perusahaan);
        $rawData = $this->rawModel->where('id_perusahaan', $id_perusahaan)
                                  ->where('tahun', $tahun)
                                  ->findAll();

        if (empty($rawData)) {
            return redirect()->to('/keuangan/import')->with('error', 'Data mentah tidak ditemukan. Silakan import ulang.');
        }

        // Structure mapped data
        $mapped = [
            'profit_before_tax' => null, 'tax_expense' => null,
            'related_party_receivables' => null, 'total_receivables' => null,
            'total_liabilities' => null, 'total_equity' => null,
            'revenue_current' => null, 'revenue_previous' => null
        ];

        foreach ($rawData as $d) {
            if ($d['mapped_to'] && array_key_exists($d['mapped_to'], $mapped)) {
                $mapped[$d['mapped_to']] = $d;
            }
        }

        $data = [
            'perusahaan' => $perusahaan,
            'tahun'      => $tahun,
            'bulan'      => session('temp_bulan_laporan') ?? '01',
            'rawData'    => $rawData,
            'mapped'     => $mapped,
            'source_file' => $rawData[0]['source_file'] ?? 'Document'
        ];

        return view('keuangan/review_mapping', $data);
    }

    public function simpanMapping()
    {
        if (session('role') === 'owner' || session('role') === 'analyst') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak.');
        }

        $id_perusahaan = $this->request->getPost('id_perusahaan');
        $tahun = $this->request->getPost('tahun');
        $bulan = $this->request->getPost('bulan') ?? '01';
        $rawData = $this->request->getPost('raw_data'); // This is the multi-dimensional array

        if (empty($id_perusahaan) || empty($tahun) || empty($rawData)) {
            return redirect()->back()->with('error', 'Data pemetaan tidak lengkap.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // =========================================================
            // TUGAS 1: DATA ANALYST (laporan_keuangan & hasil_analisis)
            // =========================================================
            // Extract the mapped analyst data from rawData if they were mapped
            $analystData = [
                'id_perusahaan' => $id_perusahaan,
                'tahun' => $tahun,
                'prepared_by' => session('id_user') ?? null,
                'status' => 'Pending', // Awal status Staff
                'laba_sebelum_pajak' => 0, 'beban_pajak' => 0,
                'piutang_berelasi' => 0, 'total_piutang' => 0,
                'total_hutang' => 0, 'total_ekuitas' => 0,
                'penjualan_tahun_ini' => 0, 'penjualan_tahun_lalu' => 0
            ];

            foreach ($rawData as $raw) {
                if (!empty($raw['mapped_to'])) {
                    // This maps directly to our laporan_keuangan columns based on the mapped_to string
                    $mappedField = $raw['mapped_to'];
                    if ($mappedField == 'profit_before_tax') $analystData['laba_sebelum_pajak'] = (float)$raw['value'];
                    if ($mappedField == 'tax_expense') $analystData['beban_pajak'] = (float)$raw['value'];
                    if ($mappedField == 'related_party_receivables') $analystData['piutang_berelasi'] = (float)$raw['value'];
                    if ($mappedField == 'total_receivables') $analystData['total_piutang'] = (float)$raw['value'];
                    if ($mappedField == 'total_liabilities') $analystData['total_hutang'] = (float)$raw['value'];
                    if ($mappedField == 'total_equity') $analystData['total_ekuitas'] = (float)$raw['value'];
                    if ($mappedField == 'revenue_current') $analystData['penjualan_tahun_ini'] = (float)$raw['value'];
                    if ($mappedField == 'revenue_previous') $analystData['penjualan_tahun_lalu'] = (float)$raw['value'];
                }
            }

            $this->laporanModel->insert($analystData);
            $id_laporan = $this->laporanModel->getInsertID();

            // Kalkulasi Rasio Analyst
            $val_etr = ($analystData['laba_sebelum_pajak'] != 0) ? ($analystData['beban_pajak'] / $analystData['laba_sebelum_pajak']) : null;
            $val_rpt_ratio = ($analystData['total_piutang'] != 0) ? ($analystData['piutang_berelasi'] / $analystData['total_piutang']) : null;
            $val_der = ($analystData['total_ekuitas'] != 0) ? ($analystData['total_hutang'] / $analystData['total_ekuitas']) : null;
            $val_sales_growth = ($analystData['penjualan_tahun_lalu'] != 0) ? (($analystData['penjualan_tahun_ini'] - $analystData['penjualan_tahun_lalu']) / $analystData['penjualan_tahun_lalu']) : null;

            $this->analisisModel->insert([
                'id_laporan' => $id_laporan,
                'val_etr' => $val_etr, 'val_rpt_ratio' => $val_rpt_ratio,
                'val_der' => $val_der, 'val_sales_growth' => $val_sales_growth
            ]);

            // Setup COA Function
            $coaQuery = $db->table('acc_coa');
            $getCoaId = function($kode, $nama, $tipe, $saldo) use ($coaQuery) {
                $row = $coaQuery->where('kode_akun', $kode)->get()->getRow();
                if ($row) return $row->id_coa;
                $coaQuery->insert(['kode_akun'=>$kode, 'nama_akun'=>$nama, 'tipe_akun'=>$tipe, 'saldo_normal'=>$saldo]);
                return $coaQuery->db()->insertID();
            };

            $jurnalHeader = [
                'id_perusahaan' => $id_perusahaan,
                'tanggal' => "$tahun-$bulan-01",
                'referensi' => "IMP/$id_perusahaan/$tahun/$bulan",
                'deskripsi' => "Import Otomatis Laporan $tahun-$bulan",
                'status' => 'draft'
            ];
            $db->table('acc_journals')->insert($jurnalHeader);
            $id_jurnal = $db->insertID();

            // =========================================================
            // LOOPING ARRAY DATA (Tugas 2 & 3)
            // =========================================================
            foreach ($rawData as $raw) {
                $label = $raw['label'];
                $val = (float)$raw['value'];
                if ($val == 0) continue; // Skip zero values

                // TUGAS 2: JURNAL AKUNTANSI
                $tipe_akun = 'Asset'; $saldo_normal = 'Debit';
                $lblLower = strtolower($label);
                
                if (strpos($lblLower, 'pendapatan') !== false || strpos($lblLower, 'penjualan') !== false || strpos($lblLower, 'revenue') !== false) {
                    $tipe_akun = 'Revenue'; $saldo_normal = 'Credit';
                } elseif (strpos($lblLower, 'beban') !== false || strpos($lblLower, 'biaya') !== false) {
                    $tipe_akun = 'Expense'; $saldo_normal = 'Debit';
                } elseif (strpos($lblLower, 'liabilitas') !== false || strpos($lblLower, 'utang') !== false || strpos($lblLower, 'hutang') !== false) {
                    $tipe_akun = 'Liability'; $saldo_normal = 'Credit';
                } elseif (strpos($lblLower, 'ekuitas') !== false || strpos($lblLower, 'modal') !== false || strpos($lblLower, 'laba') !== false) {
                    $tipe_akun = 'Equity'; $saldo_normal = 'Credit';
                }
                
                $kode_akun = strtoupper(substr($tipe_akun, 0, 1)) . '-' . rand(100, 999);
                $id_coa = $getCoaId($kode_akun, $label, $tipe_akun, $saldo_normal);

                $debit = ($saldo_normal === 'Debit') ? $val : 0;
                $kredit = ($saldo_normal === 'Credit') ? $val : 0;

                $db->table('acc_journal_items')->insert([
                    'id_journal' => $id_jurnal,
                    'id_coa' => $id_coa,
                    'debit' => $debit,
                    'credit' => $kredit
                ]);
                $id_journal_item = $db->insertID();

                // TUGAS 3: DETEKSI PAJAK TRANSAKSIONAL (potongan_pajak)
                if (strpos($lblLower, 'sewa') !== false) {
                    // PPh 4(2) 10%
                    $pajak = $val * 0.10;
                    $db->table('potongan_pajak')->insert([
                        'id_journal_item' => $id_journal_item,
                        'jenis_pajak' => 'PPh 4(2) Final - Sewa',
                        'tarif' => 10,
                        'nominal_potongan' => $pajak
                    ]);
                } elseif (strpos($lblLower, 'jasa') !== false || strpos($lblLower, 'profesional') !== false) {
                    // PPh 23 2%
                    $pajak = $val * 0.02;
                    $db->table('potongan_pajak')->insert([
                        'id_journal_item' => $id_journal_item,
                        'jenis_pajak' => 'PPh 23 - Jasa',
                        'tarif' => 2,
                        'nominal_potongan' => $pajak
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses data. Transaksi dibatalkan.');
            }

            return redirect()->to('/keuangan')->with('success', 'Data berhasil disimpan dan didistribusikan ke modul Analyst, Akuntansi, dan Pajak.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'System Error: ' . $e->getMessage());
        }
    }

    // =========================================================
    // 5. DELETE DATA
    // =========================================================

    public function delete($id)
    {
        if (session('role') === 'owner') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Owner hanya memiliki hak akses Read-Only.');
        }

        if (session('role') === 'analyst') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Analyst tidak diizinkan menghapus data mentah laporan.');
        }

        // Cascade delete analyst reports attached to this laporan
        $db = \Config\Database::connect();
        $db->table('analyst_reports')->where('id_laporan', $id)->delete();

        $this->laporanModel->delete($id);
        
        log_activity('Hapus Laporan', 'Menghapus data laporan keuangan ID: ' . $id, 'delete');
        
        return redirect()->to('/keuangan')->with('success', 'Laporan keuangan beserta data analisisnya berhasil dihapus.');
    }

    // =========================================================
    // 6. SEND TO ANALYST
    // =========================================================

    public function sendToProcessing($id)
    {
        if (session('role') === 'owner') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Owner hanya memiliki hak akses Read-Only.');
        }

        // Only admin/staff can send
        if (session('role') === 'analyst') {
            return redirect()->to('/keuangan')->with('error', 'Akses Ditolak: Analyst tidak memiliki akses untuk melakukan pengiriman ini.');
        }

        try {
            // Find the report with company details
            $laporan = $this->laporanModel
                ->select('laporan_keuangan.*, perusahaan.nama_perusahaan, perusahaan.assigned_analyst')
                ->join('perusahaan', 'perusahaan.id_perusahaan = laporan_keuangan.id_perusahaan')
                ->where('id_laporan', $id)
                ->first();

            if (!$laporan) {
                return redirect()->to('/keuangan')->with('error', 'Laporan tidak ditemukan. Data mungkin sudah dihapus.');
            }

            // Prevent re-sending if already sent or further in the pipeline
            $blockedStatuses = ['Processing', 'Analysis Completed', 'Revision Required', 'Approved'];
            if (in_array($laporan['status'], $blockedStatuses)) {
                return redirect()->to('/keuangan')->with('error', 'Laporan ini sudah dikirim atau sudah di-review. Jika ingin analisis ulang, harap hapus dan upload data baru.');
            }

            // Update the status to 'Processing'
            $updated = $this->laporanModel->update($id, [
                'status' => 'Processing'
            ]);

            if (!$updated) {
                return redirect()->to('/keuangan')->with('error', 'Gagal memperbarui status laporan. Silakan coba lagi.');
            }

            // ── DUAL WORKFLOW INTEGRATION ──
            // Insert into report_workflows for tracking
            $db = \Config\Database::connect();
            $db->table('report_workflows')->insert([
                'id_laporan' => $id,
                'analyst_status' => 'pending',
                'accounting_status' => 'pending',
                'treasurer_status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Send notification to Analyst
            $notifModel = new \App\Models\NotificationModel();
            
            if (!empty($laporan['assigned_analyst'])) {
                $notifModel->insert([
                    'target_role'    => null,
                    'target_user_id' => $laporan['assigned_analyst'],
                    'title'          => 'Laporan Baru (Analisis Risiko)',
                    'message'        => 'Laporan keuangan klien ' . $laporan['nama_perusahaan'] . ' (' . $laporan['tahun'] . ') telah dikirim untuk dianalisis.',
                    'link'           => '/workspace'
                ]);
            } else {
                // Notify all analysts
                $userModel = new \App\Models\UserModel();
                $analysts = $userModel->where('role', 'analyst')->findAll();
                foreach ($analysts as $a) {
                    $notifModel->insert([
                        'target_role'    => null,
                        'target_user_id' => $a['id_user'],
                        'title'          => 'Laporan Baru (Shared Pool)',
                        'message'        => 'Laporan keuangan ' . $laporan['nama_perusahaan'] . ' (' . $laporan['tahun'] . ') tersedia untuk dianalisis.',
                        'link'           => '/workspace'
                    ]);
                }
            }

            // Send notification to Akuntansi
            $userModel = new \App\Models\UserModel();
            $akuntan = $userModel->where('role', 'akuntansi')->findAll();
            foreach ($akuntan as $a) {
                $notifModel->insert([
                    'target_role'    => null,
                    'target_user_id' => $a['id_user'],
                    'title'          => 'Laporan Baru (Pemrosesan Akuntansi)',
                    'message'        => 'Data keuangan ' . $laporan['nama_perusahaan'] . ' (' . $laporan['tahun'] . ') siap untuk diproses ke jurnal dan buku besar.',
                    'link'           => '/accounting/process-financial'
                ]);
            }

            log_activity('Kirim Laporan', 'Mengirim Laporan Keuangan ' . $laporan['nama_perusahaan'] . ' (' . $laporan['tahun'] . ') ke Analyst & Akuntansi', 'system');

            return redirect()->to('/keuangan')->with('success', 'Laporan berhasil dikirim ke Analyst dan Akuntansi.');

        } catch (\Exception $e) {
            log_message('error', '[Keuangan::sendToAnalyst] Error: ' . $e->getMessage());
            return redirect()->to('/keuangan')->with('error', 'Terjadi kesalahan pada server saat mengirim laporan. Silakan coba lagi atau hubungi administrator. (Ref: ' . substr(md5($e->getMessage()), 0, 8) . ')');
        }
    }
}