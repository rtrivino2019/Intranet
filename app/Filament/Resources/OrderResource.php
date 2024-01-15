<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\CategoryFood;
use Illuminate\Support\Str;
use App\Enums\ProvidersFood;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
//use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\CheckboxList;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\OrderResource\Pages;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use App\Filament\Resources\OrderResource\RelationManagers\OrderitemsRelationManager;




class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Inventory';




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
                                TableRepeater::make('OrderItems')->label('')
                                ->relationship()
                                ->schema([
                                    Select::make('product_id')
                                        ->label('Product')
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
                                        ->label('Have')
                                        ->numeric()
                                        ->default(0)
                                        ->required()
                                        ->columnSpan(['md' => 1, 'sm' => 1]),

                                    TextInput::make('product_amount')
                                        ->label('Amount')
                                        ->numeric()
                                        ->default(1)
                                        ->required()
                                        ->columnSpan(['md' => 1, 'sm' => 1]),


                                    Select::make('food_supplier')
                                        ->label('Supplier')
                                        ->options(ProvidersFood::class)
                                        ->required()
                                        ->columnSpan(['md' => 1, 'sm' => 1]),



                                    TextInput::make('categoryfood')
                                    ->label('Category')
                                    ->columnSpan(['md' => 3])

                                ])
                                ->columnSpan('full')
                                ->reorderable(true)
                                ->reorderableWithButtons()
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
                //Tables\Columns\TextColumn::make('orderitems')->label('Products'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
                    //->toggleable(isToggledHiddenByDefault: true),
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
