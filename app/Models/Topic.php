<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Topic extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject',
        'creator_id',
        //'receiver_id',
        'user_id'
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
            return $this->belongsToMany(Restaurant::class, 'topic_restaurant', 'topic_id', 'restaurant_id');
        }
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'topic_id')
            ->orderBy('created_at', 'desc');
    }



    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function markMessagesAsRead(): void
    {
        foreach ($this->messages as $message) {
            if ($message->sender_id !== auth()->id() && is_null($message->read_at)) {
                $message->read_at = now();
                $message->save();
            }
        }
    }
    // public function receiver(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'receiver_id')->where('id', '!=', auth()->id());
    // }

    // public function markMessagesAsRead(): void
    // {
    //     foreach ($this->messages as $message) {
    //         if ($message->user_id !== auth()->id() && is_null($message->read_at)) {
    //             $message->read_at = now();
    //             $message->save();
    //         }
    //     }
    // }

}
