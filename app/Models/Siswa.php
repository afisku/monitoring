<?php

namespace App\Models;

use App\Models\TahunAkademik;
use App\Enums\JenisKelaminEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'va',
        'nm_siswa',
        'jenis_kelamin',
        'email',
        'telp',
        'asal_sekolah',
        'pindahan',
        'tempat_lahir',
        'tgl_lahir',
        'kab_kota',
        'yatim_piatu',
        'tahun_akademik_id',
        'unit_id',
    ];

    protected $casts = [
        'jenis_kelamin' => JenisKelaminEnum::class,
        'tgl_lahir' => 'date',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class);
    }
}
