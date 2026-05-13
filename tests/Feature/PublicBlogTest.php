<?php

namespace Tests\Feature;

use Database\Seeders\BlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicBlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_blog_endpoint_returns_published_posts_with_filters(): void
    {
        $this->seed(BlogSeeder::class);

        $this->getJson('/api/v1/blog')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'kuala-lumpur-southeast-asia-concert-capital')
            ->assertJsonPath('data.0.category.slug', 'concert-intelligence')
            ->assertJsonPath('meta.per_page', 9)
            ->assertJsonPath('meta.filters.categories.0.slug', 'concert-intelligence')
            ->assertJsonPath('meta.filters.tags.0.slug', 'kuala-lumpur');
    }

    public function test_public_blog_endpoint_filters_by_category_tag_and_search(): void
    {
        $this->seed(BlogSeeder::class);

        $this->getJson('/api/v1/blog?category=behind-the-scenes')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'behind-the-build-of-a-festival-stage');

        $this->getJson('/api/v1/blog?tag=ticketing')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'what-fans-should-check-before-buying-concert-tickets');

        $this->getJson('/api/v1/blog?search=durable')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'why-artist-stories-matter-before-show-day');
    }

    public function test_public_blog_detail_endpoint_returns_article_payload(): void
    {
        $this->seed(BlogSeeder::class);

        $this->getJson('/api/v1/blog/kuala-lumpur-southeast-asia-concert-capital')
            ->assertOk()
            ->assertJsonPath('data.title', 'How Kuala Lumpur Became Southeast Asia Concert Capital')
            ->assertJsonPath('data.author.name', 'Black Sky Editorial')
            ->assertJsonPath('data.canonical_url', url('/blog/kuala-lumpur-southeast-asia-concert-capital'));
    }

    public function test_blog_pages_have_indexable_meta(): void
    {
        $this->withoutVite();
        $this->seed(BlogSeeder::class);

        $this->get('/blog')
            ->assertOk()
            ->assertSee('Blog | Black Sky Enterprise')
            ->assertSee('CollectionPage');

        $this->get('/blog/kuala-lumpur-southeast-asia-concert-capital')
            ->assertOk()
            ->assertSee('How Kuala Lumpur Became Southeast Asia Concert Capital | Black Sky Blog')
            ->assertSee('Article');
    }
}
