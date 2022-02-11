<?php
class CategoryFunctions
{

	public $page;
	private $conn;
	private $imagePathRoot  = "https://d2t03bblpoql2z.cloudfront.net/";



	public function __construct($con, $page)
	{
		$this->conn = $con;
		$this->page = $page;
	}


	function sectionMenuCategory()
	{

		$this->pageno = floatval($this->page);
		$no_of_records_per_page = 10;
		$offset = ($this->pageno - 1) * $no_of_records_per_page;

		$sql = "SELECT COUNT(DISTINCT(category_id)) as count FROM products WHERE published = 1 ORDER BY `products`.`featured` DESC limit 1";
		$result = mysqli_query($this->conn, $sql);
		$data = mysqli_fetch_assoc($result);
		$total_rows = floatval($data['count']);
		$total_pages = ceil($total_rows / $no_of_records_per_page);



		$categoryids = array();
		$menuCategory = array();
		$itemRecords = array();


		$category_stmt = "SELECT DISTINCT(category_id) FROM products  WHERE published = 1 ORDER BY `products`.`featured` DESC LIMIT " . $offset . "," . $no_of_records_per_page . "";
		$menu_type_id_result = mysqli_query($this->conn, $category_stmt);

		while ($row = mysqli_fetch_array($menu_type_id_result)) {

			array_push($categoryids, $row);
		}

		foreach ($categoryids as $row) {
			$category = new Category($this->conn, intval($row['category_id']));
			$temp = array();
			$temp['id'] = $category->getId();
			$temp['parent_id'] = $category->getParent_id();
			$temp['level'] = $category->getLevel();
			$temp['name'] =  $category->getName();
			$temp['order_level'] =  $category->getOrder_level();
			$temp['commision_rate'] = $category->getCommission_rate();
			$temp['banner'] = $category->getBanner();
			$temp['icon'] = $category->getIcon();
			$temp['featured'] = $category->getFeatured();
			$temp['top'] = $category->getTop();
			$temp['digital'] = $category->getDigital();
			$temp['slug'] =  $category->getSlug();
			$temp['meta_title'] = $category->getMeta_title();
			$temp['meta_description'] = $category->getMeta_description();
			$temp['created_at'] = $category->getCreated_at();
			$temp['updated_at'] = $category->getUpdated_at();
			$temp['products'] = $category->getCategoryProducts();
			array_push($menuCategory, $temp);
		}


		$itemRecords["page"] = $this->pageno;
		$itemRecords["sectioned_category_results"] = $menuCategory;
		$itemRecords["total_pages"] = $total_pages;
		$itemRecords["total_results"] = $total_rows;

		return $itemRecords;
	}

	function allCombined()
	{

		$this->pageno = floatval($this->page);
		$no_of_records_per_page = 10;
		$offset = ($this->pageno - 1) * $no_of_records_per_page;

		$sql = "SELECT COUNT(DISTINCT(category_id)) as count FROM products WHERE published = 1 ORDER BY `products`.`featured` DESC limit 1";
		$result = mysqli_query($this->conn, $sql);
		$data = mysqli_fetch_assoc($result);
		$total_rows = floatval($data['count']);
		$total_pages = ceil($total_rows / $no_of_records_per_page);



		$categoryids = array();
		$menuCategory = array();
		$itemRecords = array();


		if ($this->pageno == 1) {

			// get banner
			$banners = new BusinessSettings($this->conn, 84);
			$remove_brackets = str_replace(array('[',']'),'',$banners->getHomeSliders());
			$remove_braces = str_replace(array('"','"'),'',$remove_brackets);
			$str_arr = explode(",", $remove_braces);
			$slidermeta_img_path = array();


			foreach($str_arr as $imageID){
				$temp = array();
				$upload = new Upload($this->conn, $imageID);
				$filename = $this->imagePathRoot . $upload->getFile_name();
				$temp['id'] = 1;
				$temp['link'] = 2;
				$temp['filePath'] = $filename;
				array_push($slidermeta_img_path, $temp);
			}


			$slider_temps = array();
			$slider_temps['sliderBanners'] = $slidermeta_img_path;
			array_push($menuCategory, $slider_temps);

			




			// Todays Deal Begin

			$bestsellingProductsID = array();
			$bestSellingProducts = array();
			$category_stmts = "SELECT DISTINCT(id) FROM products   WHERE published = 1 AND `todays_deal` = 1 ORDER BY `products`.`created_at` DESC  LIMIT 8";
			$menu_type_id_results = mysqli_query($this->conn, $category_stmts);

			while ($row = mysqli_fetch_array($menu_type_id_results)) {

				array_push($bestsellingProductsID, $row);
			}

			foreach ($bestsellingProductsID as $row) {
				$product = new Product($this->conn, intval($row['id']));
				$temp = array();
				$temp['id'] = $product->getId();
				$temp['name'] = $product->getName();
				$temp['category_id'] = $product->getCategory_id();
				$temp['photos'] = $product->getPhotos();
				$temp['thumbnail_img'] = $product->getThumbnail_img();
				$temp['unit_price'] = $product->getUnit_price();
				$temp['discount'] = $product->getDiscount();
				$temp['purchase_price'] = $product->getPurchase_price();
				$temp['meta_title'] = $product->getMeta_title();
				$temp['meta_description'] = $product->getMeta_description();
				$temp['meta_img'] = $product->getMeta_img();
				$temp['min_qtn'] = $product->getMin_qty();
				$temp['published'] = $product->getPublished();

				array_push($bestSellingProducts, $temp);
			}


			$best_temps = array();
			$best_temps['id'] = 100;
			$best_temps['parent_id'] = 100;
			$best_temps['level'] = 1;
			$best_temps['name'] =  "Todays Deal";
			$best_temps['order_level'] =  0;
			$best_temps['commision_rate'] = 0;
			$best_temps['banner'] = null;
			$best_temps['icon'] = null;
			$best_temps['featured'] = 0;
			$best_temps['top'] = 0;
			$best_temps['digital'] = 0;
			$best_temps['slug'] =  "Todays Deal";
			$best_temps['meta_title'] = null;
			$best_temps['meta_description'] = null;
			$best_temps['created_at'] = "10 Jul 2021";
			$best_temps['updated_at'] = "10 Jul 2021";
			$best_temps['products'] = $bestSellingProducts;
			array_push($menuCategory, $best_temps);

			// end Todays Deal  Fetch

			// Featured Products Begin

			$bestsellingProductsID = array();
			$bestSellingProducts = array();
			$category_stmts = "SELECT DISTINCT(id) FROM products   WHERE published = 1 AND `featured` = 1 ORDER BY `products`.`created_at` DESC  LIMIT 8";
			$menu_type_id_results = mysqli_query($this->conn, $category_stmts);

			while ($row = mysqli_fetch_array($menu_type_id_results)) {

				array_push($bestsellingProductsID, $row);
			}

			foreach ($bestsellingProductsID as $row) {
				$product = new Product($this->conn, intval($row['id']));
				$temp = array();
				$temp['id'] = $product->getId();
				$temp['name'] = $product->getName();
				$temp['category_id'] = $product->getCategory_id();
				$temp['photos'] = $product->getPhotos();
				$temp['thumbnail_img'] = $product->getThumbnail_img();
				$temp['unit_price'] = $product->getUnit_price();
				$temp['discount'] = $product->getDiscount();
				$temp['purchase_price'] = $product->getPurchase_price();
				$temp['meta_title'] = $product->getMeta_title();
				$temp['meta_description'] = $product->getMeta_description();
				$temp['meta_img'] = $product->getMeta_img();
				$temp['min_qtn'] = $product->getMin_qty();
				$temp['published'] = $product->getPublished();

				array_push($bestSellingProducts, $temp);
			}

			$best_temps = array();
			$best_temps['id'] = 100;
			$best_temps['parent_id'] = 100;
			$best_temps['level'] = 1;
			$best_temps['name'] =  "Featured Products";
			$best_temps['order_level'] =  0;
			$best_temps['commision_rate'] = 0;
			$best_temps['banner'] = null;
			$best_temps['icon'] = null;
			$best_temps['featured'] = 0;
			$best_temps['top'] = 0;
			$best_temps['digital'] = 0;
			$best_temps['slug'] =  "Featured Products";
			$best_temps['meta_title'] = null;
			$best_temps['meta_description'] = null;
			$best_temps['created_at'] = "10 Jul 2021";
			$best_temps['updated_at'] = "10 Jul 2021";
			$best_temps['products'] = $bestSellingProducts;
			array_push($menuCategory, $best_temps);

			// end Featured Products Fetch



			//BEST selling  fetch Begin

			$bestsellingProductsID = array();
			$bestSellingProducts = array();
			$category_stmts = "SELECT DISTINCT(id) FROM products  WHERE published = 1 ORDER BY `products`.`num_of_sale` DESC  LIMIT 8";
			$menu_type_id_results = mysqli_query($this->conn, $category_stmts);

			while ($row = mysqli_fetch_array($menu_type_id_results)) {

				array_push($bestsellingProductsID, $row);
			}

			foreach ($bestsellingProductsID as $row) {
				$product = new Product($this->conn, intval($row['id']));
				$temp = array();
				$temp['id'] = $product->getId();
				$temp['name'] = $product->getName();
				$temp['category_id'] = $product->getCategory_id();
				$temp['photos'] = $product->getPhotos();
				$temp['thumbnail_img'] = $product->getThumbnail_img();
				$temp['unit_price'] = $product->getUnit_price();
				$temp['discount'] = $product->getDiscount();
				$temp['purchase_price'] = $product->getPurchase_price();
				$temp['meta_title'] = $product->getMeta_title();
				$temp['meta_description'] = $product->getMeta_description();
				$temp['meta_img'] = $product->getMeta_img();
				$temp['min_qtn'] = $product->getMin_qty();
				$temp['published'] = $product->getPublished();

				array_push($bestSellingProducts, $temp);
			}

			$best_temps = array();
			$best_temps['id'] = 100;
			$best_temps['parent_id'] = 100;
			$best_temps['level'] = 1;
			$best_temps['name'] =  "Best Selling";
			$best_temps['order_level'] =  0;
			$best_temps['commision_rate'] = 0;
			$best_temps['banner'] = null;
			$best_temps['icon'] = null;
			$best_temps['featured'] = 0;
			$best_temps['top'] = 0;
			$best_temps['digital'] = 0;
			$best_temps['slug'] =  "Best Selling";
			$best_temps['meta_title'] = null;
			$best_temps['meta_description'] = null;
			$best_temps['created_at'] = "10 Jul 2021";
			$best_temps['updated_at'] = "10 Jul 2021";
			$best_temps['products'] = $bestSellingProducts;
			array_push($menuCategory, $best_temps);

			// end Best Selling Fetch
		}



		//fetch other categories Begin

		$category_stmt = "SELECT DISTINCT(category_id) FROM products  WHERE published = 1 ORDER BY `products`.`featured` DESC LIMIT " . $offset . "," . $no_of_records_per_page . "";
		$menu_type_id_result = mysqli_query($this->conn, $category_stmt);

		while ($row = mysqli_fetch_array($menu_type_id_result)) {

			array_push($categoryids, $row);
		}

		foreach ($categoryids as $row) {
			$category = new Category($this->conn, intval($row['category_id']));
			$temp = array();
			$temp['id'] = $category->getId();
			$temp['parent_id'] = $category->getParent_id();
			$temp['level'] = $category->getLevel();
			$temp['name'] =  $category->getName();
			$temp['order_level'] =  $category->getOrder_level();
			$temp['commision_rate'] = $category->getCommission_rate();
			$temp['banner'] = $category->getBanner();
			$temp['icon'] = $category->getIcon();
			$temp['featured'] = $category->getFeatured();
			$temp['top'] = $category->getTop();
			$temp['digital'] = $category->getDigital();
			$temp['slug'] =  $category->getSlug();
			$temp['meta_title'] = $category->getMeta_title();
			$temp['meta_description'] = $category->getMeta_description();
			$temp['created_at'] = $category->getCreated_at();
			$temp['updated_at'] = $category->getUpdated_at();
			$temp['products'] = $category->getCategoryProducts();
			array_push($menuCategory, $temp);
		}


		$itemRecords["page"] = $this->pageno;
		$itemRecords["categories"] = $menuCategory;
		$itemRecords["total_pages"] = $total_pages;
		$itemRecords["total_results"] = $total_rows;

		return $itemRecords;
	}



	function getTodaysDeals()
	{
		// SELECT * FROM `products` WHERE `published` = 1 AND `todays_deal` =1 ORDER BY `products`.`created_at` DESC LIMIT 12
		$this->pageno = floatval($this->page);
		$no_of_records_per_page = 10;
		$offset = ($this->pageno - 1) * $no_of_records_per_page;

		$sql = "SELECT COUNT(DISTINCT(id)) as count FROM products WHERE published = 1 AND `todays_deal` = 1 ORDER BY `products`.`created_at` DESC LIMIT 12";
		$result = mysqli_query($this->conn, $sql);
		$data = mysqli_fetch_assoc($result);
		$total_rows = floatval($data['count']);
		$total_pages = ceil($total_rows / $no_of_records_per_page);



		$categoryids = array();
		$menuCategory = array();
		$itemRecords = array();


		$category_stmt = "SELECT DISTINCT(id) FROM products  WHERE published = 1 AND `todays_deal` = 1 ORDER BY `products`.`created_at` DESC  LIMIT " . $offset . "," . $no_of_records_per_page . "";
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
			$temp['unit_price'] = $product->getUnit_price();
			$temp['discount'] = $product->getDiscount();
			$temp['purchase_price'] = $product->getPurchase_price();
			$temp['meta_title'] = $product->getMeta_title();
			$temp['meta_description'] = $product->getMeta_description();
			$temp['meta_img'] = $product->getMeta_img();
			$temp['min_qtn'] = $product->getMin_qty();
			$temp['published'] = $product->getPublished();

			array_push($menuCategory, $temp);
		}


		$itemRecords["page"] = $this->pageno;
		$itemRecords["sectioned_category_results"] = $menuCategory ? $menuCategory : null;
		$itemRecords["total_pages"] = $total_pages;
		$itemRecords["total_results"] = $total_rows;

		return $itemRecords;
	}


	function getFeaturedProducts()
	{
		// SELECT * FROM `products` WHERE `published` = 1 AND `featured` =1 ORDER BY `products`.`created_at` DESC LIMIT 12
		$this->pageno = floatval($this->page);
		$no_of_records_per_page = 10;
		$offset = ($this->pageno - 1) * $no_of_records_per_page;

		$sql = "SELECT COUNT(DISTINCT(id)) as count FROM products WHERE published = 1 AND `featured` = 1 ORDER BY `products`.`created_at` DESC LIMIT 12";
		$result = mysqli_query($this->conn, $sql);
		$data = mysqli_fetch_assoc($result);
		$total_rows = floatval($data['count']);
		$total_pages = ceil($total_rows / $no_of_records_per_page);



		$categoryids = array();
		$menuCategory = array();
		$itemRecords = array();


		$category_stmt = "SELECT DISTINCT(id) FROM products  WHERE published = 1 AND `featured` = 1 ORDER BY `products`.`created_at` DESC  LIMIT " . $offset . "," . $no_of_records_per_page . "";
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
			$temp['unit_price'] = $product->getUnit_price();
			$temp['discount'] = $product->getDiscount();
			$temp['purchase_price'] = $product->getPurchase_price();
			$temp['meta_title'] = $product->getMeta_title();
			$temp['meta_description'] = $product->getMeta_description();
			$temp['meta_img'] = $product->getMeta_img();
			$temp['min_qtn'] = $product->getMin_qty();
			$temp['published'] = $product->getPublished();

			array_push($menuCategory, $temp);
		}


		$itemRecords["page"] = $this->pageno;
		$itemRecords["sectioned_category_results"] = $menuCategory ? $menuCategory : null;
		$itemRecords["total_pages"] = $total_pages;
		$itemRecords["total_results"] = $total_rows;

		return $itemRecords;
	}

	function getBestSelling()
	{
		// SELECT * FROM `products` WHERE `published` = 1 ORDER BY `products`.`num_of_sale` DESC LIMIT 12
		$this->pageno = floatval($this->page);
		$no_of_records_per_page = 10;
		$offset = ($this->pageno - 1) * $no_of_records_per_page;

		$sql = "SELECT COUNT(DISTINCT(id)) as count FROM products WHERE published = 1 ORDER BY `products`.`num_of_sale` DESC LIMIT 12";
		$result = mysqli_query($this->conn, $sql);
		$data = mysqli_fetch_assoc($result);
		$total_rows = floatval($data['count']);
		$total_pages = ceil($total_rows / $no_of_records_per_page);



		$categoryids = array();
		$menuCategory = array();
		$itemRecords = array();


		$category_stmt = "SELECT DISTINCT(id) FROM products  WHERE published = 1 ORDER BY `products`.`num_of_sale` DESC  LIMIT " . $offset . "," . $no_of_records_per_page . "";
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
			$temp['unit_price'] = $product->getUnit_price();
			$temp['discount'] = $product->getDiscount();
			$temp['purchase_price'] = $product->getPurchase_price();
			$temp['meta_title'] = $product->getMeta_title();
			$temp['meta_description'] = $product->getMeta_description();
			$temp['meta_img'] = $product->getMeta_img();
			$temp['min_qtn'] = $product->getMin_qty();
			$temp['published'] = $product->getPublished();

			array_push($menuCategory, $temp);
		}


		$itemRecords["page"] = $this->pageno;
		$itemRecords["sectioned_category_results"] = $menuCategory ? $menuCategory : null;
		$itemRecords["total_pages"] = $total_pages;
		$itemRecords["total_results"] = $total_rows;

		return $itemRecords;
	}
}
