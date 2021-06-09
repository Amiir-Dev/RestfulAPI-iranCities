<?php

include '../../../loader.php';

use App\Services\CityService;
use App\Services\IsValid;
use App\Utilities\Response;
use App\Utilities\CacheUtility;



# check Authorization (use a jwt token)
$token = getBearerToken();
$user = isValidToken($token);
if (!$user)
    Response::respondAndDie("Invalid Token!!!", Response::HTTP_UNAUTHORIZED);

# Authorization OK
 
# get request token and validate it



$request_method = $_SERVER['REQUEST_METHOD'];
// $request_body = json_decode(file_get_contents('php://input'), true);
$request_body = file_get_contents('php://input');


$city_service = new CityService();
$IsValid = new IsValid();


switch ($request_method) {
    case 'GET':
        CacheUtility::start();
        $province_id = $_GET['province_id'] ?? null;

        if(!hasAccessToProvince($user, $province_id))
            Response::respondAndDie("You have no access to this province", Response::HTTP_FORBIDDEN);
        

        #------ Validator ------#
        if (isset($province_id))
            if (!($IsValid->provinceID($province_id)))
                Response::respondAndDie("Invalid Province Input ...", Response::HTTP_NOT_FOUND);

        $request_data = [
            'province_id' => $province_id,
            'page' => $_GET['page'] ?? null,
            'pagesize' => $_GET['pagesize'] ?? null,
            'fields' => $_GET['fields'] ?? null,
            'orderby' => $_GET['orderby'] ?? null
        ];
        $response = $city_service->getCities($request_data);

        echo Response::respond($response, Response::HTTP_OK);
        CacheUtility::end();
        die();


    case 'POST':
        if (!($IsValid->city($request_body)))
            return false;

        $response = $city_service->createCities($request_body);
        Response::respondAndDie($response, Response::HTTP_CREATED);
        break;

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
