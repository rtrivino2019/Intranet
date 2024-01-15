<?php

namespace App\Filament\Employee\Resources\TopicResource\Pages;

use App\Filament\Employee\Resources\TopicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Topic;

class ListTopics extends ListRecords
{
    protected static string $resource = TopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // public function getTabs(): array
    // {
    //     return [
    //         'all' => Tab::make('All Messages')
    //             ->badge(Topic::query()->where('user_id', auth()->id())->orWhere('creator_id', auth()->id())->count())
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('creator_id', auth()->id())->orWhere('creator_id', auth()->id())),
    //         'inbox' => Tab::make()
    //             ->badge(Topic::query()->where('user_id', auth()->id())->count())
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('creator_id', auth()->id())),
    //         'outbox' => Tab::make()
    //             ->badge(Topic::query()->where('creator_id', auth()->id())->count())
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('creator_id', auth()->id())),
    //     ];
    // }
}
