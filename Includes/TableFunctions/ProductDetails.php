<?php

class ProductDetails
{

    private $ImageBasepath = "https://zodongofoods.com/admin/pages/";
    public $categoryID;
    public $pageno;
    public $productId;
    private $conn;


    public function __construct($con)
    {
        $this->conn = $con;
    }


    //get selected category and products in it
    function parentCategoryProducts()
    {

        $itemRecords = array();

        $page = htmlspecialchars(strip_tags($_GET["page"]));
        $this->categoryID = htmlspecialchars(strip_tags($_GET["category"]));


        if ($this->categoryID) {
            $this->pageno = floatval($page);
            $no_of_records_per_page = 10;
            $offset = ($this->pageno - 1) * $no_of_records_per_page;

            //get category ids with the same parent id
            $sibling_cat = array();

            $test_sql = "SELECT IFNULL( (SELECT id FROM `categories` WHERE parent_id = " . $this->categoryID . " LIMIT 1) ,0)as id";
            $test_sql_result = mysqli_query($this->conn, $test_sql);
            $test_row = mysqli_fetch_array($test_sql_result);

            if($test_row['id'] == 0){
                array_push($sibling_cat, $this->categoryID);
            } else {

                $sibling_cat_sql = "SELECT id FROM `categories` WHERE parent_id = " . $this->categoryID . " ";
                $sibling_cat_sql_result = mysqli_query($this->conn, $sibling_cat_sql);

                while ($row = mysqli_fetch_array($sibling_cat_sql_result)) {
                    array_push($sibling_cat, $row['id']);
                }

            }

            $prod_string = "SELECT `id` From products WHERE published = 1 AND (";
            foreach ($sibling_cat as $cat_id) {
                $prod_string .= "category_id = " . $cat_id . " OR ";
            }

            //remove the last OR
            $prod_string = substr($prod_string, 0, strlen($prod_string) - 4);
            // add the last bracket
            $prod_string .=")"; 

            // run the query in the db and search through each of the category returned
            $prod_id = mysqli_query($this->conn, $prod_string);
            $result_count = mysqli_num_rows($prod_id);


            $total_rows = floatval(number_format($result_count));
            $total_pages = ceil($total_rows / $no_of_records_per_page);


            $itemRecords["page"] = $this->pageno;
            $itemRecords["selected_category"] = array();
            $itemRecords["total_pages"] = $total_pages;
            $itemRecords["total_results"] = $total_rows;


            // get products id from the same cat
            $same_cat_IDs = array();
            $same_cat_stm = $prod_string . " ORDER BY `products`.`created_at` DESC LIMIT " . $offset . "," . $no_of_records_per_page;
            $same_cat_id_result = mysqli_query($this->conn, $same_cat_stm);
            while ($row = mysqli_fetch_array($same_cat_id_result)) {
                array_push($same_cat_IDs, $row['id']);
            }

            // echo $same_cat_stm;

            if ($this->pageno == 1) {

                $menu_type_data = new Category($this->conn, $this->categoryID);

                $cat_banner = $menu_type_data->getBanner();
                $cat_created = $menu_type_data->getCreated_at();
                $cat_name = $menu_type_data->getName();
                $cat_metadescription = $menu_type_data->getMeta_description();

                $sel_category = array();


                if ($menu_type_data) {
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
                $product = new Product($this->conn, $row);
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

    //get selected Product details and similar product
    function readSelectedProduct()
    {

        $itemRecords = array();

        $this->menu_id = htmlspecialchars(strip_tags($_GET["menuId"]));
        $this->pageno = htmlspecialchars(strip_tags($_GET["page"]));
        $this->categoryID = htmlspecialchars(strip_tags($_GET["category"]));

        if ($this->menu_id) {
            $this->pageno = floatval($this->pageno);
            $no_of_records_per_page = 6;
            $offset = ($this->pageno - 1) * $no_of_records_per_page;

            $sql = "SELECT COUNT(*) as count FROM " . $this->itemsTable . " WHERE menu_type_id = " . $this->categoryID . " limit 1";
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


            $stmt = $this->conns->prepare("SELECT menu_id,menu_name, price, description, menu_type_id, menu_image,backgroundImage,ingredients, menu_status, created, modified,rating FROM " . $this->itemsTable . " WHERE menu_id != " . $this->menu_id . " AND menu_type_id = " . $this->categoryID . " ORDER BY menu_id LIMIT " . $offset . "," . $no_of_records_per_page);
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


   // get similar  selected product category based on product category ID
    function readsimilarProducts(){


        $itemRecords = array();

        $page = htmlspecialchars(strip_tags($_GET["page"]));
        $this->categoryID = htmlspecialchars(strip_tags($_GET["category"]));


        if ($this->categoryID) {
            $this->pageno = floatval($page);
            $no_of_records_per_page = 10;
            $offset = ($this->pageno - 1) * $no_of_records_per_page;

            $sql = "SELECT COUNT(*) as count FROM products WHERE category_id = " . $this->categoryID . " limit 1";
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
            $same_cat_stm = "SELECT `id` From products WHERE category_id = " . $this->categoryID . " ORDER BY num_of_sale DESC LIMIT " . $offset . "," . $no_of_records_per_page;
            $same_cat_id_result = mysqli_query($this->conn, $same_cat_stm);
            while ($row = mysqli_fetch_array($same_cat_id_result)) {
                array_push($same_cat_IDs, $row['id']);
            }


            if ($this->pageno == 1) {

                $menu_type_data = new Category($this->conn, $this->categoryID);

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
}
