<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReport extends EditRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        //$data['is_deposit']=false;
        $data['user_id'] = auth()->id();
    $data['sales']=$data['credit_card']+$data['cash']+$data['online']+$data['uber']+$data['grubhub']+$data['doordash'];
    //$data['%food'] = $data['amount']/$data['sales'];
    // $data['%alcohol'] = $data['food']/$data['sales'];
    // $data['%others'] = $data['others']/$data['sales'];
    // $data['%payroll'] = $data['payroll']/$data['sales'];
    // $data['%utilities'] = $data['utilities']/$data['sales'];
    return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }



}
