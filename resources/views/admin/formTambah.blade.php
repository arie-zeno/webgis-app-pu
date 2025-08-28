@extends('admin.layout.header')
@section('title', 'Tambah Projek')
@section('content')

<main class="content">
    <div class="container-fluid p-0">

        <div class="d-flex justify-content-between align-items-center">

            <h1 class="h3 mb-3">Tambah Projek</h1>
            <a class="btn btn-sm btn-primary rounded" href="{{ route('admin.projek') }}">Kembali</a>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Form Tambah Data</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tambah.projek') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Proyek</label>
                                <input type="text" required class="form-control" name="nama_projek">
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Email Proyek</label>
                                <input type="email" required class="form-control" name="email_projek">
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Tanggal Berlaku</label>
                                <input type="date" required class="form-control" name="tanggal_mulai">
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Tanggal Berakhir</label>
                                <input type="date" required class="form-control" name="tanggal_akhir">
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Koordinat Proyek</label>
                                <input type="file" required class="form-control" id="name" name="file_koordinat">
                            </div>

                            <button type="submit" class="btn btn-primary">Tambah Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

@endsection
