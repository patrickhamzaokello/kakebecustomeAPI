<?php

class FlashDealsProducts
{

    private $Table = "flash_deal_products";
    private  $id, $flash_deal_id, $product_id, $discount, $discount_type, $created_at, $updated_at;
    private $conn;


    public function __construct($id, $conn)
    {
        $this->id = $id;
        $this->conn = $conn;

        $stmt = $this->conn->prepare("SELECT  `id`, `flash_deal_id`, `product_id`, `discount`, `discount_type`, `created_at`, `updated_at`  FROM " . $this->Table . " WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->bind_result($this->id, $this->flash_deal_id, $this->product_id, $this->discount, $this->discount_type, $this->created_at, $this->updated_at);

        while ($stmt->fetch()) {
            $this->id = $this->id;
            $this->flash_deal_id = $this->flash_deal_id;
            $this->product_id = $this->product_id;
            $this->discount = $this->discount;
            $this->discount_type = $this->discount_type;
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
    public function getFlashDealId()
    {
        return $this->flash_deal_id;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @return mixed
     */
    public function getDiscountType()
    {
        return $this->discount_type;
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


}
