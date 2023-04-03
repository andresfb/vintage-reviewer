<?php

namespace App\Console\Commands;

use App\Services\EmbyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportEmbyMoviesCommand extends Command
{
    protected $signature = 'import:movies {collection?}';

    protected $description = 'Import all the movies in the given collection from the Emby server';

    public function handle(): int
    {
        try {
            $this->line('');

            $collection = $this->argument('collection');
            if (empty($collection)) {
                $collection = config('emby.default_collection_id');
            }

            $this->info("Importing Movies for $collection collection");
            if (!$this->confirm('Do you wish to continue?', true)) {
                return 0;
            }

            $service = resolve(EmbyService::class);
            $service->importMovies($collection);

            $this->line('');
            $this->info("Done.\n");

            return 0;
        } catch (\Exception $e) {
            $this->line('');
            $this->error($e->getMessage());
            $this->line('');
            Log::error($e->getMessage());

            return 1;
        }
    }
}
