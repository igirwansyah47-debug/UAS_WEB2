<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <h4>Booking #{{ $booking->id }}</h4>
        <p><strong>Tenant:</strong> {{ $booking->tenant->name }}</p>
        <p><strong>Kamar:</strong> {{ $booking->room->property->name }} - {{ $booking->room->room_type }}</p>
        <p><strong>Durasi:</strong> {{ $booking->duration_months }} bulan ({{ $booking->start_date }} s/d {{ $booking->end_date }})</p>
        <p><strong>Total Tagihan:</strong> Rp {{ number_format($booking->total_price, 0, ',', '.') }}
            @if($booking->security_deposit > 0)
                <br><small class="text-muted">(Termasuk Uang Jaminan / Deposit: Rp {{ number_format($booking->security_deposit, 0, ',', '.') }})</small>
            @endif
        </p>
        <p><strong>Status Booking:</strong> 
            @if($booking->status === 'active') <span class="badge bg-success">Aktif</span>
            @elseif($booking->status === 'pending') <span class="badge bg-warning">Pending</span>
            @elseif($booking->status === 'completed') <span class="badge bg-secondary">Selesai</span>
            @else <span class="badge bg-dark">{{ $booking->status }}</span>
            @endif
        </p>
        <hr>
        <h5>Informasi Pembayaran</h5>
        <p><strong>Status Pembayaran:</strong> 
            @if($booking->payment->status === 'paid') <span class="badge bg-success">Lunas</span>
            @elseif($booking->payment->status === 'unpaid') <span class="badge bg-danger">Belum Lunas</span>
            @else <span class="badge bg-dark">{{ $booking->payment->status }}</span>
            @endif
        </p>
        @if($booking->payment->transaction_id)
            <p><strong>ID Transaksi:</strong> {{ $booking->payment->transaction_id }}</p>
        @endif
        @if($booking->payment->payment_method)
            <p><strong>Metode Pembayaran:</strong> {{ $booking->payment->payment_method }}</p>
        @endif
        @if($booking->payment->payment_date)
            <p><strong>Tanggal Bayar:</strong> {{ \Carbon\Carbon::parse($booking->payment->payment_date)->format('d M Y H:i') }}</p>
        @endif

        {{-- Tombol Bayar Midtrans Snap (Tenant) --}}
        @if(Auth::user()->role === 'tenant' && $booking->payment->status === 'unpaid' && $booking->snap_token)
        <hr>
        <button id="pay-button" class="btn btn-primary btn-lg"><i class="bx bx-credit-card"></i> Bayar Sekarang via Midtrans</button>
        @endif
        
        {{-- Tombol Perpanjang Sewa (Tenant) --}}
        @if(Auth::user()->role === 'tenant' && $booking->status === 'active')
        <hr>
        <h5>Perpanjang Sewa</h5>
        <form action="{{ route('booking.renew', $booking) }}" method="post" class="d-flex align-items-center gap-2">
            @csrf
            <input type="number" name="duration_months" class="form-control w-25" min="1" value="1" required>
            <span>Bulan</span>
            <button class="btn btn-outline-success">Perpanjang</button>
        </form>
        @endif

        {{-- Tagihan Tambahan --}}
        @if($booking->extraBills->count() > 0)
        <hr>
        <h5>Tagihan Tambahan</h5>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($booking->extraBills as $bill)
                <tr>
                    <td>{{ $bill->title }}</td>
                    <td>Rp {{ number_format($bill->amount, 0, ',', '.') }}</td>
                    <td>
                        @if($bill->status === 'paid') <span class="badge bg-success">Lunas</span>
                        @else <span class="badge bg-danger">Belum Lunas</span>
                        @endif
                    </td>
                    <td>
                        @if(Auth::user()->role === 'tenant' && $bill->status === 'unpaid')
                            @if($bill->snap_token)
                                <button class="btn btn-sm btn-primary pay-extra-bill" data-token="{{ $bill->snap_token }}">Bayar</button>
                            @else
                                <form action="{{ route('extra_bill.pay', $bill) }}" method="post">
                                    @csrf
                                    <button class="btn btn-sm btn-primary">Proses Bayar</button>
                                </form>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- Tombol Manual Confirm (Owner/Superadmin) --}}
        @if(in_array(Auth::user()->role, ['owner', 'superadmin']) && $booking->status === 'pending')
        <hr>
        <form action="{{ route('booking.markAsPaid', $booking) }}" method="post" onsubmit="return confirm('Konfirmasi bahwa tenant sudah membayar sejumlah tagihan?')">
            @csrf
            <button class="btn btn-success">Tandai Lunas & Aktifkan Booking</button>
        </form>
        @endif
    </div>

    @push('scripts')
    @if(Auth::user()->role === 'tenant')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        const payButton = document.getElementById('pay-button');
        if (payButton) {
            payButton.addEventListener('click', function () {
                snap.pay('{{ $booking->snap_token }}', {
                    onSuccess: function(result) {
                        alert("Pembayaran berhasil!");
                        window.location.reload();
                    },
                    onPending: function(result) {
                        alert("Menunggu pembayaran Anda...");
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal!");
                    },
                    onClose: function() {
                        console.log('Popup pembayaran ditutup.');
                    }
                });
            });
        }
        
        const extraBillButtons = document.querySelectorAll('.pay-extra-bill');
        extraBillButtons.forEach(button => {
            button.addEventListener('click', function() {
                const token = this.getAttribute('data-token');
                snap.pay(token, {
                    onSuccess: function(result) {
                        alert("Pembayaran berhasil!");
                        window.location.reload();
                    },
                    onPending: function(result) {
                        alert("Menunggu pembayaran Anda...");
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal!");
                    }
                });
            });
        });
    </script>
    @endif
    @endpush
</x-app>