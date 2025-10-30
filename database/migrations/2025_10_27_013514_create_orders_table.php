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
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('sellers')->onDelete('cascade');
            $table->foreignId('rider_id')->nullable()->constrained('riders')->onDelete('set null');
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('points_discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->decimal('total_weight_kg', 8, 2);
            $table->enum('status', [
                'ordered',
                'preparing',
                'ready_for_pickup',
                'picked_up',
                'out_for_delivery',
                'delivered',
                'cancelled'
            ])->default('ordered');
            $table->string('delivery_address');
            $table->string('eta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
