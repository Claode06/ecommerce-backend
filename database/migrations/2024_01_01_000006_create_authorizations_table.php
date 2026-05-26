<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authorizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles');
            $table->foreignId('module_id')->constrained('modules');
            $table->foreignId('authorization_type_id')->constrained('authorization_types');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['role_id', 'module_id', 'authorization_type_id'], 'auth_role_module_type_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('authorizations');
    }
};
