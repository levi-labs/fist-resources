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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->enum('user_role', [
                'staff',
                'procurement',
                'logistic',
                'supplier'
            ])->nullable();
            $table->enum('notification_type', [
                'request',
                'purchase',
                'shipment'
            ]);
            $table->text('message');
            $table->enum('status', [
                'unread',
                'read'
            ])->default('unread');
            $table->enum('order_type', [
                'request',
                'restock',
                'proposed product',
                'purchase restock',
                'purchase proposed product',
                'shipment'
            ])->nullable();

            $table->unsignedBigInteger('related_order_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
