<?php

namespace App\Services;

use App\Jobs\CheckDownloadTrailerJob;
use App\Models\Movie;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DownloadTrailerService
{
    /**
     * @throws Exception
     */
    public function download(int $movieId, array $trailerLinks): void
    {
        if (!config('youtube.enable_download')) {
            return;
        }

        $binFile = config('youtube.download_binary');
        if (!file_exists($binFile)) {
            throw new \RuntimeException("Download binary not found: $binFile");
        }

        $downloadPath = Storage::disk('downloads')->path(uuid_create(UUID_TYPE_RANDOM));
        if (!file_exists($downloadPath) && !mkdir($downloadPath, 0775, true) && !is_dir($downloadPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $downloadPath));
        }

        $found = false;
        foreach ($trailerLinks as $trailerLink) {
            $result = Http::accept('application/json')
                ->baseUrl(config('youtube.embeddable_url'))
                ->get('?'.http_build_query(['url' => $trailerLink]));

            if ($result->status() !== 200) {
                Log::error("The trailer is no longer available: $trailerLink");

                continue;
            }

            $options = config('youtube.download_options');
            $cmd = "cd $downloadPath && $binFile $options $trailerLink > /dev/null &";

            shell_exec($cmd);
            $found = true;
            break;
        }

        if (!$found) {
            return;
        }

        CheckDownloadTrailerJob::dispatch($movieId, $downloadPath)
            ->onQueue('media')
            ->delay(now()->addMinutes(5));
    }

    public function checkDownload(string $downloadFolder): array
    {
        $files = glob($downloadFolder.'/*.mp4');
        if (count($files) === 0) {
            return [];
        }

        return [true, $files[0]];
    }

    /**
     * @throws Exception
     */
    public function saveTrailer(int $movieId, string $trailerFile): void
    {
        $movie = Movie::findOrFail($movieId);

        $movie->addMedia($trailerFile)
            ->toMediaCollection('trailer');
    }
}
