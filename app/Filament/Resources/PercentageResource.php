<?php

namespace App\Filament\Resources;


use Filament\Forms;
use Filament\Tables;
use App\Models\Supplier;
use Filament\Forms\Form;
use App\Models\Percentage;
use Filament\Tables\Table;
use App\Enums\PercentageType;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PercentageResource\Pages;
use App\Filament\Resources\PercentageResource\RelationManagers;
use App\Filament\Resources\PercentageResource\Widgets\PercentageStats;

class PercentageResource extends Resource
{
    protected static ?string $model = Percentage::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Reports & Percentages';
    protected static ?string $modelLabel = 'Expenses';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DateTimePicker::make('expense_date')->label('Expense Date')->required()->default(now())->timezone('America/New_York'),


                Section::make('Expense')
                ->schema([
                Forms\Components\Select::make('type')
                ->options(PercentageType::class),
                Forms\Components\Select::make('supplier_id')
                ->label('Supplier')
                ->options(Supplier::all()->pluck('name', 'id'))
                    ->required()->preload()->searchable(),

                Forms\Components\TextInput::make('check')
                    ->required()
                    ->maxLength(255)->label('Check Number'),
                Forms\Components\TextInput::make('concept')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')->required()->rule('numeric'),

                ])->columns(2),





            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //Tables\Columns\TextColumn::make('user.name'),
                TextColumn::make('expense_date')->dateTime('M j Y, g:i A')
                ->timezone('America/New_York')->sortable(),
                Tables\Columns\TextColumn::make('supplier.name')->sortable(),
                //Tables\Columns\TextColumn::make('month')->dateTime('M, Y')->sortable(),
                Tables\Columns\TextColumn::make('type')->sortable(),
                Tables\Columns\TextColumn::make('check')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('concept'),
                Tables\Columns\TextColumn::make('amount')->money('usd'),
                Tables\Columns\TextColumn::make('created_at')
                ->timezone('America/New_York')
                ->dateTime('M j Y, g:i A')->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                ->timezone('America/New_York')
                ->dateTime('M j Y, g:i A')->sortable()
                ->toggleable(isToggledHiddenByDefault: true),



            ])
            ->filters([
                Tables\Filters\Filter::make('expense_date')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y'))
                            ->label('Expense Date From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y'))
                            ->label('Expense Date Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('expense_date', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('expense_date', '<=', $date),
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

                // Filter::make('Not Deposit')
                //     ->query(fn (Builder $query): Builder => $query->where('is_deposit', false))
                //     ->default()
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
            PercentageStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPercentages::route('/'),
            'create' => Pages\CreatePercentage::route('/create'),
            'edit' => Pages\EditPercentage::route('/{record}/edit'),

        ];
    }
}
