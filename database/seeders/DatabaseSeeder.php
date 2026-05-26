<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AuthorizationTypeSeeder::class,
            ModuleGroupSeeder::class,
            ModuleSeeder::class,
            RoleSeeder::class,
            SuperadminSeeder::class,
            DemoSeeder::class,
        ]);
    }
}
