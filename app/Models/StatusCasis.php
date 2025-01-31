<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusCasis extends Model
{
    protected $table = 'status_casis';

    protected $fillable = [
        'nm_status_casis',
        'warna',
        'keterangan'
    ];
}
