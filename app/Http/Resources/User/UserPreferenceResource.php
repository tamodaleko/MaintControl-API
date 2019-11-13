<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\Resource;

class UserPreferenceResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'default_area_id' => $this->default_area_id,
            'display_period' => $this->display_period,
            'display_to' => $this->display_to,
            'display_to_preventive' => $this->display_to_preventive,
            'calendar_start_day' => $this->calendar_start_day,
            'separate_per_shift' => $this->separate_per_shift,
            'hours_on_calendar' => $this->hours_on_calendar
        ];
    }
}
