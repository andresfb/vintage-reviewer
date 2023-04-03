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

class CheckDownloadTrailerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private DownloadTrailerService $service;

    public function __construct(private readonly int $movieId, private readonly string $downloadFolder)
    {
        $this->service = resolve(DownloadTrailerService::class);
    }

    public function handle(): void
    {
        try {
            [$found, $trailer] = $this->service->checkDownload($this->downloadFolder);
            if (!$found) {
                self::dispatch($this->movieId, $this->downloadFolder)
                    ->onQueue('media')
                    ->delay(now()->addMinutes(5));
            }

            $this->service->saveTrailer($this->movieId, $trailer);
        } catch (Exception $e) {
            Log::error('@DownloadTrailerJob.handle: ' . $e->getMessage());
        }
    }
}
