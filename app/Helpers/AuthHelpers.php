<?php

namespace App\Helpers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Petugas;

class AuthHelper
{
    protected static $user;

    public static function user()
    {
        if (!self::$user) {
            try {
                self::$user = JWTAuth::parseToken()->authenticate();
            } catch (\Exception $e) {
                return null;
            }
        }
        return self::$user;
    }

    public static function id()
    {
        return self::user()?->id;
    }

    public static function role()
    {
        return self::user()?->role;
    }

    public static function isAdmin()
    {
        return self::role() === 'admin';
    }

    public static function isPetugas()
    {
        return self::role() === 'petugas';
    }
}
