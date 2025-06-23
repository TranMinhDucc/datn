<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'fullname',
        'email',
        'phone',
        'address',
        'password',
        'avatar',
        'gender',
        'role',
        'point',
        'balance',
        'two_factor_enabled',
        'token_2fa',
        'SecretKey_2fa',
        'limit_2fa',
        'status_2fa',
        'create_date',
        'update_date',
        'registered_at',
        'last_login_ip',
        'last_login_device',
        'last_login_at', // ✅ thêm dòng này
        'banned'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token', 'token_2fa', 'SecretKey_2fa'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'telegram_connected' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'balance' => 'decimal:2',
        'registered_at' => 'datetime',
        'create_date' => 'datetime',
        'update_date' => 'datetime',
    ];
    public function getAvatarUrlAttribute()
    {
        return $this->avatar && file_exists(public_path($this->avatar))
            ? asset($this->avatar)
            : asset('assets/client/images/user/12.jpg');
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ✅ Quan hệ: người dùng có nhiều đánh giá
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class);
    }
}