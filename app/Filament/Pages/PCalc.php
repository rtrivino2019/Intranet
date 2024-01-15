<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;



class PCalc extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'Reports & Percentages';
    protected static ?string $title = 'Percentages';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.p-calc';

    protected static bool $shouldRegisterNavigation = true;

    
    

}
