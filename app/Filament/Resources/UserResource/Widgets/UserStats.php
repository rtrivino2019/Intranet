<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use App\Filament\Resources\UserResource\Pages\ListUsers;

class UserStats extends BaseWidget
{
    

    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListUsers::class;
    }

    protected function getStats(): array
    {
        $reportData = Trend::model(User::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();



        return [

            Stat::make('Total Active Employees', $this->getPageTableQuery()->where('status','1')->count())
            ->chart(
                $reportData
                    ->map(fn (TrendValue $value) => $value->aggregate)
                    ->toArray()
            ),
            Stat::make('Total Non-Active Employees', $this->getPageTableQuery()->where('status','0')->count())
            ->chart(
                $reportData
                    ->map(fn (TrendValue $value) => $value->aggregate)
                    ->toArray()
            ),

        ];

    }
}
