<?php

class Cities
{

    private $Table = "cities";
    private $id,$country_id, $name, $cost, $created_at, $updated_at;
    private $conn;


    public function __construct($con, $id)
    {
        $this->conn = $con;
        $this->id = $id;

        $stmt = $this->conn->prepare("SELECT  `id`, `state_id`, `name`, `cost`, `created_at`, `updated_at` FROM " . $this->Table . " WHERE id = ? ");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->bind_result($this->id, $this->country_id, $this->name, $this->cost, $this->created_at, $this->updated_at);

        while ($stmt->fetch()) {
            $this->id = $this->id;
            $this->country_id = $this->country_id;
            $this->name = $this->name;
            $this->cost = $this->cost;
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
    public function getCountryId()
    {
        return $this->country_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
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

?>