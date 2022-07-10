<?php

//set headers to NOT cache a page
header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
header("Pragma: no-cache"); //HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


include_once '../../Includes/config/Database.php';
include_once  '../../Includes/TableClasses/Cities.php';
include_once '../../Includes/TableFunctions/CityHandler.php';


$database = new Database();
$db = $database->getConnection();


$address = new CityHandler($db);

$result = $address->readcities();



if($result){
    http_response_code(200);
    echo json_encode($result);
}else{
    http_response_code(404);
    echo json_encode(
        array("message" => "No item found.")
    );
}
?>

