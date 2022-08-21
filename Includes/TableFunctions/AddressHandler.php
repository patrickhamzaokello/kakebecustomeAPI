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
    public $longitude;
    public $latitude;
    private $postal_code;


    public function __construct($con)
    {
        $this->conn = $con;
        $this->exe_status = "failure";
    }

    function create()
    {

        $stmt = $this->conn->prepare("SELECT `user_id`, `address`, `city`, `phone` FROM " . $this->address_table . " WHERE user_id = ? AND (address = ? AND city = ? AND phone = ? )");
        $stmt->bind_param("isss", $this->user_id, $this->location, $this->district, $this->phone);
        $stmt->execute();
        $stmt->store_result();

        //if the user already exist in the database
        if ($stmt->num_rows > 0) {
            return false;
        } else {

            $stmt = $this->conn->prepare("INSERT INTO " . $this->address_table . "( `user_id`, `address`, `city`, `phone`,`latitude`,`longitude`) VALUES(?,?,?,?,?,?)");

            $this->user_id = htmlspecialchars(strip_tags($this->user_id));
            $this->district = htmlspecialchars(strip_tags($this->district));
            $this->location = htmlspecialchars(strip_tags($this->location));
            $this->phone = htmlspecialchars(strip_tags($this->phone));
            $this->latitude = htmlspecialchars(strip_tags($this->latitude));
            $this->longitude = htmlspecialchars(strip_tags($this->longitude));

            $stmt->bind_param("isssdd", $this->user_id, $this->location, $this->district, $this->phone, $this->latitude, $this->longitude);

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

            $itemRecords["page"] = $this->pageno;
            $itemRecords["user_address"] = array();
            $itemRecords["total_pages"] = $total_pages;
            $itemRecords["total_results"] = $total_rows;

            if ($total_rows > 0) {
                $address_ids = array();
                $stmt = $this->conn->prepare("SELECT `id` FROM addresses WHERE user_id = " . $userID . " ORDER BY created_at DESC LIMIT " . $offset . "," . $no_of_records_per_page . " ");
                $stmt->execute();
                $stmt->bind_result($this->id);

                while ($stmt->fetch()) {
                    array_push($address_ids, $this->id);
                }
                foreach ($address_ids as $address_id) {
                    $temp = array();
                    $address = new Addresses($this->conn, $address_id);
                    $temp['id'] = $address->getId();
                    $temp['user_id'] = $address->getUser_id();
                    $temp['username'] = $address->getUser_Name();
                    $temp['email'] = $address->getUser_email();
                    $temp['address'] = $address->getAddress();
                    $temp['country'] = $address->getCountry();
                    $temp['city'] = $address->getCity();
                    $temp['phone'] = $address->getPhone();
                    $temp['set_default'] = $address->getSet_default();
                    $temp['created_at'] = $address->getCreated_at();
                    $temp['updated_at'] = $address->getUpdated_at();
                    $temp['longitude'] = $address->getLongitude();
                    $temp['latitude'] = $address->getLatitude();
                    $temp['postal_code'] = $address->getPostal_code();
                    $temp['shipping_cost'] = $address->getShippingCost();

                    array_push($itemRecords["user_address"], $temp);
                }

            }


            return $itemRecords;
        }

    }


    function readAllAddress()
    {

        $itemRecords = array();

        $userAddressPage = htmlspecialchars(strip_tags($_GET["page"]));


        $this->pageno = floatval($userAddressPage);
        $no_of_records_per_page = 100;
        $offset = ($this->pageno - 1) * $no_of_records_per_page;


        $sql = "SELECT COUNT(*) as count FROM " . $this->address_table . " limit 1";
        $result = mysqli_query($this->conn, $sql);
        $data = mysqli_fetch_assoc($result);
        $total_rows = floatval($data['count']);
        $total_pages = ceil($total_rows / $no_of_records_per_page);

        $itemRecords["page"] = $this->pageno;
        $itemRecords["user_address"] = array();
        $itemRecords["total_pages"] = $total_pages;
        $itemRecords["total_results"] = $total_rows;

        if ($total_rows > 0) {
            $address_ids = array();
            $stmt = $this->conn->prepare("SELECT `id` FROM addresses WHERE (`longitude` IS NOT NULL AND `latitude` IS NOT NULL) AND (`longitude` != 0 AND `latitude` != 0) ORDER BY created_at DESC LIMIT " . $offset . "," . $no_of_records_per_page . " ");
            $stmt->execute();
            $stmt->bind_result($this->id);

            while ($stmt->fetch()) {
                array_push($address_ids, $this->id);
            }
            foreach ($address_ids as $address_id) {
                $temp = array();
                $address = new Addresses($this->conn, $address_id);
                $cphpdate = strtotime($address->getCreated_at());
                $uphpdate = strtotime($address->getUpdated_at());

                $cdate = date('d M Y h:i A', $cphpdate);
                $udate = date('d M Y h:i A', $uphpdate);

                $temp['id'] = $address->getId();
                $temp['user_id'] = $address->getUser_id();
                $temp['username'] = $address->getUser_Name();
                $temp['email'] = $address->getUser_email();
                $temp['address'] = $address->getAddress();
                $temp['country'] = $address->getCountry();
                $temp['city'] = $address->getCity();
                $temp['phone'] = $address->getPhone();
                $temp['set_default'] = $address->getSet_default();
                $temp['created_at'] = $cdate;
                $temp['updated_at'] = $udate;
                $temp['longitude'] = $address->getLongitude();
                $temp['latitude'] = $address->getLatitude();
                $temp['postal_code'] = $address->getPostal_code();
                $temp['shipping_cost'] = $address->getShippingCost();
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
