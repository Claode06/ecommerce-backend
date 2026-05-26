<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('warehouse_id')->constrained('warehouses');
            $table->string('order_number', 45);
            $table->unsignedInteger('status')->comment('1 = pending payment, 2 = paid, 3 = processing, 4 = shipped, 5 = delivered, 6 = cancelled');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('shipping_cost', 15, 2);
            $table->unsignedInteger('point_redeemed')->default(0);
            $table->unsignedInteger('point_earned')->default(0);
            $table->decimal('total', 15, 2);
            $table->string('buyer_name', 100);
            $table->string('buyer_email', 100);
            $table->string('buyer_phone', 20);
            $table->text('shipping_address');
            $table->text('shipping_note')->nullable();
            $table->text('note')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
