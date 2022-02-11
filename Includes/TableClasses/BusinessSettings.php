<?php

class BusinessSettings {


    private $Table = "business_settings";
    public $id, $type, $value, $lang, $created_at, $updated_at;
    private $conn;



    public function __construct($con, $id)
    {
        $this->conn = $con;
        $this->id = $id;

        $stmt = $this->conn->prepare("SELECT `id`, `type`, `value`,`created_at`, `updated_at` FROM " . $this->Table . " WHERE id = ? ORDER BY id");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->bind_result($this->id, $this->type, $this->value, $this->created_at, $this->updated_at);

        while ($stmt->fetch()) {
            $this->id = $this->id;
            $this->type = $this->type;
            $this->value =  $this->value;
            $this->created_at =  $this->created_at;
            $this->updated_at =  $this->updated_at;
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
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the value of value
     */ 
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the value of lang
     */ 
    public function getLang()
    {
        return $this->lang;
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


    public function getHomeSliders(){
        return $this->value;
    }
}


?>