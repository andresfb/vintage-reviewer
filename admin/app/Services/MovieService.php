<?php

namespace App\Services;

use App\Models\Movie;

class MovieService
{
    public function getRandomMovie(): ?Movie
    {
        return Movie::whereUsed(false)
//            ->whereIsComplete(true)
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
