<?php

class Product
{

    private $Table = "products";
    public $id, $name, $added_by,  $user_id, $category_id, $brand_id, $photos, $thumbnail_img, $video_provider, $video_link, $tags, $description, $unit_price, $purchase_price, $variant_product, $attributes, $choice_options, $colors, $variations, $todays_deal;
    public $published, $approved, $stock_visibility_state, $cash_on_delivery, $featured, $seller_featured, $current_stock, $unit, $min_qty, $low_stock_quantity;
    public $discount, $discount_type, $discount_start_date, $discount_end_date, $tax, $tax_type, $shipping_type, $shipping_cost, $is_quantity_multiplied, $est_shipping_days, $num_of_sale, $meta_title, $meta_description, $meta_img, $pdf, $slug, $rating, $barcode, $digital, $auction_product, $file_name, $file_path, $created_at, $updated_at;
    private $conn;
    private $imagePathRoot  = "https://d2t03bblpoql2z.cloudfront.net/";


    public function __construct($con, $id)
    {
        $this->conn = $con;
        $this->id = $id;

        $stmt = $this->conn->prepare("SELECT `id`, `name`, `added_by`, `user_id`, `category_id`, `brand_id`, `photos`, `thumbnail_img`, `video_provider`, `video_link`, `tags`, `description`, `unit_price`, `purchase_price`, `variant_product`, `attributes`, `choice_options`, `colors`, `variations`, `todays_deal`, `published`, `approved`, `stock_visibility_state`, `cash_on_delivery`, `featured`, `seller_featured`, `current_stock`, `unit`, `min_qty`, `low_stock_quantity`, `discount`, `discount_type`, `discount_start_date`, `discount_end_date`, `tax`, `tax_type`, `shipping_type`, `shipping_cost`, `is_quantity_multiplied`, `est_shipping_days`, `num_of_sale`, `meta_title`, `meta_description`, `meta_img`, `pdf`, `slug`, `rating`, `barcode`, `digital`, `auction_product`, `file_name`, `file_path`, `created_at`, `updated_at` FROM " . $this->Table . " WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->bind_result($this->id, $this->name, $this->added_by, $this->user_id, $this->category_id, $this->brand_id, $this->photos, $this->thumbnail_img, $this->video_provider, $this->video_link, $this->tags, $this->description, $this->unit_price, $this->purchase_price, $this->variant_product, $this->attributes, $this->choice_options, $this->colors, $this->variations, $this->todays_deal, $this->published, $this->approved, $this->stock_visibility_state, $this->cash_on_delivery, $this->featured, $this->seller_featured, $this->current_stock, $this->unit, $this->min_qty, $this->low_stock_quantity, $this->discount, $this->discount_type, $this->discount_start_date, $this->discount_end_date, $this->tax, $this->tax_type, $this->shipping_type, $this->shipping_cost, $this->is_quantity_multiplied, $this->est_shipping_days, $this->num_of_sale, $this->meta_title, $this->meta_description, $this->meta_img, $this->pdf, $this->slug, $this->rating, $this->barcode, $this->digital, $this->auction_product, $this->file_name, $this->file_path, $this->created_at, $this->updated_at);

        while ($stmt->fetch()) {

            $this->id = $this->id;
            $this->name = $this->name;
            $this->added_by =  $this->added_by;
            $this->user_id = $this->user_id;
            $this->category_id = $this->category_id;
            $this->brand_id = $this->brand_id;
            $this->photos =  $this->photos;
            $this->thumbnail_img = $this->thumbnail_img;
            $this->video_provider = $this->video_provider;
            $this->video_link = $this->video_link;
            $this->tags =   $this->tags;
            $this->description = $this->description;
            $this->unit_price =  $this->unit_price;
            $this->purchase_price =  $this->purchase_price;
            $this->variant_product = $this->variant_product;
            $this->attributes = $this->attributes;
            $this->choice_options =  $this->choice_options;
            $this->colors = $this->colors;
            $this->variations = $this->variations;
            $this->todays_deal = $this->todays_deal;
            $this->published =  $this->published;
            $this->approved =  $this->approved;
            $this->stock_visibility_state = $this->stock_visibility_state;
            $this->cash_on_delivery = $this->cash_on_delivery;
            $this->featured = $this->featured;
            $this->seller_featured =  $this->seller_featured;
            $this->current_stock = $this->current_stock;
            $this->unit = $this->unit;
            $this->min_qty = $this->min_qty;
            $this->low_stock_quantity = $this->low_stock_quantity;
            $this->discount = $this->discount;
            $this->discount_type = $this->discount_type;
            $this->discount_start_date = $this->discount_start_date;
            $this->discount_end_date =  $this->discount_end_date;
            $this->tax = $this->tax;
            $this->tax_type = $this->tax_type;
            $this->shipping_type = $this->shipping_type;
            $this->shipping_cost = $this->shipping_cost;
            $this->is_quantity_multiplied = $this->is_quantity_multiplied;
            $this->est_shipping_days =  $this->est_shipping_days;
            $this->num_of_sale =  $this->num_of_sale;
            $this->meta_title = $this->meta_title;
            $this->meta_description = $this->meta_description;
            $this->meta_img = $this->meta_img;
            $this->pdf =  $this->pdf;
            $this->slug = $this->slug;
            $this->rating = $this->rating;
            $this->barcode =    $this->barcode;
            $this->digital = $this->digital;
            $this->auction_product = $this->auction_product;
            $this->file_name = $this->file_name;
            $this->file_path = $this->file_path;
            $this->created_at = $this->created_at;
            $this->updated_at =   $this->updated_at;
        }
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of category_id
     */
    public function getCategory_id()
    {
        return $this->category_id;
    }

    /**
     * Get the value of photos
     */
    public function getPhotos()
    {
            // use of explode
            $string =$this->photos;
            $str_arr = explode(",", $string);
            $meta_img_path = array();
    
            foreach($str_arr as $imageID){
                $upload = new Upload($this->conn, $imageID);
                $filename = $this->imagePathRoot . $upload->getFile_name();
                array_push($meta_img_path, $filename);
            }
    
            return implode(",", $meta_img_path);
    }

    /**
     * Get the value of thumbnail_img
     */
    public function getThumbnail_img()
    {

        $upload = new Upload($this->conn, $this->thumbnail_img);
        $filename = $this->imagePathRoot . $upload->getFile_name();

        return $filename;
    }

    /**
     * Get the value of unit_price
     */
    public function getUnit_price()
    {
        return $this->unit_price;
    }

    /**
     * Get the value of purchase_price
     */
    public function getPurchase_price()
    {
        return $this->purchase_price;
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
     * Get the value of meta_img
     */
    public function getMeta_img()
    {
        // use of explode
        $string = $this->meta_img;
        $str_arr = explode(",", $string);
        $meta_img_path = array();

        foreach($str_arr as $imageID){
            $upload = new Upload($this->conn, $imageID);
            $filename = $this->imagePathRoot . $upload->getFile_name();
            array_push($meta_img_path, $filename);
        }

        return implode(",", $meta_img_path);
    }

    /**
     * Get the value of published
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Get the value of min_qty
     */ 
    public function getMin_qty()
    {
        return $this->min_qty;
    }

    /**
     * Get the value of discount
     */ 
    public function getDiscount()
    {
        return $this->discount;
    }
}
