<?php
// http://localhost/projects/KakebeAPI/Requests/category/sectionedCategory.php?page=2

header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once '../../Includes/config/Database.php';
include_once '../../Includes/TableClasses/FlashDeals.php';
include_once '../../Includes/TableClasses/FlashDealsProducts.php';
include_once '../../Includes/TableClasses/Category.php';
include_once '../../Includes/TableClasses/Product.php';
include_once '../../Includes/TableClasses/Upload.php';
include_once '../../Includes/TableClasses/BusinessSettings.php';
include_once '../../Includes/TableFunctions/HomeFeed.php';

$database = new Database();
$db = $database->getConnection();


$cat_page = (isset($_GET['page']) && $_GET['page']) ? $_GET['page'] : '1';
$category = new HomeFeed($db,$cat_page);


$result = $category->allCombined();

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
