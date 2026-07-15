<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('facility.update', $facility) }}" method="post">
            @csrf @method('put')
            <div class="mb-3"><label>Name</label><input type="text" name="name" value="{{ $facility->name }}" class="form-control" required></div>
            <div class="mb-3"><label>Icon</label><input type="text" name="icon" value="{{ $facility->icon }}" class="form-control"></div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</x-app>