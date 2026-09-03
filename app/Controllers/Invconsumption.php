<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Invconsumption extends BaseController
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
            SELECT c.*, l.name as location_name, s.name as created_by_name
            FROM inv_consumption c
            LEFT JOIN inv_locations l ON c.location_id = l.id
            LEFT JOIN staff s ON c.created_by = s.id
            ORDER BY c.doc_date DESC, c.id DESC
        ")->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/consumption/list', $data);
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
            WHERE i.status = 1 AND i.item_type = 'consumable'
            ORDER BY i.name ASC
        ")->getResultArray();

        // Generate doc number
        $yr = date('Y');
        $mon = date('m');
        $query = $this->db->query("
            SELECT doc_no FROM inv_consumption 
            WHERE id = (SELECT MAX(id) FROM inv_consumption 
            WHERE YEAR(doc_date) = ? AND MONTH(doc_date) = ?)
        ", [$yr, $mon])->getRowArray();

        $data['doc_no'] = 'CON' . date('y') . $mon . sprintf("%05d", (((float) substr($query['doc_no'], -5)) + 1));

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/consumption/form', $data);
        echo view('template/footer');
    }

    public function edit()
    {
        $id = $this->request->uri->getSegment(3);
        $data['data'] = $this->db->table('inv_consumption')->where('id', $id)->get()->getRowArray();

        if ($data['data']['status'] == 'approved') {
            $this->session->setFlashdata('fail', 'Cannot edit approved consumption entry');
            header("Location: " . base_url() . "/invconsumption");
            exit;
        }

        $data['details'] = $this->db->query("
            SELECT cd.*, i.name as item_name, i.item_code, u.symbol as uom_name
            FROM inv_consumption_details cd
            JOIN inv_items i ON cd.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            WHERE cd.consumption_id = ?
        ", [$id])->getResultArray();

        $data['locations'] = $this->db->table('inv_locations')
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        $data['items'] = $this->db->query("
            SELECT i.*, c.name as category_name, u.symbol as uom_name
            FROM inv_items i
            LEFT JOIN inv_categories c ON i.category_id = c.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            WHERE i.status = 1 AND i.item_type = 'consumable'
            ORDER BY i.name ASC
        ")->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/consumption/form', $data);
        echo view('template/footer');
    }

    public function view()
    {
        $id = $this->request->uri->getSegment(3);
        $data['data'] = $this->db->query("
            SELECT c.*, l.name as location_name, s.name as created_by_name
            FROM inv_consumption c
            LEFT JOIN inv_locations l ON c.location_id = l.id
            LEFT JOIN staff s ON c.created_by = s.id
            WHERE c.id = ?
        ", [$id])->getRowArray();

        $data['details'] = $this->db->query("
            SELECT cd.*, i.name as item_name, i.item_code, u.symbol as uom_name,
                   c.name as category_name
            FROM inv_consumption_details cd
            JOIN inv_items i ON cd.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            LEFT JOIN inv_categories c ON i.category_id = c.id
            WHERE cd.consumption_id = ?
            ORDER BY i.name
        ", [$id])->getResultArray();

        $data['view'] = true;

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/consumption/form', $data);
        echo view('template/footer');
    }

    public function save()
    {
        $id = $_POST['id'];
        $msg_data = array();
        $msg_data['err'] = '';
        $msg_data['succ'] = '';

        // Validate
        if (empty($_POST['doc_date']) || empty($_POST['location_id']) || empty($_POST['consumption_type'])) {
            $msg_data['err'] = 'Please fill all required fields';
            echo json_encode($msg_data);
            exit;
        }

        if (empty($_POST['items'])) {
            $msg_data['err'] = 'Please add at least one item';
            echo json_encode($msg_data);
            exit;
        }

        // Generate doc number
        $date = explode('-', $_POST['doc_date']);
        $yr = $date[0];
        $mon = $date[1];
        $query = $this->db->query("
            SELECT doc_no FROM inv_consumption 
            WHERE id = (SELECT MAX(id) FROM inv_consumption 
            WHERE YEAR(doc_date) = ? AND MONTH(doc_date) = ?)
        ", [$yr, $mon])->getRowArray();

        $data['doc_no'] = 'CON' . date('y', strtotime($_POST['doc_date'])) . $mon . sprintf("%05d", (((float) substr($query['doc_no'], -5)) + 1));
        $data['doc_date'] = $_POST['doc_date'];
        $data['consumption_type'] = $_POST['consumption_type'];
        $data['location_id'] = $_POST['location_id'];
        $data['reference_no'] = trim($_POST['reference_no']);
        $data['remarks'] = trim($_POST['remarks']);
        $data['created_by'] = $this->session->get('log_id');

        if (empty($id)) {
            // Insert consumption master
            $res = $this->db->table('inv_consumption')->insert($data);
            $cons_id = $this->db->insertID();

            if ($res) {
                // Insert details
                foreach ($_POST['items'] as $item) {
                    $detail['consumption_id'] = $cons_id;
                    $detail['item_id'] = $item['item_id'];
                    $detail['quantity'] = $item['quantity'];
                    $detail['remarks'] = $item['remarks'];
                    $this->db->table('inv_consumption_details')->insert($detail);
                }

                $msg_data['succ'] = 'Consumption Entry Added Successfully';
                $msg_data['id'] = $cons_id;
            } else {
                $msg_data['err'] = 'Please Try Again';
            }
        } else {
            // Update consumption master
            $res = $this->db->table('inv_consumption')->where('id', $id)->update($data);

            if ($res) {
                // Delete old details
                $this->db->table('inv_consumption_details')->where('consumption_id', $id)->delete();

                // Insert new details
                foreach ($_POST['items'] as $item) {
                    $detail['consumption_id'] = $id;
                    $detail['item_id'] = $item['item_id'];
                    $detail['quantity'] = $item['quantity'];
                    $detail['remarks'] = $item['remarks'];
                    $this->db->table('inv_consumption_details')->insert($detail);
                }

                $msg_data['succ'] = 'Consumption Entry Updated Successfully';
                $msg_data['id'] = $id;
            } else {
                $msg_data['err'] = 'Please Try Again';
            }
        }

        echo json_encode($msg_data);
        exit;
    }

    public function approve()
    {
        $id = $this->request->uri->getSegment(3);

        // Get consumption details
        $consumption = $this->db->table('inv_consumption')->where('id', $id)->get()->getRowArray();

        if ($consumption['status'] == 'approved') {
            $this->session->setFlashdata('fail', 'Consumption entry already approved');
            header("Location: " . base_url() . "/invconsumption/view/" . $id);
            exit;
        }

        // Get consumption items
        $items = $this->db->table('inv_consumption_details')->where('consumption_id', $id)->get()->getResultArray();

        // Check stock availability
        $stock_error = false;
        $error_items = array();

        foreach ($items as $item) {
            $stock = $this->db->query("
                SELECT SUM(available_qty) as total_stock
                FROM inv_item_stock
                WHERE item_id = ? AND location_id = ?
            ", [$item['item_id'], $consumption['location_id']])->getRowArray();

            if ($stock['total_stock'] < $item['quantity']) {
                $stock_error = true;
                $item_details = $this->db->table('inv_items')->where('id', $item['item_id'])->get()->getRowArray();
                $error_items[] = $item_details['name'] . ' (Available: ' . $stock['total_stock'] . ', Required: ' . $item['quantity'] . ')';
            }
        }

        if ($stock_error) {
            $this->session->setFlashdata('fail', 'Insufficient stock for: ' . implode(', ', $error_items));
            header("Location: " . base_url() . "/invconsumption/view/" . $id);
            exit;
        }

        // Create stock out entry
        $date = explode('-', $consumption['doc_date']);
        $yr = $date[0];
        $mon = $date[1];
        $query = $this->db->query("
            SELECT doc_no FROM inv_stock_out 
            WHERE id = (SELECT MAX(id) FROM inv_stock_out 
            WHERE YEAR(doc_date) = ? AND MONTH(doc_date) = ?)
        ", [$yr, $mon])->getRowArray();

        $stock_out['doc_no'] = 'OUT' . date('y', strtotime($consumption['doc_date'])) . $mon . sprintf("%05d", (((float) substr($query['doc_no'], -5)) + 1));
        $stock_out['doc_date'] = $consumption['doc_date'];
        $stock_out['location_id'] = $consumption['location_id'];
        $stock_out['out_type'] = 'consumption';
        $stock_out['department'] = $consumption['consumption_type'];
        $stock_out['reference_no'] = $consumption['doc_no'];
        $stock_out['reference_module'] = 'consumption';
        $stock_out['reference_id'] = $id;
        $stock_out['remarks'] = 'Auto generated from Consumption Entry: ' . $consumption['doc_no'];
        $stock_out['status'] = 'approved';
        $stock_out['created_by'] = $this->session->get('log_id');
        $stock_out['approved_by'] = $this->session->get('log_id');
        $stock_out['approved_date'] = date('Y-m-d H:i:s');

        $this->db->table('inv_stock_out')->insert($stock_out);
        $stock_out_id = $this->db->insertID();

        $total_amount = 0;

        // Process each item
        foreach ($items as $item) {
            $item_data = $this->db->table('inv_items')->where('id', $item['item_id'])->get()->getRowArray();

            // Insert stock out details
            $out_detail['stock_out_id'] = $stock_out_id;
            $out_detail['item_id'] = $item['item_id'];
            $out_detail['quantity'] = $item['quantity'];
            $out_detail['rate'] = $item_data['avg_cost'];
            $out_detail['amount'] = $item['quantity'] * $item_data['avg_cost'];
            $out_detail['remarks'] = $item['remarks'];

            $this->db->table('inv_stock_out_details')->insert($out_detail);

            $total_amount += $out_detail['amount'];

            // Update stock
            $current_stock = $this->db->query("
                SELECT * FROM inv_item_stock
                WHERE item_id = ? AND location_id = ?
            ", [$item['item_id'], $consumption['location_id']])->getRowArray();

            if (!empty($current_stock)) {
                $new_qty = $current_stock['quantity'] - $item['quantity'];
                $new_available = $current_stock['available_qty'] - $item['quantity'];

                $this->db->table('inv_item_stock')
                    ->where('id', $current_stock['id'])
                    ->update([
                        'quantity' => $new_qty,
                        'available_qty' => $new_available
                    ]);

                $balance_qty = $new_qty;
            } else {
                $balance_qty = 0;
            }

            // Insert stock movement
            $movement['item_id'] = $item['item_id'];
            $movement['location_id'] = $consumption['location_id'];
            $movement['doc_type'] = 'consumption';
            $movement['doc_no'] = $consumption['doc_no'];
            $movement['doc_date'] = $consumption['doc_date'];
            $movement['movement_type'] = 'out';
            $movement['quantity'] = $item['quantity'];
            $movement['balance_qty'] = $balance_qty;
            $movement['rate'] = $item_data['avg_cost'];
            $movement['amount'] = $item['quantity'] * $item_data['avg_cost'];
            $movement['reference_module'] = 'consumption';
            $movement['reference_id'] = $id;
            $movement['remarks'] = 'Consumption: ' . $consumption['consumption_type'];
            $movement['created_by'] = $this->session->get('log_id');

            $this->db->table('inv_stock_movement')->insert($movement);
        }

        // Update stock out total
        $this->db->table('inv_stock_out')->where('id', $stock_out_id)->update(['total_amount' => $total_amount]);

        // Update consumption status
        $this->db->table('inv_consumption')->where('id', $id)->update(['status' => 'approved']);

        $this->session->setFlashdata('succ', 'Consumption Entry Approved Successfully');
        header("Location: " . base_url() . "/invconsumption/view/" . $id);
    }

    public function delete()
    {
        $id = $this->request->uri->getSegment(3);

        $consumption = $this->db->table('inv_consumption')->where('id', $id)->get()->getRowArray();

        if ($consumption['status'] == 'approved') {
            $this->session->setFlashdata('fail', 'Cannot delete approved consumption entry');
        } else {
            $this->db->table('inv_consumption_details')->where('consumption_id', $id)->delete();
            $res = $this->db->table('inv_consumption')->delete(['id' => $id]);

            if ($res) {
                $this->session->setFlashdata('succ', 'Consumption Entry Deleted Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Please Try Again');
            }
        }

        header("Location: " . base_url() . "/invconsumption");
    }

    public function get_item_stock()
    {
        $item_id = $_POST['item_id'];
        $location_id = $_POST['location_id'];

        $stock = $this->db->query("
            SELECT s.*, i.name, i.item_code, u.symbol as uom_name
            FROM inv_item_stock s
            JOIN inv_items i ON s.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            WHERE s.item_id = ? AND s.location_id = ?
        ", [$item_id, $location_id])->getRowArray();

        echo json_encode($stock);
    }

    public function print_consumption()
    {
        $id = $this->request->uri->getSegment(3);

        $data['data'] = $this->db->query("
            SELECT c.*, l.name as location_name, l.address as location_address,
                   s.name as created_by_name
            FROM inv_consumption c
            LEFT JOIN inv_locations l ON c.location_id = l.id
            LEFT JOIN staff s ON c.created_by = s.id
            WHERE c.id = ?
        ", [$id])->getRowArray();

        $data['details'] = $this->db->query("
            SELECT cd.*, i.name as item_name, i.item_code, u.symbol as uom_name,
                   c.name as category_name
            FROM inv_consumption_details cd
            JOIN inv_items i ON cd.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            LEFT JOIN inv_categories c ON i.category_id = c.id
            WHERE cd.consumption_id = ?
            ORDER BY i.name
        ", [$id])->getResultArray();

        $data['temple'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

        echo view('inventory/consumption/print', $data);
    }
}