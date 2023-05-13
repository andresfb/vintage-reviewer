<?php

namespace App\Services;

use App\Models\Movie;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieService
{
    public function getMovies(int $perPage): LengthAwarePaginator
    {
        return Movie::with('media')
            ->oldest('release_date')
            ->paginate($perPage);
    }

    public function getRandomMovie(): ?Movie
    {
        return Movie::whereUsed(false)
            ->with('tags')
            ->with('themes')
            ->inRandomOrder()
            ->first();
    }

    public function getAllEmbyIds(): array
    {
        return Movie::select('emby_id')
            ->pluck('emby_id')
            ->toArray();
    }
}
