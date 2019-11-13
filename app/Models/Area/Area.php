<?php

namespace App\Models\Area;

use App\Models\Asset\Asset;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    const STATUS_ACTIVE = 0;
    const STATUS_ARCHIVED = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'location_id'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:191',
        'location_id' => 'required|integer|exists:locations,id'
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
     * The users that belong to the area.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User\User');
    }

    /**
     * The workgroups that belong to the area.
     */
    public function workgroups()
    {
        return $this->belongsToMany('App\Models\Workgroup');
    }

    /**
     * Get the assets for the area.
     */
    public function assets()
    {
        return $this->hasMany('App\Models\Asset\Asset');
    }

    /**
     * Attach users.
     *
     * @param array $users
     * @return void
     */
    public function attachUsers($users)
    {
        if (is_null($users)) {
            return;
        }

        if (!$users || !is_array($users)) {
            $this->users()->detach();
        } else {
            $this->users()->sync($users);
        }
    }

    /**
     * Attach workgroups.
     *
     * @param array $workgroups
     * @return void
     */
    public function attachWorkgroups($workgroups)
    {
        if (is_null($workgroups)) {
            return;
        }

        if (!$workgroups || !is_array($workgroups)) {
            $this->workgroups()->detach();
        } else {
            $this->workgroups()->sync($workgroups);
        }
    }

    /**
     * Attach assets.
     *
     * @param array $assets
     * @return void
     */
    public function attachAssets($assets)
    {
        if (!$assets || !is_array($assets)) {
            return;
        }

        $assets = Asset::find($assets);

        foreach ($assets as $asset) {
            $asset->area_id = $this->id;
            $asset->save();
        }
    }
}
