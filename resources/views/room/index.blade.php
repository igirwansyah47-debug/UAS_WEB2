<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('room.create') }}">Tambah</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Property</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rooms as $room)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $room->property->name }}</td>
                        <td>{{ $room->room_type }}</td>
                        <td>{{ $room->price }}</td>
                        <td>{{ $room->available_stock }} / {{ $room->quantity }}</td>
                        <td>
                            <a href="{{ route('room.show', $room) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('room.edit', $room) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('room.destroy', $room) }}" method="post" class="d-inline">
                                @csrf @method('delete')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>