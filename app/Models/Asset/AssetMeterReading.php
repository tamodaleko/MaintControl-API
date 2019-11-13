<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;

class AssetMeterReading extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['asset_id', 'reporter_id', 'value'];
}
