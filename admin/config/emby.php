<?php

return [

    'api' => [

        'url' => env('EMBY_API_URL'),

        'collection_url_strings' => [
            'Recursive' => 'true',
            'IncludeItemTypes' => 'Movie',
            'ExcludeItemTypes' => 'Episode',
            'ParentId' => env('EMBY_COLLECTION_ID'),
            "fields" => implode(',', [
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

];
