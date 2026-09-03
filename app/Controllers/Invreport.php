<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Invreport extends BaseController
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
        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/report/index');
        echo view('template/footer');
    }

    // Stock Summary Report
    public function stock_summary()
    {
        $data['categories'] = $this->db->table('inv_categories')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();
        $data['locations'] = $this->db->table('inv_locations')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/report/stock_summary', $data);
        echo view('template/footer');
    }

    public function stock_summary_data()
    {
        $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : '';
        $location_id = !empty($_POST['location_id']) ? $_POST['location_id'] : '';
        $item_type = !empty($_POST['item_type']) ? $_POST['item_type'] : '';

        $sql = "SELECT i.item_code, i.name, i.name_tamil, c.name as category_name,
                       u.symbol as uom_name, i.minimum_stock, i.reorder_level,
                       SUM(s.quantity) as total_qty,
                       SUM(s.available_qty) as available_qty,
                       SUM(s.reserved_qty) as reserved_qty,
                       i.avg_cost,
                       SUM(s.quantity * i.avg_cost) as stock_value
                FROM inv_items i
                LEFT JOIN inv_item_stock s ON i.id = s.item_id
                LEFT JOIN inv_categories c ON i.category_id = c.id
                LEFT JOIN uom_list u ON i.uom_id = u.id
                WHERE i.status = 1";

        $params = [];

        if (!empty($category_id)) {
            $sql .= " AND i.category_id = ?";
            $params[] = $category_id;
        }

        if (!empty($location_id)) {
            $sql .= " AND s.location_id = ?";
            $params[] = $location_id;
        }

        if (!empty($item_type)) {
            $sql .= " AND i.item_type = ?";
            $params[] = $item_type;
        }

        $sql .= " GROUP BY i.id ORDER BY i.name ASC";

        $data = $this->db->query($sql, $params)->getResultArray();

        $result = [];
        $i = 1;
        $total_value = 0;

        foreach ($data as $row) {
            $status = '';
            if ($row['total_qty'] <= 0) {
                $status = '<span class="label bg-red">Out of Stock</span>';
            } elseif ($row['total_qty'] <= $row['minimum_stock']) {
                $status = '<span class="label bg-orange">Low Stock</span>';
            } elseif ($row['total_qty'] <= $row['reorder_level']) {
                $status = '<span class="label bg-yellow">Reorder</span>';
            } else {
                $status = '<span class="label bg-green">In Stock</span>';
            }

            $result[] = [
                $i++,
                $row['item_code'],
                $row['name'] . (!empty($row['name_tamil']) ? '<br><small>' . $row['name_tamil'] . '</small>' : ''),
                $row['category_name'],
                $row['uom_name'],
                number_format($row['total_qty'], 2),
                number_format($row['available_qty'], 2),
                number_format($row['reserved_qty'], 2),
                number_format($row['avg_cost'], 2),
                number_format($row['stock_value'], 2),
                $status
            ];

            $total_value += $row['stock_value'];
        }

        echo json_encode([
            'data' => $result,
            'total_value' => number_format($total_value, 2)
        ]);
    }

    // Stock Movement Report
    public function stock_movement()
    {
        $data['items'] = $this->db->table('inv_items')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();
        $data['locations'] = $this->db->table('inv_locations')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/report/stock_movement', $data);
        echo view('template/footer');
    }

    public function stock_movement_data()
    {
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $item_id = !empty($_POST['item_id']) ? $_POST['item_id'] : '';
        $location_id = !empty($_POST['location_id']) ? $_POST['location_id'] : '';
        $movement_type = !empty($_POST['movement_type']) ? $_POST['movement_type'] : '';

        $sql = "SELECT m.*, i.name as item_name, i.item_code, l.name as location_name,
                       u.symbol as uom_name
                FROM inv_stock_movement m
                JOIN inv_items i ON m.item_id = i.id
                JOIN inv_locations l ON m.location_id = l.id
                LEFT JOIN uom_list u ON i.uom_id = u.id
                WHERE m.doc_date BETWEEN ? AND ?";

        $params = [$from_date, $to_date];

        if (!empty($item_id)) {
            $sql .= " AND m.item_id = ?";
            $params[] = $item_id;
        }

        if (!empty($location_id)) {
            $sql .= " AND m.location_id = ?";
            $params[] = $location_id;
        }

        if (!empty($movement_type)) {
            $sql .= " AND m.movement_type = ?";
            $params[] = $movement_type;
        }

        $sql .= " ORDER BY m.doc_date DESC, m.id DESC";

        $data = $this->db->query($sql, $params)->getResultArray();

        $result = [];
        $i = 1;
        $total_in = 0;
        $total_out = 0;

        foreach ($data as $row) {
            $type_badge = $row['movement_type'] == 'in'
                ? '<span class="label bg-green">IN</span>'
                : '<span class="label bg-red">OUT</span>';

            $result[] = [
                $i++,
                date('d-M-Y', strtotime($row['doc_date'])),
                $row['doc_no'],
                $row['doc_type'],
                $row['item_code'],
                $row['item_name'],
                $row['location_name'],
                $type_badge,
                number_format($row['quantity'], 2) . ' ' . $row['uom_name'],
                number_format($row['rate'], 2),
                number_format($row['amount'], 2),
                number_format($row['balance_qty'], 2),
                !empty($row['remarks']) ? $row['remarks'] : '-'
            ];

            if ($row['movement_type'] == 'in') {
                $total_in += $row['quantity'];
            } else {
                $total_out += $row['quantity'];
            }
        }

        echo json_encode([
            'data' => $result,
            'total_in' => number_format($total_in, 2),
            'total_out' => number_format($total_out, 2)
        ]);
    }

    // Consumption Report
    public function consumption_report()
    {
        $data['locations'] = $this->db->table('inv_locations')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/report/consumption_report', $data);
        echo view('template/footer');
    }

    public function consumption_report_data()
    {
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $consumption_type = !empty($_POST['consumption_type']) ? $_POST['consumption_type'] : '';
        $location_id = !empty($_POST['location_id']) ? $_POST['location_id'] : '';

        $sql = "SELECT c.doc_no, c.doc_date, c.consumption_type, 
                       l.name as location_name, cd.item_id,
                       i.name as item_name, i.item_code,
                       u.symbol as uom_name,
                       cd.quantity, cd.remarks
                FROM inv_consumption c
                JOIN inv_consumption_details cd ON c.id = cd.consumption_id
                JOIN inv_items i ON cd.item_id = i.id
                JOIN inv_locations l ON c.location_id = l.id
                LEFT JOIN uom_list u ON i.uom_id = u.id
                WHERE c.doc_date BETWEEN ? AND ?
                AND c.status = 'approved'";

        $params = [$from_date, $to_date];

        if (!empty($consumption_type)) {
            $sql .= " AND c.consumption_type = ?";
            $params[] = $consumption_type;
        }

        if (!empty($location_id)) {
            $sql .= " AND c.location_id = ?";
            $params[] = $location_id;
        }

        $sql .= " ORDER BY c.doc_date DESC";

        $data = $this->db->query($sql, $params)->getResultArray();

        $result = [];
        $i = 1;
        $total_qty = 0;

        foreach ($data as $row) {
            $result[] = [
                $i++,
                date('d-M-Y', strtotime($row['doc_date'])),
                $row['doc_no'],
                '<span class="label bg-blue">' . strtoupper($row['consumption_type']) . '</span>',
                $row['location_name'],
                $row['item_code'],
                $row['item_name'],
                number_format($row['quantity'], 2) . ' ' . $row['uom_name'],
                !empty($row['remarks']) ? $row['remarks'] : '-'
            ];

            $total_qty += $row['quantity'];
        }

        echo json_encode([
            'data' => $result,
            'total_qty' => number_format($total_qty, 2)
        ]);
    }

    // Low Stock Alert
    public function low_stock_alert()
    {
        $data['categories'] = $this->db->table('inv_categories')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/report/low_stock_alert', $data);
        echo view('template/footer');
    }

    public function low_stock_alert_data()
    {
        $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : '';
        $alert_type = !empty($_POST['alert_type']) ? $_POST['alert_type'] : 'all';

        $sql = "SELECT i.item_code, i.name, i.name_tamil, c.name as category_name,
                       u.symbol as uom_name, i.minimum_stock, i.reorder_level,
                       SUM(s.quantity) as total_qty,
                       l.name as location_name, s.location_id
                FROM inv_items i
                LEFT JOIN inv_item_stock s ON i.id = s.item_id
                LEFT JOIN inv_categories c ON i.category_id = c.id
                LEFT JOIN uom_list u ON i.uom_id = u.id
                LEFT JOIN inv_locations l ON s.location_id = l.id
                WHERE i.status = 1";

        $params = [];

        if (!empty($category_id)) {
            $sql .= " AND i.category_id = ?";
            $params[] = $category_id;
        }

        $sql .= " GROUP BY i.id, s.location_id";

        if ($alert_type == 'out_of_stock') {
            $sql .= " HAVING total_qty <= 0";
        } elseif ($alert_type == 'low_stock') {
            $sql .= " HAVING total_qty > 0 AND total_qty <= i.minimum_stock";
        } elseif ($alert_type == 'reorder') {
            $sql .= " HAVING total_qty > i.minimum_stock AND total_qty <= i.reorder_level";
        } else {
            $sql .= " HAVING total_qty <= i.reorder_level";
        }

        $sql .= " ORDER BY total_qty ASC";

        $data = $this->db->query($sql, $params)->getResultArray();

        $result = [];
        $i = 1;

        foreach ($data as $row) {
            $status = '';
            $action = '';

            if ($row['total_qty'] <= 0) {
                $status = '<span class="label bg-red">Out of Stock</span>';
                $action = '<button class="btn btn-sm btn-danger" onclick="reorder_item(' . $row['location_id'] . ')">Urgent Reorder</button>';
            } elseif ($row['total_qty'] <= $row['minimum_stock']) {
                $status = '<span class="label bg-orange">Low Stock</span>';
                $action = '<button class="btn btn-sm btn-warning" onclick="reorder_item(' . $row['location_id'] . ')">Reorder</button>';
            } else {
                $status = '<span class="label bg-yellow">Reorder Level</span>';
                $action = '<button class="btn btn-sm btn-info" onclick="reorder_item(' . $row['location_id'] . ')">Plan Reorder</button>';
            }

            $result[] = [
                $i++,
                $row['item_code'],
                $row['name'] . (!empty($row['name_tamil']) ? '<br><small>' . $row['name_tamil'] . '</small>' : ''),
                $row['category_name'],
                $row['location_name'],
                number_format($row['total_qty'], 2) . ' ' . $row['uom_name'],
                number_format($row['minimum_stock'], 2),
                number_format($row['reorder_level'], 2),
                $status,
                $action
            ];
        }

        echo json_encode(['data' => $result]);
    }

    // Stock Valuation Report
    public function stock_valuation()
    {
        $data['categories'] = $this->db->table('inv_categories')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();
        $data['locations'] = $this->db->table('inv_locations')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/report/stock_valuation', $data);
        echo view('template/footer');
    }

    public function stock_valuation_data()
    {
        $as_on_date = $_POST['as_on_date'];
        $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : '';
        $location_id = !empty($_POST['location_id']) ? $_POST['location_id'] : '';

        $sql = "SELECT i.item_code, i.name, c.name as category_name,
                       u.symbol as uom_name, l.name as location_name,
                       s.quantity, i.avg_cost, i.last_purchase_rate,
                       (s.quantity * i.avg_cost) as avg_value,
                       (s.quantity * i.last_purchase_rate) as last_purchase_value
                FROM inv_items i
                JOIN inv_item_stock s ON i.id = s.item_id
                JOIN inv_categories c ON i.category_id = c.id
                LEFT JOIN uom_list u ON i.uom_id = u.id
                JOIN inv_locations l ON s.location_id = l.id
                WHERE i.status = 1 AND s.quantity > 0";

        $params = [];

        if (!empty($category_id)) {
            $sql .= " AND i.category_id = ?";
            $params[] = $category_id;
        }

        if (!empty($location_id)) {
            $sql .= " AND s.location_id = ?";
            $params[] = $location_id;
        }

        $sql .= " ORDER BY c.name, i.name";

        $data = $this->db->query($sql, $params)->getResultArray();

        $result = [];
        $i = 1;
        $total_avg_value = 0;
        $total_last_value = 0;

        foreach ($data as $row) {
            $result[] = [
                $i++,
                $row['item_code'],
                $row['name'],
                $row['category_name'],
                $row['location_name'],
                number_format($row['quantity'], 2) . ' ' . $row['uom_name'],
                number_format($row['avg_cost'], 2),
                number_format($row['last_purchase_rate'], 2),
                number_format($row['avg_value'], 2),
                number_format($row['last_purchase_value'], 2)
            ];

            $total_avg_value += $row['avg_value'];
            $total_last_value += $row['last_purchase_value'];
        }

        echo json_encode([
            'data' => $result,
            'total_avg_value' => number_format($total_avg_value, 2),
            'total_last_value' => number_format($total_last_value, 2)
        ]);
    }

    // Supplier Wise Report
    public function supplier_wise()
    {
        $data['suppliers'] = $this->db->table('inv_suppliers')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/report/supplier_wise', $data);
        echo view('template/footer');
    }

    public function supplier_wise_data()
    {
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $supplier_id = !empty($_POST['supplier_id']) ? $_POST['supplier_id'] : '';

        $sql = "SELECT si.doc_no, si.doc_date, si.supplier_invoice_no,
                       s.name as supplier_name, s.supplier_code,
                       COUNT(DISTINCT sid.id) as item_count,
                       SUM(sid.quantity) as total_qty,
                       si.total_amount, si.status
                FROM inv_stock_in si
                JOIN inv_suppliers s ON si.supplier_id = s.id
                LEFT JOIN inv_stock_in_details sid ON si.id = sid.stock_in_id
                WHERE si.doc_date BETWEEN ? AND ?";

        $params = [$from_date, $to_date];

        if (!empty($supplier_id)) {
            $sql .= " AND si.supplier_id = ?";
            $params[] = $supplier_id;
        }

        $sql .= " GROUP BY si.id ORDER BY si.doc_date DESC";

        $data = $this->db->query($sql, $params)->getResultArray();

        $result = [];
        $i = 1;
        $total_amount = 0;

        foreach ($data as $row) {
            $status_badge = $row['status'] == 'approved'
                ? '<span class="label bg-green">Approved</span>'
                : '<span class="label bg-orange">Draft</span>';

            $result[] = [
                $i++,
                date('d-M-Y', strtotime($row['doc_date'])),
                $row['doc_no'],
                $row['supplier_code'],
                $row['supplier_name'],
                $row['supplier_invoice_no'],
                $row['item_count'],
                number_format($row['total_amount'], 2),
                $status_badge,
                '<a href="' . base_url() . '/invstockin/view/' . $row['doc_no'] . '" class="btn btn-sm btn-info">View</a>'
            ];

            if ($row['status'] == 'approved') {
                $total_amount += $row['total_amount'];
            }
        }

        echo json_encode([
            'data' => $result,
            'total_amount' => number_format($total_amount, 2)
        ]);
    }

    // Location Wise Report
    public function location_wise()
    {
        $data['locations'] = $this->db->table('inv_locations')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/report/location_wise', $data);
        echo view('template/footer');
    }

    public function location_wise_data()
    {
        $location_id = !empty($_POST['location_id']) ? $_POST['location_id'] : '';

        $sql = "SELECT l.name as location_name, l.type,
                       COUNT(DISTINCT s.item_id) as item_count,
                       SUM(s.quantity) as total_qty,
                       SUM(s.available_qty) as available_qty,
                       SUM(s.reserved_qty) as reserved_qty,
                       SUM(s.quantity * i.avg_cost) as stock_value
                FROM inv_locations l
                LEFT JOIN inv_item_stock s ON l.id = s.location_id
                LEFT JOIN inv_items i ON s.item_id = i.id
                WHERE l.status = 1";

        $params = [];

        if (!empty($location_id)) {
            $sql .= " AND l.id = ?";
            $params[] = $location_id;
        }

        $sql .= " GROUP BY l.id ORDER BY l.name";

        $data = $this->db->query($sql, $params)->getResultArray();

        $result = [];
        $i = 1;
        $total_value = 0;

        foreach ($data as $row) {
            $result[] = [
                $i++,
                $row['location_name'],
                '<span class="label bg-purple">' . strtoupper($row['type']) . '</span>',
                $row['item_count'],
                number_format($row['total_qty'], 2),
                number_format($row['available_qty'], 2),
                number_format($row['reserved_qty'], 2),
                number_format($row['stock_value'], 2)
            ];

            $total_value += $row['stock_value'];
        }

        echo json_encode([
            'data' => $result,
            'total_value' => number_format($total_value, 2)
        ]);
    }

    // Item Wise Report
    public function item_wise()
    {
        $data['items'] = $this->db->table('inv_items')->where('status', 1)->orderBy('name', 'ASC')->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/report/item_wise', $data);
        echo view('template/footer');
    }

    public function item_wise_data()
    {
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $item_id = $_POST['item_id'];

        $sql = "SELECT m.doc_date, m.doc_no, m.doc_type, l.name as location_name,
                       m.movement_type, m.quantity, m.rate, m.amount,
                       m.balance_qty, m.remarks
                FROM inv_stock_movement m
                JOIN inv_locations l ON m.location_id = l.id
                WHERE m.item_id = ?
                AND m.doc_date BETWEEN ? AND ?
                ORDER BY m.doc_date DESC, m.id DESC";

        $data = $this->db->query($sql, [$item_id, $from_date, $to_date])->getResultArray();

        // Get item details
        $item = $this->db->table('inv_items i')
            ->select('i.*, c.name as category_name, u.symbol as uom_name')
            ->join('inv_categories c', 'c.id = i.category_id', 'left')
            ->join('uom_list u', 'u.id = i.uom_id', 'left')
            ->where('i.id', $item_id)
            ->get()->getRowArray();

        $result = [];
        $i = 1;
        $total_in = 0;
        $total_out = 0;

        foreach ($data as $row) {
            $type_badge = $row['movement_type'] == 'in'
                ? '<span class="label bg-green">IN</span>'
                : '<span class="label bg-red">OUT</span>';

            $result[] = [
                $i++,
                date('d-M-Y', strtotime($row['doc_date'])),
                $row['doc_no'],
                $row['doc_type'],
                $row['location_name'],
                $type_badge,
                number_format($row['quantity'], 2),
                number_format($row['rate'], 2),
                number_format($row['amount'], 2),
                number_format($row['balance_qty'], 2),
                !empty($row['remarks']) ? $row['remarks'] : '-'
            ];

            if ($row['movement_type'] == 'in') {
                $total_in += $row['quantity'];
            } else {
                $total_out += $row['quantity'];
            }
        }

        echo json_encode([
            'data' => $result,
            'item' => $item,
            'total_in' => number_format($total_in, 2),
            'total_out' => number_format($total_out, 2)
        ]);
    }
}