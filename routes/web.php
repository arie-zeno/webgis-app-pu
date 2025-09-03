<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjekController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [ProjekController::class, 'dashboard'])->name('admin.dashboard');

Route::get('/projek', [ProjekController::class, 'index'])->name('admin.projek');
Route::post('/projek/tambah', [ProjekController::class, 'tambahProjek'])->name('tambah.projek');
Route::get('/projek/detail/{id}', [ProjekController::class, 'detailProjek'])->name('detail.projek');
Route::get('/projek/hapus/{id}', [ProjekController::class, 'hapusProjek'])->name('hapus.projek');
Route::get('/projek/formTambah', [ProjekController::class, 'formTambahProjek'])->name('formTambah.projek');


Route::get('/gis', [ProjekController::class, 'gis'])->name('admin.gis');

// kirim email
Route::post('/projek-send', [ProjekController::class, 'sendMail'])->name('projek.sendMail');

