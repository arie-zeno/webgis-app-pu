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
<div id="loading" class="position-fixed top-0 bottom-0 start-0 end-0 d-flex align-items-center justify-content-center" style="background-color: #0000007c; backdrop-filter: blur(2px); z-index: 9999;">
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
    //     let dataJson = <?php echo json_encode($data); ?>;

    //     let kmlLayers = {};

    //     var map = L.map('map').setView([-3.2956964353281704, 114.58920471974757], 12);

    //     L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
    //         maxZoom: 19,
    //         subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    //     }).addTo(map);

    //     var garisLayer = L.layerGroup();
    //     var markerLayer = L.layerGroup();
    //     dataJson.forEach(element => {
    //         // console.log(element.email)
    //         loadKML(element)
    //         console.log(kmlLayers);
    //     });


    // function loadKML(data){
    //     var customLayer = L.geoJson(null, {
    //         style: function(feature) {
    //             // Style untuk garis/polygon
    //             return {
    //                 color: "yellow",
    //                 weight: 3,
    //                 opacity: 0.9
    //             };
    //         },
    //         onEachFeature: function(feature, layer) {
    //             if (!kmlLayers[data.nama_projek]) kmlLayers[data.nama_projek] = [];
    //             kmlLayers[data.nama_projek].push(layer);
    //             // console.log(data)
    //             if (feature.geometry.type === "Point") {
    //                 markerLayer.addLayer(layer);
    //             } else {
    //                 garisLayer.addLayer(layer);
    //             }
    //             // Tambahkan popup
    //             var popupContent = "";
    //             if (feature.properties && feature.properties.name) {
    //                 popupContent += "<b>" + feature.properties.name + "</b> < br / > ";
    //     }
    //     if (feature.properties && feature.properties.description) {
    //         popupContent += feature.properties.description;
    //     }
    //     if (popupContent) {
    //         layer.bindPopup(popupContent);
    //     }
    //     }
    //     // , pointToLayer: function(feature, latlng) {
    //     // return L.marker(latlng); // marker default
    //     // }
    //     });

    //     var runLayer = omnivore.kml('/storage/' + data.file_koordinat, null, customLayer);

    //     garisLayer.addTo(map);
    //     }

    //     // markerLayer.addTo(map);

    //     L.control.layers(null, {
    //         "Garis Jalur": garisLayer
    //         , "Titik Tiang": markerLayer
    //     }).addTo(map);


    //     // Tambahkan legenda
    //     var legend = L.control({
    //         position: "bottomright"
    //     }); // bisa "topleft", "topright", "bottomleft"

    //     legend.onAdd = function(map) {
    //         var div = L.DomUtil.create("div", "info legend");

    //         div.innerHTML = `
    //         <h3>Legenda</h3>
    //         <i style="background: yellow; width: 15px; height: 10px; display: inline-block; margin-right: 5px;"></i> Garis Jalur<br>
    //         <i style="background: blue; width: 15px; height: 10px; display: inline-block; margin-right: 5px;"></i> Titik Lokasi<br>
    //     `;

    //         return div;
    //     };

    //     legend.addTo(map);
    //     L.control.slideMenu('<p>test</p>').addTo(map);

    let dataJson = <?php echo json_encode($data); ?>;
let kmlLayers = {};

var map = L.map('map').setView([-3.3006418708587857, 114.5891828445538], 12);

L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
    maxZoom: 19,
    subdomains: ['mt0','mt1','mt2','mt3']
}).addTo(map);

var puIcon = L.icon({
    iconUrl: '/img/Banjarmasin_Logo.png',

    iconSize:     [32, 32 ], // size of the icon
    // shadowSize:   [50, 64], // size of the shadow
    iconAnchor:   [15, 2], // point of the icon which will correspond to marker's location
    // shadowAnchor: [4, 62],  // the same for the shadow
    // popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
});

var markerPUPR = L.marker([-3.3006418708587857, 114.5891828445538], {icon: puIcon}).addTo(map);
markerPUPR.bindPopup("Balai Besar Pelaksanaan Jalan Nasional").openPopup();

var garisLayer = L.layerGroup().addTo(map);
var markerLayer = L.layerGroup().addTo(map);

// Load semua KML
dataJson.forEach(element => {
    loadKML(element);
});

function loadKML(data){
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

            // Tambahkan popup
            var popupContent = "<b>"+data.nama_projek+"</b><hr>";
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
    });

    var runLayer = omnivore.kml('/storage/' + data.file_koordinat, null, customLayer);

    // Simpan referensi runLayer untuk bounding
    runLayer.on("ready", function() {
        if (!kmlLayers[data.nama_projek]) kmlLayers[data.nama_projek] = [];
        kmlLayers[data.nama_projek].push(runLayer);
    });
    }

    // Control untuk ganti layer garis/marker
    L.control.layers(null, {
        "Garis Jalur": garisLayer
        , "Titik Tiang": markerLayer
    }).addTo(map);

    // Tambahkan legenda
    var legend = L.control({
        position: "bottomright"
    });
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

    // Buat slide panel isi nama projek
    let menuHtml = "<h3>Daftar Proyek</h3><ul style='list-style:none; padding-left:0;'>";
    dataJson.forEach(p => {
        menuHtml += `<li class="item-side-panel text-black" style="cursor:pointer; margin:4px 0;" onclick="flyToProject('${p.nama_projek}')">${p.nama_projek}</li>`;
    });
    menuHtml += "</ul>";

    L.control.slideMenu(menuHtml, {
        position: "topleft"
    }).addTo(map);

    // Fungsi zoom ke proyek
    function zoomToProject(namaProjek) {
        let layers = kmlLayers[namaProjek];
        if (!layers) return;

        let group = L.featureGroup(layers);
        map.fitBounds(group.getBounds(), {
            padding: [30, 30]
        });
    }

    function flyToProject(namaProjek) {
        let layers = kmlLayers[namaProjek];
        if (!layers) return;

        let group = L.featureGroup(layers);
        let center = group.getBounds().getCenter(); // cari titik tengah semua feature

        map.flyTo(center, 13, { // zoom bisa kamu sesuaikan (misal 14/15)
            animate: true
            , duration: 1.5
        });
    }

    window.addEventListener('load', () => {
        document.querySelector('#loading').classList.remove('d-flex');
        document.querySelector('#loading').classList.add('d-none');
    })

</script>


@endsection
