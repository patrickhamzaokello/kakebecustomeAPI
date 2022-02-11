<?php
class SearchFunctions
{

    public $page;
    public $query;
    private $conn;
    private $imagePathRoot  = "https://d2t03bblpoql2z.cloudfront.net/";



    public function __construct($con, $query, $page)
    {
        $this->conn = $con;
        $this->query = $query;
        $this->page = $page;
    }


    function searchMain()
    {

        // create the base variables for building the search query
        $search_string = "SELECT * FROM products WHERE published = 1 AND ";
        $display_words = "";

        // format each of search keywords into the db query to be run
        $keywords = explode(' ', $this->query);
        foreach ($keywords as $word) {
            $search_string .= "name LIKE '%" . $word . "%' OR ";
            $display_words .= $word . ' ';
        }
        $search_string = substr($search_string, 0, strlen($search_string) - 4);
        $display_words = substr($display_words, 0, strlen($display_words) - 1);


        // run the query in the db and search through each of the records returned
        $query = mysqli_query($this->conn, $search_string);
        $result_count = mysqli_num_rows($query);

        $this->pageno = floatval($this->page);
        $no_of_records_per_page = 10;
        $offset = ($this->pageno - 1) * $no_of_records_per_page;


        $total_rows = floatval(number_format($result_count));
        $total_pages = ceil($total_rows / $no_of_records_per_page);


        $itemRecords = array();


        // check if the search query returned any results
        if ($result_count > 0) {

            $categoryids = array();
            $menuCategory = array();


            $category_stmt = $search_string . " LIMIT " . $offset . "," . $no_of_records_per_page . "";


            $menu_type_id_result = mysqli_query($this->conn, $category_stmt);

            while ($row = mysqli_fetch_array($menu_type_id_result)) {

                array_push($categoryids, $row);
            }

            foreach ($categoryids as $row) {
                $product = new Product($this->conn, intval($row['id']));
                $temp = array();
                $temp['id'] = $product->getId();
                $temp['name'] = $product->getName();
                $temp['category_id'] = $product->getCategory_id();
                $temp['photos'] = $product->getPhotos();
                $temp['thumbnail_img'] = $product->getThumbnail_img();
                $temp['unit_price'] = intVal($product->getUnit_price());
                $temp['discount'] = intVal($product->getDiscount());
                $temp['purchase_price'] = intVal($product->getPurchase_price());
                $temp['meta_title'] = $product->getMeta_title();
                $temp['meta_description'] = $product->getMeta_description();
                $temp['meta_img'] = $product->getMeta_img();
                $temp['min_qtn'] = $product->getMin_qty();
                $temp['published'] = $product->getPublished();
                array_push($menuCategory, $temp);
            }

            $itemRecords["page"] = $this->pageno;
            $itemRecords["searchTerm"] = $display_words;
            $itemRecords["products"] = $menuCategory;
            $itemRecords["total_pages"] = $total_pages;
            $itemRecords["total_results"] = $total_rows;


        } else {
            $itemRecords["page"] = $this->pageno;
            $itemRecords["searchTerm"] = $display_words;
            $itemRecords["products"] = null;
            $itemRecords["total_pages"] = $total_pages;
            $itemRecords["total_results"] = $total_rows;
        }
        return $itemRecords;

    }


    function searchFullText()
    {

        // SELECT * FROM products WHERE MATCH (name) AGAINST ('cooking oil')

        // create the base variables for building the search query
        $search_string = "SELECT * FROM products WHERE published = 1 AND ";
        $display_words = "";

        // format each of search keywords into the db query to be run
        $search_string .= "MATCH (name) AGAINST ('".$this->query."')";
        $display_words .= $this->query . ' ';

        // run the query in the db and search through each of the records returned
        $query = mysqli_query($this->conn, $search_string);
        $result_count = mysqli_num_rows($query);

        $this->pageno = floatval($this->page);
        $no_of_records_per_page = 10;
        $offset = ($this->pageno - 1) * $no_of_records_per_page;


        $total_rows = floatval(number_format($result_count));
        $total_pages = ceil($total_rows / $no_of_records_per_page);


        $itemRecords = array();


        // check if the search query returned any results
        if ($result_count > 0) {

            $categoryids = array();
            $menuCategory = array();


            $category_stmt = $search_string . " LIMIT " . $offset . "," . $no_of_records_per_page . "";


            $menu_type_id_result = mysqli_query($this->conn, $category_stmt);

            while ($row = mysqli_fetch_array($menu_type_id_result)) {

                array_push($categoryids, $row);
            }

            foreach ($categoryids as $row) {
                $product = new Product($this->conn, intval($row['id']));
                $temp = array();
                $temp['id'] = $product->getId();
                $temp['name'] = $product->getName();
                $temp['category_id'] = $product->getCategory_id();
                $temp['photos'] = $product->getPhotos();
                $temp['thumbnail_img'] = $product->getThumbnail_img();
                $temp['unit_price'] = intVal($product->getUnit_price());
                $temp['discount'] = intVal($product->getDiscount());
                $temp['purchase_price'] = intVal($product->getPurchase_price());
                $temp['meta_title'] = $product->getMeta_title();
                $temp['meta_description'] = $product->getMeta_description();
                $temp['meta_img'] = $product->getMeta_img();
                $temp['min_qtn'] = $product->getMin_qty();
                $temp['published'] = $product->getPublished();
                array_push($menuCategory, $temp);
            }


            $itemRecords["page"] = $this->pageno;
            $itemRecords["searchTerm"] = $display_words;
            $itemRecords["products"] = $menuCategory;
            $itemRecords["total_pages"] = $total_pages;
            $itemRecords["total_results"] = $total_rows;

        } else {
            $itemRecords["page"] = $this->pageno;
            $itemRecords["searchTerm"] = $display_words;
            $itemRecords["products"] = null;
            $itemRecords["total_pages"] = $total_pages;
            $itemRecords["total_results"] = $total_rows;
        }

        return $itemRecords;
    }
}
