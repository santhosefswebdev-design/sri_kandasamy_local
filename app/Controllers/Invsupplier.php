<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class InvSupplier extends BaseController
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
        $data['list'] = $this->db->table('inv_suppliers')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/supplier/list', $data);
        echo view('template/footer');
    }

    public function add()
    {
        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/supplier/form');
        echo view('template/footer');
    }

    public function edit()
    {
        $id = $this->request->uri->getSegment(3);
        $data['data'] = $this->db->table('inv_suppliers')->where('id', $id)->get()->getRowArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/supplier/form', $data);
        echo view('template/footer');
    }

    public function view()
    {
        $id = $this->request->uri->getSegment(3);
        $data['data'] = $this->db->table('inv_suppliers')->where('id', $id)->get()->getRowArray();
        $data['view'] = true;

        // Get purchase history
        $data['purchase_history'] = $this->db->query("
            SELECT si.*, COUNT(sid.id) as item_count
            FROM inv_stock_in si
            LEFT JOIN inv_stock_in_details sid ON si.id = sid.stock_in_id
            WHERE si.supplier_id = ?
            GROUP BY si.id
            ORDER BY si.doc_date DESC
            LIMIT 20
        ", [$id])->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/supplier/form', $data);
        echo view('template/footer');
    }

    public function save()
    {
        $id = $_POST['id'];

        // Generate supplier code if new
        if (empty($id)) {
            $last_code = $this->db->query("SELECT supplier_code FROM inv_suppliers ORDER BY id DESC LIMIT 1")->getRowArray();
            if (!empty($last_code)) {
                $num = (int) substr($last_code['supplier_code'], 3) + 1;
            } else {
                $num = 1;
            }
            $data['supplier_code'] = 'SUP' . str_pad($num, 5, '0', STR_PAD_LEFT);
        }

        $data['name'] = trim($_POST['name']);
        $data['contact_person'] = trim($_POST['contact_person']);
        $data['email'] = trim($_POST['email']);
        $data['phone'] = trim($_POST['phone']);
        $data['address1'] = trim($_POST['address1']);
        $data['address2'] = trim($_POST['address2']);
        $data['city'] = trim($_POST['city']);
        $data['state'] = trim($_POST['state']);
        $data['country'] = trim($_POST['country']);
        $data['postal_code'] = trim($_POST['postal_code']);
        $data['gst_no'] = trim($_POST['gst_no']);
        $data['credit_days'] = !empty($_POST['credit_days']) ? $_POST['credit_days'] : 0;
        $data['credit_limit'] = !empty($_POST['credit_limit']) ? $_POST['credit_limit'] : 0;
        $data['remarks'] = trim($_POST['remarks']);
        $data['status'] = isset($_POST['status']) ? 1 : 0;

        if (empty($id)) {
            $builder = $this->db->table('inv_suppliers')->insert($data);
            if ($builder) {
                $this->session->setFlashdata('succ', 'Supplier Added Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Please Try Again');
            }
        } else {
            $builder = $this->db->table('inv_suppliers')->where('id', $id)->update($data);
            if ($builder) {
                $this->session->setFlashdata('succ', 'Supplier Updated Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Please Try Again');
            }
        }
        header("Location: " . base_url() . "/invsupplier");
    }

    public function delete()
    {
        $id = $this->request->uri->getSegment(3);

        // Check if supplier has stock in entries
        $stock_count = $this->db->table('inv_stock_in')->where('supplier_id', $id)->countAllResults();
        if ($stock_count > 0) {
            $this->session->setFlashdata('fail', 'Cannot delete supplier. Purchase records exist for this supplier.');
        } else {
            $res = $this->db->table('inv_suppliers')->delete(['id' => $id]);
            if ($res) {
                $this->session->setFlashdata('succ', 'Supplier Deleted Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Please Try Again');
            }
        }
        header("Location: " . base_url() . "/invsupplier");
    }
}