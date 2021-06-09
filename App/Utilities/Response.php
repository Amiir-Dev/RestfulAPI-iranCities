<?php

namespace App\Utilities;

include "../bootstrap/constants.php";
class Response
{

    public static function respond($data, $status_code = HTTP_OK)
    {
        # set http headers
        self::setHeaders($status_code);

        # prepare response data
        $response = [
            'http_status' => $status_code,
            'http_message' => STATUS_TEXTS[$status_code],
            'data' => $data,
        ];

        # convert respone to json and return
        return json_encode($response);
    }

    public static function respondAndDie($data, $status_code = HTTP_OK)
    {
        die(self::respond($data, $status_code));
    }

    public static function setHeaders($status_code = HTTP_OK)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8 ");
        header("Access-Control-Allow-methods: GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("HTTP/1.1 $status_code " . STATUS_TEXTS[$status_code]);
    }
}
