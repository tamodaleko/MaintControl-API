<?php

namespace App\Models\Secure;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SecureAnswer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'question_id', 'answer'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required|integer|exists:users,id',
        'question_id' => 'required|integer|exists:secure_questions,id',
        'answer' => 'required|string|max:191'
    ];

    /**
     * Verify user's secure answer.
     *
     * @param integer $user_id
     * @param integer $question_id
     * @param string $answer
     * @return bool
     */
    public static function verify($user_id, $question_id, $answer)
    {
        $secureAnswer = static::where('user_id', $user_id)
            ->where('question_id', $question_id)
            ->first();

        if (!$secureAnswer) {
            return false;
        }

        if (Str::lower($answer) !== Str::lower($secureAnswer->answer)) {
            return false;
        }

        return true;
    }
}
