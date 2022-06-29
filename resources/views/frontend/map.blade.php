@extends('layout.skeleton')

@section('content')
<div class="card">
    <div class="card-body" id="mapid"></div>
</div>
<div class="col-md-6 mt-5">
    <input id="search" class="form-control" type="text">
    <button class="btn btn-primary mt-3">Tes <i class="fas fa-search"></i></button>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
    integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
    crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.76.1/dist/L.Control.Locate.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

<style>
    #mapid { min-height: 500px; }
</style>
@endsection

@push('scripts')
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
    integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
    crossorigin=""></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.76.1/dist/L.Control.Locate.min.js" charset="utf-8"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    var map = L.map('mapid').setView([{{ config('leaflet.map_center_latitude') }}, {{ config('leaflet.map_center_longitude') }}], {{ config('leaflet.zoom_level') }});

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    var markers = L.markerClusterGroup();
    L.Control.geocoder().addTo(map);

    axios.get('{{ route('sport-arena.list') }}')
    .then(function (response) {
        var marker = L.geoJSON(response.data, {
            pointToLayer: function(geoJsonPoint, latlng) {
                return L.marker(latlng).bindPopup(function (layer) {
                    return layer.feature.properties.map_popup_content;
                });
            }
        });
        markers.addLayer(marker);
    })
    .catch(function (error) {
        console.log(error);
    });
    map.addLayer(markers);

    //get current location
    L.control.locate().addTo(map);

    $('#search').on('keyup', function() {
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content;
        axios.defaults.headers.common['Content-Type'] = 'application/json';
        axios.defaults.headers.common['Accept'] = 'application/json';
        axios.defaults.headers.common['Methods'] = "DELETE, POST, GET, OPTIONS";
        
        let url = 'https://api.geoapify.com/v1/geocode/search?text='+$(this).val()+'&apiKey=6dc7fb95a3b246cfa0f3bcef5ce9ed9a';

        axios.get(url)
        .then(function (response) {
            if (response.featureCollection.features.length === 0) {
                document.getElementById("search").textContent = "The address is not found";
                return;
            }

            console.log(response)
        })
        .catch(function (error) {
            console.log(error.message);
        });
    });
</script>
@endpush
