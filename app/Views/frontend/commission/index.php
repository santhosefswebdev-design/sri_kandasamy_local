<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/typicons/typicons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/mdi/css/materialdesignicons.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/css/vertical-layout-light/style.css">

<style>
    body {
        background: #f5f5f5;
        min-height: 100vh;
    }

    .main-panel {
        width: 100%;
    }

    .content-wrapper {
        background: #f5f5f5;
        padding: 20px;
    }

    .report-title {
        text-align: center;
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 30px;
        color: #333;
    }

    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .filter-row {
        display: flex;
        gap: 15px;
        align-items: end;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .btn-filter {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 10px 30px;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        height: 42px;
    }

    .btn-filter:hover {
        background: #45a049;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .btn-print {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-pdf {
        background: #dc3545;
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-excel {
        background: #28a745;
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-add {
        background: #17a2b8;
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        margin-left: auto;
    }

    .search-section {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 15px;
    }

    .search-input {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 300px;
    }

    .table-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead {
        background: #f7ebbb;
    }

    .data-table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        border: 1px solid #ddd;
        color: #333;
    }

    .data-table td {
        padding: 12px;
        border: 1px solid #ddd;
        color: #666;
    }

    .data-table tbody tr:hover {
        background: #f9f9f9;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .pagination-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding: 15px 0;
    }

    .pagination-info {
        color: #666;
    }

    .pagination-buttons button {
        padding: 8px 20px;
        margin-left: 10px;
        border: 1px solid #ddd;
        background: white;
        cursor: pointer;
        border-radius: 4px;
    }

    .pagination-buttons button:hover {
        background: #f5f5f5;
    }

    .action-btn {
        padding: 6px 12px;
        margin: 0 2px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 12px;
    }

    .btn-view {
        background: #2196F3;
        color: white;
    }

    .btn-print-small {
        background: #FF9800;
        color: white;
    }

    .btn-delete {
        background: #f44336;
        color: white;
    }

    .suc-alert,
    .alert {
        padding: 15px 20px;
        border-radius: 4px;
        margin-bottom: 20px;
        position: relative;
    }

    .suc-alert {
        background: #4CAF50;
        color: white;
    }

    .alert {
        background: #f44336;
        color: white;
    }

    .closebtn,
    .suc-closebtn {
        position: absolute;
        right: 15px;
        top: 10px;
        font-size: 24px;
        cursor: pointer;
        color: white;
    }
</style>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <?php if (isset($_SESSION['succ']) && $_SESSION['succ'] != '') { ?>
                        <div class="suc-alert">
                            <span class="suc-closebtn" onClick="this.parentElement.style.display='none';">&times;</span>
                            <?php echo $_SESSION['succ']; $_SESSION['succ'] = ''; ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['fail']) && $_SESSION['fail'] != '') { ?>
                        <div class="alert">
                            <span class="closebtn" onClick="this.parentElement.style.display='none';">&times;</span>
                            <?php echo $_SESSION['fail']; $_SESSION['fail'] = ''; ?>
                        </div>
                    <?php } ?>

                    <h1 class="report-title">Priest Commission Report</h1>

                    <!-- Filter Section -->
                    <div class="filter-section">
                        <form method="GET" action="<?php echo base_url(); ?>/commission_online" id="filter_form">
                            <div class="filter-row">
                                <div class="filter-group">
                                    <input type="date" name="from_date" id="from_date" 
                                           value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01'); ?>"
                                           placeholder="From Date">
                                </div>
                                <div class="filter-group">
                                    <input type="date" name="to_date" id="to_date" 
                                           value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d'); ?>"
                                           placeholder="To Date">
                                </div>
                                <div class="filter-group">
                                    <select name="priest_id" id="priest_filter">
                                        <option value="">-- Select Priest --</option>
                                        <?php foreach ($staff_list as $staff) { ?>
                                            <option value="<?php echo $staff['id']; ?>"
                                                <?php echo (isset($_GET['priest_id']) && $_GET['priest_id'] == $staff['id']) ? 'selected' : ''; ?>>
                                                <?php echo $staff['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="filter-group">
                                    <select name="product_id" id="product_filter">
                                        <option value="">-- Select Product --</option>
                                        <?php foreach ($archanai_list as $archanai) { ?>
                                            <option value="<?php echo $archanai['id']; ?>"
                                                <?php echo (isset($_GET['product_id']) && $_GET['product_id'] == $archanai['id']) ? 'selected' : ''; ?>>
                                                <?php echo $archanai['name_eng']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn-filter">FILTER</button>
                            </div>
                        </form>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button onclick="printReport()" class="btn-print">PRINT</button>
                        <button onclick="exportPDF()" class="btn-pdf">PDF</button>
                        <button onclick="exportExcel()" class="btn-excel">EXCEL</button>
                        <a href="<?php echo base_url(); ?>/commission_online/add" class="btn-add">
                            <i class="mdi mdi-plus"></i> ADD NEW
                        </a>
                    </div>

                    <!-- Search Box -->
                    <div class="search-section">
                        <input type="text" id="search_input" class="search-input" placeholder="Search:">
                    </div>

                    <!-- Data Table -->
                    <div class="table-container">
                        <table class="data-table" id="commission_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Date</th>
                                    <th>Priest Name</th>
                                    <th>Services</th>
                                    <th>Amount (RM)</th>
                                    <!-- <th>Created</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($commission_list)) {
                                    $i = 1;
                                    foreach ($commission_list as $commission) { ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo date('d-m-Y', strtotime($commission['date'])); ?></td>
                                            <td><?php echo $commission['staff_name']; ?></td>
                                            <td>
                                                <small><?php echo substr($commission['remarks'], 0, 40); ?>
                                                    <?php echo strlen($commission['remarks']) > 40 ? '...' : ''; ?>
                                                </small>
                                            </td>
                                            <td><strong><?php echo number_format($commission['amount'], 2); ?></strong></td>
                                            <!-- <td><?php echo date('d-m-Y h:i A', strtotime($commission['created'])); ?></td> -->
                                            <td>
                                                <button onclick="viewCommission(<?php echo $commission['id']; ?>)" 
                                                        class="action-btn btn-view" title="View">
                                                    <i class="mdi mdi-eye"></i>
                                                </button>
                                                <button onclick="printReceipt(<?php echo $commission['id']; ?>)" 
                                                        class="action-btn btn-print-small" title="Print">
                                                    <i class="mdi mdi-printer"></i>
                                                </button>
                                                <button onclick="confirmDelete(<?php echo $commission['id']; ?>)" 
                                                        class="action-btn btn-delete" title="Delete">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="7" class="no-data">No data available in table</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="pagination-section">
                            <div class="pagination-info">
                                Showing <?php echo !empty($commission_list) ? '1' : '0'; ?> to 
                                <?php echo !empty($commission_list) ? count($commission_list) : '0'; ?> of 
                                <?php echo !empty($commission_list) ? count($commission_list) : '0'; ?> entries
                            </div>
                            <div class="pagination-buttons">
                                <button onclick="previousPage()">Previous</button>
                                <button onclick="nextPage()">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="mdi mdi-alert-circle-outline" style="font-size:42px; color:red;"></i>
                        <h5 class="mt-3">Are you sure you want to delete this commission?</h5>
                        <p>This action cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="#" id="confirm-delete-btn" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/vendors/js/vendor.bundle.base.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/js/off-canvas.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/js/hoverable-collapse.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/js/template.js"></script>

    <script>
        // Search functionality
        $('#search_input').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#commission_table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // View commission
        function viewCommission(id) {
            window.location.href = '<?php echo base_url(); ?>/commission_online/view/' + id;
        }

        // Print receipt
        function printReceipt(id) {
            window.open('<?php echo base_url(); ?>/commission_online/print_receipt/' + id, '_blank', 'width=680,height=500');
        }

        // Delete confirmation
        function confirmDelete(id) {
            $('#confirm-delete-btn').attr('href', '<?php echo base_url(); ?>/commission_online/delete/' + id);
            $('#delete-modal').modal('show');
        }

        // Print report
        function printReport() {
            window.print();
        }

        // Export to PDF
        function exportPDF() {
            alert('PDF export functionality - to be implemented');
            // You can add PDF library here
        }

        // Export to Excel
        function exportExcel() {
            var table = document.getElementById('commission_table');
            var html = table.outerHTML;
            var url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
            var downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);
            downloadLink.href = url;
            downloadLink.download = 'commission_report_' + new Date().getTime() + '.xls';
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        // Pagination (basic implementation)
        function previousPage() {
            alert('Previous page functionality');
        }

        function nextPage() {
            alert('Next page functionality');
        }

        // Print styles
        window.addEventListener('beforeprint', function() {
            document.querySelector('.filter-section').style.display = 'none';
            document.querySelector('.action-buttons').style.display = 'none';
            document.querySelector('.search-section').style.display = 'none';
            document.querySelector('.pagination-section').style.display = 'none';
            document.querySelectorAll('.action-btn').forEach(function(btn) {
                btn.style.display = 'none';
            });
        });

        window.addEventListener('afterprint', function() {
            document.querySelector('.filter-section').style.display = 'block';
            document.querySelector('.action-buttons').style.display = 'flex';
            document.querySelector('.search-section').style.display = 'flex';
            document.querySelector('.pagination-section').style.display = 'flex';
            document.querySelectorAll('.action-btn').forEach(function(btn) {
                btn.style.display = 'inline-block';
            });
        });
    </script>
</body>