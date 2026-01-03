@extends('layouts.sidebarpage')

@section('subcontent')
<form method="GET" x-data="{ quantities: {}, get hasItems() { return Object.values(this.quantities).some(q => q > 0) } }">
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
                <input type="number" name="{{ $product->product_name }}" x-model="quantities[{{ $product->id }}]" class="w-10 text-sm font-semibold text-center border-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" readonly>
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