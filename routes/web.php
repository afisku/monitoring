<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;

Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

Route::get('download-template-siswa', function () {
    $filePath = storage_path('app/templates/template_siswa.xlsx'); // Path file template

    if (!file_exists($filePath)) {
        abort(404, 'Template tidak ditemukan.');
    }
    return response()->download($filePath, 'template_siswa.xlsx');
})->name('download.template.siswa');



Route::get('/export-croscek/{unit}', [ExportController::class, 'export'])->name('export.croscek');

