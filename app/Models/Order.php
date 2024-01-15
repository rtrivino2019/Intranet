<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_date',
        'order_number',


    ];



    public function user(): BelongsTo
        {
            return $this->belongsTo(User::class, 'user_id');
        }

    public function restaurant(): BelongsToMany
        {
            return $this->belongsToMany(Restaurant::class);
        }



        public function restaurants()
        {
            return $this->belongsToMany(Restaurant::class, 'order_restaurant', 'order_id', 'restaurant_id');
        }


    public function orderItems(): HasMany {
        return $this->hasMany(OrderItem::class);
        
    }
    //changed to Orders to have a cleaner title in the form
    public function Orders(): HasMany {
        return $this->hasMany(OrderItem::class);
    }

    

}
