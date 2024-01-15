<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Report;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\ReportResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReportResource\RelationManagers;
use App\Filament\Resources\ReportResource\Widgets\ReportStats;
use Illuminate\Support\Carbon;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Infolists\Components\TextEntry;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationGroup = 'Reports & Percentages';
    protected static ?string $modelLabel = 'Daily Report & Sales';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DateTimePicker::make('report_date')->label('Report Date')->required()->default(now())->timezone('America/New_York'),

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

                Section::make('Daily Expenses & Percentages Information')
                ->schema([
                    TextInput::make('food')->required()->rule('numeric'),
                    TextInput::make('liquor')->required()->rule('numeric'),
                    TextInput::make('beer')->required()->rule('numeric'),
                    TextInput::make('wine')->required()->rule('numeric'),
                    TextInput::make('taxes')->required()->rule('numeric'),
                    TextInput::make('discount')->required()->rule('numeric'),
                    TextInput::make('voids')->required()->rule('numeric'),
                    TextInput::make('gift_certificate')->label('Gift Certificates')->required()->rule('numeric'),
                ])->columns(3),

                Textarea::make('body')
                    ->required()->label('Manager Comments'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('report_date')->dateTime('M j, Y')
                ->timezone('America/New_York')->sortable(),
                
                Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->since(),

                Tables\Columns\TextColumn::make('user.name')
                    
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('sales')
                    ->numeric()
                    ->sortable(),

                //TextColumn::make('restaurant.name')->label('Restaurant'),
                //TextColumn::make('user.name')->label('Name')->sortable(),

                // TextColumn::make('credit_card')->label('Credit Cards')->sortable()->money('usd'),
                // TextColumn::make('cash')->sortable()->money('usd'),
                // TextColumn::make('online')->label('Online Sales')->sortable()->money('usd'),
                // TextColumn::make('uber')->sortable()->money('usd'),
                // TextColumn::make('grubhub')->sortable()->money('usd'),
                // TextColumn::make('doordash')->sortable()->money('usd'),
                // TextColumn::make('sales')->sortable()->money('usd'),
                


                // TextColumn::make('food')->sortable()->money('usd')->toggleable(),
                // TextColumn::make('liquor')->sortable()->money('usd')->toggleable(),
                // TextColumn::make('beer')->sortable()->money('usd')->toggleable(),
                // TextColumn::make('wine')->sortable()->money('usd')->toggleable(),
                // TextColumn::make('taxes')->sortable()->money('usd')->toggleable(),
                // TextColumn::make('discount')->sortable()->money('usd')->toggleable(),
                // TextColumn::make('voids')->sortable()->money('usd')->toggleable(),
                // TextColumn::make('gift_certificate')->sortable()->money('usd')->toggleable(),


                TextColumn::make('body')->label('Comments')->sortable()->toggleable(),
            ])
            ->defaultSort('updated_at','desc')
            ->filters([
                Tables\Filters\Filter::make('report_date')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y'))
                            ->label('Report Date From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y'))
                            ->label('Report Date Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('report_date', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('report_date', '<=', $date),
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

    public static function getWidgets(): array
    {
        return [
            ReportStats::class,

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
