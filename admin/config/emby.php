<?php

return [

    'api' => [

        'url' => env('EMBY_API_URL'),

        'collection_url_strings' => [
            'Recursive' => 'true',
            'IncludeItemTypes' => 'Movie',
            'ExcludeItemTypes' => 'Episode',
            'fields' => implode(',', [
                'Overview',
                'Genres',
                'ProductionYear',
                'Taglines',
                'Rating',
                'RunTimeTicks',
                'ImageTags',
                'ProviderIds',
                'PremiereDate',
                'OfficialRating',
                'RemoteTrailers',
                'CriticRating',
                'CommunityRating',
            ]),
        ],

    ],

    'movie_url' => env('EMBY_SERVER_MOVIE_PAGE'),

    'user_id' => env('EMBY_USER_ID'),

    'usable_collection_ids' => explode(',', env('EMBY_USABLE_COLLECTION_IDS')),

    'default_collection_id' => env('EMBY_DEFAULT_COLLECTION_ID'),

    'default_import_count' => env('EMBY_DEFAULT_IMPORT_COUNT'),

];
