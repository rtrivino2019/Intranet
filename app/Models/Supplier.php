<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];



    public function product(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function Item(): BelongsToMany
    {
        return $this->belongsToMany(Item::class);
    }


}

