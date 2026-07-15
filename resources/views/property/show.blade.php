<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <h4>{{ $property->name }}</h4>
        <p>{{ $property->description }}</p>
        <p>{{ $property->address }}, {{ $property->city }}</p>
        @if($property->image) <img src="{{ asset('storage/'.$property->image) }}" width="200"> @endif
        <hr>
        <h5>Rooms</h5>
        <ul>
            @foreach($property->rooms as $room)
            <li>{{ $room->room_type }} - Rp {{ number_format($room->price, 0, ',', '.') }}</li>
            @endforeach
        </ul>
    </div>
</x-app>