@extends('admin.layouts.app')

@section('page_title', 'Manajemen Stok')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100">
    <form class="flex gap-2">
      <select name="warehouse_id" class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-400 outline-none">
        <option value="">Semua Gudang</option>
        @foreach($warehouses as $wh)
          <option value="{{ $wh->id }}" @selected(request('warehouse_id') == $wh->id)>{{ $wh->name }}</option>
        @endforeach
      </select>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk/SKU..." class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-400 outline-none">
      <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Filter</button>
      @if(request()->anyFilled(['warehouse_id','search']))<a href="{{ route('admin.stocks.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2.5 text-sm font-medium transition-colors">Reset</a>@endif
    </form>
  </div>
  <div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead><tr class="bg-gray-50 text-left">
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Gudang</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Produk</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Varian</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">SKU</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Qty</th>
      <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
    </tr></thead>
    <tbody>
      @foreach($stocks as $stock)
      <tr class="border-t border-gray-100 hover:bg-sky-50/50 transition-colors">
        <td class="px-6 py-4 font-medium text-gray-800">{{ $stock->warehouse?->name }}</td>
        <td class="px-6 py-4">{{ $stock->productVariant?->product?->name }}</td>
        <td class="px-6 py-4">{{ $stock->productVariant?->label }}</td>
        <td class="px-6 py-4">{{ $stock->productVariant?->sku }}</td>
        <td class="px-6 py-4">{{ $stock->quantity }}</td>
        <td class="px-6 py-4">
          <button type="button" class="text-sky-600 hover:text-sky-800 text-sm font-medium transition-colors" onclick="openStockModal({{ $stock->warehouse_id }}, {{ $stock->product_variant_id }}, {{ $stock->quantity }})">Set Stok</button>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">{{ $stocks->links() }}</div>
</div>

<div id="stockModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-sm w-full">
    <h4 class="font-semibold mb-4">Set Stok</h4>
    <form action="{{ route('admin.stocks.store') }}" method="POST">
      @csrf
      <input type="hidden" name="warehouse_id" id="modal_warehouse_id">
      <input type="hidden" name="product_variant_id" id="modal_variant_id">
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Quantity</label>
        <input type="number" name="quantity" id="modal_quantity" min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 outline-none" required>
      </div>
      <div class="flex gap-2">
        <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-teal-600 text-white text-sm font-medium rounded-lg hover:shadow-md hover:shadow-sky-100 transition-all">Simpan</button>
        <button type="button" class="text-gray-600 hover:text-gray-800 px-4 py-2.5 text-sm font-medium transition-colors" onclick="closeStockModal()">Batal</button>
      </div>
    </form>
  </div>
</div>

<script>
function openStockModal(warehouseId, variantId, qty) {
  document.getElementById('modal_warehouse_id').value = warehouseId;
  document.getElementById('modal_variant_id').value = variantId;
  document.getElementById('modal_quantity').value = qty;
  document.getElementById('stockModal').classList.remove('hidden');
  document.getElementById('stockModal').classList.add('flex');
}
function closeStockModal() {
  document.getElementById('stockModal').classList.add('hidden');
  document.getElementById('stockModal').classList.remove('flex');
}
</script>
@endsection
