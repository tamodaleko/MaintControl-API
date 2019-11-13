<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'email', 'phone', 'fax', 'office', 'skype'];

    /**
     * Update user profile information.
     *
     * @param array $areas
     * @return bool
     */
    public function updateInformation($email, $phone, $fax, $office, $skype)
    {
        $updates = [];

        if (is_string($email)) {
            $updates['email'] = $email;
        }

        if (is_string($phone)) {
            $updates['phone'] = $phone;
        }

        if (is_string($fax)) {
            $updates['fax'] = $fax;
        }

        if (is_string($office)) {
            $updates['office'] = $office;
        }

        if (is_string($skype)) {
            $updates['skype'] = $skype;
        }

        if ($updates) {
            return $this->update($updates);
        }

        return false;
    }
}
