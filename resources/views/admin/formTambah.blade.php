@extends('admin.layout.header')
@section('title', 'Tambah Projek')
@section('content')
    {{-- loading --}}
    <div id="loading" class="position-fixed top-0 bottom-0 start-0 end-0 d-none align-items-center justify-content-center"
        style="background-color: #ffffff7c; backdrop-filter: blur(2px); z-index: 99;">
        <img src="/img/pu_nobg.png" alt="" width="600">
    </div>
    {{-- endloading --}}
    <main class="content">
        <div class="container-fluid p-0">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-3">Tambah Projek</h1>
                <a class="btn btn-sm btn-primary rounded" href="{{ route('admin.projek') }}">Kembali</a>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    {{-- Form utama hanya sampai sini --}}
                                    <form action="{{ route('tambah.projek') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="nama_projek" class="form-label">Nama Proyek</label>
                                            <input type="text" required class="form-control" name="nama_projek">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email_projek" class="form-label">Email Proyek</label>
                                            <input type="email" required class="form-control" name="email_projek">
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal_mulai" class="form-label">Tanggal Berlaku</label>
                                            <input type="date" required class="form-control" name="tanggal_mulai">
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal_akhir" class="form-label">Tanggal Berakhir</label>
                                            <input type="date" required class="form-control" name="tanggal_akhir">
                                        </div>
                                        <div class="mb-3">
                                            <label for="file_koordinat" class="form-label">Koordinat Proyek</label>
                                            <input type="file" required class="form-control" name="file_koordinat">
                                        </div>
                                </div>

                                <div class="col-6 border-start">
                                    <div id="dokumentasi-wrapper">
                                        <div class="dokumentasi-item mb-3">
                                            <label for="dokumentasi-0" class="form-label">Dokumentasi 1</label>
                                            <input id="dokumentasi-0" type="file" name="gambars[]"
                                                class="form-control mb-2" onchange="previewImage(this, 0)" required>
                                            <div id="preview-0" class="mb-2"></div>
                                            <input type="text" name="caption[]" class="form-control"
                                                placeholder="Keterangan" required>
                                        </div>

                                        <div class="dokumentasi-item mb-3 d-none">
                                            <label for="dokumentasi-1" class="form-label">Dokumentasi 2</label>
                                            <input id="dokumentasi-1" type="file" name="gambars[]"
                                                class="form-control mb-2" onchange="previewImage(this, 1)">
                                            <div id="preview-1" class="mb-2"></div>
                                            <input type="text" name="caption[]" class="form-control"
                                                placeholder="Keterangan">
                                        </div>

                                        <div class="dokumentasi-item mb-3 d-none">
                                            <label for="dokumentasi-2" class="form-label">Dokumentasi 3</label>
                                            <input id="dokumentasi-2" type="file" name="gambars[]"
                                                class="form-control mb-2" onchange="previewImage(this, 2)">
                                            <div id="preview-2" class="mb-2"></div>
                                            <input type="text" name="caption[]" class="form-control"
                                                placeholder="Keterangan">
                                        </div>

                                        <div class="dokumentasi-item mb-3 d-none">
                                            <label for="dokumentasi-3" class="form-label">Dokumentasi 4</label>
                                            <input id="dokumentasi-3" type="file" name="gambars[]"
                                                class="form-control mb-2" onchange="previewImage(this, 3)">
                                            <div id="preview-3" class="mb-2"></div>
                                            <input type="text" name="caption[]" class="form-control"
                                                placeholder="Keterangan">
                                        </div>

                                        <div class="dokumentasi-item mb-3 d-none">
                                            <label for="dokumentasi-4" class="form-label">Dokumentasi 5</label>
                                            <input id="dokumentasi-4" type="file" name="gambars[]"
                                                class="form-control mb-2" onchange="previewImage(this, 4)">
                                            <div id="preview-4" class="mb-2"></div>
                                            <input type="text" name="caption[]" class="form-control"
                                                placeholder="Keterangan">
                                        </div>

                                        



                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary w-100"
                                        onclick="addDokumentasi()">+ Tambah Dokumentasi</button>
                                    <br><br>
                                    <button id="btn-tambah" type="submit" class="btn text-white w-100"
                                        style="background-color:rgb(48, 48, 114);">Tambah Data</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        let index = 2;
        let div = [];

        let dokumentasi_item = document.querySelectorAll(".dokumentasi-item");
        dokumentasi_item.forEach((element, index) => {
            if (element.classList.contains('d-none')) {
                // console.log(index);
                // console.log(element);
                div.push(element)
            }
        });
        console.log(div);

        

        function addDokumentasi() {
            div[0].classList.toggle("d-none");
            div.shift();
            // let wrapper = document.getElementById('dokumentasi-wrapper');
        //     let item = document.createElement('div');
        //     item.classList.add('dokumentasi-item', 'mb-3');
        //     item.innerHTML = `
        //     <label for="dokumentasi-${index}" class="form-label">Dokumentasi ${index}</label>

        //     <input type="file" name="gambars[]" class="form-control mb-2" onchange="previewImage(this, ${index})" required>

        //     <div id="preview-${index}" class="mb-2"></div>
        //     <input type="text" name="caption[]" class="form-control" placeholder="Caption..." required>
        //     <button type="button" class="btn btn-sm btn-outline-danger rounded mt-2" onclick="this.parentElement.remove()">Hapus</button>
        // `;
        //     wrapper.appendChild(item);
        //     index++;
        }

        // fungsi untuk preview gambar
        function previewImage(input, idx) {
            const previewContainer = document.getElementById(`preview-${idx}`);
            previewContainer.innerHTML = ""; // reset dulu
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let img = document.createElement("img");
                    img.src = e.target.result;
                    img.style.maxWidth = "200px";
                    img.style.maxHeight = "150px";
                    img.classList.add("img-thumbnail");
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        btn = document.querySelector('#btn-tambah');
        loading = document.querySelector('#loading');
        // console.log(btn)
        // btn.addEventListener('click', () => {
        //     loading.classList.add('d-flex');
        //     loading.classList.remove('d-none');
        // })
    </script>

@endsection
