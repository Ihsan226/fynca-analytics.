<?php
namespace App\Controllers;

use App\Models\PerusahaanModel;
use App\Models\LaporanKeuanganModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Import extends BaseController
{
    protected $perusahaanModel;
    protected $laporanModel;

    public function __construct()
    {
        $this->perusahaanModel = new PerusahaanModel();
        $this->laporanModel    = new LaporanKeuanganModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Import Laporan Keuangan',
            'perusahaan' => $this->perusahaanModel->findAll()
        ];

        return view('laporan/import', $data);
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header Template
        $sheet->setCellValue('A1', 'Tahun');
        $sheet->setCellValue('B1', 'Laba Sebelum Pajak');
        $sheet->setCellValue('C1', 'Beban Pajak');
        $sheet->setCellValue('D1', 'Piutang Berelasi');
        $sheet->setCellValue('E1', 'Total Piutang');
        $sheet->setCellValue('F1', 'Total Hutang');
        $sheet->setCellValue('G1', 'Total Ekuitas');
        $sheet->setCellValue('H1', 'Penjualan Tahun Ini');
        $sheet->setCellValue('I1', 'Penjualan Tahun Lalu');

        // Dummy Data
        $sheet->setCellValue('A2', date('Y'));
        $sheet->setCellValue('B2', 5000000000);
        $sheet->setCellValue('C2', 1100000000);
        $sheet->setCellValue('D2', 200000000);
        $sheet->setCellValue('E2', 1000000000);
        $sheet->setCellValue('F2', 2500000000);
        $sheet->setCellValue('G2', 5000000000);
        $sheet->setCellValue('H2', 15000000000);
        $sheet->setCellValue('I2', 12000000000);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Laporan_Keuangan.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit();
    }

    public function process()
    {
        $id_perusahaan = $this->request->getPost('id_perusahaan');
        $fileExcel = $this->request->getFile('file_excel');

        if (!$id_perusahaan) {
            return redirect()->back()->with('error', 'Klien belum dipilih.');
        }

        if (!$fileExcel->isValid() || $fileExcel->getExtension() != 'xlsx') {
            return redirect()->back()->with('error', 'Format file tidak valid. Harap unggah file .xlsx');
        }

        try {
            $spreadsheet = IOFactory::load($fileExcel->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();

            $successCount = 0;
            $errorCount = 0;

            // Start reading from row 2 (assuming row 1 is header)
            for ($row = 2; $row <= $highestRow; $row++) {
                $tahun = $sheet->getCell('A' . $row)->getValue();
                if (empty($tahun)) continue;

                $data = [
                    'id_perusahaan'        => $id_perusahaan,
                    'tahun'                => $tahun,
                    'laba_sebelum_pajak'   => $sheet->getCell('B' . $row)->getValue() ?? 0,
                    'beban_pajak'          => $sheet->getCell('C' . $row)->getValue() ?? 0,
                    'piutang_berelasi'     => $sheet->getCell('D' . $row)->getValue() ?? 0,
                    'total_piutang'        => $sheet->getCell('E' . $row)->getValue() ?? 0,
                    'total_hutang'         => $sheet->getCell('F' . $row)->getValue() ?? 0,
                    'total_ekuitas'        => $sheet->getCell('G' . $row)->getValue() ?? 0,
                    'penjualan_tahun_ini'  => $sheet->getCell('H' . $row)->getValue() ?? 0,
                    'penjualan_tahun_lalu' => $sheet->getCell('I' . $row)->getValue() ?? 0,
                ];

                // Cek data ganda
                $existing = $this->laporanModel->where('id_perusahaan', $id_perusahaan)
                                               ->where('tahun', $tahun)
                                               ->first();

                if ($existing) {
                    $this->laporanModel->update($existing['id_laporan'], $data);
                } else {
                    $this->laporanModel->save($data);
                }
                
                $successCount++;
            }

            return redirect()->to('/keuangan')->with('success', "Berhasil mengimpor $successCount data laporan.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses file: ' . $e->getMessage());
        }
    }
}
