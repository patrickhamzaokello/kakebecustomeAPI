<?php
//set headers to NOT cache a page
header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
header("Pragma: no-cache"); //HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../../Includes/config/Database.php';
include_once '../../Includes/TableFunctions/AddressHandler.php';
 
$database = new Database();
$db = $database->getConnection();

$address = new AddressHandler($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id) && !empty($data->district) &&
!empty($data->location) && !empty($data->phone)){

    $address->user_id = $data->user_id;
    $address->district = $data->district;
    $address->location = $data->location;
    $address->phone = $data->phone;


    if($address->create()){
        http_response_code(201);  
        $response['error'] = false;
        $response['message'] = 'Address created.';
        echo json_encode($response);
      
    } else{         
        http_response_code(503);   
        $response['error'] = true;
        $response['message'] = 'Unable to create Address.';
        echo json_encode($response);
    }
}else{    
    http_response_code(400);    
    $response['error'] = true;
    $response['message'] = 'Unable to create Address. Data is incomplete.';
    echo json_encode($response);
}
?>