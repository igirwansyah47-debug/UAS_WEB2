<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <h4>{{ $room->room_type }} - {{ $room->property->name }}</h4>
        <p>Price: Rp {{ number_format($room->price, 0, ',', '.') }}</p>
        <p>Stock: {{ $room->available_stock }} / {{ $room->quantity }}</p>
        @if($room->image) <img src="{{ asset('storage/'.$room->image) }}" width="200"> @endif
        <hr>
        <h5>Facilities</h5>
        <ul>
            @foreach($room->facilities as $facility)
            <li><i class="{{ $facility->icon }}"></i> {{ $facility->name }}</li>
            @endforeach
        </ul>
        
        @if(Auth::user()->role === 'tenant')
        <div class="mt-3">
            <a href="{{ route('booking.create', ['room_id' => $room->id]) }}" class="btn btn-success">Pesan Kamar Ini</a>
            <a href="{{ route('complaint.create', ['room_id' => $room->id]) }}" class="btn btn-warning">Ajukan Komplain</a>
        </div>
        @endif
    </div>
</x-app>