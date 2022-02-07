<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/Database.php';
include_once '../class/FoodMenu.php';
 
$database = new Database();
$db = $database->getConnection();
 
$items = new FoodMenu($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->menu_name) && !empty($data->price)  && !empty($data->description) && !empty($data->menu_type_id)&& !empty($data->menu_image) && !empty($data->ingredients) && !empty($data->menu_status) && !empty($data->created)){   
  
    $items->menu_name = $data->menu_name;
    $items->price = $data->price;
    $items->description = $data->description;
    $items->menu_type_id = $data->menu_type_id;
    $items->menu_image = $data->menu_image;
    $items->ingredients = $data->ingredients;
    $items->menu_status = $data->menu_status;
    $items->created = date('Y-m-d H:i:s'); 


    if($items->create()){         
        http_response_code(201);         
        echo json_encode(array("message" => "Menu was added."));
    } else{         
        http_response_code(503);        
        echo json_encode(array("message" => "Unable to create item."));
    }

}else{    
    http_response_code(400);    
    echo json_encode(array("message" => "Unable to create item. Data is incomplete."));
}
?>