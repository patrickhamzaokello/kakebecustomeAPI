<?php
class Category
{

    private $Table = "categories";
    public $id, $parent_id, $level, $name, $order_level, $commission_rate, $banner, $icon, $featured, $top, $digital, $slug, $meta_title, $meta_description, $created_at, $updated_at;
    private $conn;



    public function __construct($con, $id)
    {
        $this->conn = $con;
        $this->id = $id;

        $stmt = $this->conn->prepare("SELECT `id`, `parent_id`, `level`, `name`, `order_level`, `commision_rate`, `banner`, `icon`, `featured`, `top`, `digital`, `slug`, `meta_title`, `meta_description`, `created_at`,  `updated_at` FROM " . $this->Table . " WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->bind_result($this->id, $this->parent_id, $this->level, $this->name, $this->order_level, $this->commission_rate, $this->banner, $this->icon, $this->featured, $this->top, $this->digital, $this->slug, $this->meta_title, $this->meta_description, $this->created_at, $this->updated_at);

        while ($stmt->fetch()) {
            $this->id = $this->id;
            $this->parent_id = $this->parent_id;
            $this->level =  $this->level;
            $this->name =  $this->name;
            $this->order_level =  $this->order_level;
            $this->commision_rate = $this->commision_rate;
            $this->banner = $this->banner;
            $this->icon = $this->icon;
            $this->featured = $this->featured;
            $this->top = $this->top;
            $this->digital = $this->digital;
            $this->slug =  $this->slug;
            $this->meta_title = $this->meta_title;
            $this->meta_description = $this->meta_description;
            $this->created_at =  $this->created_at;
            $this->updated_at = $this->updated_at;
        }
    }




    function getCategoryProducts()
    {

        $categoryProductsID = array();
        $pro_id = null;
        $pro_name = null;
        $pro_category_id = null;

        $stmt = $this->conn->prepare("SELECT `id`, `name`, `category_id` FROM products WHERE category_id = ? ORDER BY num_of_sale LIMIT 6");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->bind_result($pro_id, $pro_name, $pro_category_id);

        while ($stmt->fetch()) {
            array_push($categoryProducts, $pro_id);
        }

        return $categoryProductsID;
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
        return $this->banner;
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
