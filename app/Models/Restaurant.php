<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        //'slug',

    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function reports(): BelongsToMany
    {
        return $this->belongsToMany(Report::class);
    }

    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class);
    }


    public function messages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class);
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class);
    }

    public function percentages(): BelongsToMany
    {
        return $this->belongsToMany(Percentage::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }

    public function orderitems(): BelongsToMany
    {
        return $this->belongsToMany(OrderItem::class);
    }
}
