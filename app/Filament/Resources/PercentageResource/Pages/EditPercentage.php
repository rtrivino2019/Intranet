<?php

namespace App\Filament\Resources\PercentageResource\Pages;

use App\Filament\Resources\PercentageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPercentage extends EditRecord
{
    protected static string $resource = PercentageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
