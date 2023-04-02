<?php

namespace App\Services;

use App\Libraries\EmbyApiLibrary;
use Illuminate\Support\Facades\Log;

class EmbyService
{
    public function __construct(private readonly EmbyApiLibrary $library)
    {
    }

    public function importMovies(): void
    {
        $movies = $this->library->getCollectionItems();
        if (empty($movies)) {
            Log::error('@EmbyService.importMovies: No movies found in collection');
            return;
        }

        foreach ($movies as $movie) {
            $this->importMovie($movie);
        }
    }

    private function importMovie($movie): void
    {
        // TODO: create the movie
        dd($movie);
    }
}
