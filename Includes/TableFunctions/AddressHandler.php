<?php

class AddressHandler
{

    private $order_table = "addresses";
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
    private $userID;
    private $userOrderPage;

    private $conn;


    public function __construct($con)
    {
        $this->conn = $con;
        $this->exe_status = "failure";
    }

    function create()
    {

        $stmt = $this->conn->prepare("INSERT INTO " . $this->order_table . "(`shipping_address`, `user_id`, `created_at`, `grand_total`, `delivery_status`, `payment_status_viewed`) VALUES(?,?,?,?,?,?)");

        $this->order_address = htmlspecialchars(strip_tags($this->order_address));
        $this->customer_id = htmlspecialchars(strip_tags($this->customer_id));
        $this->order_total_amount = htmlspecialchars(strip_tags($this->order_total_amount));
        $this->order_status = htmlspecialchars(strip_tags($this->order_status));
        $this->processed_by = htmlspecialchars(strip_tags($this->processed_by));
        $this->order_date = htmlspecialchars(strip_tags($this->order_date));

        $stmt->bind_param("sisisi", $this->order_address, $this->customer_id, $this->order_date, $this->order_total_amount, $this->order_status, $this->processed_by);

        if ($stmt->execute()) {

            $LastInsertOrderid = $this->conn->insert_id;
            $this->order_id = $LastInsertOrderid;
            $this->exe_status = "success";
        } else {
            $this->exe_status = "failure";
        }

        $stmt_OrderDetail = $this->conn->prepare("INSERT INTO " . $this->order_detail_table . "(`order_id`, `product_id`, `price`, `quantity`) VALUES(?,?,?,?)");
        $stmt_OrderDetail->bind_param("iiii", $this->order_id, $this->menu_id, $this->amount, $this->no_of_serving);


        foreach ($this->orderItemList as $i => $i_value) {
            $this->menu_id = htmlspecialchars(strip_tags($i_value->menuId));
            $this->amount = htmlspecialchars(strip_tags($i_value->price));
            $this->no_of_serving = htmlspecialchars(strip_tags($i_value->quantity));
            $this->menu_total_amount = htmlspecialchars(strip_tags(($i_value->price) * ($i_value->quantity)));

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


    function readUserAddress()
    {

        $itemRecords = array();

        $this->userID = htmlspecialchars(strip_tags($_GET["userId"]));
        $this->userOrderPage = htmlspecialchars(strip_tags($_GET["page"]));

//        {"name":"Oweta Jacob Emmy","email":"oweta001@gmail.com","address":"Obote Avenue","country":"Uganda","city":"Lira City","postal_code":null,"phone":"0394510777"}

        if ($this->userID) {
            $this->pageno = floatval($this->userOrderPage);
            $no_of_records_per_page = 10;
            $offset = ($this->pageno - 1) * $no_of_records_per_page;


            $sql = "SELECT COUNT(*) as count FROM " . $this->order_table . " WHERE user_id = " . $this->userID . " limit 1";
            $result = mysqli_query($this->conn, $sql);
            $data = mysqli_fetch_assoc($result);
            $total_rows = floatval($data['count']);
            $total_pages = ceil($total_rows / $no_of_records_per_page);


            if($total_rows > 0){
                $name_sql = "SELECT name, email FROM users WHERE id = " . $this->userID . " limit 1";
                $name_result = mysqli_query($this->conn, $name_sql);
                $name_data = mysqli_fetch_assoc($name_result);
                $name = $name_data['name'];
                $email = $name_data['email'];
            } else {
                $name = null;
                $email = null;
            }




            $itemRecords["page"] = $this->pageno;
            $itemRecords["user_address"] = array();
            $itemRecords["total_pages"] = $total_pages;
            $itemRecords["total_results"] = $total_rows;

            $stmt = $this->conn->prepare("SELECT `id`, `user_id`, `address`, `country`, `city`, `longitude`, `latitude`, `postal_code`, `phone`, `set_default`, `created_at`, `updated_at` FROM addresses WHERE user_id = " . $this->userID . " ORDER BY id LIMIT " . $offset . "," . $no_of_records_per_page . "");
            $stmt->execute();
            $stmt->bind_result($this->id, $this->user_id, $this->address, $this->country, $this->city, $this->longitude, $this->latitude, $this->postal_code, $this->phone, $this->set_default, $this->created_at, $this->updated_at);


            while ($stmt->fetch()) {

                $temp = array();

                $temp['id'] = $this->id;
                $temp['user_id'] = $this->user_id;
                $temp['username'] = $name != null ? $name :"pk";
                $temp['email'] = $email != null ? $name :"pk";
                $temp['address'] = $this->address;
                $temp['country'] = $this->country;
                $temp['city'] = $this->city;
                $temp['phone'] = $this->phone;

                array_push($itemRecords["user_address"], $temp);
            }


            return $itemRecords;
        }

    }


    function delete()
    {

        $stmt = $this->conn->prepare(" DELETE FROM " . $this->order_table . " WHERE id = ?");

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bind_param("i", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
