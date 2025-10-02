@extends('admin.layout.header')
@section('title', 'Pemetaan')
@section('leafletJS')

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">



    <script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js'></script>

    <link rel="stylesheet" href="/js/leaflet/dist/leaflet.css" />
    <script src="/js/leaflet/dist/leaflet.js"></script>

    {{-- side panel --}}
    <link rel="stylesheet" href="/js/leaflet/dist/L.Control.SlideMenu.css">
    <script src="/js/leaflet/dist/L.Control.SlideMenu.js"></script>

    <style>
        .info.legend {
            background: white;
            padding: 10px;
            border-radius: 5px;
            line-height: 18px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            font-size: 13px;
        }

        li.item-side-panel:hover {
            opacity: .3;
            font-weight: bold;
            color: red;
        }
    </style>



@endsection

@section('content')
    {{-- @dd($data); --}}
    {{-- loading --}}
    <div id="loading" class="position-fixed top-0 bottom-0 start-0 end-0 d-flex align-items-center justify-content-center"
        style="background-color: #ffffff7c; backdrop-filter: blur(2px); z-index: 9999;">
        <img src="/img/pu_nobg.png" alt="" width="600">
    </div>

    <main class="content mt-0 py-3">
        <div class="container-fluid p-0">
            <h1 class="h3 mb-3">Pemetaan Pembangunan</h1>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="map" style="height: 80vh">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>


<script>
    let dataJson = @json($data); // koleksi data projek
    let kmlLayers = {};

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

    var markerPUPR = L.marker([-3.3006418708587857, 114.5891828445538], {
        icon: puIcon
    }).addTo(map);
    markerPUPR.bindPopup("Balai Besar Pelaksanaan Jalan Nasional");

    var garisLayer = L.layerGroup().addTo(map);
    var markerLayer = L.layerGroup().addTo(map);

    // --- fungsi util ---
    function normalizeJson(raw) {
        if (!raw) return [];
        if (typeof raw === 'string') {
            try {
                const parsed = JSON.parse(raw);
                return Array.isArray(parsed) ? parsed : [parsed];
            } catch (err) {
                return [];
            }
        }
        if (Array.isArray(raw)) return raw;
        if (typeof raw === 'object') return Object.values(raw);
        return [];
    }
    function toLatLng(item) {
        if (!item) return null;
        if (item.lat !== undefined && item.lng !== undefined) return [Number(item.lat), Number(item.lng)];
        if (Array.isArray(item) && item.length >= 2) return [Number(item[0]), Number(item[1])];
        if (item['0'] !== undefined && item['1'] !== undefined) return [Number(item['0']), Number(item['1'])];
        return null;
    }

    // --- looping semua proyek ---
    dataJson.forEach(element => {
        if (element.file_koordinat) {
            loadKML(element);
        } else {
            loadFromJson(element);
        }
    });

    // --- fungsi load KML ---
    function loadKML(data) {
        var customLayer = L.geoJson(null, {
            style: function(feature) {
                return { color: "red", weight: 3 };
            },
            onEachFeature: function(feature, layer) {
                if (!kmlLayers[data.nama_projek]) kmlLayers[data.nama_projek] = [];
                kmlLayers[data.nama_projek].push(layer);

                if (feature.geometry.type === "Point") {
                    markerLayer.addLayer(layer);
                } else {
                    garisLayer.addLayer(layer);
                }

                // popup
                var popupContent = `<a href="/projek/detail/${data.id}">${data.nama_projek}</a><hr>`;
                if (feature.properties?.name) popupContent += "<b>" + feature.properties.name + "</b> <br>";
                if (feature.properties?.description) popupContent += feature.properties.description;
                layer.bindPopup(popupContent);
            }
        });

        var runLayer = omnivore.kml('/storage/' + data.file_koordinat, null, customLayer);
        runLayer.on("ready", function() {
            if (!kmlLayers[data.nama_projek]) kmlLayers[data.nama_projek] = [];
            kmlLayers[data.nama_projek].push(runLayer);
        });
    }

    // --- fungsi load JSON dari DB ---
    function loadFromJson(data) {
        if (!kmlLayers[data.nama_projek]) kmlLayers[data.nama_projek] = [];

        // polyline
        let lineCoords = normalizeJson(data.line);
        if (lineCoords.length > 0) {
            const latlngs = lineCoords.map(toLatLng).filter(x => x !== null);
            if (latlngs.length > 0) {
                const polyline = L.polyline(latlngs, { color: "red", weight: 3 });
                polyline.bindPopup(`<a href="/projek/detail/${data.id}">${data.nama_projek}</a>`);
                garisLayer.addLayer(polyline);
                kmlLayers[data.nama_projek].push(polyline);
            }
        }

        // markers
        let markers = normalizeJson(data.markers);
        if (markers.length > 0) {
            markers.forEach(m => {
                const pos = toLatLng(m);
                if (!pos) return;
                const marker = L.marker(pos);
                let popup = `<a href="/projek/detail/${data.id}">${data.nama_projek}</a><hr>`;
                if (m.title) popup += `<b>${m.title}</b><br>`;
                if (m.desc) popup += m.desc;
                marker.bindPopup(popup);
                markerLayer.addLayer(marker);
                kmlLayers[data.nama_projek].push(marker);
            });
        }
    }

    // --- layer control ---
    L.control.layers(null, {
        "Garis Jalur": garisLayer,
        "Titik Tiang": markerLayer
    }, { collapsed: false }).addTo(map);

    // --- legenda ---
    var legend = L.control({ position: "bottomright" });
    legend.onAdd = function(map) {
        var div = L.DomUtil.create("div", "info legend");
        div.innerHTML = `
            <h3>Legenda</h3>
            <img src="/img/Banjarmasin_Logo.png" height=15> Balai Besar Pelaksanaan Jalan Nasional<br>
            <i style="background: red; width: 15px; height: 5px; display:inline-block;"></i> Garis Jalur<br>
            <img src="/js/leaflet/dist/images/marker-icon.png" height=24> Titik Tiang<br>
        `;
        return div;
    };
    legend.addTo(map);

    // --- daftar proyek di slide panel ---
    let menuHtml = "<h3>Daftar Proyek</h3><ul style='list-style:none; padding-left:0;'>";
    dataJson.forEach(p => {
        menuHtml += `<li class="item-side-panel text-black" style="cursor:pointer; margin:4px 0;" onclick="flyToProject('${p.nama_projek}')">${p.nama_projek}</li>`;
    });
    menuHtml += "</ul>";

    L.control.slideMenu(menuHtml, { position: "topleft" }).addTo(map);

    // --- fungsi zoom/fly ---
    function zoomToProject(namaProjek) {
        let layers = kmlLayers[namaProjek];
        if (!layers) return;
        let group = L.featureGroup(layers);
        map.fitBounds(group.getBounds(), { padding: [30, 30] });
    }
    function flyToProject(namaProjek) {
        let layers = kmlLayers[namaProjek];
        if (!layers) return;
        let group = L.featureGroup(layers);
        let center = group.getBounds().getCenter();
        map.flyTo(center, 13, { animate: true, duration: 1.5 });
    }

    window.addEventListener('load', () => {
        document.querySelector('#loading').classList.remove('d-flex');
        document.querySelector('#loading').classList.add('d-none');
    })
</script>



@endsection
