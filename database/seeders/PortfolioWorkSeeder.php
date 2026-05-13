<?php

namespace Database\Seeders;

use App\Models\PortfolioWork;
use Illuminate\Database\Seeder;

class PortfolioWorkSeeder extends Seeder
{
    public function run(): void
    {
        $works = [
            [
                'title' => 'Neon Pulse',
                'slug' => 'neon-pulse',
                'category' => 'Arena Concert',
                'year' => '2025',
                'location' => 'Kuala Lumpur',
                'role' => 'Full Production',
                'attendance' => '60,000+',
                'excerpt' => 'A sold-out 3-night arena run with world-class production, lighting, and fan operations.',
                'description' => "Neon Pulse brought a high-energy arena show into Kuala Lumpur across three consecutive nights.\n\nBlack Sky handled the concert production layer from stage direction and technical coordination through show-day crowd flow. The result was a polished arena experience with strong visual pacing, clean audience movement, and a production plan built for scale.",
                'featured_image' => 'https://images.unsplash.com/photo-1722506224957-7695c097ff88?auto=format&fit=crop&w=1600&q=80',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1200&q=80',
                ],
                'accent_color' => '#a855f7',
                'sort_order' => 10,
            ],
            [
                'title' => 'Tidal Wave',
                'slug' => 'tidal-wave',
                'category' => 'Music Festival',
                'year' => '2025',
                'location' => 'Penang',
                'role' => 'Festival Operations',
                'attendance' => '38,000',
                'excerpt' => "A beachside festival format across multiple stages, vendor zones, and late-night programming.",
                'description' => "Tidal Wave expanded the Black Sky festival playbook with a coastal format designed around crowd flow, stage separation, and sponsor visibility.\n\nThe production approach balanced operational clarity with high-impact visual moments so fans could move between stages without losing the event's identity.",
                'featured_image' => 'https://images.unsplash.com/photo-1760218832333-5b16f66ebc1a?auto=format&fit=crop&w=1600&q=80',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1561577328-58217edab062?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?auto=format&fit=crop&w=1200&q=80',
                ],
                'accent_color' => '#0ea5e9',
                'sort_order' => 20,
            ],
            [
                'title' => 'Dark Matter',
                'slug' => 'dark-matter',
                'category' => 'Media Production',
                'year' => '2024',
                'location' => 'Regional SEA',
                'role' => 'Original Content',
                'attendance' => '6 Episodes',
                'excerpt' => "An original editorial project documenting Southeast Asia's underground live music culture.",
                'description' => "Dark Matter followed artists, venues, and creative crews building live culture below the mainstream radar.\n\nBlack Sky shaped the editorial system, production schedule, and release assets so the series could support both storytelling and event discovery.",
                'featured_image' => 'https://images.unsplash.com/photo-1590699306463-dbb2b13c0ca0?auto=format&fit=crop&w=1600&q=80',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1521334884684-d80222895322?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1635942104748-c869be8c597c?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1524368535928-5b5e00ddc76b?auto=format&fit=crop&w=1200&q=80',
                ],
                'accent_color' => '#e11d48',
                'sort_order' => 30,
            ],
            [
                'title' => 'Ultraviolet',
                'slug' => 'ultraviolet',
                'category' => 'Festival',
                'year' => '2024',
                'location' => 'Singapore',
                'role' => 'Creative Direction',
                'attendance' => '24,000',
                'excerpt' => 'A synchronized electronic music experience with real-time lighting and visual direction.',
                'description' => "Ultraviolet was built as a sensory-first festival environment where lighting, video, and artist pacing worked as one system.\n\nThe project focused on making every transition feel intentional, from entry moments to headline finale cues.",
                'featured_image' => 'https://images.unsplash.com/photo-1765224747205-3c9c23f0553c?auto=format&fit=crop&w=1600&q=80',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1774112560513-38b6ec3ba898?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1506157786151-b8491531f063?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=1200&q=80',
                ],
                'accent_color' => '#ffb700',
                'sort_order' => 40,
            ],
            [
                'title' => 'Voltage - The Asia Tour',
                'slug' => 'voltage-the-asia-tour',
                'category' => 'Arena Tour',
                'year' => '2024',
                'location' => '8 Cities SEA',
                'role' => 'Tour Management',
                'attendance' => '2M+',
                'excerpt' => 'A coordinated arena tour across Southeast Asia with routing, production, and promotion under one system.',
                'description' => "Voltage connected eight city markets through a single operational and creative framework.\n\nThe tour required synchronized routing, local partner coordination, ticketing visibility, and consistent audience communication from announcement through final show day.",
                'featured_image' => 'https://images.unsplash.com/photo-1774112560513-38b6ec3ba898?auto=format&fit=crop&w=1600&q=80',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1516280440614-37939bbacd81?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1507874457470-272b3c8d8ee2?auto=format&fit=crop&w=1200&q=80',
                ],
                'accent_color' => '#f97316',
                'sort_order' => 50,
            ],
        ];

        foreach ($works as $work) {
            PortfolioWork::query()->updateOrCreate(
                ['slug' => $work['slug']],
                [
                    ...$work,
                    'status' => 'published',
                    'published_at' => now()->subDays($work['sort_order']),
                    'meta_title' => $work['title'] . ' | Black Sky Portfolio',
                    'meta_description' => $work['excerpt'],
                    'meta_keywords' => implode(', ', [$work['title'], $work['category'], $work['location'], 'Black Sky portfolio']),
                    'canonical_url' => url('/portfolio/' . $work['slug']),
                    'og_image' => $work['featured_image'],
                ],
            );
        }
    }
}
