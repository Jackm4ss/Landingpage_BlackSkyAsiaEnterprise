<?php

namespace App\Filament\Resources\PortfolioWorkResource\Pages;

use App\Filament\Resources\PortfolioWorkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPortfolioWorks extends ListRecords
{
    protected static string $resource = PortfolioWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
