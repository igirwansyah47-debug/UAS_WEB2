<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    
    <div class="card shadow-lg p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Properti</th>
                        <th>Owner</th>
                        <th>Kota</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($properties as $property)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $property->name }}</td>
                        <td>{{ $property->owner->name }}</td>
                        <td>{{ $property->city ?? '-' }}</td>
                        <td>
                            @if($property->is_verified)
                                <span class="badge bg-success">Terverifikasi</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum Terverifikasi</span>
                            @endif
                        </td>
                        <td>
                            @if(!$property->is_verified)
                                <form action="{{ route('verification.approve', $property) }}" method="POST" class="d-inline" onsubmit="return confirm('Verifikasi properti ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm"><i class="bx bx-check"></i> Setujui</button>
                                </form>
                            @else
                                <form action="{{ route('verification.reject', $property) }}" method="POST" class="d-inline" onsubmit="return confirm('Cabut verifikasi properti ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="bx bx-x"></i> Cabut</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>