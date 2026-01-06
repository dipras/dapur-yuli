@extends('layouts.sidebarpage')

@section('subcontent')
<!-- Alternative Flow: Error Messages -->
@if(session('error'))
<div class="px-4 pt-4">
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

<!-- Alternative Flow: Validation Errors (Stok Tidak Cukup) -->
@if($errors->any())
<div class="px-4 pt-4">
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p class="text-yellow-700 font-medium">Stok Tidak Mencukupi</p>
        </div>
        <ul class="text-yellow-600 text-sm mt-1 ml-7 list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form method="POST" action="/checkout/summary" x-data="{ quantities: {}, get hasItems() { return Object.values(this.quantities).some(q => q > 0) } }">
    @csrf
    <!-- Category Tabs -->
    <div class="px-4 py-4 flex gap-3 overflow-x-auto">
        <a href="/" class="{{ $type == 'all' ? 'bg-primary text-white' : 'bg-white' }} px-4 py-2 rounded-full text-xs shadow">
            Semua ({{ $foodTotal + $drinkTotal }})
        </a>
        <a href="/?type=food" class="{{ $type == 'food' ? 'bg-primary text-white' : 'bg-white' }} px-4 py-2 rounded-full text-xs shadow">
            Makanan ({{ $foodTotal }})
        </a>
        <a href="/?type=drink" class="{{ $type == 'drink' ? 'bg-primary text-white' : 'bg-white' }} px-4 py-2 rounded-full text-xs shadow">
            Minuman ({{ $drinkTotal }})
        </a>
    </div>

    <!-- Product List -->
    <div class="px-4 space-y-4 pb-24">

        <!-- Item -->
        @foreach($products as $product)
        <div class="bg-white rounded-xl shadow flex items-center p-3">
            <img src="{{ $product->image }}" class="w-16 h-16 rounded-lg object-cover">
            <div class="ml-3 flex-1">
                <h3 class="font-semibold">{{ $product->name }}</h3>
                <p class="text-xs text-gray-500">Stok tak terbatas</p>
                <p class="font-bold text-sm mt-1">Rp. {{ $product->price }}</p>
            </div>
            <div class="flex items-center gap-2" x-init="quantities[{{ $product->id }}] = 0">
                <button type="button" @click="quantities[{{ $product->id }}] = Math.max(0, quantities[{{ $product->id }}] - 1)" class="w-7 h-7 rounded-full border border-red-500 text-red-500 flex items-center justify-center hover:bg-red-50">−</button>
                <input type="number" name="products[{{ $product->id }}]" x-model="quantities[{{ $product->id }}]" class="w-10 text-sm font-semibold text-center border-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" readonly>
                <button type="button" @click="quantities[{{ $product->id }}]++" class="w-7 h-7 rounded-full border border-green-500 text-green-500 flex items-center justify-center hover:bg-green-50">+</button>
            </div>
        </div>
        @endforeach


    </div>

    <div class="sticky bottom-4 left-4 right-4 px-8" x-show="hasItems">
        <button type="submit" class="w-full bg-primary text-white py-3 rounded-xl shadow flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span>Keranjang</span>
        </button>
    </div>
</form>
@endsection