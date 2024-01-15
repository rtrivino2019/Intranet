<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\ReportResource\Pages;
use App\Filament\Owner\Resources\ReportResource\RelationManagers;
use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Infolists\Components\TextEntry;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('report_date'),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name'),
                Forms\Components\TextInput::make('cash')
                    ->numeric(),
                Forms\Components\TextInput::make('credit_card')
                    ->numeric(),
                Forms\Components\TextInput::make('online')
                    ->numeric(),
                Forms\Components\TextInput::make('doordash')
                    ->numeric(),
                Forms\Components\TextInput::make('uber')
                    ->numeric(),
                Forms\Components\TextInput::make('grubhub')
                    ->numeric(),
                Forms\Components\TextInput::make('sales')
                    ->numeric(),
                Forms\Components\TextInput::make('food')
                    ->numeric(),
                Forms\Components\TextInput::make('liquor')
                    ->numeric(),
                Forms\Components\TextInput::make('beer')
                    ->numeric(),
                Forms\Components\TextInput::make('wine')
                    ->numeric(),
                Forms\Components\TextInput::make('taxes')
                    ->numeric(),
                Forms\Components\TextInput::make('discount')
                    ->numeric(),
                Forms\Components\TextInput::make('voids')
                    ->numeric(),
                Forms\Components\TextInput::make('gift_certificate')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('report_date')
                    ->dateTime('M j, Y')
                    ->sortable(),
                   

                Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->since(),

                Tables\Columns\TextColumn::make('restaurant.name'),
                
                
                Tables\Columns\TextColumn::make('user.name')
                    
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('sales')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('body')->label('Comments')->sortable()->toggleable(),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()
                ->schema([
                    Components\Split::make([
                        Components\Grid::make(2)
                        ->schema([
                            Components\Group::make([
                                TextEntry::make('report_date')->date(),
                                TextEntry::make('updated_at')->date(),
                                TextEntry::make('restaurant.name'),
                            ]),
                            Components\Group::make([
                                
                                TextEntry::make('user.name')->label('Order created or modified by'),
                                TextEntry::make('sales')->money('usd'),
                                TextEntry::make('body')->label('Manager Comments'),
                            ]),
                        ]),
                    ]),
                ]),
                Components\Section::make()
                ->schema([
                    Components\Split::make([
                        Components\Grid::make(2)
                        ->schema([
                            Components\Group::make([
                                TextEntry::make('credit_card')->label('Credit Cards')->money('usd'),
                                TextEntry::make('cash')->money('usd'),
                                TextEntry::make('online')->label('Online Sales')->money('usd'),
                                
                            ]),
                            Components\Group::make([
                                TextEntry::make('uber')->money('usd'),
                                TextEntry::make('grubhub')->money('usd'),
                                TextEntry::make('doordash')->money('usd'),
                            ]),
                        ]),
                    ]),
                ]),

                Components\Section::make()
                ->schema([
                    Components\Split::make([
                        Components\Grid::make(2)
                        ->schema([
                            Components\Group::make([   
                                TextEntry::make('food')->money('usd'),
                                TextEntry::make('liquor')->money('usd'),
                                TextEntry::make('beer')->money('usd'),
                                TextEntry::make('wine')->money('usd'),
                                
                            ]),
                            Components\Group::make([
                                TextEntry::make('taxes')->money('usd'),
                                TextEntry::make('discount')->money('usd'),
                                TextEntry::make('voids')->money('usd'),
                                TextEntry::make('gift_certificate')->money('usd'),
                            ]),
                        ]),
                    ]),
                ]),

                
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
            'view' => Pages\ViewReport::route('/{record}'),
        ];
    }    
}
