<?php

namespace App\Imports;

use Exception;
use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Siswa;
use App\Models\TahunAkademik;
use InvalidArgumentException;
use App\Enums\JenisKelaminEnum;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImportProcessor implements ToCollection, WithHeadingRow
{
    // Header yang diperlukan pada file Excel
    protected $requiredHeaders = [
        'va', 'nm_siswa', 'jenis_kelamin', 'email', 'telp','asal_sekolah','pindahan', 'tempat_lahir','tgl_lahir', 'kab_kota','yatim_piatu', 'nm_unit','tahun_akademik'
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
        $tahunAkademikMap = TahunAkademik::select('id', 'th_akademik')->get()->toArray();   

        foreach ($rows as $index => $row) {
            try {
                if (Siswa::where('va', $row['va'])->exists()) {
                    throw new \Exception("No. VA (contoh = {$row['va']}) sudah ada pada baris ke-" . ($index + 1));
                }
        
                $unit_id = $this->searchInArray($unitMap, 'nm_unit', $row['nm_unit']);
                $tahunAkademik_id = $this->searchInArray($tahunAkademikMap, 'th_akademik', $row['tahun_akademik']);
        
                Siswa::updateOrCreate(
                    ['va' => $row['va']],
                    [
                        'nm_siswa' => $row['nm_siswa'] ?? '-',
                        'jenis_kelamin' => $this->convertJenisKelamin($row['jenis_kelamin']),
                        'email' => $row['email'] ?? '-',
                        'telp' => $row['telp'] ?? '-',
                        'asal_sekolah' => $row['asal_sekolah'] ?? '-',
                        'pindahan' => $row['pindahan'] ?? '-',
                        'tempat_lahir' => $row['tempat_lahir'] ?? '-',
                        'tgl_lahir' => $row['tgl_lahir'] ? $this->convertExcelDate($row['tgl_lahir']) : null,
                        'kab_kota' => $row['kab_kota'] ?? '-',
                        'yatim_piatu' => $row['yatim_piatu'] ?? 'Tidak',
                        'unit_id' => $unit_id,
                        'tahun_akademik_id' => $tahunAkademik_id,
                    ]
                );
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Gagal Memproses Data')
                    ->body("Kesalahan pada baris ke-" . ($index + 1) . ": " . $e->getMessage())
                    ->danger()
                    ->send();
                return;
            }
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

    private function convertJenisKelamin(string $jenisKelamin): string
    {
        return match (strtolower(trim($jenisKelamin))) {
            'laki-laki', 'laki laki', 'l' => JenisKelaminEnum::LakiLaki->value,
            'perempuan', 'p' => JenisKelaminEnum::Perempuan->value,
            default => throw new \InvalidArgumentException("Jenis kelamin '$jenisKelamin' tidak valid."),
        };
    }

    private function convertExcelDate($value): ?string
    {
        if (!$value) {
            return null;
        }

        // Tangani jika berupa angka (Excel timestamp)
        if (is_numeric($value)) {
            try {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
            } catch (Exception $e) {
                throw new InvalidArgumentException("Nilai tanggal dalam format numeric tidak valid.");
            }
        }

        // Format tanggal manual yang didukung
        $formats = ['d/m/Y', 'Y-m-d', 'd-m-Y'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->format('Y-m-d');
            } catch (Exception $e) {
                // Lanjutkan ke format berikutnya
            }
        }

        throw new InvalidArgumentException("Format tanggal '$value' tidak valid. Format yang didukung adalah 'dd/mm/YYYY', 'YYYY-mm-dd', atau 'dd-mm-YYYY'.");
    }
}
