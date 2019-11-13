<?php

namespace App\Models\Area;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AreaWorkgroup extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'area_workgroup';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['area_id', 'workgroup_id'];
}
