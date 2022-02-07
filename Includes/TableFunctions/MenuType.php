<?php
class MenuType
{

	private $itemsTable = "tblmenutype";
	public $id;
	public $name;
	public $description;
	public $imageCover;
	public $created;
	public $modified;
	private $conn;



	public function __construct($con)
	{
		$this->conn = $con;
	}

	

	function read()
	{
		$categoryids = array();
		$itemRecords = array();


		if ($this->id) {
			$stmt = $this->conn->prepare("SELECT id,name,description,imageCover,created, modified FROM " . $this->itemsTable . " WHERE id = ?");
			$stmt->bind_param("i", $this->id);
			$stmt->execute();
			$stmt->bind_result($id, $name, $description, $imageCover, $created, $modified);


			while ($stmt->fetch()) {

				$temp = array();

				$temp['id'] = $id;
				$temp['name'] = $name;
				$temp['description'] = $description;
				$temp['imageCover'] = $imageCover;
				$temp['created'] = $created;
				$temp['modified'] = $modified;

				array_push($itemRecords, $temp);
			}
		} else {


			$category_stmt = "SELECT DISTINCT(menu_type_id) FROM tblmenu ";
			$menu_type_id_result = mysqli_query($this->conn, $category_stmt);

			while ($row = mysqli_fetch_array($menu_type_id_result)) {

				array_push($categoryids, $row);
			}

			foreach ($categoryids as $row) {
				$menu = new MenuTypeClass($this->conn, intval($row['menu_type_id']));

				$temp = array();

				$temp['id'] = $menu->getMenuTypeId();
				$temp['name'] = $menu->getMenuTypeName();
				$temp['description'] = $menu->getMenuTypeDescription();
				$temp['imageCover'] = $menu->getMenuTypeImageCover();
				$temp['created'] = $menu->getMenuTypeCreated();
				$temp['modified'] = $menu->getMenuTypeModified();

				array_push($itemRecords, $temp);
			}
		}

		return $itemRecords;
	}

	function readPaginated()
	{
		$categoryids = array();
		$menuCategory = array();
		$itemRecords = array();


		if ($this->id) {
			$stmt = $this->conn->prepare("SELECT id,name,description,imageCover,created, modified FROM " . $this->itemsTable . " WHERE id = ?");
			$stmt->bind_param("i", $this->id);
			$stmt->execute();
			$stmt->bind_result($id, $name, $description, $imageCover, $created, $modified);


			while ($stmt->fetch()) {

				$temp = array();

				$temp['id'] = $id;
				$temp['name'] = $name;
				$temp['description'] = $description;
				$temp['imageCover'] = $imageCover;
				$temp['created'] = $created;
				$temp['modified'] = $modified;

				array_push($menuCategory, $temp);
			}
		} else {


			$category_stmt = "SELECT DISTINCT(menu_type_id) FROM tblmenu ";
			$menu_type_id_result = mysqli_query($this->conn, $category_stmt);

			while ($row = mysqli_fetch_array($menu_type_id_result)) {

				array_push($categoryids, $row);
			}

			foreach ($categoryids as $row) {
				$menu = new MenuTypeClass($this->conn, intval($row['menu_type_id']));

				$temp = array();

				$temp['id'] = $menu->getMenuTypeId();
				$temp['name'] = $menu->getMenuTypeName();
				$temp['description'] = $menu->getMenuTypeDescription();
				$temp['imageCover'] = $menu->getMenuTypeImageCover();
				$temp['created'] = $menu->getMenuTypeCreated();
				$temp['modified'] = $menu->getMenuTypeModified();

				array_push($menuCategory, $temp);
			}
		}

		$itemRecords["page"] = 1;
		$itemRecords["results"] = $menuCategory;
		$itemRecords["total_pages"] = 2;
		$itemRecords["total_results"] = 4;

		return $itemRecords;
	}


	function sectionMenuCategory()
	{
		$categoryids = array();
		$menuCategory = array();
		$itemRecords = array();


		if ($this->id) {
			$stmt = $this->conn->prepare("SELECT id,name,description,imageCover,created, modified FROM " . $this->itemsTable . " WHERE id = ?");
			$stmt->bind_param("i", $this->id);
			$stmt->execute();
			$stmt->bind_result($id, $name, $description, $imageCover, $created, $modified);


			while ($stmt->fetch()) {

				$temp = array();

				$temp['id'] = $id;
				$temp['name'] = $name;
				$temp['description'] = $description;
				$temp['imageCover'] = $imageCover;
				$temp['created'] = $created;
				$temp['modified'] = $modified;

				array_push($menuCategory, $temp);
			}
		} else {


			$category_stmt = "SELECT DISTINCT(menu_type_id) FROM tblmenu ";
			$menu_type_id_result = mysqli_query($this->conn, $category_stmt);

			while ($row = mysqli_fetch_array($menu_type_id_result)) {

				array_push($categoryids, $row);
			}

			foreach ($categoryids as $row) {
				$menu = new MenuTypeClass($this->conn, intval($row['menu_type_id']));
				$temp = array();
				$temp['id'] = $menu->getMenuTypeId();
				$temp['name'] = $menu->getMenuTypeName();
				$temp['description'] = $menu->getMenuTypeDescription();
				$temp['imageCover'] = $menu->getMenuTypeImageCover();
				$temp['created'] = $menu->getMenuTypeCreated();
				$temp['modified'] = $menu->getMenuTypeModified();
				$temp['sectioned_menuItems'] = $menu->getCategoryMenuitems();
				array_push($menuCategory, $temp);
			}
		}

		$itemRecords["page"] = 1;
		$itemRecords["sectioned_category_results"] = $menuCategory;
		$itemRecords["total_pages"] = 2;
		$itemRecords["total_results"] = 4;

		return $itemRecords;
	}

	
}
