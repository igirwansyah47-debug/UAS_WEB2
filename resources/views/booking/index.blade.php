<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
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
                    @foreach ($bookings as $booking)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $booking->tenant->name }}</td>
                        <td>{{ $booking->room->property->name }} - {{ $booking->room->room_type }}</td>
                        <td>{{ $booking->duration_months }} bln ({{ $booking->start_date }} s/d {{ $booking->end_date }})</td>
                        <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                        <td>
                            @if($booking->status == 'pending') <span class="badge bg-warning">Pending</span>
                            @elseif($booking->status == 'active') <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                        <td>
                            @if($booking->payment->status == 'unpaid') <span class="badge bg-danger">Unpaid</span>
                            @elseif($booking->payment->status == 'paid') <span class="badge bg-success">Paid</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('booking.show', $booking) }}" class="btn btn-info btn-sm">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>