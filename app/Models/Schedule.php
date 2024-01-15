<?php

namespace App\Models;

use App\Models\Restaurant;
use App\Enums\DaysOfTheWeek;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'day_of_week',


    ];

    protected $casts = [
        'day_of_week' => DaysOfTheWeek::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // public function owner(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'owner_id');
    // }

    public function slots(): HasMany
    {
        return $this->hasMany(Slot::class);
    }

    public function restaurant(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class);
    }

    public function restaurants()
        {
            return $this->belongsToMany(Restaurant::class, 'report_restaurant', 'report_id', 'restaurant_id');
        }
    // public function restaurant(): BelongsTo
    // {
    //     return $this->belongsTo(Restaurant::class);
    // }
}


// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Schedule extends Model
// {
//     use HasFactory;
// }
