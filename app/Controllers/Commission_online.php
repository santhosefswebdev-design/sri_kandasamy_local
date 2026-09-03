<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Commission_online extends BaseController
{
    function __construct()
    {
        parent::__construct();
        helper('url');
        helper("common_helper");
        $this->model = new PermissionModel();
        if (($this->session->get('log_id_frend')) == false) {
            $data['dn_msg'] = 'Please Login';
            header('Location: ' . base_url() . '/member_login');
            exit;
        }
    }

    public function index()
    {
        $login_id = $_SESSION['log_id_frend'];

        // Get staff list for filter
        $data['staff_list'] = $this->db->table('staff')
            ->where('is_admin', 0)
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get()
            ->getResultArray();

        // Get archanai list for product filter
        $data['archanai_list'] = $this->db->table('archanai')
            ->where('view_archanai', 1)
            ->where('commission >', 0)
            ->orderBy('name_eng', 'asc')
            ->get()
            ->getResultArray();

        // Build query with filters
        $builder = $this->db->table('commission')
            ->join('staff', 'staff.id = commission.staff_id', 'left')
            ->select('commission.*, staff.name as staff_name')
            ->where('commission.type', 'Priest Commission');

        // Apply product/archanai filter if provided
        if (!empty($_GET['product_id'])) {
            $builder->join('commission_details', 'commission_details.commission_id = commission.id', 'inner');
            $builder->where('commission_details.archanai_id', $_GET['product_id']);
            $builder->groupBy('commission.id');
        }

        // Apply date filters if provided
        if (!empty($_GET['from_date'])) {
            $builder->where('commission.date >=', $_GET['from_date']);
        }

        if (!empty($_GET['to_date'])) {
            $builder->where('commission.date <=', $_GET['to_date']);
        }

        // Apply priest filter if provided
        if (!empty($_GET['priest_id'])) {
            $builder->where('commission.staff_id', $_GET['priest_id']);
        }

        // Get commission list with filters applied
        $data['commission_list'] = $builder->orderBy('commission.created', 'desc')
            ->get()
            ->getResultArray();

        echo view('frontend/layout/header');
        echo view('frontend/commission/index', $data);
    }

    public function add()
    {
        $login_id = $_SESSION['log_id_frend'];

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

        echo view('frontend/layout/header');
        echo view('frontend/commission/add_priest', $data);
    }

    // New function to get available quantity for a date and archanai
    public function get_daily_sales()
    {
        $date = $this->request->getPost('date');

        if (empty($date)) {
            echo json_encode([
                'sales_data' => [],
                'error' => 'No date provided'
            ]);
            exit();
        }

        // Get ALL archanai sales for the selected date (confirmed bookings only)
        $sales = $this->db->table('archanai_booking')
            ->join('archanai_booking_details', 'archanai_booking_details.archanai_booking_id = archanai_booking.id')
            ->select('archanai_booking_details.archanai_id, SUM(archanai_booking_details.quantity) as total_quantity')
            ->where('archanai_booking.date', $date)
            ->where('archanai_booking.payment_status', 2) // Only confirmed bookings
            ->groupBy('archanai_booking_details.archanai_id')
            ->get()
            ->getResultArray();

        // Get already assigned priest commission quantities for the same date
        $assigned_commissions = $this->db->table('commission')
            ->join('commission_details', 'commission_details.commission_id = commission.id')
            ->select('commission_details.archanai_id, SUM(commission_details.quantity) as assigned_quantity')
            ->where('commission.date', $date)
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

        echo json_encode([
            'sales_data' => $available_data,
            'total_sold' => array_sum($sales_data),
            'total_assigned' => array_sum($assigned_data),
            'total_available' => array_sum($available_data),
            'debug' => [
                'date' => $date,
                'sold_breakdown' => $sales_data,
                'assigned_breakdown' => $assigned_data
            ]
        ]);
        exit();
    }
    public function save()
    {
        $msg_data = array();
        $msg_data['err'] = '';
        $msg_data['succ'] = '';

        $data = array();
        $data['date'] = $_POST['commission_date'];
        $data['type'] = 'Priest Commission';
        $data['staff_id'] = $_POST['priest_id'];

        $total_commission = 0;
        $remarks_array = array();

        if (!empty($_POST['archanai_items'])) {
            foreach ($_POST['archanai_items'] as $item) {
                if (!empty($item['archanai_id']) && !empty($item['quantity'])) {
                    // SERVER-SIDE VALIDATION: Check available quantity
                    $sold_qty = $this->db->table('archanai_booking')
                        ->join('archanai_booking_details', 'archanai_booking_details.archanai_booking_id = archanai_booking.id')
                        ->select('SUM(archanai_booking_details.quantity) as total_qty')
                        ->where('archanai_booking.date', $data['date'])
                        ->where('archanai_booking_details.archanai_id', $item['archanai_id'])
                        ->where('archanai_booking.payment_status', 2) // Only confirmed bookings
                        ->get()
                        ->getRowArray();

                    $assigned_qty = $this->db->table('commission')
                        ->join('commission_details', 'commission_details.commission_id = commission.id')
                        ->select('SUM(commission_details.quantity) as assigned_qty')
                        ->where('commission.date', $data['date'])
                        ->where('commission_details.archanai_id', $item['archanai_id'])
                        ->where('commission.type', 'Priest Commission')
                        ->get()
                        ->getRowArray();

                    $total_sold = !empty($sold_qty['total_qty']) ? $sold_qty['total_qty'] : 0;
                    $total_assigned = !empty($assigned_qty['assigned_qty']) ? $assigned_qty['assigned_qty'] : 0;
                    $available = $total_sold - $total_assigned;

                    // Get archanai name for error message
                    $archanai = $this->db->table('archanai')
                        ->where('id', $item['archanai_id'])
                        ->get()
                        ->getRowArray();

                    if ($item['quantity'] > $available) {
                        $msg_data['err'] = 'Quantity for "' . $archanai['name_eng'] . '" exceeds available quantity. Available: ' . $available;
                        echo json_encode($msg_data);
                        exit();
                    }

                    $commission_amount = $archanai['commission'] * $item['quantity'];
                    $total_commission += $commission_amount;

                    $remarks_array[] = $archanai['name_eng'] . ' (Qty: ' . $item['quantity'] . ' × RM' . number_format($archanai['commission'], 2) . ')';
                }
            }
        }

        if ($total_commission <= 0) {
            $msg_data['err'] = 'Please add at least one service with quantity';
            echo json_encode($msg_data);
            exit();
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
                if (!empty($item['archanai_id']) && !empty($item['quantity'])) {
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
            }

            $msg_data['succ'] = 'Priest Commission Added Successfully';
            $msg_data['id'] = $commission_id;
        } else {
            $msg_data['err'] = 'Please Try Again';
        }

        echo json_encode($msg_data);
        exit();
    }

    public function view($id)
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

        echo view('frontend/layout/header');
        echo view('frontend/commission/view_details', $data);
    }

    public function print_receipt($id)
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

        echo view('frontend/commission/thermal_receipt', $data);
    }

    public function delete($id)
    {
        // Delete commission details first
        $this->db->table('commission_details')
            ->where('commission_id', $id)
            ->delete();

        // Delete main commission record
        $result = $this->db->table('commission')
            ->where('id', $id)
            ->delete();

        if ($result) {
            $_SESSION['succ'] = 'Commission deleted successfully';
        } else {
            $_SESSION['fail'] = 'Failed to delete commission';
        }

        return redirect()->to(base_url() . '/commission_online');
    }
}