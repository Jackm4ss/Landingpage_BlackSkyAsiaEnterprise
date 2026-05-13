<?php

namespace App\Filament\Pages\Events;

use App\Models\Event;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ListEvents extends Page
{
    use WithFileUploads;
    use WithPagination;

    private const EVENT_IMAGE_MAX_EDGE = 1920;

    private const EVENT_IMAGE_QUALITY = 82;

    private const EVENT_IMAGE_UPLOAD_MAX_KB = 1024000;

    private const EVENT_GENRES = [
        'FESTIVAL' => 'Festival',
        'ARENA SHOW' => 'Arena Show',
        'CONCERT' => 'Concert',
        'LIVE SHOW' => 'Live Show',
        'TOUR' => 'Tour',
        'RAVE' => 'Rave',
        'CLUB NIGHT' => 'Club Night',
        'OUTDOOR' => 'Outdoor',
        'K-POP' => 'K-Pop',
        'J-POP' => 'J-Pop',
        'ASIAN POP' => 'Asian Pop',
        'POP' => 'Pop',
        'ROCK' => 'Rock',
        'INDIE' => 'Indie',
        'HIP-HOP' => 'Hip-Hop',
        'R&B' => 'R&B',
        'EDM' => 'EDM',
        'HOUSE' => 'House',
        'TECHNO' => 'Techno',
        'TRANCE' => 'Trance',
        'JAZZ' => 'Jazz',
        'ACOUSTIC' => 'Acoustic',
        'ORCHESTRA' => 'Orchestra',
        'THEATRE' => 'Theatre',
        'COMEDY' => 'Comedy',
        'FAN MEETING' => 'Fan Meeting',
        'CONFERENCE' => 'Conference',
        'EXHIBITION' => 'Exhibition',
    ];

    private const EVENT_SECTION_TYPES = [
        'about' => 'About',
        'event_details' => 'Event Details',
        'on_sale_details' => 'On-Sale Details',
        'ticket_pricing' => 'Seat Map & Ticket Pricing',
        'location' => 'Location',
        'ticketing_information' => 'Ticketing Information',
        'important_information' => 'Important Information',
        'admission_policy' => 'Admission Policy',
        'fan_benefit_information' => 'Fan Benefit Information',
        'event_guide' => 'Event Guide',
        'custom' => 'Custom Section',
    ];

    private const CORE_EVENT_SECTION_KEYS = [
        'about',
        'event_details',
        'location',
    ];

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Events';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'events';

    protected static string $view = 'filament.pages.events.list-events';

    protected ?string $maxContentWidth = 'full';

    #[Url(except: '')]
    public string $search = '';

    #[Url(as: 'from', except: '')]
    public string $dateFrom = '';

    #[Url(as: 'to', except: '')]
    public string $dateTo = '';

    #[Url(as: 'per_page', except: 10)]
    public int $perPage = 10;

    public bool $isFormOpen = false;

    public bool $isDeleteOpen = false;

    public ?int $editingEventId = null;

    public ?int $deletingEventId = null;

    public string $deletingEventTitle = '';

    public mixed $eventImage = null;

    public string $sectionToAdd = 'about';

    /**
     * @var array<string, mixed>
     */
    public array $form = [];

    public function mount(): void
    {
        $this->resetEventForm();
    }

    public function getTitle(): string | Htmlable
    {
        return 'Events';
    }

    public function getHeading(): string | Htmlable
    {
        return '';
    }

    /**
     * @return array<string, string>
     */
    public function genreOptions(): array
    {
        return self::EVENT_GENRES;
    }

    /**
     * @return array<string, string>
     */
    public function sectionOptions(): array
    {
        return self::EVENT_SECTION_TYPES;
    }

    public function sectionHelpText(string $sectionKey): ?string
    {
        return match ($sectionKey) {
            'event_details' => 'Venue, date, and time come from the main fields above. Add notes here only when this event needs extra detail copy.',
            'location' => 'Venue, city, country, and directions are generated from the location fields above. Add parking or entry notes here when needed.',
            'ticket_pricing' => 'Use short lines or bullets for pricing tiers, seat maps, and ticket categories.',
            'ticketing_information' => 'Use this for ticket limits, purchase rules, and redemption instructions.',
            'important_information', 'admission_policy' => 'Use bullets for rules, entry requirements, age limits, or prohibited items.',
            default => null,
        };
    }

    public function updatedSearch(): void
    {
        $this->resetPage('eventsPage');
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage('eventsPage');
    }

    public function updatedDateTo(): void
    {
        $this->resetPage('eventsPage');
    }

    public function updatedPerPage(): void
    {
        $this->perPage = in_array($this->perPage, [5, 10, 25, 50], true) ? $this->perPage : 10;

        $this->resetPage('eventsPage');
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->perPage = 10;

        $this->resetPage('eventsPage');
    }

    public function openCreate(): void
    {
        $this->resetValidation();
        $this->editingEventId = null;
        $this->eventImage = null;
        $this->resetEventForm();
        $this->isFormOpen = true;
    }

    public function openEdit(int $eventId): void
    {
        $event = Event::query()
            ->with('sections')
            ->findOrFail($eventId);

        $this->resetValidation();
        $this->eventImage = null;
        $this->editingEventId = $event->id;
        $this->form = [
            'title' => $event->title,
            'slug' => $event->slug,
            'subtitle' => $event->subtitle,
            'venue' => $event->venue,
            'city' => $event->city,
            'country_code' => $event->country_code,
            'genre' => $event->genre,
            'start_date' => $event->start_date?->toDateString(),
            'start_time' => $event->start_time ? Str::of((string) $event->start_time)->beforeLast(':')->toString() : '',
            'end_date' => $event->end_date?->toDateString(),
            'date_display' => $event->date_display,
            'timezone' => $event->timezone,
            'status' => $event->status,
            'is_sold_out' => $event->is_sold_out,
            'image_url' => $event->image_url,
            'accent_color' => $event->accent_color,
            'glow_color' => $event->glow_color,
            'vendor_url' => $event->vendor_url,
            'organizer_name' => $event->organizer_name,
            'organizer_url' => $event->organizer_url,
            'spotify_embed_url' => $event->spotify_embed_url,
            'sections' => $this->eventSectionFormRows($event),
            'meta_title' => $event->meta_title,
            'meta_description' => $event->meta_description,
            'meta_keywords' => $event->meta_keywords,
            'canonical_url' => $event->canonical_url,
            'og_image' => $event->og_image,
        ];
        $this->isFormOpen = true;
    }

    public function addEventSection(): void
    {
        $sectionKey = array_key_exists($this->sectionToAdd, self::EVENT_SECTION_TYPES)
            ? $this->sectionToAdd
            : 'custom';

        $this->form['sections'][] = [
            'id' => null,
            'section_key' => $sectionKey,
            'title' => self::EVENT_SECTION_TYPES[$sectionKey],
            'content' => '',
            'is_enabled' => true,
        ];
    }

    public function removeEventSection(int $index): void
    {
        unset($this->form['sections'][$index]);

        $this->form['sections'] = array_values($this->form['sections'] ?? []);
    }

    public function closeEventForm(): void
    {
        $this->isFormOpen = false;
        $this->editingEventId = null;
        $this->eventImage = null;
        $this->resetValidation();
        $this->resetEventForm();
    }

    public function saveEvent(): void
    {
        $this->form['slug'] = filled($this->form['slug'] ?? null)
            ? Str::slug((string) $this->form['slug'])
            : Str::slug((string) ($this->form['title'] ?? ''));

        $validated = $this->validate()['form'];
        $sections = $validated['sections'] ?? [];
        unset($validated['sections']);

        $slug = $validated['slug'];

        $event = $this->editingEventId
            ? Event::query()->findOrFail($this->editingEventId)
            : new Event();

        $oldImageUrl = $event->image_url;
        $imageUrl = filled($validated['image_url'] ?? null) ? $validated['image_url'] : $event->image_url;

        if ($this->eventImage instanceof TemporaryUploadedFile) {
            $imageUrl = $this->storeCompressedEventImage($this->eventImage, $slug);
        }

        $accentColor = $validated['accent_color'] ?? '#0EA5E9';
        $spotifyEmbedUrl = $this->normalizeSpotifyEmbedUrl($validated['spotify_embed_url'] ?? null);

        $event->fill([
            ...$validated,
            'slug' => $slug,
            'country_code' => Str::upper($validated['country_code']),
            'genre' => Str::upper($validated['genre']),
            'start_time' => filled($validated['start_time'] ?? null) ? $validated['start_time'] : null,
            'end_date' => filled($validated['end_date'] ?? null) ? $validated['end_date'] : null,
            'date_display' => filled($validated['date_display'] ?? null) ? $validated['date_display'] : null,
            'is_sold_out' => (bool) ($validated['is_sold_out'] ?? false),
            'image_url' => $imageUrl,
            'accent_color' => $accentColor,
            'spotify_embed_url' => $spotifyEmbedUrl,
            'glow_color' => filled($validated['glow_color'] ?? null)
                ? $validated['glow_color']
                : $this->glowColorFromAccent($accentColor),
            'published_at' => $validated['status'] === 'published'
                ? ($event->published_at ?? now())
                : null,
            'meta_title' => filled($validated['meta_title'] ?? null)
                ? $validated['meta_title']
                : $this->defaultMetaTitle($validated),
            'meta_description' => filled($validated['meta_description'] ?? null)
                ? $validated['meta_description']
                : $this->defaultMetaDescription($validated),
            'canonical_url' => filled($validated['canonical_url'] ?? null)
                ? $validated['canonical_url']
                : url('/events/' . $slug),
            'og_image' => filled($validated['og_image'] ?? null)
                ? $validated['og_image']
                : $imageUrl,
        ]);
        $event->save();
        $this->syncEventSections($event, $sections);

        if ($this->eventImage instanceof TemporaryUploadedFile && $oldImageUrl !== $imageUrl) {
            $this->deleteStoredEventImage($oldImageUrl);
        }

        $this->isFormOpen = false;
        $this->editingEventId = null;
        $this->eventImage = null;
        $this->resetEventForm();
        $this->resetPage('eventsPage');

        Notification::make()
            ->title('Event saved')
            ->body($event->title . ' has been saved.')
            ->success()
            ->send();
    }

    private function glowColorFromAccent(string $accentColor): string
    {
        if (! preg_match('/^#([0-9A-Fa-f]{6})$/', $accentColor, $matches)) {
            return 'rgba(14,165,233,0.45)';
        }

        $hex = $matches[1];

        return sprintf(
            'rgba(%d,%d,%d,0.45)',
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        );
    }

    public function confirmDelete(int $eventId): void
    {
        $event = Event::query()->findOrFail($eventId);

        $this->deletingEventId = $eventId;
        $this->deletingEventTitle = $event->title;
        $this->isDeleteOpen = true;
    }

    public function closeDeleteModal(): void
    {
        $this->deletingEventId = null;
        $this->deletingEventTitle = '';
        $this->isDeleteOpen = false;
    }

    public function deleteEvent(): void
    {
        $event = Event::query()->findOrFail($this->deletingEventId);
        $title = $event->title;

        $event->delete();

        $this->closeDeleteModal();
        $this->resetPage('eventsPage');

        Notification::make()
            ->title('Event deleted')
            ->body($title . ' has been removed.')
            ->success()
            ->send();
    }

    public function events(): LengthAwarePaginator
    {
        $perPage = in_array($this->perPage, [5, 10, 25, 50], true) ? $this->perPage : 10;

        return Event::query()
            ->select([
                'id',
                'title',
                'slug',
                'subtitle',
                'venue',
                'city',
                'country_code',
                'genre',
                'start_date',
                'end_date',
                'date_display',
                'status',
                'is_sold_out',
                'image_url',
                'accent_color',
                'published_at',
            ])
            ->search($this->search)
            ->startingFrom($this->dateFrom)
            ->startingUntil($this->dateTo)
            ->orderBy('start_date')
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'eventsPage');
    }

    protected function rules(): array
    {
        return [
            'form.title' => ['required', 'string', 'max:255'],
            'form.slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('events', 'slug')->ignore($this->editingEventId),
            ],
            'form.subtitle' => ['nullable', 'string', 'max:255'],
            'form.venue' => ['required', 'string', 'max:255'],
            'form.city' => ['required', 'string', 'max:255'],
            'form.country_code' => ['required', 'string', 'size:2', 'regex:/^[A-Za-z]{2}$/'],
            'form.genre' => ['required', 'string', 'max:80', Rule::in(array_keys(self::EVENT_GENRES))],
            'form.start_date' => ['required', 'date'],
            'form.start_time' => ['nullable', 'date_format:H:i'],
            'form.end_date' => ['nullable', 'date', 'after_or_equal:form.start_date'],
            'form.date_display' => ['nullable', 'string', 'max:255'],
            'form.timezone' => ['required', 'string', 'max:64'],
            'form.status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'form.is_sold_out' => ['boolean'],
            'form.image_url' => ['nullable', 'string', 'max:2048'],
            'eventImage' => [
                Rule::requiredIf(! $this->editingEventId && blank($this->form['image_url'] ?? null)),
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp,gif',
                'max:' . self::EVENT_IMAGE_UPLOAD_MAX_KB,
            ],
            'form.accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'form.glow_color' => ['nullable', 'string', 'max:80'],
            'form.vendor_url' => ['nullable', 'url', 'max:2048'],
            'form.organizer_name' => ['nullable', 'string', 'max:255'],
            'form.organizer_url' => ['nullable', 'url', 'max:2048'],
            'form.spotify_embed_url' => ['nullable', 'url', 'max:2048'],
            'form.sections' => ['array', 'max:12'],
            'form.sections.*.section_key' => ['required', Rule::in(array_keys(self::EVENT_SECTION_TYPES))],
            'form.sections.*.title' => ['nullable', 'string', 'max:255'],
            'form.sections.*.content' => ['nullable', 'string', 'max:20000'],
            'form.sections.*.is_enabled' => ['boolean'],
            'form.meta_title' => ['nullable', 'string', 'max:255'],
            'form.meta_description' => ['nullable', 'string', 'max:1000'],
            'form.meta_keywords' => ['nullable', 'string', 'max:255'],
            'form.canonical_url' => ['nullable', 'url', 'max:255'],
            'form.og_image' => ['nullable', 'string', 'max:2048'],
        ];
    }

    private function resetEventForm(): void
    {
        $this->sectionToAdd = 'about';

        $this->form = [
            'title' => '',
            'slug' => '',
            'subtitle' => '',
            'venue' => '',
            'city' => '',
            'country_code' => '',
            'genre' => '',
            'start_date' => '',
            'start_time' => '',
            'end_date' => '',
            'date_display' => '',
            'timezone' => 'Asia/Kuala_Lumpur',
            'status' => 'published',
            'is_sold_out' => false,
            'image_url' => '',
            'accent_color' => '#0EA5E9',
            'glow_color' => '',
            'vendor_url' => '',
            'organizer_name' => 'Black Sky Enterprise',
            'organizer_url' => '',
            'spotify_embed_url' => '',
            'sections' => $this->eventSectionFormRows(),
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'canonical_url' => '',
            'og_image' => '',
        ];
    }

    /**
     * Store one optimized artwork for public use. Admins can upload original
     * 4K/8K artwork; the saved asset is capped for web delivery.
     */
    private function storeCompressedEventImage(TemporaryUploadedFile $upload, string $slug): string
    {
        @ini_set('memory_limit', '1024M');
        @set_time_limit(120);

        $sourcePath = $upload->getRealPath();
        $imageInfo = @getimagesize($sourcePath);

        if ($imageInfo === false) {
            throw ValidationException::withMessages([
                'eventImage' => 'The artwork file could not be read as an image.',
            ]);
        }

        [$sourceWidth, $sourceHeight, $imageType] = $imageInfo;
        $source = match ($imageType) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($sourcePath),
            IMAGETYPE_GIF => @imagecreatefromgif($sourcePath),
            default => false,
        };

        if (! $source) {
            throw ValidationException::withMessages([
                'eventImage' => 'The artwork format is not supported. Please upload JPG, PNG, WEBP, or GIF.',
            ]);
        }

        $scale = min(1, self::EVENT_IMAGE_MAX_EDGE / max($sourceWidth, $sourceHeight));
        $targetWidth = max(1, (int) round($sourceWidth * $scale));
        $targetHeight = max(1, (int) round($sourceHeight * $scale));
        $target = imagecreatetruecolor($targetWidth, $targetHeight);

        imagealphablending($target, false);
        imagesavealpha($target, true);
        imagefilledrectangle($target, 0, 0, $targetWidth, $targetHeight, imagecolorallocatealpha($target, 0, 0, 0, 127));
        imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

        $safeSlug = Str::slug($slug) ?: 'event';
        $relativePath = 'events/' . $safeSlug . '-' . Str::uuid() . '.webp';

        Storage::disk('public')->makeDirectory('events');

        $stored = @imagewebp($target, Storage::disk('public')->path($relativePath), self::EVENT_IMAGE_QUALITY);

        imagedestroy($source);
        imagedestroy($target);

        if (! $stored) {
            throw ValidationException::withMessages([
                'eventImage' => 'The artwork could not be compressed. Please try another image.',
            ]);
        }

        return Storage::disk('public')->url($relativePath);
    }

    private function deleteStoredEventImage(?string $imageUrl): void
    {
        if (blank($imageUrl)) {
            return;
        }

        $path = parse_url($imageUrl, PHP_URL_PATH);

        if (! is_string($path) || ! str_contains($path, '/storage/events/')) {
            return;
        }

        Storage::disk('public')->delete(Str::after($path, '/storage/'));
    }

    private function normalizeSpotifyEmbedUrl(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $parts = parse_url(trim($url));
        $host = Str::lower((string) ($parts['host'] ?? ''));
        $path = trim((string) ($parts['path'] ?? ''), '/');

        if (! in_array($host, ['open.spotify.com', 'www.open.spotify.com'], true) || $path === '') {
            throw ValidationException::withMessages([
                'form.spotify_embed_url' => 'Use a Spotify URL from open.spotify.com.',
            ]);
        }

        $segments = explode('/', $path);

        if (($segments[0] ?? null) === 'embed') {
            $type = $segments[1] ?? '';
            $id = $segments[2] ?? '';
        } else {
            $type = $segments[0] ?? '';
            $id = $segments[1] ?? '';
        }

        if (! in_array($type, ['artist', 'album', 'playlist', 'track', 'show', 'episode'], true) || $id === '') {
            throw ValidationException::withMessages([
                'form.spotify_embed_url' => 'Use a Spotify artist, album, playlist, track, show, or episode URL.',
            ]);
        }

        return 'https://open.spotify.com/embed/' . $type . '/' . $id . '?utm_source=generator';
    }

    /**
     * @param array<string, mixed> $event
     */
    private function defaultMetaTitle(array $event): string
    {
        return Str::limit($event['title'] . ' | Black Sky Enterprise', 255, '');
    }

    /**
     * @param array<string, mixed> $event
     */
    private function defaultMetaDescription(array $event): string
    {
        $parts = array_filter([
            $event['subtitle'] ?? null,
            filled($event['venue'] ?? null) ? 'Live at ' . $event['venue'] : null,
            filled($event['city'] ?? null) ? $event['city'] : null,
        ]);

        return Str::limit(implode(' - ', $parts) ?: $event['title'], 1000, '');
    }

    /**
     * @param array<int, array<string, mixed>> $sections
     */
    private function syncEventSections(Event $event, array $sections): void
    {
        $event->sections()->delete();

        foreach (array_values($sections) as $index => $section) {
            $content = trim((string) ($section['content'] ?? ''));

            if ($content === '') {
                continue;
            }

            $sectionKey = (string) ($section['section_key'] ?? 'custom');
            $title = trim((string) ($section['title'] ?? ''));

            if (! array_key_exists($sectionKey, self::EVENT_SECTION_TYPES)) {
                $sectionKey = 'custom';
            }

            $event->sections()->create([
                'section_key' => $sectionKey,
                'title' => $title !== '' ? $title : self::EVENT_SECTION_TYPES[$sectionKey],
                'content' => $content,
                'sort_order' => $index,
                'is_enabled' => (bool) ($section['is_enabled'] ?? true),
            ]);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function eventSectionFormRows(?Event $event = null): array
    {
        $rows = $event?->relationLoaded('sections')
            ? $event->sections
                ->map(fn ($section): array => [
                    'id' => $section->id,
                    'section_key' => $section->section_key,
                    'title' => $section->title,
                    'content' => $section->content,
                    'is_enabled' => $section->is_enabled,
                ])
                ->values()
                ->all()
            : [];

        $existingKeys = collect($rows)
            ->pluck('section_key')
            ->filter()
            ->all();

        foreach (self::CORE_EVENT_SECTION_KEYS as $sectionKey) {
            if (in_array($sectionKey, $existingKeys, true)) {
                continue;
            }

            $rows[] = [
                'id' => null,
                'section_key' => $sectionKey,
                'title' => self::EVENT_SECTION_TYPES[$sectionKey],
                'content' => $this->defaultSectionContent($sectionKey, $event),
                'is_enabled' => true,
            ];
        }

        return array_values($rows);
    }

    private function defaultSectionContent(string $sectionKey, ?Event $event = null): string
    {
        if ($sectionKey === 'about' && filled($event?->subtitle)) {
            return (string) $event->subtitle;
        }

        return '';
    }
}
