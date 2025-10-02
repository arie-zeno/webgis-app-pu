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
                            <h5 class="fw-lgiht">Berlaku pada tanggal <b>{{ $tanggal }}</b> sampai
                                <b>{{ $kadaluwarsaStr }}</b> ({{ $sisaWaktu }}).
                            </h5>
                            <div class="row justify-content-center mt-3">
                                @forelse ($data->dokumentasi as $dok)
                                    <a href="/storage/{{ $dok->gambar }}" data-toggle="lightbox"
                                        data-caption="{{ $dok->caption }}"
                                        class="col-3  shadow rounded-4 mx-1 my-1 d-flex flex-column bg-light justify-content-center div-image">
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
    // Data dari controller (boleh null / string / array / object)
    let rawLine = @json($data->line ?? null);
    let rawMarkers = @json($data->markers ?? null);
    let namaJalur = @json($data->nama_jalur ?? '');

    // helper: normalisasi input JSON ke Array
    function normalizeJson(raw) {
        if (raw === null) return [];
        // jika Blade men-encode sebagai string (mis: "\"[...]" atau "[]")
        if (typeof raw === 'string') {
            try {
                const parsed = JSON.parse(raw);
                return Array.isArray(parsed) ? parsed : [parsed];
            } catch (err) {
                console.error('normalizeJson: gagal parse string JSON:', err, raw);
                return [];
            }
        }
        if (Array.isArray(raw)) return raw;
        if (typeof raw === 'object') {
            // object mungkin memiliki numeric keys; ubah ke array
            return Object.keys(raw).map(k => raw[k]);
        }
        return [];
    }

    // helper: konversi satu item koordinat jadi [lat, lng] atau null
    function toLatLng(item) {
        if (!item) return null;
        // format object {lat:..., lng:...}
        if (typeof item === 'object' && item.lat !== undefined && item.lng !== undefined) {
            return [Number(item.lat), Number(item.lng)];
        }
        // format array [lat, lng]
        if (Array.isArray(item) && item.length >= 2) {
            return [Number(item[0]), Number(item[1])];
        }
        // kemungkinan object dengan keys "0","1"
        if (item['0'] !== undefined && item['1'] !== undefined) {
            return [Number(item['0']), Number(item['1'])];
        }
        return null;
    }

    let lineCoords = normalizeJson(rawLine);
    let markers = normalizeJson(rawMarkers);

    // debugging singkat (bisa dihapus)
    console.log('rawLine type:', typeof rawLine, rawLine);
    console.log('normalized lineCoords:', lineCoords);
    console.log('rawMarkers type:', typeof rawMarkers, rawMarkers);
    console.log('normalized markers:', markers);

    var map = L.map('map').setView([-3.3006418708587857, 114.5891828445538], 12);
    L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
        maxZoom: 19,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);

    var puIcon = L.icon({
        iconUrl: '/img/Banjarmasin_Logo.png',
        iconSize: [32, 32],
        iconAnchor: [15, 2]
    });

    var markerPUPR = L.marker([-3.3006418708587857, 114.5891828445538], { icon: puIcon }).addTo(map);
    markerPUPR.bindPopup("Balai Besar Pelaksanaan Jalan Nasional");

    var garisLayer = L.layerGroup();
    var markerLayer = L.layerGroup();

    // render polyline — aman meski lineCoords awalnya string/null/object
    if (lineCoords.length > 0) {
        const latlngs = lineCoords.map(toLatLng).filter(x => x !== null);
        if (latlngs.length > 0) {
            const polyline = L.polyline(latlngs, { color: "red", weight: 3 });
            polyline.bindPopup(`<b>${namaJalur}</b>`); // popup saat diklik
            garisLayer.addLayer(polyline);
            try { map.fitBounds(polyline.getBounds()); } catch (err) {}
        }
    }

    // render markers — dukung format object atau array
    if (markers.length > 0) {
        markers.forEach(function(m, idx) {
            const pos = toLatLng(m);
            if (!pos) return;
            const mk = L.marker(pos);
            let popup = `<b>${m.title}</b><br>`;
            // if (m.title) popup += `<b>${m.title}</b><br>`;
            if (m.desc) popup += m.desc;
            mk.bindPopup(popup);
            markerLayer.addLayer(mk);
        });
    }

    garisLayer.addTo(map);
    markerLayer.addTo(map);

    L.control.layers(null, {
        "Garis Jalur": garisLayer,
        "Titik Marker": markerLayer
    }, { collapsed: false }).addTo(map);

    // legenda
    var legend = L.control({ position: "bottomright" });
    legend.onAdd = function(map) {
        var div = L.DomUtil.create("div", "info legend");
        div.innerHTML = `<h3>Legenda</h3>
                         <img src="/img/Banjarmasin_Logo.png" height=15> Balai Besar Pelaksanaan Jalan Nasional<br>
                         <i style="background: red; width: 15px; height: 5px; display:inline-block;"></i> Garis Jalur<br>
                         <img src="/js/leaflet/dist/images/marker-icon.png" height=24> Titik Marker<br>`;
        return div;
    };
    legend.addTo(map);
</script>



@endsection

@section('loadJS')
    // <script src="https://cdn.jsdelivr.net/npm/bs5-lightbox@1.8.5/dist/index.bundle.min.js"></script>
     <script src="/bootstrap5/js/lightbox.js"></script>
@endsection
