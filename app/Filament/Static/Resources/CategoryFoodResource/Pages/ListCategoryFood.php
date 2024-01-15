<?php

namespace App\Filament\Static\Resources\CategoryFoodResource\Pages;

use App\Filament\Static\Resources\CategoryFoodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoryFood extends ListRecords
{
    protected static string $resource = CategoryFoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
