@extends('layouts.sidebarpage')

@section('header_title', 'Manajemen User')

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

<!-- List -->
<div class="px-4 mt-4 space-y-3 pb-4">
    @forelse($users as $user)
    <!-- User Item -->
    <div class="bg-white rounded-xl shadow flex items-center p-3">
        <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-200 flex-shrink-0">
            @if($user->avatar)
                <img src="{{ $user->avatar }}" alt="{{ $user->full_name ?? $user->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <x-heroicon-o-user class="w-6 h-6" />
                </div>
            @endif
        </div>
        <div class="ml-3 flex-1">
            <p class="font-semibold">{{ $user->full_name ?? $user->name }}</p>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
            <p class="text-xs text-gray-400">{{ $user->role ? ucfirst($user->role->value) : '-' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('users.edit', $user) }}" class="text-yellow-500 hover:text-yellow-600">
                <x-heroicon-o-pencil class="w-5 h-5" />
            </a>
            @if($user->id !== Auth::id())
            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-600">
                    <x-heroicon-o-trash class="w-5 h-5" />
                </button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow p-4 text-center text-gray-500">
        Tidak ada user
    </div>
    @endforelse
</div>
@endsection
