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
        Schema::create('menu_modul', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('modul_id');
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('modul_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_modul');
    }
};
