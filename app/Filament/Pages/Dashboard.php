<?php

namespace App\Filament\Pages;

use App\Models\BlogPost;
use App\Models\Event;
use App\Models\PortfolioWork;
use App\Models\User;
use App\Support\RegistrationSourceMeta;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.dashboard';

    protected ?string $maxContentWidth = 'full';

    public function getHeading(): string | Htmlable
    {
        return '';
    }

    protected function getViewData(): array
    {
        $trafficSources = $this->trafficSources();
        $eventsCount = Event::query()->count();
        $articlesCount = BlogPost::query()->count();
        $worksCount = PortfolioWork::query()->count();
        $registeredCount = $this->registeredUsersQuery()->count();

        return [
            'updatedAt' => now()->format('d/m/Y'),
            'stats' => [
                [
                    'label' => 'Total Event',
                    'value' => $eventsCount,
                    'delta' => $eventsCount > 0 ? 'Live' : 'No data',
                    'icon' => 'heroicon-o-calendar-days',
                    'tone' => 'blue',
                ],
                [
                    'label' => 'Total Articles',
                    'value' => $articlesCount,
                    'delta' => $articlesCount > 0 ? 'Live' : 'No data',
                    'icon' => 'heroicon-o-document-text',
                    'tone' => 'cyan',
                ],
                [
                    'label' => 'Total Registered',
                    'value' => $registeredCount,
                    'delta' => $registeredCount > 0 ? 'Live' : 'No data',
                    'icon' => 'heroicon-o-users',
                    'tone' => 'green',
                ],
                [
                    'label' => 'Total Work',
                    'value' => $worksCount,
                    'delta' => $worksCount > 0 ? 'Live' : 'No data',
                    'icon' => 'heroicon-o-briefcase',
                    'tone' => 'violet',
                ],
            ],
            'trafficSources' => $trafficSources,
            'trafficTotal' => array_sum(array_column($trafficSources, 'value')),
            'trafficGradient' => $this->donutGradient($trafficSources),
            'topCountries' => $this->topCountries(),
        ];
    }

    /**
     * @return array<int, array{source: string, label: string, value: int, percentage: int, color: string, icon: string}>
     */
    private function trafficSources(): array
    {
        /** @var Collection<int, object{registration_source: string, aggregate: int}> $rows */
        $rows = $this->registeredUsersQuery()
            ->selectRaw('registration_source, COUNT(*) as aggregate')
            ->whereNotNull('registration_source')
            ->groupBy('registration_source')
            ->orderByDesc('aggregate')
            ->limit(6)
            ->get();

        if ($rows->isEmpty()) {
            return [];
        }

        $total = (int) $rows->sum('aggregate');
        $usedPercentage = 0;

        return $rows
            ->values()
            ->map(function (object $row, int $index) use ($rows, $total, &$usedPercentage): array {
                $source = (string) $row->registration_source;
                $value = (int) $row->aggregate;
                $percentage = $index === $rows->count() - 1
                    ? max(0, 100 - $usedPercentage)
                    : (int) round(($value / max(1, $total)) * 100);
                $usedPercentage += $percentage;
                $meta = RegistrationSourceMeta::for($source);

                return [
                    'source' => $source,
                    'label' => $meta['label'],
                    'value' => $value,
                    'percentage' => $percentage,
                    'color' => $meta['color'],
                    'icon' => $meta['icon'],
                ];
            })
            ->all();
    }

    /**
     * @return array<int, array{code: string, country: string, value: int, delta: string, trend: string}>
     */
    private function topCountries(): array
    {
        /** @var Collection<int, object{registration_country_code: string, aggregate: int}> $rows */
        $rows = $this->registeredUsersQuery()
            ->selectRaw('registration_country_code, COUNT(*) as aggregate')
            ->whereNotNull('registration_country_code')
            ->groupBy('registration_country_code')
            ->orderByDesc('aggregate')
            ->limit(6)
            ->get();

        if ($rows->isEmpty()) {
            return [];
        }

        $total = (int) $rows->sum('aggregate');

        return $rows
            ->map(function (object $row) use ($total): array {
                $code = Str::upper((string) $row->registration_country_code);
                $value = (int) $row->aggregate;

                return [
                    'code' => $code,
                    'country' => \Locale::getDisplayRegion('-'.$code, 'en') ?: $code,
                    'value' => $value,
                    'delta' => (int) round(($value / max(1, $total)) * 100).'%',
                    'trend' => 'up',
                ];
            })
            ->values()
            ->all();
    }

    /**
     * The dashboard only tracks public registrations; seeded/admin users keep
     * attribution null so they never pollute acquisition analytics.
     *
     * @return Builder<User>
     */
    private function registeredUsersQuery(): Builder
    {
        return User::query()->whereNotNull('registration_source');
    }

    /**
     * @param  array<int, array{percentage: int, color: string}>  $segments
     */
    private function donutGradient(array $segments): string
    {
        if ($segments === []) {
            return 'conic-gradient(rgba(148, 163, 184, 0.2) 0% 100%)';
        }

        $cursor = 0;
        $stops = [];

        foreach ($segments as $segment) {
            $next = $cursor + $segment['percentage'];
            $stops[] = "{$segment['color']} {$cursor}% {$next}%";
            $cursor = $next;
        }

        return 'conic-gradient(' . implode(', ', $stops) . ')';
    }
}
