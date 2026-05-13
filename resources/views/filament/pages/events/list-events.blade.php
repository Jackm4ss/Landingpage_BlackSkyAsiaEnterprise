@php
    $events = $this->events();
@endphp

<x-filament-panels::page class="bsa-events-page">
    <div class="bsa-events">
        <section class="bsa-events-hero">
            <div>
                <p class="bsa-eyebrow">Event Management</p>
                <h1>Events List View</h1>
                <p class="bsa-muted">Manage all events and concerts from one focused list.</p>
            </div>
            <div class="bsa-events-total">
                <span>Total Events</span>
                <strong>{{ number_format($events->total()) }}</strong>
            </div>
        </section>

        <section class="bsa-events-card" aria-label="Events list">
            <div class="bsa-events-card-head">
                <div>
                    <p class="bsa-eyebrow">Upcoming Events & Shows</p>
                    <h2>Published Event Catalog</h2>
                </div>
                <button type="button" class="bsa-events-add" wire:click="openCreate">
                    <x-heroicon-o-plus />
                    <span>Add Event</span>
                </button>
            </div>

            <div class="bsa-events-toolbar" aria-label="Event filters">
                <label class="bsa-events-field bsa-events-field-search">
                    <span>Search</span>
                    <input
                        id="event-search"
                        name="search"
                        type="search"
                        wire:model.live.debounce.350ms="search"
                        placeholder="Search events, venue, city..."
                        autocomplete="off"
                    >
                </label>

                <div class="bsa-events-field bsa-events-field-range">
                    <span>Date Range</span>
                    <div
                        wire:ignore
                        data-bsa-date-range-picker
                        data-from-input="event-date-from"
                        data-to-input="event-date-to"
                    ></div>
                    <input id="event-date-from" name="date_from" type="hidden" wire:model.live="dateFrom">
                    <input id="event-date-to" name="date_to" type="hidden" wire:model.live="dateTo">
                </div>

                <label class="bsa-events-field bsa-events-field-show">
                    <span>Show</span>
                    <select id="event-per-page" name="per_page" wire:model.live="perPage">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </label>

                <button type="button" class="bsa-events-clear" wire:click="clearFilters">
                    <x-heroicon-o-x-mark />
                    <span>Clear</span>
                </button>
            </div>

            <div class="bsa-events-table-wrap">
                <table class="bsa-events-table">
                    <thead>
                        <tr>
                            <th scope="col">Event</th>
                            <th scope="col">Date</th>
                            <th scope="col">Venue</th>
                            <th scope="col">Genre</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="bsa-events-actions-head">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                            <tr wire:key="event-row-{{ $event->id }}">
                                <td>
                                    <div class="bsa-event-product">
                                        <img
                                            src="{{ $event->image_url }}"
                                            alt="{{ $event->title }} event artwork"
                                            loading="lazy"
                                            width="68"
                                            height="46"
                                        >
                                        <div>
                                            <strong>{{ $event->title }}</strong>
                                            <span>{{ $event->subtitle }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="bsa-event-date">
                                        <strong>{{ $event->admin_date_label }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="bsa-event-venue">
                                        <strong>{{ $event->venue }}</strong>
                                        <span>{{ $event->city }}, {{ $event->country_code }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="bsa-event-chip" style="--event-accent: {{ $event->accent_color }}">
                                        {{ $event->genre }}
                                    </span>
                                </td>
                                <td>
                                    <span @class([
                                        'bsa-event-status',
                                        'bsa-event-status-sold-out' => $event->is_sold_out,
                                        'bsa-event-status-published' => ! $event->is_sold_out && $event->status === 'published',
                                    ])>
                                        {{ $event->admin_status_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="bsa-events-actions">
                                        <button type="button" wire:click="openEdit({{ $event->id }})" aria-label="Edit {{ $event->title }}">
                                            <x-heroicon-o-pencil-square />
                                        </button>
                                        <button type="button" class="bsa-events-danger-action" wire:click="confirmDelete({{ $event->id }})" aria-label="Delete {{ $event->title }}">
                                            <x-heroicon-o-trash />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="bsa-events-empty">
                                        <div>
                                            <x-heroicon-o-calendar-days />
                                        </div>
                                        <strong>No events found</strong>
                                        <span>Events from the public Upcoming Events & Shows catalog will appear here after they are added.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bsa-events-footer">
                <p>
                    Showing {{ $events->firstItem() ?? 0 }} to {{ $events->lastItem() ?? 0 }} of {{ $events->total() }} entries
                </p>

                @if ($events->lastPage() > 1)
                    <nav class="bsa-events-pagination" aria-label="Events pagination">
                        <button type="button" wire:click="previousPage('eventsPage')" @disabled($events->onFirstPage())>
                            <x-heroicon-o-chevron-left />
                        </button>

                        @foreach (range(1, $events->lastPage()) as $page)
                            <button
                                type="button"
                                wire:click="gotoPage({{ $page }}, 'eventsPage')"
                                @class(['is-active' => $events->currentPage() === $page])
                                aria-label="Go to page {{ $page }}"
                            >
                                {{ $page }}
                            </button>
                        @endforeach

                        <button type="button" wire:click="nextPage('eventsPage')" @disabled(! $events->hasMorePages())>
                            <x-heroicon-o-chevron-right />
                        </button>
                    </nav>
                @endif
            </div>
        </section>

        @if ($isFormOpen)
            <div class="bsa-events-modal-backdrop" wire:key="event-form-modal">
                <section class="bsa-events-modal" role="dialog" aria-modal="true" aria-labelledby="event-form-title">
                    <header class="bsa-events-modal-head">
                        <div>
                            <p class="bsa-eyebrow">{{ $editingEventId ? 'Edit Event' : 'Create Event' }}</p>
                            <h2 id="event-form-title">{{ $editingEventId ? 'Update Event' : 'Add New Event' }}</h2>
                        </div>
                        <button type="button" class="bsa-events-modal-close" wire:click="closeEventForm" aria-label="Close event form">
                            <x-heroicon-o-x-mark />
                        </button>
                    </header>

                    <form class="bsa-events-form" wire:submit.prevent="saveEvent" enctype="multipart/form-data">
                        <div class="bsa-events-form-intro">
                            <strong>{{ $editingEventId ? 'Update the main event details.' : 'Add the event details admins use every day.' }}</strong>
                            <span>Fill the event basics and upload the artwork. The public event card is prepared automatically.</span>
                        </div>

                        <div class="bsa-events-form-grid bsa-events-form-grid-simple">
                            <label class="bsa-events-field">
                                <span>Event Name</span>
                                <input id="event-form-title-input" name="title" type="text" wire:model="form.title" placeholder="IGNITE FESTIVAL">
                                @error('form.title') <em>{{ $message }}</em> @enderror
                            </label>

                            <label class="bsa-events-field">
                                <span>Genre</span>
                                <select id="event-form-genre" name="genre" wire:model="form.genre">
                                    <option value="">Select genre</option>
                                    @foreach ($this->genreOptions() as $genreValue => $genreLabel)
                                        <option value="{{ $genreValue }}">{{ $genreLabel }}</option>
                                    @endforeach
                                </select>
                                @error('form.genre') <em>{{ $message }}</em> @enderror
                            </label>

                            <label class="bsa-events-field bsa-events-field-wide">
                                <span>Short Description</span>
                                <input id="event-form-subtitle" name="subtitle" type="text" wire:model="form.subtitle" placeholder="A short line for the event card">
                                @error('form.subtitle') <em>{{ $message }}</em> @enderror
                            </label>

                            <div class="bsa-events-field bsa-events-field-wide">
                                <span>Event Date Range</span>
                                <div
                                    wire:ignore
                                    data-bsa-date-range-picker
                                    data-from-input="event-form-start-date"
                                    data-to-input="event-form-end-date"
                                ></div>
                                <input id="event-form-start-date" name="start_date" type="hidden" wire:model.live="form.start_date">
                                <input id="event-form-end-date" name="end_date" type="hidden" wire:model.live="form.end_date">
                                @error('form.start_date') <em>{{ $message }}</em> @enderror
                                @error('form.end_date') <em>{{ $message }}</em> @enderror
                            </div>

                            <label class="bsa-events-field">
                                <span>Venue</span>
                                <input id="event-form-venue" name="venue" type="text" wire:model="form.venue" placeholder="Venue name">
                                @error('form.venue') <em>{{ $message }}</em> @enderror
                            </label>

                            <label class="bsa-events-field">
                                <span>City</span>
                                <input id="event-form-city" name="city" type="text" wire:model="form.city" placeholder="Kuala Lumpur">
                                @error('form.city') <em>{{ $message }}</em> @enderror
                            </label>

                            <div class="bsa-events-field">
                                <span>Country</span>
                                <div
                                    wire:ignore
                                    data-bsa-country-dropdown
                                    data-country-input="event-form-country"
                                ></div>
                                <input id="event-form-country" name="country_code" type="hidden" wire:model.live="form.country_code">
                                @error('form.country_code') <em>{{ $message }}</em> @enderror
                            </div>

                            <label class="bsa-events-field">
                                <span>Ticket Link</span>
                                <input id="event-form-vendor" name="vendor_url" type="url" wire:model="form.vendor_url" placeholder="https://...">
                                @error('form.vendor_url') <em>{{ $message }}</em> @enderror
                            </label>

                            <label class="bsa-events-field">
                                <span>Visibility</span>
                                <select id="event-form-status" name="status" wire:model="form.status">
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                    <option value="archived">Archived</option>
                                </select>
                                @error('form.status') <em>{{ $message }}</em> @enderror
                            </label>

                            <label class="bsa-events-toggle">
                                <input id="event-form-sold-out" name="is_sold_out" type="checkbox" wire:model="form.is_sold_out">
                                <span>Sold out</span>
                            </label>

                            <div class="bsa-events-upload bsa-events-field-wide">
                                <label class="bsa-events-field">
                                    <span>Artwork Upload</span>
                                    <input
                                        id="event-form-image-file"
                                        name="event_image"
                                        type="file"
                                        wire:model="eventImage"
                                        accept="image/jpeg,image/png,image/webp,image/gif"
                                    >
                                    @error('eventImage') <em>{{ $message }}</em> @enderror
                                </label>

                                <div class="bsa-events-upload-preview">
                                    @if ($eventImage)
                                        <img src="{{ $eventImage->temporaryUrl() }}" alt="Selected event artwork preview">
                                        <span>Optimized on save</span>
                                    @elseif (filled($form['image_url'] ?? null))
                                        <img src="{{ $form['image_url'] }}" alt="Current event artwork preview">
                                        <span>Current artwork</span>
                                    @else
                                        <div>
                                            <x-heroicon-o-photo />
                                            <span>JPG, PNG, WEBP, GIF</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <details class="bsa-events-form-section bsa-events-advanced">
                            <summary>
                                <span>More Options</span>
                                <small>Custom page URL, display date, color, and search preview.</small>
                            </summary>

                            <div class="bsa-events-form-grid">
                                <label class="bsa-events-field">
                                    <span>Page URL</span>
                                    <input id="event-form-slug" name="slug" type="text" wire:model="form.slug" placeholder="auto-from-title">
                                    @error('form.slug') <em>{{ $message }}</em> @enderror
                                </label>

                                <label class="bsa-events-field">
                                    <span>Date Display</span>
                                    <input id="event-form-date-display" name="date_display" type="text" wire:model="form.date_display" placeholder="JUL 19-21, 2026">
                                    @error('form.date_display') <em>{{ $message }}</em> @enderror
                                </label>

                                <label class="bsa-events-field">
                                    <span>Timezone</span>
                                    <input id="event-form-timezone" name="timezone" type="text" wire:model="form.timezone" placeholder="Asia/Kuala_Lumpur">
                                    @error('form.timezone') <em>{{ $message }}</em> @enderror
                                </label>

                                <label class="bsa-events-field">
                                    <span>Accent Color</span>
                                    <input id="event-form-accent" name="accent_color" type="color" wire:model="form.accent_color">
                                    @error('form.accent_color') <em>{{ $message }}</em> @enderror
                                </label>

                                <label class="bsa-events-field">
                                    <span>Search Title</span>
                                    <input id="event-form-meta-title" name="meta_title" type="text" wire:model="form.meta_title" placeholder="Auto if blank">
                                    @error('form.meta_title') <em>{{ $message }}</em> @enderror
                                </label>

                                <label class="bsa-events-field">
                                    <span>Preferred URL</span>
                                    <input id="event-form-canonical" name="canonical_url" type="url" wire:model="form.canonical_url" placeholder="Auto if blank">
                                    @error('form.canonical_url') <em>{{ $message }}</em> @enderror
                                </label>

                                <label class="bsa-events-field bsa-events-field-wide">
                                    <span>Search Description</span>
                                    <textarea id="event-form-meta-description" name="meta_description" rows="3" wire:model="form.meta_description" placeholder="Auto if blank"></textarea>
                                    @error('form.meta_description') <em>{{ $message }}</em> @enderror
                                </label>

                                <label class="bsa-events-field bsa-events-field-wide">
                                    <span>Search Keywords</span>
                                    <input id="event-form-meta-keywords" name="meta_keywords" type="text" wire:model="form.meta_keywords" placeholder="concert, event, malaysia">
                                    @error('form.meta_keywords') <em>{{ $message }}</em> @enderror
                                </label>
                            </div>
                        </details>

                        <footer class="bsa-events-modal-footer">
                            <button type="button" class="bsa-events-modal-secondary" wire:click="closeEventForm">Cancel</button>
                            <button type="submit" class="bsa-events-modal-primary" wire:loading.attr="disabled" wire:target="saveEvent">
                                <x-heroicon-o-check />
                                <span>{{ $editingEventId ? 'Save Changes' : 'Create Event' }}</span>
                            </button>
                        </footer>
                    </form>
                </section>
            </div>
        @endif

        @if ($isDeleteOpen)
            <div class="bsa-events-modal-backdrop" wire:key="event-delete-modal">
                <section class="bsa-events-delete-modal" role="dialog" aria-modal="true" aria-labelledby="event-delete-title">
                    <header>
                        <p class="bsa-eyebrow">Delete Event</p>
                        <h2 id="event-delete-title">Remove {{ $deletingEventTitle }}</h2>
                    </header>
                    <p>This action removes the event from the admin catalog and public API.</p>
                    <footer class="bsa-events-modal-footer">
                        <button type="button" class="bsa-events-modal-secondary" wire:click="closeDeleteModal">Cancel</button>
                        <button type="button" class="bsa-events-modal-danger" wire:click="deleteEvent">
                            <x-heroicon-o-trash />
                            <span>Delete Event</span>
                        </button>
                    </footer>
                </section>
            </div>
        @endif
    </div>
</x-filament-panels::page>
