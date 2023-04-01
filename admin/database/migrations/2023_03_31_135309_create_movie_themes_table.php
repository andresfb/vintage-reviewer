<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movie_themes', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')
                ->references('id')
                ->on('movies')
                ->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_themes');
    }
};
