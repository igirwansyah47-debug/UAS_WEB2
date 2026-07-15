<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('property.update', $property) }}" method="post" enctype="multipart/form-data">
            @csrf @method('put')
            <div class="mb-3"><label>Name</label><input type="text" name="name" value="{{ $property->name }}" class="form-control" required></div>
            <div class="mb-3"><label>Description</label><textarea name="description" class="form-control">{{ $property->description }}</textarea></div>
            <div class="mb-3"><label>Address</label><input type="text" name="address" value="{{ $property->address }}" class="form-control" required></div>
            <div class="mb-3"><label>City</label><input type="text" name="city" value="{{ $property->city }}" class="form-control" required></div>
            <div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control"></div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</x-app>