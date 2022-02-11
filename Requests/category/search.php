<?php
// http://localhost/projects/KakebeAPI/Requests/category/search.php?query="hello pk"page=2


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once '../../Includes/config/Database.php';
include_once '../../Includes/TableClasses/Category.php';
include_once '../../Includes/TableClasses/Product.php';
include_once '../../Includes/TableClasses/Upload.php';
include_once '../../Includes/TableClasses/BusinessSettings.php';
include_once '../../Includes/TableFunctions/SearchFunctions.php';

$database = new Database();
$db = $database->getConnection();


$cat_page = (isset($_GET['page']) && $_GET['page']) ? $_GET['page'] : '1';
$cat_search_query = (isset($_GET['query']) && $_GET['query']) ? $_GET['query'] : '';
$category = new SearchFunctions($db,$cat_search_query,$cat_page);


$result = $category->searchMain();

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
