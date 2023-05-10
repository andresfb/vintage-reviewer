<?php

namespace App\Services;

use App\Jobs\DownloadTrailerJob;
use App\Libraries\EmbyApiLibrary;
use App\Models\Movie;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class EmbyService
{
    public bool $screenOutput = true;

    private int $importedMovies = 0;

    private int $totalMovies = 0;

    public function __construct(private readonly EmbyApiLibrary $library)
    {
    }

    public function getMovies(string $collectionId): Collection
    {
        $movies = $this->library->getCollectionItems($collectionId);
        if ($movies === null || empty($movies->Items)) {
            Log::error('@EmbyService.getMovies: No movies found in collection');

            return collect();
        }

        return collect($movies->Items);
    }

    public function importMovies(string $collectionId): void
    {
        $movies = $this->library->getCollectionItems($collectionId);
        if ($movies === null || empty($movies->Items)) {
            Log::error('@EmbyService.importMovies: No movies found in collection');

            return;
        }

        $this->totalMovies = $movies->TotalRecordCount;
        $this->outputLine("\nImporting $this->totalMovies movies\n");

        foreach ($movies->Items as $movie) {
            $this->importMovie($movie);
        }
    }

    public function importMovie(object $movie): void
    {
        $this->outputLine("Importing `$movie->Name`");

        [$tmdbId, $imdbId] = $this->getProviderIds($movie);

        $record = Movie::updateOrCreate([
            'emby_id' => $movie->Id,
        ], [
            'title' => $movie->Name,
            'overview' => $movie->Overview,
            'runtime' => ceil(($movie->RunTimeTicks / 10000000)),
            'tag_line' => $this->getTagLine($movie),
            'rated' => $movie->OfficialRating ?? null,
            'release_date' => $this->getReleaseDate($movie),
            'tmdb_id' => $tmdbId,
            'imdb_id' => $imdbId,
            'rating' => $this->getRating($movie),
            'trailer_link' => $movie->RemoteTrailers[0]->Url ?? null,
        ]);

        $this->saveTags($movie, $record);
        $this->savePoster($movie, $record);
        $this->saveBackdrop($movie, $record);
        $this->saveTrailer($movie, $record);

        $this->importedMovies++;

        $this->outputLine("Imported `$movie->Name` - {$this->importedMovies} of {$this->totalMovies}\n");
    }

    private function getTagLine(object $movie): ?string
    {
        if (empty($movie->Taglines)) {
            return null;
        }

        return $movie->Taglines[0];
    }

    private function getProviderIds(object $movie): array
    {
        $tmdbId = null;
        $imdbId = null;

        if (!empty($movie->ProviderIds)) {
            $tmdbId = $movie->ProviderIds->Tmdb ?? null;
            $imdbId = $movie->ProviderIds->Imdb ?? null;
        }

        return [$tmdbId, $imdbId];
    }

    private function getRating(object $movie): float|int|null
    {
        $ratings = collect();

        if (!empty($movie->CommunityRating)) {
            $ratings->add($movie->CommunityRating);
        }

        if (!empty($movie->CriticRating)) {
            $ratings->add(($movie->CriticRating / 10));
        }

        return $ratings->avg();
    }

    private function getTags(object $movie): array
    {
        if (!empty($movie->Genres)) {
            return $movie->Genres;
        }

        if (empty($movie->GenreItems)) {
            return [];
        }

        $genres = [];
        foreach ($movie->GenreItems as $genreItem) {
            $genres[] = $genreItem->Name;
        }

        return $genres;
    }

    private function saveTags(object $movie, Movie $record): void
    {
        $tags = $this->getTags($movie);
        if (!empty($tags)) {
            $record->syncTags($tags);
        }
    }

    private function savePoster(object $movie, Movie $record): void
    {
        $this->outputLine('Loading poster... ');
        if (empty($movie->ImageTags->Primary)) {
            $this->outputLine('No poster found');

            return;
        }

        $url = sprintf(
            config('emby.api.url'),
            "Items/{$movie->Id}/Images/Primary",
            http_build_query([
                'tag' => $movie->ImageTags->Primary,
            ]),
        );

        try {
            $record->addMediaFromUrl($url)
                ->toMediaCollection('poster');
        } catch (Exception $e) {
            Log::error('@EmbyService.savePoster: '.$e->getMessage());
            $this->outputLine("Error loading poster for $movie->Name {$e->getMessage()}");

            return;
        }
    }

    private function saveBackdrop(object $movie, Movie $record): void
    {
        $this->outputLine('Loading backdrop... ');
        if (empty($movie->BackdropImageTags)) {
            $this->outputLine('No backdrop found');

            return;
        }

        $url = sprintf(
            config('emby.api.url'),
            "Items/{$movie->Id}/Images/Backdrop",
            http_build_query([
                'tag' => $movie->BackdropImageTags[0],
            ]),
        );

        try {
            $record->addMediaFromUrl($url)
                ->toMediaCollection('backdrop');
        } catch (Exception $e) {
            Log::error('@EmbyService.saveBackdrop: '.$e->getMessage());
            $this->outputLine("Error loading backdrop for $movie->Name {$e->getMessage()}");

            return;
        }
    }

    private function saveTrailer(object $movie, Movie $record): void
    {
        if (!config('youtube.enable_download')) {
            return;
        }

        $this->outputLine('Loading trailer... ');
        if (empty($movie->RemoteTrailers)) {
            $this->outputLine('No trailer found');

            return;
        }

        DownloadTrailerJob::dispatch($record->id, $movie->RemoteTrailers)
            ->onQueue('media')
            ->delay(now()->addSeconds(5));
    }

    private function getReleaseDate(object $movie): string
    {
        if (!empty($movie->PremiereDate)) {
            return $movie->PremiereDate;
        }

        if (!empty($movie->ProductionYear)) {
            return "$movie->ProductionYear-01-01";
        }

        return '1970-04-01';
    }

    private function outputLine(string $message): void
    {
        if (!$this->screenOutput) {
            return;
        }

        echo $message.PHP_EOL;
    }
}
