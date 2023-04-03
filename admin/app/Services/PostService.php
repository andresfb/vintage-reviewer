<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Post;
use Exception;

class PostService
{
    /**
     * @throws Exception;
     */
    public function createFromMovie(Movie $movie): Post
    {
        $post = Post::updateOrCreate([
            'movie_id' => $movie->id,
        ], [
            'title' => "My Review of $movie->title ({$movie->year()})",
            'tag_line' => $movie->tag_line,
            'active' => false,
            'published_at' => now()->addMonth(),
        ]);

        $tags = $movie->tags->pluck('name')->toArray();
        $post->syncTags($tags);

        foreach ($movie->themes as $theme) {
            $post->syncTagsWithType($theme->name, 'themes');
        }

        $media = $movie->getFirstMedia('backdrop');
        if ($media === null) {
            return $post;
        }

        $post->addMediaFromUrl($media->getUrl())
            ->toMediaCollection('image');

        return $post;
    }
}
