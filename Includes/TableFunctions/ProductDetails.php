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

        $this->productId = htmlspecialchars(strip_tags($_GET["productID"]));
        $this->pageno = htmlspecialchars(strip_tags($_GET["page"]));
        $this->categoryID = htmlspecialchars(strip_tags($_GET["category"]));

        if ($this->productId) {
            $this->pageno = floatval($this->pageno);
            $no_of_records_per_page = 6;
            $offset = ($this->pageno - 1) * $no_of_records_per_page;

            $sql = "SELECT COUNT(*) as count FROM products WHERE published = 1 AND id != " . $this->productId . " AND category_id = " . $this->categoryID . " limit 1";
            $result = mysqli_query($this->conn, $sql);
            $data = mysqli_fetch_assoc($result);
            $total_rows = floatval($data['count']);
            $total_pages = ceil($total_rows / $no_of_records_per_page);

            $itemRecords["page"] = $this->pageno;
            $itemRecords["selectedProduct"] = array();
            $itemRecords["total_pages"] = $total_pages;
            $itemRecords["total_results"] = $total_rows;


            if ($this->pageno == 1) {

                $product = new Product($this->conn, $this->productId);

                if($product){
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


                    array_push($itemRecords["selectedProduct"], $temp);

                }

            }


            // get products id from the same cat
            $same_cat_IDs = array();
            $same_cat_stm = "SELECT id FROM products  WHERE published = 1 AND id != " . $this->productId . " AND  category_id = " . $this->categoryID . " LIMIT " . $offset . "," . $no_of_records_per_page;
            $same_cat_id_result = mysqli_query($this->conn, $same_cat_stm);
            while ($row = mysqli_fetch_array($same_cat_id_result)) {
                array_push($same_cat_IDs, $row['id']);
            }

            $allProducts = array();

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

                array_push($allProducts, $temp);
            }

            $slider_temps = array();
            $slider_temps['similarProducts'] = $allProducts;
            array_push($itemRecords['selectedProduct'], $slider_temps);



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
