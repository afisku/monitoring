<?php

namespace App\Models;

use App\Models\Unit;
use App\Models\Siswa;
use App\Models\StatusCasis;
use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CroscekSmp extends Model
{
    protected $table = "crosceksmp";

    protected $fillable = [
        "unit_id",
        "siswa_id",
        "biodata",
        "dokumen",
        "permintaan",
        "note",
        "anak_gtk",
        "divisi_id",
        "nama_GTK",
        "status_casis_id",
        "tahun_akademik_id",
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    public function statusCasis()
    {
        return $this->belongsTo(StatusCasis::class, 'status_casis_id');
    }

    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }

}
