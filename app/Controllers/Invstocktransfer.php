<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Invstocktransfer extends BaseController
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
            SELECT st.*, 
                   l1.name as from_location_name, 
                   l2.name as to_location_name,
                   s1.name as created_by_name, 
                   s2.name as approved_by_name,
                   s3.name as received_by_name
            FROM inv_stock_transfer st
            LEFT JOIN inv_locations l1 ON st.from_location_id = l1.id
            LEFT JOIN inv_locations l2 ON st.to_location_id = l2.id
            LEFT JOIN staff s1 ON st.created_by = s1.id
            LEFT JOIN staff s2 ON st.approved_by = s2.id
            LEFT JOIN staff s3 ON st.received_by = s3.id
            ORDER BY st.id DESC
        ")->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_transfer/list', $data);
        echo view('template/footer');
    }

    public function add()
    {
        $data['locations'] = $this->db->table('inv_locations')
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        $data['items'] = $this->db->query("
            SELECT i.*, c.name as category_name, u.symbol as uom_name
            FROM inv_items i
            LEFT JOIN inv_categories c ON i.category_id = c.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            WHERE i.status = 1
            ORDER BY i.name ASC
        ")->getResultArray();

        // Generate doc number
        $yr = date('Y');
        $mon = date('m');
        $query = $this->db->query("SELECT doc_no FROM inv_stock_transfer WHERE id=(SELECT MAX(id) FROM inv_stock_transfer WHERE YEAR(doc_date)='" . $yr . "' AND MONTH(doc_date)='" . $mon . "')")->getRowArray();
        $data['doc_no'] = 'ST' . date('y') . $mon . (sprintf("%05d", (((float) substr($query['doc_no'], -5)) + 1)));

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_transfer/form', $data);
        echo view('template/footer');
    }

    public function get_location_stock()
    {
        $location_id = $_POST['location_id'];
        $item_id = $_POST['item_id'];

        $stock = $this->db->query("
            SELECT available_qty
            FROM inv_item_stock
            WHERE location_id = ? AND item_id = ?
        ", [$location_id, $item_id])->getRowArray();

        $data['available_qty'] = !empty($stock['available_qty']) ? $stock['available_qty'] : 0;

        echo json_encode($data);
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
        $query = $this->db->query("SELECT doc_no FROM inv_stock_transfer WHERE id=(SELECT MAX(id) FROM inv_stock_transfer WHERE YEAR(doc_date)='" . $yr . "' AND MONTH(doc_date)='" . $mon . "')")->getRowArray();

        $data['doc_no'] = 'ST' . date('y', strtotime($doc_date)) . $mon . (sprintf("%05d", (((float) substr($query['doc_no'], -5)) + 1)));
        $data['doc_date'] = $doc_date;
        $data['from_location_id'] = $_POST['from_location_id'];
        $data['to_location_id'] = $_POST['to_location_id'];
        $data['reference_no'] = trim($_POST['reference_no']);
        $data['remarks'] = trim($_POST['remarks']);
        $data['status'] = 'draft';
        $data['created_by'] = $this->session->get('log_id');

        if (empty($data['from_location_id']) || empty($data['to_location_id'])) {
            $msg_data['err'] = 'Please select both locations';
            echo json_encode($msg_data);
            exit();
        }

        if ($data['from_location_id'] == $data['to_location_id']) {
            $msg_data['err'] = 'From and To locations cannot be same';
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
                SELECT available_qty
                FROM inv_item_stock
                WHERE location_id = ? AND item_id = ?
            ", [$data['from_location_id'], $item['item_id']])->getRowArray();

            $available = !empty($stock['available_qty']) ? $stock['available_qty'] : 0;

            if ($item['quantity'] > $available) {
                $item_name = $this->db->table('inv_items')->where('id', $item['item_id'])->get()->getRowArray();
                $msg_data['err'] = 'Insufficient stock for item: ' . $item_name['name'] . ' (Available: ' . $available . ')';
                echo json_encode($msg_data);
                exit();
            }
        }

        // Insert stock transfer master
        $res = $this->db->table('inv_stock_transfer')->insert($data);
        $transfer_id = $this->db->insertID();

        if ($res) {
            // Insert stock transfer details
            foreach ($_POST['items'] as $item) {
                $detail_data['transfer_id'] = $transfer_id;
                $detail_data['item_id'] = $item['item_id'];
                $detail_data['quantity'] = $item['quantity'];
                $detail_data['received_qty'] = 0;
                $detail_data['batch_no'] = !empty($item['batch_no']) ? $item['batch_no'] : NULL;
                $detail_data['remarks'] = !empty($item['remarks']) ? $item['remarks'] : NULL;

                $this->db->table('inv_stock_transfer_details')->insert($detail_data);
            }

            $msg_data['succ'] = 'Stock Transfer Created Successfully';
            $msg_data['id'] = $transfer_id;
        } else {
            $msg_data['err'] = 'Please Try Again';
        }

        echo json_encode($msg_data);
        exit();
    }

    public function approve()
    {
        $id = $this->request->uri->getSegment(3);

        // Get stock transfer details
        $transfer = $this->db->table('inv_stock_transfer')->where('id', $id)->get()->getRowArray();

        if ($transfer['status'] != 'draft') {
            $this->session->setFlashdata('fail', 'Stock Transfer already processed');
            header("Location: " . base_url() . "/invstocktransfer");
            exit;
        }

        // Get items
        $items = $this->db->table('inv_stock_transfer_details')->where('transfer_id', $id)->get()->getResultArray();

        // Update stock transfer status
        $update_data['status'] = 'approved';
        $update_data['approved_by'] = $this->session->get('log_id');
        $this->db->table('inv_stock_transfer')->where('id', $id)->update($update_data);

        // Update item stock - Deduct from source location
        foreach ($items as $item) {
            // Deduct from source location
            $from_stock = $this->db->table('inv_item_stock')
                ->where('item_id', $item['item_id'])
                ->where('location_id', $transfer['from_location_id'])
                ->get()->getRowArray();

            if ($from_stock) {
                $new_qty = $from_stock['quantity'] - $item['quantity'];
                $new_available = $from_stock['available_qty'] - $item['quantity'];

                $this->db->table('inv_item_stock')
                    ->where('id', $from_stock['id'])
                    ->set('quantity', $new_qty)
                    ->set('available_qty', $new_available)
                    ->update();

                // Insert stock movement for source location (OUT)
                $movement_data['item_id'] = $item['item_id'];
                $movement_data['location_id'] = $transfer['from_location_id'];
                $movement_data['doc_type'] = 'stock_transfer';
                $movement_data['doc_no'] = $transfer['doc_no'];
                $movement_data['doc_date'] = $transfer['doc_date'];
                $movement_data['movement_type'] = 'out';
                $movement_data['quantity'] = $item['quantity'];
                $movement_data['balance_qty'] = $new_available;
                $movement_data['remarks'] = 'Transfer to: ' . $this->db->table('inv_locations')->where('id', $transfer['to_location_id'])->get()->getRowArray()['name'];
                $movement_data['created_by'] = $this->session->get('log_id');

                $this->db->table('inv_stock_movement')->insert($movement_data);
            }
        }

        $this->session->setFlashdata('succ', 'Stock Transfer Approved. Items will be added to destination after receiving.');
        header("Location: " . base_url() . "/invstocktransfer/view/" . $id);
    }

    public function receive()
    {
        $id = $this->request->uri->getSegment(3);

        // Get stock transfer details
        $transfer = $this->db->table('inv_stock_transfer')->where('id', $id)->get()->getRowArray();

        if ($transfer['status'] != 'approved') {
            $this->session->setFlashdata('fail', 'Stock Transfer must be approved first');
            header("Location: " . base_url() . "/invstocktransfer");
            exit;
        }

        // Get items
        $items = $this->db->table('inv_stock_transfer_details')->where('transfer_id', $id)->get()->getResultArray();

        // Update stock transfer status
        $update_data['status'] = 'received';
        $update_data['received_by'] = $this->session->get('log_id');
        $update_data['received_date'] = date('Y-m-d H:i:s');
        $this->db->table('inv_stock_transfer')->where('id', $id)->update($update_data);

        // Add to destination location
        foreach ($items as $item) {
            // Check if stock exists in destination
            $to_stock = $this->db->table('inv_item_stock')
                ->where('item_id', $item['item_id'])
                ->where('location_id', $transfer['to_location_id'])
                ->get()->getRowArray();

            if ($to_stock) {
                // Update existing stock
                $new_qty = $to_stock['quantity'] + $item['quantity'];
                $new_available = $to_stock['available_qty'] + $item['quantity'];

                $this->db->table('inv_item_stock')
                    ->where('id', $to_stock['id'])
                    ->set('quantity', $new_qty)
                    ->set('available_qty', $new_available)
                    ->update();
            } else {
                // Create new stock record
                $stock_data['item_id'] = $item['item_id'];
                $stock_data['location_id'] = $transfer['to_location_id'];
                $stock_data['quantity'] = $item['quantity'];
                $stock_data['reserved_qty'] = 0;
                $stock_data['available_qty'] = $item['quantity'];

                $this->db->table('inv_item_stock')->insert($stock_data);
            }

            // Update received quantity
            $this->db->table('inv_stock_transfer_details')
                ->where('id', $item['id'])
                ->set('received_qty', $item['quantity'])
                ->update();

            // Get updated balance
            $balance = $this->db->query("
                SELECT available_qty FROM inv_item_stock 
                WHERE item_id = ? AND location_id = ?
            ", [$item['item_id'], $transfer['to_location_id']])->getRowArray()['available_qty'];

            // Insert stock movement for destination location (IN)
            $movement_data['item_id'] = $item['item_id'];
            $movement_data['location_id'] = $transfer['to_location_id'];
            $movement_data['doc_type'] = 'stock_transfer';
            $movement_data['doc_no'] = $transfer['doc_no'];
            $movement_data['doc_date'] = $transfer['doc_date'];
            $movement_data['movement_type'] = 'in';
            $movement_data['quantity'] = $item['quantity'];
            $movement_data['balance_qty'] = $balance;
            $movement_data['remarks'] = 'Transfer from: ' . $this->db->table('inv_locations')->where('id', $transfer['from_location_id'])->get()->getRowArray()['name'];
            $movement_data['created_by'] = $this->session->get('log_id');

            $this->db->table('inv_stock_movement')->insert($movement_data);
        }

        $this->session->setFlashdata('succ', 'Stock Transfer Received Successfully');
        header("Location: " . base_url() . "/invstocktransfer/view/" . $id);
    }

    public function view()
    {
        $id = $this->request->uri->getSegment(3);

        $data['data'] = $this->db->query("
            SELECT st.*, 
                   l1.name as from_location_name, 
                   l2.name as to_location_name,
                   s1.name as created_by_name, 
                   s2.name as approved_by_name,
                   s3.name as received_by_name
            FROM inv_stock_transfer st
            LEFT JOIN inv_locations l1 ON st.from_location_id = l1.id
            LEFT JOIN inv_locations l2 ON st.to_location_id = l2.id
            LEFT JOIN staff s1 ON st.created_by = s1.id
            LEFT JOIN staff s2 ON st.approved_by = s2.id
            LEFT JOIN staff s3 ON st.received_by = s3.id
            WHERE st.id = ?
        ", [$id])->getRowArray();

        $data['items'] = $this->db->query("
            SELECT std.*, i.name as item_name, i.item_code, u.symbol as uom_name,
                   c.name as category_name
            FROM inv_stock_transfer_details std
            JOIN inv_items i ON std.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            LEFT JOIN inv_categories c ON i.category_id = c.id
            WHERE std.transfer_id = ?
        ", [$id])->getResultArray();

        $data['view'] = true;

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_transfer/view', $data);
        echo view('template/footer');
    }

    public function print_page()
    {
        $id = $this->request->uri->getSegment(3);

        $data['data'] = $this->db->query("
            SELECT st.*, 
                   l1.name as from_location_name, l1.address as from_location_address,
                   l2.name as to_location_name, l2.address as to_location_address,
                   s1.name as created_by_name, 
                   s2.name as approved_by_name,
                   s3.name as received_by_name
            FROM inv_stock_transfer st
            LEFT JOIN inv_locations l1 ON st.from_location_id = l1.id
            LEFT JOIN inv_locations l2 ON st.to_location_id = l2.id
            LEFT JOIN staff s1 ON st.created_by = s1.id
            LEFT JOIN staff s2 ON st.approved_by = s2.id
            LEFT JOIN staff s3 ON st.received_by = s3.id
            WHERE st.id = ?
        ", [$id])->getRowArray();

        $data['items'] = $this->db->query("
            SELECT std.*, i.name as item_name, i.item_code, u.symbol as uom_name,
                   c.name as category_name
            FROM inv_stock_transfer_details std
            JOIN inv_items i ON std.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            LEFT JOIN inv_categories c ON i.category_id = c.id
            WHERE std.transfer_id = ?
        ", [$id])->getResultArray();

        $data['temple'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

        echo view('inventory/stock_transfer/print', $data);
    }

    public function delete()
    {
        $id = $this->request->uri->getSegment(3);

        // Check if already approved
        $transfer = $this->db->table('inv_stock_transfer')->where('id', $id)->get()->getRowArray();

        if ($transfer['status'] != 'draft') {
            $this->session->setFlashdata('fail', 'Cannot delete processed Stock Transfer.');
            header("Location: " . base_url() . "/invstocktransfer");
            exit;
        }

        // Delete details
        $this->db->table('inv_stock_transfer_details')->where('transfer_id', $id)->delete();

        // Delete master
        $res = $this->db->table('inv_stock_transfer')->delete(['id' => $id]);

        if ($res) {
            $this->session->setFlashdata('succ', 'Stock Transfer Deleted Successfully');
        } else {
            $this->session->setFlashdata('fail', 'Please Try Again');
        }

        header("Location: " . base_url() . "/invstocktransfer");
    }
}