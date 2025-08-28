<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projek extends Model
{
     protected $fillable = [
        'nama_projek',
        'email',
        'tanggal_projek',
        'kadaluwarsa_projek',
        'file_koordinat',
    ];

       //    $table->string('nama_proyek');
        //     $table->dateTime('tanggal_proyek');
        //     $table->dateTime('kadaluwarsa_proyek');
        //     $table->string('file');
}
