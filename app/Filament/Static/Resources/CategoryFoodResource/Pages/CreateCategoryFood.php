<?php

namespace App\Filament\Static\Resources\CategoryFoodResource\Pages;

use App\Filament\Static\Resources\CategoryFoodResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoryFood extends CreateRecord
{
    protected static string $resource = CategoryFoodResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
