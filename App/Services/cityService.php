<?php

namespace App\Services;

class cityService
{

    public function getCity($data)
    {
        $province_id = $data['province_id'] ?? null;
        $where = '';

        if (!(isset($province_id)))
            $where = "WHERE province_id = {$province_id} ";


        # Order By
        $orderbyStr = $this->orderBy($data);

        # Pagination
        $limit = $this->pagination($data);

        # Fields Params
        $fields = $this->checkFields($data) ?? '*';

        $sql = "SELECT {$fields} FROM city {$where} {$orderbyStr} {$limit}";
        $stmt = (DB::connect())->prepare($sql);
        $stmt->execute();
        $records = $stmt->fetchAll(\PDO::FETCH_OBJ);
        return $records;
    }

    public function addCity($data)
    {
        $sql = "INSERT INTO `city` (`province_id`, `name`) VALUES (:province_id, :name);";
        $stmt = (DB::connect())->prepare($sql);
        $stmt->execute([':province_id' => $data['province_id'], ':name' => $data['city_name']]);
        return $stmt->rowCount();
    }

    public function UpdateCity($city_id, $name)
    {
        $sql = "UPDATE city SET name = '$name' WHERE id = $city_id";
        $stmt = (DB::connect())->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function DeleteCity($city_id)
    {
        $sql = "DELETE FROM city WHERE id = $city_id";
        $stmt = (DB::connect())->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }




    #---------  Checker Functions ---------#
    public function checkFields($data)
    {
        $fields = $data['fields'];
        if (!isset($fields))
            return false;

        str_replace(' ', '', $fields);
        $fields_array = explode(',', $fields);
        $table_columns = ['id', 'province_id', 'name'];

        foreach ($fields_array as $f)
            if (!in_array($f, $table_columns))
                return false;

        return $fields;
    }

    public function pagination($data)
    {
        $page = $data['page'] ?? null;
        $pagesize = $data['pagesize'] ?? null;
        $limit = '';
        if (is_numeric($page) and is_numeric($pagesize)) {
            $start = ($page - 1) * $pagesize;
            $limit = " LIMIT $start, $pagesize";
        }
        return $limit;
    }

    public function orderBy($data)
    {
        $orderby = strtoupper($data['orderby']) ?? null;
        $orderbyStr = '';
        if (!is_null($orderby or in_array($orderby, ['ASC, DESC'])))
            $orderbyStr = "ORDER BY {$orderby}";

        return $orderbyStr;
    }
}
