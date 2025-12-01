<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add payment fields after delivery_address
            $table->enum('payment_method', ['card', 'gcash', 'cod'])
                  ->default('cod')
                  ->after('delivery_address');

            $table->enum('payment_status', ['pending', 'paid', 'failed'])
                  ->default('pending')
                  ->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status']);
        });
    }
};
