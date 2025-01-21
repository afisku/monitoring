<?php

namespace App\Models;

use App\Enums\JenisKelaminEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'va',
        'nm_siswa',
        'tempat_lahir',
        'jenis_kelamin',
        'tgl_lahir',
        'telp',
        'email',
        'negara',
        'provinsi',
        'kab_kota',
        'alamat',
        'asal_sekolah',
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
}
