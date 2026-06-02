<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'notifications',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'notifications' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($user) {
            $user->notifications = [];
        });
    }

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
