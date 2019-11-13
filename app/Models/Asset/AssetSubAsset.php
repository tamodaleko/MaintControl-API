<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AssetSubAsset extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asset_sub_asset';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['asset_id', 'sub_asset_id'];
}
