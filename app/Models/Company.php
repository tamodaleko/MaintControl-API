<?php

namespace App\Models;

use App\Models\ActivityType;
use App\Models\AdditionalField;
use App\Models\Area\Area;
use App\Models\Asset\Asset;
use App\Models\Spare;
use App\Models\User\User;
use App\Models\Workgroup;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['owner_id', 'name', 'logo_file_id', 'slogan'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'owner_id' => 'required|integer|exists:users,id',
        'name' => 'required|string|max:191|unique:companies',
        'logo_file_id' => 'nullable|integer|exists:files,id',
        'slogan' => 'nullable|string|max:191'
    ];

    /**
     * Get the users for the company.
     */
    public function users()
    {
        return $this->hasMany('App\Models\User\User');
    }

    /**
     * Get the spares for the company.
     */
    public function spares()
    {
        return $this->hasMany('App\Models\Spare');
    }

    /**
     * Get the areas for the company.
     */
    public function areas()
    {
        return $this->hasMany('App\Models\Area\Area');
    }

    /**
     * Get the workgroups for the company.
     */
    public function workgroups()
    {
        return $this->hasMany('App\Models\Workgroup');
    }

    /**
     * Get the assets for the company.
     */
    public function assets()
    {
        return $this->hasMany('App\Models\Asset\Asset');
    }

    /**
     * Get the locations for the company.
     */
    public function locations()
    {
        return $this->hasMany('App\Models\Location\Location');
    }

    /**
     * Get the priorities for the company.
     */
    public function priorities()
    {
        return $this->hasMany('App\Models\Priority');
    }

    /**
     * Get the skills for the company.
     */
    public function skills()
    {
        return $this->hasMany('App\Models\Skill');
    }

    /**
     * Get the permits for the company.
     */
    public function permits()
    {
        return $this->hasMany('App\Models\Permit');
    }

    /**
     * Get the criticals for the company.
     */
    public function criticals()
    {
        return $this->hasMany('App\Models\Critical');
    }

    /**
     * Get the conditions for the company.
     */
    public function conditions()
    {
        return $this->hasMany('App\Models\Condition');
    }

    /**
     * Get the units for the company.
     */
    public function units()
    {
        return $this->hasMany('App\Models\Unit');
    }

    /**
     * Get the currencies for the company.
     */
    public function currencies()
    {
        return $this->hasMany('App\Models\Currency');
    }

    /**
     * Get the asset categories for the company.
     */
    public function assetCategories()
    {
        return $this->hasMany('App\Models\Asset\AssetCategory');
    }

    /**
     * Get the activity types corrective for the company.
     */
    public function activityTypesCO()
    {
        return $this->hasMany('App\Models\ActivityType')->where('type', ActivityType::TYPE_CORRECTIVE);
    }

    /**
     * Get the activity types preventive for the company.
     */
    public function activityTypesPO()
    {
        return $this->hasMany('App\Models\ActivityType')->where('type', ActivityType::TYPE_PREVENTIVE);
    }

    /**
     * Get the additional fields corrective for the company.
     */
    public function additionalFieldsCO()
    {
        return $this->hasMany('App\Models\AdditionalField')->where('type', AdditionalField::TYPE_CORRECTIVE);
    }

    /**
     * Get the additional fields preventive for the company.
     */
    public function additionalFieldsPO()
    {
        return $this->hasMany('App\Models\AdditionalField')->where('type', AdditionalField::TYPE_PREVENTIVE);
    }

    /**
     * Get the active users for the company.
     */
    public function activeUsers()
    {
        return $this->users()->where('status', User::STATUS_ACTIVE);
    }

    /**
     * Get the active spares for the company.
     */
    public function activeSpares()
    {
        return $this->spares()->where('status', Spare::STATUS_ACTIVE);
    }

    /**
     * Get the active areas for the company.
     */
    public function activeAreas()
    {
        return $this->areas()->where('status', Area::STATUS_ACTIVE);
    }

    /**
     * Get the active workgroups for the company.
     */
    public function activeWorkgroups()
    {
        return $this->workgroups()->where('status', Workgroup::STATUS_ACTIVE);
    }

    /**
     * Get the active assets for the company.
     */
    public function activeAssets()
    {
        return $this->assets()->where('status', Asset::STATUS_ACTIVE);
    }

    /**
     * Get the archived users for the company.
     */
    public function archivedUsers()
    {
        return $this->users()->where('status', User::STATUS_ARCHIVED);
    }

    /**
     * Get the archived spares for the company.
     */
    public function archivedSpares()
    {
        return $this->spares()->where('status', Spare::STATUS_ARCHIVED);
    }

    /**
     * Get the archived areas for the company.
     */
    public function archivedAreas()
    {
        return $this->areas()->where('status', Area::STATUS_ARCHIVED);
    }

    /**
     * Get the archived workgroups for the company.
     */
    public function archivedWorkgroups()
    {
        return $this->workgroups()->where('status', Workgroup::STATUS_ARCHIVED);
    }

    /**
     * Get the archived assets for the company.
     */
    public function archivedAssets()
    {
        return $this->assets()->where('status', Asset::STATUS_ARCHIVED);
    }

    /**
     * Get the logo file associated with the company.
     */
    public function logo()
    {
        return $this->belongsTo('App\Models\File', 'logo_file_id');
    }

    /**
     * Update predefined field.
     *
     * @param string $field
     * @param array $values
     * @return void
     */
    public function updatePredefinedField($field, array $values)
    {
        switch ($field) {
            case 'priorities':
                $modelName = '\App\Models\Priority';
                $this->priorities()->delete();
                break;
            case 'skills':
                $modelName = '\App\Models\Skill';
                $this->skills()->delete();
                break;
            case 'permits':
                $modelName = '\App\Models\Permit';
                $this->permits()->delete();
                break;
            case 'criticals':
                $modelName = '\App\Models\Critical';
                $this->criticals()->delete();
                break;
            case 'activityTypesCO':
                $modelName = '\App\Models\ActivityType';
                $type = ActivityType::TYPE_CORRECTIVE;
                $this->activityTypesCO()->delete();
                break;
            case 'activityTypesPO':
                $modelName = '\App\Models\ActivityType';
                $type = ActivityType::TYPE_PREVENTIVE;
                $this->activityTypesPO()->delete();
                break;
            case 'additionalFieldsCO':
                $modelName = '\App\Models\AdditionalField';
                $type = AdditionalField::TYPE_CORRECTIVE;
                $this->additionalFieldsCO()->delete();
                break;
            case 'additionalFieldsPO':
                $modelName = '\App\Models\AdditionalField';
                $type = AdditionalField::TYPE_PREVENTIVE;
                $this->additionalFieldsPO()->delete();
                break;
            case 'assetCategories':
                $modelName = '\App\Models\Asset\AssetCategory';
                $this->assetCategories()->delete();
                break;
            case 'conditions':
                $modelName = '\App\Models\Condition';
                $this->conditions()->delete();
                break;
            case 'units':
                $modelName = '\App\Models\Unit';
                $this->units()->delete();
                break;
        }

        foreach ($values as $value) {
            $value['company_id'] = $this->id;

            if (isset($type)) {
                $value['type'] = $type;
            }

            $modelName::create($value);
        }
    }

    /**
     * Get logo link.
     *
     * @return string
     */
    public function getLogoLink()
    {
        if (!$this->logo) {
            return null;
        }

        return url('/uploads/images/' . $this->logo->folder . '/' . $this->logo->name);
    }
}
