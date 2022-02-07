<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../class/Order.php';

$database = new Database();
$db = $database->getConnection();

 
$order = new Order($db);


// $order->order_id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';



$result = $order->readUserOrders();



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

