<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;

class AssetCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:191'
    ];
}
