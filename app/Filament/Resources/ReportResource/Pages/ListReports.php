<?php

namespace App\Filament\Resources\ReportResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ReportResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;
    use ExposesTableToWidgets;

    protected function getHeaderWidgets(): array
    {
        return ReportResource::getWidgets();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
