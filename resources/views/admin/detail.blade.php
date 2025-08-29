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

</style>

@endsection

@section('content')
<main class="content py-3">
    <div class="container-fluid p-0">

        <div class="d-flex flex-row justify-content-between align-items-center">

            <h1 class="h3 mb-3">Detail {{ $data->nama_projek }}</h1>
            <a class="btn btn-sm btn-primary rounded" href="{{ route('admin.projek') }}">Kembali</a>
        </div>

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

    iconSize:     [32, 48.043478261 ], // size of the icon
    // shadowSize:   [50, 64], // size of the shadow
    iconAnchor:   [15, 2], // point of the icon which will correspond to marker's location
    // shadowAnchor: [4, 62],  // the same for the shadow
    // popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
});

var markerPUPR = L.marker([-3.3006418708587857, 114.5891828445538], {icon: puIcon}).addTo(map);
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
        var popupContent = "<b>"+dataJson.nama_projek+"</b><br>";
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
        "Garis Jalur": garisLayer
        ,"Titik Tiang": markerLayer
    }).addTo(map);


    // Tambahkan legenda
    var legend = L.control({
        position: "bottomright"
    }); // bisa "topleft", "topright", "bottomleft"

    legend.onAdd = function(map) {
        var div = L.DomUtil.create("div", "info legend");
        div.innerHTML = `
        <h3>Legenda</h3>
        <img src="/img/Banjarmasin_Logo.png" height=24> Balai Besar Pelaksanaan Jalan Nasional<br>
        <i style="background: red; width: 15px; height: 5px; display:inline-block; "></i> Garis Jalur<br>
        <img src="/js/leaflet/dist/images/marker-icon.png" height=24> Titik Tiang<br>
    `;
        return div;
    };

    legend.addTo(map);

</script>


@endsection
