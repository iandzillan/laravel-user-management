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
        Schema::create('pr_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pr_id');
            $table->unsignedBigInteger('item_id');
            $table->integer('qty');
            $table->timestamps();

            $table->foreign('pr_id')->references('id')->on('purchase_requisitions')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_item');
    }
};
