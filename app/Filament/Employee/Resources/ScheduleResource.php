<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\ScheduleResource\Pages;
use App\Filament\Employee\Resources\ScheduleResource\RelationManagers;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;




use App\Models\Role;
use App\Models\Slot;
use App\Models\User;

use Filament\Forms\Get;
use Filament\Forms\Set;

use App\Models\Restaurant;

use App\Enums\DaysOfTheWeek;

use Illuminate\Support\Collection;



class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Communications';

    public static function form(Form $form): Form
    {
        $currentUser = auth()->user();

    if (!$currentUser) {
        // Handle the case when there's no authenticated user
        return $form;
    }

    $currentRestaurants = $currentUser->restaurants;

    if ($currentRestaurants->isEmpty()) {
        // Handle the case when the user is not associated with any restaurant
        return $form;
    }

    $currentRestaurantIds = $currentRestaurants->pluck('id')->toArray();

    $usersGroupedByRestaurant = User::whereHas('restaurants', function ($query) use ($currentRestaurantIds) {
            $query->whereIn('restaurant_id', $currentRestaurantIds);
        })
        ->get()
        ->groupBy(function ($user) {
            return $user->restaurants->pluck('name')->implode(', '); // Assuming 'name' is the restaurant name column
        })
        ->map(function ($users, $restaurantNames) {
            return $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => "{$user->name} ({$user->position->p_name})"
                ];
            });
        });

    $groupedOptions = [];

    foreach ($usersGroupedByRestaurant as $restaurantNames => $users) {
        $groupedOptions[$restaurantNames] = $users->pluck('text', 'id')->toArray();
    }

        return $form
            ->schema([
                Forms\Components\Section::make([

                    Forms\Components\Select::make('user_id')
                    ->native(false)
                    ->label('Employee')
                    ->options($groupedOptions)
                    ->required()
                    ->searchable()
                    ->live(),

                    Forms\Components\Select::make('day_of_week')
                        ->options(DaysOfTheWeek::class)
                        ->native(false)
                        ->required(),
                    Forms\Components\Repeater::make('slots')
                        ->relationship()
                        ->schema([
                            Forms\Components\TimePicker::make('start')
                                ->seconds(false)
                                ->required(),
                            Forms\Components\TimePicker::make('end')
                                ->seconds(false)
                                ->required()
                        ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup(
                Tables\Grouping\Group::make('restaurant.name')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false)
            )
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day_of_week')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slots')
                    ->badge()
                    ->formatStateUsing(fn (Slot $state) => $state->start->format('h:i A') . ' - ' . $state->end->format('h:i A')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(fn (Schedule $record) => $record->slots()->delete())
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
