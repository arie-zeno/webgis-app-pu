<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjekController;
use App\Http\Controllers\ProjekController2;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [ProjekController::class, 'dashboard'])->name('admin.dashboard');

Route::get('/projek', [ProjekController::class, 'index'])->name('admin.projek');
Route::get('/projek/detail/{id}', [ProjekController::class, 'detailProjek'])->name('detail.projek');
Route::get('/projek/hapus/{id}', [ProjekController::class, 'hapusProjek'])->name('hapus.projek');
Route::get('/projek/formTambah', [ProjekController::class, 'formTambahProjek'])->name('formTambah.projek');
Route::post('/projek/tambah', [ProjekController::class, 'tambahProjek'])->name('tambah.projek');


Route::get('/projek2', [ProjekController2::class, 'index'])->name('admin2.projek');
Route::get('/projek/formTambah2', [ProjekController2::class, 'formTambahProjek'])->name('formTambah2.projek');
Route::post('/projek/tambah2', [ProjekController2::class, 'tambahProjek'])->name('tambah2.projek');
Route::get('/projek/detail2/{id}', [ProjekController2::class, 'detailProjek'])->name('detail2.projek');


Route::get('/gis', [ProjekController::class, 'gis'])->name('admin.gis');

// kirim email
Route::post('/projek-send', [ProjekController::class, 'sendMail'])->name('projek.sendMail');

// Auth
Route::get('/daftar', [AuthController::class, 'daftar'])->name('auth.daftar');
Route::get('/login', [AuthController::class, 'login'])->name('auth.login');