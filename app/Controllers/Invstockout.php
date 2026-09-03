<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Invstockout extends BaseController
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
            SELECT so.*, l.name as location_name,
                   st.name as created_by_name, ap.name as approved_by_name
            FROM inv_stock_out so
            LEFT JOIN inv_locations l ON so.location_id = l.id
            LEFT JOIN staff st ON so.created_by = st.id
            LEFT JOIN staff ap ON so.approved_by = ap.id
            ORDER BY so.id DESC
        ")->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_out/list', $data);
        echo view('template/footer');
    }

    public function add()
    {
        $data['locations'] = $this->db->table('inv_locations')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();

        // Generate doc number
        $yr = date('Y');
        $mon = date('m');
        $query = $this->db->query("SELECT doc_no FROM inv_stock_out WHERE id=(SELECT MAX(id) FROM inv_stock_out WHERE YEAR(doc_date)='" . $yr . "' AND MONTH(doc_date)='" . $mon . "')")->getRowArray();
        $data['doc_no'] = 'SO' . date('y') . $mon . (sprintf("%05d", (((float) substr($query['doc_no'], -5)) + 1)));

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_out/form', $data);
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
            WHERE s.location_id = ? AND s.available_qty > 0
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
        $query = $this->db->query("SELECT doc_no FROM inv_stock_out WHERE id=(SELECT MAX(id) FROM inv_stock_out WHERE YEAR(doc_date)='" . $yr . "' AND MONTH(doc_date)='" . $mon . "')")->getRowArray();

        $data['doc_no'] = 'SO' . date('y', strtotime($doc_date)) . $mon . (sprintf("%05d", (((float) substr($query['doc_no'], -5)) + 1)));
        $data['doc_date'] = $doc_date;
        $data['location_id'] = $_POST['location_id'];
        $data['out_type'] = $_POST['out_type'];
        $data['department'] = !empty($_POST['department']) ? $_POST['department'] : NULL;
        $data['reference_no'] = trim($_POST['reference_no']);
        $data['total_amount'] = $_POST['total_amount'];
        $data['issued_to'] = trim($_POST['issued_to']);
        $data['remarks'] = trim($_POST['remarks']);
        $data['status'] = 'draft';
        $data['created_by'] = $this->session->get('log_id');

        if (empty($data['location_id']) || empty($data['out_type'])) {
            $msg_data['err'] = 'Please fill required fields';
            echo json_encode($msg_data);
            exit();
        }

        if (empty($_POST['items']) || count($_POST['items']) == 0) {
            $msg_data['err'] = 'Please add at least one item';
            echo json_encode($msg_data);
            exit();
        }

        // Check stock availability
        foreach ($_POST['items'] as $item) {
            $stock = $this->db->query("
                SELECT available_qty FROM inv_item_stock 
                WHERE item_id = ? AND location_id = ?
            ", [$item['item_id'], $data['location_id']])->getRowArray();

            if (empty($stock) || $stock['available_qty'] < $item['quantity']) {
                $item_name = $this->db->table('inv_items')->where('id', $item['item_id'])->get()->getRowArray()['name'];
                $msg_data['err'] = 'Insufficient stock for item: ' . $item_name;
                echo json_encode($msg_data);
                exit();
            }
        }

        // Insert stock out master
        $res = $this->db->table('inv_stock_out')->insert($data);
        $stock_out_id = $this->db->insertID();

        if ($res) {
            // Insert stock out details
            foreach ($_POST['items'] as $item) {
                $detail_data['stock_out_id'] = $stock_out_id;
                $detail_data['item_id'] = $item['item_id'];
                $detail_data['quantity'] = $item['quantity'];
                $detail_data['rate'] = $item['rate'];
                $detail_data['amount'] = $item['amount'];
                $detail_data['batch_no'] = !empty($item['batch_no']) ? $item['batch_no'] : NULL;
                $detail_data['remarks'] = !empty($item['remarks']) ? $item['remarks'] : NULL;

                $this->db->table('inv_stock_out_details')->insert($detail_data);
            }

            $msg_data['succ'] = 'Stock Out Added Successfully';
            $msg_data['id'] = $stock_out_id;
        } else {
            $msg_data['err'] = 'Please Try Again';
        }

        echo json_encode($msg_data);
        exit();
    }

    public function approve()
    {
        $id = $this->request->uri->getSegment(3);

        // Get stock out details
        $stock_out = $this->db->table('inv_stock_out')->where('id', $id)->get()->getRowArray();

        if ($stock_out['status'] != 'draft') {
            $this->session->setFlashdata('fail', 'Stock Out already processed');
            header("Location: " . base_url() . "/invstockout");
            exit;
        }

        // Get items
        $items = $this->db->table('inv_stock_out_details')->where('stock_out_id', $id)->get()->getResultArray();

        // Check stock availability again
        foreach ($items as $item) {
            $stock = $this->db->query("
                SELECT available_qty FROM inv_item_stock 
                WHERE item_id = ? AND location_id = ?
            ", [$item['item_id'], $stock_out['location_id']])->getRowArray();

            if (empty($stock) || $stock['available_qty'] < $item['quantity']) {
                $item_name = $this->db->table('inv_items')->where('id', $item['item_id'])->get()->getRowArray()['name'];
                $this->session->setFlashdata('fail', 'Insufficient stock for item: ' . $item_name);
                header("Location: " . base_url() . "/invstockout/view/" . $id);
                exit;
            }
        }

        // Update stock out status
        $update_data['status'] = 'approved';
        $update_data['approved_by'] = $this->session->get('log_id');
        $update_data['approved_date'] = date('Y-m-d H:i:s');
        $this->db->table('inv_stock_out')->where('id', $id)->update($update_data);

        // Update item stock
        foreach ($items as $item) {
            // Get current stock
            $existing_stock = $this->db->table('inv_item_stock')
                ->where('item_id', $item['item_id'])
                ->where('location_id', $stock_out['location_id'])
                ->get()->getRowArray();

            if ($existing_stock) {
                // Update stock
                $new_qty = $existing_stock['quantity'] - $item['quantity'];
                $new_available = $existing_stock['available_qty'] - $item['quantity'];

                $this->db->table('inv_item_stock')
                    ->where('id', $existing_stock['id'])
                    ->set('quantity', $new_qty)
                    ->set('available_qty', $new_available)
                    ->update();
            }

            // Insert stock movement
            $movement_data['item_id'] = $item['item_id'];
            $movement_data['location_id'] = $stock_out['location_id'];
            $movement_data['doc_type'] = 'stock_out';
            $movement_data['doc_no'] = $stock_out['doc_no'];
            $movement_data['doc_date'] = $stock_out['doc_date'];
            $movement_data['movement_type'] = 'out';
            $movement_data['quantity'] = $item['quantity'];
            $movement_data['balance_qty'] = $this->db->query("
                SELECT available_qty FROM inv_item_stock 
                WHERE item_id = ? AND location_id = ?
            ", [$item['item_id'], $stock_out['location_id']])->getRowArray()['available_qty'];
            $movement_data['rate'] = $item['rate'];
            $movement_data['amount'] = $item['amount'];
            $movement_data['remarks'] = 'Stock Out - ' . $stock_out['doc_no'] . ' (' . $stock_out['out_type'] . ')';
            $movement_data['created_by'] = $this->session->get('log_id');

            $this->db->table('inv_stock_movement')->insert($movement_data);
        }

        $this->session->setFlashdata('succ', 'Stock Out Approved Successfully');
        header("Location: " . base_url() . "/invstockout/view/" . $id);
    }

    public function view()
    {
        $id = $this->request->uri->getSegment(3);

        $data['data'] = $this->db->query("
            SELECT so.*, l.name as location_name,
                   st.name as created_by_name, ap.name as approved_by_name
            FROM inv_stock_out so
            LEFT JOIN inv_locations l ON so.location_id = l.id
            LEFT JOIN staff st ON so.created_by = st.id
            LEFT JOIN staff ap ON so.approved_by = ap.id
            WHERE so.id = ?
        ", [$id])->getRowArray();

        $data['items'] = $this->db->query("
            SELECT sod.*, i.name as item_name, i.item_code, u.symbol as uom_name,
                   c.name as category_name
            FROM inv_stock_out_details sod
            JOIN inv_items i ON sod.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            LEFT JOIN inv_categories c ON i.category_id = c.id
            WHERE sod.stock_out_id = ?
        ", [$id])->getResultArray();

        $data['view'] = true;

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_out/view', $data);
        echo view('template/footer');
    }

    public function print_page()
    {
        $id = $this->request->uri->getSegment(3);

        $data['data'] = $this->db->query("
            SELECT so.*, l.name as location_name, l.address as location_address,
                   st.name as created_by_name, ap.name as approved_by_name
            FROM inv_stock_out so
            LEFT JOIN inv_locations l ON so.location_id = l.id
            LEFT JOIN staff st ON so.created_by = st.id
            LEFT JOIN staff ap ON so.approved_by = ap.id
            WHERE so.id = ?
        ", [$id])->getRowArray();

        $data['items'] = $this->db->query("
            SELECT sod.*, i.name as item_name, i.item_code, u.symbol as uom_name,
                   c.name as category_name
            FROM inv_stock_out_details sod
            JOIN inv_items i ON sod.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            LEFT JOIN inv_categories c ON i.category_id = c.id
            WHERE sod.stock_out_id = ?
        ", [$id])->getResultArray();

        $data['temple'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

        echo view('inventory/stock_out/print', $data);
    }

    public function delete()
    {
        $id = $this->request->uri->getSegment(3);

        // Check if already approved
        $stock_out = $this->db->table('inv_stock_out')->where('id', $id)->get()->getRowArray();

        if ($stock_out['status'] == 'approved') {
            $this->session->setFlashdata('fail', 'Cannot delete approved Stock Out. Please contact administrator.');
            header("Location: " . base_url() . "/invstockout");
            exit;
        }

        // Delete details
        $this->db->table('inv_stock_out_details')->where('stock_out_id', $id)->delete();

        // Delete master
        $res = $this->db->table('inv_stock_out')->delete(['id' => $id]);

        if ($res) {
            $this->session->setFlashdata('succ', 'Stock Out Deleted Successfully');
        } else {
            $this->session->setFlashdata('fail', 'Please Try Again');
        }

        header("Location: " . base_url() . "/invstockout");
    }
}