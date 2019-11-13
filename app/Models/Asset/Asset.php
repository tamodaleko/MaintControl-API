<?php

namespace App\Models\Asset;

use App\Models\Asset\AssetMeterReading;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    const STATUS_ACTIVE = 0;
    const STATUS_ARCHIVED = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'name', 'uid', 'area_id', 'category_id', 'condition_id', 'avatar_file_id', 'meter_readings', 
        'location_description', 'manufacturer', 'model', 'serial', 'installed'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:191',
        'uid' => 'required|string|max:191',
        'area_id' => 'required|integer|exists:areas,id',
        'category_id' => 'nullable|integer|exists:asset_categories,id',
        'condition_id' => 'nullable|integer|exists:conditions,id',
        'avatar_file_id' => 'nullable|integer|exists:files,id',
        'meter_readings' => 'nullable|integer',
        'location_description' => 'nullable|string|max:191',
        'manufacturer' => 'nullable|string|max:191',
        'model' => 'nullable|string|max:191',
        'serial' => 'nullable|string|max:191',
        'installed' => 'nullable|string|max:191'
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
     * The spares that belong to the asset.
     */
    public function spares()
    {
        return $this->belongsToMany('App\Models\Spare');
    }

    /**
     * Get the sub assets for the asset.
     */
    public function subAssets()
    {
        return $this->belongsToMany('App\Models\Asset\Asset', 'asset_sub_asset', 'asset_id', 'sub_asset_id');
    }

    /**
     * Get the meter readings for the asset.
     */
    public function meterReadings()
    {
        return $this->hasMany('App\Models\Asset\AssetMeterReading')->orderBy('created_at', 'desc');
    }

    /**
     * Get the avatar file associated with the asset.
     */
    public function avatar()
    {
        return $this->belongsTo('App\Models\File', 'avatar_file_id');
    }

    /**
     * Attach spares.
     *
     * @param array $spares
     * @return void
     */
    public function attachSpares($spares)
    {
        if (is_null($spares)) {
            return;
        }

        if (!$spares || !is_array($spares)) {
            $this->spares()->detach();
        } else {
            $this->spares()->sync($spares);
        }
    }

    /**
     * Attach sub assets.
     *
     * @param array $subAssets
     * @return void
     */
    public function attachSubAssets($subAssets)
    {
        if (is_null($subAssets)) {
            return;
        }

        if (!$subAssets || !is_array($subAssets)) {
            $this->subAssets()->detach();
        } else {
            $this->subAssets()->sync($subAssets);
        }
    }

    /**
     * Get avatar link.
     *
     * @return string
     */
    public function getAvatarLink()
    {
        if (!$this->avatar) {
            return null;
        }

        return url('/uploads/images/' . $this->avatar->folder . '/' . $this->avatar->name);
    }

    /**
     * Add meter reading record.
     *
     * @param int|null $reporter_id
     * @return bool
     */
    public function logMeterReading($reporter_id = null)
    {
        return AssetMeterReading::create([
            'asset_id' => $this->id,
            'reporter_id' => $reporter_id,
            'value' => $this->meter_readings
        ]);
    }
}
