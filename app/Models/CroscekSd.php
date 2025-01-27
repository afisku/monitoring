<?php

namespace App\Models;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CroscekSd extends Model
{
    protected $table = "crosceksd";
    protected $fillable = [
        "siswa_id",
        "biodata",
        "dokumen",
        "permintaan",
        "note",
        "anak_gtk",
        "nama_GTK",
        "unit_id",
        "tahun_akademik_id",    
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class);
    }
}
