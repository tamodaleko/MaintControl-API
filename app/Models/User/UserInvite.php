<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserInvite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'invited_user_id', 'code', 'used'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'used' => 'boolean'
    ];

    /**
     * Get the invited user associated with the invite.
     */
    public function invitedUser()
    {
        return $this->belongsTo('App\Models\User\User', 'invited_user_id');
    }

    /**
     * Get user invite by invite code.
     *
     * @param string $code
     * @return static
     */
    public static function getByCode($code)
    {
        return static::whereNotNull('invited_user_id')
            ->where('code', $code)
            ->first();
    }

    /**
     * Get invited user by invite code.
     *
     * @param string $code
     * @return \App\Models\User\User
     */
    public static function getInvitedUserByCode($code)
    {
        $invite = static::getByCode($code);

        if (!$invite) {
            return null;
        }

        return $invite->invitedUser;
    }
}
