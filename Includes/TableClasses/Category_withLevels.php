<?php

class Category
{

    private $Table = "categories";
    public $id, $parent_id, $level, $name, $order_level, $commision_rate, $banner, $icon, $featured, $top, $digital, $slug, $meta_title, $meta_description, $created_at, $updated_at;
    private $conn;

    private $imagePathRoot = "https://d2t03bblpoql2z.cloudfront.net/";


    public function __construct($con, $id)
    {
        $this->conn = $con;
        $this->id = $id;

        $stmt = $this->conn->prepare("SELECT `id`, `parent_id`, `level`, `name`, `order_level`, `commision_rate`, `banner`, `icon`, `featured`, `top`, `digital`, `slug`, `meta_title`, `meta_description`, `created_at`,  `updated_at` FROM " . $this->Table . " WHERE id = ? ORDER BY featured DESC");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->bind_result($this->id, $this->parent_id, $this->level, $this->name, $this->order_level, $this->commission_rate, $this->banner, $this->icon, $this->featured, $this->top, $this->digital, $this->slug, $this->meta_title, $this->meta_description, $this->created_at, $this->updated_at);

        while ($stmt->fetch()) {
            $this->id = $this->id;
            $this->parent_id = $this->parent_id;
            $this->level = $this->level;
            $this->name = $this->name;
            $this->order_level = $this->order_level;
            $this->commision_rate = $this->commision_rate;
            $this->banner = $this->banner;
            $this->icon = $this->icon;
            $this->featured = $this->featured;
            $this->top = $this->top;
            $this->digital = $this->digital;
            $this->slug = $this->slug;
            $this->meta_title = $this->meta_title;
            $this->meta_description = $this->meta_description;
            $this->created_at = $this->created_at;
            $this->updated_at = $this->updated_at;
        }
    }


    function getCategoryProducts()
    {
        $categoryids = array();
        $categoryMenuItems = array();

        //get category ids with the same parent id
        $sibling_cat = array();

        $test_sql = "SELECT IFNULL( (SELECT id FROM `categories` WHERE parent_id = " . $this->id . " LIMIT 1) ,0)as id";
        $test_sql_result = mysqli_query($this->conn, $test_sql);
        $test_row = mysqli_fetch_array($test_sql_result);

        if($test_row['id'] == 0){
            array_push($sibling_cat, $this->id);
        } else {
            // level 1 shop_category [product] selection
            $sibling_cat_sql = "SELECT id FROM `categories` WHERE parent_id = " . $this->id . " ";
            $sibling_cat_sql_result = mysqli_query($this->conn, $sibling_cat_sql);

            while ($row = mysqli_fetch_array($sibling_cat_sql_result)) {
                array_push($sibling_cat, $row['id']);
            }

            // like a Betty :)

            // level 2 shop_category [product] selection
            $level2_cat_string = "SELECT `id` From categories WHERE ";
            foreach ($sibling_cat as $lel_cat_id) {
                $level2_cat_string .= "parent_id = " . $lel_cat_id . " OR ";
            }

            //remove the last OR
            $level2_cat_string = substr($level2_cat_string, 0, strlen($level2_cat_string) - 4);
            // add the last bracket

            $prod_cat_id = mysqli_query($this->conn, $level2_cat_string);
            while ($row = mysqli_fetch_array($prod_cat_id)) {
                array_push($sibling_cat, $row['id']);
            }
            //end
        }

        // SELECT `id` From products WHERE published = 1 AND (category_id = 100 OR category_id = 101 OR category_id = 102)

        $prod_string = "SELECT `id` From products WHERE published = 1 AND (";
        foreach ($sibling_cat as $cat_id) {
            $prod_string .= "category_id = " . $cat_id . " OR ";
        }

        //remove the last OR
        $prod_string = substr($prod_string, 0, strlen($prod_string) - 4);
        // add the last bracket
        $prod_string .=")";

//            echo $prod_string;

        // get products id from the same cat
        $same_cat_IDs = array();
        $same_cat_stm = $prod_string . " ORDER BY num_of_sale LIMIT 6";
        $same_cat_id_result = mysqli_query($this->conn, $same_cat_stm);
        while ($row = mysqli_fetch_array($same_cat_id_result)) {
            array_push($same_cat_IDs, $row['id']);
        }
//         echo $same_cat_stm;


        foreach ($same_cat_IDs as $product_id) {
            $product = new Product($this->conn, intval($product_id));
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

            array_push($categoryMenuItems, $temp);
        }


        return $categoryMenuItems;
    }


    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Get the value of parent_id
     */
    public function getParent_id()
    {
        return $this->parent_id;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of order_level
     */
    public function getOrder_level()
    {
        return $this->order_level;
    }

    /**
     * Get the value of commission_rate
     */
    public function getCommission_rate()
    {
        return $this->commission_rate;
    }

    /**
     * Get the value of banner
     */
    public function getBanner()
    {
        $upload = new Upload($this->conn, $this->banner);
        $filename = $this->imagePathRoot . $upload->getFile_name();

        return $filename;
    }

    /**
     * Get the value of icon
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Get the value of featured
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * Get the value of top
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * Get the value of digital
     */
    public function getDigital()
    {
        return $this->digital;
    }

    /**
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get the value of meta_title
     */
    public function getMeta_title()
    {
        return $this->meta_title;
    }

    /**
     * Get the value of meta_description
     */
    public function getMeta_description()
    {
        return $this->meta_description;
    }

    /**
     * Get the value of created_at
     */
    public function getCreated_at()
    {
        $phpdate = strtotime($this->created_at);
        $mysqldate = date('d M Y', $phpdate);
        return $mysqldate;
    }

    /**
     * Get the value of updated_at
     */
    public function getUpdated_at()
    {

        $phpdate = strtotime($this->updated_at);
        $mysqldate = date('d M Y', $phpdate);
        return $mysqldate;
    }

}
