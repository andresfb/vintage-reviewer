<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->text('overview');
            $table->string('tmdb_id', 50)->nullable();
            $table->string('imdb_id', 50)->nullable();
            $table->string('emby_id', 50)->nullable();
            $table->text('tag_line')->nullable();
            $table->text('description')->nullable();
            $table->text('story_line')->nullable();
            $table->text('synopsis')->nullable();
            $table->string('rated', 20)->nullable();
            $table->string('language', 4)->nullable();
            $table->integer('rating')->nullable();
            $table->integer('runtime')->nullable();
            $table->string('trailer_link')->nullable();
            $table->boolean('is_complete')->default(false);
            $table->date('release_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
