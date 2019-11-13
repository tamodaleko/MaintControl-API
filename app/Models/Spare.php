<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spare extends Model
{
    const STATUS_ACTIVE = 0;
    const STATUS_ARCHIVED = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'uid', 'name', 'stock', 'manufacturer', 'vendor', 'amount', 'unit_id', 'currency_id'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'uid' => 'required|string|max:191',
        'name' => 'required|string|max:191',
        'stock' => 'required|string|max:191',
        'manufacturer' => 'required|string|max:191',
        'vendor' => 'required|string|max:191',
        'amount' => 'required|numeric',
        'unit_id' => 'nullable|integer|exists:units,id',
        'currency_id' => 'required|integer|exists:currencies,id'
    ];

    /**
     * Status options
     *
     * @var array
     */
    public static $statusOptions = [
        self::STATUS_ACTIVE,
        self::STATUS_ARCHIVED
    ];

    /**
     * Get the currency record associated with the spare.
     */
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

    /**
     * The assets that belong to the spare.
     */
    public function assets()
    {
        return $this->belongsToMany('App\Models\Asset\Asset');
    }

    /**
     * Attach assets.
     *
     * @param array $assets
     * @return void
     */
    public function attachAssets($assets)
    {
        if (is_null($assets)) {
            return;
        }

        if (!$assets || !is_array($assets)) {
            $this->assets()->detach();
        } else {
            $this->assets()->sync($assets);
        }
    }
}
