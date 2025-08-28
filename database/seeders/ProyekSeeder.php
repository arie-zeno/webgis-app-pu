<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProyekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tanggal_projek = Carbon::now();

        DB::table('projeks')->insert([
            'nama_projek' => Str::random(10),
            'email' => Str::random(10),
            'tanggal_projek' => $tanggal_projek,
            'kadaluwarsa_projek' => $tanggal_projek->copy()->addYears(5),
            'file_koordinat' => Str::random(10)
        ]);
    }
     
}
