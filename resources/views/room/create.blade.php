<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('room.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Property</label>
                <select name="property_id" class="form-control" required>
                    @foreach($properties as $p) <option value="{{ $p->id }}">{{ $p->name }}</option> @endforeach
                </select>
            </div>
            <div class="mb-3"><label>Type</label><input type="text" name="room_type" class="form-control" required></div>
            <div class="mb-3">
                <label>Price</label>
                <input type="number" name="price" class="form-control" value="{{ old('price') }}" required min="0">
                @error('price')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label>Security Deposit <small class="text-muted">- Optional</small></label>
                <input type="number" name="security_deposit" class="form-control" value="{{ old('security_deposit', 0) }}" min="0">
                @error('security_deposit')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3"><label>Quantity</label><input type="number" name="quantity" class="form-control" required></div>
            <div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control"></div>
            
            <div class="mb-3">
                <label>Facilities</label>
                @foreach($facilities as $f)
                <div>
                    <label><input type="checkbox" name="facilities[]" value="{{ $f->id }}"> {{ $f->name }}</label>
                </div>
                @endforeach
            </div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</x-app>