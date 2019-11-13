<?php

namespace App\Models\Area;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AreaUser extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'area_user';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['area_id', 'user_id'];
}
