<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Review;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_completed',
        'postal_code',
        'address',
        'building_name',
        'profile_image_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function orders(): HasMany {
        return $this->hasMany(Order::class);
    }

    public function purchasedItems(): BelongsToMany {
        return $this->belongsToMany(Item::class, 'orders', 'user_id', 'item_id')
        ->withTimestamps();
    }

    public function getTradingItemsAttribute()
    {
        $userId = $this->id;

        return \App\Models\Item::where(function($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where(function($q) {
                    $q->has('order')->orHas('messages');
                });
        })
        ->orWhere(function($query) use ($userId) {
            $query->whereHas('order', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->orWhereHas('messages', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        })
        ->distinct()
        ->get();
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function averageRating()
    {
        $average = $this->receivedReviews()->avg('rating');
        return $average ? (int)round($average) : null;
    }

    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }
}
