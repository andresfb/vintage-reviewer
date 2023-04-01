<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Movie extends Model implements HasMedia
{
    use SoftDeletes, Sluggable, HasTags, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'overview',
        'release_date',
        'tmdb_id',
        'imdb_id',
        'tag_line',
        'description',
        'story_line',
        'synopsis',
        'language',
        'popularity',
        'rating',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected function popularity(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => $value / 100,
            set: static fn ($value) => $value * 100,
        );
    }

    protected function rating(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => $value / 100,
            set: static fn ($value) => $value * 100,
        );
    }

    public function themes(): HasMany
    {
        return $this->hasMany(MovieTheme::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('poster')
            ->singleFile()
            ->withResponsiveImages()
            ->useDisk('s3');

        $this->addMediaCollection('trailer')
            ->singleFile()
            ->useDisk('s3');
    }
}
