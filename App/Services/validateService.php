<?php

namespace App\Services;

class validateService
{

    public function city($data)
    {
        return ($this->name($data) and $this->cityId($data));
    }

    public function province($data)
    {
        return ($this->name($data) and $this->provinceId($data));
    }

    public function cityId($data)
    {
        if (!(is_numeric($data['city_id'])))
            return false;
        return true;
    }


    public function provinceId($data)
    {
        $province_id = $data['province_id'];
        if (!(is_numeric($province_id) and ($province_id >= 1 and $province_id <= 31)) or is_null($province_id))
            return false;
        return true;
    }


    public function name($data)
    {
        if (empty($data['city_name']) or is_numeric($data['city_name']))
            return false;
        return true;
    }
}
