<?php

namespace App\Filament\Employee\Resources\ScheduleResource\Pages;

use App\Filament\Employee\Resources\ScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use App\Enums\DaysOfTheWeek;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where(function ($query) {
                $query->orWhere('day_of_week', DaysOfTheWeek::Sunday)
                    ->orWhere('day_of_week', DaysOfTheWeek::Monday)
                    ->orWhere('day_of_week', DaysOfTheWeek::Tuesday)
                    ->orWhere('day_of_week', DaysOfTheWeek::Wednesday)
                    ->orWhere('day_of_week', DaysOfTheWeek::Thursday)
                    ->orWhere('day_of_week', DaysOfTheWeek::Friday)
                    ->orWhere('day_of_week', DaysOfTheWeek::Saturday);
                })),

            'Sunday' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_week', DaysOfTheWeek::Sunday)),
            'Monday' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_week', DaysOfTheWeek::Monday)),
            'Tuesday' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_week', DaysOfTheWeek::Tuesday)),
            'Wednesday' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_week', DaysOfTheWeek::Wednesday)),
            'Thursday' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_week', DaysOfTheWeek::Thursday)),
            'Friday' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_week', DaysOfTheWeek::Friday)),
            'Saturday' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_week', DaysOfTheWeek::Saturday)),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return Carbon::today()->format('l');
    }
}
