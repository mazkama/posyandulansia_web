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
        $rows = [];

        // HEADER INFORMASI
        $rows[] = ['LAPORAN PEMERIKSAAN KESEHATAN LANSIA'];
        $rows[] = ['Tanggal Cetak:', date('d/m/Y')];
        $rows[] = [''];
        $rows[] = ['Tanggal Pemeriksaan', ': ' . date('d-m-Y', strtotime($this->jadwal->tanggal))];
        $rows[] = ['Lokasi', ': ' . $this->jadwal->lokasi];
        $rows[] = ['Jumlah Data', ': ' . $this->cekKesehatan->count() . ' orang'];
        $rows[] = ['']; // Baris kosong

        // HEADINGS
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

        // DATA
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
            8 => ['font' => ['bold' => true]],                   // Baris heading tabel
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function(\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $headerRow = 8; // Baris heading
                $startRow = $headerRow + 1;
                $endRow = $startRow + $this->cekKesehatan->count() - 1;

                // Merge & center judul
                $sheet->mergeCells('A1:J1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Heading background
                $sheet->getStyle("A{$headerRow}:J{$headerRow}")
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E2EFDA');

                // Border seluruh data
                $sheet->getStyle("A{$headerRow}:J{$endRow}")
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Center untuk kolom numerik
                foreach (['D', 'E', 'F', 'G', 'H', 'I'] as $col) {
                    $sheet->getStyle("{$col}{$startRow}:{$col}{$endRow}")
                        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Diagnosa wrap text
                $sheet->getStyle("J{$startRow}:J{$endRow}")
                    ->getAlignment()->setWrapText(true);
            }
        ];
    }
}
