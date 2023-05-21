<?php

namespace App\ViewModels;

use App\Models\Movie;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class MoviesViewModel extends ViewModel
{
    private Collection $movies;

    public function __construct(public readonly ?LengthAwarePaginator $movieList)
    {
    }

    public function movies(): Collection
    {
        if (!empty($this->movies) && !$this->movies->isEmpty()) {
            return $this->movies;
        }

        $this->movies = $this->movieList->map(function (Movie $movie) {
            return [
                'id' => $movie->id,
                'title' => $movie->title,
                'slug' => $movie->slug,
                'tmdb_id' => $movie->tmdb_id,
                'imdb_id' => $movie->imdb_id,
                'emby_id' => $movie->emby_id,
                'rated' => $movie->rated,
                'rating' => $movie->rating,
                'runtime' => gmdate('H:i', $movie->runtime),
                'is_complete' => $movie->is_complete,
                'used' => $movie->used,
                'release_date' => $movie->release_date->format('m-Y'),
                'image' => $this->getMedia($movie),
            ];
        });

        return $this->movies;
    }

    private function getMedia(Movie $movie): string
    {
        $image = $movie->getMedia('poster')->first();
        if (empty($image)) {
            return '';
        }

        return $image->getUrl();
    }
}
