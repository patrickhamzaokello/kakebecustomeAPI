<?php

class Attributes
{

    private $Table = "attributes";
    private $id, $name, $created_at, $updated_at;
    private $conn;

    /**
     * @param $id
     * @param $conn
     */
    public function __construct($id, $conn)
    {
        $this->id = $id;
        $this->conn = $conn;

        $stmt = $this->conn->prepare("SELECT `id`, `name`, `created_at`, `updated_at` FROM " . $this->Table . " WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->bind_result($this->id, $this->name, $this->created_at, $this->updated_at);

        while ($stmt->fetch()) {
            $this->id = $this->id;
            $this->name = $this->name;
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
    public function getName()
    {
        return $this->name;
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
