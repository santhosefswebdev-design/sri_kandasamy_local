<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Invlocation extends BaseController
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
        $data['list'] = $this->db->table('inv_locations l')
            ->select('l.*, s.name as staff_name')
            ->join('staff s', 's.id = l.incharge_staff_id', 'left')
            ->orderBy('l.name', 'ASC')
            ->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/location/list', $data);
        echo view('template/footer');
    }

    public function add()
    {
        $data['staff'] = $this->db->table('staff')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/location/form', $data);
        echo view('template/footer');
    }

    public function edit()
    {
        $id = $this->request->uri->getSegment(3);
        $data['data'] = $this->db->table('inv_locations')->where('id', $id)->get()->getRowArray();
        $data['staff'] = $this->db->table('staff')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/location/form', $data);
        echo view('template/footer');
    }

    public function view()
    {
        $id = $this->request->uri->getSegment(3);
        $data['data'] = $this->db->table('inv_locations')->where('id', $id)->get()->getRowArray();
        $data['staff'] = $this->db->table('staff')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();
        $data['view'] = true;

        // Get stock summary for this location
        $data['stock_summary'] = $this->db->query("
            SELECT COUNT(DISTINCT item_id) as item_count,
                   SUM(quantity) as total_qty,
                   SUM(available_qty) as available_qty,
                   SUM(reserved_qty) as reserved_qty
            FROM inv_item_stock
            WHERE location_id = ?
        ", [$id])->getRowArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/location/form', $data);
        echo view('template/footer');
    }

    public function save()
    {
        $id = $_POST['id'];
        $data['name'] = trim($_POST['name']);
        $data['name_tamil'] = trim($_POST['name_tamil']);
        $data['type'] = $_POST['type'];
        $data['address'] = trim($_POST['address']);
        $data['incharge_staff_id'] = !empty($_POST['incharge_staff_id']) ? $_POST['incharge_staff_id'] : NULL;
        $data['status'] = isset($_POST['status']) ? 1 : 0;

        if (empty($id)) {
            $builder = $this->db->table('inv_locations')->insert($data);
            if ($builder) {
                $this->session->setFlashdata('succ', 'Location Added Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Please Try Again');
            }
        } else {
            $builder = $this->db->table('inv_locations')->where('id', $id)->update($data);
            if ($builder) {
                $this->session->setFlashdata('succ', 'Location Updated Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Please Try Again');
            }
        }
        header("Location: " . base_url() . "/invlocation");
    }

    public function delete()
    {
        $id = $this->request->uri->getSegment(3);

        // Check if location has stock
        $stock_count = $this->db->table('inv_item_stock')->where('location_id', $id)->countAllResults();
        if ($stock_count > 0) {
            $this->session->setFlashdata('fail', 'Cannot delete location. Stock exists in this location.');
        } else {
            $res = $this->db->table('inv_locations')->delete(['id' => $id]);
            if ($res) {
                $this->session->setFlashdata('succ', 'Location Deleted Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Please Try Again');
            }
        }
        header("Location: " . base_url() . "/invlocation");
    }

    public function stock_details()
    {
        $id = $this->request->uri->getSegment(3);
        $data['location'] = $this->db->table('inv_locations')->where('id', $id)->get()->getRowArray();

        $data['items'] = $this->db->query("
            SELECT s.*, i.name, i.item_code, i.name_tamil, u.symbol as uom_name,
                   c.name as category_name
            FROM inv_item_stock s
            JOIN inv_items i ON s.item_id = i.id
            LEFT JOIN uom_list u ON i.uom_id = u.id
            LEFT JOIN inv_categories c ON i.category_id = c.id
            WHERE s.location_id = ?
            ORDER BY i.name ASC
        ", [$id])->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/location/stock_details', $data);
        echo view('template/footer');
    }
}