<?php
ini_set("display_errors", 1);

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

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $data = json_decode(file_get_contents("php://input"));
    $headers = getallheaders();

    if (!empty($data->name) && !empty($data->description) && !empty($data->status)) {
        try {
            $jwt = $headers['Authorization'];
            $secretKey = "owt125";
            $decodedJwt = JWT::decode($jwt, $secretKey, ['HS256']);

            $obj->user_id = $decodedJwt->data->id;
            $obj->project_name = $data->name;
            $obj->description = $data->description;
            $obj->status = $data->status;

            if ($obj->createProject()) {
                http_response_code(200);
                echo json_encode([
                    "status" => 1,
                    "message" => "Project Created Successfully"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => 0,
                    "message" => "Failed To Create Project"
                ]);
            }
        } catch (\Exception $ex) {
            http_response_code(500);
            echo json_encode([
                "status" => 0,
                "message" => $ex->getMessage()
            ]);
        }
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => 0,
            "message" => "All data needed"
        ]);
    }
} else {
    http_response_code(506);
    echo json_encode([
        "status" => 0,
        "message" => "Access Denied"
    ]);
}
