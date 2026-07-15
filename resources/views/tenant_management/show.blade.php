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
                <p><strong>Total Biaya:</strong> Rp {{ number_format($booking->total_price, 0, ',', '.') }} 
                    @if($booking->security_deposit > 0)
                        <br><small class="text-muted">(Deposit: Rp {{ number_format($booking->security_deposit, 0, ',', '.') }})</small>
                    @endif
                </p>
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
                    @if($booking->security_deposit > 0)
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="return_deposit" value="1" id="returnDeposit">
                        <label class="form-check-label" for="returnDeposit">
                            Uang Jaminan (Deposit) senilai Rp {{ number_format($booking->security_deposit, 0, ',', '.') }} telah dikembalikan ke Tenant.
                        </label>
                    </div>
                    @endif
                    <button type="submit" class="btn btn-warning">Selesaikan Penyewaan</button>
                    <small class="text-muted d-block mt-1">Gunakan tombol ini jika penghuni keluar (checkout) dari kos.</small>
                </form>

                <hr>
                <h5>Tagihan Tambahan</h5>
                <p class="text-muted">Tambahkan tagihan seperti Listrik, Air, dll ke tenant ini.</p>
                <form action="{{ route('extra_bill.store') }}" method="POST" class="d-flex gap-2 mb-3">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                    <input type="text" name="title" class="form-control" placeholder="Nama Tagihan (Cth: Listrik Nov)" required>
                    <input type="number" name="amount" class="form-control" placeholder="Nominal" min="1" required>
                    <button class="btn btn-primary">Tambah</button>
                </form>

                @if($booking->extraBills->count() > 0)
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Tagihan</th>
                            <th>Jumlah</th>
                            <th>Status</th>
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                @endif
            </div>
        </div>
    </div>
</x-app>