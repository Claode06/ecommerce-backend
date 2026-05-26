<?php

namespace Database\Seeders;

use App\Models\ModuleGroup;
use Illuminate\Database\Seeder;

class ModuleGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['name' => 'Manajemen Pengguna'],
            ['name' => 'Manajemen Produk'],
            ['name' => 'Inventori & Gudang'],
            ['name' => 'Promosi'],
            ['name' => 'Transaksi'],
            ['name' => 'Lainnya'],
        ];

        foreach ($groups as $group) {
            ModuleGroup::create($group);
        }
    }
}
