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

    // TODO: remove the two rating fields and use a single one

    protected $fillable = [
        'tmdb_id',
        'imdb_id',
        'emby_id',
        'title',
        'slug',
        'overview',
        'release_date',
        'tag_line',
        'description',
        'story_line',
        'synopsis',
        'language',
        'rated',
        'tmdb_rating',
        'imdb_rating',
        'runtime',
        'trailer',
    ];

    protected $casts = [
        'is_complete' => 'boolean',
        'runtime' => 'integer',
        'release_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saved(static function (Movie $movie) {
            $movie->is_complete = !$movie->hasMissingInfo();
        });
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected function tmdbRating(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => $value / 100,
            set: static fn ($value) => $value * 100,
        );
    }

    protected function imdbRating(): Attribute
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

    public function hasMissingInfo(): bool
    {
        return $this->overview === null
            || $this->release_date === null
            || $this->tag_line === null
            || $this->description === null
            || $this->story_line === null
            || $this->synopsis === null
            || $this->language === null
            || $this->rated === null
            || $this->tmdb_rating === null
            || $this->imdb_rating === null
            || $this->runtime === null
            || $this->trailer === null;
    }
}
