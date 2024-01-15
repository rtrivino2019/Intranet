<?php

namespace App\Filament\Resources\ReportResource\Widgets;

use App\Models\Report;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\ReportResource\Pages\ListReports;


class ReportStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListReports::class;
    }
    protected function getStats(): array
    {
        $reportData = Trend::model(Report::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();

        return [
            Stat::make('Records', $this->getPageTableQuery()->count())
            ->chart(
                $reportData
                    ->map(fn (TrendValue $value) => $value->aggregate)
                    ->toArray()
            ),
            Stat::make('Sales', $this->getPageTableQuery()->sum('sales')),


        ];

    }

}
