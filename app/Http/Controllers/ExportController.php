<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\CroscekSd;
use App\Models\CroscekTk;
use App\Models\CroscekSma;
use App\Models\CroscekSmp;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function export(Request $request, $unit)
    {
        // Ambil data sesuai unit
        $data = match ($unit) {
            'tk' => CroscekTk::whereHas('unit', fn ($q) => $q->where('nm_unit', 'TKIT'))->get(),
            'sd' => CroscekSd::whereHas('unit', fn ($q) => $q->where('nm_unit', 'SDIT'))->get(),
            'smp' => CroscekSmp::whereHas('unit', fn ($q) => $q->where('nm_unit', 'SMPIT'))->get(),
            'sma' => CroscekSma::whereHas('unit', fn ($q) => $q->where('nm_unit', 'SMAIT'))->get(),
            default => abort(404, 'Unit tidak ditemukan'),
        };

        // Hitung statistik
        $totalSiswa = $data->count();
        $totalLunas = $data->filter(fn($siswa) => optional($siswa->statusCasis)->nm_status_casis === 'LUNAS')->count();
        $totalMengundurkanDiri = $data->filter(fn($siswa) => optional($siswa->statusCasis)->nm_status_casis === 'MENGUNDURKAN DIRI')->count();
        $totalAnakGtk = $data->where('anak_gtk', 'YA')->count();

        // Hitung total siswa yatim per unit
        // **Hitung total siswa yatim hanya di unit yang dipilih**
        $totalYatim = $data->filter(fn($siswa) => $siswa->siswa->yatim_piatu === 'YA')->count();
        $totalPindahan = $data->filter(fn($siswa) => $siswa->siswa->pindahan)->count();
        $totalAlumni = $data->filter(fn($siswa) => $siswa->siswa->asal_sekolah === 'AL-FITYAN')->count();
        $totalPerempuan = $data->filter(fn($siswa) => $siswa->siswa->jenis_kelamin === 'PEREMPUAN')->count();
        $totalLakiLaki = $data->filter(fn($siswa) => $siswa->siswa->jenis_kelamin === 'LAKI-LAKI')->count();



        // Kirim ke view PDF
        $pdf = Pdf::loadView('exports.croscek', compact('data', 'unit', 'totalSiswa', 'totalLunas', 'totalMengundurkanDiri', 'totalAnakGtk', 'totalYatim','totalPindahan','totalAlumni','totalPerempuan','totalLakiLaki'));
        return $pdf->stream("export-{$unit}.pdf"); // Tampilkan di browser
    }
}
