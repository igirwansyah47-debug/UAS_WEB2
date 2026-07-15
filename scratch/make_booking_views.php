<?php
$dir = 'resources/views/booking';
if(!is_dir(__DIR__ . '/../' . $dir)) mkdir(__DIR__ . '/../' . $dir, 0777, true);

file_put_contents(__DIR__ . '/../resources/views/booking/index.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tenant</th>
                        <th>Kamar</th>
                        <th>Durasi</th>
                        <th>Total Harga</th>
                        <th>Status Booking</th>
                        <th>Status Bayar</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\$bookings as \$booking)
                    <tr>
                        <td>{{ \$loop->iteration }}</td>
                        <td>{{ \$booking->tenant->name }}</td>
                        <td>{{ \$booking->room->property->name }} - {{ \$booking->room->room_type }}</td>
                        <td>{{ \$booking->duration_months }} bln ({{ \$booking->start_date }} s/d {{ \$booking->end_date }})</td>
                        <td>Rp {{ number_format(\$booking->total_price, 0, ',', '.') }}</td>
                        <td>
                            @if(\$booking->status == 'pending') <span class="badge bg-warning">Pending</span>
                            @elseif(\$booking->status == 'active') <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                        <td>
                            @if(\$booking->payment->status == 'unpaid') <span class="badge bg-danger">Unpaid</span>
                            @elseif(\$booking->payment->status == 'paid') <span class="badge bg-success">Paid</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('booking.show', \$booking) }}" class="btn btn-info btn-sm">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>
EOT);

file_put_contents(__DIR__ . '/../resources/views/booking/create.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <h4>Pesan Kamar: {{ \$room->property->name }} - {{ \$room->room_type }}</h4>
        <p>Harga per bulan: Rp {{ number_format(\$room->price, 0, ',', '.') }}</p>
        <hr>
        <form action="{{ route('booking.store') }}" method="post">
            @csrf
            <input type="hidden" name="room_id" value="{{ \$room->id }}">
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
EOT);

file_put_contents(__DIR__ . '/../resources/views/booking/show.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <h4>Booking #{{ \$booking->id }}</h4>
        <p><strong>Tenant:</strong> {{ \$booking->tenant->name }}</p>
        <p><strong>Kamar:</strong> {{ \$booking->room->property->name }} - {{ \$booking->room->room_type }}</p>
        <p><strong>Durasi:</strong> {{ \$booking->duration_months }} bulan ({{ \$booking->start_date }} s/d {{ \$booking->end_date }})</p>
        <p><strong>Total Tagihan:</strong> Rp {{ number_format(\$booking->total_price, 0, ',', '.') }}</p>
        <p><strong>Status Booking:</strong> {{ \$booking->status }}</p>
        <hr>
        <h5>Informasi Pembayaran</h5>
        <p><strong>Status Pembayaran:</strong> {{ \$booking->payment->status }}</p>
        @if(\$booking->payment->payment_date)
            <p><strong>Tanggal Bayar:</strong> {{ \$booking->payment->payment_date }}</p>
        @endif
        
        @if(Auth::user()->role === 'owner' && \$booking->status === 'pending')
        <hr>
        <form action="{{ route('booking.markAsPaid', \$booking) }}" method="post" onsubmit="return confirm('Konfirmasi bahwa tenant sudah membayar sejumlah tagihan?')">
            @csrf
            <button class="btn btn-success">Tandai Lunas & Aktifkan Booking</button>
        </form>
        @endif
    </div>
</x-app>
EOT);

echo "Booking views generated.";
