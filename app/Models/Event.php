<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'venue',
        'city',
        'country_code',
        'genre',
        'start_date',
        'end_date',
        'date_display',
        'timezone',
        'status',
        'is_sold_out',
        'image_url',
        'accent_color',
        'glow_color',
        'vendor_url',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_image',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_sold_out' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        if ($query->getConnection()->getDriverName() === 'mysql') {
            return $query->whereFullText(['title', 'subtitle', 'venue', 'city', 'genre'], $term);
        }

        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

        return $query->where(function (Builder $query) use ($like): void {
            $query
                ->where('title', 'like', $like)
                ->orWhere('subtitle', 'like', $like)
                ->orWhere('venue', 'like', $like)
                ->orWhere('city', 'like', $like)
                ->orWhere('genre', 'like', $like)
                ->orWhere('status', 'like', $like);
        });
    }

    public function scopeStartingFrom(Builder $query, ?string $date): Builder
    {
        if (blank($date)) {
            return $query;
        }

        return $query->where('start_date', '>=', $date);
    }

    public function scopeStartingUntil(Builder $query, ?string $date): Builder
    {
        if (blank($date)) {
            return $query;
        }

        return $query->where('start_date', '<=', $date);
    }

    public function getAdminStatusLabelAttribute(): string
    {
        if ($this->is_sold_out) {
            return 'Sold Out';
        }

        return Str::of($this->status)
            ->replace('_', ' ')
            ->title()
            ->toString();
    }

    public function getAdminDateLabelAttribute(): string
    {
        if (filled($this->date_display)) {
            return $this->date_display;
        }

        if ($this->end_date && ! $this->start_date->isSameDay($this->end_date)) {
            return $this->start_date->format('M d') . '-' . $this->end_date->format('d, Y');
        }

        return $this->start_date->format('M d, Y');
    }
}
