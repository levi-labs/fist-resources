<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restock_purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('restock_purchase_order_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
            $table->foreign('restock_purchase_order_id')->references('id')->on('restock_purchase_orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restock_purchase_order_details');
    }
};
