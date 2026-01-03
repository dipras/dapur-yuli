@extends('layouts.sidebarpage')

@section('header_title', 'Atur Stocks')
@section('subcontent')

<!-- Success Message -->
@if (session('success'))
<div class="px-4 mt-4">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
        {{ session('success') }}
    </div>
</div>
@endif

<!-- Error Messages -->
@if ($errors->any())
<div class="px-4 mt-4">
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<!-- Search -->
<div class="px-4 mt-4">
    <form action="{{ route('product.index') }}" method="GET">
        @if(request('type'))
        <input type="hidden" name="type" value="{{ request('type') }}">
        @endif
        <div class="relative">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari Barang"
                class="w-full rounded-xl px-4 py-3 pr-10 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @if(request('search'))
            <a href="{{ route('product.index', array_filter(['type' => request('type')])) }}"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </a>
            @else
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <x-heroicon-o-magnifying-glass class="w-5 h-5" />
            </button>
            @endif
        </div>
    </form>
</div>

<!-- Filter -->
<div class="flex items-center gap-2 px-4 mt-4">
    <a href="{{ route('product.index', array_filter(['search' => request('search')])) }}" class="{{ !request('type') ? 'bg-blue-500 text-white' : 'bg-white' }} px-4 py-2 rounded-full text-sm font-medium shadow">
        Semua ({{ $allTotal }})
    </a>
    <a href="{{ route('product.index', array_filter(['type' => 'food', 'search' => request('search')])) }}" class="{{ request('type') == 'food' ? 'bg-blue-500 text-white' : 'bg-white' }} px-4 py-2 rounded-full text-sm shadow">
        Makanan ({{ $foodTotal }})
    </a>
    <a href="{{ route('product.index', array_filter(['type' => 'drink', 'search' => request('search')])) }}" class="{{ request('type') == 'drink' ? 'bg-blue-500 text-white' : 'bg-white' }} px-4 py-2 rounded-full text-sm shadow">
        Minuman ({{ $drinkTotal }})
    </a>
</div>

<!-- List -->
<div class="px-4 mt-4 space-y-3">
    @forelse($products as $product)
    <!-- Item -->
    <div class="bg-white rounded-xl shadow flex items-center p-3">
        <img src="{{ $product->image }}" class="w-16 h-16 rounded-lg object-cover" alt="{{ $product->product_name }}">
        <div class="ml-3 flex-1">
            <p class="font-semibold">{{ $product->product_name }}</p>
            <p class="text-sm text-gray-500">Stok: {{ $product->stock }}</p>
            <p class="font-semibold">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('product.edit', $product) }}" class="text-yellow-500 hover:text-yellow-600">
                <x-heroicon-o-pencil class="w-5 h-5" />
            </a>
            <form action="{{ route('product.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-600">
                    <x-heroicon-o-trash class="w-5 h-5" />
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow p-4 text-center text-gray-500">
        Tidak ada produk
    </div>
    @endforelse

</div>
<a href="{{ route('product.create') }}" class="bg-primary ml-2 w-12 h-12 text-2xl text-white rounded-full shadow flex items-center justify-center sticky bottom-4 cursor-pointer hover:bg-blue-600 left-0">
    <x-heroicon-o-plus class="w-6 h-6" />
</a>
@endsection