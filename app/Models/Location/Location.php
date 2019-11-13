<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'name', 'country_id', 'city', 'address', 'phone', 'fax', 'email', 
        'time_format_24', 'workdays', 'manager_id', 'timezone_id', 'currency_id'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:191',
        'country_id' => 'nullable|integer|exists:countries,id',
        'city' => 'nullable|string|max:191',
        'address' => 'nullable|string|max:191',
        'phone' => 'nullable|string|max:191',
        'fax' => 'nullable|string|max:191',
        'email' => 'nullable|string|max:191',
        'time_format_24' => 'nullable|integer',
        'workdays' => 'nullable|array',
        'manager_id' => 'nullable|integer|exists:users,id',
        'timezone_id' => 'nullable|integer|exists:timezones,id',
        'currency_id' => 'nullable|integer|exists:currencies,id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'workdays' => 'array'
    ];

    /**
     * Get the shifts for the location.
     */
    public function shifts()
    {
        return $this->hasMany('App\Models\Location\LocationShift');
    }

    /**
     * Add shifts.
     *
     * @param array $shifts
     * @return void
     */
    public function addShifts($shifts)
    {
        if (is_null($shifts) || !is_array($shifts)) {
            return;
        }

        $this->shifts()->delete();

        if ($shifts) {
            foreach ($shifts as $k => $shift) {
                LocationShift::create([
                    'location_id' => $this->id,
                    'name' => $shift['name'],
                    'start' => $shift['start'],
                    'end' => $shift['end']
                ]);
            }
        }
    }
}
