<?php

namespace App\Filament\Owner\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\ProvidersFood;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Owner\Resources\OrderResource\Pages;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use App\Filament\Owner\Resources\OrderResource\RelationManagers\OrdersRelationManager;
use App\Filament\Owner\Resources\OrderResource\RelationManagers\OrderitemsRelationManager;


class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                TextInput::make('order_number')
                                    // ->default(Str::random(5))
                                    ->default('R-'.random_int(1000,9999))
                                    //->unique()
                                    ->required(),
                                Forms\Components\DatePicker::make('order_date')
                                    ->default(now())//->unique()
                                    ->required(),
                            ])->columns([
                                'sm'=> 2,
                            ]),

                        Section::make()
                            ->schema([
                                //Forms\Components\Placeholder::make('Products'),
                                TableRepeater::make('OrderItems')->label('')
                                ->relationship()
                                ->schema([
                                    Select::make('product_id')
                                        ->label('Product')
                                        ->disableLabel()
                                        ->options(function () {
                                            // Retrieve products and group them by categoryfood
                                            $groupedProducts = Product::orderBy('id')->orderBy('categoryfood')->orderBy('name')->get()->groupBy('categoryfood');

                                            // Prepare options array
                                            $options = [];
                                            foreach ($groupedProducts as $category => $products) {
                                                $options[$category] = $products->pluck('name', 'id')->toArray();
                                            }

                                            return $options;
                                        })
                                        // ->options(Product::query()->orderBy('name')->pluck('name', 'id')
                                        // ->mapWithKeys(function ($productName, $productId) {
                                        //     $product = Product::find($productId);
                                        //     //return ["$productId" => "{$productName} ({$product->unit})-({$product->supplier}) - Category: {$product->categoryfood}"];
                                        //     return ["$productId" => "{$productName} ({$product->unit})-({$product->supplier})"];
                                        // })
                                        // )
                                        ->required()
                                        ->searchable()
                                        ->reactive()
                                        ->afterStateUpdated(function($state,callable $set){
                                           $product = Product::find($state);
                                           if($product){
                                            $set('categoryfood', $product->categoryfood);
                                            $set('food_supplier', $product->supplier);
                                            $set('unit', $product->unit);
                                            

                                           }
                                        })
                                        ->columnSpan(['md' => 1, 'sm' => 1]),


                                    TextInput::make('unit')
                                    ->label('Units')
                                    ->columnSpan(['md' => 3]),

                                    TextInput::make('have')
                                        ->label('Have')->disableLabel()
                                        ->numeric()
                                        ->default(0)
                                        ->required()
                                        ->columnSpan(['md' => 1, 'sm' => 1]),

                                    TextInput::make('product_amount')
                                        ->label('Amount')->disableLabel()
                                        ->numeric()
                                        ->default(1)
                                        ->required()
                                        ->columnSpan(['md' => 1, 'sm' => 1]),


                                    Select::make('food_supplier')
                                        ->label('Supplier')->disableLabel()
                                        ->options(ProvidersFood::class)
                                        ->required()
                                        ->columnSpan(['md' => 1, 'sm' => 1]),



                                    TextInput::make('categoryfood')
                                    ->label('Category')
                                    ->columnSpan(['md' => 3])



                                ])
                                ->columnSpan('full')
                            ]),
                    ])->columnSpan('full')
            ]);


    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('restaurant.name'),
                Tables\Columns\TextColumn::make('order_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
                    
            ])
            ->defaultSort('updated_at','desc')

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->color('success')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Order $record) => route('pdf', $record))
                    ->openUrlInNewTab(), 

               




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
                                TextEntry::make('order_date')->date(),
                                TextEntry::make('updated_at')->date(),
                                TextEntry::make('user.name')->label('Order created or modified by'),
                            ]),
                            Components\Group::make([
                                TextEntry::make('restaurant.name'),
                                TextEntry::make('order_number'),

                            ]),


                        ]),

                    ]),

                ])
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            OrderitemsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }    
}
