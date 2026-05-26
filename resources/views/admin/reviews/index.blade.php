@extends('admin.layouts.app')

@section('page_title', 'Moderasi Ulasan Produk')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
    <form class="flex gap-2">
      <select name="rating" class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-400 outline-none">
        <option value="">Semua Rating</option>
        @foreach([1,2,3,4,5] as $r)<option value="{{ $r }}" @selected(request('rating') == $r)>{{ $r }} ★</option>@endforeach
      </select>
      <select name="visibility" class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-400 outline-none">
        <option value="">Semua Status</option>
        <option value="1" @selected(request('visibility') === '1')>Tampil</option>
        <option value="0" @selected(request('visibility') === '0')>Tersembunyi</option>
      </select>
      <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Filter</button>
      @if(request()->anyFilled(['rating','visibility']))<a href="{{ route('admin.reviews.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2.5 text-sm font-medium transition-colors">Reset</a>@endif
    </form>
  </div>
  <div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead><tr class="bg-gray-50 text-left">
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Produk</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Customer</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Rating</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Ulasan</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Tampil</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Tanggal</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
    </tr></thead>
    <tbody>
      @foreach($reviews as $review)
      <tr class="border-t border-gray-100 hover:bg-sky-50/50 transition-colors">
        <td class="px-6 py-4 font-medium text-gray-800">{{ $review->product?->name }}</td>
        <td class="px-6 py-4">{{ $review->user?->name }}</td>
        <td class="px-6 py-4">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5-$review->rating) }}</td>
        <td class="px-6 py-4 max-w-xs truncate">{{ $review->reason }}</td>
        <td class="px-6 py-4">{!! $review->is_visible ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Ya</span>' : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Tidak</span>' !!}</td>
        <td class="px-6 py-4">{{ $review->created_at->format('d/m/Y') }}</td>
        <td class="px-6 py-4">
          <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST">
            @csrf
            <button class="text-sky-600 hover:text-sky-800 text-sm font-medium transition-colors">{{ $review->is_visible ? 'Sembunyikan' : 'Tampilkan' }}</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">{{ $reviews->links() }}</div>
</div>
@endsection
