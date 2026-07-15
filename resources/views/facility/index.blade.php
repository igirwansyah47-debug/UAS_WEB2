<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('facility.create') }}">Tambah</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Icon</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($facilities as $facility)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $facility->name }}</td>
                        <td><i class="{{ $facility->icon }}"></i> {{ $facility->icon }}</td>
                        <td>
                            <a href="{{ route('facility.edit', $facility) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('facility.destroy', $facility) }}" method="post" class="d-inline">
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