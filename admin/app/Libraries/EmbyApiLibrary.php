<?php

namespace App\Libraries;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmbyApiLibrary
{
    protected array $endPoints = [];

    public function __construct()
    {
        $baseUrl = config('emby.api.url');

        $this->endPoints = [
            'get_collection_items' => sprintf(
                $baseUrl,
                'Users/'. config('emby.user_id'). '/Items',
                http_build_query(config('emby.api.collection_url_strings')),
            ),
        ];
    }

    public function getCollectionItems(string $collectionId): ?object
    {
        try {
            $response = $this->getResponse(
                $this->endPoints['get_collection_items'] . "&ParentId=$collectionId"
            );
        } catch (Exception $e) {
            Log::error('@EmbyApiService.getCollectionItems: ' . $e->getMessage());
            return null;
        }

        return $response;
    }

    /**
     * @throws Exception
     */
    private function getResponse(string $url): object
    {
        return Http::accept('application/json')
            ->get($url)
            ->throw()
            ->object();
    }
}
