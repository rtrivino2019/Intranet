<?php
namespace App\Filament\Resources\PercentageResource\Pages;
//use app\Models\Report;
use App\Filament\Resources\PercentageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePercentage extends CreateRecord
{
    protected static string $resource = PercentageResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array {
        $data['user_id'] = auth()->id();
        $data['is_deposit']=false;
        //$data['restaurant_id'] = auth()->user()->restaurant_id;
       
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make()
    //         ->label('New Supplier'),
            

    //     ];
    // }
}
