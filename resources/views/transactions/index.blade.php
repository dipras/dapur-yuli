@extends('layouts.sidebarpage')

@section('header_title', 'Riwayat Transaksi')
@section('subcontent')
<div class="min-h-screen bg-gray-50 pb-24">

    <!-- Filters -->
    <div class="bg-white p-4 shadow mb-4">
        <form method="GET" action="/transactions" class="space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs text-gray-600 mb-1 block">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-600 mb-1 block">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            
            <div>
                <label class="text-xs text-gray-600 mb-1 block">Metode Pembayaran</label>
                <select name="payment_method" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-primary text-white py-2 rounded-lg text-sm font-semibold">
                    Filter
                </button>
                <a href="/transactions" class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg text-sm font-semibold text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Total Summary -->
    <div class="mx-4 mb-4">
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-5 text-white">
            <p class="text-sm opacity-90 mb-1">Total Penjualan</p>
            <p class="text-3xl font-bold">Rp {{ number_format($totalSum, 0, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-2">{{ $transactions->total() }} Transaksi</p>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="px-4 space-y-3">
        @forelse($transactions as $transaction)
        <a href="/transactions/{{ $transaction->id }}" class="block bg-white rounded-xl shadow hover:shadow-md transition p-4">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="font-semibold text-sm">{{ $transaction->transaction_number }}</p>
                    <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }} WIB</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $transaction->payment_method == 'cash' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700' }}">
                    {{ $transaction->payment_method == 'cash' ? 'Cash' : 'QRIS' }}
                </span>
            </div>
            
            <div class="flex items-center justify-between mt-3 pt-3 border-t">
                <div class="text-xs text-gray-600">
                    <span>{{ count($transaction->items) }} item</span>
                    @if($transaction->user)
                    <span class="ml-2">• {{ $transaction->user->name }}</span>
                    @endif
                </div>
                <p class="font-bold text-primary">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
            </div>
        </a>
        @empty
        <div class="bg-white rounded-xl shadow p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-500">Belum ada transaksi</p>
        </div>
        @endforelse

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="py-4">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
