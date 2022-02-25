<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../Includes/config/Database.php';
include_once '../../Includes/TableClasses/Category.php';
include_once '../../Includes/TableClasses/Product.php';
include_once '../../Includes/TableClasses/Upload.php';
include_once '../../Includes/TableClasses/BusinessSettings.php';
include_once '../../Includes/TableFunctions/ProductDetails.php';

$database = new Database();
$db = $database->getConnection();

 
$menus = new ProductDetails($db);

$menus->menu_id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';

$result = $menus->parentCategoryProducts();

if($result){    
    http_response_code(200);     
    echo json_encode($result);
}else{     
    http_response_code(404);     
    echo json_encode(
        array("message" => "No item found.")
    );
} 


