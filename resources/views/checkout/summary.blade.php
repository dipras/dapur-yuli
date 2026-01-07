@extends('layouts.sidebarpage')

@section('subcontent')
<div class="min-h-screen bg-primary pb-24">
    <!-- Header -->
    <div class="px-4 py-6 flex items-center gap-3">
        <a href="/" class="text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-white text-xl font-bold">CHECKOUT</h1>
    </div>

    <!-- Content Card -->
    <div class="bg-gray-50 rounded-t-3xl min-h-screen px-6 py-6">
        <!-- Alternative Flow: Error Messages -->
        @if(session('error'))
        <div class="mb-4">
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-red-700 font-medium">Transaksi Gagal</p>
                </div>
                <p class="text-red-600 text-sm mt-1 ml-7">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Transaction Info -->
        <div class="text-center mb-6">
            <h2 class="text-lg font-bold mb-2">Apakah Transaksi Sudah Benar?</h2>
            <div class="text-sm text-gray-600">
                <p class="font-semibold">Transaksi {{ $transactionNumber }}</p>
                <p>{{ $date }}</p>
                <p>{{ $time }}</p>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
            <div class="bg-blue-200 grid grid-cols-12 gap-2 px-4 py-3 text-sm font-semibold">
                <div class="col-span-1">NO</div>
                <div class="col-span-6">Produk</div>
                <div class="col-span-2 text-center">Jumlah</div>
                <div class="col-span-3 text-right">Subtotal</div>
            </div>
            
            @foreach($items as $index => $item)
            <div class="grid grid-cols-12 gap-2 px-4 py-3 text-sm border-b last:border-b-0">
                <div class="col-span-1">{{ $index + 1 }}</div>
                <div class="col-span-6">{{ $item['name'] }}</div>
                <div class="col-span-2 text-center">{{ $item['quantity'] }}</div>
                <div class="col-span-3 text-right">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
            </div>
            @endforeach

            <!-- Total -->
            <div class="bg-blue-200 grid grid-cols-12 gap-2 px-4 py-3 text-sm font-bold">
                <div class="col-span-1">Total</div>
                <div class="col-span-6"></div>
                <div class="col-span-2 text-center">{{ $totalItems }}</div>
                <div class="col-span-3 text-right">Rp {{ number_format($totalPrice, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Payment Buttons -->
        <form method="POST" action="/checkout/payment">
            @csrf
            @foreach($items as $item)
            <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $item['product_id'] }}">
            <input type="hidden" name="items[{{ $loop->index }}][quantity]" value="{{ $item['quantity'] }}">
            @endforeach
            <input type="hidden" name="total" value="{{ $totalPrice }}">
            <input type="hidden" name="transaction_number" value="{{ $transactionNumber }}">
            
            <div class="grid grid-cols-2 gap-3">
                <button type="submit" name="payment_method" value="cash" class="bg-green-500 hover:bg-green-600 text-white py-4 rounded-xl shadow font-semibold flex flex-col items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Bayar Cash</span>
                </button>
                
                <button type="submit" name="payment_method" value="qris" class="bg-purple-500 hover:bg-purple-600 text-white py-4 rounded-xl shadow font-semibold flex flex-col items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <span>Bayar QRIS</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
