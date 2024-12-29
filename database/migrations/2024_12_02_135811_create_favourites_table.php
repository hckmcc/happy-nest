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
        Schema::create('favourites', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ad_id');;
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('ad_id')->references('id')->on('ads');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favourites', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Удаление внешнего ключа при откате
            $table->dropForeign(['ad_id']);
        });
        Schema::dropIfExists('favourites');
    }
};
