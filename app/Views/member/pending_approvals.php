<?php global $lang; ?>
<style>
    .table-responsive {
        overflow-x: hidden;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 12px;
    }

    .bg-warning {
        background-color: #ff9800;
        color: white;
    }

    .bg-info {
        background-color: #2196F3;
        color: white;
    }

    .form-section {
        background: #f8f9fa;
        padding: 15px;
        margin: 15px 0;
        border-radius: 5px;
        border-left: 4px solid #007bff;
    }

    .form-section h5 {
        margin-top: 0;
        color: #007bff;
        font-weight: bold;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>PENDING MEMBER APPROVALS <small>Member / <b>Pending Approvals</b></small></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8">
                                <h2>Members Awaiting Approval</h2>
                            </div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/member">
                                    <button type="button" class="btn bg-deep-purple waves-effect">
                                        <i class="material-icons">list</i> All Members
                                    </button>
                                </a>
                                <a href="<?php echo base_url(); ?>/member/drafts">
                                    <button type="button" class="btn btn-warning waves-effect">
                                        <i class="material-icons">drafts</i> Drafts
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
                        <?php if ($_SESSION['fail'] != '') { ?>
                            <div class="row" style="padding: 0 30%;" id="content_alert">
                                <div class="alert">
                                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                                    <p><?php echo $_SESSION['fail']; ?></p>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Application Date</th>
                                        <th>Name</th>
                                        <th>IC No</th>
                                        <th>Member Type</th>
                                        <th>Mobile</th>
                                        <th>Payment</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($list as $row) {
                                        // Calculate payment amount based on member type if not already set
                                        $payment_amount = !empty($row['payment']) && $row['payment'] > 0
                                            ? $row['payment']
                                            : (!empty($row['member_type_amount']) ? $row['member_type_amount'] : 0);
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($row['created'])); ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['ic_no']; ?></td>
                                            <td>
                                                <span
                                                    class="badge <?php echo ($row['member_type'] == 3) ? 'bg-info' : (($row['member_type'] == 4) ? 'bg-dark' : 'bg-warning'); ?>">
                                                    <?php echo $row['tname']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $row['mobile']; ?></td>
                                            <td>
                                                <strong>RM <?php echo number_format($payment_amount, 2); ?></strong>
                                            </td>
                                            <td style="width: 20%;">
                                                <button class="btn btn-success btn-sm"
                                                    onclick="approveModal(<?php echo $row['id']; ?>)"
                                                    data-name="<?php echo $row['name']; ?>"
                                                    data-payment="<?php echo $payment_amount; ?>"
                                                    data-payment-mode="<?php echo $row['payment_mode'] ?? ''; ?>"
                                                    data-member-type="<?php echo $row['member_type']; ?>">
                                                    <i class="material-icons">check</i> Approve
                                                </button>
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="rejectModal(<?php echo $row['id']; ?>)"
                                                    data-name="<?php echo $row['name']; ?>">
                                                    <i class="material-icons">close</i> Reject
                                                </button>
                                                <a class="btn btn-warning btn-sm"
                                                    href="<?= base_url() ?>/member/edit/<?php echo $row['id']; ?>"
                                                    title="Edit Application">
                                                    <i class="material-icons">edit</i> Edit
                                                </a>
                                                <a class="btn btn-primary btn-sm"
                                                    href="<?= base_url() ?>/member/view/<?php echo $row['id']; ?>">
                                                    <i class="material-icons">visibility</i> View
                                                </a>
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

    <!-- Approval Modal -->
    <div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="<?php echo base_url(); ?>/member/approve" method="post" id="approvalForm">
                    <div class="modal-header bg-success" style="color: white;">
                        <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                        <h4 class="modal-title">
                            <i class="material-icons" style="vertical-align: middle;">check_circle</i>
                            Approve Member Application
                        </h4>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="member_id" id="approve_member_id">

                        <div class="alert alert-info">
                            <i class="material-icons" style="vertical-align: middle;">info</i>
                            <strong>Approving member:</strong> <span id="approve_member_name"></span>
                        </div>

                        <!-- Committee Meeting Information -->
                        <div class="form-section">
                            <h5><i class="material-icons" style="vertical-align: middle; font-size: 20px;">event</i>
                                Committee Meeting Information</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Committee Meeting Date <span style="color:red;">*</span></label>
                                        <input type="date" name="meeting_date" class="form-control" required
                                            max="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Meeting Number</label>
                                        <input type="text" name="meeting_no" class="form-control"
                                            placeholder="e.g., MGT/2024/001">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Meeting Details/Minutes</label>
                                <textarea name="meeting_details" class="form-control" rows="2"
                                    placeholder="Enter meeting details or decision notes"></textarea>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="form-section">
                            <h5><i class="material-icons" style="vertical-align: middle; font-size: 20px;">payment</i>
                                Payment Information</h5>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Payment Amount (RM) <span style="color:red;">*</span></label>
                                        <input type="number" step="0.01" name="payment_amount" id="payment_amount"
                                            class="form-control" required style="font-weight: bold; font-size: 16px;">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Payment Mode <span style="color:red;">*</span></label>
                                        <select name="payment_mode" id="payment_mode_select" class="form-control"
                                            required>
                                            <option value="">-- Select Payment Mode --</option>
                                            <?php if (!empty($payment_modes)) {
                                                foreach ($payment_modes as $pm) { ?>
                                                    <option value="<?php echo $pm['id']; ?>"
                                                        data-name="<?php echo strtolower($pm['name']); ?>">
                                                        <?php echo $pm['name']; ?>
                                                    </option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Payment Date <span style="color:red;">*</span></label>
                                        <input type="date" name="payment_date" class="form-control" required
                                            value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Receipt/Reference Number - CONDITIONAL DISPLAY -->
                            <div class="row" id="reference_number_row" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Receipt/Reference Number <span style="color:red;">*</span></label>
                                        <input type="text" name="payment_reference" id="payment_reference"
                                            class="form-control" placeholder="Enter receipt or transaction number">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Payment Status <span style="color:red;">*</span></label>
                                        <select name="payment_status" class="form-control" required>
                                            <option value="2" selected>Paid</option>
                                            <option value="1">Pending</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Status for Cash (no reference number) -->
                            <div class="row" id="cash_payment_row" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Payment Status <span style="color:red;">*</span></label>
                                        <select name="payment_status_cash" class="form-control" required>
                                            <option value="2" selected>Paid</option>
                                            <option value="1">Pending</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Payment Remarks</label>
                                <textarea name="payment_remarks" class="form-control" rows="2"
                                    placeholder="Any additional payment notes"></textarea>
                            </div>
                        </div>

                        <!-- Ledger Assignment -->
                        <div class="form-section">
                            <h5><i class="material-icons"
                                    style="vertical-align: middle; font-size: 20px;">account_balance</i>
                                Ledger Assignment</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Select Ledger <span style="color:red;">*</span></label>
                                        <select name="ledger_id" id="ledger_id" class="form-control" required>
                                            <option value="">-- Select Ledger --</option>
                                            <?php if (!empty($ledgers)) {
                                                foreach ($ledgers as $ledger) { ?>
                                                    <option value="<?php echo $ledger['id']; ?>">
                                                        <?php echo $ledger['name']; ?>
                                                    </option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning" style="margin-bottom: 0;">
                            <i class="material-icons" style="vertical-align: middle;">warning</i>
                            <strong>Note:</strong> Upon approval, the member will be assigned a member number and
                            notified via WhatsApp/Email.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="material-icons">check</i> Approve & Confirm Payment
                        </button>
                        <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">
                            <i class="material-icons">close</i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?php echo base_url(); ?>/member/reject" method="post">
                    <div class="modal-header bg-danger" style="color: white;">
                        <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                        <h4 class="modal-title">
                            <i class="material-icons" style="vertical-align: middle;">cancel</i>
                            Reject Member Application
                        </h4>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="member_id" id="reject_member_id">

                        <div class="alert alert-danger">
                            <i class="material-icons" style="vertical-align: middle;">warning</i>
                            <strong>Rejecting member:</strong> <span id="reject_member_name"></span>
                        </div>

                        <div class="form-group">
                            <label>Rejection Reason <span style="color:red;">*</span></label>
                            <textarea name="rejection_reason" class="form-control" rows="4" required
                                placeholder="Please provide a clear reason for rejection. This will be communicated to the applicant."></textarea>
                        </div>

                        <div class="alert alert-warning">
                            <i class="material-icons" style="vertical-align: middle;">info</i>
                            <strong>Note:</strong> The applicant will be notified of the rejection via WhatsApp/Email.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger btn-lg">
                            <i class="material-icons">cancel</i> Confirm Rejection
                        </button>
                        <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">
                            <i class="material-icons">arrow_back</i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    function approveModal(id) {
        var name = event.target.getAttribute('data-name');
        var payment = event.target.getAttribute('data-payment');
        var paymentMode = event.target.getAttribute('data-payment-mode');

        $('#approve_member_id').val(id);
        $('#approve_member_name').text(name);
        $('#payment_amount').val(payment);

        // Pre-select payment mode if available
        if (paymentMode) {
            $('#payment_mode_select').val(paymentMode);
            // Trigger change event to show/hide reference number field
            $('#payment_mode_select').trigger('change');
        }

        $('#approveModal').modal('show');
    }

    function rejectModal(id) {
        var name = event.target.getAttribute('data-name');
        $('#reject_member_id').val(id);
        $('#reject_member_name').text(name);
        $('#rejectModal').modal('show');
    }

    // CONDITIONAL DISPLAY: Show/hide reference number based on payment mode
    $(document).ready(function () {
        $('#payment_mode_select').on('change', function () {
            var selectedOption = $(this).find('option:selected');
            var paymentModeName = selectedOption.data('name');

            // Check if payment mode is "cash"
            if (paymentModeName === 'cash') {
                // Hide reference number row, show cash payment row
                $('#reference_number_row').hide();
                $('#payment_reference').prop('required', false);
                $('#cash_payment_row').show();
            } else {
                // Show reference number row, hide cash payment row
                $('#reference_number_row').show();
                $('#payment_reference').prop('required', true);
                $('#cash_payment_row').hide();
            }
        });

        // Trigger on modal open to set initial state
        $('#approveModal').on('shown.bs.modal', function () {
            $('#payment_mode_select').trigger('change');
        });
    });

    // Form validation before submission
    $('#approvalForm').on('submit', function (e) {
        var paymentAmount = parseFloat($('#payment_amount').val());
        var paymentMode = $('#payment_mode_select').val();
        var meetingDate = $('input[name="meeting_date"]').val();
        var ledgerId = $('#ledger_id').val();
        var paymentModeName = $('#payment_mode_select').find('option:selected').data('name');
        var paymentReference = $('#payment_reference').val();

        if (!meetingDate) {
            alert('Please select the committee meeting date');
            e.preventDefault();
            return false;
        }

        if (!paymentAmount || paymentAmount <= 0) {
            alert('Please enter a valid payment amount');
            e.preventDefault();
            return false;
        }

        if (!paymentMode) {
            alert('Please select a payment mode');
            e.preventDefault();
            return false;
        }

        // Check reference number only for non-cash payments
        if (paymentModeName !== 'cash' && !paymentReference) {
            alert('Please enter receipt/reference number');
            e.preventDefault();
            return false;
        }

        if (!ledgerId) {
            alert('Please select a ledger');
            e.preventDefault();
            return false;
        }

        return confirm('Are you sure you want to approve this member application?');
    });
</script>