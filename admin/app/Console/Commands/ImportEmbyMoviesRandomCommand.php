<?php

namespace App\Console\Commands;

use App\Services\EmbyService;
use App\Services\MovieService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportEmbyMoviesRandomCommand extends Command
{
    protected $signature = 'import:movie-random {count?}';

    protected $description = 'Import a given set of movies at random from the Emby server';

    public function __construct(
        private readonly EmbyService $embyService,
        private readonly MovieService $movieService,
    ){
        parent::__construct();
    }

    public function handle(): int
    {
        try {
            $this->line('');

            $count = $this->argument('count');
            if (empty($count)) {
                $count = config('emby.default_import_count');
            }

            $this->info("Importing $count Movies for all collection");
            if (!$this->confirm('Do you wish to continue?', true)) {
                return 0;
            }

            $availableMovies = $this->movieService->getAllEmbyIds();
            $embyMovies = $this->loadEmbyMovies();

            $selected = [];
            foreach ($embyMovies as $embyMovie) {
                if (in_array($embyMovie->Id, $availableMovies, true)) {
                    continue;
                }

                $selected[] = $embyMovie;
                if (count($selected) >= $count) {
                    break;
                }
            }

            foreach ($selected as $item) {
                $this->embyService->importMovie($item);
            }

            return 0;
        } catch (\Exception $e) {
            $this->line('');
            $this->error($e->getMessage());
            $this->line('');
            Log::error($e->getMessage());

            return 1;
        }
    }

    private function loadEmbyMovies(): array
    {
        $collections = collect(config('emby.usable_collection_ids'));
        if ($collections->isEmpty()) {
            throw new \RuntimeException('No usable collections found');
        }

        $movies = [];
        $items = $collections->shuffle()->toArray();
        foreach ($items as $collection) {
            $movies[] = $this->embyService->getMovies($collection);
        }

        return collect($movies)->flatten()->shuffle()->toArray();
    }
}
