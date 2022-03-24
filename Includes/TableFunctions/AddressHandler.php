<?php

class AddressHandler
{

    private $address_table = "addresses";
    public $user_id;
    public $district;
    public $location;
    public $phone;
    private $conn;
    private $exe_status;
    private $address;
    private $country;
    private $city;
    private $set_default;
    private $created_at;
    private $updated_at;
    private $longitude;
    private $latitude;
    private $postal_code;


    public function __construct($con)
    {
        $this->conn = $con;
        $this->exe_status = "failure";
    }

    function create()
    {

        $stmt = $this->conn->prepare("SELECT `user_id`, `address`, `city`, `phone` FROM " . $this->address_table . " WHERE user_id = ? AND (address = ? AND city = ? AND phone = ? )");
        $stmt->bind_param("isss",  $this->user_id, $this->location, $this->district, $this->phone);
        $stmt->execute();
        $stmt->store_result();

        //if the user already exist in the database
        if ($stmt->num_rows > 0) {
            return false;
        } else {

            $stmt = $this->conn->prepare("INSERT INTO " . $this->address_table . "( `user_id`, `address`, `city`, `phone`) VALUES(?,?,?,?)");

            $this->user_id = htmlspecialchars(strip_tags($this->user_id));
            $this->district = htmlspecialchars(strip_tags($this->district));
            $this->location = htmlspecialchars(strip_tags($this->location));
            $this->phone = htmlspecialchars(strip_tags($this->phone));

            $stmt->bind_param("isss", $this->user_id, $this->location, $this->district, $this->phone);

            if ($stmt->execute()) {
                $this->exe_status = "success";
            } else {
                $this->exe_status = "failure";
            }


            if ($this->exe_status == "success") {
                return true;
            }

            return false;
        }


    }


    function readUserAddress()
    {

        $itemRecords = array();

        $userID = htmlspecialchars(strip_tags($_GET["userId"]));
        $userAddressPage = htmlspecialchars(strip_tags($_GET["page"]));

//        {"name":"Oweta Jacob Emmy","email":"oweta001@gmail.com","address":"Obote Avenue","country":"Uganda","city":"Lira City","postal_code":null,"phone":"0394510777"}

        if ($userID) {
            $this->pageno = floatval($userAddressPage);
            $no_of_records_per_page = 10;
            $offset = ($this->pageno - 1) * $no_of_records_per_page;


            $sql = "SELECT COUNT(*) as count FROM " . $this->address_table . " WHERE user_id = " . $userID . " limit 1";
            $result = mysqli_query($this->conn, $sql);
            $data = mysqli_fetch_assoc($result);
            $total_rows = floatval($data['count']);
            $total_pages = ceil($total_rows / $no_of_records_per_page);

            if($total_rows > 0){
                $name_sql = "SELECT name, email FROM users WHERE id = " . $userID . " limit 1";
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

            $stmt = $this->conn->prepare("SELECT `id`, `user_id`, `address`, `country`, `city`, `longitude`, `latitude`, `postal_code`, `phone`, `set_default`, `created_at`, `updated_at` FROM addresses WHERE user_id = " . $userID . " ORDER BY created_at DESC LIMIT " . $offset . "," . $no_of_records_per_page . " ");
            $stmt->execute();
            $stmt->bind_result($this->id, $this->user_id, $this->address, $this->country, $this->city, $this->longitude, $this->latitude, $this->postal_code, $this->phone, $this->set_default, $this->created_at, $this->updated_at);


            while ($stmt->fetch()) {

                $temp = array();

                $temp['id'] = $this->id;
                $temp['user_id'] = $this->user_id;
                $temp['username'] = $name != null ? $name :"kakebe_user";
                $temp['email'] = $email != null ? $email :"user@shopkakebe.com";
                $temp['address'] = $this->address;
                $temp['country'] = $this->country;
                $temp['city'] = $this->city;
                $temp['phone'] = $this->phone;
                $temp['set_default'] = $this->set_default;
                $temp['created_at'] = $this->created_at;
                $temp['updated_at'] = $this->updated_at;
                $temp['longitude'] = $this->longitude;
                $temp['latitude'] = $this->latitude;
                $temp['postal_code'] = $this->postal_code;

                array_push($itemRecords["user_address"], $temp);
            }


            return $itemRecords;
        }

    }


    function delete()
    {

        $stmt = $this->conn->prepare(" DELETE FROM " . $this->address_table . " WHERE id = ?");

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bind_param("i", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
