<?php

namespace App\Models;

use App\Models\Dokumentasi;
use Illuminate\Database\Eloquent\Model;

class Projek extends Model
{
     protected $fillable = [
        'nama_projek',
        'email',
        'tanggal_projek',
        'kadaluwarsa_projek',
        'file_koordinat',
        'nama_jalur',
        'line',
        'markers',
    ];

    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class, 'projek_id');
    }

       //    $table->string('nama_proyek');
        //     $table->dateTime('tanggal_proyek');
        //     $table->dateTime('kadaluwarsa_proyek');
        //     $table->string('file');
}
