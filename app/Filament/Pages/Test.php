<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Test extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.test';
    protected static bool $shouldRegisterNavigation = false;// This is used to show on the side of the navigation
}
