<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalField extends Model
{
    const TYPE_CORRECTIVE = 'corrective';
    const TYPE_PREVENTIVE = 'preventive';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'type'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:191',
        'type' => 'required|string|max:191'
    ];
}
