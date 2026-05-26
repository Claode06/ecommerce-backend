@extends('admin.layouts.app')

@section('page_title', 'Manajemen Admin')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <h3 class="text-base font-semibold text-gray-800">Daftar Admin</h3>
    <a href="{{ route('admin.admins.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      + Admin Baru
    </a>
  </div>
  <div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead><tr class="bg-gray-50 text-left">
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Nama</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Role</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Login</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
    </tr></thead>
    <tbody>
      @foreach($admins as $admin)
      <tr class="border-t border-gray-100 hover:bg-sky-50/50 transition-colors">
        <td class="px-6 py-4 font-medium text-gray-800">{{ $admin->name }}</td>
        <td class="px-6 py-4">{{ $admin->email }}</td>
        <td class="px-6 py-4">{{ $admin->role?->name }}</td>
        <td class="px-6 py-4">{!! $admin->status ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Aktif</span>' : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Nonaktif</span>' !!}</td>
        <td class="px-6 py-4 text-gray-500">{{ $admin->last_login ? $admin->last_login->format('d/m/Y H:i') : '-' }}</td>
        <td class="px-6 py-4">
          <div class="flex gap-2">
          <a href="{{ route('admin.admins.edit', $admin) }}" class="text-sky-600 hover:text-sky-800 text-sm font-medium transition-colors">Edit</a>
          <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Hapus admin ini?')">
            @csrf @method('DELETE')
            <button class="text-rose-600 hover:text-rose-800 text-sm font-medium transition-colors">Hapus</button>
          </form>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">{{ $admins->links() }}</div>
</div>
@endsection
