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
        Schema::create('restock_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('supplier_id')->unsigned();
            $table->bigInteger('staff_id')->unsigned();
            $table->bigInteger('procurement_id')->unsigned();
            $table->date('order_date');
            $table->date('delivery_date');
            $table->enum('status', [
                'pending',
                'approved',
                'awaiting shipment',
                'shipped',
                'delivered',
                'rejected',
            ])->default('pending');
            $table->decimal('total_price', 10, 2);
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('procurement_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restock_purchase_orders');
    }
};
