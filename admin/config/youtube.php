<?php

return [

    'embeddable_url' => 'https://www.youtube.com/oembed',

    'enable_download' => env('YOUTUBE_ENABLE_DOWNLOAD', false),

    'download_binary' => env('YOUTUBE_DOWNLOAD_BINARY', '/usr/local/bin/yt-dlp'),

    'download_options' => '-f "bestvideo[height<=1080][vcodec!*=vp9]+bestaudio/best[height<=1080]" --merge-output-format mp4',

];
