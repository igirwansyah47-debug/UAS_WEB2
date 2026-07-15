<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <h4>Komplain Kamar: {{ $room->property->name }} - {{ $room->room_type }}</h4>
        <hr>
        <form action="{{ route('complaint.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="room_id" value="{{ $room->id }}">
            <div class="mb-3">
                <label>Subjek</label>
                <input type="text" name="subject" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label>Lampiran Foto (Opsional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <button class="btn btn-primary">Kirim Komplain</button>
        </form>
    </div>
</x-app>