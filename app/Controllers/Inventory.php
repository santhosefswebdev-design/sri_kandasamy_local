<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use App\Models\InventoryItemModel;
use App\Models\InventoryTransactionModel;
use App\Models\InventoryStockModel;

class Inventory extends BaseController
{
    protected $itemModel;
    protected $transactionModel;
    protected $stockModel;
    protected $permissionModel;

    function __construct()
    {
        parent::__construct();
        helper('url');
        helper('form');

        $this->itemModel = new InventoryItemModel();
        $this->transactionModel = new InventoryTransactionModel();
        $this->stockModel = new InventoryStockModel();
        $this->permissionModel = new PermissionModel();

        if (($this->session->get('login')) == false && $this->session->get('role') != 1) {
            $data['dn_msg'] = 'Please Login';
            header('Location: ' . base_url() . '/login');
            exit;
        }
    }

    /**
     * Dashboard - Inventory Overview
     */
    public function index()
    {
        $data['permission'] = $this->permissionModel->get_permission('inventory');

        // Get inventory summary
        $data['total_items'] = $this->itemModel->where('status', 1)->countAllResults();
        $data['low_stock_items'] = count($this->itemModel->getLowStockItems());

        // Get total stock value
        $stockValuation = $this->stockModel->getStockValuationReport();
        $data['total_stock_value'] = array_sum(array_column($stockValuation, 'stock_value'));

        // Recent transactions
        $data['recent_transactions'] = $this->transactionModel
            ->select('inventory_transactions.*, inventory_items.name as item_name, inventory_items.item_code')
            ->join('inventory_items', 'inventory_items.id = inventory_transactions.item_id')
            ->orderBy('inventory_transactions.transaction_date', 'DESC')
            ->limit(10)
            ->findAll();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/dashboard', $data);
        echo view('template/footer');
    }

    /**
     * Items List
     */
    public function items()
    {
        $data['permission'] = $this->permissionModel->get_permission('inventory');

        // Get filters
        $filters = [
            'category_id' => $this->request->getGet('category_id'),
            'item_type' => $this->request->getGet('item_type'),
            'status' => $this->request->getGet('status') ?? 1,
            'search' => $this->request->getGet('search')
        ];

        $data['items'] = $this->itemModel->getItemsWithStock($filters);
        $data['categories'] = $this->db->table('inventory_categories')
            ->where('status', 1)
            ->get()
            ->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/items_list', $data);
        echo view('template/footer');
    }

    /**
     * Add Item Form
     */
    public function add_item()
    {
        if (!$this->permissionModel->permission_validate('inventory', 'create_p')) {
            header('Location: ' . base_url() . '/dashboard');
            exit;
        }

        $data['categories'] = $this->db->table('inventory_categories')
            ->where('status', 1)
            ->get()
            ->getResultArray();
        $data['uoms'] = $this->db->table('inventory_uom')
            ->where('status', 1)
            ->get()
            ->getResultArray();
        $data['suppliers'] = $this->db->table('supplier')
            ->where('status', 1)
            ->get()
            ->getResultArray();
        $data['locations'] = $this->db->table('inventory_locations')
            ->where('status', 1)
            ->get()
            ->getResultArray();

        // Generate new item code
        $data['item_code'] = $this->itemModel->generateItemCode('INV');

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/item_form', $data);
        echo view('template/footer');
    }

    /**
     * Edit Item Form
     */
    public function edit_item($id)
    {
        if (!$this->permissionModel->permission_validate('inventory', 'edit')) {
            header('Location: ' . base_url() . '/dashboard');
            exit;
        }

        $data['item'] = $this->itemModel->getItemWithDetails($id);
        if (!$data['item']) {
            $this->session->setFlashdata('fail', 'Item not found');
            return redirect()->to('/inventory/items');
        }

        $data['categories'] = $this->db->table('inventory_categories')
            ->where('status', 1)
            ->get()
            ->getResultArray();
        $data['uoms'] = $this->db->table('inventory_uom')
            ->where('status', 1)
            ->get()
            ->getResultArray();
        $data['suppliers'] = $this->db->table('supplier')
            ->where('status', 1)
            ->get()
            ->getResultArray();
        $data['locations'] = $this->db->table('inventory_locations')
            ->where('status', 1)
            ->get()
            ->getResultArray();

        // Get current stock
        $data['stock_locations'] = $this->itemModel->getItemStockByLocation($id);

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/item_form', $data);
        echo view('template/footer');
    }

    /**
     * Save Item
     */
    public function save_item()
    {
        $id = $this->request->getPost('id');

        $data = [
            'item_code' => $this->request->getPost('item_code'),
            'name' => $this->request->getPost('name'),
            'name_tamil' => $this->request->getPost('name_tamil'),
            'category_id' => $this->request->getPost('category_id'),
            'uom_id' => $this->request->getPost('uom_id'),
            'item_type' => $this->request->getPost('item_type'),
            'description' => $this->request->getPost('description'),
            'minimum_stock' => $this->request->getPost('minimum_stock') ?? 0,
            'reorder_level' => $this->request->getPost('reorder_level') ?? 0,
            'maximum_stock' => $this->request->getPost('maximum_stock'),
            'standard_cost' => $this->request->getPost('standard_cost') ?? 0,
            'supplier_id' => $this->request->getPost('supplier_id'),
            'barcode' => $this->request->getPost('barcode'),
            'is_trackable' => $this->request->getPost('is_trackable') ?? 1,
            'has_expiry' => $this->request->getPost('has_expiry') ?? 0,
            'shelf_life_days' => $this->request->getPost('shelf_life_days'),
            'hsn_code' => $this->request->getPost('hsn_code'),
            'tax_percentage' => $this->request->getPost('tax_percentage') ?? 0,
            'status' => 1
        ];

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = time() . '_' . $image->getName();
            $image->move(ROOTPATH . 'public/uploads/inventory/', $newName);
            $data['image'] = $newName;
        }

        if (empty($id)) {
            $data['created_by'] = $this->session->get('log_id');
            $result = $this->itemModel->insert($data);

            if ($result) {
                // Create opening stock if provided
                $opening_stock = $this->request->getPost('opening_stock');
                $location_id = $this->request->getPost('location_id');

                if ($opening_stock > 0 && $location_id) {
                    $this->transactionModel->recordStockIn([
                        'transaction_date' => date('Y-m-d H:i:s'),
                        'item_id' => $result,
                        'location_id' => $location_id,
                        'quantity' => $opening_stock,
                        'uom_id' => $data['uom_id'],
                        'unit_cost' => $data['standard_cost'],
                        'reference_type' => 'opening_stock',
                        'remarks' => 'Opening Stock'
                    ]);
                }

                $this->session->setFlashdata('succ', 'Item Added Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Failed to add item');
            }
        } else {
            $data['updated_by'] = $this->session->get('log_id');
            $result = $this->itemModel->update($id, $data);

            if ($result) {
                $this->session->setFlashdata('succ', 'Item Updated Successfully');
            } else {
                $this->session->setFlashdata('fail', 'Failed to update item');
            }
        }

        return redirect()->to('/inventory/items');
    }

    /**
     * View Item Details
     */
    public function view_item($id)
    {
        $data['item'] = $this->itemModel->getItemWithDetails($id);
        if (!$data['item']) {
            $this->session->setFlashdata('fail', 'Item not found');
            return redirect()->to('/inventory/items');
        }

        $data['stock_locations'] = $this->itemModel->getItemStockByLocation($id);
        $data['transactions'] = $this->transactionModel->getItemTransactions($id, ['limit' => 20]);
        $data['view'] = true;

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/item_view', $data);
        echo view('template/footer');
    }

    /**
     * Stock In Form
     */
    public function stock_in()
    {
        $data['items'] = $this->itemModel->where('status', 1)
            ->where('is_trackable', 1)
            ->orderBy('name', 'ASC')
            ->findAll();
        $data['locations'] = $this->db->table('inventory_locations')
            ->where('status', 1)
            ->get()
            ->getResultArray();
        $data['suppliers'] = $this->db->table('supplier')
            ->where('status', 1)
            ->get()
            ->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_in_form', $data);
        echo view('template/footer');
    }

    /**
     * Process Stock In
     */
    public function process_stock_in()
    {
        $items = $this->request->getPost('items');
        $location_id = $this->request->getPost('location_id');
        $supplier_id = $this->request->getPost('supplier_id');
        $transaction_date = $this->request->getPost('transaction_date');
        $reference_number = $this->request->getPost('reference_number');

        if (empty($items) || empty($location_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Please provide all required information'
            ]);
            return;
        }

        $success_count = 0;
        $errors = [];

        foreach ($items as $item) {
            if (empty($item['item_id']) || empty($item['quantity'])) {
                continue;
            }

            $result = $this->transactionModel->recordStockIn([
                'transaction_date' => $transaction_date ?? date('Y-m-d H:i:s'),
                'item_id' => $item['item_id'],
                'location_id' => $location_id,
                'quantity' => $item['quantity'],
                'uom_id' => $item['uom_id'],
                'unit_cost' => $item['unit_cost'] ?? 0,
                'batch_number' => $item['batch_number'] ?? null,
                'expiry_date' => $item['expiry_date'] ?? null,
                'supplier_id' => $supplier_id,
                'reference_number' => $reference_number,
                'remarks' => $item['remarks'] ?? null
            ]);

            if ($result['success']) {
                $success_count++;
            } else {
                $errors[] = $result['message'];
            }
        }

        echo json_encode([
            'success' => true,
            'message' => "$success_count item(s) added to stock",
            'errors' => $errors
        ]);
    }

    /**
     * Stock Out Form
     */
    public function stock_out()
    {
        $data['items'] = $this->itemModel->getItemsWithStock();
        $data['locations'] = $this->db->table('inventory_locations')
            ->where('status', 1)
            ->get()
            ->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_out_form', $data);
        echo view('template/footer');
    }

    /**
     * Process Stock Out
     */
    public function process_stock_out()
    {
        $items = $this->request->getPost('items');
        $location_id = $this->request->getPost('location_id');
        $transaction_date = $this->request->getPost('transaction_date');
        $reference_number = $this->request->getPost('reference_number');
        $remarks = $this->request->getPost('remarks');

        if (empty($items) || empty($location_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Please provide all required information'
            ]);
            return;
        }

        $success_count = 0;
        $errors = [];

        foreach ($items as $item) {
            if (empty($item['item_id']) || empty($item['quantity'])) {
                continue;
            }

            $result = $this->transactionModel->recordStockOut([
                'transaction_date' => $transaction_date ?? date('Y-m-d H:i:s'),
                'item_id' => $item['item_id'],
                'location_id' => $location_id,
                'quantity' => $item['quantity'],
                'uom_id' => $item['uom_id'],
                'reference_number' => $reference_number,
                'remarks' => $remarks
            ]);

            if ($result['success']) {
                $success_count++;
            } else {
                $errors[] = $result['message'];
            }
        }

        echo json_encode([
            'success' => $success_count > 0,
            'message' => "$success_count item(s) removed from stock",
            'errors' => $errors
        ]);
    }

    /**
     * Stock Transfer Form
     */
    public function stock_transfer()
    {
        $data['items'] = $this->itemModel->getItemsWithStock();
        $data['locations'] = $this->db->table('inventory_locations')
            ->where('status', 1)
            ->get()
            ->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/stock_transfer_form', $data);
        echo view('template/footer');
    }

    /**
     * Process Stock Transfer
     */
    public function process_transfer()
    {
        $items = $this->request->getPost('items');
        $from_location_id = $this->request->getPost('from_location_id');
        $to_location_id = $this->request->getPost('to_location_id');
        $transaction_date = $this->request->getPost('transaction_date');
        $reference_number = $this->request->getPost('reference_number');

        if (empty($items) || empty($from_location_id) || empty($to_location_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Please provide all required information'
            ]);
            return;
        }

        if ($from_location_id == $to_location_id) {
            echo json_encode([
                'success' => false,
                'message' => 'Source and destination locations cannot be the same'
            ]);
            return;
        }

        $success_count = 0;
        $errors = [];

        foreach ($items as $item) {
            if (empty($item['item_id']) || empty($item['quantity'])) {
                continue;
            }

            $result = $this->transactionModel->recordTransfer([
                'transaction_date' => $transaction_date ?? date('Y-m-d H:i:s'),
                'item_id' => $item['item_id'],
                'from_location_id' => $from_location_id,
                'to_location_id' => $to_location_id,
                'quantity' => $item['quantity'],
                'uom_id' => $item['uom_id'],
                'reference_number' => $reference_number,
                'remarks' => $item['remarks'] ?? null
            ]);

            if ($result['success']) {
                $success_count++;
            } else {
                $errors[] = $result['message'];
            }
        }

        echo json_encode([
            'success' => $success_count > 0,
            'message' => "$success_count item(s) transferred successfully",
            'errors' => $errors
        ]);
    }

    /**
     * Stock Reports
     */
    public function reports()
    {
        $report_type = $this->request->getGet('type') ?? 'stock_summary';

        $data['report_type'] = $report_type;
        $data['locations'] = $this->db->table('inventory_locations')
            ->where('status', 1)
            ->get()
            ->getResultArray();
        $data['categories'] = $this->db->table('inventory_categories')
            ->where('status', 1)
            ->get()
            ->getResultArray();

        switch ($report_type) {
            case 'low_stock':
                $data['report_data'] = $this->stockModel->getLowStockReport();
                break;
            case 'overstock':
                $data['report_data'] = $this->stockModel->getOverstockReport();
                break;
            case 'stock_valuation':
                $data['report_data'] = $this->stockModel->getStockValuationReport();
                break;
            case 'stock_movement':
                $filters = [
                    'start_date' => $this->request->getGet('start_date'),
                    'end_date' => $this->request->getGet('end_date'),
                    'location_id' => $this->request->getGet('location_id')
                ];
                $data['report_data'] = $this->transactionModel->getStockMovementReport($filters);
                break;
            default:
                // Stock Summary
                $location_id = $this->request->getGet('location_id');
                if ($location_id) {
                    $data['report_data'] = $this->stockModel->getLocationStock($location_id);
                } else {
                    $data['report_data'] = $this->itemModel->getItemsWithStock();
                }
        }

        echo view('template/header');
        echo view('template/sidebar');
        echo view('inventory/reports', $data);
        echo view('template/footer');
    }

    /**
     * AJAX: Get item details
     */
    public function get_item_details()
    {
        $item_id = $this->request->getPost('item_id');
        $location_id = $this->request->getPost('location_id');

        $item = $this->itemModel->getItemWithDetails($item_id);

        if ($location_id) {
            $stock = $this->stockModel->getCurrentStock($item_id, $location_id);
            $item['current_stock'] = $stock;
        }

        echo json_encode($item);
    }

    /**
     * AJAX: Check stock availability
     */
    public function check_stock()
    {
        $item_id = $this->request->getPost('item_id');
        $location_id = $this->request->getPost('location_id');
        $required_qty = $this->request->getPost('quantity');

        $result = $this->itemModel->checkStockAvailability($item_id, $location_id, $required_qty);

        echo json_encode($result);
    }
}