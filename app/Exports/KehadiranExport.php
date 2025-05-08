<?php

namespace App\Exports;

use App\Models\Kehadiran;
use App\Models\Lansia;
use App\Models\Jadwal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class KehadiranExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $jadwalId;

    public function __construct($jadwalId)
    {
        $this->jadwalId = $jadwalId;
    }

    public function collection()
    {
        // Ambil semua data lansia termasuk ttl
        $lansias = Lansia::select('id', 'nama', 'nik', 'ttl')->get();
    
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
                // Jika kolom ttl ada, langsung tampilkan
                'tempat_tanggal_lahir' => !empty($lansia->ttl) ? $lansia->ttl : '-',
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
            'Tempat Tanggal Lahir',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row['no'],
            $row['nama_lengkap'],
            $row['nik'],
            $row['tempat_tanggal_lahir'],
            $row['status']
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $sheetDelegate = $sheet->getDelegate();
        
                // Menambahkan 3 baris baru di awal sheet untuk header
                $sheetDelegate->insertNewRowBefore(1, 3);
        
                // Mengambil data jadwal untuk tanggal dan lokasi
                $jadwal = Jadwal::find($this->jadwalId); 
        
                // Pastikan jadwal ditemukan
                if ($jadwal) {
                    $tanggalJadwal = $jadwal->tanggal;  
                    $lokasiJadwal = $jadwal->lokasi;    
                } else {
                    // Jika jadwal tidak ditemukan, gunakan default
                    $tanggalJadwal = now()->format('d F Y');
                    $lokasiJadwal = 'Lokasi Tidak Ditemukan';
                }
        
                // Menambahkan header "Laporan Kehadiran", Tanggal, dan Lokasi
                $sheetDelegate->setCellValue('A1', 'Laporan Kehadiran Posyandu Lansia');
                $sheetDelegate->setCellValue('A2', 'Tanggal: ' . $tanggalJadwal); 
                $sheetDelegate->setCellValue('A3', 'Lokasi: ' . $lokasiJadwal); 
        
           // Menggabungkan sel untuk judul "Laporan Kehadiran"
            $sheetDelegate->mergeCells('A1:E1');
            $sheetDelegate->getStyle('A1')->applyFromArray([
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center']
            ]);

            // Mengatur format untuk tanggal dan lokasi
            $sheetDelegate->mergeCells('A2:E2');
            $sheetDelegate->getStyle('A2')->applyFromArray([
                'font' => ['bold' => true, 'size' => 12], 
                'alignment' => ['horizontal' => 'center'] 
            ]);
            $sheetDelegate->mergeCells('A3:E3');
            $sheetDelegate->getStyle('A3')->applyFromArray([
                'font' => ['bold' => true, 'size' => 12], 
                'alignment' => ['horizontal' => 'center'] 
            ]);


            $sheetDelegate->fromArray(['No', 'Nama Lengkap', 'NIK', 'Tempat Tanggal Lahir', 'Status'], null, 'A4');

            // Menambahkan gaya untuk judul kolom
            $sheetDelegate->getStyle('A4:E4')->applyFromArray([
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '00FF00'],
                ],
                'borders' => [
                    'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ]
            ]);

            // Menambahkan data
            $data = $this->collection()->toArray();
            $sheetDelegate->fromArray($data, null, 'A5');

            // Menambahkan baris total di bagian akhir
            $lastRow = $sheetDelegate->getHighestRow();

            // Total Hadir
            $sheetDelegate->setCellValue('A' . ($lastRow + 1), '');
            $sheetDelegate->setCellValue('B' . ($lastRow + 1), 'Total Hadir:');
            $sheetDelegate->setCellValue('C' . ($lastRow + 1), $this->getTotalHadir());

            // Total Tidak Hadir
            $sheetDelegate->setCellValue('A' . ($lastRow + 2), '');
            $sheetDelegate->setCellValue('B' . ($lastRow + 2), 'Total Tidak Hadir:');
            $sheetDelegate->setCellValue('C' . ($lastRow + 2), $this->getTotalTidakHadir());

            // Total Keseluruhan
            $sheetDelegate->setCellValue('A' . ($lastRow + 3), '');
            $sheetDelegate->setCellValue('B' . ($lastRow + 3), 'Total Keseluruhan:');
            $sheetDelegate->setCellValue('C' . ($lastRow + 3), $this->getTotalKeseluruhan());

            // Memformat hasil total agar lebih rapi
            $sheetDelegate->getStyle('A' . ($lastRow + 1) . ':C' . ($lastRow + 3))->applyFromArray([
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
                'borders' => [
                    'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ]
            ]);
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
