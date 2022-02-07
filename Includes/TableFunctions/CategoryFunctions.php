<?php
class CategoryFunctions
{

	private $Table = "categories";
	// public $id, $parent_id, $level, $name, $order_level, $commision_rate, $banner, $icon, $featured, $top, $digital, $slug, $meta_title, $meta_description, $created_at, $updated_at;
	public $page;
	private $conn;



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
			$temp['sectioned_menuItems'] = $category->getCategoryProducts();
			array_push($menuCategory, $temp);
		}


		$itemRecords["page"] = $this->pageno;
		$itemRecords["sectioned_category_results"] = $menuCategory;
		$itemRecords["total_pages"] = $total_pages;
		$itemRecords["total_results"] = $total_rows;

		return $itemRecords;
	}
}
