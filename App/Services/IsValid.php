<?php

namespace App\Services;

class IsValid
{
    private $city_name;
    private $province_id;

    public function cityNAme($data)
    {
        if (empty($data) or is_numeric($data)) {
            return false;
        }
        return true;
    }

    public function provinceID($data)
    {
        if (!(is_numeric($data) and ($data >= 1 and $data <= 31))) {
            return false;
        }
        return true;
    }

    public function city($data)
    {
        $this->cityName = $data['cityName'];
        $this->province_id = $data['province_id'];

        if ($this->cityName($this->cityName) and $this->provinceID($this->province_id)) {
            return true;
        }
        return false;
    }
}
