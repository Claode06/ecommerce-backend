@extends('admin.layouts.app')

@section('page_title', 'File Storage')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-base font-semibold text-gray-800">Daftar File</h3></div>
  <div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead><tr class="bg-gray-50 text-left">
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">ID</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Link</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Tanggal Upload</th>
    </tr></thead>
    <tbody>
      @foreach($files as $file)
      <tr class="border-t border-gray-100 hover:bg-sky-50/50 transition-colors">
        <td class="px-6 py-4 font-medium text-gray-800">{{ $file->id }}</td>
        <td class="px-6 py-4"><a href="{{ asset('storage/'.$file->link) }}" target="_blank" class="text-sky-600 hover:text-sky-800 text-sm font-medium transition-colors">{{ $file->link }}</a></td>
        <td class="px-6 py-4">{{ $file->created_at->format('d/m/Y H:i') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">{{ $files->links() }}</div>
</div>
@endsection
