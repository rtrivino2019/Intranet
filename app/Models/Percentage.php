<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Percentage extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_date',
         'check',
         'concept',
         'type',
         'amount',
         'user_id',
         'supplier_id',

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
            return $this->belongsToMany(Restaurant::class, 'percentage_restaurant', 'percentage_id', 'restaurant_id');
        }

        public function supplier(): BelongsTo {
            return $this->belongsTo(Supplier::class);
        }





}
