<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\ModuleGroup;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $groups = ModuleGroup::all()->keyBy('name');

        $modules = [
            // Manajemen Pengguna
            ['module_group_id' => $groups['Manajemen Pengguna']->id, 'name' => 'Manajemen Role', 'route' => 'admin.roles.index', 'order' => 1, 'is_shown' => true],
            ['module_group_id' => $groups['Manajemen Pengguna']->id, 'name' => 'Manajemen Admin', 'route' => 'admin.admins.index', 'order' => 2, 'is_shown' => true],
            ['module_group_id' => $groups['Manajemen Pengguna']->id, 'name' => 'Manajemen User', 'route' => 'admin.users.index', 'order' => 3, 'is_shown' => true],

            // Manajemen Produk
            ['module_group_id' => $groups['Manajemen Produk']->id, 'name' => 'Manajemen Kategori', 'route' => 'admin.categories.index', 'order' => 1, 'is_shown' => true],
            ['module_group_id' => $groups['Manajemen Produk']->id, 'name' => 'Manajemen Merek', 'route' => 'admin.brands.index', 'order' => 2, 'is_shown' => true],
            ['module_group_id' => $groups['Manajemen Produk']->id, 'name' => 'Manajemen Produk', 'route' => 'admin.products.index', 'order' => 3, 'is_shown' => true],

            // Inventori & Gudang
            ['module_group_id' => $groups['Inventori & Gudang']->id, 'name' => 'Manajemen Gudang', 'route' => 'admin.warehouses.index', 'order' => 1, 'is_shown' => true],
            ['module_group_id' => $groups['Inventori & Gudang']->id, 'name' => 'Manajemen Stok', 'route' => 'admin.stocks.index', 'order' => 2, 'is_shown' => true],

            // Promosi
            ['module_group_id' => $groups['Promosi']->id, 'name' => 'Manajemen Promosi', 'route' => 'admin.promotions.index', 'order' => 1, 'is_shown' => true],

            // Transaksi
            ['module_group_id' => $groups['Transaksi']->id, 'name' => 'Akun Pembayaran', 'route' => 'admin.payment-accounts.index', 'order' => 1, 'is_shown' => true],
            ['module_group_id' => $groups['Transaksi']->id, 'name' => 'Manajemen Pesanan', 'route' => 'admin.orders.index', 'order' => 2, 'is_shown' => true],
            ['module_group_id' => $groups['Transaksi']->id, 'name' => 'Konfirmasi Pembayaran', 'route' => 'admin.payments.index', 'order' => 3, 'is_shown' => true],
            ['module_group_id' => $groups['Transaksi']->id, 'name' => 'Pengiriman & Tracking', 'route' => 'admin.shipments.index', 'order' => 4, 'is_shown' => true],

            // Lainnya
            ['module_group_id' => $groups['Lainnya']->id, 'name' => 'Poin Reward', 'route' => 'admin.points.index', 'order' => 1, 'is_shown' => true],
            ['module_group_id' => $groups['Lainnya']->id, 'name' => 'Ulasan Produk', 'route' => 'admin.reviews.index', 'order' => 2, 'is_shown' => true],
            ['module_group_id' => $groups['Lainnya']->id, 'name' => 'File Storage', 'route' => 'admin.file-storages.index', 'order' => 3, 'is_shown' => true],
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}
