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
        'va', 'nm_siswa', 'jenis_kelamin', 'email', 'telp','asal_sekolah','pindahan',
         'tempat_lahir','tgl_lahir', 'kab_kota','yatim_piatu', 'nama_unit','tahun_akademik'
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

        //ambil data master akademik
        $tahunAkademikMap = TahunAkademik::where('status', true)
        ->select('id', 'th_akademik')
        ->get()
        ->toArray();   

         // Inisialisasi pesan error global
        $errorMessages = [];
        $successCount = 0; // Variabel untuk menghitung data berhasil diupload

        foreach ($rows as $index => $row) {
            try {
                if (Siswa::where('va', $row['va'])->exists()) {
                    throw new \Exception("No. VA (contoh = {$row['va']}) sudah ada pada baris ke-" . ($index + 1));
                }
        


                // Validasi dan cari unit
                $unit_id = null;
                if (!in_array($row['nama_unit'], [null, '', '-', '#N/A'])) {
                    $unit_id = $this->searchInArray($unitMap, 'nm_unit', $row['nama_unit']);
                    if (!$unit_id) {
                        $errorMessages[] = "Unit '{$row['nama_unit']}' tidak ditemukan di DATA MASTER pada baris ke-" . ($index + 1);
                        continue; // Lewati baris jika unit tidak valid
                    }
                } else {
                    $errorMessages[] = "Unit kosong atau tidak valid pada baris ke-" . ($index + 1);
                    continue; // Lewati baris jika unit kosong
                }

               // Validasi dan cari tahun akademik
               $tahunAkademik_id = null;
               if (!in_array($row['tahun_akademik'], [null, '', '-', '#N/A'])) {
                   $tahunAkademik_id = $this->searchInArray($tahunAkademikMap, 'th_akademik', $row['tahun_akademik']);
                   if (!$tahunAkademik_id) {
                       $errorMessages[] = "Tahun akademik '{$row['tahun_akademik']}' tidak ditemukan atau tidak aktif pada baris ke-" . ($index + 1);
                       continue;
                   }
               } else {
                   $errorMessages[] = "Tahun akademik kosong pada baris ke-" . ($index + 1);
                   continue;
               }
        
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
                        'yatim_piatu' => $row['yatim_piatu'] ?? 'TIDAK',
                        'unit_id' => $unit_id,
                        'tahun_akademik_id' => $tahunAkademik_id,
                    ]
                );

                $successCount++; // Tambahkan jika berhasil diproses
            } catch (\Exception $e) {
                $errorMessages[] = "Kesalahan pada baris ke-" . ($index + 1) . ": " . $e->getMessage();
            }
        }
    
        // Kirim notifikasi untuk kesalahan
        if (!empty($errorMessages)) {
            Notification::make()
                ->title('Gagal Memproses Beberapa Data')
                ->body(implode("\n", $errorMessages))
                ->danger()
                ->send();
            return;
        }

        Notification::make()
        ->title('Berhasil Import Siswa')
        ->body("Data siswa berhasil diimpor ke dalam sistem. Total data berhasil: $successCount.")
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
