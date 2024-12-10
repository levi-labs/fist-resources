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
        Schema::create('goods_received', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shipment_id')->unsigned();
            $table->bigInteger('restock_purchase_order_id')->unsigned()->nullable();
            $table->bigInteger('proposed_product_purchase_order_id')->unsigned()->nullable();
            $table->integer('quantity_received');
            $table->bigInteger('received_by')->unsigned();
            $table->date('received_date');
            $table->enum('condition', [
                'good',
                'damaged',
                'lost',
            ]);
            $table->enum('request_type', [
                'restock',
                'proposed',
            ]);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');
            $table->foreign('restock_purchase_order_id')->references('id')->on('restock_purchase_orders')->onDelete('cascade');
            $table->foreign('proposed_product_purchase_order_id')->references('id')->on('proposed_product_purchase_orders')->onDelete('cascade');
            $table->foreign('received_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_received');
    }
};
