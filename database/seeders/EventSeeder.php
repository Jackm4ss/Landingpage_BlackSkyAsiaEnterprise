<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'IGNITE FESTIVAL',
                'slug' => 'ignite-festival',
                'subtitle' => 'The Greatest Electronic Music Gathering in SEA',
                'venue' => 'Axiata Arena',
                'city' => 'Kuala Lumpur',
                'country_code' => 'MY',
                'genre' => 'FESTIVAL',
                'start_date' => '2026-07-19',
                'start_time' => '20:00',
                'end_date' => '2026-07-21',
                'date_display' => 'JUL 19-21, 2026',
                'status' => 'published',
                'is_sold_out' => false,
                'image_url' => 'https://images.unsplash.com/photo-1774112560513-38b6ec3ba898?auto=format&fit=crop&w=1200&q=80',
                'accent_color' => '#A855F7',
                'glow_color' => 'rgba(168,85,247,0.45)',
                'spotify_embed_url' => 'https://open.spotify.com/embed/playlist/37i9dQZF1DX4dyzvuaRJ0n?utm_source=generator',
                'sections' => [
                    'about' => "IGNITE FESTIVAL brings a full-scale electronic music weekend to Kuala Lumpur with immersive stage production, regional DJs, and a high-energy crowd experience built for Southeast Asia.",
                    'ticket_pricing' => "- General Admission: RM 288\n- VIP Deck: RM 488\n- Weekend Pass: RM 688",
                    'important_information' => "Ticket purchases are completed through the official vendor link. Please review the vendor checkout page carefully before confirming your purchase.",
                ],
            ],
            [
                'title' => 'ECHOES',
                'slug' => 'echoes-singapore-night',
                'subtitle' => 'Asia Arena Tour - Singapore Night',
                'venue' => 'Singapore Indoor Stadium',
                'city' => 'Singapore',
                'country_code' => 'SG',
                'genre' => 'ARENA SHOW',
                'start_date' => '2026-08-05',
                'start_time' => '20:30',
                'end_date' => '2026-08-05',
                'date_display' => 'AUG 05, 2026',
                'status' => 'published',
                'is_sold_out' => true,
                'image_url' => 'https://images.unsplash.com/photo-1772587023108-61e60c18537a?auto=format&fit=crop&w=1200&q=80',
                'accent_color' => '#0EA5E9',
                'glow_color' => 'rgba(14,165,233,0.45)',
                'spotify_embed_url' => 'https://open.spotify.com/embed/playlist/37i9dQZF1DX1IeqVkK7Ebc?utm_source=generator',
                'sections' => [
                    'about' => "ECHOES lands in Singapore for one arena night with a large-format stage, focused lighting direction, and a set designed for fans who want the full tour experience.",
                    'admission_policy' => "Admission policy and entry requirements will follow the official venue and ticketing partner guidance.",
                ],
            ],
            [
                'title' => 'BLACKOUT',
                'slug' => 'blackout',
                'subtitle' => 'The Ultimate Dark Rave Experience',
                'venue' => 'Zepp KL',
                'city' => 'Kuala Lumpur',
                'country_code' => 'MY',
                'genre' => 'RAVE',
                'start_date' => '2026-08-23',
                'start_time' => '22:00',
                'end_date' => '2026-08-23',
                'date_display' => 'AUG 23, 2026',
                'status' => 'published',
                'is_sold_out' => false,
                'image_url' => 'https://images.unsplash.com/photo-1754492885592-34e5fe3f0093?auto=format&fit=crop&w=1200&q=80',
                'accent_color' => '#E11D48',
                'glow_color' => 'rgba(225,29,72,0.45)',
                'spotify_embed_url' => 'https://open.spotify.com/embed/playlist/37i9dQZF1DX0BcQWzuB7ZO?utm_source=generator',
                'sections' => [
                    'about' => "BLACKOUT is a late-night rave concept shaped around darker sound design, heavy lighting, and a venue-first crowd flow.",
                    'event_guide' => "Event guide will be updated closer to show day.",
                ],
            ],
            [
                'title' => 'SKY SESSIONS',
                'slug' => 'sky-sessions',
                'subtitle' => 'Rooftop Open Air Concert Series',
                'venue' => 'TREC Entertainment Hub',
                'city' => 'Kuala Lumpur',
                'country_code' => 'MY',
                'genre' => 'OUTDOOR',
                'start_date' => '2026-09-12',
                'start_time' => '18:30',
                'end_date' => '2026-09-12',
                'date_display' => 'SEP 12, 2026',
                'status' => 'published',
                'is_sold_out' => false,
                'image_url' => 'https://images.unsplash.com/photo-1739194029327-bb1b252cf9c3?auto=format&fit=crop&w=1200&q=80',
                'accent_color' => '#F97316',
                'glow_color' => 'rgba(249,115,22,0.45)',
                'spotify_embed_url' => 'https://open.spotify.com/embed/playlist/37i9dQZF1DX4sWSpwq3LiO?utm_source=generator',
                'sections' => [
                    'about' => "SKY SESSIONS is an open-air concert series built around sunset programming, rooftop atmosphere, and an easy city-night arrival.",
                    'ticketing_information' => "Availability, ticket limits, and payment rules are controlled by the official ticketing vendor.",
                ],
            ],
            [
                'title' => 'STARLIGHT',
                'slug' => 'starlight',
                'subtitle' => 'K-Pop & Asian Pop Live Spectacular',
                'venue' => 'Stadium Merdeka',
                'city' => 'Kuala Lumpur',
                'country_code' => 'MY',
                'genre' => 'K-POP',
                'start_date' => '2026-10-03',
                'start_time' => '20:00',
                'end_date' => '2026-10-03',
                'date_display' => 'OCT 03, 2026',
                'status' => 'published',
                'is_sold_out' => false,
                'image_url' => 'https://images.unsplash.com/photo-1590699306463-dbb2b13c0ca0?auto=format&fit=crop&w=1200&q=80',
                'accent_color' => '#FFB700',
                'glow_color' => 'rgba(255,183,0,0.45)',
                'spotify_embed_url' => 'https://open.spotify.com/embed/playlist/37i9dQZF1DX9tPFwDMOaN1?utm_source=generator',
                'sections' => [
                    'about' => "STARLIGHT brings Asian pop staging, fan moments, and bright arena-scale visuals into one live spectacular at Stadium Merdeka.",
                    'fan_benefit_information' => "Fan benefit information will be announced by the promoter before the event date.",
                ],
            ],
        ];

        foreach ($events as $event) {
            $sections = $event['sections'] ?? [];
            unset($event['sections']);

            $model = Event::query()->updateOrCreate(
                ['slug' => $event['slug']],
                [
                    ...$event,
                    'timezone' => $event['country_code'] === 'SG' ? 'Asia/Singapore' : 'Asia/Kuala_Lumpur',
                    'organizer_name' => 'Black Sky Enterprise',
                    'published_at' => now(),
                    'meta_title' => $event['title'] . ' | Black Sky Enterprise',
                    'meta_description' => $event['subtitle'] . ' at ' . $event['venue'] . ', ' . $event['city'] . '.',
                    'meta_keywords' => strtolower($event['title'] . ', ' . $event['genre'] . ', ' . $event['city'] . ', Black Sky Enterprise'),
                    'canonical_url' => url('/events/' . $event['slug']),
                    'og_image' => $event['image_url'],
                ],
            );

            $model->sections()->delete();

            foreach (array_values($sections) as $index => $content) {
                $sectionKey = array_keys($sections)[$index];

                $model->sections()->create([
                    'section_key' => $sectionKey,
                    'title' => str($sectionKey)->replace('_', ' ')->title()->toString(),
                    'content' => $content,
                    'sort_order' => $index,
                    'is_enabled' => true,
                ]);
            }
        }
    }
}
