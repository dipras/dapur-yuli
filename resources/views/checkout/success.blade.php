@extends('layouts.sidebarpage')

@section('subcontent')
<div class="min-h-screen bg-primary pb-24">
    <!-- Header -->
    <div class="px-4 py-6 text-center">
        <h1 class="text-white text-xl font-bold">PEMBAYARAN BERHASIL</h1>
    </div>

    <!-- Content Card -->
    <div class="bg-gray-50 rounded-t-3xl min-h-screen px-6 py-12">
        <!-- Success Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-24 h-24 bg-green-500 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <!-- Success Message -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold mb-2">Pembayaran Berhasil!</h2>
            <p class="text-gray-600">Terima kasih atas pembelian Anda</p>
        </div>

        <!-- Transaction Details -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h3 class="font-semibold mb-4 text-center">Detail Transaksi</h3>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Nomor Transaksi</span>
                    <span class="font-semibold">{{ $transactionNumber }}</span>
                </div>
                
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Tanggal</span>
                    <span class="font-semibold">{{ $date }}</span>
                </div>
                
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Waktu</span>
                    <span class="font-semibold">{{ $time }}</span>
                </div>
                
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Metode Pembayaran</span>
                    <span class="font-semibold">{{ $paymentMethod == 'qris' ? 'QRIS' : 'Cash' }}</span>
                </div>
                
                <div class="flex justify-between py-3 bg-green-50 px-3 rounded-lg mt-4">
                    <span class="text-gray-800 font-semibold">Total Pembayaran</span>
                    <span class="font-bold text-lg text-green-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Success Animation -->
        <div class="text-center mb-6">
            <div class="inline-block animate-bounce">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <a href="/" class="block w-full bg-primary hover:bg-blue-700 text-white py-4 rounded-xl shadow font-semibold text-center">
                Kembali ke Beranda
            </a>
            
            <button onclick="window.print()" class="block w-full bg-white hover:bg-gray-50 text-primary border-2 border-primary py-4 rounded-xl shadow font-semibold text-center">
                Cetak Struk
            </button>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>
@endsection
