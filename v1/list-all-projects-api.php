<?php

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once("../config/database.php");
include_once("../classes/users.php");

$connection = new Database();
$db = $connection->connect();
$obj = new Users($db);

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $projects = $obj->getAllProjects();


    if ($projects->num_rows > 0) {
        while($row = $projects->fetch_assoc()){
            $arr = [
                "id" => $row['id'],
                "name" => $row['name'],
                "description" => $row['description'],
                "user_id" => $row['user_id'],
                "status" => $row['status'],
                "created_at" => $row['created'],
            ];

        }
        http_response_code(200);
        echo json_encode([
            "status" => 1,
            "message" => $arr
        ]);
    }else{
        http_response_code(404);
        echo json_encode([
            "status" => 0,
            "message" => "There are no projects available"
        ]);
    }
} else {
    http_response_code(506);
    echo json_encode([
        "status" => 0,
        "message" => "Access Denied"
    ]);
}
