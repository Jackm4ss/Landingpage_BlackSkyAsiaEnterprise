<?php

namespace Tests\Feature;

use Database\Seeders\PortfolioWorkSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPortfolioTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_portfolio_endpoint_returns_published_works(): void
    {
        $this->seed(PortfolioWorkSeeder::class);

        $this->getJson('/api/v1/portfolio')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'neon-pulse')
            ->assertJsonPath('data.0.category', 'Arena Concert')
            ->assertJsonPath('meta.per_page', 8)
            ->assertJsonPath('meta.filters.categories.0', 'Arena Concert');
    }

    public function test_public_portfolio_endpoint_supports_search_and_detail(): void
    {
        $this->seed(PortfolioWorkSeeder::class);

        $this->getJson('/api/v1/portfolio?search=tidal')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'tidal-wave');

        $this->getJson('/api/v1/portfolio/neon-pulse')
            ->assertOk()
            ->assertJsonPath('data.title', 'Neon Pulse')
            ->assertJsonPath('data.canonical_url', url('/portfolio/neon-pulse'));
    }

    public function test_portfolio_detail_page_has_indexable_meta(): void
    {
        $this->withoutVite();
        $this->seed(PortfolioWorkSeeder::class);

        $this->get('/portfolio/neon-pulse')
            ->assertOk()
            ->assertSee('Neon Pulse | Black Sky Portfolio')
            ->assertSee('CreativeWork');
    }
}
