<?php global $lang; ?>
<style>
    .table-responsive {
        overflow-x: auto;
    }

    .status-paid {
        background-color: #4CAF50;
        color: white;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 11px;
    }

    .status-pending {
        background-color: #ff9800;
        color: white;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 11px;
    }

    .status-partial {
        background-color: #2196F3;
        color: white;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 11px;
    }

    .filter-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .filter-form .form-control {
        border-radius: 6px;
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }

    .filter-form .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn-filter {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        border-radius: 25px;
        padding: 10px 25px;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
    }

    .btn-clear {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border: none;
        border-radius: 25px;
        padding: 10px 25px;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-clear:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
    }

    .info-box {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .info-box-icon {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        width: 70px;
        height: 70px;
    }

    .info-box-content {
        padding: 15px;
        flex: 1;
    }

    .info-box-text {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        color: #666;
        margin-bottom: 5px;
    }

    .info-box-number {
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }

    .member-status-active {
        color: #28a745;
        font-weight: bold;
    }

    .member-status-inactive {
        color: #dc3545;
        font-weight: bold;
    }

    .export-buttons {
        margin-bottom: 15px;
    }

    .export-buttons .btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .table th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .year-selector {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 10px;
    }

    .reminder-btn {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        border: none;
        border-radius: 25px;
        color: white;
        font-weight: 500;
    }

    .reminder-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>MEMBER DUES MANAGEMENT <small>Member / <b>Annual Dues</b></small></h2>
        </div>

        <!-- Statistics Row -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box d-flex">
                    <div class="info-box-icon bg-green">
                        <i class="material-icons">check_circle</i>
                    </div>
                    <div class="info-box-content">
                        <div class="info-box-text">PAID</div>
                        <div class="info-box-number count-to" data-from="0"
                            data-to="<?php echo count(array_filter($members, function ($m) {
                                return $m['dues_status'] == 1; })); ?>"
                            data-speed="1000" data-fresh-interval="20">
                            <?php echo count(array_filter($members, function ($m) { return $m['dues_status'] == 1; })); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box d-flex">
                    <div class="info-box-icon bg-orange">
                        <i class="material-icons">warning</i>
                    </div>
                    <div class="info-box-content">
                        <div class="info-box-text">PENDING</div>
                        <div class="info-box-number count-to" data-from="0"
                            data-to="<?php echo count(array_filter($members, function ($m) {
                                return $m['dues_status'] == 0; })); ?>"
                            data-speed="1000" data-fresh-interval="20">
                            <?php echo count(array_filter($members, function ($m) { return $m['dues_status'] == 0; })); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box d-flex">
                    <div class="info-box-icon bg-blue">
                        <i class="material-icons">hourglass_empty</i>
                    </div>
                    <div class="info-box-content">
                        <div class="info-box-text">PARTIAL</div>
                        <div class="info-box-number count-to" data-from="0"
                            data-to="<?php echo count(array_filter($members, function ($m) {
                                return $m['dues_status'] == 2; })); ?>"
                            data-speed="1000" data-fresh-interval="20">
                            <?php echo count(array_filter($members, function ($m) { return $m['dues_status'] == 2; })); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box d-flex">
                    <div class="info-box-icon bg-red">
                        <i class="material-icons">attach_money</i>
                    </div>
                    <div class="info-box-content">
                        <div class="info-box-text">TOTAL DUE</div>
                        <div class="info-box-number">RM <?php
                        $total_due = array_sum(array_map(function ($m) {
                            return $m['due_amount'] - $m['paid_amount'];
                        }, $members));
                        echo number_format($total_due, 2);
                        ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="filter-card">
            <h4><i class="material-icons">filter_list</i> Filter Members</h4>
            <hr>
            <form method="get" action="<?php echo base_url(); ?>/member/dues_list" class="filter-form">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Year</label>
                            <div class="year-selector">
                                <select name="year" class="form-control">
                                    <?php foreach ($available_years as $year) { ?>
                                        <option value="<?php echo $year; ?>" <?php echo ($year == $current_year) ? 'selected' : ''; ?>>
                                            <?php echo $year; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Member Name</label>
                            <input type="text" name="member_name" class="form-control" 
                                   placeholder="Search by name..." 
                                   value="<?php echo $filters['member_name']; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Member Status</label>
                            <select name="status_filter" class="form-control">
                                <option value="all" <?php echo ($filters['status_filter'] == 'all') ? 'selected' : ''; ?>>All Status</option>
                                <option value="active" <?php echo ($filters['status_filter'] == 'active') ? 'selected' : ''; ?>>Active Only</option>
                                <option value="inactive" <?php echo ($filters['status_filter'] == 'inactive') ? 'selected' : ''; ?>>Inactive Only</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Join Date From</label>
                            <input type="date" name="date_from" class="form-control" 
                                   value="<?php echo $filters['date_from']; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Join Date To</label>
                            <input type="date" name="date_to" class="form-control" 
                                   value="<?php echo $filters['date_to']; ?>">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-filter">
                                <i class="material-icons">search</i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="<?php echo base_url(); ?>/member/dues_list" class="btn btn-clear">
                            <i class="material-icons">clear</i> Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Main Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>Ordinary Member Annual Dues - Year <?php echo $current_year; ?></h2>
                            </div>
                            <div class="col-md-6" align="right">
                                <button type="button" class="btn reminder-btn waves-effect" onclick="sendReminders()">
                                    <i class="material-icons">email</i> Send Reminders
                                </button>
                                <a href="<?php echo base_url(); ?>/member">
                                    <button type="button" class="btn bg-deep-purple waves-effect">
                                        <i class="material-icons">list</i> All Members
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <?php if ($_SESSION['succ'] != '') { ?>
                            <div class="row" style="padding: 0 30%;" id="content_alert">
                                <div class="suc-alert">
                                    <span class="suc-closebtn"
                                        onclick="this.parentElement.style.display='none';">&times;</span>
                                    <p><?php echo $_SESSION['succ']; ?></p>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Export Buttons -->
                        <div class="export-buttons">
                            <button class="btn btn-default btn-sm" onclick="exportTable('copy')">
                                <i class="material-icons">content_copy</i> Copy
                            </button>
                            <button class="btn btn-default btn-sm" onclick="exportTable('excel')">
                                <i class="material-icons">save</i> Excel
                            </button>
                            <button class="btn btn-default btn-sm" onclick="exportTable('pdf')">
                                <i class="material-icons">picture_as_pdf</i> PDF
                            </button>
                            <button class="btn btn-default btn-sm" onclick="exportTable('print')">
                                <i class="material-icons">print</i> Print
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="duesTable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Member No</th>
                                        <th>Name</th>
                                        <th>IC No</th>
                                        <th>Mobile</th>
                                        <th>Member Status</th>
                                        <th>Join Date</th>
                                        <th>Due Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Balance</th>
                                        <th>Payment Status</th>
                                        <th>Last Payment</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($members as $member) {
                                        $balance = $member['due_amount'] - $member['paid_amount'];
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><strong><?php echo $member['member_no']; ?></strong></td>
                                            <td><?php echo $member['name']; ?></td>
                                            <td><?php echo $member['ic_no']; ?></td>
                                            <td><?php echo $member['mobile']; ?></td>
                                            <td>
                                                <span class="<?php echo ($member['status'] == 'active') ? 'member-status-active' : 'member-status-inactive'; ?>">
                                                    <?php echo strtoupper($member['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($member['start_date'])); ?></td>
                                            <td>RM <?php echo number_format($member['due_amount'], 2); ?></td>
                                            <td>RM <?php echo number_format($member['paid_amount'], 2); ?></td>
                                            <td>
                                                <strong <?php echo ($balance > 0) ? 'style="color: red;"' : 'style="color: green;"'; ?>>
                                                    RM <?php echo number_format($balance, 2); ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <?php if ($member['dues_status'] == 1) { ?>
                                                    <span class="status-paid">PAID</span>
                                                <?php } elseif ($member['dues_status'] == 2) { ?>
                                                    <span class="status-partial">PARTIAL</span>
                                                <?php } else { ?>
                                                    <span class="status-pending">PENDING</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php echo $member['payment_date'] ? date('d/m/Y', strtotime($member['payment_date'])) : '-'; ?>
                                            </td>
                                            <td>
                                                <?php if ($member['dues_status'] != 1) { ?>
                                                    <button class="btn btn-primary btn-sm"
                                                        onclick="payDuesModal(<?php echo $member['id']; ?>, '<?php echo $member['name']; ?>', <?php echo $balance; ?>)">
                                                        <i class="material-icons">payment</i> Pay
                                                    </button>
                                                <?php } ?>
                                                <button class="btn btn-info btn-sm"
                                                    onclick="viewHistory(<?php echo $member['id']; ?>)">
                                                    <i class="material-icons">history</i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="payDuesModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?php echo base_url(); ?>/member/pay_dues" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Record Dues Payment</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="member_id" id="pay_member_id">
                        <input type="hidden" name="year" value="<?php echo $current_year; ?>">

                        <div class="alert alert-info">
                            <strong>Member:</strong> <span id="pay_member_name"></span><br>
                            <strong>Balance Due:</strong> RM <span id="pay_balance"></span>
                        </div>

                        <div class="form-group">
                            <label>Payment Amount <span style="color:red;">*</span></label>
                            <input type="number" step="0.01" name="amount" id="pay_amount" class="form-control" required
                                max="" placeholder="Enter payment amount">
                        </div>

                        <div class="form-group">
                            <label>Payment Mode <span style="color:red;">*</span></label>
                            <select name="payment_mode" class="form-control" required>
                                <option value="">-- Select Payment Mode --</option>
                                <option value="1">Cash</option>
                                <option value="2">Bank Transfer</option>
                                <option value="3">Cheque</option>
                                <option value="4">Online Payment</option>
                                <option value="5">Credit Card</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Payment Reference/Transaction No</label>
                            <input type="text" name="payment_reference" class="form-control"
                                placeholder="Enter transaction/cheque number">
                        </div>

                        <div class="form-group">
                            <label>Payment Notes</label>
                            <textarea name="payment_notes" class="form-control" rows="2"
                                placeholder="Any additional notes about this payment"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="material-icons">save</i> Record Payment
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Include DataTable and Export libraries -->
<link href="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with export functionality
    var table = $('#duesTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 25,
        order: [[0, 'asc']],
        columnDefs: [
            { targets: -1, orderable: false }, // Disable sorting on action column
            { targets: [7, 8, 9], className: 'text-right' } // Right align amount columns
        ],
        language: {
            search: "Search Members:",
            lengthMenu: "Show _MENU_ members per page",
            info: "Showing _START_ to _END_ of _TOTAL_ members",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        initComplete: function() {
            // Auto-hide success/error messages after 5 seconds
            setTimeout(function () {
                $('#content_alert').fadeOut('slow');
            }, 5000);
        }
    });

    // Export functions
    window.exportTable = function(type) {
        table.button('.' + type).trigger();
    };
});

function payDuesModal(memberId, memberName, balance) {
    $('#pay_member_id').val(memberId);
    $('#pay_member_name').text(memberName);
    $('#pay_balance').text(balance.toFixed(2));
    $('#pay_amount').attr('max', balance);
    $('#pay_amount').val(balance); // Pre-fill with full balance
    $('#payDuesModal').modal('show');
}

function viewHistory(memberId) {
    // Open payment history in new window
    window.open('<?php echo base_url(); ?>/member/dues_history/' + memberId, '_blank', 'width=800,height=600');
}

function sendReminders() {
    var pendingCount = <?php echo count(array_filter($members, function ($m) { return $m['dues_status'] == 0; })); ?>;
    
    if (pendingCount === 0) {
        alert('No pending dues found for sending reminders.');
        return;
    }
    
    if (confirm('Send reminder notifications to ' + pendingCount + ' members with pending dues for year <?php echo $current_year; ?>?')) {
        // Show loading
        var btn = event.target;
        var originalText = btn.innerHTML;
        btn.innerHTML = '<i class="material-icons">hourglass_empty</i> Sending...';
        btn.disabled = true;
        
        // AJAX call to send reminders
        $.ajax({
            url: '<?php echo base_url(); ?>/member/send_dues_reminders/<?php echo $current_year; ?>',
            type: 'POST',
            data: {
                year: <?php echo $current_year; ?>,
                filters: <?php echo json_encode($filters); ?>
            },
            success: function(response) {
                alert('Reminder notifications sent successfully!');
            },
            error: function() {
                alert('Error sending reminders. Please try again.');
            },
            complete: function() {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    }
}

// Auto-update year selector
$('select[name="year"]').change(function() {
    $(this).closest('form').submit();
});

// Enhanced form validation for payment modal
$('#payDuesModal form').on('submit', function(e) {
    var amount = parseFloat($('#pay_amount').val());
    var maxAmount = parseFloat($('#pay_amount').attr('max'));
    
    if (amount <= 0) {
        e.preventDefault();
        alert('Please enter a valid payment amount.');
        return false;
    }
    
    if (amount > maxAmount) {
        e.preventDefault();
        alert('Payment amount cannot exceed the balance due of RM ' + maxAmount.toFixed(2));
        return false;
    }
    
    return true;
});
</script>