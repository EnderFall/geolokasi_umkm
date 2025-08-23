<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'role',
        'is_active',
        'password',
    ];

    protected $appends = ['role_color'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get role color for badge
     */
    public function getRoleColorAttribute()
    {
        return match($this->role) {
            'superadmin' => 'danger',
            'admin' => 'warning',
            'penjual' => 'success',
            'pembeli' => 'info',
            default => 'secondary',
        };
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    /**
     * Check if user is penjual
     */
    public function isPenjual()
    {
        return $this->role === 'penjual';
    }

    /**
     * Check if user is pembeli
     */
    public function isPembeli()
    {
        return $this->role === 'pembeli';
    }

    /**
     * Get user's outlet
     */
    public function outlet()
    {
        return $this->hasOne(Outlet::class);
    }

    /**
     * Get user's ratings
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get user's orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get user's reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
