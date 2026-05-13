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
                'end_date' => '2026-07-21',
                'date_display' => 'JUL 19-21, 2026',
                'status' => 'published',
                'is_sold_out' => false,
                'image_url' => 'https://images.unsplash.com/photo-1774112560513-38b6ec3ba898?auto=format&fit=crop&w=1200&q=80',
                'accent_color' => '#A855F7',
                'glow_color' => 'rgba(168,85,247,0.45)',
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
                'end_date' => '2026-08-05',
                'date_display' => 'AUG 05, 2026',
                'status' => 'published',
                'is_sold_out' => true,
                'image_url' => 'https://images.unsplash.com/photo-1772587023108-61e60c18537a?auto=format&fit=crop&w=1200&q=80',
                'accent_color' => '#0EA5E9',
                'glow_color' => 'rgba(14,165,233,0.45)',
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
                'end_date' => '2026-08-23',
                'date_display' => 'AUG 23, 2026',
                'status' => 'published',
                'is_sold_out' => false,
                'image_url' => 'https://images.unsplash.com/photo-1754492885592-34e5fe3f0093?auto=format&fit=crop&w=1200&q=80',
                'accent_color' => '#E11D48',
                'glow_color' => 'rgba(225,29,72,0.45)',
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
                'end_date' => '2026-09-12',
                'date_display' => 'SEP 12, 2026',
                'status' => 'published',
                'is_sold_out' => false,
                'image_url' => 'https://images.unsplash.com/photo-1739194029327-bb1b252cf9c3?auto=format&fit=crop&w=1200&q=80',
                'accent_color' => '#F97316',
                'glow_color' => 'rgba(249,115,22,0.45)',
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
                'end_date' => '2026-10-03',
                'date_display' => 'OCT 03, 2026',
                'status' => 'published',
                'is_sold_out' => false,
                'image_url' => 'https://images.unsplash.com/photo-1590699306463-dbb2b13c0ca0?auto=format&fit=crop&w=1200&q=80',
                'accent_color' => '#FFB700',
                'glow_color' => 'rgba(255,183,0,0.45)',
            ],
        ];

        foreach ($events as $event) {
            Event::query()->updateOrCreate(
                ['slug' => $event['slug']],
                [
                    ...$event,
                    'timezone' => $event['country_code'] === 'SG' ? 'Asia/Singapore' : 'Asia/Kuala_Lumpur',
                    'published_at' => now(),
                    'meta_title' => $event['title'] . ' | Black Sky Enterprise',
                    'meta_description' => $event['subtitle'] . ' at ' . $event['venue'] . ', ' . $event['city'] . '.',
                    'meta_keywords' => strtolower($event['title'] . ', ' . $event['genre'] . ', ' . $event['city'] . ', Black Sky Enterprise'),
                    'canonical_url' => url('/events/' . $event['slug']),
                    'og_image' => $event['image_url'],
                ],
            );
        }
    }
}
