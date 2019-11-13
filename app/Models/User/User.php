<?php

namespace App\Models\User;

use App\Models\Area\Area;
use App\Models\Asset\Asset;
use App\Models\Location\Location;
use App\Models\PasswordReset;
use App\Models\Spare;
use App\Models\Workgroup;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Validation\Rule;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    const ROLE_USER = 'user';
    const ROLE_EXECUTOR = 'executor';
    const ROLE_SUPERVISOR = 'supervisor';
    const ROLE_PLANNER = 'planner';
    const ROLE_ADMIN = 'admin';

    const STATUS_ACTIVE = 0;
    const STATUS_ARCHIVED = 1;

    /**
     * The guard name for roles.
     *
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'name', 'username', 'password', 'position', 'avatar_file_id', 'skill_id', 'currency_id', 'rate'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:191',
        'username' => 'required|string|max:191|unique:users',
        'password' => 'required_without:invite|string|max:191',
        'roles' => 'required|array',
        'invite' => 'required_without:password|string|max:191',
        'position' => 'nullable|string|max:191',
        'avatar_file_id' => 'nullable|integer|exists:files,id',
        'skill_id' => 'nullable|integer|exists:skills,id',
        'currency_id' => 'nullable|integer|exists:currencies,id',
        'rate' => 'nullable|numeric'
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
     * Get the company associated with the user.
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * Get the avatar file associated with the asset.
     */
    public function avatar()
    {
        return $this->belongsTo('App\Models\File', 'avatar_file_id');
    }

    /**
     * Get the profile record associated with the user.
     */
    public function profile()
    {
        return $this->hasOne('App\Models\User\UserProfile');
    }

    /**
     * Get the preference record associated with the user.
     */
    public function preference()
    {
        return $this->hasOne('App\Models\User\UserPreference');
    }

    /**
     * Get the invite record associated with the user.
     */
    public function invite()
    {
        return $this->hasOne('App\Models\User\UserInvite', 'invited_user_id');
    }

    /**
     * The workgroups that belong to the user.
     */
    public function workgroups()
    {
        return $this->belongsToMany('App\Models\Workgroup');
    }

    /**
     * The areas that belong to the user.
     */
    public function areas()
    {
        return $this->belongsToMany('App\Models\Area\Area');
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
     * Attach areas.
     *
     * @param array $areas
     * @return void
     */
    public function attachAreas($areas)
    {
        if (is_null($areas)) {
            return;
        }

        if (!$areas || !is_array($areas)) {
            $this->areas()->detach();
        } else {
            $this->areas()->sync($areas);
        }
    }

    /**
     * Get attached areas for the user.
     *
     * @return array
     */
    public function getAttachedAreas()
    {
        if (!$this->hasAnyRole([self::ROLE_EXECUTOR, self::ROLE_SUPERVISOR])) {
            return $this->areas;
        }

        $areaIds = [];

        foreach ($this->workgroups as $workgroup) {
            foreach ($workgroup->areas->pluck('id') as $id) {
                if (!in_array($id, $areaIds)) {
                    $areaIds[] = $id;
                }
            }
        }

        return Area::whereIn('id', $areaIds)->get();
    }

    /**
     * Get available users for the user.
     *
     * @param int $area_id
     * @return array
     */
    public function getAvailableUsers($area_id = null)
    {
        $areaIds = Area::select('id')
            ->join('area_user as au', 'au.area_id', '=', 'areas.id')
            ->where('au.user_id', $this->id)
            ->pluck('id');

        $workgroupIds = Workgroup::select('id')
            ->leftJoin('area_workgroup as aw', 'aw.workgroup_id', '=', 'workgroups.id')
            ->where(function ($query) use ($areaIds) {
                $query->whereIn('aw.area_id', $areaIds)
                    ->orWhere('aw.area_id', null);
            })
            ->pluck('id');

        $users = static::select('users.*')
            ->leftJoin('area_user as au', 'au.user_id', '=', 'users.id')
            ->leftJoin('user_workgroup as uw', 'uw.user_id', '=', 'users.id')
            ->where(function ($query) use ($areaIds, $workgroupIds) {
                $query->whereIn('au.area_id', $areaIds)
                    ->orWhereIn('uw.workgroup_id', $workgroupIds);
            })
            ->distinct();

        if ($area_id) {
            $users->where('au.area_id', $area_id);
        }

        return $users->get();
    }

    /**
     * Get available areas for the user.
     *
     * @return array
     */
    public function getAvailableAreas()
    {
        return $this->areas;
    }

    /**
     * Get available assets for the user.
     *
     * @param int $area_id
     * @return array
     */
    public function getAvailableAssets($area_id = null)
    {
        $assets = Asset::select('assets.*')
            ->leftJoin('area_user as au', 'au.area_id', '=', 'assets.area_id')
            ->where(function ($query) {
                $query->where('au.user_id', $this->id)
                    ->orWhere('assets.area_id', null);
            })
            ->where('status', Asset::STATUS_ACTIVE)
            ->distinct();

        if ($area_id) {
            $assets->where('assets.area_id', $area_id);
        }

        return $assets->get();
    }

    /**
     * Get available workgroups for the user.
     *
     * @param int $area_id
     * @return array
     */
    public function getAvailableWorkgroups($area_id = null)
    {
        $workgroups = Workgroup::select('workgroups.*')
            ->leftJoin('area_workgroup as aw', 'aw.workgroup_id', '=', 'workgroups.id')
            ->leftJoin('area_user as au', 'au.area_id', '=', 'aw.area_id')
            ->where(function ($query) {
                $query->where('au.user_id', $this->id)
                    ->orWhere('aw.area_id', null);
            })
            ->where('status', Workgroup::STATUS_ACTIVE)
            ->distinct();

        if ($area_id) {
            $workgroups->where('aw.area_id', $area_id)
                ->orWhere('aw.area_id', null);
        }

        return $workgroups->get();
    }

    /**
     * Get available spares for the user.
     *
     * @return array
     */
    public function getAvailableSpares()
    {
        return Spare::all();
    }

    /**
     * Get available locations for the user.
     *
     * @return array
     */
    public function getAvailableLocations()
    {
        return Location::select('locations.*')
            ->join('areas as a', 'a.location_id', '=', 'locations.id')
            ->join('area_user as au', 'au.area_id', '=', 'a.id')
            ->where('au.user_id', $this->id)
            ->distinct()
            ->get();
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
}
