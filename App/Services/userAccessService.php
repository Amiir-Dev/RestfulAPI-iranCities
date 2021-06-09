<?php

namespace App\Services;

class userAccessService
{

    public function hasProvinceAccess($user, $province_id)
    {
        return (in_array($user->role, ['admin', 'president']) or
            in_array($province_id, $user->allowed_provinces));
    }
}
