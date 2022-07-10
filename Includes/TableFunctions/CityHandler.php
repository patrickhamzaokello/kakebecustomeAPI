<?php

class CityHandler
{

    private $cities_table = "cities";
    private $conn;



    public function __construct($con)
    {
        $this->conn = $con;
        $this->exe_status = "failure";
    }


    function readcities()
    {

        $itemRecords = array();
        $cities_array = array();


        $stmt = "SELECT `id` FROM " . $this->cities_table . " ORDER BY  `cities`.`name` ASC";
        $city_id_result = mysqli_query($this->conn, $stmt);

        while ($row = mysqli_fetch_array($city_id_result)) {
            array_push($cities_array, $row['id']);
        }

        foreach ($cities_array as $id){
            $city = new Cities($this->conn,$id);
            $cityname = $city->getName();
            array_push($itemRecords, $cityname);
        }

        return $itemRecords;

    }


}
