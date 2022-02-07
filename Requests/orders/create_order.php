<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/Database.php';
include_once '../class/Order.php';
 
$database = new Database();
$db = $database->getConnection();
 
$items = new Order($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->order_address) && !empty($data->customer_id) &&
!empty($data->total_amount) && !empty($data->order_status)&& !empty($data->processed_by) && !empty($data->orderItemList)){   
  
    $items->order_address = $data->order_address;
    $items->customer_id = $data->customer_id;
    $items->order_total_amount = $data->total_amount;
    $items->order_status = $data->order_status;
    $items->processed_by = $data->processed_by;
    $items->orderItemList = $data->orderItemList;
    $items->order_date = date('Y-m-d H:i:s'); 


    if($items->create()){         
        http_response_code(201);  
        $response['error'] = false;
        $response['message'] = 'Order was created.';
        echo json_encode($response);
      
    } else{         
        http_response_code(503);   
        $response['error'] = true;
        $response['message'] = 'Unable to create Order.';     
        echo json_encode($response);
    }
}else{    
    http_response_code(400);    
    $response['error'] = true;
    $response['message'] = 'Unable to create item. Data is incomplete.';     
    echo json_encode($response);
}
?>