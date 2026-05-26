<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('name', 'Superadmin')->first();

        Admin::create([
            'role_id' => $role->id,
            'name' => 'Super Admin',
            'email' => 'claode@pmb.com',
            'password' => Hash::make('password123'),
            'status' => 1,
        ]);
    }
}
