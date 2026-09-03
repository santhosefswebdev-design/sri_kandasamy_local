<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Commission extends BaseController
{
    function __construct(){
        parent:: __construct();
        helper('url');
		helper("common");
        $this->model = new PermissionModel();
        if( ($this->session->get('login') ) == false && $this->session->get('role') != 1){
            $data['dn_msg'] = 'Please Login';
            header('Location: '.base_url().'/login');
            exit;
		}
    }
    
    public function index(){
		if(!$this->model->list_validate('commission')){
			header('Location: '.base_url().'/dashboard');
		}
		$data['permission'] = $this->model->get_permission('commission');

		$data['list'] = $this->db->table('commission')->join('staff','staff.id = commission.staff_id')->select('commission.*,staff.name as staff_name')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('commission/index',$data);
		echo view('template/footer');
    }
	public function view(){
	    if(!$this->model->permission_validate('member','view')){
			header('Location: '.base_url().'/dashboard');
		}
	    $id=  $this->request->uri->getSegment(3);
		
	    
	    $data['data'] = $this->db->table('commission')->where('id', $id)->get()->getRowArray();
		$data['staff_list'] = $this->db->table('staff')->where('is_admin',0)->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();
	    $data['view'] = true;
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('commission/add', $data);
		echo view('template/footer');
	}
	public function add()
	{
		if(!$this->model->permission_validate('commission', 'create_p')){
			header('Location: '.base_url().'/dashboard');
		}
		$data['staff_list'] = $this->db->table('staff')->where('is_admin',0)->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('commission/add',$data);
		echo view('template/footer');
	}
	public function edit(){
	    if(!$this->model->permission_validate('commission','edit')){
			header('Location: '.base_url().'/dashboard');			
		}
	    $id=  $this->request->uri->getSegment(3);
	    $data['data'] 		= $this->db->table('commission')->where('id', $id)->get()->getRowArray();
		$data['staff_list'] = $this->db->table('staff')->where('is_admin',0)->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('commission/add', $data);
		echo view('template/footer');
	}

	public function save(){
		// echo '<pre>'; print_r($_POST); die;
		$id = $_POST['id'];
        $data['date']		    =	trim($_POST['commission_date']);
		$data['type']    =	trim($_POST['commission_type']);
		$data['amount']    		=	trim($_POST['commission_amount']);
		$data['staff_id']  =	trim($_POST['commission_staff']);
		$data['remarks']  =	trim($_POST['commission_remarks']);

		if(empty($id)){
			$data['created']  =	date('Y-m-d H:i:s');
			$res = $this->db->table('commission')->insert($data);
			if($res){
				$this->session->setFlashdata('succ', 'commission Added Successfully');
				header("Location: ".base_url()."/commission");
			}else{
				$this->session->setFlashdata('fail', 'Please Try Again');
				header("Location: ".base_url()."/commission");
			}
		}else{
			$data['updated'] = date('Y-m-d H:i:s');
			$res = $this->db->table('commission')->where('id', $id)->update($data);
			if($res){
				$this->session->setFlashdata('succ', 'commission Updated Successfully');
				header("Location: ".base_url()."/commission");
			}else{
				$this->session->setFlashdata('fail', 'Please Try Again');
				header("Location: ".base_url()."/commission");
			}
		}
	}
	
	public function delete()
	{
		if(!$this->model->permission_validate('commission','delete_p')){
			header('Location: '.base_url().'/dashboard');			
		}
		$id=  $this->request->uri->getSegment(3);
		$res=$this->db->table('commission')->delete(['id' => $id]);
		if($res){
		    $this->session->setFlashdata('succ', 'commission Deleted Successfully');
		    header("Location: ".base_url()."/commission");
		}else{
		    $this->session->setFlashdata('fail', 'Please Try Again');
		    header("Location: ".base_url()."/commission");
		}
		header("Location: ".base_url()."/commission");
	     
	}
	
	
	public function print_page(){
		if(!$this->model->permission_validate('commission','print')){
			header('Location: '.base_url().'/dashboard');			
		}
	 	$id = $this->request->uri->getSegment(3);

		$data['qry1'] = $this->db->table('commission')->where('id', $id)->get()->getRowArray();
		echo view('commission/print_page', $data);
	}

	public function add_priest_commission()
	{
		if (!$this->model->permission_validate('commission', 'create_p')) {
			header('Location: ' . base_url() . '/dashboard');
		}

		$data['staff_list'] = $this->db->table('staff')
			->where('is_admin', 0)
			->where('status', 1)
			->orderBy('name', 'asc')
			->get()
			->getResultArray();

		$data['archanai_list'] = $this->db->table('archanai')
			->where('view_archanai', 1)
			->where('commission >', 0)
			->orderBy('name_eng', 'asc')
			->get()
			->getResultArray();

		echo view('template/header');
		echo view('template/sidebar');
		echo view('commission/add_priest', $data);
		echo view('template/footer');
	}

	public function save_priest_commission()
	{
		$data = array();
		$data['from_date'] = $_POST['from_date'];
		$data['to_date'] = $_POST['to_date'];
		$data['date'] = $_POST['from_date']; // Keep for backward compatibility
		$data['type'] = 'Priest Commission';
		$data['staff_id'] = $_POST['priest_id'];

		$total_commission = 0;
		$remarks_array = array();

		if (!empty($_POST['archanai_items'])) {
			foreach ($_POST['archanai_items'] as $item) {
				$archanai = $this->db->table('archanai')
					->where('id', $item['archanai_id'])
					->get()
					->getRowArray();

				$commission_amount = $archanai['commission'] * $item['quantity'];
				$total_commission += $commission_amount;

				$remarks_array[] = $archanai['name_eng'] . ' (Qty: ' . $item['quantity'] . ' × RM' . number_format($archanai['commission'], 2) . ')';
			}
		}

		$data['amount'] = $total_commission;
		$data['remarks'] = implode(', ', $remarks_array);
		$data['created'] = date('Y-m-d H:i:s');
		$data['updated'] = date('Y-m-d H:i:s');

		$builder = $this->db->table('commission')->insert($data);
		$commission_id = $this->db->insertID();

		if ($builder && $commission_id) {
			// Store detailed items for receipt
			foreach ($_POST['archanai_items'] as $item) {
				$archanai = $this->db->table('archanai')
					->where('id', $item['archanai_id'])
					->get()
					->getRowArray();

				$detail_data = array(
					'commission_id' => $commission_id,
					'archanai_id' => $item['archanai_id'],
					'archanai_name' => $archanai['name_eng'],
					'quantity' => $item['quantity'],
					'rate' => $archanai['commission'],
					'amount' => $archanai['commission'] * $item['quantity']
				);

				$this->db->table('commission_details')->insert($detail_data);
			}

			$this->session->setFlashdata('succ', 'Priest Commission Added Successfully');

			// Redirect to print receipt
			if (!empty($_POST['print_receipt'])) {
				header("Location: " . base_url() . "/commission/print_priest_receipt/" . $commission_id);
			} else {
				header("Location: " . base_url() . "/commission");
			}
		} else {
			$this->session->setFlashdata('fail', 'Please Try Again');
			header("Location: " . base_url() . "/commission/add_priest_commission");
		}
	}

	public function print_priest_receipt($id)
	{
		$data['commission'] = $this->db->table('commission')
			->join('staff', 'staff.id = commission.staff_id', 'left')
			->select('commission.*, staff.name as staff_name')
			->where('commission.id', $id)
			->get()
			->getRowArray();

		$data['details'] = $this->db->table('commission_details')
			->where('commission_id', $id)
			->get()
			->getResultArray();

		$data['temple_details'] = $this->db->table('admin_profile')
			->where('id', 1)
			->get()
			->getRowArray();

		echo view('commission/thermal_receipt', $data);
	}
	// Add this method to your Commission Controller (Commission.php)

	// Add this method to your Commission Controller (Commission.php)

	// Add this method to your Commission Controller (Commission.php)

	public function get_daily_sales()
	{
		$from_date = $this->request->getPost('from_date');
		$to_date = $this->request->getPost('to_date');

		if (empty($from_date) || empty($to_date)) {
			echo json_encode([
				'sales_data' => [],
				'error' => 'No date range provided',
				'debug' => ['from_date' => $from_date, 'to_date' => $to_date]
			]);
			exit();
		}

		// Get ALL archanai sales for the selected date range (confirmed bookings only)
		$sales = $this->db->table('archanai_booking')
			->join('archanai_booking_details', 'archanai_booking_details.archanai_booking_id = archanai_booking.id')
			->select('archanai_booking_details.archanai_id, SUM(archanai_booking_details.quantity) as total_quantity')
			->where('archanai_booking.date >=', $from_date)
			->where('archanai_booking.date <=', $to_date)
			->where('archanai_booking.payment_status', 2) // Only confirmed bookings
			->groupBy('archanai_booking_details.archanai_id')
			->get()
			->getResultArray();

		// Get already assigned priest commission quantities for the same date range
		$assigned_commissions = $this->db->table('commission')
			->join('commission_details', 'commission_details.commission_id = commission.id')
			->select('commission_details.archanai_id, SUM(commission_details.quantity) as assigned_quantity')
			->where('commission.from_date >=', $from_date)
			->where('commission.to_date <=', $to_date)
			->where('commission.type', 'Priest Commission')
			->groupBy('commission_details.archanai_id')
			->get()
			->getResultArray();

		// Create arrays for easy lookup
		$sales_data = [];
		foreach ($sales as $sale) {
			$sales_data[$sale['archanai_id']] = (int) $sale['total_quantity'];
		}

		$assigned_data = [];
		foreach ($assigned_commissions as $assigned) {
			$assigned_data[$assigned['archanai_id']] = (int) $assigned['assigned_quantity'];
		}

		// Calculate available quantities (sold - already assigned)
		$available_data = [];
		foreach ($sales_data as $archanai_id => $sold_qty) {
			$assigned_qty = isset($assigned_data[$archanai_id]) ? $assigned_data[$archanai_id] : 0;
			$available = $sold_qty - $assigned_qty;
			// Only include positive quantities
			if ($available > 0) {
				$available_data[$archanai_id] = $available;
			}
		}

		$result = array(
			'sales_data' => $available_data,
			'total_sold' => array_sum($sales_data),
			'total_assigned' => array_sum($assigned_data),
			'total_available' => array_sum($available_data),
			'debug' => [
				'from_date' => $from_date,
				'to_date' => $to_date,
				'sold_breakdown' => $sales_data,
				'assigned_breakdown' => $assigned_data
			]
		);

		echo json_encode($result);
		exit();
	}

}

