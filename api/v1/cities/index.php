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
        if (!($IsValid->provinceID($province_id)))
            Response::respondAndDie("Invalid Province Input ...", Response::HTTP_NOT_FOUND);

        $request_data = [
            'province_id' => $province_id
        ];
        $response = $city_service->getCities($request_data);
        Response::respondAndDie($response, Response::HTTP_OK);


    case 'POST':
        if (!($IsValid->city($request_body)))
            return false;

        $response = $city_service->createCities($request_body);
        Response::respondAndDie($response, Response::HTTP_CREATED);


    case 'PUT':
        Response::respondAndDie('PUT', Response::HTTP_OK);


    case 'DELETE':
        Response::respondAndDie('DELETE', Response::HTTP_OK);


    default:
        die('Invalid Method Request');
}
