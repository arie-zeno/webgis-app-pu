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
                            <form action="{{ route('tambah2.projek') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="lineInput" name="line">
                                <input type="hidden" id="markersInput" name="markers">

                            <div class="row">

                                    <div class="col-4">
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
                                    </div>

                                    <div class="col-5 border-start">
                                        <div class="mb-3">
                                            <label for="name">Nama Jalur</label>
                                            <input type="text" name="nama_jalur" class="form-control" required>
                                        </div>
                                        <div id="map" style="height: 500px;"></div>

                                        <br><br>
                                    </div>

                                    <div class="col-3 border-start">
                                        <h5>Marker Aktif</h5>
                                        <div id="markerList"></div>


                                        <br><br>
                                        <button id="btn-tambah" type="submit" class="btn text-white w-100"
                                            style="background-color:rgb(48, 48, 114);">Tambah Data</button>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Leaflet Draw -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <!-- GeometryUtil + Snap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-geometryutil/0.9.3/leaflet.geometryutil.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-snap/0.0.2/leaflet.snap.js"></script>

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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var map = L.map('map').setView([-3.3006418708587857, 114.5891828445538], 12);

        // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //     attribution: '© OpenStreetMap'
        // }).addTo(map);

        L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 19,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);

        var puIcon = L.icon({
            iconUrl: '/img/Banjarmasin_Logo.png',

            iconSize: [32, 32], // size of the icon
            // shadowSize:   [50, 64], // size of the shadow
            iconAnchor: [15, 2], // point of the icon which will correspond to marker's location
            // shadowAnchor: [4, 62],  // the same for the shadow
            // popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
        });

        var markerPUPR = L.marker([-3.3006418708587857, 114.5891828445538], {
            icon: puIcon
        }).addTo(map);
        markerPUPR.bindPopup("Balai Besar Pelaksanaan Jalan Nasional").openPopup();

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                polygon: false,
                rectangle: false,
                circle: false,
                circlemarker: false,
                marker: false,
                polyline: true
            },
            edit: {
                featureGroup: drawnItems
            }
        });
        map.addControl(drawControl);

        var polylineCoords = [];
        var markersData = [];
        var markersLayer = {};
        var polylineFinished = false;
        var currentPolylineLayer = null;
        var activeMarkerIndex = null;

        var snapCursor = null;

        function clearAll() {
            drawnItems.clearLayers();
            polylineCoords = [];
            markersData = [];
            markersLayer = {};
            activeMarkerIndex = null;
            polylineFinished = false;
            currentPolylineLayer = null;
            document.getElementById("markerList").innerHTML = "";
            if (snapCursor) {
                map.removeLayer(snapCursor);
                snapCursor = null;
            }
        }

        // polyline dibuat
        map.on(L.Draw.Event.CREATED, function(event) {
            if (event.layerType === 'polyline') {
                clearAll();

                currentPolylineLayer = event.layer;
                drawnItems.addLayer(currentPolylineLayer);
                polylineCoords = currentPolylineLayer.getLatLngs().map(p => ({
                    lat: p.lat,
                    lng: p.lng
                }));
                polylineFinished = true;

                snapCursor = L.circleMarker([0, 0], {
                    radius: 6,
                    color: 'red',
                    fillColor: 'red',
                    fillOpacity: 0.6,
                    interactive: false
                }).addTo(map);

                alert("Polyline berhasil dibuat. Klik di garis untuk tambah marker.");
            }
        });

        // hapus polyline
        map.on('draw:deleted', function() {
            clearAll();
        });

        // gerakkan phantom marker (snap ke line)
        map.on('mousemove', function(e) {
            if (!polylineFinished || !currentPolylineLayer || !snapCursor) return;
            var snappedLatLng = L.GeometryUtil.closest(map, currentPolylineLayer, e.latlng);
            snapCursor.setLatLng(snappedLatLng);
        });

        // klik map → tambah marker
        map.on('click', function(e) {
            if (!polylineFinished) return;

            var latlng = e.latlng;
            var markerIndex = markersData.length;

            var marker = L.marker(latlng).addTo(drawnItems);
            markersLayer[markerIndex] = marker;

            markersData.push({
                lat: latlng.lat,
                lng: latlng.lng,
                title: '',
                desc: ''
            });

            marker.on('click', function(ev) {
                L.DomEvent.stopPropagation(ev);
                activeMarkerIndex = markerIndex;
                renderMarkerForms();
            });

            activeMarkerIndex = markerIndex;
            renderMarkerForms();
        });

        // render ulang semua marker forms
        function renderMarkerForms() {
            var container = document.getElementById("markerList");
            container.innerHTML = "";

            markersData.forEach(function(marker, idx) {
                if (!marker) return;

                var card = document.createElement("div");
                card.className = "card p-2 mb-2";

                if (idx === activeMarkerIndex) {
                    card.innerHTML = `
                        <label>Judul</label>
                        <input type="text" class="form-control mb-1 marker-title" 
                            name="judul_marker[]" value="${marker.title}" data-index="${idx}">
                        <label>Deskripsi</label>
                        <textarea class="form-control marker-desc" 
                            name="deskripsi_marker[]" data-index="${idx}">${marker.desc}</textarea>
                        <button type="button" class="btn btn-sm btn-danger mt-2 remove-marker" data-index="${idx}">Hapus</button>
                    `;
                } else {
                    card.innerHTML = `
                        <input type="hidden" class="marker-title" 
                            name="judul_marker[]" value="${marker.title}" data-index="${idx}">
                        <input type="hidden" class="marker-desc" 
                            name="deskripsi_marker[]" value="${marker.desc}" data-index="${idx}">
                        <small class="text-muted">Marker #${idx + 1} (klik untuk edit)</small>
                        <button type="button" class="btn btn-sm btn-danger mt-2 remove-marker" data-index="${idx}">Hapus</button>
                    `;
                }

                container.appendChild(card);
            });
            var cleanedMarkers = markersData.filter(m => m !== null);
            document.getElementById("lineInput").value = JSON.stringify(polylineCoords);
            document.getElementById("markersInput").value = JSON.stringify(cleanedMarkers);
        }

        // update marker data
        document.addEventListener("input", function(e) {
            if (e.target.classList.contains("marker-title")) {
                var idx = e.target.dataset.index;
                if (markersData[idx]) markersData[idx].title = e.target.value;
            }
            if (e.target.classList.contains("marker-desc")) {
                var idx = e.target.dataset.index;
                if (markersData[idx]) markersData[idx].desc = e.target.value;
            }
        });

        // hapus marker
        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("remove-marker")) {
                var idx = e.target.dataset.index;
                if (markersLayer[idx]) {
                    drawnItems.removeLayer(markersLayer[idx]);
                    delete markersLayer[idx];
                }
                markersData[idx] = null;
                if (activeMarkerIndex == idx) activeMarkerIndex = null;
                renderMarkerForms();
            }
        });

        // submit
        document.getElementById("btn-tambah").addEventListener("submit", function(e) {
            if (polylineCoords.length === 0) {
                alert("Harap gambar polyline terlebih dahulu!");
                e.preventDefault();
                return;
            }
            var cleanedMarkers = markersData.filter(m => m !== null);
            document.getElementById("lineInput").value = JSON.stringify(polylineCoords);
            document.getElementById("markersInput").value = JSON.stringify(cleanedMarkers);
        });
    });
</script>


@endsection
