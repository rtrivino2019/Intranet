<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Http\Livewire\ReplicateOrderComponent;
use Illuminate\Support\Facades\Auth;

class ReplicateOrderPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static string $view = 'filament.pages.replicate-order-page';

    protected static ?string $navigationGroup = 'Inventory';
    protected static ?string $title = 'Replicate Order';
    protected static ?int $navigationSort = 2;

    public static $name = 'Replicate Order';



    public static $icon = 'heroicon-o-duplicate';

    protected static bool $shouldRegisterNavigation = false;
    

 





}
