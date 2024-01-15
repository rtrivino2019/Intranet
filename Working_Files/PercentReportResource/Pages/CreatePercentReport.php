<?php

namespace App\Filament\Resources\PercentReportResource\Pages;

use App\Filament\Resources\PercentReportResource;
use App\Models\Restaurant;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePercentReport extends CreateRecord
{
    protected static string $resource = PercentReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array {
        $data['user_id'] = auth()->id();
        $data['sales']=$data['credit_card']+$data['cash']+$data['online']+$data['uber']+$data['grubhub']+$data['doordash'];
        // $data['is_deposit']=true;



        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
