<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $author = Author::query()->updateOrCreate(
            ['slug' => 'black-sky-editorial'],
            [
                'name' => 'Black Sky Editorial',
                'bio' => 'Editorial desk covering concerts, artist stories, venue intelligence, and live entertainment culture across Southeast Asia.',
                'photo' => 'https://images.unsplash.com/photo-1516280440614-37939bbacd81?auto=format&fit=crop&w=800&q=80',
                'email' => 'editorial@blackskyenterprise.com',
                'social_media' => [
                    'instagram' => 'https://instagram.com/blackskyenterprise',
                ],
                'is_active' => true,
            ],
        );

        $categories = collect([
            'concert-intelligence' => [
                'name' => 'Concert Intelligence',
                'description' => 'Market insight, event demand signals, and live entertainment analysis.',
                'sort_order' => 10,
            ],
            'artist-stories' => [
                'name' => 'Artist Stories',
                'description' => 'Profiles, interviews, and editorial stories around artists and fans.',
                'sort_order' => 20,
            ],
            'behind-the-scenes' => [
                'name' => 'Behind The Scenes',
                'description' => 'Production notes from venue planning, staging, ticketing, and show day operations.',
                'sort_order' => 30,
            ],
        ])->mapWithKeys(fn (array $category, string $slug) => [
            $slug => BlogCategory::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    ...$category,
                    'meta_title' => $category['name'] . ' | Black Sky News',
                    'meta_description' => $category['description'],
                    'is_active' => true,
                ],
            ),
        ]);

        $tags = collect([
            'kuala-lumpur' => ['name' => 'Kuala Lumpur', 'sort_order' => 10],
            'concert-guide' => ['name' => 'Concert Guide', 'sort_order' => 20],
            'ticketing' => ['name' => 'Ticketing', 'sort_order' => 30],
            'festival' => ['name' => 'Festival', 'sort_order' => 40],
            'production' => ['name' => 'Production', 'sort_order' => 50],
            'seo-discovery' => ['name' => 'SEO Discovery', 'sort_order' => 60],
        ])->mapWithKeys(fn (array $tag, string $slug) => [
            $slug => BlogTag::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    ...$tag,
                    'description' => 'Black Sky editorial tag for ' . $tag['name'] . '.',
                    'is_active' => true,
                ],
            ),
        ]);

        $posts = [
            [
                'title' => 'How Kuala Lumpur Became Southeast Asia Concert Capital',
                'slug' => 'kuala-lumpur-southeast-asia-concert-capital',
                'excerpt' => 'A practical look at why Kuala Lumpur keeps attracting arena tours, festival formats, and regional live music audiences.',
                'content' => "Kuala Lumpur has become one of Southeast Asia's most reliable live entertainment hubs because it combines venue access, regional flight connectivity, and a fan culture that moves quickly when the right artist is announced.\n\nFor promoters, that means planning needs to begin long before a poster goes live. Venue holds, production routing, transport, and vendor ticketing all shape whether a concert can scale without creating friction for fans.\n\nBlack Sky tracks these signals across city demand, genre momentum, and audience behavior so each campaign can meet fans where they already are searching.",
                'featured_image' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1400&q=80',
                'category' => 'concert-intelligence',
                'tags' => ['kuala-lumpur', 'concert-guide', 'seo-discovery'],
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'What Fans Should Check Before Buying Concert Tickets',
                'slug' => 'what-fans-should-check-before-buying-concert-tickets',
                'excerpt' => 'A clear ticketing checklist for fans before they leave Black Sky and complete purchase through an official vendor.',
                'content' => "Black Sky events may redirect ticket purchases to verified external vendors, so fans should always check the event name, date, seat category, payment currency, and refund policy before confirming payment.\n\nThe safest path is simple: start from the official event page, follow the listed vendor link, and avoid screenshots or forwarded links from unknown sources.\n\nWhen demand spikes, official pages also help fans understand whether tickets are available, sold out, or pending a future sale window.",
                'featured_image' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1400&q=80',
                'category' => 'concert-intelligence',
                'tags' => ['ticketing', 'concert-guide'],
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Behind The Build Of A Festival Stage',
                'slug' => 'behind-the-build-of-a-festival-stage',
                'excerpt' => 'From site layout to lighting angles, every festival stage starts as an operational plan before it becomes a crowd moment.',
                'content' => "A strong stage build starts with practical decisions: crowd flow, load-in access, power distribution, weather planning, and a production schedule that gives each crew enough space to work safely.\n\nThe visual layer comes after the operational base is stable. Lighting, video, scenic structure, and artist positioning all need to support the same goal: making the performance legible from the front row to the back of the venue.\n\nThat is why production planning is part creative direction and part risk management.",
                'featured_image' => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1400&q=80',
                'category' => 'behind-the-scenes',
                'tags' => ['festival', 'production'],
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'Why Artist Stories Matter Before Show Day',
                'slug' => 'why-artist-stories-matter-before-show-day',
                'excerpt' => 'Editorial content helps audiences discover the artist, understand the moment, and decide why the show matters now.',
                'content' => "A concert campaign is stronger when audiences understand more than the date and venue. Artist stories give fans a reason to care, share, and return to the event page before ticket decisions are made.\n\nFor search discovery, editorial articles also create durable entry points for people who are researching artists, tour context, venue details, and regional concert news.\n\nThe best content is useful before the sale and still valuable after the show.",
                'featured_image' => 'https://images.unsplash.com/photo-1521334884684-d80222895322?auto=format&fit=crop&w=1400&q=80',
                'category' => 'artist-stories',
                'tags' => ['seo-discovery', 'concert-guide'],
                'published_at' => now()->subDays(18),
            ],
        ];

        foreach ($posts as $post) {
            $blogPost = BlogPost::query()->updateOrCreate(
                ['slug' => $post['slug']],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'content' => $post['content'],
                    'featured_image' => $post['featured_image'],
                    'meta_title' => $post['title'] . ' | Black Sky News',
                    'meta_description' => $post['excerpt'],
                    'meta_keywords' => implode(', ', array_merge([$post['title']], $post['tags'])),
                    'canonical_url' => url('/news/' . $post['slug']),
                    'og_image' => $post['featured_image'],
                    'status' => 'published',
                    'author_id' => $author->id,
                    'category_id' => $categories[$post['category']]->id,
                    'published_at' => $post['published_at'],
                ],
            );

            $blogPost->tags()->sync(
                collect($post['tags'])->map(fn (string $tagSlug) => $tags[$tagSlug]->id)->all(),
            );
        }
    }
}
