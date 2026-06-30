<?php

namespace App\Models;
use App\Enums\UserRole;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'mobile',
        'email',
        'password',
        'role',
        'is_active',
    ];

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
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }
        public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isEmployee(): bool
    {
       return $this->role === UserRole::Employee;
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }
    public function assignedBusinesses(): HasMany
{
    return $this->hasMany(Business::class, 'assigned_user_id');
}

public function callLogs(): HasMany
{
    return $this->hasMany(CallLog::class);
}

public function reminders(): HasMany
{
    return $this->hasMany(Reminder::class);
}

public function smsLogs(): HasMany
{
    return $this->hasMany(SmsLog::class);
}

public function orders(): HasMany
{
    return $this->hasMany(Order::class);
}

public function activityLogs(): HasMany
{
    return $this->hasMany(ActivityLog::class);
}
}
