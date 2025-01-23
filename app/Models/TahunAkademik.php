<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahunAkademik extends Model
{
    use HasFactory;
    protected $table = 'tahun_akademik';
    protected $fillable = [
        'th_akademik',
        'status',
    ];

    public function getSiswa()
    {
        return $this->hasMany(Siswa::class);
    }
}
