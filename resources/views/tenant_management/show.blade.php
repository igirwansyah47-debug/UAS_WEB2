<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <div class="text-center">
                    <img src="{{ $booking->tenant->avatar ? asset('storage/'.$booking->tenant->avatar) : asset('niceadmin/img/noprofil.png') }}" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    <h5 class="mt-3">{{ $booking->tenant->name }}</h5>
                    <p class="text-muted">{{ $booking->tenant->email }}</p>
                </div>
                <hr>
                <p><strong>No. HP:</strong> {{ $booking->tenant->phone ?? '-' }}</p>
                <p><strong>No. KTP:</strong> {{ $booking->tenant->ktp_number ?? '-' }}</p>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow-sm p-3">
                <h5>Detail Penyewaan</h5>
                <hr>
                <p><strong>Properti:</strong> {{ $booking->room->property->name }}</p>
                <p><strong>Kamar:</strong> {{ $booking->room->room_type }}</p>
                <p><strong>Durasi:</strong> {{ $booking->duration_months }} bulan ({{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }})</p>
                <p><strong>Total Biaya:</strong> Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                <p>
                    <strong>Status Sewa:</strong> 
                    @if($booking->status == 'active') <span class="badge bg-success">Aktif</span>
                    @elseif($booking->status == 'completed') <span class="badge bg-secondary">Selesai</span>
                    @elseif($booking->status == 'pending') <span class="badge bg-warning">Pending</span>
                    @endif
                </p>
                
                @if($booking->status === 'active')
                <hr>
                <form action="{{ route('tenant_management.completeBooking', $booking) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan penyewaan ini? Stok kamar akan kembali tersedia.')">
                    @csrf
                    <button type="submit" class="btn btn-warning">Selesaikan Penyewaan</button>
                    <small class="text-muted d-block mt-1">Gunakan tombol ini jika penghuni keluar (checkout) dari kos.</small>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-app>