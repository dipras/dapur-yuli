@extends('layouts.main')

@section('content')
<div class="bg-slate-100 relative min-h-screen" x-data="{ open: false }" `>

    <!-- Header -->
    <header class="bg-primary text-white flex items-center justify-between px-4 py-4">
        <button class="text-2xl" @click="open = true">☰</button>
        <h1 class="font-bold tracking-wide">@yield('header_title', 'DAPUR YULI')</h1>
        <div class="w-6"></div>
    </header>
    @yield('subcontent')


    <aside
        x-show="open == true"
        x-transition:enter="transition transform duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="absolute top-0 left-0 w-72 bg-white rounded-r-3xl shadow-lg flex flex-col h-full"
        style="z-index: 100;"
        >

        <!-- User Card -->
        <div class="p-6">
            <a href="{{ route('profile.show') }}" class="flex items-center gap-3 bg-white shadow rounded-2xl p-4 hover:bg-gray-50 transition">
                <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-200 flex-shrink-0">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <x-heroicon-o-user class="w-6 h-6" />
                        </div>
                    @endif
                </div>
                <div>
                    <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->role ? ucfirst(Auth::user()->role->value) : 'Kasir' }}</p>
                </div>
            </a>
        </div>

        <!-- Menu -->
        <nav class="flex-1 flex flex-col px-6 gap-6 pb-24">
            <a href="/" class="flex items-center gap-3 font-bold {{ request()->is('/') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                📋 <span>Checkout</span>
            </a>
            <a href="/transactions" class="flex items-center gap-3 font-bold {{ request()->is('transactions*') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                🧾 <span>Histori Transaksi</span>
            </a>
            <a href="/product" class="flex items-center gap-3 font-bold {{ request()->is('product*') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                🍽️ <span>Atur stok makanan</span>
            </a>
            <a href="/report" class="flex items-center gap-3 font-bold {{ request()->is('report*') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                💰 <span>Laporan Keuangan</span>
            </a>
            @if(Auth::user()->role && Auth::user()->role->value === 'admin')
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 font-bold {{ request()->is('users*') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                👥 <span>Manajemen User</span>
            </a>
            @endif
            <form method="POST" action="/logout" class="flex flex-col justify-end">
                @csrf
                <button type="submit" class="flex items-center gap-3 font-bold text-gray-700 hover:text-red-600">
                    🚪 <span>Logout</span>
                </button>
            </form>
        </nav>

        <!-- Logout -->

    </aside>
    <div x-show="open == true" @click="open = false" class="absolute w-full h-full top-0 left-0 z-1" style="background: rgba(0, 0, 0, 0.3)"></div>

</div>

@endsection