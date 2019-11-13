<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workgroup extends Model
{
    const STATUS_ACTIVE = 0;
    const STATUS_ARCHIVED = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'supervisor_id'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:191',
        'supervisor_id' => 'nullable|integer|exists:users,id'
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
     * The users that belong to the workgroup.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User\User');
    }

    /**
     * The areas that belong to the workgroup.
     */
    public function areas()
    {
        return $this->belongsToMany('App\Models\Area\Area');
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
}
