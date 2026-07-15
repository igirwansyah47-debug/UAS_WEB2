<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row">
        @if(auth()->user()->role === 'superadmin')
            <!-- Superadmin Dashboard -->
            <div class="col-md-3">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Total Tenant</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-user"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $totalTenants }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Total Owner</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-buildings"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $totalOwners }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <h5 class="card-title">Total Transaksi</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-cart"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $totalTransactions }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <h5 class="card-title">Total Pendapatan</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-dollar"></i>
                            </div>
                            <div class="ps-3">
                                <h6>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Grafik Pendapatan Bulanan</h5>
                        <div id="revenueChart"></div>
                    </div>
                </div>
            </div>

        @elseif(auth()->user()->role === 'owner')
            <!-- Owner Dashboard -->
            <div class="col-md-4">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Tingkat Okupansi Kamar</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-bed"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $occupancyRate }}%</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <h5 class="card-title">Total Pendapatan</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-dollar"></i>
                            </div>
                            <div class="ps-3">
                                <h6>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card info-card customers-card">
                    <div class="card-body">
                        <h5 class="card-title">Tagihan Belum Lunas</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-credit-card"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $pendingPayments }} Tagihan</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Grafik Pendapatan Properti Bulanan</h5>
                        <div id="revenueChart"></div>
                    </div>
                </div>
            </div>

        @elseif(auth()->user()->role === 'tenant')
            <!-- Tenant Dashboard -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Kos yang sedang disewa</h5>
                        @if($activeBookings->count() > 0)
                            <div class="list-group">
                                @foreach($activeBookings as $booking)
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $booking->room->property->name }} - {{ $booking->room->room_type }}</h6>
                                            <small>{{ $booking->duration_months }} Bulan</small>
                                        </div>
                                        <p class="mb-1">Aktif hingga: {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>Belum ada kos yang disewa secara aktif.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tagihan Jatuh Tempo / Menunggu Pembayaran</h5>
                        @if($pendingPayments->count() > 0)
                            <div class="list-group">
                                @foreach($pendingPayments as $payment)
                                    <div class="list-group-item list-group-item-danger">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $payment->booking->room->property->name }} - {{ $payment->booking->room->room_type }}</h6>
                                            <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                                        </div>
                                        <small>Segera lakukan pembayaran.</small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>Tidak ada tagihan yang tertunda.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'owner')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let chartLabels = {!! json_encode($chartLabels ?? []) !!};
            let chartValues = {!! json_encode($chartValues ?? []) !!};

            new ApexCharts(document.querySelector("#revenueChart"), {
                series: [{
                    name: 'Pendapatan',
                    data: chartValues
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: {
                        show: false
                    },
                },
                markers: {
                    size: 4
                },
                colors: ['#4154f1'],
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0.4,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: chartLabels
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                            return "Rp " + value.toLocaleString("id-ID");
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "Rp " + val.toLocaleString("id-ID");
                        }
                    }
                }
            }).render();
        });
    </script>
    @endif
    @endpush

</x-app>
