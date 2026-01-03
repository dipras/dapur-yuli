@extends('layouts.main')

@section('content')

<div class="relative">

    <!-- Header Image -->
    <div class="h-40 w-full">
        <img
            src="/warung.png"
            alt="header"
            class="h-full w-full object-cover" />
    </div>

    <!-- Form Content -->
    <div class="relative">
        <div class="w-full h-5 bg-white absolute -top-5 rounded-t-3xl"></div>
        <div class="px-6 pb-6">
            <h2 class="text-2xl font-bold text-center mb-6">SIGN IN</h2>
    
            <form method="POST" action="/login">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="email@example.com"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
        
                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="************"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror" />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
        
                <!-- Remember Me -->
                <div class="flex items-center mb-4">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        class="mr-2 rounded border-gray-300 text-primary focus:ring-blue-500" />
                    <label for="remember" class="text-sm">Remember me</label>
                </div>
        
                <!-- Login Button -->
                <button
                    type="submit"
                    class="w-full bg-primary text-white py-2 rounded-lg font-semibold hover:bg-secondary transition">
                    Login
                </button>
            </form>
    
            <!-- Divider -->
            <div class="flex items-center my-6">
                <div class="grow h-px bg-gray-300"></div>
                <span class="px-3 text-sm text-gray-500">Login with others</span>
                <div class="grow h-px bg-gray-300"></div>
            </div>
    
            <!-- Google Login -->
            <button
                class="w-full flex items-center justify-center border border-gray-300 rounded-lg py-2 hover:bg-gray-100 transition">
                <img
                    src="https://www.svgrepo.com/show/475656/google-color.svg"
                    alt="Google"
                    class="w-6 h-6" />
            </button>
        </div>
    </div>
</div>

@endsection