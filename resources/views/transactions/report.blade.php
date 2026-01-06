@extends('layouts.sidebarpage')

@section('header_title', 'Laporan Keuangan')
@section('subcontent')
<div class="min-h-screen bg-gray-50 pb-24">

    <!-- Total Revenue Card -->
    <div class="px-4 py-6 bg-primary">
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-white">
            <p class="text-sm opacity-90 mb-2">Total Pendapatan</p>
            <h2 class="text-3xl font-bold mb-3">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
            <div class="flex items-center justify-between text-sm">
                <span class="opacity-90">
                    {{ $startDate->format('d') }} - {{ $endDate->format('d M Y') }}
                </span>
                <div class="flex gap-2">
                    <button onclick="exportData()" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg font-semibold">
                        Ekspor
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Period Filter -->
    <div class="px-4 -mt-3 mb-4">
        <div class="bg-white rounded-xl shadow p-2 flex gap-2">
            <a href="/laporan?period=daily" class="flex-1 text-center py-2 rounded-lg font-semibold text-sm {{ $period == 'daily' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Hari Ini
            </a>
            <a href="/laporan?period=weekly" class="flex-1 text-center py-2 rounded-lg font-semibold text-sm {{ $period == 'weekly' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Minggu
            </a>
            <a href="/laporan?period=monthly" class="flex-1 text-center py-2 rounded-lg font-semibold text-sm {{ $period == 'monthly' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Bulan
            </a>
            <a href="/laporan?period=yearly" class="flex-1 text-center py-2 rounded-lg font-semibold text-sm {{ $period == 'yearly' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Tahun
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="px-4 mb-4 grid grid-cols-2 gap-3">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-500">Cash</p>
                    <p class="font-bold text-sm">Rp {{ number_format($cashTotal, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-500">QRIS</p>
                    <p class="font-bold text-sm">Rp {{ number_format($qrisTotal, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-500">Transaksi</p>
                    <p class="font-bold text-sm">{{ $totalTransactions }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-500">Rata-rata</p>
                    <p class="font-bold text-sm">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="px-4 mb-4">
        <div class="bg-white rounded-xl shadow p-4">
            <h3 class="font-semibold mb-4 text-sm">Ringkasan</h3>
            <div style="height: 180px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Period Summary -->
    <div class="px-4 mb-4">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow p-5 text-white">
            <p class="text-sm opacity-90 mb-1">
                @if($period == 'daily')
                    Pemasukan Hari Ini
                @elseif($period == 'weekly')
                    Pemasukan 1 Minggu
                @elseif($period == 'monthly')
                    Pemasukan 1 Bulan
                @else
                    Pemasukan 1 Tahun
                @endif
            </p>
            <p class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Today's Transactions -->
    <div class="px-4 mb-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold">Hari ini, {{ now()->format('d M') }}</h3>
            <a href="/transactions" class="text-primary text-sm font-semibold">Lihat Semua</a>
        </div>

        <div class="space-y-2">
            @forelse($todayTransactions as $transaction)
            <a href="/transactions/{{ $transaction->id }}" class="block bg-white rounded-xl shadow hover:shadow-md transition p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-sm">{{ $transaction->transaction_number }}</p>
                        <p class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }} WIB</p>
                    </div>
                    <p class="font-bold text-primary">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                </div>
            </a>
            @empty
            <div class="bg-white rounded-xl shadow p-6 text-center">
                <p class="text-gray-500 text-sm">Belum ada transaksi hari ini</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // @ts-nocheck
    // Chart data from backend
    const chartLabels = {!! json_encode($chartData['labels']) !!};
    const revenueData = {!! json_encode($chartData['data']) !!};
    
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Pendapatan',
                data: revenueData,
                backgroundColor: '#1e40af',
                borderRadius: 8,
                barThickness: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    ticks: {
                        font: {
                            size: 10
                        },
                        maxRotation: 0,
                        minRotation: 0
                    },
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 10
                        },
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'k';
                            }
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });

    function exportData() {
        alert('Fitur export akan segera hadir!');
        // Implement export functionality here (CSV, Excel, or PDF)
    }
</script>
@endsection
