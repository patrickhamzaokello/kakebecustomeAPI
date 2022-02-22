<?php

class ProductDetails
{

    private $itemsTable = "tblmenu";
    private $ImageBasepath = "https://zodongofoods.com/admin/pages/";
    public $menu_id;
    public $menu_name;
    public $price;
    public $description;
    public $menu_type_id;
    public $menu_image;
    public $ingredients;
    public $menu_status;
    public $created;
    public $modified;
    public $rating;
    public $input_menu_type_id;
    public $pageno;
    private $conns;


    public function __construct($con)
    {
        $this->conns = $con;
    }


    //get selected category and products in it
    function readPage()
    {

        $itemRecords = array();

        $this->menu_id = htmlspecialchars(strip_tags($_GET["page"]));
        $this->input_menu_type_id = htmlspecialchars(strip_tags($_GET["category"]));


        if ($this->menu_id) {
            $this->pageno = floatval($this->menu_id);
            $no_of_records_per_page = 10;
            $offset = ($this->pageno - 1) * $no_of_records_per_page;

            $sql = "SELECT COUNT(*) as count FROM " . $this->itemsTable . " WHERE menu_type_id = " . $this->input_menu_type_id . " limit 1";
            $result = mysqli_query($this->conns, $sql);
            $data = mysqli_fetch_assoc($result);
            $total_rows = floatval($data['count']);
            $total_pages = ceil($total_rows / $no_of_records_per_page);


            $menu_type_sql = "SELECT * FROM tblmenutype WHERE id = " . $this->input_menu_type_id . " limit 1";
            $menu_type_result = mysqli_query($this->conns, $menu_type_sql);
            $menu_type_data = mysqli_fetch_assoc($menu_type_result);

            $menu_type_name = $menu_type_data['name'];
            $menu_type_description = $menu_type_data['description'];
            $menu_type_imageCover = $menu_type_data['imageCover'];
            $menu_type_created = $menu_type_data['created'];


            $itemRecords["page"] = $this->pageno;
            $itemRecords["results"] = array();
            $itemRecords["total_pages"] = $total_pages;
            $itemRecords["total_results"] = $total_rows;


            $stmt = $this->conns->prepare("SELECT menu_id,menu_name, price, description, menu_type_id, menu_image,backgroundImage,ingredients, menu_status, created, modified,rating FROM " . $this->itemsTable . " WHERE menu_type_id = " . $this->input_menu_type_id . " ORDER BY menu_id LIMIT " . $offset . "," . $no_of_records_per_page);
        } else {
            $stmt = $this->conns->prepare("SELECT menu_id,menu_name, price, description, menu_type_id, menu_image,backgroundImage, ingredients, menu_status, created, modified,rating FROM " . $this->itemsTable);
        }


        $stmt->execute();
        $stmt->bind_result($this->menu_id, $this->menu_name, $this->price, $this->description, $this->menu_type_id, $this->menu_image, $this->backgroundImage, $this->ingredients, $this->menu_status, $this->created, $this->modified, $this->rating);


        if ($this->pageno == 1) {
            $menu_type_temp = array();

            $menu_type_temp['menu_name'] = $menu_type_name;
            $menu_type_temp['description'] = $menu_type_description;
            $menu_type_temp['menu_image'] = $this->ImageBasepath . $menu_type_imageCover;
            $menu_type_temp['backgroundImage'] = $this->ImageBasepath . $menu_type_imageCover;
            $menu_type_temp['created'] = $menu_type_created;


            array_push($itemRecords["results"], $menu_type_temp);
        }


        while ($stmt->fetch()) {

            $temp = array();

            $temp['menu_id'] = $this->menu_id;
            $temp['menu_name'] = $this->menu_name;
            $temp['price'] = $this->price;
            $temp['description'] = $this->description;
            $temp['menu_type_id'] = $this->menu_type_id;
            $temp['menu_image'] = $this->ImageBasepath . $this->menu_image;
            $temp['backgroundImage'] = $this->ImageBasepath . $this->backgroundImage;
            $temp['ingredients'] = $this->description;
            $temp['menu_status'] = $this->menu_type_id;
            $temp['created'] = $this->created;
            $temp['modified'] = $this->modified;
            $temp['rating'] = $this->rating;


            array_push($itemRecords["results"], $temp);
        }


        return $itemRecords;
    }

    //get selected menu details and similar product
    function readMenuDetail()
    {

        $itemRecords = array();

        $this->menu_id = htmlspecialchars(strip_tags($_GET["menuId"]));
        $this->pageno = htmlspecialchars(strip_tags($_GET["page"]));
        $this->input_menu_type_id = htmlspecialchars(strip_tags($_GET["category"]));

        if ($this->menu_id) {
            $this->pageno = floatval($this->pageno);
            $no_of_records_per_page = 6;
            $offset = ($this->pageno - 1) * $no_of_records_per_page;

            $sql = "SELECT COUNT(*) as count FROM " . $this->itemsTable . " WHERE menu_type_id = " . $this->input_menu_type_id . " limit 1";
            $result = mysqli_query($this->conns, $sql);
            $data = mysqli_fetch_assoc($result);
            $total_rows = floatval($data['count']);
            $total_pages = ceil($total_rows / $no_of_records_per_page);


            $menu_type_sql = "SELECT menu_id,menu_name, price, description, menu_type_id, menu_image,backgroundImage,ingredients, menu_status, created, modified,rating FROM " . $this->itemsTable . " WHERE menu_id = " . $this->menu_id . " limit 1";
            $menu_type_result = mysqli_query($this->conns, $menu_type_sql);
            $menu_type_data = mysqli_fetch_assoc($menu_type_result);

            $menu_id = $menu_type_data['menu_id'];
            $menu_name = $menu_type_data['menu_name'];
            $price = $menu_type_data['price'];
            $description = $menu_type_data['description'];
            $menu_type_id = $menu_type_data['menu_type_id'];
            $menu_image = $menu_type_data['menu_image'];
            $backgroundImage = $menu_type_data['backgroundImage'];
            $ingredients = $menu_type_data['ingredients'];
            $menu_status = $menu_type_data['menu_status'];
            $created = $menu_type_data['created'];
            $modified = $menu_type_data['modified'];
            $rating = $menu_type_data['rating'];


            $itemRecords["page"] = $this->pageno;
            $itemRecords["results"] = array();
            $itemRecords["total_pages"] = $total_pages;
            $itemRecords["total_results"] = $total_rows;


            $stmt = $this->conns->prepare("SELECT menu_id,menu_name, price, description, menu_type_id, menu_image,backgroundImage,ingredients, menu_status, created, modified,rating FROM " . $this->itemsTable . " WHERE menu_id != " . $this->menu_id . " AND menu_type_id = " . $this->input_menu_type_id . " ORDER BY menu_id LIMIT " . $offset . "," . $no_of_records_per_page);
        } else {
            $stmt = $this->conns->prepare("SELECT menu_id,menu_name, price, description, menu_type_id, menu_image,backgroundImage, ingredients, menu_status, created, modified,rating FROM " . $this->itemsTable);
        }


        $stmt->execute();
        $stmt->bind_result($menu_id, $menu_name, $price, $description, $menu_type_id, $menu_image, $backgroundImage, $ingredients, $menu_status, $created, $modified, $rating);


        if ($this->pageno == 1) {
            $menudetailtemp = array();


            $menudetailtemp['menu_id'] = floatval($menu_id);
            $menudetailtemp['menu_name'] = $menu_name;
            $menudetailtemp['price'] = floatval($price);
            $menudetailtemp['description'] = $description;
            $menudetailtemp['menu_type_id'] = floatval($menu_type_id);
            $menudetailtemp['menu_image'] = $this->ImageBasepath . $menu_image;
            $menudetailtemp['backgroundImage'] = $this->ImageBasepath . $backgroundImage;
            $menudetailtemp['ingredients'] = $ingredients;
            $menudetailtemp['menu_status'] = floatval($menu_status);
            $menudetailtemp['created'] = $created;
            $menudetailtemp['modified'] = $modified;
            $menudetailtemp['rating'] = floatval($rating);


            array_push($itemRecords["results"], $menudetailtemp);
        }


        while ($stmt->fetch()) {

            $temp = array();

            $temp['menu_id'] = $menu_id;
            $temp['menu_name'] = $menu_name;
            $temp['price'] = $price;
            $temp['description'] = $description;
            $temp['menu_type_id'] = $menu_type_id;
            $temp['menu_image'] = $this->ImageBasepath . $menu_image;
            $temp['backgroundImage'] = $this->ImageBasepath . $backgroundImage;
            $temp['ingredients'] = $description;
            $temp['menu_status'] = $menu_status;
            $temp['created'] = $created;
            $temp['modified'] = $modified;
            $temp['rating'] = $rating;


            array_push($itemRecords["results"], $temp);
        }


        return $itemRecords;
    }


    // search api end point
    function topMenuItems()
    {

        $itemRecords = array();

        $this->menu_id = htmlspecialchars(strip_tags($_GET["page"]));
        $this->input_menu_type_id = 3;


        if ($this->menu_id) {
            $this->pageno = floatval($this->menu_id);
            $no_of_records_per_page = 100;
            $offset = ($this->pageno - 1) * $no_of_records_per_page;

            $sql = "SELECT COUNT(*) as count FROM " . $this->itemsTable . "  limit 1";
            $result = mysqli_query($this->conns, $sql);
            $data = mysqli_fetch_assoc($result);
            $total_rows = floatval($data['count']);
            $total_pages = ceil($total_rows / $no_of_records_per_page);


            $itemRecords["page"] = $this->pageno;
            $itemRecords["results"] = array();
            $itemRecords["total_pages"] = $total_pages;
            $itemRecords["total_results"] = $total_rows;


            $stmt = $this->conns->prepare("SELECT menu_id,menu_name, price, description, menu_type_id, menu_image,backgroundImage,ingredients, menu_status, created, modified,rating FROM " . $this->itemsTable . " ORDER BY menu_id LIMIT " . $offset . "," . $no_of_records_per_page);
        } else {
            $stmt = $this->conns->prepare("SELECT menu_id,menu_name, price, description, menu_type_id, menu_image,backgroundImage, ingredients, menu_status, created, modified,rating FROM " . $this->itemsTable);
        }


        $stmt->execute();
        $stmt->bind_result($this->menu_id, $this->menu_name, $this->price, $this->description, $this->menu_type_id, $this->menu_image, $this->backgroundImage, $this->ingredients, $this->menu_status, $this->created, $this->modified, $this->rating);


        while ($stmt->fetch()) {

            $temp = array();

            $temp['menu_id'] = $this->menu_id;
            $temp['menu_name'] = $this->menu_name;
            $temp['price'] = $this->price;
            $temp['description'] = $this->description;
            $temp['menu_type_id'] = $this->menu_type_id;
            $temp['menu_image'] = $this->ImageBasepath . $this->menu_image;
            $temp['backgroundImage'] = $this->ImageBasepath . $this->backgroundImage;
            $temp['ingredients'] = $this->description;
            $temp['menu_status'] = $this->menu_type_id;
            $temp['created'] = $this->created;
            $temp['modified'] = $this->modified;
            $temp['rating'] = $this->rating;


            array_push($itemRecords["results"], $temp);
        }


        return $itemRecords;
    }

}
