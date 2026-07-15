<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('facility.store') }}" method="post">
            @csrf
            <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
            <div class="mb-3"><label>Icon (e.g. bx-wifi)</label><input type="text" name="icon" class="form-control"></div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</x-app>