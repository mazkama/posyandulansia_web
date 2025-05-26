<?php

namespace App\Exports;

use App\Models\CekKesehatan;
use App\Models\Jadwal;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CekKesehatanExport implements FromArray, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    protected $jadwalId;
    protected $jadwal;
    protected $cekKesehatan;

    public function __construct($jadwalId)
    {
        $this->jadwalId = $jadwalId;
        $this->jadwal = Jadwal::find($jadwalId);
        $this->cekKesehatan = CekKesehatan::with('lansia')
            ->where('jadwal_id', $jadwalId)
            ->get();
    }

    public function array(): array
    {
        // Jika data kurang dari 10, kembalikan array kosong → export blank putih
        if ($this->cekKesehatan->count() < 10) {
            return [];
        }

        $rows = [];

        // Hitung statistik sesuai kondisi
        $jumlah_gula_tinggi = $this->cekKesehatan->where('gula_darah', '>', 140)->count();
        $jumlah_asam_urat_tinggi = $this->cekKesehatan->where('asam_urat', '>', 7)->count();
        $jumlah_kolestrol_tinggi = $this->cekKesehatan->where('kolestrol', '>', 200)->count();
        $jumlah_diabetes_mellitus = $this->cekKesehatan->where('gula_darah', '>', 200)->count();
        $jumlah_tekanan_darah_tinggi = $this->cekKesehatan->filter(function ($item) {
            return ($item->tekanan_darah_sistolik > 140 || $item->tekanan_darah_diastolik > 90);
        })->count();

        // HEADER INFORMASI
        $rows[] = ['LAPORAN PEMERIKSAAN KESEHATAN LANSIA'];
        $rows[] = ['Tanggal Cetak:', date('d/m/Y')];
        $rows[] = [''];
        $rows[] = ['Tanggal Pemeriksaan', ': ' . date('d-m-Y', strtotime($this->jadwal->tanggal))];
        $rows[] = ['Lokasi', ': ' . $this->jadwal->lokasi];
        $rows[] = ['Jumlah Data', ': ' . $this->cekKesehatan->count() . ' orang'];
        $rows[] = ['']; // Baris kosong

        // Statistik Kesehatan Lansia (sesuai format PDF)
        $rows[] = ['Statistik Kesehatan Lansia'];
        $rows[] = ['Jumlah Gula Darah Tinggi (> 140 mg/dL): ' . $jumlah_gula_tinggi . ' orang'];
        $rows[] = ['Jumlah Asam Urat Tinggi (> 7 mg/dL): ' . $jumlah_asam_urat_tinggi . ' orang'];
        $rows[] = ['Jumlah Kolesterol Tinggi (> 200 mg/dL): ' . $jumlah_kolestrol_tinggi . ' orang'];
        $rows[] = ['Jumlah Diabetes Mellitus (> 200 mg/dL): ' . $jumlah_diabetes_mellitus . ' orang'];
        $rows[] = ['Jumlah Tekanan Darah Tinggi (> 140/90 mmHg): ' . $jumlah_tekanan_darah_tinggi . ' orang'];

        $rows[] = ['']; // Baris kosong

        // HEADINGS untuk data detail
        $rows[] = [
            'No',
            'Nama Lansia',
            'Tanggal Pemeriksaan',
            'Berat Badan (kg)',
            'TD Sistolik (mmHg)',
            'TD Diastolik (mmHg)',
            'Gula Darah (mg/dL)',
            'Kolesterol (mg/dL)',
            'Asam Urat (mg/dL)',
            'Diagnosa'
        ];

        // DATA detail
        $i = 1;
        foreach ($this->cekKesehatan as $data) {
            $rows[] = [
                $i++,
                $data->lansia->nama,
                date('d-m-Y', strtotime($data->tanggal)),
                $data->berat_badan,
                $data->tekanan_darah_sistolik,
                $data->tekanan_darah_diastolik,
                $data->gula_darah,
                $data->kolestrol,
                $data->asam_urat,
                $data->diagnosa
            ];
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Laporan Pemeriksaan';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],     // Judul utama
            9 => ['font' => ['bold' => true, 'size' => 12]],     // Statistik Kesehatan Lansia (header)
            10 => ['font' => ['bold' => true]],                   // Baris statistik
            11 => ['font' => ['bold' => true]],
            12 => ['font' => ['bold' => true]],
            13 => ['font' => ['bold' => true]],
            14 => ['font' => ['bold' => true]],
            16 => ['font' => ['bold' => true]],                   // Header tabel detail
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $headerRow = 16; // Baris heading tabel detail (No, Nama, ...)
                $startRow = $headerRow + 1;
                $endRow = $startRow + $this->cekKesehatan->count() - 1;

                // Merge & center judul utama
                $sheet->mergeCells('A1:J1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Center untuk judul Statistik Kesehatan Lansia
                $sheet->mergeCells('A9:B9');
                $sheet->getStyle('A9')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Border seluruh data tabel detail
                $sheet->getStyle("A{$headerRow}:J{$endRow}")
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Background warna heading tabel detail
                $sheet->getStyle("A{$headerRow}:J{$headerRow}")
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E2EFDA');

                // Center alignment untuk kolom angka (berat badan, TD, gula, kolesterol, asam urat)
                foreach (['D', 'E', 'F', 'G', 'H', 'I'] as $col) {
                    $sheet->getStyle("{$col}{$startRow}:{$col}{$endRow}")
                        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Wrap text untuk kolom Diagnosa
                $sheet->getStyle("J{$startRow}:J{$endRow}")
                    ->getAlignment()->setWrapText(true);
            }
        ];
    }
}
