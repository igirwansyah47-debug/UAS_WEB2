<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="d-flex justify-content-between align-items-center">
            <h4>{{ $property->name }}</h4>
            @if(Auth::user()->role === 'tenant')
            <form action="{{ route('wishlist.toggle') }}" method="post">
                @csrf
                <input type="hidden" name="property_id" value="{{ $property->id }}">
                @php
                    $inWishlist = Auth::user()->wishlists->where('property_id', $property->id)->count() > 0;
                @endphp
                <button class="btn btn-{{ $inWishlist ? 'danger' : 'outline-danger' }}">
                    <i class="bx bx-heart"></i> {{ $inWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}
                </button>
            </form>
            @endif
        </div>
        
        <p>{{ $property->description }}</p>
        <p>{{ $property->address }}, {{ $property->city }}</p>
        
        @if(Auth::user()->role === 'tenant')
            <a href="{{ route('chat.index', ['user' => $property->owner_id]) }}" class="btn btn-primary btn-sm mb-3">
                <i class="bi bi-chat-dots"></i> Chat Pemilik Kos
            </a>
        @endif
        
        @php
            $avgRating = $property->reviews->avg('rating');
        @endphp
        <p>
            <strong>Rating:</strong> 
            @if($avgRating)
                {{ number_format($avgRating, 1) }} / 5.0
            @else
                Belum ada rating
            @endif
        </p>

        @if($property->image) <img src="{{ asset('storage/'.$property->image) }}" class="mb-3" style="max-width:300px; display:block;"> @endif
        
        @if($property->latitude && $property->longitude)
        <div class="mb-3">
            <h5>Lokasi</h5>
            <div id="map" style="height: 300px; width: 100%; max-width: 600px; border-radius: 8px; border: 1px solid #ddd;"></div>
        </div>
        @endif
        
        <hr>
        <h5>Kamar Tersedia</h5>
        <ul>
            @foreach($property->rooms as $room)
            <li>
                <a href="{{ route('room.show', $room) }}">{{ $room->room_type }}</a> - Rp {{ number_format($room->price, 0, ',', '.') }}
            </li>
            @endforeach
        </ul>

        <hr>
        <h5>Ulasan (Reviews)</h5>
        @if($property->reviews->count() > 0)
            <div class="list-group mb-3">
                @foreach($property->reviews as $r)
                <div class="list-group-item flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">{{ $r->tenant->name }}</h6>
                        <small>{{ $r->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1">Rating: {{ $r->rating }} / 5</p>
                    <small>{{ $r->comment }}</small>
                </div>
                @endforeach
            </div>
        @else
            <p>Belum ada ulasan.</p>
        @endif

        @if(Auth::user()->role === 'tenant')
        <div class="card p-3 bg-light">
            <h6>Berikan Ulasan</h6>
            <form action="{{ route('review.store') }}" method="post">
                @csrf
                <input type="hidden" name="property_id" value="{{ $property->id }}">
                <div class="mb-3">
                    <label>Rating (1-5)</label>
                    <select name="rating" class="form-select" required>
                        <option value="5">5 - Sangat Baik</option>
                        <option value="4">4 - Baik</option>
                        <option value="3">3 - Cukup</option>
                        <option value="2">2 - Kurang</option>
                        <option value="1">1 - Sangat Kurang</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Komentar</label>
                    <textarea name="comment" class="form-control" rows="2"></textarea>
                </div>
                <button class="btn btn-primary btn-sm">Kirim Ulasan</button>
            </form>
        </div>
        @endif
    </div>
    
    @push('scripts')
    @if($property->latitude && $property->longitude)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var lat = {{ $property->latitude }};
        var lng = {{ $property->longitude }};
        var map = L.map('map').setView([lat, lng], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup("<b>{{ $property->name }}</b>").openPopup();
    </script>
    @endif
    @endpush
</x-app>