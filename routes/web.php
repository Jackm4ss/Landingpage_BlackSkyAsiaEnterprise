<?php

use App\Models\BlogPost;
use App\Models\Event;
use App\Models\PortfolioWork;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

Route::redirect('/blog', '/news')->name('blog.index');

Route::get('/blog/{slug}', fn (string $slug) => redirect('/news/' . $slug))
    ->name('blog.show');

Route::get('/news', function () {
    return view('app', [
        'pageMeta' => [
            'title' => 'News | Black Sky Enterprise',
            'description' => 'Read Black Sky Enterprise concert news, announcements, artist stories, venue intelligence, and live entertainment insights across Southeast Asia.',
            'canonical' => url('/news'),
            'type' => 'website',
        ],
        'structuredData' => [
            [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => 'Black Sky News',
                'description' => 'Concert news, announcements, artist stories, and live entertainment insights from Black Sky Enterprise.',
                'url' => url('/news'),
            ],
        ],
    ]);
})->name('news.index');

Route::get('/news/{slug}', function (string $slug) {
    $post = BlogPost::query()
        ->published()
        ->with(['author:id,name,slug', 'category:id,name,slug'])
        ->where('slug', $slug)
        ->firstOrFail();
    $canonicalUrl = $post->canonical_url;

    if (! $canonicalUrl || str_contains($canonicalUrl, '/blog/')) {
        $canonicalUrl = url('/news/' . $post->slug);
    }

    return view('app', [
        'pageMeta' => [
            'title' => $post->seo_title,
            'description' => $post->seo_description,
            'keywords' => $post->meta_keywords,
            'canonical' => $canonicalUrl,
            'image' => $post->og_image_url,
            'type' => 'article',
        ],
        'structuredData' => [
            [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $post->title,
                'description' => $post->seo_description,
                'image' => $post->og_image_url,
                'datePublished' => $post->published_at?->toIso8601String(),
                'dateModified' => $post->updated_at?->toIso8601String(),
                'articleSection' => $post->category?->name,
                'author' => [
                    '@type' => 'Person',
                    'name' => $post->author?->name,
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'Black Sky Enterprise',
                    'url' => url('/'),
                ],
                'mainEntityOfPage' => url('/news/' . $post->slug),
            ],
        ],
    ]);
})->name('news.show');

Route::get('/portfolio/{slug}', function (string $slug) {
    $work = PortfolioWork::query()
        ->published()
        ->where('slug', $slug)
        ->firstOrFail();

    $canonicalUrl = $work->canonical_url ?: url('/portfolio/' . $work->slug);
    $image = $work->og_image ? PortfolioWork::publicAssetUrl($work->og_image) : $work->featured_image_url;

    return view('app', [
        'pageMeta' => [
            'title' => $work->seo_title,
            'description' => $work->seo_description,
            'keywords' => $work->meta_keywords,
            'canonical' => $canonicalUrl,
            'image' => $image,
            'type' => 'article',
        ],
        'structuredData' => [
            [
                '@context' => 'https://schema.org',
                '@type' => 'CreativeWork',
                'name' => $work->title,
                'description' => $work->seo_description,
                'image' => array_values(array_filter([$image])),
                'datePublished' => $work->published_at?->toIso8601String(),
                'locationCreated' => $work->location,
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'Black Sky Enterprise',
                    'url' => url('/'),
                ],
                'mainEntityOfPage' => $canonicalUrl,
            ],
        ],
    ]);
})->name('portfolio.show');

Route::get('/projects/{slug}', fn (string $slug) => redirect('/portfolio/' . $slug))
    ->name('projects.show');

Route::get('/reset-password/{token}', function (string $token) {
    return view('app', [
        'pageMeta' => [
            'title' => 'Reset Password | Black Sky Enterprise',
            'description' => 'Set a new Black Sky Enterprise account password using your secure reset link.',
            'canonical' => url('/reset-password/' . $token),
            'type' => 'website',
        ],
    ]);
})->name('password.reset');

Route::get('/events', function () {
    return view('app', [
        'pageMeta' => [
            'title' => 'Events | Black Sky Enterprise',
            'description' => 'Browse Black Sky Enterprise concerts, festivals, and live entertainment events across Malaysia, Indonesia, and Southeast Asia.',
            'canonical' => url('/events'),
            'type' => 'website',
        ],
        'structuredData' => [
            [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => 'Black Sky Events',
                'description' => 'Published Black Sky Enterprise events and concerts.',
                'url' => url('/events'),
            ],
        ],
    ]);
})->name('events.index');

Route::get('/events/{slug}', function (string $slug) {
    $event = Event::query()
        ->where('slug', $slug)
        ->where('status', 'published')
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->firstOrFail();

    $canonicalUrl = $event->canonical_url ?: url('/events/' . $event->slug);
    $description = $event->meta_description ?: trim(implode(' - ', array_filter([
        $event->subtitle,
        $event->venue,
        $event->city,
    ])));
    $startDate = $event->start_date
        ? Carbon::parse($event->start_date->toDateString() . ' ' . ($event->start_time ?: '00:00:00'), $event->timezone)->toIso8601String()
        : null;

    return view('app', [
        'pageMeta' => [
            'title' => $event->meta_title ?: $event->title . ' | Black Sky Enterprise',
            'description' => $description ?: 'Black Sky Enterprise event detail.',
            'keywords' => $event->meta_keywords,
            'canonical' => $canonicalUrl,
            'image' => $event->og_image ?: $event->image_url,
            'type' => 'website',
        ],
        'structuredData' => [
            [
                '@context' => 'https://schema.org',
                '@type' => 'Event',
                'name' => $event->title,
                'description' => $description,
                'image' => array_values(array_filter([$event->og_image ?: $event->image_url])),
                'startDate' => $startDate,
                'endDate' => $event->end_date?->toDateString(),
                'eventStatus' => 'https://schema.org/EventScheduled',
                'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
                'location' => [
                    '@type' => 'Place',
                    'name' => $event->venue,
                    'address' => trim($event->city . ', ' . $event->country_code),
                ],
                'organizer' => [
                    '@type' => 'Organization',
                    'name' => $event->organizer_name ?: 'Black Sky Enterprise',
                    'url' => $event->organizer_url ?: url('/'),
                ],
                'offers' => filled($event->vendor_url) ? [
                    '@type' => 'Offer',
                    'url' => $event->vendor_url,
                    'availability' => $event->is_sold_out
                        ? 'https://schema.org/SoldOut'
                        : 'https://schema.org/InStock',
                ] : null,
            ],
        ],
    ]);
})->name('events.show');

Route::view('/{path?}', 'app')
    ->where('path', '^(?!api|sanctum|storage|build|admin).*$')
    ->name('spa');
