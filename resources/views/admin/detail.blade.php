@extends('admin.layout.header')
@section('title', $data->nama_projek)
@section('leafletJS')

    <script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js'></script>

    <link rel="stylesheet" href="/js/leaflet/dist/leaflet.css" />
    <script src="/js/leaflet/dist/leaflet.js"></script>
    <style>
        .info.legend {
            background: white;
            padding: 10px;
            border-radius: 5px;
            line-height: 18px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            font-size: 13px;
        }

        .div-image{
            overflow: hidden;
        }
        .div-image:hover img{
            transform: scale(1.2);
            transition: .2s;

        }
    </style>

@endsection

@php
    use Illuminate\Support\Carbon;
@endphp
@section('content')
    @php
        $tanggal = Carbon::parse($data['tanggal_projek'])->locale('id')->translatedFormat('d F Y');
        $kadaluwarsa = Carbon::parse($data['kadaluwarsa_projek']);

        $kadaluwarsaStr = $kadaluwarsa->locale('id')->translatedFormat('d F Y');

        $sisaWaktu = $kadaluwarsa->diff(Carbon::now());
        // dd($kadaluwarsa->lessThan(Carbon::now()));
        if ($kadaluwarsa->lessThan(Carbon::now())) {
            $sisaWaktu = 'Expired';
        } else {
            $sisaWaktu = [
                'tahun' => $sisaWaktu->y ? $sisaWaktu->y . ' tahun ' : null,
                'bulan' => $sisaWaktu->m ? $sisaWaktu->m . ' bulan ' : null,
                'hari' => $sisaWaktu->d ? $sisaWaktu->d . ' hari' : null,
            ];

            $sisaWaktu = 'Tersisa ' . $sisaWaktu['tahun'] . $sisaWaktu['bulan'] . $sisaWaktu['hari'] . ' lagi';
        }
    @endphp

    <main class="content py-3">
        <div class="container-fluid p-0">

            <div class="d-flex flex-row justify-content-between align-items-center">

                <h1 class="h3 mb-3">Detail {{ $data->nama_projek }}</h1>
                {{-- <a class="btn btn-sm btn-primary rounded" href="{{ route('admin.projek') }}">Kembali</a> --}}
                <a class="btn btn-sm btn-outline-danger rounded " href="{{ back()->getTargetUrl() }}">Kembali</a>

            </div>

            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body border-start border-warning border-3 rounded">
                            <h1>{{ $data->nama_projek }}</h1>
                            <h6 class="text-secondary">{{ $data->email }}</h6>
                            <h5 class="fw-lgiht">Berlaku pada tanggal <b>{{ $tanggal }}</b>, akan berakhir pada tanggal
                                <b>{{ $kadaluwarsaStr }}</b> ({{ $sisaWaktu }}).
                            </h5>
                            <div class="row justify-content-center mt-3">
                                @forelse ($data->dokumentasi as $dok)
                                    <a href="/storage/{{ $dok->gambar }}" data-toggle="lightbox"
                                        data-caption="{{ $dok->caption }}"
                                        class="col-3  shadow rounded-4 mx-1 d-flex flex-column bg-light justify-content-center div-image">
                                        <img src="/storage/{{ $dok->gambar }}" class="img-fluid">
                                    </a>

                                @empty
                                    <p class="fw-lgith">Belum ada dokuemntasi.</p>
                                @endforelse

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="map" style="height: 70vh">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
    {{-- <div class="container  mx-auto">
    <div class="d-flex justify-content-end mt-5">
        <form action="{{ route('admin.projek') }}" method="GET">


<button type="submit" class="btn btn-sm btn-primary">Kembali</button>
</form>
</div>
<h1>Test Detail {{ $data->email }}</h1>

</div> --}}

    <script>
        let dataJson = <?php echo json_encode($data); ?>;
        var map = L.map('map').setView([-3.3006418708587857, 114.5891828445538], 12);

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

        var garisLayer = L.layerGroup();
        var markerLayer = L.layerGroup();

        var customLayer = L.geoJson(null, {
            style: function(feature) {
                // Style untuk garis/polygon
                return {
                    color: "red",
                    weight: 3,
                };
            },
            onEachFeature: function(feature, layer) {
                if (feature.geometry.type === "Point") {
                    markerLayer.addLayer(layer);
                } else {
                    garisLayer.addLayer(layer);
                }
                // Tambahkan popup
                var popupContent = "<b>" + dataJson.nama_projek + "</b><br>";
                if (feature.properties && feature.properties.name) {
                    popupContent += "<b>" + feature.properties.name + "</b> <br> ";
                }
                if (feature.properties && feature.properties.description) {
                    popupContent += feature.properties.description;
                }
                if (popupContent) {
                    layer.bindPopup(popupContent);
                }
            }
            // , pointToLayer: function(feature, latlng) {
            // return L.marker(latlng); // marker default
            // }
        });

        var runLayer = omnivore.kml('/storage/' + dataJson.file_koordinat, null, customLayer)
            .on('ready', function() {
                map.fitBounds(runLayer.getBounds()); // zoom ke layer KML
            });

        garisLayer.addTo(map);
        markerLayer.addTo(map);

        L.control.layers(null, {
            "Garis Jalur": garisLayer,
            "Titik Tiang": markerLayer
        }, {
            collapsed: false
        }).addTo(map);


        // Tambahkan legenda
        var legend = L.control({
            position: "bottomright"
        }); // bisa "topleft", "topright", "bottomleft"

        legend.onAdd = function(map) {
            var div = L.DomUtil.create("div", "info legend");
            div.innerHTML = `<h3>Legenda</h3>
                            <img src="/img/Banjarmasin_Logo.png" height=15> Balai Besar Pelaksanaan Jalan Nasional<br>
                            <i style="background: red; width: 15px; height: 5px; display:inline-block; "></i> Garis Jalur<br>
                            <img src="/js/leaflet/dist/images/marker-icon.png" height=24> Titik Tiang<br>`;
            return div;
        };

        legend.addTo(map);
    </script>


@endsection

@section('loadJS')
    <script src="https://cdn.jsdelivr.net/npm/bs5-lightbox@1.8.5/dist/index.bundle.min.js"></script>
@endsection
