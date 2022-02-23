<?php

class Prada
{

    private $ImageBasepath = "https://zodongofoods.com/admin/pages/";
    public $input_menu_type_id;
    public $pageno;
    private $conn;


    public function __construct($con)
    {
        $this->conn = $con;
    }


    //get selected category and products in it
    function readPage()
    {

        $itemRecords = array();

        $page = htmlspecialchars(strip_tags($_GET["page"]));
        $this->input_menu_type_id = htmlspecialchars(strip_tags($_GET["category"]));


        if ($this->input_menu_type_id) {
            $this->pageno = floatval($page);
            $no_of_records_per_page = 10;
            $offset = ($this->pageno - 1) * $no_of_records_per_page;

            $sql = "SELECT COUNT(*) as count FROM products WHERE category_id = " . $this->input_menu_type_id . " limit 1";
            $result = mysqli_query($this->conn, $sql);
            $data = mysqli_fetch_assoc($result);
            $total_rows = floatval($data['count']);
            $total_pages = ceil($total_rows / $no_of_records_per_page);


            $itemRecords["page"] = $this->pageno;
            $itemRecords["selected_category"] = array();
            $itemRecords["total_pages"] = $total_pages;
            $itemRecords["total_results"] = $total_rows;


            // get products id from the same cat
            $same_cat_IDs = array();
            $same_cat_stm = "SELECT `id` From products WHERE category_id = " . $this->input_menu_type_id . " ORDER BY num_of_sale DESC LIMIT " . $offset . "," . $no_of_records_per_page;
            $same_cat_id_result = mysqli_query($this->conn, $same_cat_stm);
            while ($row = mysqli_fetch_array($same_cat_id_result)) {
                array_push($same_cat_IDs, $row['id']);
            }


            if ($this->pageno == 1) {

                $menu_type_data = new Category($this->conn, $this->input_menu_type_id);

                $cat_banner = $menu_type_data->getBanner();
                $cat_created = $menu_type_data->getCreated_at();
                $cat_name = $menu_type_data->getName();
                $cat_metadescription = $menu_type_data->getMeta_description();

                $sel_category = array();


                if($menu_type_data){
                    $menu_type_temp = array();
                    $menu_type_temp['name'] = $cat_name;
                    $menu_type_temp['meta_description'] = $cat_metadescription;
                    $menu_type_temp['banner'] = $cat_banner;
                    $menu_type_temp['banner'] = $cat_banner;
                    $menu_type_temp['created'] = $cat_created;

                    array_push($sel_category, $menu_type_temp);
                }

                $category = array();
                $category['category_info'] = $sel_category;


                array_push($itemRecords["selected_category"], $category);
            }



            foreach ($same_cat_IDs as $row) {
                $product = new Product($this->conn,$row);
                $temp = array();
                $temp['id'] = $product->getId();
                $temp['name'] = $product->getName();
                $temp['category_id'] = $product->getCategory_id();
                $temp['photos'] = $product->getPhotos();
                $temp['thumbnail_img'] = $product->getThumbnail_img();
                $temp['unit_price'] = $product->getUnit_price();
                $temp['discount'] = $product->getDiscount();
                $temp['purchase_price'] = $product->getPurchase_price();
                $temp['meta_title'] = $product->getMeta_title();
                $temp['meta_description'] = $product->getMeta_description();
                $temp['meta_img'] = $product->getMeta_img();
                $temp['min_qtn'] = $product->getMin_qty();
                $temp['published'] = $product->getPublished();

                array_push($itemRecords["selected_category"], $temp);
            }



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
