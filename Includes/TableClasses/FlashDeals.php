<?php

class FlashDeals
{

    private $Table = "flash_deals";
    private  $id, $title, $start_date, $end_date, $status, $featured, $background_color, $text_color, $banner, $slug, $created_at, $updated_at;
    private $conn;
//    private $imagePathRoot  = "https://d2t03bblpoql2z.cloudfront.net/";
    private $imagePathRoot  = "https://kakebeshop.com/public/";

    public function __construct($id, $conn)
    {
        $this->id = $id;
        $this->conn = $conn;


        $stmt = $this->conn->prepare("SELECT  `id`, `title`, `start_date`, `end_date`, `status`, `featured`, `background_color`, `text_color`, `banner`, `slug`, `created_at`, `updated_at` FROM " . $this->Table . " WHERE id = ?");
        $stmt->bind_param("i", $this->id);

        $stmt->execute();
        $stmt->bind_result($this->id, $this->title, $this->start_date, $this->end_date, $this->status, $this->featured, $this->background_color, $this->text_color, $this->banner, $this->slug, $this->created_at, $this->updated_at);

        while ($stmt->fetch()) {
            $this->id = $this->id;
            $this->title = $this->title;
            $this->start_date = $this->start_date;
            $this->end_date = $this->end_date;
            $this->status = $this->status;
            $this->featured = $this->featured;
            $this->background_color = $this->background_color;
            $this->text_color = $this->text_color;
            $this->banner = $this->banner;
            $this->slug = $this->slug;
            $this->created_at = $this->created_at;
            $this->updated_at = $this->updated_at;
        }


    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
//        date("Y-m-d H:i:s", 1655983782);
        return date("Y-m-d H:i:s", $this->start_date);
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
//        date("Y-m-d H:i:s", 1655983782);
        return date("Y-m-d H:i:s", $this->end_date);
    }

    public function getTimeRemaining(){

        $start = date("Y-m-d H:i:s", $this->start_date);
        $end = date("Y-m-d H:i:s", $this->end_date);

        $current_time_date = date('Y-m-d H:i:s', time());


        $datetime1 = new DateTime($start);//start time
        $datetime2 = new DateTime($end);//end time
        $servertime = new DateTime($current_time_date);


        $interval =   $servertime->diff($datetime2);

        $doPlural = function($nb,$str){return $nb>1?$str.'s':$str;}; // adds plurals

        $format = array();
        if($interval->y !== 0) {
            $format[] = "%y ".$doPlural($interval->y, "year");
        }
        if($interval->m !== 0) {
            $format[] = "%m ".$doPlural($interval->m, "month");
        }
        if($interval->d !== 0) {
            $format[] = "%d ".$doPlural($interval->d, "day");
        }
        if($interval->h !== 0) {
            $format[] = "%h ".$doPlural($interval->h, "hour");
        }
        if($interval->i !== 0) {
            $format[] = "%i ".$doPlural($interval->i, "minute");
        }
        if($interval->s !== 0) {
            if(!count($format)) {
                return "less than a minute ago";
            } else {
                $format[] = "%s ".$doPlural($interval->s, "second");
            }
        }

        // We use the two biggest parts
        if(count($format) > 1) {
            $format = array_shift($format)." and ".array_shift($format);
        } else {
            $format = array_pop($format);
        }

        // Prepend 'since ' or whatever you like
        return $interval->format($format);
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * @return mixed
     */
    public function getBackgroundColor()
    {
        return $this->background_color;
    }

    /**
     * @return mixed
     */
    public function getTextColor()
    {
        return $this->text_color;
    }

    /**
     * @return mixed
     */
    public function getBanner()
    {
        $upload = new Upload($this->conn, $this->banner);
        $filename = $this->imagePathRoot . $upload->getFile_name();

        return $filename;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function getProducts(){
        $categoryids = array();
        $categoryMenuItems = array();


        $category_stmt = "SELECT product_id FROM flash_deal_products  WHERE flash_deal_id = ". $this->id. " ORDER BY `flash_deal_products`.`created_at` DESC LIMIT 8";
        $menu_type_id_result = mysqli_query($this->conn, $category_stmt);

        while ($row = mysqli_fetch_array($menu_type_id_result)) {

            array_push($categoryids, $row);
        }


        foreach ($categoryids as $row) {
            $product = new Product($this->conn, intval($row['product_id']));
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


}
