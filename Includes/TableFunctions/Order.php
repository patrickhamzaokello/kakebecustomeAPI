<?php
class Order
{

	private $order_table = "tblorder";
	private $order_detail_table = "tblorderdetails";
	public $order_id;
	public $order_address;
	public $customer_id;
	public $order_total_amount;
	public $order_status;
	public $processed_by;
	public $orderItemList;
	public $order_date;

	//orderdetails page
	public $menu_id;
	public $amount;
	public $no_of_serving;
	public $menu_total_amount;
	private $exe_status;

	// order private
	private $userOrderid;
	private $userOrderPage;

	private $conn;



	public function __construct($con)
	{
		$this->conn = $con;
		$this->exe_status = "failure";
	}

	function create()
	{

		$stmt = $this->conn->prepare("INSERT INTO " . $this->order_table . "(`order_address`, `customer_id`, `order_date`, `total_amount`, `order_status`, `processed_by`) VALUES(?,?,?,?,?,?)");

		$this->order_address = htmlspecialchars(strip_tags($this->order_address));
		$this->customer_id = htmlspecialchars(strip_tags($this->customer_id));
		$this->order_total_amount = htmlspecialchars(strip_tags($this->order_total_amount));
		$this->order_status = htmlspecialchars(strip_tags($this->order_status));
		$this->processed_by = htmlspecialchars(strip_tags($this->processed_by));
		$this->order_date = htmlspecialchars(strip_tags($this->order_date));

		$stmt->bind_param("sisiii", $this->order_address, $this->customer_id, $this->order_date, $this->order_total_amount, $this->order_status, $this->processed_by);

		if ($stmt->execute()) {

			$LastInsertOrderid = $this->conn->insert_id;
			$this->order_id  = $LastInsertOrderid;
			$this->exe_status = "success";
		} else {
			$this->exe_status = "failure";
		}

		$stmt_OrderDetail = $this->conn->prepare("INSERT INTO " . $this->order_detail_table . "(`order_id`, `menu_id`, `amount`, `no_of_serving`, `total_amount`) VALUES(?,?,?,?,?)");
		$stmt_OrderDetail->bind_param("siiii", $this->order_id, $this->menu_id, $this->amount, $this->no_of_serving, $this->menu_total_amount);



		foreach ($this->orderItemList  as $i => $i_value) {
			$this->menu_id = htmlspecialchars(strip_tags($i_value->menuId));
			$this->amount = htmlspecialchars(strip_tags($i_value->price));
			$this->no_of_serving = htmlspecialchars(strip_tags($i_value->quantity));
			$this->menu_total_amount = htmlspecialchars(strip_tags(($i_value->price) * ($i_value->quantity)));

			if ($stmt_OrderDetail->execute()) {
				$this->exe_status = "success";
			} else {
				$this->exe_status = "failure";
			}
		}


		if ($this->exe_status == "success") {
			return true;
		}

		return false;
	}


	function readUserOrders()
	{



		$itemRecords = array();

		$this->userOrderid = htmlspecialchars(strip_tags($_GET["customerId"]));
		$this->userOrderPage = htmlspecialchars(strip_tags($_GET["page"]));

		// echo "working". $this->userOrderid .$this->userOrderPage;


		if ($this->userOrderid) {
			$this->pageno = floatval($this->userOrderPage);
			$no_of_records_per_page = 10;
			$offset = ($this->pageno - 1) * $no_of_records_per_page;


			$sql = "SELECT COUNT(*) as count FROM " . $this->order_table . " WHERE customer_id = " . $this->userOrderid . " limit 1";
			$result = mysqli_query($this->conn, $sql);
			$data = mysqli_fetch_assoc($result);
			$total_rows = floatval($data['count']);
			$total_pages = ceil($total_rows / $no_of_records_per_page);


			$itemRecords["page"] = $this->pageno;
			$itemRecords["results"] = array();
			$itemRecords["total_pages"] = $total_pages;
			$itemRecords["total_results"] = $total_rows;


			$stmt = $this->conn->prepare("SELECT `order_id`, `order_address`, `customer_id`, `order_date`, `total_amount`, `order_status`, `processed_by` FROM " . $this->order_table . " WHERE customer_id = " . $this->userOrderid . " ORDER BY order_id DESC  LIMIT " . $offset . "," . $no_of_records_per_page);
		} else {
			// echo "working b";
			$stmt = $this->conn->prepare("SELECT `order_id`, `order_address`, `customer_id`, `order_date`, `total_amount`, `order_status`, `processed_by` FROM " . $this->order_table." ORDER BY order_id DESC" );
		}


		$stmt->execute();
		$stmt -> store_result();
		$stmt->bind_result($this->order_id, $this->order_address, $this->customer_id, $this->order_date, $this->total_amount, $this->order_status, $this->processed_by);

		$numberofrows = $stmt->num_rows;

		if($numberofrows > 0){
			while ($stmt->fetch()) {

				$temp = array();
	
				$temp['order_id'] = $this->order_id;
				$temp['order_address'] = $this->order_address;
				$temp['customer_id'] = $this->customer_id;
				$temp['order_date'] = $this->order_date;
				$temp['total_amount'] = $this->total_amount;
				$temp['order_status'] = $this->order_status;
				$temp['processed_by'] = $this->processed_by;
	
				array_push($itemRecords["results"], $temp);
			}
		} else {
			$temp = array();
	
				$temp['order_id'] = 0;
				$temp['order_address'] = "null";
				$temp['customer_id'] = 0;
				$temp['order_date'] = "null";
				$temp['total_amount'] = 0;
				$temp['order_status'] = 0;
				$temp['processed_by'] = 0;
	
				array_push($itemRecords["results"], $temp);
		}


		


		return $itemRecords;
	}




	function delete()
	{

		$stmt = $this->conn->prepare("
		DELETE FROM " . $this->order_table . " 
		WHERE id = ?");

		$this->id = htmlspecialchars(strip_tags($this->id));

		$stmt->bind_param("i", $this->id);

		if ($stmt->execute()) {
			return true;
		}

		return false;
	}
}
