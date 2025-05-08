{{--@extends('front.layout.master')--}}
{{--@section('title', 'Store Locations')--}}
{{--@section('body')--}}
{{--    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />--}}
{{--    <style>--}}
{{--        #map { height: 600px; }--}}
{{--        .store-list { max-height: 600px; overflow-y: auto; }--}}
{{--        .store-card { padding: 15px; border-bottom: 1px solid #ddd; cursor: pointer; }--}}
{{--        .store-card:hover { background-color: #f9f9f9; }--}}
{{--        .search-box { margin-bottom: 30px; }--}}
{{--        .direction-link { color: #007bff; text-decoration: none; font-weight: 500; }--}}
{{--        .direction-link i { margin-right: 4px; }--}}
{{--    </style>--}}
{{--    <div class="breacrumb-section">--}}
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                <div class="col-lg-12">--}}
{{--                    <div class="breadcrumb-text">--}}
{{--                        <a href="{{route('front.home')}}"><i class="fa fa-home"></i>Home</a>--}}
{{--                        <span>Our Stores</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="container my-5">--}}
{{--        <h2 class="mb-4">🛍️ Find a Store</h2>--}}

{{--        <form method="GET" action="{{ route('stores.index') }}" class="col-md-5 search-box">--}}
{{--            <input type="text" name="search" class="form-control w-75" placeholder="Enter store name or address..." value="{{ $query ?? '' }}">--}}
{{--        </form>--}}

{{--        <div class="row">--}}
{{--            <div class="col-md-5 store-list">--}}
{{--                @foreach($stores as $index => $store)--}}
{{--                    <div class="store-card" onclick="focusMarker({{ $index }})">--}}
{{--                        <h5><strong>{{ $store->name }}</strong></h5>--}}
{{--                        <p><i class="fa fa-map-marker-alt"></i> {{ $store->address }}</p>--}}
{{--                        <p><i class="fa fa-clock"></i> {{ $store->opening_hours ?? 'N/A' }}</p>--}}
{{--                        <p><i class="fa fa-phone"></i> {{ $store->phone ?? 'N/A' }}</p>--}}
{{--                        <a class="direction-link" href="https://www.google.com/maps/dir/?api=1&destination={{ $store->latitude }},{{ $store->longitude }}" target="_blank">--}}
{{--                            <i class="fa fa-walking"></i> Directions--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--            <div class="col-md-7">--}}
{{--                <div id="map"></div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>--}}
{{--    <script>--}}
{{--        var map = L.map('map').setView([16.047079, 108.206230], 6);--}}

{{--        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {--}}
{{--            attribution: '&copy; OpenStreetMap contributors'--}}
{{--        }).addTo(map);--}}

{{--        var markers = [];--}}
{{--        @foreach($stores as $store)--}}
{{--        var marker = L.marker([{{ $store->latitude }}, {{ $store->longitude }}]).addTo(map)--}}
{{--            .bindPopup(`<strong>{{ $store->name }}</strong><br>{{ $store->address }}<br>{{ $store->phone }}`);--}}
{{--        markers.push(marker);--}}
{{--        @endforeach--}}

{{--        function focusMarker(index) {--}}
{{--            var marker = markers[index];--}}
{{--            map.setView(marker.getLatLng(), 15);--}}
{{--            marker.openPopup();--}}
{{--        }--}}
{{--    </script>--}}
{{--@endsection--}}
@extends('front.layout.master')
@section('title', 'Store Locations')
@section('body')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 600px; }
        .store-list { max-height: 600px; overflow-y: auto; }
        .store-card { padding: 15px; border-bottom: 1px solid #ddd; cursor: pointer; }
        .store-card:hover { background-color: #f9f9f9; }
        .search-box { margin-bottom: 30px; }
        .direction-link { color: #007bff; text-decoration: none; font-weight: 500; }
        .direction-link i { margin-right: 4px; }
    </style>
        <div class="breacrumb-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb-text">
                            <a href="{{route('front.home')}}"><i class="fa fa-home"></i>Home</a>
                            <span>Our Stores</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="container my-5">
        <h2 class="mb-4">Find a Store</h2>

        <form method="GET" action="{{ route('stores.index') }}" class="col-md-5  search-box">
            <input type="text" name="search" class="form-control w-75" placeholder="Enter store name or address..." value="{{ $query ?? '' }}">
        </form>

        <div class="row">
            <div class="col-md-5 store-list">
                @foreach($stores as $index => $store)
                    <div class="store-card" onclick="focusMarker({{ $index }})">
                        <h5><strong>{{ $store->name }}</strong></h5>
                        <p><i class="fa fa-map-marker-alt"></i> {{ $store->address }}</p>
                        <p><i class="fa fa-clock"></i> {{ $store->opening_hours ?? 'N/A' }}</p>
                        <p><i class="fa fa-phone"></i> {{ $store->phone ?? 'N/A' }}</p>
                        <a class="direction-link" href="https://www.google.com/maps/dir/?api=1&destination={{ $store->latitude }},{{ $store->longitude }}" target="_blank">
                            <i class="fa fa-walking"></i> Directions
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="col-md-7">
                <div id="map"></div>
            </div>
        </div>

        <div class="mt-5">
            <h2 class="mb-4">Featured Stores</h2>
            <div class="row">
                @foreach($featuredStores as $store)
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <img src="{{ asset('storage/stores/' . $store->image) }}" alt="{{ $store->name }}" class="img-fluid">
                            <div class="card-body">
                                <h5><strong>{{ $store->name }}</strong></h5>
                                <p></p>
                                <p><i class="fa fa-map-marker-alt"></i> {{ $store->address }}</p>
                                <p><i class="fa fa-clock"></i> {{ $store->opening_hours ?? 'N/A' }}</p>
                                <p><i class="fa fa-phone"></i> {{ $store->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([16.047079, 108.206230], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var markers = [];
        @foreach($stores as $store)
        var marker = L.marker([{{ $store->latitude }}, {{ $store->longitude }}]).addTo(map)
            .bindPopup(`<strong>{{ $store->name }}</strong><br>{{ $store->address }}<br>{{ $store->phone }}`);
        markers.push(marker);
        @endforeach

        function focusMarker(index) {
            var marker = markers[index];
            map.setView(marker.getLatLng(), 15);
            marker.openPopup();
        }
    </script>
@endsection
