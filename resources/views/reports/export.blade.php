@extends('layouts.sidebarpage')

@section('header_title', 'Ekspor Laporan Keuangan')
@section('subcontent')
<div class="min-h-screen bg-gray-50 pb-24">
    <!-- Header -->
    <div class="bg-primary px-4 py-6 sticky top-0 z-10 shadow">
        <div class="flex items-center gap-3">
            <a href="/report" class="text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-white text-lg font-bold">Ekspor Laporan Keuangan</h1>
        </div>
    </div>

    <!-- Alternative Flow: Error Messages -->
    @if(session('error'))
    <div class="px-4 pt-4">
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-red-700 font-medium">Gagal Ekspor</p>
            </div>
            <p class="text-red-600 text-sm mt-1 ml-7">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Form -->
    <div class="px-4 py-6">
        <form method="POST" action="/report/export/download" class="space-y-6">
            @csrf

            <!-- Filter Period -->
            <div>
                <label class="block text-sm font-semibold mb-2">Filter</label>
                <div class="relative">
                    <select name="period" required class="w-full bg-white border border-gray-300 rounded-xl px-4 py-4 text-sm appearance-none focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Pilih Periode</option>
                        <option value="daily">Hari Ini</option>
                        <option value="weekly">Minggu Ini</option>
                        <option value="monthly">Bulan Ini</option>
                        <option value="yearly">Tahun Ini</option>
                        <option value="custom">Custom Range</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Custom Date Range (hidden by default) -->
            <div id="customDateRange" style="display: none;">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold mb-2 text-gray-600">Dari Tanggal</label>
                        <input type="date" name="start_date" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-2 text-gray-600">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                </div>
            </div>

            <!-- Export Format -->
            <div>
                <label class="block text-sm font-semibold mb-2">Ekspor Sebagai</label>
                <div class="relative">
                    <select name="format" required class="w-full bg-white border border-gray-300 rounded-xl px-4 py-4 text-sm appearance-none focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Pilih Format</option>
                        <option value="csv">CSV (Excel)</option>
                        <option value="pdf">PDF</option>
                        <option value="json">JSON</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Payment Method Filter -->
            <div>
                <label class="block text-sm font-semibold mb-2">Pemasukan Diperoleh</label>
                <div class="relative">
                    <select name="payment_method" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-4 text-sm appearance-none focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Semua Metode Pembayaran</option>
                        <option value="cash">Cash</option>
                        <option value="qris">QRIS</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Year Filter -->
            <div>
                <label class="block text-sm font-semibold mb-2">Tahun</label>
                <input type="number" name="year" value="{{ now()->year }}" min="2020" max="2100" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan tahun">
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Informasi Ekspor</p>
                        <ul class="text-xs space-y-1 text-blue-700">
                            <li>• File akan berisi detail transaksi sesuai filter</li>
                            <li>• Format CSV dapat dibuka di Excel/Google Sheets</li>
                            <li>• Format PDF cocok untuk laporan cetak</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Export Button -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-primary hover:bg-blue-700 text-white py-4 rounded-xl shadow-lg font-bold text-base flex items-center justify-center gap-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Ekspor</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Show/hide custom date range based on period selection
    const periodSelect = document.querySelector('select[name="period"]');
    const customDateRange = document.getElementById('customDateRange');
    
    periodSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customDateRange.style.display = 'block';
        } else {
            customDateRange.style.display = 'none';
        }
    });
</script>
@endsection
