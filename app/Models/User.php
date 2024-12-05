<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'isAdmin',
        'experience_points',
        'isOnline',
    ];
    public function repairs()
    {
        return $this->hasMany(OrderRepair::class, 'user_id');
    }

    // To get completed orders through repairs
    public function completedOrders()
    {
        return $this->hasMany(OrderRepair::class, 'user_id', 'id')
           ;
    }
    
    // Method to count unique order_ids for the authenticated user
    public function countUniqueCompletedOrders()
    {
        return $this->completedOrders()->distinct('order_id')->count('order_id');
    }
    

    // Method to update the level based on experience points
    protected function updateLevel()
    {
        // Example logic for level calculation
        $this->level = (int) ($this->experience_points / 100); // Assuming 100 points per level
    }

    // Method to determine if the user qualifies for a specific task complexity
    public function canHandleComplexity($requiredLevel)
    {
        return $this->level >= $requiredLevel;
    }
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
        'password' => 'hashed',
    ];
}
