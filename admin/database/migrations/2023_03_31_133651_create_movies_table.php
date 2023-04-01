<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movies', static function (Blueprint $table) {
            $table->id();
            $table->string('tmdb_id');
            $table->string('imdb_id');
            $table->string('title');
            $table->string('slug');
            $table->text('overview');
            $table->text('tag_line')->nullable();
            $table->text('description')->nullable();
            $table->text('story_line')->nullable();
            $table->text('synopsis')->nullable();
            $table->string('language')->nullable();
            $table->integer('popularity')->nullable();
            $table->integer('rating')->nullable();
            $table->date('release_date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
