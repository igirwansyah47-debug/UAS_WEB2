<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('property.create') }}">Tambah</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($properties as $property)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $property->name }}</td>
                        <td>{{ $property->city }}</td>
                        <td>{{ $property->address }}</td>
                        <td>
                            @if($property->is_verified)
                                <span class="badge bg-success">Terverifikasi</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('property.show', $property) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('property.edit', $property) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('property.destroy', $property) }}" method="post" class="d-inline">
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