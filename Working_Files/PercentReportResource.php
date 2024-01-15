<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Percentage;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PercentReportResource\Pages;
use App\Filament\Resources\PercentReportResource\RelationManagers;
use App\Filament\Resources\PercentReportResource\Widgets\PercentReport;


class PercentReportResource extends Resource
{
    protected static ?string $model = Percentage::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Reports & Percentages';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Daily Sales';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Daily Sales')
                ->schema([
                    TextInput::make('credit_card')->label('Credit Cards')->required()->rule('numeric')->live(),
                    TextInput::make('cash')->required()->rule('numeric')->live(),
                    TextInput::make('online')->required()->rule('numeric')->live(),
                    TextInput::make('uber')->required()->rule('numeric')->live(),
                    TextInput::make('grubhub')->required()->rule('numeric')->live(),
                    TextInput::make('doordash')->required()->rule('numeric')->live(),
                    //TextInput::make('class')->required()->default('income'),
                    //TextInput::make('sales')->required()->rule('numeric'),

                        // ->placeholder(function (Get $get) {
                        // $fields = $get('cash')+$get('online')+$get('uber')+$get('grubhub')+$get('credit_card')+$get('doordash');
                        // $sum=$fields;
                        // return $sum; }),
                     //Placeholder::make('sum')->content(fn($get)=>$get('cash')+$get('online')),
                ])->columns(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('credit_card')->label('Credit Cards')->sortable()->money('usd'),
                TextColumn::make('cash')->sortable()->money('usd'),
                TextColumn::make('online')->label('Online Sales')->sortable()->money('usd'),
                TextColumn::make('uber')->sortable()->money('usd'),
                TextColumn::make('grubhub')->sortable()->money('usd'),
                TextColumn::make('doordash')->sortable()->money('usd'),
                TextColumn::make('sales')->sortable()->money('usd'),
                TextColumn::make('sales'),


                // ->getStateUsing(function(Model $record) {
                //     return $record->cash + $record->online;
                // }),
            ])
            ->filters([
                Filter::make('Deposit')
                ->query(fn (Builder $query): Builder => $query->where('is_deposit', true))
                ->default(),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('created_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Order from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Order until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),






            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getWidgets(): array
    {
        return [
            PercentReport::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPercentReports::route('/'),
            'create' => Pages\CreatePercentReport::route('/create'),
            'edit' => Pages\EditPercentReport::route('/{record}/edit'),
        ];
    }
}
