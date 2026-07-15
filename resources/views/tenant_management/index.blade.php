<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    
    <div class="card shadow-lg p-3 mb-4">
        <form action="{{ route('tenant_management.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Filter Properti</label>
                <select name="property_id" class="form-select">
                    <option value="">Semua Properti</option>
                    @foreach($properties as $prop)
                        <option value="{{ $prop->id }}" {{ request('property_id') == $prop->id ? 'selected' : '' }}>{{ $prop->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status Sewa</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary"><i class="bx bx-filter"></i> Filter</button>
                <a href="{{ route('tenant_management.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="card shadow-lg p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Penghuni</th>
                        <th>Properti</th>
                        <th>Kamar</th>
                        <th>Status Sewa</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $booking->tenant->name }}</td>
                        <td>{{ $booking->room->property->name }}</td>
                        <td>{{ $booking->room->room_type }}</td>
                        <td>
                            @if($booking->status == 'active') <span class="badge bg-success">Aktif</span>
                            @elseif($booking->status == 'completed') <span class="badge bg-secondary">Selesai</span>
                            @elseif($booking->status == 'pending') <span class="badge bg-warning">Pending</span>
                            @else <span class="badge bg-dark">{{ $booking->status }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('tenant_management.show', $booking->id) }}" class="btn btn-info btn-sm">Detail Penghuni</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>