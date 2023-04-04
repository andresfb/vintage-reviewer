<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Services\MovieService;
use App\Services\PostService;
use Filament\Facades\Filament;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Log;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('Create post')
                ->action(function () {
                    try {
                        $movieService = resolve(MovieService::class);
                        $movie = $movieService->getRandomMovie();
                        if (!$movie) {
                            Filament::notify('danger', 'No movies available');

                            return;
                        }

                        $postService = resolve(PostService::class);
                        $post = $postService->createFromMovie($movie);

                        $movie->used = true;
                        $movie->save();

                        $this->redirect(route('filament.resources.posts.edit', $post->id));
                    } catch (\Exception $e) {
                        Log::error('Error creating post: '.$e->getMessage());
                        Filament::notify('danger', 'Error creating post: '.$e->getMessage());
                    }
                })->requiresConfirmation(),
        ];
    }
}
