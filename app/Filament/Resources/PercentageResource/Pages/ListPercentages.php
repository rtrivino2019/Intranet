<?php

namespace App\Filament\Resources\PercentageResource\Pages;

use App\Filament\Resources\PercentageResource;
use App\Models\Report;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListPercentages extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = PercentageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            

        ];
    }

    protected function getHeaderWidgets(): array
    {
        return PercentageResource::getWidgets();

    }



    public function getTabs(): array
    {
        return [
            null => ListRecords\Tab::make('All'),
            'Food' => ListRecords\Tab::make()->query(fn ($query) => $query->where('type', 'food')),
            'Alcohol' => ListRecords\Tab::make()->query(fn ($query) => $query->where('type', 'alcohol')),
            'Others' => ListRecords\Tab::make()->query(fn ($query) => $query->where('type', 'others')),
            'Payroll' => ListRecords\Tab::make()->query(fn ($query) => $query->where('type', 'payroll')),
            'Utilities' => ListRecords\Tab::make()->query(fn ($query) => $query->where('type', 'utilities')),
        ];


    }


}
