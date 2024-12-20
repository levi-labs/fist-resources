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
        Schema::create('proposed_inventory_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('staff_id')->unsigned();
            $table->bigInteger('procurement_id')->unsigned()->nullable();
            $table->bigInteger('proposed_product_id')->unsigned();
            $table->string('request_code', 20);
            $table->integer('quantity');
            $table->date('date_requested');
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'resubmitted'
            ]);

            $table->integer('resubmit_count')->default(0);
            $table->text('notes')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('procurement_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('proposed_product_id')->references('id')->on('proposed_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_inventory_request');
    }
};
