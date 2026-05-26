@extends('admin.layouts.app')

@section('title', 'Login - C-Commerce Admin')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 via-purple-500 to-rose-400 py-12 px-4">
    <div class="bg-white/95 backdrop-blur-sm p-8 rounded-2xl shadow-2xl w-full max-w-md border border-white/20">
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-gradient-to-br from-sky-600 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-sky-100">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">C-Commerce</h1>
            <p class="text-gray-500 text-sm mt-1">Masuk ke panel admin</p>
        </div>

        @if($errors->any())
            <div class="flex items-center gap-2 bg-rose-50 border-l-4 border-rose-500 text-rose-800 px-4 py-3 rounded-r-lg shadow-sm mb-6">
                <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium">{{ $errors->first() }}</p>
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sky-400 focus:border-sky-500 outline-none transition-shadow"
                       placeholder="admin@example.com" required autofocus>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1.5">Password</label>
                <input type="password" name="password"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sky-400 focus:border-sky-500 outline-none transition-shadow"
                       placeholder="••••••••" required>
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-sky-600 focus:ring-sky-400">
                    <span class="text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>
            <button type="submit"
                    class="w-full py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white font-medium rounded-xl hover:shadow-lg hover:shadow-sky-100 transition-all active:scale-[0.98]">
                Masuk
            </button>
        </form>
    </div>
</div>
@endsection
