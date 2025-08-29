@extends('admin.layout.header')

@section('loadCSS')
<link rel="stylesheet" href="/js/table-bootstrap/dist/bootstrap-table.min.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

@endsection
@section('title', 'Projek')
@section('content')
@php
use Illuminate\Support\Carbon;
@endphp

{{-- loading --}}
<div id="loading" class="position-fixed top-0 bottom-0 start-0 end-0 d-none align-items-center justify-content-center" style="background-color: #ffffff7c; backdrop-filter: blur(2px); z-index: 99;">
    <img src="/img/pu_nobg.png" alt="" width="600">
</div>
{{-- endloading --}}
<main class="content pt-3">
    <div class="container-fluid">
        <h1 class="h3 mb-3">Halaman Projek</h1>
        {{-- <div class="flex justify-end">
        <button type="button" class="bg-purple-500 btn hover:bg-purple-400 hover:shadow-sm text-white" data-bs-toggle="modal" data-bs-target="#tambahData">
            Tambah Data
        </button>
    </div> --}}
        {{-- table --}}
        <div class="row">
            <div class="col-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">List Projek</h5>
                        <a href="{{ route('formTambah.projek') }}" class="btn btn-sm btn-primary my-0">
                            Tambah Data
                        </a>
                        {{-- <h5 class="card-title mb-0">List Projek</h5> --}}
                    </div>
                    <table class="table table-hover my-0">
                        <thead>
                            <tr>
                                <th>Vendor</th>
                                <th class="d-none d-md-table-cell" >Email</th>
                                <th class="d-none d-md-table-cell">Tanggal Berlaku</th>
                                <th>Tanggal Berakhir</th>
                                <th class="d-none d-md-table-cell"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr>
                                <td>Project Apollo</td>
                                <td class="d-none d-xl-table-cell">01/01/2023</td>
                                <td class="d-none d-xl-table-cell">31/06/2023</td>
                                <td><span class="badge bg-success">Done</span></td>
                                <td class="d-none d-md-table-cell">Vanessa Tucker</td>
                            </tr> --}}
                            {{-- <tr>
                                <td>Project Fireball</td>
                                <td class="d-none d-xl-table-cell">01/01/2023</td>
                                <td class="d-none d-xl-table-cell">31/06/2023</td>
                                <td><span class="badge bg-danger">Cancelled</span></td>
                                <td class="d-none d-md-table-cell">William Harris</td>
                            </tr> --}}

                            @foreach ($projek as $i => $data)

                            @php
                            $tanggal = Carbon::parse($data['tanggal_projek'])->locale('id')->translatedFormat('d F Y');
                            $kadaluwarsa = Carbon::parse($data['kadaluwarsa_projek']);

                            $kadaluwarsaStr = $kadaluwarsa->locale('id')->translatedFormat('d F Y');

                            $sisaWaktu = $kadaluwarsa->diff(Carbon::now());
                            // dd($kadaluwarsa->lessThan(Carbon::now()));
                            if($kadaluwarsa->lessThan(Carbon::now())){
                            $sisaWaktu = "Expired";
                            } else{
                            $sisaWaktu = [
                            "tahun" => $sisaWaktu->y ? $sisaWaktu->y.' tahun ' : null,
                            "bulan" => $sisaWaktu->m ? $sisaWaktu->m.' bulan ' : null,
                            "hari" => $sisaWaktu->d ? $sisaWaktu->d.' hari' : null,
                            ];

                            $sisaWaktu = $sisaWaktu['tahun'] . $sisaWaktu['bulan'] . $sisaWaktu['hari'];
                            }
                            @endphp
                            <tr>
                                {{-- <th scope="row">{{ $projek->firstItem() + $i }}</th> --}}
                                <td>{{ $data['nama_projek'] }}</td>
                                <td class="d-none d-md-table-cell">{{ $data['email'] }}</td>
                                <td class="d-none d-md-table-cell">{{ $tanggal }}</td>
                                <td class="align-middle">{{ $kadaluwarsaStr }} <span class=" badge {{ $sisaWaktu == "Expired" ? "bg-danger" : "bg-success" }} ">{{ $sisaWaktu }}</span> </td>
                                <td class="">
                                    <div class="d-flex justify-content-end">
                                        <form class="d-inline-block mx-1" action="{{ route('detail.projek', ['id' => $data['id']]) }}" method="get">
                                            <button class="mb-1 btn btn-sm btn-info btn-detail">Detail</button>
                                        </form>

                                        {{-- kirim email --}}
                                        <form action="{{ route('projek.sendMail') }}" method="post" class="mx-1">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $data['id'] }}">
                                            <input type="hidden" name="tanggal_akhir" value="{{ $kadaluwarsaStr}}">
                                            <input type="hidden" name="sisa_waktu" value="{{ $sisaWaktu}}">
                                            <button type="submit" class="mb-1 btn btn-sm btn-warning send-email">Email</button>
                                        </form>

                                        <form class="d-inline-block mx-1" action="{{ route('hapus.projek', ['id' => $data['id']]) }}" method="get"> <button class="mb-1 btn btn-sm btn-danger">Hapus</button> </form>

                                    </div>
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">

                {{ $projek->links() }}
            </div>
        </div>

        {{-- my code --}}
        {{-- <div class="d-flex justify-content-end mb-2">
            <a href="{{ route('formTambah.projek') }}" class="btn btn-sm btn-primary">
        Tambah Data
        </a>
    </div> --}}

    {{-- <div class="d-flex justify-content-between">

            <h1 class="font-bold text-4xl">Data Proyek</h1>
            <form method="GET" action="{{ route('admin.projek') }}" class="mb-3 d-flex">
    <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control me-2" placeholder="Cari projek...">
    </form>
    </div> --}}

    {{-- <table class="table">
            <thead>
                <tr class="align-middle text-center">
                    <th scope="col">
                        <a class="text-decoration-none text-black d-flex align-items-center" href="{{ route('admin.projek', ['sort_by' => 'created_at', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}">
    No.
    </a>
    </th>
    <th scope="col"> Nama Proyek</th>
    <th scope="col">Email Proyek</th>
    <th scope="col">
        <a class="text-decoration-none text-black d-flex align-items-center" href="{{ route('admin.projek', ['sort_by' => 'tanggal_projek', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}">
            Tanggal Berlaku
        </a>
    </th>
    <th scope="col">
        <a class="text-decoration-none text-black d-flex align-items-center" href="{{ route('admin.projek', ['sort_by' => 'kadaluwarsa_projek', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}">
            Tanggal Berakhir
        </a>
    </th>
    <th scope="col"></th>
    </tr>
    </thead>
    <tbody>

        @foreach ($projek as $i => $data)

        @php
        $tanggal = Carbon::parse($data['tanggal_projek'])->locale('id')->translatedFormat('d F Y');
        $kadaluwarsa = Carbon::parse($data['kadaluwarsa_projek']);

        $kadaluwarsaStr = $kadaluwarsa->locale('id')->translatedFormat('d F Y');

        $sisaWaktu = $kadaluwarsa->diff(Carbon::now());
        // dd($kadaluwarsa->lessThan(Carbon::now()));
        if($kadaluwarsa->lessThan(Carbon::now())){
        $sisaWaktu = "Expired";
        } else{
        $sisaWaktu = [
        "tahun" => $sisaWaktu->y ? $sisaWaktu->y.' tahun ' : null,
        "bulan" => $sisaWaktu->m ? $sisaWaktu->m.' bulan ' : null,
        "hari" => $sisaWaktu->d ? $sisaWaktu->d.' hari' : null,
        ];

        $sisaWaktu = $sisaWaktu['tahun'] . $sisaWaktu['bulan'] . $sisaWaktu['hari'];
        }
        @endphp
        <tr>
            <th scope="row">{{ $projek->firstItem() + $i }}</th>
            <td>{{ $data['nama_projek'] }}</td>
            <td>{{ $data['email'] }}</td>
            <td class="text-center">{{ $tanggal }}</td>
            <td class="text-center">{{ $kadaluwarsaStr }} <br> ({{ $sisaWaktu }})</td>
            <td>
                <form class="d-inline-block" action="{{ route('detail.projek', ['id' => $data['id']]) }}" method="get"> <button class="mb-1 btn btn-sm btn-info">Detail</button> </form>

                {{-- kirim email --}}
                {{-- <form action="{{ route('projek.sendMail') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $data['id'] }}">
                <input type="hidden" name="tanggal_akhir" value="{{ $kadaluwarsaStr}}">
                <input type="hidden" name="sisa_waktu" value="{{ $sisaWaktu}}">
                <button type="submit" class="mb-1 btn btn-sm btn-warning send-email">Email</button>
                </form> --}}

                {{-- <form class="d-inline-block" action="{{ route('hapus.projek', ['id' => $data['id']]) }}" method="get"> <button class="mb-1 btn btn-sm btn-danger">Hapus</button> </form>
            </td>
        </tr>

        @endforeach
    </tbody>
    </table> --}}

    {{-- <div class="mt-4">

            {{ $projek->links() }}
    </div> --}}

    </div>
</main>
{{-- <hr> --}}
{{-- @dd(json_encode($projek)) --}}
{{-- <div class="container">
    <table class="table table-sm" data-toggle="table" data-search="true" data-show-columns="true" data-pagination="true">
        <thead>
            <tr class="align-middle text-center">
                <th data-sortable="true">No.</th>
                <th data-sortable="true">Nama Projek</th>
                <th>Email</th>
                <th>Tanggal Berlaku</th>
                <th data-sortable="true">Tanggal Berakhir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projek as $data)
            @php
            $tanggal = Carbon::parse($data['tanggal_projek'])->locale('id')->translatedFormat('d F Y');
            $kadaluwarsa = Carbon::parse($data['kadaluwarsa_projek']);

            $kadaluwarsaStr = $kadaluwarsa->locale('id')->translatedFormat('d F Y');

            $sisaWaktu = $kadaluwarsa->diff(Carbon::now());
            // dd($kadaluwarsa->lessThan(Carbon::now()));
            if($kadaluwarsa->lessThan(Carbon::now())){
            $sisaWaktu = "Expired";
            } else{
            $sisaWaktu = [
            "tahun" => $sisaWaktu->y ? $sisaWaktu->y.' tahun ' : null,
            "bulan" => $sisaWaktu->m ? $sisaWaktu->m.' bulan ' : null,
            "hari" => $sisaWaktu->d ? $sisaWaktu->d.' hari' : null,
            ];

            $sisaWaktu = $sisaWaktu['tahun'] . $sisaWaktu['bulan'] . $sisaWaktu['hari'];
            }
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
<td>{{ $data['nama_projek'] }}</td>
<td>{{ $data['email'] }}</td>
<td>{{ $tanggal }}</td>
<td>{{ $kadaluwarsaStr }} <br> ({{ $sisaWaktu }})</td>
<td class="text-center">
    <form class="d-inline-block" action="{{ route('detail.projek', ['id' => $data['id']]) }}" method="get"> <button class="mb-1 btn btn-sm btn-info">Detail</button> </form>

    <button class="mb-1 btn btn-sm btn-warning">Email</button>

    <form class="d-inline-block" action="{{ route('hapus.projek', ['id' => $data['id']]) }}" method="get"> <button class="mb-1 btn btn-sm btn-danger">Hapus</button> </form>
</td>
</tr>
@endforeach
</tbody>
</table>
</div> --}}

{{-- <hr> --}}





<script>
    btn = document.querySelectorAll('.send-email');
    btnDetail = document.querySelectorAll('.btn-detail');
    loading = document.querySelector('#loading');
    // console.log(btn)
    btn.forEach(element => {
        // console.log(element);
        element.addEventListener('click', () => {
            document.querySelector('#loading').classList.add('d-flex');
            document.querySelector('#loading').classList.remove('d-none');
        })
    });

    btnDetail.forEach(element => {
        // console.log(element);
        element.addEventListener('click', () => {
            document.querySelector('#loading').classList.add('d-flex');
            document.querySelector('#loading').classList.remove('d-none');
        })
    });



</script>
@endsection

@section('loadJS')
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
<script src="/js/table-bootstrap/dist/bootstrap-table.min.js"></script>
@endsection
