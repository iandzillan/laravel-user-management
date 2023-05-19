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
        Schema::create('sub_menu_package', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_menu_id');
            $table->unsignedBigInteger('package_id');
            $table->timestamps();

            $table->foreign('sub_menu_id')->references('id')->on('sub_menus')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_package');
    }
};
