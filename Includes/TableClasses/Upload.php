<?php

// SELECT `id`, `file_original_name`, `file_name`, `user_id`, `file_size`, `extension`, `type`, `created_at`, `updated_at`, `deleted_at` FROM `uploads` WHERE 1

class Upload
{

    private $conn;
    private $Table = "uploads";
    public $id, $file_original_name, $file_name, $user_id, $file_size, $extension, $type, $created_at, $updated_at, $deleted_at;


    public function __construct($con, $id)
    {
        $this->conn = $con;
        $this->id = $id;


        $stmt = $this->conn->prepare("SELECT `id`, `file_original_name`, `file_name`, `user_id`, `file_size`, `extension`, `type`, `created_at`, `updated_at`, `deleted_at` FROM " . $this->Table . " WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->bind_result($this->id, $this->file_original_name, $this->file_name, $this->user_id, $this->file_size, $this->extension, $this->type, $this->created_at, $this->updated_at, $this->deleted_at);

        while ($stmt->fetch()) {

            $this->id = $this->id;
            $this->file_original_name = $this->file_original_name;
            $this->file_name =  $this->file_name;
            $this->user_id = $this->user_id;
            $this->file_size = $this->file_size;
            $this->extension = $this->extension;
            $this->type =  $this->type;
            $this->created_at = $this->created_at;
            $this->updated_at = $this->updated_at;
            $this->deleted_at = $this->deleted_at;
        }
    }

    /**
     * Get the value of file_name
     */ 
    public function getFile_name()
    {
        return $this->file_name;
    }
}
