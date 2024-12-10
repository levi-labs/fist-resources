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
        Schema::create('proposed_product_purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('proposed_order_id')->unsigned();
            $table->bigInteger('proposed_product_id')->unsigned();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->foreign('proposed_order_id', 'pppo_details_order_id_fk')->references('id')->on('proposed_product_purchase_orders')->onDelete('cascade');
            $table->foreign('proposed_product_id', 'pppo_details_product_id_fk')->references('id')->on('proposed_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_product_purchase_order_details');
    }
};
