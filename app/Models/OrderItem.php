<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
         'product_id',
         'product_amount',
         // 'product_price'
         //'supplier_id'
         'food_supplier',
         'categoryfood',
         'unit',
         'have'
        ];


        public function order(): BelongsTo {
            return $this->belongsTo(Order::class);
        }

        // public function supplier(): BelongsTo {
        //     return $this->belongsTo(Supplier::class);
        // }

        public function product(): BelongsTo {
            return $this->belongsTo(Product::class, 'product_id');
        }

        public function restaurant(): BelongsToMany
        {
            return $this->belongsToMany(Restaurant::class);
        }


}

