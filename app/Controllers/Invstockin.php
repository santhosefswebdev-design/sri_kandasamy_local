<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Invstockin extends BaseController
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
            SELECT si.*, l.name as location_name, s.name as supplier_name,
                   st.name as created_by_name
            FROM inv_stock_in si
            LEFT JOIN inv_locations l ON si.location_id = l.id
            LEFT JOIN inv_suppliers s ON si.supplier_id = s.id
            LEFT JOIN staff st ON si.created_by = st.id
            ORDER BY si.id DESC
        ")->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_in/list', $data);
        echo view('template/footer');
    }

    public function add()
    {
        $data['locations'] = $this->db->table('inv_locations')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();
        $data['suppliers'] = $this->db->table('inv_suppliers')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();
        $data['items'] = $this->db->table('inv_items i')
            ->join('uom_list u', 'i.uom_id = u.id', 'left')
            ->join('inv_categories c', 'i.category_id = c.id', 'left')
            ->select('i.*, u.symbol as uom_name, c.name as category_name')
            ->where('i.status', 1)
            ->orderBy('i.name', 'ASC')
            ->get()->getResultArray();

        // Generate doc number
        $yr = date('Y');
        $mon = date('m');
        $query = $this->db->query("SELECT doc_no FROM inv_stock_in WHERE YEAR(doc_date) = ? AND MONTH(doc_date) = ? ORDER BY id DESC LIMIT 1", [$yr, $mon])->getRowArray();
        $data['doc_no'] = 'SI' . date('y') . $mon . sprintf("%05d", (!empty($query) ? ((int) substr($query['doc_no'], -5)) + 1 : 1));

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_in/form', $data);
        echo view('template/footer');
    }

    public function edit()
    {
        $id = $this->request->uri->getSegment(3);

        // Check if already approved
        $check = $this->db->table('inv_stock_in')->where('id', $id)->get()->getRowArray();
        if ($check['status'] == 'approved') {
            $this->session->setFlashdata('fail', 'Cannot edit approved stock in');
            header("Location: " . base_url() . "/invstockin");
            exit;
        }

        $data['data'] = $check;
        $data['locations'] = $this->db->table('inv_locations')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();
        $data['suppliers'] = $this->db->table('inv_suppliers')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();
        $data['items'] = $this->db->table('inv_items i')
            ->join('uom_list u', 'i.uom_id = u.id', 'left')
            ->join('inv_categories c', 'i.category_id = c.id', 'left')
            ->select('i.*, u.symbol as uom_name, c.name as category_name')
            ->where('i.status', 1)
            ->orderBy('i.name', 'ASC')
            ->get()->getResultArray();

        $data['details'] = $this->db->query("
            SELECT d.*, i.name as item_name, i.item_code, u.symbol as uom_name
            FROM inv_stock_in_details d
            JOIN inv_items i ON d.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            WHERE d.stock_in_id = ?
        ", [$id])->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_in/form', $data);
        echo view('template/footer');
    }

    public function view()
    {
        $id = $this->request->uri->getSegment(3);
        $data['data'] = $this->db->query("
            SELECT si.*, l.name as location_name, s.name as supplier_name, s.phone as supplier_phone,
                   s.address1, s.city, s.state,
                   st.name as created_by_name, st2.name as approved_by_name
            FROM inv_stock_in si
            LEFT JOIN inv_locations l ON si.location_id = l.id
            LEFT JOIN inv_suppliers s ON si.supplier_id = s.id
            LEFT JOIN staff st ON si.created_by = st.id
            LEFT JOIN staff st2 ON si.approved_by = st2.id
            WHERE si.id = ?
        ", [$id])->getRowArray();

        $data['details'] = $this->db->query("
            SELECT d.*, i.name as item_name, i.item_code, i.name_tamil, u.symbol as uom_name,
                   c.name as category_name
            FROM inv_stock_in_details d
            JOIN inv_items i ON d.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            LEFT JOIN inv_categories c ON i.category_id = c.id
            WHERE d.stock_in_id = ?
            ORDER BY d.id ASC
        ", [$id])->getResultArray();

        $data['view'] = true;

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_in/view', $data);
        echo view('template/footer');
    }

    public function save()
    {
        $msg_data = array();
        $msg_data['err'] = '';
        $msg_data['succ'] = '';

        $id = $_POST['id'];
        $doc_date = $_POST['doc_date'];

        // Validate
        if (empty($_POST['location_id']) || empty($_POST['items'])) {
            $msg_data['err'] = 'Please fill all required fields';
            echo json_encode($msg_data);
            exit;
        }

        // Generate doc number if new
        if (empty($id)) {
            $yr = date('Y', strtotime($doc_date));
            $mon = date('m', strtotime($doc_date));
            $query = $this->db->query("SELECT doc_no FROM inv_stock_in WHERE YEAR(doc_date) = ? AND MONTH(doc_date) = ? ORDER BY id DESC LIMIT 1", [$yr, $mon])->getRowArray();
            $data['doc_no'] = 'SI' . date('y', strtotime($doc_date)) . $mon . sprintf("%05d", (!empty($query) ? ((int) substr($query['doc_no'], -5)) + 1 : 1));
        }

        $data['doc_date'] = $doc_date;
        $data['location_id'] = $_POST['location_id'];
        $data['supplier_id'] = !empty($_POST['supplier_id']) ? $_POST['supplier_id'] : NULL;
        $data['supplier_invoice_no'] = trim($_POST['supplier_invoice_no']);
        $data['supplier_invoice_date'] = !empty($_POST['supplier_invoice_date']) ? $_POST['supplier_invoice_date'] : NULL;
        $data['reference_no'] = trim($_POST['reference_no']);
        $data['remarks'] = trim($_POST['remarks']);
        $data['total_amount'] = $_POST['grand_total'];

        if (empty($id)) {
            $data['created_by'] = $this->session->get('log_id');
            $data['status'] = 'draft';

            $builder = $this->db->table('inv_stock_in')->insert($data);
            $stock_in_id = $this->db->insertID();
        } else {
            $builder = $this->db->table('inv_stock_in')->where('id', $id)->update($data);
            $stock_in_id = $id;

            // Delete old details
            $this->db->table('inv_stock_in_details')->where('stock_in_id', $id)->delete();
        }

        if ($builder) {
            // Insert details
            foreach ($_POST['items'] as $item) {
                if (!empty($item['item_id']) && $item['quantity'] > 0) {
                    $detail_data = [
                        'stock_in_id' => $stock_in_id,
                        'item_id' => $item['item_id'],
                        'quantity' => $item['quantity'],
                        'rate' => $item['rate'],
                        'amount' => $item['amount'],
                        'batch_no' => trim($item['batch_no']),
                        'expiry_date' => !empty($item['expiry_date']) ? $item['expiry_date'] : NULL,
                        'remarks' => trim($item['remarks'])
                    ];

                    $this->db->table('inv_stock_in_details')->insert($detail_data);
                }
            }

            $msg_data['succ'] = empty($id) ? 'Stock In Added Successfully' : 'Stock In Updated Successfully';
            $msg_data['id'] = $stock_in_id;
        } else {
            $msg_data['err'] = 'Please Try Again';
        }

        echo json_encode($msg_data);
        exit;
    }

    public function approve()
    {
        $id = $this->request->uri->getSegment(3);

        // Get stock in data
        $stock_in = $this->db->table('inv_stock_in')->where('id', $id)->get()->getRowArray();

        if ($stock_in['status'] == 'approved') {
            $this->session->setFlashdata('fail', 'Stock In already approved');
            header("Location: " . base_url() . "/invstockin/view/" . $id);
            exit;
        }

        // Get details
        $details = $this->db->table('inv_stock_in_details')->where('stock_in_id', $id)->get()->getResultArray();

        // Update stock in status
        $this->db->table('inv_stock_in')->where('id', $id)->update([
            'status' => 'approved',
            'approved_by' => $this->session->get('log_id'),
            'approved_date' => date('Y-m-d H:i:s')
        ]);

        // Update stock quantities and create movements
        foreach ($details as $detail) {
            // Check if stock exists for this location
            $stock = $this->db->table('inv_item_stock')
                ->where('item_id', $detail['item_id'])
                ->where('location_id', $stock_in['location_id'])
                ->get()->getRowArray();

            if (!empty($stock)) {
                // Update existing stock
                $new_qty = $stock['quantity'] + $detail['quantity'];
                $this->db->table('inv_item_stock')
                    ->where('id', $stock['id'])
                    ->update([
                        'quantity' => $new_qty,
                        'available_qty' => $stock['available_qty'] + $detail['quantity']
                    ]);
            } else {
                // Create new stock entry
                $this->db->table('inv_item_stock')->insert([
                    'item_id' => $detail['item_id'],
                    'location_id' => $stock_in['location_id'],
                    'quantity' => $detail['quantity'],
                    'available_qty' => $detail['quantity'],
                    'reserved_qty' => 0
                ]);
            }

            // Get updated stock for balance
            $updated_stock = $this->db->table('inv_item_stock')
                ->where('item_id', $detail['item_id'])
                ->where('location_id', $stock_in['location_id'])
                ->get()->getRowArray();

            // Create stock movement log
            $this->db->table('inv_stock_movement')->insert([
                'item_id' => $detail['item_id'],
                'location_id' => $stock_in['location_id'],
                'doc_type' => 'stock_in',
                'doc_no' => $stock_in['doc_no'],
                'doc_date' => $stock_in['doc_date'],
                'movement_type' => 'in',
                'quantity' => $detail['quantity'],
                'balance_qty' => $updated_stock['quantity'],
                'rate' => $detail['rate'],
                'amount' => $detail['amount'],
                'remarks' => 'Stock In - ' . $stock_in['doc_no'],
                'created_by' => $this->session->get('log_id')
            ]);

            // Update item average cost
            $this->updateItemAverageCost($detail['item_id']);
        }

        $this->session->setFlashdata('succ', 'Stock In Approved Successfully');
        header("Location: " . base_url() . "/invstockin/view/" . $id);
    }

    private function updateItemAverageCost($item_id)
    {
        // Calculate weighted average cost
        $result = $this->db->query("
            SELECT SUM(quantity * rate) / SUM(quantity) as avg_cost
            FROM inv_stock_in_details
            WHERE item_id = ?
            AND stock_in_id IN (SELECT id FROM inv_stock_in WHERE status = 'approved')
        ", [$item_id])->getRowArray();

        if (!empty($result['avg_cost'])) {
            $this->db->table('inv_items')->where('id', $item_id)->update([
                'avg_cost' => $result['avg_cost']
            ]);
        }
    }

    public function delete()
    {
        $id = $this->request->uri->getSegment(3);

        // Check if approved
        $check = $this->db->table('inv_stock_in')->where('id', $id)->get()->getRowArray();
        if ($check['status'] == 'approved') {
            $this->session->setFlashdata('fail', 'Cannot delete approved stock in');
        } else {
            // Delete details
            $this->db->table('inv_stock_in_details')->where('stock_in_id', $id)->delete();

            // Delete master
            $res = $this->db->table('inv_stock_in')->delete(['id' => $id]);

            if ($res) {
                $this->session->setFlashdata('succ', 'Stock In Deleted Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Please Try Again');
            }
        }

        header("Location: " . base_url() . "/invstockin");
    }

    public function print()
    {
        $id = $this->request->uri->getSegment(3);

        $data['data'] = $this->db->query("
            SELECT si.*, l.name as location_name, l.address as location_address,
                   s.name as supplier_name, s.phone as supplier_phone,
                   s.address1, s.city, s.state, s.postal_code,
                   st.name as created_by_name, st2.name as approved_by_name
            FROM inv_stock_in si
            LEFT JOIN inv_locations l ON si.location_id = l.id
            LEFT JOIN inv_suppliers s ON si.supplier_id = s.id
            LEFT JOIN staff st ON si.created_by = st.id
            LEFT JOIN staff st2 ON si.approved_by = st2.id
            WHERE si.id = ?
        ", [$id])->getRowArray();

        $data['details'] = $this->db->query("
            SELECT d.*, i.name as item_name, i.item_code, i.name_tamil, u.symbol as uom_name
            FROM inv_stock_in_details d
            JOIN inv_items i ON d.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            WHERE d.stock_in_id = ?
            ORDER BY d.id ASC
        ", [$id])->getResultArray();

        $data['temple'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

        echo view('inventory/stock_in/print', $data);
    }

    public function get_item_details()
    {
        $item_id = $_POST['item_id'];

        $item = $this->db->query("
            SELECT i.*, u.symbol as uom_name, i.last_purchase_rate as rate
            FROM inv_items i
            LEFT JOIN uom_list u ON i.uom_id = u.id
            WHERE i.id = ?
        ", [$item_id])->getRowArray();

        echo json_encode($item);
    }
}