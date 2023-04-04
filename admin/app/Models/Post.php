<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Post extends Model implements HasMedia
{
    use SoftDeletes, Sluggable, InteractsWithMedia, HasTags;

    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    protected static function booted(): void
    {
        static::deleted(static function (Post $post) {
            Movie::markUnused($post->movie_id);
        });
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->acceptsMimeTypes([
                'image/jpeg', 'image/png',
            ])
            ->singleFile()
            ->withResponsiveImages()
            ->useDisk('s3');

        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes([
                'image/jpeg', 'image/png',
            ])
            ->withResponsiveImages()
            ->useDisk('s3');
    }
}
