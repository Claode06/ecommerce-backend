@extends('admin.layouts.app')

@section('page_title', 'Edit Admin')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <form action="{{ route('admin.admins.update', $admin) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Nama</label>
      <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('name') border-rose-300 ring-2 ring-rose-200 @enderror">
      @error('name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Email</label>
      <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('email') border-rose-300 ring-2 ring-rose-200 @enderror">
      @error('email')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Role</label>
      <select name="role_id" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none @error('role_id') border-rose-300 ring-2 ring-rose-200 @enderror">
        @foreach($roles as $role)
          <option value="{{ $role->id }}" @selected(old('role_id', $admin->role_id) == $role->id)>{{ $role->name }}</option>
        @endforeach
      </select>
      @error('role_id')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium mb-1">Status</label>
      <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none">
        <option value="1" @selected($admin->status)>Aktif</option>
        <option value="0" @selected(!$admin->status)>Nonaktif</option>
      </select>
    </div>
    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Simpan</button>
    <a href="{{ route('admin.admins.index') }}" class="ml-2 text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors">Batal</a>
  </form>
</div>
@endsection
