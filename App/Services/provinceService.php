<?php

namespace App\Services;

class provinceService
{

    public function get($data)
    {
        $sql = "select * from province";
        $stmt = (DB::connect())->prepare($sql);
        $stmt->execute();
        $records = $stmt->fetchAll(\PDO::FETCH_OBJ);
        return $records;
    }

    public function add($data)
    {
        if (!isValidProvince($data)) {
            return false;
        }
        $sql = "INSERT INTO `province` (`name`) VALUES (:name);";
        $stmt = (DB::connect())->prepare($sql);
        $stmt->execute([':name' => $data['name']]);
        return $stmt->rowCount();
    }

    public function Update($province_id, $name)
    {
        $sql = "update province set name = '$name' where id = $province_id";
        $stmt = (DB::connect())->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function Delete($province_id)
    {
        $sql = "delete from province where id = $province_id";
        $stmt = (DB::connect())->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }
}