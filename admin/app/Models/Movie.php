<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Movie extends Model implements HasMedia
{
    use SoftDeletes, Sluggable, HasTags;
    use InteractsWithMedia, CascadeSoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'is_complete' => 'boolean',
        'runtime' => 'integer',
        'release_date' => 'date',
    ];

    protected array $cascadeDeletes = [
        'media',
        'themes',
    ];

    protected static function booted(): void
    {
        static::saved(static function (Movie $movie) {
            $movie->is_complete = !$movie->hasMissingInfo();
        });
    }

    protected function rating(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => $value / 100,
            set: static fn ($value) => $value * 100,
        );
    }

    public function year(): string
    {
        return $this->release_date->format('Y');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function themes(): HasMany
    {
        return $this->hasMany(MovieTheme::class);
    }

    public function post(): HasOne
    {
        return $this->hasOne(Post::class);
    }

    public function registerMediaCollections(): void
    {
        $mediaDisk = config('media-library.disk_name');

        $this->addMediaCollection('poster')
            ->singleFile()
            ->useDisk($mediaDisk);

        $this->addMediaCollection('backdrop')
            ->singleFile()
            ->useDisk($mediaDisk);

        $this->addMediaCollection('trailer')
            ->singleFile()
            ->useDisk($mediaDisk);
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

    public static function markUnused(int $movieId): void
    {
        $movie = self::findOrFail($movieId);
        $movie->update(['used' => false]);
    }
}
