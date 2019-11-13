<?php

namespace App\Models\Secure;

use Illuminate\Database\Eloquent\Model;

class SecureQuestion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['question'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'question' => 'required|string|max:191'
    ];
}
