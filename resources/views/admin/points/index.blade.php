@extends('admin.layouts.app')

@section('page_title', 'Monitoring Poin Reward')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100">
    <form class="flex gap-2">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email..." class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-400 outline-none">
      <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Cari</button>
      @if(request('search'))<a href="{{ route('admin.points.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2.5 text-sm font-medium transition-colors">Reset</a>@endif
    </form>
  </div>
  <div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead><tr class="bg-gray-50 text-left">
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Nama</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Saldo Poin</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
    </tr></thead>
    <tbody>
      @foreach($users as $user)
      <tr class="border-t border-gray-100 hover:bg-sky-50/50 transition-colors">
        <td class="px-6 py-4 font-medium text-gray-800">{{ $user->name }}</td>
        <td class="px-6 py-4">{{ $user->email }}</td>
        <td class="px-6 py-4 font-semibold">{{ number_format($user->userPoint?->balance ?? 0) }}</td>
        <td class="px-6 py-4"><a href="{{ route('admin.points.show', $user) }}" class="text-sky-600 hover:text-sky-800 text-sm font-medium transition-colors">Riwayat</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">{{ $users->links() }}</div>
</div>
@endsection
