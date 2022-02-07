<?php
class Banner
{

    private $itemsTable = "tblbanner";
    public $id;
    public $name;
    public $imageUrl;
    public $status;
    public $display_order;
    public $datecreated;
    public $datemodified;
    private $conns;



    public function __construct($con)
    {
        $this->conns = $con;
    }


    function read()
    {

        $bannerResults = array();

        $stmt = $this->conns->prepare("SELECT id, name, imageUrl, status, display_order, datecreated, datemodified FROM tblbanner WHERE STATUS = 1 ORDER BY display_order LIMIT 4");
        $stmt->execute();
        $stmt->bind_result($id, $name, $imageUrl, $status, $display_order, $datecreated, $datemodified);

        $bannerResults["page"] = 1;
        $bannerResults["banners"] = array();
        $bannerResults["total_pages"] = 2;
        $bannerResults["total_results"] = 4;

        while ($stmt->fetch()) {

            $temp = array();

            $temp['id'] = $id;
            $temp['name'] = $name;
            $temp['imageUrl'] = $imageUrl;
            $temp['status'] = $status;
            $temp['display_order'] = $display_order;
            $temp['datecreated'] = $datecreated;
            $temp['datemodified'] = $datemodified;


            array_push($bannerResults["banners"], $temp);
        }

        return $bannerResults;
    }
}
