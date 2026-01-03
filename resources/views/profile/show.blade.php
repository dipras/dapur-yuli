@extends('layouts.sidebarpage')

@section('header_title', 'PROFILE')

@section('subcontent')
<div class="px-4 py-6">
    
    <!-- Success Message -->
    @if (session('success'))
    <div class="mb-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    </div>
    @endif
    
    <!-- Profile Card with Gradient Background -->
    <div class="bg-gradient-to-b from-blue-500 to-cyan-400 rounded-3xl p-6 mb-6 relative overflow-hidden">
        <div class="flex flex-col items-center relative z-10">
            <!-- Avatar -->
            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg mb-4">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->full_name ?? $user->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <x-heroicon-o-user class="w-16 h-16 text-gray-400" />
                    </div>
                @endif
            </div>
            
            <!-- User Info -->
            <h2 class="font-bold text-xl text-white mb-1">{{ $user->full_name ?? $user->name }}</h2>
            <p class="text-white text-sm opacity-90">{{ $user->email }}</p>
            
            <!-- Edit Button -->
            <a href="{{ route('profile.edit') }}" class="mt-4 bg-white text-blue-600 font-semibold px-6 py-2 rounded-full shadow flex items-center gap-2 hover:bg-gray-50 transition">
                <x-heroicon-o-pencil class="w-4 h-4" />
                <span>Edit Profile</span>
            </a>
        </div>
    </div>
    
    <!-- Information Section -->
    <div class="bg-white rounded-3xl shadow-sm p-6">
        <h3 class="font-bold text-lg mb-4">Information</h3>
        
        <div class="space-y-4">
            <!-- Nama Lengkap -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500 text-sm">Nama Lengkap</span>
                <span class="font-semibold text-right">{{ $user->full_name ?? '-' }}</span>
            </div>
            
            <!-- Username -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500 text-sm">Username</span>
                <span class="font-semibold">{{ $user->username ?? '-' }}</span>
            </div>
            
            <!-- Jenis Kelamin -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500 text-sm">Jenis Kelamin</span>
                <span class="font-semibold">{{ $user->gender ? ($user->gender == 'male' ? 'Laki-laki' : 'Perempuan') : '-' }}</span>
            </div>
            
            <!-- Tanggal Lahir -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500 text-sm">Tanggal Lahir</span>
                <span class="font-semibold">{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d-m-Y') : '-' }}</span>
            </div>
            
            <!-- Jabatan -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500 text-sm">Jabatan</span>
                <span class="font-semibold">{{ $user->role ? ucfirst($user->role->value) : '-' }}</span>
            </div>
            
            <!-- No Telp -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500 text-sm">No Telp.</span>
                <span class="font-semibold">{{ $user->phone ?? '-' }}</span>
            </div>
            
            <!-- Alamat -->
            <div class="flex justify-between items-start py-3">
                <span class="text-gray-500 text-sm">Alamat</span>
                <span class="font-semibold text-right max-w-[60%]">{{ $user->address ?? '-' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
