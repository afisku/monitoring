<?php

namespace App\Http\Controllers;

use App\Models\CroscekSd;
use App\Models\CroscekSma;
use App\Models\CroscekSmp;
use App\Models\CroscekTk;
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
        $totalLunas = $data->where('status_casis_id', function ($query) {
            $query->select('id')->from('status_casis')->where('nm_status_casis', 'LUNAS');
        })->count();
        $totalAnakGtk = $data->where('anak_gtk', 'YA')->count();

        // Kirim ke view PDF
        $pdf = Pdf::loadView('exports.croscek', compact('data', 'unit', 'totalSiswa', 'totalLunas', 'totalAnakGtk'));
        return $pdf->stream("export-{$unit}.pdf"); // Tampilkan di browser
    }
}
