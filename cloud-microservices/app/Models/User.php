<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'users';   // collection f cloud1

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = ['password'];
}
