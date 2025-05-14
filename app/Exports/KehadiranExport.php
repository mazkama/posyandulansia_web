<?php

namespace App\Exports;

use App\Models\Kehadiran;
use App\Models\Lansia;
use App\Models\Jadwal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Events\AfterSheet;

class KehadiranExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    protected $jadwalId;

    public function __construct($jadwalId)
    {
        $this->jadwalId = $jadwalId;
    }

    public function collection()
    {
        // Ambil semua data lansia
        $lansias = Lansia::select('id', 'nama', 'nik', 'umur', 'alamat')->get();
    
        // Ambil ID Lansia yang hadir di jadwal ini
        $kehadiran = Kehadiran::where('jadwal_id', $this->jadwalId)
            ->pluck('lansia_id')
            ->toArray();
    
        // Mapping data, termasuk yang tidak hadir
        return $lansias->map(function ($lansia, $index) use ($kehadiran) {
            return [
                'no' => $index + 1,
                'nama_lengkap' => $lansia->nama ?? '-',
                'nik' => "'" . $lansia->nik ?? '-',
                // Menampilkan umur dan alamat
                'umur' => $lansia->umur ?? '-',
                'alamat' => $lansia->alamat ?? '-',
                // Status hadir atau tidak
                'status' => in_array($lansia->id, $kehadiran) ? 'Hadir' : 'Tidak Hadir',
            ];
        });
    }
    
    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NIK',
            'Umur',
            'Alamat',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row['no'],
            $row['nama_lengkap'],
            $row['nik'],
            $row['umur'],
            $row['alamat'],
            $row['status']
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->insertNewRowBefore(1, 3);
        
                // Mengambil data jadwal untuk tanggal dan lokasi
                $jadwal = Jadwal::find($this->jadwalId); 
                if ($jadwal) {
                    $tanggalJadwal = $jadwal->tanggal;
                    $lokasiJadwal = $jadwal->lokasi;
                } else {
                    $tanggalJadwal = now()->format('d F Y');
                    $lokasiJadwal = 'Lokasi Tidak Ditemukan';
                }
        
                // Menambahkan header "Laporan Kehadiran", Tanggal, dan Lokasi
                $sheet->setCellValue('A1', 'Laporan Kehadiran Posyandu Lansia');
                $sheet->setCellValue('A2', 'Tanggal: ' . $tanggalJadwal);
                $sheet->setCellValue('A3', 'Lokasi: ' . $lokasiJadwal);
        
                // Merge cells untuk judul
                $sheet->mergeCells('A1:F1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
                // Merge dan format tanggal dan lokasi
                $sheet->mergeCells('A2:F2');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->mergeCells('A3:F3');
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
                // Tabel header
                $sheet->fromArray(['No', 'Nama Lengkap', 'NIK', 'Umur', 'Alamat', 'Status'], null, 'A4');
        
                // Styling untuk header
                $sheet->getStyle('A4:F4')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '00FF00'],
                    ],
                    'borders' => [
                        'top' => ['borderStyle' => Border::BORDER_THIN],
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    ]
                ]);
        
                // Data Collection
                $data = $this->collection()->toArray();
                $sheet->fromArray($data, null, 'A5');
        
                // Menambahkan baris Total
                $lastRow = $sheet->getHighestRow();
                $sheet->setCellValue('A' . ($lastRow + 1), '');
                $sheet->setCellValue('B' . ($lastRow + 1), 'Total Hadir:');
                $sheet->setCellValue('C' . ($lastRow + 1), $this->getTotalHadir());
                $sheet->setCellValue('A' . ($lastRow + 2), '');
                $sheet->setCellValue('B' . ($lastRow + 2), 'Total Tidak Hadir:');
                $sheet->setCellValue('C' . ($lastRow + 2), $this->getTotalTidakHadir());
                $sheet->setCellValue('A' . ($lastRow + 3), '');
                $sheet->setCellValue('B' . ($lastRow + 3), 'Total Keseluruhan:');
                $sheet->setCellValue('C' . ($lastRow + 3), $this->getTotalKeseluruhan());
        
                // Formatting total row
                $sheet->getStyle('A' . ($lastRow + 1) . ':C' . ($lastRow + 3))->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => [
                        'top' => ['borderStyle' => Border::BORDER_THIN],
                    ]
                ]);
        
                // Adjusting column width to auto-size
                foreach (range('A', 'F') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }

    protected function getTotalHadir()
    {
        return Kehadiran::where('jadwal_id', $this->jadwalId)->count();
    }

    protected function getTotalTidakHadir()
    {
        $totalLansia = Lansia::count();
        return $totalLansia - $this->getTotalHadir();
    }

    protected function getTotalKeseluruhan()
    {
        return Lansia::count();
    }
}
