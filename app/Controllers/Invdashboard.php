<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Invdashboard extends BaseController
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
        // Get low stock items
        $data['low_stock'] = $this->db->query("
            SELECT i.id, i.name, i.item_code, i.minimum_stock, 
                   SUM(s.available_qty) as total_stock, l.name as location_name
            FROM inv_items i
            LEFT JOIN inv_item_stock s ON i.id = s.item_id
            LEFT JOIN inv_locations l ON s.location_id = l.id
            WHERE i.status = 1
            GROUP BY i.id
            HAVING total_stock <= i.minimum_stock
            ORDER BY total_stock ASC
            LIMIT 10
        ")->getResultArray();

        // Get stock summary by location
        $data['location_stock'] = $this->db->query("
            SELECT l.name, l.type, COUNT(DISTINCT s.item_id) as item_count,
                   SUM(s.quantity) as total_qty
            FROM inv_locations l
            LEFT JOIN inv_item_stock s ON l.id = s.location_id
            WHERE l.status = 1
            GROUP BY l.id
        ")->getResultArray();

        // Recent stock movements
        $data['recent_movements'] = $this->db->query("
            SELECT m.*, i.name as item_name, i.item_code, l.name as location_name
            FROM inv_stock_movement m
            JOIN inv_items i ON m.item_id = i.id
            JOIN inv_locations l ON m.location_id = l.id
            ORDER BY m.created DESC
            LIMIT 20
        ")->getResultArray();

        // Stock value by category
        $data['category_value'] = $this->db->query("
            SELECT c.name, COUNT(i.id) as item_count,
                   SUM(s.quantity * i.avg_cost) as total_value
            FROM inv_categories c
            LEFT JOIN inv_items i ON c.id = i.category_id
            LEFT JOIN inv_item_stock s ON i.id = s.item_id
            WHERE c.status = 1
            GROUP BY c.id
        ")->getResultArray();

        // Stats
        $data['stats'] = [
            'total_items' => $this->db->table('inv_items')->where('status', 1)->countAllResults(),
            'total_suppliers' => $this->db->table('inv_suppliers')->where('status', 1)->countAllResults(),
            'total_locations' => $this->db->table('inv_locations')->where('status', 1)->countAllResults(),
            'pending_stock_in' => $this->db->table('inv_stock_in')->where('status', 'draft')->countAllResults(),
            'pending_stock_out' => $this->db->table('inv_stock_out')->where('status', 'draft')->countAllResults(),
        ];

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/dashboard/index', $data);
        echo view('template/footer');
    }
}