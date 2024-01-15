<?php

namespace App\Filament\Resources\PercentReportResource\Widgets;

use App\Models\Percentage;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\PercentReportResource\Pages\ListPercentReports;


class PercentReport extends BaseWidget
{


    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListPercentReports::class;

    }

    protected function getStats(): array
    {
        $Data = Trend::model(Percentage::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();


        return [
            Stat::make('Records', $this->getPageTableQuery()->count())
            ->chart(
                $Data
                    ->map(fn (TrendValue $value) => $value->aggregate)
                    ->toArray()
            ),
            Stat::make('Sales', $this->getPageTableQuery()->sum('sales')),


            // Stat::make('% Alcohol', number_format($this->getPageTableQuery()->sum('%alcohol'), 2)),
            // Stat::make('% Others', number_format($this->getPageTableQuery()->sum('%others'), 2)),
            // Stat::make('% Payroll', number_format($this->getPageTableQuery()->sum('%payroll'), 2)),
            // Stat::make('% Utilities', number_format($this->getPageTableQuery()->sum('%utilities'), 2)),




        ];
    }



    protected function data()
    {
        $n1=100;
        $n2=200;
        return[
            'result'=>$n1/$n2
        ];
    }

}
