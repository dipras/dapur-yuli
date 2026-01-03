@extends('layouts.sidebarpage')

@section('header_title', 'EDIT PROFILE')

@section('subcontent')
<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="px-4 py-6 space-y-6" x-data="{ avatarUrl: '{{ $user->avatar }}', previewAvatar(event) { const file = event.target.files[0]; if (file) { this.avatarUrl = URL.createObjectURL(file); } } }">
    @csrf
    @method('PUT')
    
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
    
    <!-- Avatar Upload -->
    <div class="flex justify-center">
        <label class="relative cursor-pointer">
            <input type="file" name="avatar" class="hidden" accept="image/*" @change="previewAvatar($event)">
            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200 shadow-lg flex items-center justify-center bg-white">
                <template x-if="avatarUrl">
                    <img :src="avatarUrl" class="w-full h-full object-cover" alt="Avatar">
                </template>
                <template x-if="!avatarUrl">
                    <div class="flex flex-col items-center text-gray-400">
                        <x-heroicon-o-camera class="w-8 h-8" />
                        <span class="text-xs mt-1">Upload</span>
                    </div>
                </template>
            </div>
        </label>
    </div>
    
    <!-- Nama Lengkap -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap<span class="text-red-500">*</span></label>
        <input type="text" name="full_name" required 
               value="{{ old('full_name', $user->full_name) }}"
               placeholder="Nama lengkap"
               class="w-full px-4 py-3 bg-white rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('full_name') ring-2 ring-red-500 @enderror">
        @error('full_name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Username -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">Username<span class="text-red-500">*</span></label>
        <input type="text" name="username" required 
               value="{{ old('username', $user->username) }}"
               placeholder="Username"
               class="w-full px-4 py-3 bg-white rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('username') ring-2 ring-red-500 @enderror">
        @error('username')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Tanggal Lahir -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Lahir<span class="text-red-500">*</span></label>
        <input type="date" name="birth_date" required 
               value="{{ old('birth_date', $user->birth_date) }}"
               class="w-full px-4 py-3 bg-white rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('birth_date') ring-2 ring-red-500 @enderror">
        @error('birth_date')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Jenis Kelamin -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin<span class="text-red-500">*</span></label>
        <select name="gender" required class="w-full px-4 py-3 bg-white rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none @error('gender') ring-2 ring-red-500 @enderror">
            <option value="">Pilih jenis kelamin</option>
            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
        </select>
        @error('gender')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Alamat -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat<span class="text-red-500">*</span></label>
        <textarea name="address" required rows="3"
                  placeholder="Alamat lengkap"
                  class="w-full px-4 py-3 bg-white rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') ring-2 ring-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
        @error('address')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- No. Telp -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">No. Telp</label>
        <input type="text" name="phone" 
               value="{{ old('phone', $user->phone) }}"
               placeholder="Nomor telepon"
               class="w-full px-4 py-3 bg-white rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') ring-2 ring-red-500 @enderror">
        @error('phone')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Submit Buttons -->
    <div class="space-y-3 pt-4">
        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-xl shadow-lg hover:bg-blue-700 transition">
            Simpan Perubahan
        </button>
        <a href="{{ route('profile.show') }}" class="w-full bg-white text-blue-600 font-semibold py-3 rounded-xl border-2 border-blue-600 hover:bg-blue-50 transition flex items-center justify-center">
            Batalkan Perubahan
        </a>
    </div>
</form>
@endsection
