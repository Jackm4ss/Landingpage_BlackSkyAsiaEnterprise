<?php

use App\Models\BlogPost;
use Illuminate\Support\Facades\Route;

Route::get('/blog', function () {
    return view('app', [
        'pageMeta' => [
            'title' => 'Blog | Black Sky Enterprise',
            'description' => 'Read Black Sky Enterprise concert guides, artist stories, venue intelligence, and live entertainment insights across Southeast Asia.',
            'canonical' => url('/blog'),
            'type' => 'website',
        ],
        'structuredData' => [
            [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => 'Black Sky Blog',
                'description' => 'Concert guides, artist stories, and live entertainment insights from Black Sky Enterprise.',
                'url' => url('/blog'),
            ],
        ],
    ]);
})->name('blog.index');

Route::get('/blog/{slug}', function (string $slug) {
    $post = BlogPost::query()
        ->published()
        ->with(['author:id,name,slug', 'category:id,name,slug'])
        ->where('slug', $slug)
        ->firstOrFail();

    return view('app', [
        'pageMeta' => [
            'title' => $post->seo_title,
            'description' => $post->seo_description,
            'keywords' => $post->meta_keywords,
            'canonical' => $post->canonical_url ?: url('/blog/' . $post->slug),
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
                'mainEntityOfPage' => url('/blog/' . $post->slug),
            ],
        ],
    ]);
})->name('blog.show');

Route::view('/{path?}', 'app')
    ->where('path', '^(?!api|sanctum|storage|build|admin).*$')
    ->name('spa');
