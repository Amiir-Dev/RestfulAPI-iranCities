<?php

include '../../../loader.php';

use App\Services\cityService;
use App\Utilities\Response;
use App\Utilities\CacheUtility;
use App\Services\tokenService;
use App\Services\userAccessService;
use App\Services\validateService;



# check Authorization (use a jwt token)
$tokenService = new tokenService;
$token = $tokenService->get();
$user = $tokenService->isValid($token);
if (!$user)
    Response::respondAndDie("Invalid Token!!!", HTTP_UNAUTHORIZED);


$request_method = $_SERVER['REQUEST_METHOD'];
// $request_body = json_decode(file_get_contents('php://input'), true);
$request_body = file_get_contents('php://input');


$city_service = new CityService();
$validate_service = new validateService();


switch ($request_method) {
    case 'GET':
        CacheUtility::start();
        $province_id = $_GET['province_id'] ?? null;
        if (!(new userAccessService)->hasProvinceAccess($user, $province_id))
            Response::respondAndDie("You have no access to this province", HTTP_FORBIDDEN);


        #------ Validator ------#
        if (isset($province_id))
            if (!($validate_service->provinceId($province_id)))
                Response::respondAndDie("Invalid Province Input ...", HTTP_NOT_FOUND);

        $request_data = [
            'province_id' => $province_id,
            'page' => $_GET['page'] ?? null,
            'pagesize' => $_GET['pagesize'] ?? null,
            'fields' => $_GET['fields'] ?? null,
            'orderby' => $_GET['orderby'] ?? null
        ];
        $response = $city_service->getCity($request_data);
        echo Response::respond($response, HTTP_OK);
        CacheUtility::end();
        die();


    case 'POST':
        if (!($validate_service->city($request_body)))
            return false;

        $response = $city_service->addCity($request_body);
        Response::respondAndDie($response, HTTP_CREATED);
        break;

    case 'PUT':
        if (!$validate_service->city($request_body))
            Response::respondAndDie('Invalid City Data...', HTTP_NOT_ACCEPTABLE);

        [$city_id, $city_name] = [$request_body['city_id'], $request_body['city_name']];

        $response = $city_service->updateCity($city_id, $city_name);
        if (!$response) 
            Response::respondAndDie('Invalid City Data...', HTTP_NOT_FOUND);
        
        Response::respondAndDie($response, HTTP_OK);


    case 'DELETE':
        $city_id = $_GET['city_id'] ?? null;
        if (!$validate_service->cityId($city_id));
            Response::respondAndDie('Invalid City Data...', HTTP_NOT_FOUND);

        $response = $city_service->deleteCity($city_id);
        Response::respondAndDie($response, HTTP_OK);


    default:
        die('Invalid Method Request');
}
