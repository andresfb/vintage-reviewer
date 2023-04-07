<?php

namespace App\Libraries;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class MediaPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media).'/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media).'/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media).'/responsive-images/';
    }

    private function getBasePath(Media $media): string
    {
        $contentId = str_pad((string) $media->model_id, 12, "0", STR_PAD_LEFT);
        return Str::of(
            collect(str_split($contentId, 3))
                ->reverse()
                ->implode("/")
        )
        ->append("/")
        ->append($media->collection_name)
        ->append("/")
        ->append((string) $media->id)
        ->toString();
    }
}
