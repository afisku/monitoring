<?php

namespace App\Http\Controllers;

use App\Models\CroscekSmp;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function export()
    {
        // Ambil data yang dibutuhkan
        $totalSiswa = CroscekSmp::count();
        $totalSiswaLunas = CroscekSmp::whereHas('statusCasis', function ($query) {
            $query->where('nm_status_casis', 'LUNAS');
        })->count();
        $totalAnakGtk = CroscekSmp::where('anak_gtk', 'YA')->count();

        // Load tampilan PDF
        $pdf = Pdf::loadView('exports.croscek_smp', compact('totalSiswa', 'totalSiswaLunas', 'totalAnakGtk'));

        // Set ukuran kertas ke portrait
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('rekap_croscek_smp.pdf');
    }
}
