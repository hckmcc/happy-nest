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
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');;
            $table->string('name');
            $table->text('description');
            $table->float('price');
            $table->string('address');
            $table->boolean('is_completed')->default(false);
            $table->integer('views')->default(0);
            $table->string('photo')->default('storage/ads/placeholder.jpg');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Удаление внешнего ключа при откате
            $table->dropForeign(['category_id']);
        });
        Schema::dropIfExists('ads');
    }
};
