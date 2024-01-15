<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit',
        'supplier',
        'categoryfood',

    ];


    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    // public function categoryfood()
    // {
    //     return $this->belongsTo(Categoryfood::class, 'categoryfood_id');
    // }



    public function Item(): BelongsToMany
    {
        return $this->belongsToMany(Item::class);
    }

}

