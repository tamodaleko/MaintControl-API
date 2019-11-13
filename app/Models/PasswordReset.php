<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasswordReset extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'token', 'used'];

    /**
     * Generate password reset token.
     *
     * @param \App\Models\User\User $user
     * @return string
     */
    public static function generateToken(User $user)
    {
        do {
            $token = md5(time() . $user->id . Str::random(5));
            $passwordReset = static::where('token', $token)->first();
        } while (!empty($passwordReset));

        static::create([
            'user_id' => $user->id,
            'token' => $token
        ]);

        return $token;
    }
}
