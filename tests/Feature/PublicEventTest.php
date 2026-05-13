<?php

namespace Tests\Feature;

use Database\Seeders\EventSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_event_index_returns_upcoming_events(): void
    {
        $this->seed(EventSeeder::class);

        $this->getJson('/api/v1/events?per_page=5')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'ignite-festival')
            ->assertJsonPath('data.0.time', '8:00 PM')
            ->assertJsonPath('meta.per_page', 5);
    }

    public function test_public_event_detail_returns_sections_and_seo_payload(): void
    {
        $this->seed(EventSeeder::class);

        $this->getJson('/api/v1/events/ignite-festival')
            ->assertOk()
            ->assertJsonPath('data.slug', 'ignite-festival')
            ->assertJsonPath('data.organizer_name', 'Black Sky Enterprise')
            ->assertJsonPath('data.spotify_embed_url', 'https://open.spotify.com/embed/playlist/37i9dQZF1DX4dyzvuaRJ0n?utm_source=generator')
            ->assertJsonPath('data.sections.0.section_key', 'about')
            ->assertJsonPath('data.canonical_url', url('/events/ignite-festival'));
    }

    public function test_public_event_detail_page_has_indexable_meta(): void
    {
        $this->withoutVite();
        $this->seed(EventSeeder::class);

        $this->get('/events/ignite-festival')
            ->assertOk()
            ->assertSee('IGNITE FESTIVAL | Black Sky Enterprise')
            ->assertSee('EventScheduled')
            ->assertSee('/events/ignite-festival');
    }
}
