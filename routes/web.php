<?php

use App\Models\BlogPost;
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
            'image' => $post->og_image ?: $post->featured_image,
            'type' => 'article',
        ],
        'structuredData' => [
            [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $post->title,
                'description' => $post->seo_description,
                'image' => $post->og_image ?: $post->featured_image,
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

Route::view('/{path?}', 'app')
    ->where('path', '^(?!api|sanctum|storage|build|admin).*$')
    ->name('spa');
