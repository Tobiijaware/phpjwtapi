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
    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->email) && !empty($data->password)){
        $obj->email = $data->email;
        
        $userdata = $obj->checkLogin();

        if(!empty($userdata)){
            $name = $userdata['name'];
            $email = $userdata['email'];
            $password = $userdata['password'];

            if(password_verify($data->password, $password)){
                $payloadInfo = [
                    "iss" => "localhost",
                    "iat" => time(),
                    "nbf" => time() + 10,
                    "exp" => time() + 360,
                    "aud" => "myusers",
                    "data" => [
                        "id" => $userdata['id'],
                        "name" => $userdata['name'],
                        "email" => $userdata['email']
                    ]
                ];

                $secretKey = "owt125";

                $jwt = JWT::encode($payloadInfo, $secretKey);

                http_response_code(200);
                echo json_encode([
                    "status" => 1,
                    "jwt" => $jwt,
                    "message" => "User logged in successfully"
                ]);
            }else{
                http_response_code(404);
                echo json_encode([
                    "status" => 0,
                    "message" => "Invalid Credentials"
                ]);
            }
        }else{
            http_response_code(404);
            echo json_encode([
                "status" => 1,
                "message" => "User Doesn't Exist"
            ]);
        }
    }
        

}else{
    http_response_code(503);
    echo json_encode([
        "status" => 0,
        "message" => "Access Denied"
    ]);

}
