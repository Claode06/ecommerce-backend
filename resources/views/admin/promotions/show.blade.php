@extends('admin.layouts.app')

@section('page_title', $promotion->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-1">
    <div class="bg-white rounded-lg shadow p-6">
      <h4 class="font-semibold mb-4">Info Promosi</h4>
      <p class="text-sm mb-1"><span class="text-gray-500">Nama:</span> {{ $promotion->name }}</p>
      <p class="text-sm mb-1"><span class="text-gray-500">Tipe:</span> {{ ['1'=>'Flash Sales'][$promotion->type] }}</p>
      <p class="text-sm mb-1"><span class="text-gray-500">Status:</span> {!! $promotion->is_active ? '<span class="text-green-600">Aktif</span>' : '<span class="text-red-600">Nonaktif</span>' !!}</p>
      <p class="text-sm mb-1"><span class="text-gray-500">Mulai:</span> {{ \Carbon\Carbon::parse($promotion->start_at)->format('d/m/Y H:i') }}</p>
      <p class="text-sm"><span class="text-gray-500">Berakhir:</span> {{ \Carbon\Carbon::parse($promotion->end_at)->format('d/m/Y H:i') }}</p>
      <a href="{{ route('admin.promotions.edit', $promotion) }}" class="mt-4 inline-block bg-gray-900 text-white px-4 py-2 rounded text-sm hover:bg-gray-800">Edit</a>
    </div>
  </div>
  <div class="lg:col-span-2">
    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex justify-between items-center mb-4">
        <h4 class="font-semibold">Item Promosi</h4>
        <a href="{{ route('admin.promotions.items.create', $promotion) }}" class="bg-gray-900 text-white px-3 py-1 rounded text-xs hover:bg-gray-800">+ Tambah Item</a>
      </div>
      @if($promotion->promotionItems->isEmpty())
        <p class="text-gray-500 text-sm">Belum ada item promosi.</p>
      @else
        <table class="w-full text-sm">
          <thead><tr class="bg-gray-50 text-left"><th class="p-2">Produk</th><th class="p-2">Varian</th><th class="p-2">Harga Normal</th><th class="p-2">Harga Promo</th><th class="p-2">Aksi</th></tr></thead>
          <tbody>
            @foreach($promotion->promotionItems as $item)
            <tr class="border-t">
              <td class="p-2">{{ $item->productVariant?->product?->name }}</td>
              <td class="p-2">{{ $item->productVariant?->label }} ({{ $item->productVariant?->sku }})</td>
              <td class="p-2">Rp {{ number_format($item->productVariant?->price, 0, ',', '.') }}</td>
              <td class="p-2 text-red-600 font-semibold">Rp {{ number_format($item->override_price, 0, ',', '.') }}</td>
              <td class="p-2">
                <form action="{{ route('admin.promotions.items.destroy', [$promotion, $item]) }}" method="POST" onsubmit="return confirm('Hapus item?')">
                  @csrf @method('DELETE')<button class="text-red-600 hover:underline">Hapus</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>
</div>
@endsection
