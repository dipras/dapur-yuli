@extends('layouts.sidebarpage')

@section('subcontent')
<div class="min-h-screen bg-primary pb-24">
    <!-- Header -->
    <div class="px-4 py-6 flex items-center gap-3">
        <a href="/checkout/summary" class="text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-white text-xl font-bold">PEMBAYARAN QRIS</h1>
    </div>

    <!-- Content Card -->
    <div class="bg-gray-50 rounded-t-3xl min-h-screen px-6 py-8">
        <!-- Transaction Info -->
        <div class="text-center mb-6">
            <h2 class="text-lg font-bold mb-2">Scan QR Code untuk Membayar</h2>
            <div class="text-sm text-gray-600">
                <p class="font-semibold">Transaksi {{ $transactionNumber }}</p>
                <p class="text-2xl font-bold text-primary mt-2">Rp {{ number_format($total, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- QR Code -->
        <div class="bg-white rounded-xl shadow p-8 mb-6 flex justify-center">
            <div class="w-64 h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                <!-- QR Code Placeholder - In production, generate actual QR code -->
                <div class="grid grid-cols-8 gap-1 p-4">
                    @for($i = 0; $i < 64; $i++)
                        <div class="w-3 h-3 {{ rand(0, 1) ? 'bg-black' : 'bg-white' }} rounded-sm"></div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 rounded-xl p-4 mb-6">
            <h3 class="font-semibold mb-2 text-sm">Cara Pembayaran:</h3>
            <ol class="text-xs text-gray-700 space-y-1 list-decimal list-inside">
                <li>Buka aplikasi e-wallet Anda (GoPay, OVO, DANA, dll)</li>
                <li>Pilih fitur scan QR Code</li>
                <li>Arahkan kamera ke QR Code di atas</li>
                <li>Konfirmasi pembayaran di aplikasi Anda</li>
                <li>Setelah berhasil, klik tombol "Sudah Bayar" di bawah</li>
            </ol>
        </div>

        <!-- Confirm Payment Button -->
        <form method="POST" action="/checkout/success">
            @csrf
            <input type="hidden" name="transaction_number" value="{{ $transactionNumber }}">
            <input type="hidden" name="payment_method" value="qris">
            <input type="hidden" name="total" value="{{ $total }}">
            @if(isset($items))
                @foreach($items as $index => $item)
                <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item['product_id'] }}">
                <input type="hidden" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}">
                @endforeach
            @endif
            
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-4 rounded-xl shadow font-semibold flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Sudah Bayar</span>
            </button>
        </form>
    </div>
</div>
@endsection
