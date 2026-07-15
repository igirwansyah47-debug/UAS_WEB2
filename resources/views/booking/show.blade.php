<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <h4>Booking #{{ $booking->id }}</h4>
        <p><strong>Tenant:</strong> {{ $booking->tenant->name }}</p>
        <p><strong>Kamar:</strong> {{ $booking->room->property->name }} - {{ $booking->room->room_type }}</p>
        <p><strong>Durasi:</strong> {{ $booking->duration_months }} bulan ({{ $booking->start_date }} s/d {{ $booking->end_date }})</p>
        <p><strong>Total Tagihan:</strong> Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
        <p><strong>Status Booking:</strong> {{ $booking->status }}</p>
        <hr>
        <h5>Informasi Pembayaran</h5>
        <p><strong>Status Pembayaran:</strong> {{ $booking->payment->status }}</p>
        @if($booking->payment->payment_date)
            <p><strong>Tanggal Bayar:</strong> {{ $booking->payment->payment_date }}</p>
        @endif
        
        @if(Auth::user()->role === 'owner' && $booking->status === 'pending')
        <hr>
        <form action="{{ route('booking.markAsPaid', $booking) }}" method="post" onsubmit="return confirm('Konfirmasi bahwa tenant sudah membayar sejumlah tagihan?')">
            @csrf
            <button class="btn btn-success">Tandai Lunas & Aktifkan Booking</button>
        </form>
        @endif
    </div>
</x-app>