<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Invstockadjustment extends BaseController
{
    function __construct()
    {
        parent::__construct();
        helper('url');
        $this->model = new PermissionModel();
        if (($this->session->get('login')) == false && $this->session->get('role') != 1) {
            $data['dn_msg'] = 'Please Login';
            header('Location: ' . base_url() . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['list'] = $this->db->query("
            SELECT sa.*, l.name as location_name,
                   st.name as created_by_name, ap.name as approved_by_name
            FROM inv_stock_adjustment sa
            LEFT JOIN inv_locations l ON sa.location_id = l.id
            LEFT JOIN staff st ON sa.created_by = st.id
            LEFT JOIN staff ap ON sa.approved_by = ap.id
            ORDER BY sa.id DESC
        ")->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_adjustment/list', $data);
        echo view('template/footer');
    }

    public function add()
    {
        $data['locations'] = $this->db->table('inv_locations')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();

        // Generate doc number
        $yr = date('Y');
        $mon = date('m');
        $query = $this->db->query("SELECT doc_no FROM inv_stock_adjustment WHERE id=(SELECT MAX(id) FROM inv_stock_adjustment WHERE YEAR(doc_date)='" . $yr . "' AND MONTH(doc_date)='" . $mon . "')")->getRowArray();
        $data['doc_no'] = 'ADJ' . date('y') . $mon . (sprintf("%05d", (((float) substr($query['doc_no'], -5)) + 1)));

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_adjustment/form', $data);
        echo view('template/footer');
    }

    public function get_location_items()
    {
        $location_id = $_POST['location_id'];

        $items = $this->db->query("
            SELECT s.*, i.name as item_name, i.item_code, i.name_tamil,
                   u.symbol as uom_name, c.name as category_name, i.avg_cost
            FROM inv_item_stock s
            JOIN inv_items i ON s.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            LEFT JOIN inv_categories c ON i.category_id = c.id
            WHERE s.location_id = ? AND i.status = 1
            ORDER BY i.name ASC
        ", [$location_id])->getResultArray();

        echo json_encode($items);
    }

    public function save()
    {
        $msg_data = array();
        $msg_data['err'] = '';
        $msg_data['succ'] = '';

        $id = $_POST['id'];
        $doc_date = $_POST['doc_date'];

        // Generate doc number
        $yr = date('Y', strtotime($doc_date));
        $mon = date('m', strtotime($doc_date));
        $query = $this->db->query("SELECT doc_no FROM inv_stock_adjustment WHERE id=(SELECT MAX(id) FROM inv_stock_adjustment WHERE YEAR(doc_date)='" . $yr . "' AND MONTH(doc_date)='" . $mon . "')")->getRowArray();

        $data['doc_no'] = 'ADJ' . date('y', strtotime($doc_date)) . $mon . (sprintf("%05d", (((float) substr($query['doc_no'], -5)) + 1)));
        $data['doc_date'] = $doc_date;
        $data['location_id'] = $_POST['location_id'];
        $data['adjustment_type'] = $_POST['adjustment_type'];
        $data['reference_no'] = trim($_POST['reference_no']);
        $data['remarks'] = trim($_POST['remarks']);
        $data['status'] = 'draft';
        $data['created_by'] = $this->session->get('log_id');

        if (empty($data['location_id']) || empty($data['adjustment_type'])) {
            $msg_data['err'] = 'Please fill required fields';
            echo json_encode($msg_data);
            exit();
        }

        if (empty($_POST['items']) || count($_POST['items']) == 0) {
            $msg_data['err'] = 'Please add at least one item';
            echo json_encode($msg_data);
            exit();
        }

        // Check if there are any differences
        $has_difference = false;
        foreach ($_POST['items'] as $item) {
            if ($item['difference_qty'] != 0) {
                $has_difference = true;
                break;
            }
        }

        if (!$has_difference) {
            $msg_data['err'] = 'No adjustments found. Please enter physical quantity different from system quantity.';
            echo json_encode($msg_data);
            exit();
        }

        // Insert adjustment master
        $res = $this->db->table('inv_stock_adjustment')->insert($data);
        $adjustment_id = $this->db->insertID();

        if ($res) {
            // Insert adjustment details
            foreach ($_POST['items'] as $item) {
                if ($item['difference_qty'] != 0) { // Only save items with differences
                    $detail_data['adjustment_id'] = $adjustment_id;
                    $detail_data['item_id'] = $item['item_id'];
                    $detail_data['system_qty'] = $item['system_qty'];
                    $detail_data['physical_qty'] = $item['physical_qty'];
                    $detail_data['difference_qty'] = $item['difference_qty'];
                    $detail_data['rate'] = $item['rate'];
                    $detail_data['amount'] = $item['amount'];
                    $detail_data['remarks'] = !empty($item['remarks']) ? $item['remarks'] : NULL;

                    $this->db->table('inv_stock_adjustment_details')->insert($detail_data);
                }
            }

            $msg_data['succ'] = 'Stock Adjustment Added Successfully';
            $msg_data['id'] = $adjustment_id;
        } else {
            $msg_data['err'] = 'Please Try Again';
        }

        echo json_encode($msg_data);
        exit();
    }

    public function approve()
    {
        $id = $this->request->uri->getSegment(3);

        // Get adjustment details
        $adjustment = $this->db->table('inv_stock_adjustment')->where('id', $id)->get()->getRowArray();

        if ($adjustment['status'] != 'draft') {
            $this->session->setFlashdata('fail', 'Stock Adjustment already processed');
            header("Location: " . base_url() . "/invstockadjustment");
            exit;
        }

        // Get items
        $items = $this->db->table('inv_stock_adjustment_details')->where('adjustment_id', $id)->get()->getResultArray();

        // Update adjustment status
        $update_data['status'] = 'approved';
        $update_data['approved_by'] = $this->session->get('log_id');
        $update_data['approved_date'] = date('Y-m-d H:i:s');
        $this->db->table('inv_stock_adjustment')->where('id', $id)->update($update_data);

        // Update item stock
        foreach ($items as $item) {
            // Get current stock
            $current_stock = $this->db->table('inv_item_stock')
                ->where('item_id', $item['item_id'])
                ->where('location_id', $adjustment['location_id'])
                ->get()->getRowArray();

            if ($current_stock) {
                // Calculate new quantities
                $new_qty = $current_stock['quantity'] + $item['difference_qty'];
                $new_available = $current_stock['available_qty'] + $item['difference_qty'];

                // Ensure quantities don't go negative
                if ($new_qty < 0)
                    $new_qty = 0;
                if ($new_available < 0)
                    $new_available = 0;

                // Update stock
                $this->db->table('inv_item_stock')
                    ->where('id', $current_stock['id'])
                    ->set('quantity', $new_qty)
                    ->set('available_qty', $new_available)
                    ->update();

                // Insert stock movement
                $movement_type = ($item['difference_qty'] > 0) ? 'in' : 'out';
                $movement_qty = abs($item['difference_qty']);

                $movement_data['item_id'] = $item['item_id'];
                $movement_data['location_id'] = $adjustment['location_id'];
                $movement_data['doc_type'] = 'stock_adjustment';
                $movement_data['doc_no'] = $adjustment['doc_no'];
                $movement_data['doc_date'] = $adjustment['doc_date'];
                $movement_data['movement_type'] = $movement_type;
                $movement_data['quantity'] = $movement_qty;
                $movement_data['balance_qty'] = $new_available;
                $movement_data['rate'] = $item['rate'];
                $movement_data['amount'] = abs($item['amount']);
                $movement_data['remarks'] = 'Stock Adjustment - ' . $adjustment['adjustment_type'] . ' - ' . $adjustment['doc_no'];
                $movement_data['created_by'] = $this->session->get('log_id');

                $this->db->table('inv_stock_movement')->insert($movement_data);
            }
        }

        $this->session->setFlashdata('succ', 'Stock Adjustment Approved Successfully');
        header("Location: " . base_url() . "/invstockadjustment/view/" . $id);
    }

    public function view()
    {
        $id = $this->request->uri->getSegment(3);

        $data['data'] = $this->db->query("
            SELECT sa.*, l.name as location_name,
                   st.name as created_by_name, ap.name as approved_by_name
            FROM inv_stock_adjustment sa
            LEFT JOIN inv_locations l ON sa.location_id = l.id
            LEFT JOIN staff st ON sa.created_by = st.id
            LEFT JOIN staff ap ON sa.approved_by = ap.id
            WHERE sa.id = ?
        ", [$id])->getRowArray();

        $data['items'] = $this->db->query("
            SELECT sad.*, i.name as item_name, i.item_code, u.symbol as uom_name,
                   c.name as category_name
            FROM inv_stock_adjustment_details sad
            JOIN inv_items i ON sad.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            LEFT JOIN inv_categories c ON i.category_id = c.id
            WHERE sad.adjustment_id = ?
        ", [$id])->getResultArray();

        $data['view'] = true;

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_adjustment/view', $data);
        echo view('template/footer');
    }

    public function print_page()
    {
        $id = $this->request->uri->getSegment(3);

        $data['data'] = $this->db->query("
            SELECT sa.*, l.name as location_name, l.address as location_address,
                   st.name as created_by_name, ap.name as approved_by_name
            FROM inv_stock_adjustment sa
            LEFT JOIN inv_locations l ON sa.location_id = l.id
            LEFT JOIN staff st ON sa.created_by = st.id
            LEFT JOIN staff ap ON sa.approved_by = ap.id
            WHERE sa.id = ?
        ", [$id])->getRowArray();

        $data['items'] = $this->db->query("
            SELECT sad.*, i.name as item_name, i.item_code, u.symbol as uom_name,
                   c.name as category_name
            FROM inv_stock_adjustment_details sad
            JOIN inv_items i ON sad.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            LEFT JOIN inv_categories c ON i.category_id = c.id
            WHERE sad.adjustment_id = ?
        ", [$id])->getResultArray();

        $data['temple'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

        echo view('inventory/stock_adjustment/print', $data);
    }

    public function delete()
    {
        $id = $this->request->uri->getSegment(3);

        // Check if already approved
        $adjustment = $this->db->table('inv_stock_adjustment')->where('id', $id)->get()->getRowArray();

        if ($adjustment['status'] == 'approved') {
            $this->session->setFlashdata('fail', 'Cannot delete approved Stock Adjustment. Please contact administrator.');
            header("Location: " . base_url() . "/invstockadjustment");
            exit;
        }

        // Delete details
        $this->db->table('inv_stock_adjustment_details')->where('adjustment_id', $id)->delete();

        // Delete master
        $res = $this->db->table('inv_stock_adjustment')->delete(['id' => $id]);

        if ($res) {
            $this->session->setFlashdata('succ', 'Stock Adjustment Deleted Successfully');
        } else {
            $this->session->setFlashdata('fail', 'Please Try Again');
        }

        header("Location: " . base_url() . "/invstockadjustment");
    }
}