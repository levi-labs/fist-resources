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
        Schema::create('proposed_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->unsigned();
            $table->string('name', 40);
            $table->string('brand', 40);
            $table->string('model', 40);
            $table->string('size', 20)->nullable();
            $table->string('unit_type', 20)->nullable();
            $table->string('sku', 40)->unique()->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image');
            $table->text('description');
            $table->enum('status', ['unregistered', 'registered'])->default('unregistered');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_products');
    }
};
