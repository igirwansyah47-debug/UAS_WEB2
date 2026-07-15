<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('property.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
            <div class="mb-3"><label>Description</label><textarea name="description" class="form-control"></textarea></div>
            <div class="mb-3"><label>Address</label><input type="text" name="address" class="form-control" required></div>
            <div class="mb-3"><label>City</label><input type="text" name="city" class="form-control" required></div>
            <div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control"></div>
            
            <div class="mb-3">
                <label>Lokasi Peta (Opsional)</label>
                <div id="map" style="height: 300px; width: 100%; border-radius: 8px; border: 1px solid #ddd;"></div>
                <small class="text-muted">Klik pada peta untuk menentukan lokasi.</small>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Latitude</label>
                    <input type="text" id="latitude" name="latitude" class="form-control" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Longitude</label>
                    <input type="text" id="longitude" name="longitude" class="form-control" readonly>
                </div>
            </div>

            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Set default to Jakarta
        var map = L.map('map').setView([-6.200000, 106.816666], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var marker = null;
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
            
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);
        });
    </script>
    @endpush
</x-app>