<?php

namespace App\Filament\Resources\PortfolioWorkResource\Pages;

use App\Filament\Resources\PortfolioWorkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPortfolioWork extends EditRecord
{
    protected static string $resource = PortfolioWorkResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return PortfolioWorkResource::normalizeFormData($data);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
