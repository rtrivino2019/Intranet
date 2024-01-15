<?php

namespace App\Filament\Resources\PercentReportResource\Pages;

use App\Filament\Resources\PercentReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPercentReports extends ListRecords
{
    protected static string $resource = PercentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return PercentReportResource::getWidgets();

    }
}
