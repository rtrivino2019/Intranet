<?php

namespace App\Filament\Resources\PercentReportResource\Pages;

use App\Filament\Resources\PercentReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPercentReport extends EditRecord
{
    protected static string $resource = PercentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
    $data['sales']=$data['credit_card']+$data['cash']+$data['online']+$data['uber']+$data['grubhub']+$data['doordash'];
    $data['is_deposit']=true;
    return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
