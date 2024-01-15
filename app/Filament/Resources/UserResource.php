<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use App\Models\User;
use Filament\Tables;
use App\Models\Position;
use Filament\Forms\Form;
use Filament\Pages\Page;

use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\Widgets\UserStats;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Employees per Restaurant';
    protected static ?string $modelLabel = 'Employees';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make()->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
                //->hidden(! auth()->user()->hasRole('Admin')),

                Forms\Components\TextInput::make('lastname')
                ->required()
                ->maxLength(255),
                //->hidden(! auth()->user()->hasRole('Admin')),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                    //->hidden(! auth()->user()->hasRole('Admin')),

                //Forms\Components\DateTimePicker::make('email_verified_at'),
                //->hidden(! auth()->user()->hasRole('Admin')),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (Page $livewire) => ($livewire instanceof CreateUser))
                    ->maxLength(255),
                    //->hidden(! auth()->user()->hasRole('Admin')),

                    Forms\Components\TextInput::make('phone')
                    ->required()
                    ->maxLength(255),
                    //->hidden(! auth()->user()->hasRole('Admin')),

                    Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(1000),
                    //->hidden(! auth()->user()->hasRole('Admin')),

                    Forms\Components\FileUpload::make('W2W4_path')
                    ->label('Upload Application'),
                    //->hidden(! auth()->user()->hasRole('Admin')),

                    Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->required()
                    ->preload()
                    // ->when(auth()->user()->hasRole(['Owner', 'Admin']), function ($query) {
                    //     // Show all roles for owner or admin
                    //     return $query->preload();
                    // })
                    // ->when(auth()->user()->hasRole('Manager'), function ($query) {
                    //     // Exclude owner and admin roles for manager
                    //     return $query->options(function () {
                    //         return Role::whereNotIn('name', ['Owner', 'Admin'])->pluck('name');
                    //     });
                    // }),

                    ->hidden(! auth()->user()->hasRole(['Owner', 'Admin'])),


                    Select::make('position_id')
                        ->label('Position')
                        ->required()
                        ->options(Position::all()->pluck('p_name', 'id')),




                    // This is for many to many relationships with pivot table
                    Select::make('restaurant')
                     ->multiple()
                     ->relationship('restaurant', 'name')->preload()->required()
                     ->hidden(! auth()->user()->hasRole(['Owner', 'Admin'])),

                     Select::make('restaurant')
                     ->relationship('restaurants', 'name')
                     ->options(function () {
                         // Fetch all restaurants assigned to the current user
                         $userRestaurants = auth()->user()->restaurants;

                         // Get the first restaurant from the collection
                         $userRestaurant = $userRestaurants->first();

                         // Return the first restaurant as an array suitable for the dropdown
                         return $userRestaurant ? [$userRestaurant->id => $userRestaurant->name] : null;
                     })
                     ->preload()->required()
                     ->hidden(! auth()->user()->hasRole('Manager')),



                    //  TextInput::make('restaurant.name')
                    // ->label('Restaurant Name')
                    // ->default(function () {
                    //     // Fetch all restaurants assigned to the current user
                    //     $userRestaurants = auth()->user()->restaurants;

                    //     // Get the first restaurant from the collection
                    //     $userRestaurant = $userRestaurants->first();

                    //     // Return the name of the first restaurant or an empty string
                    //     return $userRestaurant ? $userRestaurant->name : '';
                    // })
                    // ->required()

                    // ->hidden(! auth()->user()->hasRole('Manager')),






                    // Select::make('position_id')
                    // ->label('Position')
                    // ->options(Position::all()->pluck('p_name', 'id')->toArray()),
                    //->hidden(! auth()->user()->hasRole('Admin')),


                    Forms\Components\TextInput::make('notes'),
                    //->hidden(! auth()->user()->hasRole('Admin')),

                    Forms\Components\Toggle::make('status')
                    ->required()->default('1'),
                    //->hidden(! auth()->user()->hasRole('Admin')),

            ])
            ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->Groups(['status','position.p_name'])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable()->sortable(),
                //->hidden(! auth()->user()->hasRole('Admin')),

                Tables\Columns\TextColumn::make('lastname')
                ->searchable()->sortable(),
                //->hidden(! auth()->user()->hasRole('Admin')),

                Tables\Columns\TextColumn::make('email')->searchable(),
                //->hidden(! auth()->user()->hasRole('Admin')),

                Tables\Columns\IconColumn::make('status')->sortable()->boolean(),
                //->hidden(! auth()->user()->hasRole('Admin')),

                Tables\Columns\TextColumn::make('position.p_name')->label('Position')->sortable()->searchable(),
                //->hidden(! auth()->user()->hasRole('Admin')),

                Tables\Columns\TextColumn::make('roles.name')->sortable(),

                Tables\Columns\TextColumn::make('notes')->label('Manager Notes')->sortable(),
                //->hidden(! auth()->user()->hasRole('Admin')),

                Tables\Columns\TextColumn::make('restaurant.name')->label('Restaurant')
                ->toggleable(isToggledHiddenByDefault: true),
                //->hidden(! auth()->user()->hasRole('Admin')),

            ])
            ->defaultSort('created_at','desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ])
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

    public static function getWidgets(): array
    {
        return [
            UserStats::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
