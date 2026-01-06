@extends('layouts.sidebarpage')

@section('header_title', 'Transaksi Detail')
@section('subcontent')
<div class="min-h-screen bg-primary pb-24">

    <!-- Content Card -->
    <div class="bg-gray-50 rounded-t-3xl min-h-screen px-6 py-6">
        <!-- Transaction Info -->
        <div class="text-center mb-6">
            <h2 class="text-lg font-bold mb-2">Detail Transaksi</h2>
            <div class="text-sm text-gray-600">
                <p class="font-semibold">{{ $transaction->transaction_number }}</p>
                <p>{{ $transaction->created_at->format('d/m/Y') }}</p>
                <p>{{ $transaction->created_at->format('H:i') }} WIB</p>
            </div>
        </div>

        <!-- Payment Method Badge -->
        <div class="flex justify-center mb-6">
            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $transaction->payment_method == 'cash' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700' }}">
                {{ $transaction->payment_method == 'cash' ? 'Pembayaran Cash' : 'Pembayaran QRIS' }}
            </span>
        </div>

        <!-- User Info -->
        @if($transaction->user)
        <div class="bg-blue-50 rounded-xl p-4 mb-6">
            <p class="text-xs text-gray-600 mb-1">Kasir</p>
            <p class="font-semibold">{{ $transaction->user->name }}</p>
            <p class="text-sm text-gray-600">{{ $transaction->user->email }}</p>
        </div>
        @endif

        <!-- Products Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
            <div class="bg-blue-200 grid grid-cols-12 gap-2 px-4 py-3 text-sm font-semibold">
                <div class="col-span-1">NO</div>
                <div class="col-span-6">Produk</div>
                <div class="col-span-2 text-center">Jumlah</div>
                <div class="col-span-3 text-right">Subtotal</div>
            </div>
            
            @foreach($transaction->items as $index => $item)
            <div class="grid grid-cols-12 gap-2 px-4 py-3 text-sm border-b last:border-b-0">
                <div class="col-span-1">{{ $index + 1 }}</div>
                <div class="col-span-6">
                    @if(isset($item['name']))
                        {{ $item['name'] }}
                    @else
                        Produk #{{ $item['product_id'] }}
                    @endif
                </div>
                <div class="col-span-2 text-center">{{ $item['quantity'] }}</div>
                <div class="col-span-3 text-right">
                    @if(isset($item['price']))
                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                    @else
                        -
                    @endif
                </div>
            </div>
            @endforeach

            <!-- Total -->
            <div class="bg-blue-200 grid grid-cols-12 gap-2 px-4 py-3 text-sm font-bold">
                <div class="col-span-7">Total</div>
                <div class="col-span-2 text-center">{{ collect($transaction->items)->sum('quantity') }}</div>
                <div class="col-span-3 text-right">Rp {{ number_format($transaction->total, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <a href="/transactions" class="block w-full bg-primary hover:bg-blue-700 text-white py-4 rounded-xl shadow font-semibold text-center">
                Kembali ke History
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
