@extends('admin.layouts.app')

@section('page_title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
    <div class="relative bg-white rounded-xl shadow-sm border border-gray-100 p-5 overflow-hidden hover:shadow-md transition-shadow">
        <div class="absolute top-0 right-0 w-20 h-20 bg-sky-50 rounded-bl-3xl -mr-8 -mt-8"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-sky-600">Pesanan Hari Ini</span>
                <div class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $ordersToday }}</p>
        </div>
    </div>

    <div class="relative bg-white rounded-xl shadow-sm border border-gray-100 p-5 overflow-hidden hover:shadow-md transition-shadow">
        <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-50 rounded-bl-3xl -mr-8 -mt-8"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Total Revenue</span>
                <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="relative bg-white rounded-xl shadow-sm border border-gray-100 p-5 overflow-hidden hover:shadow-md transition-shadow">
        <div class="absolute top-0 right-0 w-20 h-20 bg-amber-50 rounded-bl-3xl -mr-8 -mt-8"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-amber-600">Customer Aktif</span>
                <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $activeCustomers }}</p>
        </div>
    </div>

    <div class="relative bg-white rounded-xl shadow-sm border border-gray-100 p-5 overflow-hidden hover:shadow-md transition-shadow">
        <div class="absolute top-0 right-0 w-20 h-20 bg-rose-50 rounded-bl-3xl -mr-8 -mt-8"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-rose-600">Stok Rendah</span>
                <div class="w-9 h-9 bg-rose-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-rose-600">{{ $lowStocks->count() }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-indigo-500 rounded-full inline-block"></span>
            Pesanan Hari Ini per Status
        </h3>
        <div class="space-y-3">
            @php
                $statusLabels = [1 => 'Pending', 2 => 'Paid', 3 => 'Processing', 4 => 'Shipped', 5 => 'Delivered', 6 => 'Cancelled'];
                $statusColors = [1 => 'bg-amber-100 text-amber-800', 2 => 'bg-blue-100 text-blue-800', 3 => 'bg-indigo-100 text-indigo-800', 4 => 'bg-purple-100 text-purple-800', 5 => 'bg-emerald-100 text-emerald-800', 6 => 'bg-gray-100 text-gray-800'];
            @endphp
            @foreach($statusLabels as $key => $label)
                <div class="flex items-center justify-between py-1.5">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ ['bg-amber-400','bg-blue-400','bg-indigo-400','bg-purple-400','bg-emerald-400','bg-gray-400'][$key-1] }}"></span>
                        <span class="text-sm text-gray-600">{{ $label }}</span>
                    </span>
                    <span class="text-sm font-semibold {{ explode(' ', $statusColors[$key-1])[1] }} px-2.5 py-0.5 rounded-full {{ explode(' ', $statusColors[$key-1])[0] }}">
                        {{ $ordersByStatus[$key] ?? 0 }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-rose-500 rounded-full inline-block"></span>
            Stok Menipis (&lt; 5)
        </h3>
        @if($lowStocks->isEmpty())
            <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm">Semua stok aman.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-100">
                            <th class="pb-2 font-medium text-gray-500 text-xs uppercase tracking-wider">Produk / SKU</th>
                            <th class="pb-2 font-medium text-gray-500 text-xs uppercase tracking-wider">Gudang</th>
                            <th class="pb-2 font-medium text-gray-500 text-xs uppercase tracking-wider text-right">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStocks as $stock)
                            <tr class="border-b border-gray-50 hover:bg-rose-50 transition-colors">
                                <td class="py-2.5 text-gray-800">{{ $stock->productVariant->product->name ?? '-' }} / {{ $stock->productVariant->sku }}</td>
                                <td class="py-2.5 text-gray-500">{{ $stock->warehouse->name }}</td>
                                <td class="py-2.5 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">{{ $stock->quantity }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
