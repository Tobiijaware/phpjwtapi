<?php

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

    if(!empty($data->name) && !empty($data->email) && !empty($data->password)){
        $obj->name = $data->name;
        $obj->email = $data->email;
        $obj->password = password_hash($data->password, PASSWORD_DEFAULT);

        $checkUser = $obj->checkEmail();
        if(!empty($checkUser)){
            http_response_code(500);
            echo json_encode([
                "status" => 0,
                "message" => "User Already Exists"
            ]);
        }else{
            $obj->createUser();
            http_response_code(200);
            echo json_encode([
                "status" => 1,
                "message" => "User Successfully Created"
            ]);
        }

       
    }else{
        http_response_code(500);
        echo json_encode([
        "status" => 0,
        "message" => "Failed To Create User"
        ]);
    }

}else{
    http_response_code(503);
    echo json_encode([
        "status" => 0,
        "message" => "Access Denied"
    ]);
}
