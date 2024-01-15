<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;


use Filament\Tables\Concerns\CanBeHidden;
use Filament\Tables\Concerns\HasTable;
use Filament\Tables\Table;
use Livewire\Component;





class ListOrders extends ListRecords
{

    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            

        ];
    }



}
