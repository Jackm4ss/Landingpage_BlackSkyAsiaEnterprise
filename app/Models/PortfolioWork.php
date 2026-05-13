<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'year',
        'location',
        'role',
        'attendance',
        'excerpt',
        'description',
        'featured_image',
        'gallery_images',
        'accent_color',
        'status',
        'sort_order',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_image',
        'created_by',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'published_at' => 'datetime',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        if ($query->getConnection()->getDriverName() === 'mysql') {
            return $query->whereFullText(['title', 'excerpt', 'description', 'location', 'category'], $term);
        }

        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

        return $query->where(function (Builder $query) use ($like): void {
            $query
                ->where('title', 'like', $like)
                ->orWhere('excerpt', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhere('location', 'like', $like)
                ->orWhere('category', 'like', $like);
        });
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return self::publicAssetUrl($this->featured_image);
    }

    /**
     * @return array<int, string>
     */
    public function getGalleryImageUrlsAttribute(): array
    {
        return collect($this->gallery_images ?? [])
            ->map(fn (?string $path): ?string => self::publicAssetUrl($path))
            ->filter()
            ->values()
            ->all();
    }

    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: Str::limit($this->title . ' | Black Sky Portfolio', 60, '');
    }

    public function getSeoDescriptionAttribute(): string
    {
        return $this->meta_description ?: Str::limit((string) ($this->excerpt ?: strip_tags((string) $this->description)), 160, '');
    }

    public static function publicAssetUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $path = (string) $path;

        if (Str::startsWith($path, ['http://', 'https://', 'data:', '/'])) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
