<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
     protected $table = 'dokumentasi';
    protected $fillable = ['projek_id', 'gambar', 'caption'];

    public function projek()
    {
        return $this->belongsTo(Projek::class, 'projek_id');
    }
}
