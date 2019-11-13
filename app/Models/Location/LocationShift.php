<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;

class LocationShift extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['location_id', 'name', 'start', 'end'];
}
