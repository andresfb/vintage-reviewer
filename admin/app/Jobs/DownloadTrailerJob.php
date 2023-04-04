<?php

namespace App\Jobs;

use App\Services\DownloadTrailerService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DownloadTrailerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private DownloadTrailerService $service;

    public function __construct(private readonly int $movieId, private readonly array $trailerLinks)
    {
        $this->service = resolve(DownloadTrailerService::class);
    }

    public function handle(): void
    {
        try {
            $this->service->download($this->movieId, $this->trailerLinks);
        } catch (Exception $e) {
            Log::error('@DownloadTrailerJob.handle: '.$e->getMessage());
        }
    }
}
