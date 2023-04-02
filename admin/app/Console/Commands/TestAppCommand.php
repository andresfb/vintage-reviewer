<?php

namespace App\Console\Commands;

use App\Services\EmbyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestAppCommand extends Command
{
    protected $signature = 'test:app';

    protected $description = 'Run simple tests';

    public function handle(): int
    {
        try {
            $this->line('');
            $this->info('Starting tests');

            $service = resolve(EmbyService::class);
            $service->importMovies();

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
