@extends('admin.layouts.app')

@section('page_title', $product->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-2">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <h4 class="font-semibold mb-4">Info Produk</h4>
      <p class="text-sm mb-1"><span class="text-gray-500">Nama:</span> {{ $product->name }}</p>
      <p class="text-sm mb-1"><span class="text-gray-500">Slug:</span> {{ $product->slug }}</p>
      <p class="text-sm mb-1"><span class="text-gray-500">Kategori:</span> {{ $product->category?->name }}</p>
      <p class="text-sm mb-1"><span class="text-gray-500">Merek:</span> {{ $product->brand?->name }}</p>
      <p class="text-sm mb-1"><span class="text-gray-500">Gender:</span> {{ ['1'=>'Wanita','2'=>'Pria','3'=>'Unisex'][$product->gender] }}</p>
      <p class="text-sm mb-1"><span class="text-gray-500">Status:</span> {!! $product->is_active ? '<span class="text-green-600">Aktif</span>' : '<span class="text-red-600">Nonaktif</span>' !!}</p>
      @if($product->description)<p class="text-sm mb-1 mt-2"><span class="text-gray-500">Deskripsi:</span></p><p class="text-sm">{{ $product->description }}</p>@endif
      @if($product->features)<p class="text-sm mb-1 mt-2"><span class="text-gray-500">Features:</span></p><p class="text-sm">{{ $product->features }}</p>@endif
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <div class="flex justify-between items-center mb-4">
        <h4 class="font-semibold">Varian Produk</h4>
        <a href="{{ route('admin.products.variants.create', $product) }}" class="bg-gray-900 text-white px-3 py-1 rounded text-xs hover:bg-gray-800">+ Varian</a>
      </div>
      @if($product->variants->isEmpty())
        <p class="text-gray-500 text-sm">Belum ada varian.</p>
      @else
        <table class="w-full text-sm">
          <thead><tr class="bg-gray-50 text-left"><th class="p-2">Label</th><th class="p-2">SKU</th><th class="p-2">Harga</th><th class="p-2">Status</th><th class="p-2">Aksi</th></tr></thead>
          <tbody>
            @foreach($product->variants as $variant)
            <tr class="border-t">
              <td class="p-2">{{ $variant->label }}</td>
              <td class="p-2">{{ $variant->sku }}</td>
              <td class="p-2">Rp {{ number_format($variant->price, 0, ',', '.') }}</td>
              <td class="p-2">{!! $variant->is_active ? '<span class="text-green-600">Aktif</span>' : '<span class="text-red-600">Nonaktif</span>' !!}</td>
              <td class="p-2 flex gap-2">
                <a href="{{ route('admin.products.variants.edit', [$product, $variant]) }}" class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" method="POST" onsubmit="return confirm('Hapus varian?')">
                  @csrf @method('DELETE')<button class="text-red-600 hover:underline">Hapus</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <h4 class="font-semibold mb-4">Galeri Gambar</h4>
      @if($product->images->isEmpty())
        <p class="text-gray-500 text-sm">Belum ada gambar galeri.</p>
      @else
        <div class="grid grid-cols-4 gap-2">
          @foreach($product->images as $image)
            <div class="border rounded p-1"><img src="{{ asset('storage/'.$image->fileStorage?->link) }}" class="w-full h-24 object-cover"></div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
  <div class="lg:col-span-1">
    <div class="bg-white rounded-lg shadow p-6">
      <h4 class="font-semibold mb-4">Thumbnail</h4>
      <img src="{{ asset('storage/'.$product->thumbnailFile?->link) }}" class="w-full rounded">
    </div>
  </div>
</div>
@endsection
