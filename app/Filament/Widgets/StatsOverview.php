<?php

namespace App\Filament\Widgets;
use App\Models\User;


use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{


    protected function getStats(): array
    {
        return [
            Stat::make('Total Employees', User::all()->count())
                ->description('All Restaurants')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Total Active Employees', User::where('status','1')->count())
                ->description('All Restaurants')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart([17, 16, 14, 15, 14, 13, 12])
                ->color('danger'),
            Stat::make('Total Inactive Employees', User::where('status','0')->count())
                ->description('All Restaurants')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),
        ];

    }

   

}

