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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->char('code', 8)->unique();
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->string('location');
            $table->unsignedBigInteger('uom_id');
            $table->integer('stock');
            $table->integer('safety_stock');
            $table->string('desc');
            $table->string('status')->nullable();
            $table->string('qrcode')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('uom_id')->references('id')->on('uoms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
