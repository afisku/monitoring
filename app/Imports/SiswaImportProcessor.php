<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Unit;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImportProcessor implements ToCollection, WithHeadingRow
{
    // Header yang diperlukan pada file Excel
    protected $requiredHeaders = [
        'va', 'nm_siswa', 'tempat_lahir', 'jenis_kelamin', 
        'tgl_lahir', 'telp', 'email', 'negara', 
        'provinsi', 'kab_kota', 'alamat', 'asal_sekolah', 'nm_unit'
    ];

    public function collection(Collection $rows)
    {
        // Validasi header pada Excel
        $headers = array_keys($rows->first()->toArray());
        $missingHeaders = array_diff($this->requiredHeaders, $headers);

        if (!empty($missingHeaders)) {
            $quotedHeaders = array_map(fn($header) => "'$header'", $missingHeaders);
            Notification::make()
                ->title('Gagal Import Siswa')
                ->body('Tidak dapat menemukan Header ' . implode(', ', $quotedHeaders) . ' pada file Excel')
                ->danger()
                ->send();
            return;
        }

        // Ambil data master unit
        $unitMap = Unit::select('id', 'nm_unit')->get()->toArray();

        foreach ($rows as $row) {
            // Cari unit_id berdasarkan nm_unit
            $unit_id = $this->searchInArray($unitMap, 'nm_unit', $row['nm_unit']);

            // Masukkan atau update data ke tabel Siswa
            Siswa::updateOrCreate(
                ['va' => $row['va']], // Unique field untuk identifikasi
                [
                    'nm_siswa' => $row['nm_siswa'],
                    'tempat_lahir' => $row['tempat_lahir'],
                    'jenis_kelamin' => $row['jenis_kelamin'],
                    'tgl_lahir' => $row['tgl_lahir'],
                    'telp' => $row['telp'],
                    'email' => $row['email'],
                    'negara' => $row['negara'],
                    'provinsi' => $row['provinsi'],
                    'kab_kota' => $row['kab_kota'],
                    'alamat' => $row['alamat'],
                    'asal_sekolah' => $row['asal_sekolah'],
                    'unit_id' => $unit_id,
                ]
            );
        }

        Notification::make()
            ->title('Berhasil Import Siswa')
            ->body('Data siswa berhasil diimpor ke dalam sistem.')
            ->success()
            ->send();
    }

    private function searchInArray(array $data, string $searchKey, string $searchValue)
    {
        foreach ($data as $item) {
            if ($item[$searchKey] === $searchValue) {
                return $item['id'];
            }
        }
        return null;
    }
}
