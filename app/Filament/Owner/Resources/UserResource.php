<?php

namespace App\Filament\Owner\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Position;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Owner\Resources\UserResource\Pages;
use App\Filament\Owner\Resources\UserResource\Pages\CreateUser;
use App\Filament\Owner\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $modelLabel = 'Employees';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make()->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
                
                Forms\Components\TextInput::make('lastname')
                ->required()
                ->maxLength(255),
                
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                   
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (Page $livewire) => ($livewire instanceof CreateUser))
                    ->maxLength(255),
                    

                    Forms\Components\TextInput::make('phone')
                    ->required()
                    ->maxLength(255),
                    

                    Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(1000),
                    

                    Forms\Components\FileUpload::make('W2W4_path')
                    ->label('Upload Application'),
                    

                    Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->required()
                    ->preload(),
                    

                    

                    Select::make('position_id')
                        ->label('Position')
                        ->required()
                        ->options(Position::all()->pluck('p_name', 'id')),




                    // This is for many to many relationships with pivot table
                    Select::make('restaurant')
                     ->multiple()
                     ->relationship('restaurant', 'name')->preload()->required(),
                     

                    





                    Forms\Components\TextInput::make('notes'),
                    

                    Forms\Components\Toggle::make('status')
                    ->required()->default('1'),
                   

            ])
            ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->Groups(['status','position.p_name','roles.name'])
        ->columns([
            Tables\Columns\TextColumn::make('name')
            ->searchable()->sortable(),
           

            Tables\Columns\TextColumn::make('lastname')
            ->searchable()->sortable(),
            

            Tables\Columns\TextColumn::make('email')->searchable(),
            

            Tables\Columns\IconColumn::make('status')->sortable()->boolean(),
            

            Tables\Columns\TextColumn::make('position.p_name')->label('Position')->sortable()->searchable(),
            
            Tables\Columns\TextColumn::make('roles.name')->sortable()->searchable(),

            Tables\Columns\TextColumn::make('notes')->label('Manager Notes')->sortable(),
            

            Tables\Columns\TextColumn::make('restaurant.name')->label('Restaurant')
            ->toggleable(isToggledHiddenByDefault: true),
            

        ])
        ->defaultSort('created_at','desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }    
}
