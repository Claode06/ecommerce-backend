<?php

namespace Database\Seeders;

use App\Models\AuthorizationType;
use Illuminate\Database\Seeder;

class AuthorizationTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['create', 'read', 'update', 'delete'];

        foreach ($types as $type) {
            AuthorizationType::create(['name' => $type]);
        }
    }
}
