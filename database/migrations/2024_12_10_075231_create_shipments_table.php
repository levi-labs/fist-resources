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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('restock_purchase_order_id')->unsigned()->nullable();
            $table->bigInteger('proposed_product_purchase_order_id')->unsigned()->nullable();
            $table->string('courier', 60);
            $table->string('tracking_number', 100)->nullable();
            $table->date('shipment_date');
            $table->enum('status', [
                'awaiting shipment',
                'shipped',
                'delivered',
                'failed'
            ]);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('restock_purchase_order_id')->references('id')->on('restock_purchase_orders')->onDelete('cascade');
            $table->foreign('proposed_product_purchase_order_id')->references('id')->on('proposed_product_purchase_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
