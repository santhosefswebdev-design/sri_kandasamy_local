<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Invcategory extends BaseController
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

  public function index(){
        // Fetch all categories with parent category name
        $data['list'] = $this->db->query("
            SELECT c.*, 
                   p.name as parent_name
            FROM inv_categories c
            LEFT JOIN inv_categories p ON c.parent_id = p.id
            ORDER BY c.name ASC
        ")->getResultArray();
            
        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/category/list', $data);
        echo view('template/footer');
    }
    
    public function add(){
        $data['data'] = array(); // Initialize empty data
        $data['categories'] = $this->db->table('inv_categories')
            ->where('parent_id', 0)
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();
            
        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/category/form', $data);
        echo view('template/footer');
    }
    
    public function edit(){
        $id = $this->request->uri->getSegment(3);
        $data['data'] = $this->db->table('inv_categories')->where('id', $id)->get()->getRowArray();
        $data['categories'] = $this->db->table('inv_categories')
            ->where('parent_id', 0)
            ->where('status', 1)
            ->where('id !=', $id)
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();
            
        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/category/form', $data);
        echo view('template/footer');
    }
    
    public function view(){
        $id = $this->request->uri->getSegment(3);
        $data['data'] = $this->db->table('inv_categories')->where('id', $id)->get()->getRowArray();
        $data['categories'] = $this->db->table('inv_categories')
            ->where('parent_id', 0)
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();
        $data['view'] = true;
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/category/form', $data);
        echo view('template/footer');
    }
    
    public function save(){
        $id = !empty($_POST['id']) ? $_POST['id'] : '';
        $data['name'] = trim($_POST['name']);
        $data['name_tamil'] = trim($_POST['name_tamil']);
        $data['parent_id'] = !empty($_POST['parent_id']) ? $_POST['parent_id'] : 0;
        $data['type'] = $_POST['type'];
        $data['status'] = isset($_POST['status']) ? 1 : 0;
        
        if(empty($id)){
            $builder = $this->db->table('inv_categories')->insert($data);
            if($builder){
                $this->session->setFlashdata('succ', 'Category Added Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Please Try Again');
            }
        } else {
            $builder = $this->db->table('inv_categories')->where('id', $id)->update($data);
            if($builder){
                $this->session->setFlashdata('succ', 'Category Updated Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Please Try Again');
            }
        }
        header("Location: ".base_url()."/invcategory");
    }
    
    public function delete(){
        $id = $this->request->uri->getSegment(3);
        
        // Check if category has items
        $item_count = $this->db->table('inv_items')->where('category_id', $id)->countAllResults();
        if($item_count > 0){
            $this->session->setFlashdata('fail', 'Cannot delete category. Items exist under this category.');
        } else {
            $res = $this->db->table('inv_categories')->delete(['id' => $id]);
            if($res){
                $this->session->setFlashdata('succ', 'Category Deleted Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Please Try Again');
            }
        }
        header("Location: ".base_url()."/invcategory");
    }

}