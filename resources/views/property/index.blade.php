<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        @if(in_array(Auth::user()->role, ['superadmin', 'owner']))
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('property.create') }}">Tambah</a>
        </div>
        @endif
        @if(Auth::user()->role === 'tenant')
        <div class="mb-4">
            <h5>Peta Persebaran Kos</h5>
            <div id="map" style="height: 400px; width: 100%; border-radius: 8px; border: 1px solid #ddd;"></div>
        </div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($properties as $property)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $property->name }}</td>
                        <td>{{ $property->city }}</td>
                        <td>{{ $property->address }}</td>
                        <td>
                            @if($property->is_verified)
                                <span class="badge bg-success">Terverifikasi</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('property.show', $property) }}" class="btn btn-info btn-sm">Show</a>
                            @if(in_array(Auth::user()->role, ['superadmin', 'owner']))
                            <a href="{{ route('property.edit', $property) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('property.destroy', $property) }}" method="post" class="d-inline">
                                @csrf @method('delete')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    @if(Auth::user()->role === 'tenant')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-6.200000, 106.816666], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var properties = @json($properties);
        
        properties.forEach(function(prop) {
            if(prop.latitude && prop.longitude) {
                var marker = L.marker([prop.latitude, prop.longitude]).addTo(map);
                marker.bindPopup(`<b>${prop.name}</b><br>${prop.city}<br><a href="/property/${prop.id}">Lihat Detail</a>`);
            }
        });
    </script>
    @endif
    @endpush
</x-app>