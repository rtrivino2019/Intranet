<?php

namespace App\Filament\Static\Resources\CategoryFoodResource\Pages;

use App\Filament\Static\Resources\CategoryFoodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoryFood extends EditRecord
{
    protected static string $resource = CategoryFoodResource::class;

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
