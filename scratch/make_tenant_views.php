<?php
$dir = 'resources/views/tenant_management';
if(!is_dir(__DIR__ . '/../' . $dir)) mkdir(__DIR__ . '/../' . $dir, 0777, true);

file_put_contents(__DIR__ . '/../resources/views/tenant_management/index.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    
    <div class="card shadow-lg p-3 mb-4">
        <form action="{{ route('tenant_management.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Filter Properti</label>
                <select name="property_id" class="form-select">
                    <option value="">Semua Properti</option>
                    @foreach(\$properties as \$prop)
                        <option value="{{ \$prop->id }}" {{ request('property_id') == \$prop->id ? 'selected' : '' }}>{{ \$prop->name }}</option>
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
                    @foreach (\$bookings as \$booking)
                    <tr>
                        <td>{{ \$loop->iteration }}</td>
                        <td>{{ \$booking->tenant->name }}</td>
                        <td>{{ \$booking->room->property->name }}</td>
                        <td>{{ \$booking->room->room_type }}</td>
                        <td>
                            @if(\$booking->status == 'active') <span class="badge bg-success">Aktif</span>
                            @elseif(\$booking->status == 'completed') <span class="badge bg-secondary">Selesai</span>
                            @elseif(\$booking->status == 'pending') <span class="badge bg-warning">Pending</span>
                            @else <span class="badge bg-dark">{{ \$booking->status }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('tenant_management.show', \$booking->id) }}" class="btn btn-info btn-sm">Detail Penghuni</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>
EOT);

file_put_contents(__DIR__ . '/../resources/views/tenant_management/show.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <div class="text-center">
                    <img src="{{ \$booking->tenant->avatar ? asset('storage/'.\$booking->tenant->avatar) : asset('niceadmin/img/noprofil.png') }}" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    <h5 class="mt-3">{{ \$booking->tenant->name }}</h5>
                    <p class="text-muted">{{ \$booking->tenant->email }}</p>
                </div>
                <hr>
                <p><strong>No. HP:</strong> {{ \$booking->tenant->phone ?? '-' }}</p>
                <p><strong>No. KTP:</strong> {{ \$booking->tenant->ktp_number ?? '-' }}</p>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow-sm p-3">
                <h5>Detail Penyewaan</h5>
                <hr>
                <p><strong>Properti:</strong> {{ \$booking->room->property->name }}</p>
                <p><strong>Kamar:</strong> {{ \$booking->room->room_type }}</p>
                <p><strong>Durasi:</strong> {{ \$booking->duration_months }} bulan ({{ \Carbon\Carbon::parse(\$booking->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse(\$booking->end_date)->format('d M Y') }})</p>
                <p><strong>Total Biaya:</strong> Rp {{ number_format(\$booking->total_price, 0, ',', '.') }}</p>
                <p>
                    <strong>Status Sewa:</strong> 
                    @if(\$booking->status == 'active') <span class="badge bg-success">Aktif</span>
                    @elseif(\$booking->status == 'completed') <span class="badge bg-secondary">Selesai</span>
                    @elseif(\$booking->status == 'pending') <span class="badge bg-warning">Pending</span>
                    @endif
                </p>
                
                @if(\$booking->status === 'active')
                <hr>
                <form action="{{ route('tenant_management.completeBooking', \$booking) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan penyewaan ini? Stok kamar akan kembali tersedia.')">
                    @csrf
                    <button type="submit" class="btn btn-warning">Selesaikan Penyewaan</button>
                    <small class="text-muted d-block mt-1">Gunakan tombol ini jika penghuni keluar (checkout) dari kos.</small>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-app>
EOT);

echo "Tenant views generated.";
