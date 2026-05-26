@extends('admin.layouts.app')

@section('page_title', 'Otorisasi Role: '.$role->name)

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <form action="{{ route('admin.roles.authorizations.update', $role) }}" method="POST">
    @csrf @method('PUT')

    @foreach($moduleGroups as $group)
      <div class="mb-6">
        <h4 class="font-semibold text-gray-800 mb-2 border-b pb-1">{{ $group->name }}</h4>
        @foreach($group->modules as $module)
          <div class="flex items-center gap-4 mb-2 pl-2">
            <span class="w-48 text-sm">{{ $module->name }}</span>
            @foreach($authorizationTypes as $type)
              <label class="flex items-center gap-1 text-sm">
                <input type="checkbox" name="authorizations[{{ $module->id }}][]" value="{{ $type->id }}"
                  {{ ($existing[$module->id] ?? collect())->contains('authorization_type_id', $type->id) ? 'checked' : '' }}>
                {{ $type->name }}
              </label>
            @endforeach
          </div>
        @endforeach
      </div>
    @endforeach

    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Simpan Otorisasi</button>
    <a href="{{ route('admin.roles.index') }}" class="ml-2 text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors">Kembali</a>
  </form>
</div>
@endsection
