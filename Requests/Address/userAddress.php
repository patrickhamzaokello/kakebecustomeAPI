<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


include_once '../../Includes/config/Database.php';
include_once  '../../Includes/TableClasses/Addresses.php';
include_once '../../Includes/TableFunctions/AddressHandler.php';


$database = new Database();
$db = $database->getConnection();


$address = new AddressHandler($db);

$result = $address->readUserAddress();



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

