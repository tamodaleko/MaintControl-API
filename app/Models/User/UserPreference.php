<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'default_area_id', 'display_period', 'display_to', 'display_to_preventive', 
        'calendar_start_day', 'separate_per_shift', 'hours_on_calendar'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'display_to' => 'boolean',
        'display_to_preventive' => 'boolean',
        'separate_per_shift' => 'boolean',
        'hours_on_calendar' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'default_area_id' => 'nullable|integer|exists:areas,id',
        'display_period' => 'nullable|string|max:191',
        'display_to' => 'nullable|boolean',
        'display_to_preventive' => 'nullable|boolean',
        'calendar_start_day' => 'nullable|integer',
        'separate_per_shift' => 'nullable|boolean',
        'hours_on_calendar' => 'nullable|boolean'
    ];
}
