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
        Schema::create('ad_promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('promotion_id');
            $table->unsignedBigInteger('ad_id');
            $table->timestamps();

            $table->foreign('promotion_id')->references('id')->on('promotion_types')->onDelete('cascade');
            $table->foreign('ad_id')->references('id')->on('ads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_promotions', function (Blueprint $table) {
            $table->dropForeign(['promotion_id']);
            $table->dropForeign(['ad_id']);
        });
        Schema::dropIfExists('ad_promotions');
    }
};
