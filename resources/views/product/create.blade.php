@extends('layouts.sidebarpage')

@section('header_title', 'TAMBAH STOK')

@section('subcontent')
<form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="px-4 py-6 space-y-6" x-data="{ imageUrl: '', previewImage(event) { const file = event.target.files[0]; if (file) { this.imageUrl = URL.createObjectURL(file); } } }">
    @csrf
    
    <!-- Error Messages -->
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <!-- Image Upload -->
    <div class="flex justify-center">
        <label class="relative cursor-pointer">
            <input type="file" name="image" class="hidden" accept="image/*" required @change="previewImage($event)">
            <div class="w-80 h-48 bg-white rounded-2xl shadow-lg flex items-center justify-center overflow-hidden" 
                 :style="imageUrl ? `background-image: url(${imageUrl}); background-size: cover; background-position: center;` : ''">
                <template x-if="!imageUrl">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                            <x-heroicon-o-camera class="w-8 h-8 text-gray-400" />
                        </div>
                        <p class="text-gray-500 font-medium">Tambahkan Gambar</p>
                    </div>
                </template>
            </div>
        </label>
    </div>
    
    <!-- Category -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis stok</label>
        <select name="category" required class="w-full px-4 py-3 bg-white rounded-xl shadow-sm border-0 focus:ring-2 focus:ring-blue-500 appearance-none @error('category') ring-2 ring-red-500 @enderror">
            <option value="">Pilih kategori</option>
            <option value="food" {{ old('category') == 'food' ? 'selected' : '' }}>Makanan</option>
            <option value="drink" {{ old('category') == 'drink' ? 'selected' : '' }}>Minuman</option>
        </select>
        @error('category')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Product Name -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama stok</label>
        <input type="text" name="product_name" required value="{{ old('product_name') }}"
               placeholder="Nama produk"
               class="w-full px-4 py-3 bg-white rounded-xl shadow-sm border-0 focus:ring-2 focus:ring-blue-500 @error('product_name') ring-2 ring-red-500 @enderror">
        @error('product_name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Price -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Harga stok</label>
        <input type="number" name="price" required min="0" step="100" value="{{ old('price') }}"
               placeholder="Rp. 0"
               class="w-full px-4 py-3 bg-white rounded-xl shadow-sm border-0 focus:ring-2 focus:ring-blue-500 @error('price') ring-2 ring-red-500 @enderror">
        @error('price')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Stock Quantity -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah stok</label>
        <input type="number" name="stock" required min="0" value="{{ old('stock') }}"
               placeholder="0"
               class="w-full px-4 py-3 bg-white rounded-xl shadow-sm border-0 focus:ring-2 focus:ring-blue-500 @error('stock') ring-2 ring-red-500 @enderror">
        @error('stock')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Submit Button -->
    <div class="pt-4">
        <button type="submit" class="w-full bg-white text-gray-800 font-semibold py-3 rounded-xl shadow-lg hover:bg-gray-50 transition">
            Simpan
        </button>
    </div>
</form>
@endsection
