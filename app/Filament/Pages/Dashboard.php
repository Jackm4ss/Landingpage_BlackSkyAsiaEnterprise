<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

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

        return [
            'updatedAt' => now()->format('d/m/Y'),
            'stats' => [
                [
                    'label' => 'Total Event',
                    'value' => 0,
                    'delta' => 'No data',
                    'icon' => 'heroicon-o-calendar-days',
                    'tone' => 'blue',
                ],
                [
                    'label' => 'Total Articles',
                    'value' => 0,
                    'delta' => 'No data',
                    'icon' => 'heroicon-o-document-text',
                    'tone' => 'cyan',
                ],
                [
                    'label' => 'Total Registered',
                    'value' => 0,
                    'delta' => 'No data',
                    'icon' => 'heroicon-o-users',
                    'tone' => 'green',
                ],
                [
                    'label' => 'Total Work',
                    'value' => 0,
                    'delta' => 'No data',
                    'icon' => 'heroicon-o-briefcase',
                    'tone' => 'violet',
                ],
            ],
            'trafficSources' => $trafficSources,
            'trafficTotal' => array_sum(array_column($trafficSources, 'value')),
            'trafficGradient' => $this->donutGradient($trafficSources),
            'topCountries' => [],
        ];
    }

    /**
     * @return array<int, array{label: string, value: int, percentage: int, color: string}>
     */
    private function trafficSources(): array
    {
        return [];
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
