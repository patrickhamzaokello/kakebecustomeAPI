<?php 

Class Addresses {

    private $table = "addresses";
    private $id, $user_id, $address, $country, $city, $longitude, $latitude, $postal_code, $phone, $set_default, $created_at, $updated_at;
    private $conn;


    public function __construct($con, $id)
    {
        $this->conn = $con;
        $this->id = $id;

        $stmt = $this->conn->prepare("SELECT `id`, `user_id`, `address`, `country`, `city`, `longitude`, `latitude`, `postal_code`, `phone`, `set_default`, `created_at`, `updated_at` FROM " . $this->Table . " WHERE id = ? ");
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


    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of user_id
     */ 
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * Get the value of address
     */ 
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get the value of country
     */ 
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Get the value of city
     */ 
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get the value of longitude
     */ 
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Get the value of latitude
     */ 
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Get the value of postal_code
     */ 
    public function getPostal_code()
    {
        return $this->postal_code;
    }

    /**
     * Get the value of phone
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get the value of set_default
     */ 
    public function getSet_default()
    {
        return $this->set_default;
    }

    /**
     * Get the value of created_at
     */ 
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * Get the value of updated_at
     */ 
    public function getUpdated_at()
    {
        return $this->updated_at;
    }
}

?>