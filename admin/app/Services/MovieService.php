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
}
