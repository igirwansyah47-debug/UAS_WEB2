<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <h4>Pesan Kamar: {{ $room->property->name }} - {{ $room->room_type }}</h4>
        <p>Harga per bulan: Rp {{ number_format($room->price, 0, ',', '.') }}</p>
        <hr>
        <form action="{{ route('booking.store') }}" method="post">
            @csrf
            <input type="hidden" name="room_id" value="{{ $room->id }}">
            <div class="mb-3">
                <label>Tanggal Mulai (Start Date)</label>
                <input type="date" name="start_date" class="form-control" required min="{{ date('Y-m-d') }}">
            </div>
            <div class="mb-3">
                <label>Durasi (Bulan)</label>
                <input type="number" name="duration_months" class="form-control" required min="1" value="1">
            </div>
            <button class="btn btn-primary">Pesan Sekarang</button>
        </form>
    </div>
</x-app>