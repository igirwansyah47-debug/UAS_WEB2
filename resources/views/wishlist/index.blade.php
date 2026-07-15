<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="row">
        @forelse($wishlists as $w)
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                @if($w->property->image)
                    <img src="{{ asset('storage/'.$w->property->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $w->property->name }}</h5>
                    <p class="card-text">{{ $w->property->city }}</p>
                    <a href="{{ route('property.show', $w->property) }}" class="btn btn-primary btn-sm">Lihat Properti</a>
                    <form action="{{ route('wishlist.toggle') }}" method="post" class="d-inline">
                        @csrf
                        <input type="hidden" name="property_id" value="{{ $w->property->id }}">
                        <button class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p>Wishlist masih kosong.</p>
        </div>
        @endforelse
    </div>
</x-app>