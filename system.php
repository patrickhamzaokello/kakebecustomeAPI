<?php
//set headers to NOT cache a page
header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
header("Pragma: no-cache"); //HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


include_once 'Includes/config/Database.php';
include_once 'Includes/TableFunctions/Order.php';

$database = new Database();
$db = $database->getConnection();


$order = new Order($db);


$result = $order->readTodaysOrders();

if ($result) {
    http_response_code(200);
    $result = json_encode($result);
} else {
    http_response_code(404);
    $result = json_encode(
        array("message" => "No item found.")
    );
}

$obj = json_decode($result, TRUE);

foreach ($obj as $keys) {
    foreach ($keys as $key => $value) {
        ?>
         <p><?php echo $key ?></p>
        <p><?php echo $value ?></p>
        <?php
    }
}
?>


