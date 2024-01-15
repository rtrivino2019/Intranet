<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\CategoryFood;
use App\Enums\ProvidersFood;
use PhpParser\Node\Stmt\Label;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;





class OrderitemsRelationManager extends RelationManager
{
    //protected static string $relationship = 'orderitems';

    protected static string $relationship = 'orders';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('id')
                //     ->required()
                //     ->maxLength(255),
            ]);
    }


    public function table(Table $table): Table
    {

        return $table
            ->recordTitleAttribute('id')

            ->columns([
                //Tables\Columns\TextColumn::make('id'),
                //Tables\Columns\TextColumn::make('order_id'),

                Tables\Columns\TextColumn::make('product.name')->sortable(),
                Tables\Columns\TextColumn::make('have'),
                Tables\Columns\TextColumn::make('product_amount')->numeric()->Label('Amount'),

                

                Tables\Columns\TextColumn::make('product.unit')->Label('Unit'),
                Tables\Columns\TextColumn::make('food_supplier')->Label('Supplier')->sortable(),
                Tables\Columns\TextColumn::make('categoryfood')->Label('Category')->sortable(),


            ])
            ->filters([

                Tables\Filters\SelectFilter::make('food_supplier')
                ->label('Supplier')
                ->options(ProvidersFood::class),

                Tables\Filters\SelectFilter::make('categoryfood')
                ->label('Product')
                ->options(CategoryFood::class),

                 Filter::make('Quantities > 0')->toggle()->query(
                    function($query){
                        return $query->where('product_amount','>','0');
                    }
                 )->default()


            ])
            ->headerActions([

                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),



            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                //Tables\Actions\CreateAction::make(),
            ]);
    }
}
