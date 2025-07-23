<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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
        'pin',
        'password',
        'category_id'
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
        'category_id' => UserRole::class,
    ];

    public function hasPermission(UserRole $role)
    {
        // if current user is admin
        if ($this->category_id == UserRole::Admin) {
            return true;
        }
        return $this->category_id  == $role;
    }

    public function isAdmin()
    {
        return $this->category_id == UserRole::Admin;
    }

    public function isWaiter()
    {
        return $this->category_id == UserRole::Waiter;
    }

    public function isBiller()
    {
        return $this->category_id == UserRole::Biller;
    }

    public function isKitchen()
    {
        return $this->category_id == UserRole::Kitchen;
    }
}
