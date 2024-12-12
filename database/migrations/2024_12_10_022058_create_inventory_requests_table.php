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
        Schema::create('inventory_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('staff_id')->unsigned();
            $table->bigInteger('procurement_id')->unsigned()->nullable();
            $table->bigInteger('product_id')->unsigned();
            $table->integer('quantity');
            $table->date('date_requested');
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'resubmitted'
            ])->default('pending');

            $table->integer('resubmit_count')->default(0);
            $table->text('notes')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('procurement_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_request');
    }
};
