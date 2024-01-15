<?php

namespace App\Filament\Static\Resources\ProductResource\Pages;

use App\Filament\Static\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Enums\CategoryFood;


class ListProducts extends ListRecords
{
    
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            null => ListRecords\Tab::make('All'),
            'vegetales' => ListRecords\Tab::make()->query(fn ($query) => $query->where('categoryfood', 'Vegetales')),
            'carnes' => ListRecords\Tab::make()->query(fn ($query) => $query->where('categoryfood', 'Carnes')),
            'papeleria' => ListRecords\Tab::make()->query(fn ($query) => $query->where('categoryfood', 'Papeleria')),
            'nevera' => ListRecords\Tab::make()->query(fn ($query) => $query->where('categoryfood', 'Nevera')),
            'despensa' => ListRecords\Tab::make()->query(fn ($query) => $query->where('categoryfood', 'Despensa')),
            'cleaners' => ListRecords\Tab::make()->query(fn ($query) => $query->where('categoryfood', 'Cleaners')),
            'sodas/otros' => ListRecords\Tab::make()->query(fn ($query) => $query->where('categoryfood', 'Sodas/Otros')),
        ];
    }
}
