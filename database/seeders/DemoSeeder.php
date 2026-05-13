<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Bookmark;
use App\Models\Event;
use App\Models\PortfolioWork;
use App\Models\SyncedTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedEvents();
        $this->seedNews();
        $this->seedPortfolio();
        $this->seedMembers();
    }

    private function seedEvents(): void
    {
        $cities = [
            ['Kuala Lumpur', 'MY', 'Axiata Arena', 'Asia/Kuala_Lumpur'],
            ['Singapore', 'SG', 'Singapore Indoor Stadium', 'Asia/Singapore'],
            ['Jakarta', 'ID', 'Beach City International Stadium', 'Asia/Jakarta'],
            ['Bangkok', 'TH', 'Impact Arena', 'Asia/Bangkok'],
            ['Manila', 'PH', 'Mall of Asia Arena', 'Asia/Manila'],
            ['Ho Chi Minh City', 'VN', 'Saigon Exhibition Center', 'Asia/Ho_Chi_Minh'],
        ];
        $genres = ['Festival', 'Arena Show', 'K-Pop', 'Rave', 'Indie', 'Electronic', 'Hip-Hop', 'Pop'];
        $images = [
            'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1506157786151-b8491531f063?auto=format&fit=crop&w=1400&q=80',
        ];
        $accents = ['#0EA5E9', '#A855F7', '#E11D48', '#14B8A6', '#F97316', '#FFB700'];

        for ($i = 1; $i <= 24; $i++) {
            [$city, $country, $venue, $timezone] = $cities[($i - 1) % count($cities)];
            $genre = $genres[($i - 1) % count($genres)];
            $date = now()->addDays(10 + ($i * 8));
            $title = 'BLACK SKY LIVE ' . str_pad((string) $i, 2, '0', STR_PAD_LEFT);
            $slug = Str::slug($title . '-' . $city);
            $accent = $accents[($i - 1) % count($accents)];

            $event = Event::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'subtitle' => $genre . ' showcase across ' . $city,
                    'venue' => $venue,
                    'city' => $city,
                    'country_code' => $country,
                    'genre' => Str::upper($genre),
                    'start_date' => $date->toDateString(),
                    'start_time' => sprintf('%02d:00', 18 + ($i % 5)),
                    'end_date' => $i % 5 === 0 ? $date->copy()->addDay()->toDateString() : $date->toDateString(),
                    'date_display' => $date->format('M d, Y'),
                    'timezone' => $timezone,
                    'status' => 'published',
                    'is_sold_out' => $i % 9 === 0,
                    'image_url' => $images[($i - 1) % count($images)],
                    'accent_color' => $accent,
                    'glow_color' => $this->hexToRgba($accent, 0.45),
                    'vendor_url' => 'https://ticket2u.com.my/black-sky-demo-' . $i,
                    'organizer_name' => 'Black Sky Enterprise',
                    'spotify_embed_url' => 'https://open.spotify.com/embed/playlist/37i9dQZF1DX4dyzvuaRJ0n?utm_source=generator',
                    'published_at' => now()->subDays($i),
                    'meta_title' => $title . ' | Black Sky Enterprise',
                    'meta_description' => $genre . ' event at ' . $venue . ', ' . $city . '.',
                    'meta_keywords' => strtolower($title . ', ' . $genre . ', ' . $city . ', Black Sky Enterprise'),
                    'canonical_url' => url('/events/' . $slug),
                    'og_image' => $images[($i - 1) % count($images)],
                ],
            );

            $event->sections()->delete();
            foreach ($this->eventSections($city, $venue, $genre) as $index => $section) {
                $event->sections()->create([
                    'section_key' => $section['key'],
                    'title' => $section['title'],
                    'content' => $section['content'],
                    'sort_order' => $index,
                    'is_enabled' => true,
                ]);
            }
        }
    }

    private function seedNews(): void
    {
        $author = Author::query()->firstOrCreate(
            ['slug' => 'black-sky-editorial'],
            [
                'name' => 'Black Sky Editorial',
                'bio' => 'Editorial desk covering concerts, artist stories, venue intelligence, and live entertainment culture across Southeast Asia.',
                'photo' => 'https://images.unsplash.com/photo-1516280440614-37939bbacd81?auto=format&fit=crop&w=800&q=80',
                'email' => 'editorial@blackskyenterprise.com',
                'is_active' => true,
            ],
        );

        $categories = collect(['Concert Intelligence', 'Artist Stories', 'Behind The Scenes', 'Venue Guide'])
            ->mapWithKeys(fn (string $name, int $index) => [
                Str::slug($name) => BlogCategory::query()->updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'name' => $name,
                        'description' => $name . ' for Southeast Asia live entertainment.',
                        'sort_order' => ($index + 1) * 10,
                        'is_active' => true,
                    ],
                ),
            ]);

        $tags = collect(['Kuala Lumpur', 'Ticketing', 'Festival', 'Production', 'Fan Guide', 'Asia Tour', 'Venue'])
            ->mapWithKeys(fn (string $name, int $index) => [
                Str::slug($name) => BlogTag::query()->updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'name' => $name,
                        'description' => 'Demo editorial tag for ' . $name . '.',
                        'sort_order' => ($index + 1) * 10,
                        'is_active' => true,
                    ],
                ),
            ]);

        $images = [
            'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1521334884684-d80222895322?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1400&q=80',
        ];

        for ($i = 1; $i <= 18; $i++) {
            $categoryKey = $categories->keys()[($i - 1) % $categories->count()];
            $title = 'Black Sky Market Note ' . str_pad((string) $i, 2, '0', STR_PAD_LEFT);
            $slug = Str::slug($title);
            $selectedTags = $tags->values()->take(3)->pluck('id')->all();

            $post = BlogPost::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'excerpt' => 'A demo-ready editorial story covering demand, fan behavior, and concert discovery signals for client review.',
                    'content' => "Black Sky tracks live entertainment demand across search, social, venue capacity, and ticketing behavior.\n\nThis demo article gives the client a realistic content surface for news listing, filtering, and article detail review. It is intentionally written as production-style editorial copy instead of placeholder text.\n\nThe operational takeaway is simple: better event data creates better audience decisions before show day.",
                    'featured_image' => $images[($i - 1) % count($images)],
                    'meta_title' => $title . ' | Black Sky News',
                    'meta_description' => 'Demo-ready Black Sky editorial content for client review.',
                    'meta_keywords' => 'Black Sky, concerts, live entertainment, demo news',
                    'canonical_url' => url('/news/' . $slug),
                    'og_image' => $images[($i - 1) % count($images)],
                    'status' => 'published',
                    'author_id' => $author->id,
                    'category_id' => $categories[$categoryKey]->id,
                    'view_count' => 1200 + ($i * 173),
                    'published_at' => now()->subDays($i * 2),
                ],
            );

            $post->tags()->sync($selectedTags);
        }
    }

    private function seedPortfolio(): void
    {
        $categories = ['Arena Concert', 'Music Festival', 'Tour Management', 'Brand Activation', 'Media Production'];
        $locations = ['Kuala Lumpur', 'Singapore', 'Jakarta', 'Bangkok', 'Manila', 'Penang'];
        $images = [
            'https://images.unsplash.com/photo-1722506224957-7695c097ff88?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1760218832333-5b16f66ebc1a?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1590699306463-dbb2b13c0ca0?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1774112560513-38b6ec3ba898?auto=format&fit=crop&w=1600&q=80',
        ];
        $accents = ['#0ea5e9', '#a855f7', '#e11d48', '#14b8a6', '#f97316'];

        for ($i = 1; $i <= 16; $i++) {
            $title = 'Black Sky Showcase Case ' . str_pad((string) $i, 2, '0', STR_PAD_LEFT);
            $slug = Str::slug($title);
            $category = $categories[($i - 1) % count($categories)];
            $location = $locations[($i - 1) % count($locations)];
            $image = $images[($i - 1) % count($images)];

            PortfolioWork::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'category' => $category,
                    'year' => (string) (2026 - ($i % 4)),
                    'location' => $location,
                    'role' => $i % 2 === 0 ? 'Full Production' : 'Campaign and Operations',
                    'attendance' => number_format(18000 + ($i * 4200)),
                    'excerpt' => 'A production-ready case study for Black Sky event delivery, operations, and audience experience.',
                    'description' => "This portfolio entry demonstrates how Black Sky presents campaign outcomes to clients and partners.\n\nThe project covers planning, production, audience flow, content capture, and post-event reporting across Southeast Asia live entertainment markets.",
                    'featured_image' => $image,
                    'gallery_images' => [
                        $images[$i % count($images)],
                        $images[($i + 1) % count($images)],
                        $images[($i + 2) % count($images)],
                    ],
                    'accent_color' => $accents[($i - 1) % count($accents)],
                    'status' => 'published',
                    'sort_order' => $i * 10,
                    'published_at' => now()->subDays($i * 3),
                    'meta_title' => $title . ' | Black Sky Portfolio',
                    'meta_description' => 'Demo portfolio detail for client review.',
                    'meta_keywords' => 'Black Sky, portfolio, ' . $category . ', ' . $location,
                    'canonical_url' => url('/portfolio/' . $slug),
                    'og_image' => $image,
                ],
            );
        }
    }

    private function seedMembers(): void
    {
        $role = Role::findOrCreate('user');
        $sources = ['instagram', 'instagram', 'facebook', 'tiktok', 'google', 'newsletter', 'partner', 'direct', 'other'];
        $countries = ['MY', 'MY', 'MY', 'ID', 'SG', 'TH', 'PH', 'VN', 'BN', 'JP', 'KR', 'AU'];
        $events = Event::query()->where('status', 'published')->orderBy('start_date')->get();

        for ($i = 1; $i <= 240; $i++) {
            $source = $sources[($i - 1) % count($sources)];
            $country = $countries[($i - 1) % count($countries)];
            $email = 'demo.member.' . str_pad((string) $i, 3, '0', STR_PAD_LEFT) . '@blacksky.test';

            $user = User::query()->updateOrCreate(
                ['email' => $email],
                [
                    'name' => 'Demo Member ' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                    'phone' => '+60' . str_pad((string) (110000000 + $i), 9, '0', STR_PAD_LEFT),
                    'password' => Hash::make('password'),
                    'email_verified_at' => now()->subDays($i % 30),
                    'registration_source' => $source,
                    'registration_country_code' => $country,
                    'registration_referrer' => $source === 'direct' ? null : 'https://' . $source . '.com/blackskyenterprise',
                    'date_of_birth' => now()->subYears(20 + ($i % 18))->subDays($i)->toDateString(),
                    'gender' => ['male', 'female', 'prefer_not_to_say'][$i % 3],
                    'is_active' => true,
                ],
            );
            $user->syncRoles([$role]);

            if ($events->isNotEmpty() && $i % 3 !== 0) {
                $event = $events[($i - 1) % $events->count()];

                Bookmark::query()->updateOrCreate([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                ]);
            }

            if ($events->isNotEmpty() && $i % 4 !== 0) {
                $event = $events[($i + 2) % $events->count()];
                SyncedTransaction::query()->updateOrCreate(
                    [
                        'vendor' => 'ticket2u',
                        'external_order_id' => 'BSE-DEMO-' . str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                    ],
                    [
                        'user_id' => $user->id,
                        'event_id' => $event->id,
                        'buyer_email' => $user->email,
                        'event_title' => $event->title,
                        'ticket_type' => $i % 5 === 0 ? 'VIP Deck' : 'General Admission',
                        'quantity' => 1 + ($i % 4),
                        'total_amount' => 188 + (($i % 6) * 75),
                        'currency' => 'MYR',
                        'status' => $i % 11 === 0 ? 'pending' : 'confirmed',
                        'purchased_at' => now()->subDays($i % 45),
                        'raw_payload' => [
                            'source' => 'demo_seed',
                            'country' => $country,
                        ],
                    ],
                );
            }
        }
    }

    /**
     * @return array<int, array{key: string, title: string, content: string}>
     */
    private function eventSections(string $city, string $venue, string $genre): array
    {
        return [
            [
                'key' => 'event_details',
                'title' => 'Event Details',
                'content' => "A high-energy {$genre} event at {$venue}, {$city}. The show package includes headline programming, production staging, fan entry flow, and partner-ready audience reporting.",
            ],
            [
                'key' => 'ticket_pricing',
                'title' => 'Ticket Pricing',
                'content' => "- General Admission: RM 188\n- Premium Zone: RM 328\n- VIP Deck: RM 488",
            ],
            [
                'key' => 'important_information',
                'title' => 'Important Information',
                'content' => 'Ticket purchases are completed through the official vendor link. Entry requirements, age policy, and final admission terms are confirmed by the venue and ticketing partner.',
            ],
        ];
    }

    private function hexToRgba(string $hex, float $alpha): string
    {
        $hex = ltrim($hex, '#');

        return sprintf(
            'rgba(%d,%d,%d,%s)',
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
            rtrim(rtrim(number_format($alpha, 2), '0'), '.'),
        );
    }
}
