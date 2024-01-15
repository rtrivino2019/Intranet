<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [

        'body',
        'user_id',
        'report_date',
        'credit_card',
        'cash',
        'online',
        'uber',
        'grubhub',
        'doordash',
        'sales',
        'food',
        'liquor',
        'beer',
        'wine',
        'taxes',
        'discount',
        'voids',
        'gift_certificate',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }



   // not using restaurants because of the multiple tenant relationship
    public function restaurant(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class);
    }

    public function restaurants()
        {
            return $this->belongsToMany(Restaurant::class, 'report_restaurant', 'report_id', 'restaurant_id');
        }
}
