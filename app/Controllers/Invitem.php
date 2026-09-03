<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Invitem extends BaseController
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
            SELECT i.*, c.name as category_name, u.symbol as uom_name,
                   COALESCE(SUM(s.quantity), 0) as total_stock,
                   COALESCE(SUM(s.available_qty), 0) as available_stock
            FROM inv_items i
            LEFT JOIN inv_categories c ON i.category_id = c.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            LEFT JOIN inv_item_stock s ON i.id = s.item_id
            GROUP BY i.id
            ORDER BY i.name ASC
        ")->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/item/list', $data);
        echo view('template/footer');
    }

    public function add()
    {
        $data['categories'] = $this->db->table('inv_categories')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();
        $data['uom'] = $this->db->table('uom_list')->orderBy('name', 'ASC')->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/item/form', $data);
        echo view('template/footer');
    }

    public function edit()
    {
        $id = $this->request->uri->getSegment(3);
        $data['data'] = $this->db->table('inv_items')->where('id', $id)->get()->getRowArray();
        $data['categories'] = $this->db->table('inv_categories')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();
        $data['uom'] = $this->db->table('uom_list')->orderBy('name', 'ASC')->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/item/form', $data);
        echo view('template/footer');
    }

    public function view()
    {
        $id = $this->request->uri->getSegment(3);
        $data['data'] = $this->db->table('inv_items')->where('id', $id)->get()->getRowArray();
        $data['categories'] = $this->db->table('inv_categories')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();
        $data['uom'] = $this->db->table('uom_list')->orderBy('name', 'ASC')->get()->getResultArray();
        $data['view'] = true;

        // Get stock by location
        $data['location_stock'] = $this->db->query("
            SELECT s.*, l.name as location_name, l.type as location_type
            FROM inv_item_stock s
            JOIN inv_locations l ON s.location_id = l.id
            WHERE s.item_id = ?
            ORDER BY l.name ASC
        ", [$id])->getResultArray();

        // Get recent movements
        $data['recent_movements'] = $this->db->query("
            SELECT m.*, l.name as location_name
            FROM inv_stock_movement m
            JOIN inv_locations l ON m.location_id = l.id
            WHERE m.item_id = ?
            ORDER BY m.created DESC
            LIMIT 20
        ", [$id])->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/item/form', $data);
        echo view('template/footer');
    }

    public function save()
    {
        $id = $_POST['id'];

        // Generate item code if new
        if (empty($id)) {
            $last_code = $this->db->query("SELECT item_code FROM inv_items ORDER BY id DESC LIMIT 1")->getRowArray();
            if (!empty($last_code)) {
                $num = (int) substr($last_code['item_code'], 3) + 1;
            } else {
                $num = 1;
            }
            $data['item_code'] = 'ITM' . str_pad($num, 5, '0', STR_PAD_LEFT);
        }

        $data['name'] = trim($_POST['name']);
        $data['name_tamil'] = trim($_POST['name_tamil']);
        $data['category_id'] = $_POST['category_id'];
        $data['uom_id'] = $_POST['uom_id'];
        $data['item_type'] = $_POST['item_type'];
        $data['reorder_level'] = !empty($_POST['reorder_level']) ? $_POST['reorder_level'] : 0;
        $data['minimum_stock'] = !empty($_POST['minimum_stock']) ? $_POST['minimum_stock'] : 0;
        $data['maximum_stock'] = !empty($_POST['maximum_stock']) ? $_POST['maximum_stock'] : 0;
        $data['description'] = trim($_POST['description']);
        $data['status'] = isset($_POST['status']) ? 1 : 0;

        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            if (empty($id)) {
                $name = time() . '_' . $_FILES['image']['name'];
                $target_dir = "uploads/inventory/items/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $name);
                $data['image'] = $name;
            } else {
                $existingImage = $this->db->table('inv_items')->select('image')->where('id', $id)->get()->getRow();
                if ($existingImage && !empty($existingImage->image)) {
                    $oldImagePath = "uploads/inventory/items/" . $existingImage->image;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $name = time() . '_' . $_FILES['image']['name'];
                $target_dir = "uploads/inventory/items/";
                move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $name);
                $data['image'] = $name;
            }
        }

        if (empty($id)) {
            $data['created_by'] = $this->session->get('log_id');
            $builder = $this->db->table('inv_items')->insert($data);
            if ($builder) {
                $this->session->setFlashdata('succ', 'Item Added Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Please Try Again');
            }
        } else {
            $builder = $this->db->table('inv_items')->where('id', $id)->update($data);
            if ($builder) {
                $this->session->setFlashdata('succ', 'Item Updated Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Please Try Again');
            }
        }
        header("Location: " . base_url() . "/invitem");
    }

    public function delete()
    {
        $id = $this->request->uri->getSegment(3);

        // Check if item has stock
        $stock_count = $this->db->table('inv_item_stock')->where('item_id', $id)->where('quantity >', 0)->countAllResults();
        if ($stock_count > 0) {
            $this->session->setFlashdata('fail', 'Cannot delete item. Stock exists for this item.');
        } else {
            // Check if item has movements
            $movement_count = $this->db->table('inv_stock_movement')->where('item_id', $id)->countAllResults();
            if ($movement_count > 0) {
                $this->session->setFlashdata('fail', 'Cannot delete item. Transaction history exists for this item.');
            } else {
                $res = $this->db->table('inv_items')->delete(['id' => $id]);
                if ($res) {
                    $this->session->setFlashdata('succ', 'Item Deleted Successfully');
                } else {
                    $this->session->setFlashdata('fail', 'Please Try Again');
                }
            }
        }
        header("Location: " . base_url() . "/invitem");
    }

    // Ajax function to get item details
    public function get_item_details()
    {
        $item_id = $_POST['item_id'];
        $location_id = !empty($_POST['location_id']) ? $_POST['location_id'] : 0;

        $item = $this->db->query("
            SELECT i.*, c.name as category_name, u.symbol as uom_name, u.name as uom_full_name
            FROM inv_items i
            LEFT JOIN inv_categories c ON i.category_id = c.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            WHERE i.id = ?
        ", [$item_id])->getRowArray();

        if ($location_id > 0) {
            $stock = $this->db->table('inv_item_stock')
                ->where('item_id', $item_id)
                ->where('location_id', $location_id)
                ->get()->getRowArray();
            $item['current_stock'] = !empty($stock['available_qty']) ? $stock['available_qty'] : 0;
        } else {
            $total_stock = $this->db->query("
                SELECT COALESCE(SUM(available_qty), 0) as total
                FROM inv_item_stock
                WHERE item_id = ?
            ", [$item_id])->getRowArray();
            $item['current_stock'] = $total_stock['total'];
        }

        echo json_encode($item);
    }

    // Low stock report
    public function low_stock()
    {
        $data['list'] = $this->db->query("
            SELECT i.*, c.name as category_name, u.symbol as uom_name,
                   COALESCE(SUM(s.quantity), 0) as total_stock,
                   COALESCE(SUM(s.available_qty), 0) as available_stock,
                   (i.reorder_level - COALESCE(SUM(s.available_qty), 0)) as shortage
            FROM inv_items i
            LEFT JOIN inv_categories c ON i.category_id = c.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            LEFT JOIN inv_item_stock s ON i.id = s.item_id
            WHERE i.status = 1
            GROUP BY i.id
            HAVING available_stock <= i.reorder_level
            ORDER BY shortage DESC
        ")->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/item/low_stock', $data);
        echo view('template/footer');
    }
}