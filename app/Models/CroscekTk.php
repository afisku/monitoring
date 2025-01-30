<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CroscekTk extends Model
{
    protected $table = "croscektk";

    protected $fillable = [
        "unit_id",
        "siswa_id",
        "biodata",
        "dokumen",
        "permintaan",
        "note",
        "anak_gtk",
        "unit_gtk",
        "nama_GTK",
        "tahun_akademik_id",
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }
}
