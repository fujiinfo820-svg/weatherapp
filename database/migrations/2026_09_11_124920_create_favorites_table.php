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
    Schema::create('favorites', function (Blueprint $table) {
        $table->id();
        // ユーザーが削除されたらお気に入りも自動削除（foreignId）
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('city_name');
        $table->timestamps();

        // 同じユーザーが同じ都市を二重登録できないようにユニーク制約
        $table->unique(['user_id', 'city_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
