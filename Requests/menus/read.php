<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../class/FoodMenu.php';

$database = new Database();
$db = $database->getConnection();
 
$menus = new FoodMenu($db);

$menus->menu_id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';

$result = $menus->read();

if($result->num_rows > 0){    
    $itemRecords=array(); 
	while ($item = $result->fetch_assoc()) { 	
        extract($item); 
        $itemDetails=array(
            "menu_id" => $menu_id,
            "menu_name" => $menu_name,
            "price" => $price,
            "description" => $description,
			"menu_type_id" => $menu_type_id,
			"menu_image" => $menu_image,
			"ingredients" => $ingredients,
			"menu_status" => $menu_status,
			"created" => $created,
            "modified" => $modified			
        ); 
       array_push($itemRecords, $itemDetails);
    }    
    http_response_code(200);     
    echo json_encode($itemRecords);
}else{     
    http_response_code(404);     
    echo json_encode(
        array("message" => "No item found.")
    );
} 
?>