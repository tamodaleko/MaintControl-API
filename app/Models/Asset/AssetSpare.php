<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AssetSpare extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asset_spare';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['asset_id', 'spare_id'];
}
