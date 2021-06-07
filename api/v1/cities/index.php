<?php

include '../../../loader.php';
include '../../../App/iran.php';

use App\Services\CityService;
use App\Services\IsValid;
use App\Utilities\Response;

$request_method = $_SERVER['REQUEST_METHOD'];
$request_body = json_decode(file_get_contents('php://input'), true);
// $request_body = file_get_contents('php://input');
$city_service = new CityService();
$IsValid = new IsValid();


switch ($request_method) {
    case 'GET':
        $province_id = $_GET['province_id'] ?? null;

        #------ Validator ------#
        if (isset($province_id))
            if (!($IsValid->provinceID($province_id)))
                Response::respondAndDie("Invalid Province Input ...", Response::HTTP_NOT_FOUND);

        $request_data = [
            'province_id' => $province_id,
            'page' => $_GET['page'] ?? null,
            'pagesize' => $_GET['pagesize'] ?? null,
        ];
        $response = $city_service->getCities($request_data);
        Response::respondAndDie($response, Response::HTTP_OK);


    case 'POST':
        if (!($IsValid->city($request_body)))
            return false;

        $response = $city_service->createCities($request_body);
        Response::respondAndDie($response, Response::HTTP_CREATED);


    case 'PUT':
        [$city_id, $city_name] = [$request_body['city_id'], $request_body['city_name']];
        if (!is_numeric($city_id) or $IsValid->cityNAme($city_name))
            Response::respondAndDie('Invalid City Data...', Response::HTTP_NOT_ACCEPTABLE);

        $response = $city_service->updateCityName($city_id, $city_name);
        if (!$response) {
            Response::respondAndDie('Invalid City Data...', Response::HTTP_NOT_FOUND);
        }
        Response::respondAndDie($response, Response::HTTP_OK);


    case 'DELETE':
        $city_id = $_GET['city_id'] ?? null;
        if (!is_null($city_id) or is_null($city_id))
            Response::respondAndDie('Invalid City Data...', Response::HTTP_NOT_FOUND);
        $response = $city_service->deleteCity($city_id);
        Response::respondAndDie($response, Response::HTTP_OK);


    default:
        die('Invalid Method Request');
}
