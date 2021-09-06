<?php
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once("../config/database.php");
include_once("../classes/users.php");


$connection = new Database();
$db = $connection->connect();
$obj = new Users($db);

if($_SERVER['REQUEST_METHOD'] === "POST"){
    //$data = json_decode(file_get_contents("php://input"));
    $all_headers = getallheaders();
    $jwt = $all_headers['Authorization'];

    if(!empty($jwt)){

        try {
            $secretKey = "owt125";
            $decodedJwt = JWT::decode($jwt, $secretKey, ['HS256']);
            http_response_code(200);
        echo json_encode([
            "status" => 1,
            "message" => "We got JWT Token",
            "user_data" => $decodedJwt
        ]);
        } catch (\Exception $ex) {
            echo json_encode([
                "status" => 0,
                "message" => $ex->getMessage(),
                
            ]);
        }
       


        
    }
}else{

}