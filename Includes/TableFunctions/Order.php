<?php

class Order
{

    private $order_table = "orders";
    private $order_detail_table = "order_details";
    public $order_id;
    public $order_address;
    public $customer_id;
    public $order_total_amount;
    public $order_status;
    public $processed_by;
    public $orderItemList;
    public $order_date;

    //orderdetails page
    public $menu_id;
    public $amount;
    public $no_of_serving;
    public $menu_total_amount;
    private $exe_status;

    // order private
    private $userOrderid;
    private $userOrderPage;

    //shiping cost;
    private $shipping_cost;

    private $conn;


    public function __construct($con)
    {
        $this->conn = $con;
        $this->exe_status = "failure";
    }

    function create()
    {

        $stmt = $this->conn->prepare("INSERT INTO " . $this->order_table . "(`shipping_address`, `user_id`, `created_at`, `grand_total`, `delivery_status`, `payment_status_viewed`, `payment_type`, `code`, `date`) VALUES(?,?,?,?,?,?,?,?,?)");

        $this->order_address = strip_tags($this->order_address);
        $this->customer_id = htmlspecialchars(strip_tags($this->customer_id));
        $this->order_total_amount = htmlspecialchars(strip_tags($this->order_total_amount));
        $this->order_status = htmlspecialchars(strip_tags($this->order_status));
        $this->processed_by = htmlspecialchars(strip_tags($this->processed_by));
        $this->order_date = htmlspecialchars(strip_tags($this->order_date));
        $cashonDelivery = "cash_on_delivery";

        $currentTimeinSeconds = time();
        $currentDate = date('Ymd', $currentTimeinSeconds);
        $code = $currentDate . "-" . $currentTimeinSeconds;

        $stmt->bind_param("sisisissi", $this->order_address, $this->customer_id, $this->order_date, $this->order_total_amount, $this->order_status, $this->processed_by, $cashonDelivery, $code, $currentTimeinSeconds);

        if ($stmt->execute()) {

            $LastInsertOrderid = $this->conn->insert_id;
            $this->order_id = $LastInsertOrderid;
            $this->exe_status = "success";
        } else {
            $this->exe_status = "failure";
        }

        // shipping cost getting
        $sh_key = 'city';
        $sh_city = json_decode($this->order_address)->$sh_key;
        $sh_result = mysqli_query($this->conn, "SELECT cost FROM `cities` WHERE name = '" . $sh_city . "' LIMIT 1;");
        $sh_data = mysqli_fetch_assoc($sh_result);
        if ($sh_data != null) {
            $this->shipping_cost = floatval($sh_data['cost']);
        } else {
            $this->shipping_cost = floatval(0);
        }

        $stmt_OrderDetail = $this->conn->prepare("INSERT INTO " . $this->order_detail_table . "(`order_id`, `product_id`, `price`, `quantity`, `shipping_cost`) VALUES(?,?,?,?,?)");
        $stmt_OrderDetail->bind_param("iiiii", $this->order_id, $this->menu_id, $this->amount, $this->no_of_serving, $this->shipping_cost);

        foreach ($this->orderItemList as $i => $i_value) {
            $this->menu_id = htmlspecialchars(strip_tags($i_value->menuId));
            $this->amount = htmlspecialchars(strip_tags($i_value->price));
            $this->no_of_serving = htmlspecialchars(strip_tags($i_value->quantity));
            $this->menu_total_amount = htmlspecialchars(strip_tags(($i_value->price) * ($i_value->quantity)));
            if($i === array_key_first($this->orderItemList)){
                $this->shipping_cost = $this->shipping_cost;
            } else {
                $this->shipping_cost = 0;
            }

            if ($stmt_OrderDetail->execute()) {
                $this->exe_status = "success";
            } else {
                $this->exe_status = "failure";
            }
        }


        if ($this->exe_status == "success") {
            return true;
        }

        return false;
    }


    function readUserOrders()
    {
        $itemRecords = array();

        $this->userOrderid = htmlspecialchars(strip_tags($_GET["customerId"]));
        $this->userOrderPage = htmlspecialchars(strip_tags($_GET["page"]));

        if (htmlspecialchars(strip_tags($_GET["customerId"])) != null) {
            $this->pageno = floatval($this->userOrderPage);
            $no_of_records_per_page = 10;
            $offset = ($this->pageno - 1) * $no_of_records_per_page;

            $sql = "SELECT COUNT(*) as count FROM " . $this->order_table . " WHERE user_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $this->userOrderid);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $total_rows = floatval($data['count']);
            $total_pages = ceil($total_rows / $no_of_records_per_page);

            $itemRecords["page"] = $this->pageno;
            $itemRecords["results"] = array();
            $itemRecords["total_pages"] = $total_pages;
            $itemRecords["total_results"] = $total_rows;

            $stmt->close(); // Close the previous statement

            $stmt = $this->conn->prepare("SELECT `id`, `shipping_address`, `user_id`, `created_at`, `grand_total`, `delivery_status`, `payment_status_viewed`  FROM " . $this->order_table . " WHERE user_id = ? ORDER BY id DESC LIMIT ?, ?");
            $stmt->bind_param("iii", $this->userOrderid, $offset, $no_of_records_per_page);
        } else {
            $stmt = $this->conn->prepare("SELECT `id`, `shipping_address`, `user_id`, `created_at`, `grand_total`, `delivery_status`, `payment_status_viewed` FROM " . $this->order_table . " ORDER BY id DESC");
        }

        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($this->order_id, $this->order_address, $this->customer_id, $this->order_date, $this->total_amount, $this->order_status, $this->processed_by);

        $numberofrows = $stmt->num_rows;

        if ($numberofrows > 0) {
            while ($stmt->fetch()) {
                $temp = array();

                $data_address = json_decode($this->order_address);
                $data_address->country = "Uganda";
                $phpdate = strtotime($this->order_date);
                $mysqldate = date('d M Y h:i A', $phpdate);
                $orderDetails = $this->fetchOrderDetails($this->order_id);
//                $data_address->country . " , " . $data_address->city . ", " . $data_address->address . " , " . $data_address->phone . " , " . $data_address->email . "\n" .

                $temp['order_id'] = $this->order_id;
                $temp['order_address'] = $orderDetails;
                $temp['customer_id'] = $this->customer_id;
                $temp['order_date'] = $mysqldate;
                $temp['total_amount'] = $this->total_amount;
                $temp['order_status'] = $this->order_status;
                $temp['processed_by'] = $this->processed_by;


                array_push($itemRecords["results"], $temp);
            }
        } else {
            $temp = array();

            $temp['order_id'] = 0;
            $temp['order_address'] = "null";
            $temp['customer_id'] = 0;
            $temp['order_date'] = "null";
            $temp['total_amount'] = 0;
            $temp['order_status'] = "null";
            $temp['processed_by'] = 0;

            array_push($itemRecords["results"], $temp);
        }

        return $itemRecords;
    }

    function fetchOrderDetails($order_id)
    {
        $orderDetails = array();

        $stmt = $this->conn->prepare("SELECT `product_id`, `price` FROM `order_details` WHERE `order_id` = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($product_id, $price);

        while ($stmt->fetch()) {
            // Fetch product name from the `products` table based on $product_id
            $product_query = "SELECT `name` FROM `products` WHERE `id` = ?";
            $product_stmt = $this->conn->prepare($product_query);
            $product_stmt->bind_param("i", $product_id);
            $product_stmt->execute();
            $product_stmt->bind_result($product_name);

            if ($product_stmt->fetch()) {
                $orderDetails[] = $product_name . " (" . number_format($price, 2, '.', ',') . " UGX)";
            } else {
                $orderDetails[] = "Product not found"; // Handle the case where the product is not found.
            }
            $product_stmt->close(); // Close the product statement
        }

        $stmt->close();

        return implode("\n", $orderDetails); // Separate order details with newline characters
    }




    function readTodaysOrders()
    {

        $itemRecords = array();


        $orders_del = mysqli_query($this->conn, "SELECT `id`, `shipping_address`, `user_id`, `created_at`, `grand_total`, `delivery_status`, `payment_status_viewed`  FROM " . $this->order_table . " WHERE (DATE(`created_at`) = CURDATE()) AND (`delivery_status` != 'delivered') ORDER BY `orders`.`created_at` DESC  LIMIT 10  ");

        while ($row = mysqli_fetch_array($orders_del)) {

            array_push($itemRecords, $row);

        }


        return $itemRecords;
    }


}
