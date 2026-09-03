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
</style>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>PENDING MEMBER APPROVALS <small>Member / <b>Pending Approvals</b></small></h2>
        </div>
        <!-- Basic Examples -->
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
                                        <th>Proposer 1</th>
                                        <th>Proposer 2</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($list as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($row['created'])); ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['ic_no']; ?></td>
                                            <td>
                                                <span
                                                    class="badge <?php echo ($row['member_type'] == 3) ? 'bg-info' : 'bg-warning'; ?>">
                                                    <?php echo $row['tname']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $row['mobile']; ?></td>
                                            <td>
                                                <?php echo $row['proposer_1_name']; ?>
                                                <?php if ($row['proposer_1_member_no']) { ?>
                                                    <br><small>(<?php echo $row['proposer_1_member_no']; ?>)</small>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php echo $row['proposer_2_name']; ?>
                                                <?php if ($row['proposer_2_member_no']) { ?>
                                                    <br><small>(<?php echo $row['proposer_2_member_no']; ?>)</small>
                                                <?php } ?>
                                            </td>
                                            <td style="width: 20%;">
                                                <button class="btn btn-success btn-sm"
                                                    onclick="approveModal(<?php echo $row['id']; ?>)"
                                                    data-name="<?php echo $row['name']; ?>">
                                                    <i class="material-icons">check</i> Approve
                                                </button>
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="rejectModal(<?php echo $row['id']; ?>)"
                                                    data-name="<?php echo $row['name']; ?>">
                                                    <i class="material-icons">close</i> Reject
                                                </button>
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?php echo base_url(); ?>/member/approve" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Approve Member Application</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="member_id" id="approve_member_id">
                        <p>Approving member: <strong><span id="approve_member_name"></span></strong></p>

                        <div class="form-group">
                            <label>Committee Meeting Date <span style="color:red;">*</span></label>
                            <input type="date" name="meeting_date" class="form-control" required
                                max="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label>Meeting Number</label>
                            <input type="text" name="meeting_no" class="form-control" placeholder="e.g., MGT/2024/001">
                        </div>

                        <div class="form-group">
                            <label>Meeting Details/Minutes</label>
                            <textarea name="meeting_details" class="form-control" rows="3"
                                placeholder="Enter meeting details or decision notes"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Approve</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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
                    <div class="modal-header">
                        <h4 class="modal-title">Reject Member Application</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="member_id" id="reject_member_id">
                        <p>Rejecting member: <strong><span id="reject_member_name"></span></strong></p>

                        <div class="form-group">
                            <label>Rejection Reason <span style="color:red;">*</span></label>
                            <textarea name="rejection_reason" class="form-control" rows="4" required
                                placeholder="Please provide a reason for rejection"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Reject</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    function approveModal(id) {
        var name = event.target.getAttribute('data-name');
        $('#approve_member_id').val(id);
        $('#approve_member_name').text(name);
        $('#approveModal').modal('show');
    }

    function rejectModal(id) {
        var name = event.target.getAttribute('data-name');
        $('#reject_member_id').val(id);
        $('#reject_member_name').text(name);
        $('#rejectModal').modal('show');
    }
</script>