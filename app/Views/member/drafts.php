<style>
    .draft-badge {
        background: #ff9800;
        color: white;
        padding: 3px 10px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: bold;
    }

    .pending-badge {
        background: #2196F3;
        color: white;
        padding: 3px 10px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: bold;
    }

    .suc-alert {
        background-color: #4CAF50;
        color: white;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    .suc-closebtn {
        float: right;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
    }

    .alert-danger {
        background-color: #f44336;
        color: white;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    .closebtn {
        float: right;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .btn-group .btn {
        margin-right: 3px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .empty-state i {
        font-size: 64px;
        color: #ccc;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        margin-bottom: 10px;
        color: #333;
    }

    .stats-card {
        background: linear-gradient(135deg, #ff9800, #f57c00);
        color: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .stats-card h3 {
        margin: 0;
        font-size: 36px;
    }

    .stats-card p {
        margin: 5px 0 0 0;
        opacity: 0.9;
    }
</style>

<?php
// Helper function to display value or dash
function dv($value, $default = '-')
{
    if ($value === null || $value === '' || $value === '0' || $value == '0000-00-00' || $value == '0000-00-00 00:00:00') {
        return $default;
    }
    return htmlspecialchars($value);
}

// Helper function for date formatting
function dvDate($value, $format = 'd/m/Y')
{
    if ($value === null || $value === '' || $value == '0000-00-00' || $value == '0000-00-00 00:00:00') {
        return '-';
    }
    return date($format, strtotime($value));
}
?>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                MEMBER DRAFTS
                <small>Member / <b>Draft Applications</b></small>
            </h2>
        </div>

        <!-- Stats Card -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card">
                    <h3><?php echo count($list); ?></h3>
                    <p><i class="material-icons" style="vertical-align: middle;">drafts</i> Total Drafts</p>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>
                                    <i class="material-icons" style="vertical-align: middle;">drafts</i>
                                    Draft Member Applications
                                    <span class="draft-badge"><?php echo count($list); ?> Drafts</span>
                                </h2>
                            </div>
                            <div class="col-md-6" align="right">
                                <a href="<?php echo base_url(); ?>/member">
                                    <button type="button" class="btn bg-deep-purple waves-effect">
                                        <i class="material-icons">list</i> All Members
                                    </button>
                                </a>
                                <a href="<?php echo base_url(); ?>/member/pending_approvals">
                                    <button type="button" class="btn bg-blue waves-effect">
                                        <i class="material-icons">pending</i> Pending Approvals
                                    </button>
                                </a>
                                <a href="<?php echo base_url(); ?>/member/add">
                                    <button type="button" class="btn bg-green waves-effect">
                                        <i class="material-icons">add</i> Add New
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="body">
                        <!-- Success Message -->
                        <?php if (!empty($_SESSION['succ'])) { ?>
                            <div class="row" style="padding: 0 15%;" id="content_alert">
                                <div class="suc-alert">
                                    <span class="suc-closebtn"
                                        onclick="this.parentElement.style.display='none';">&times;</span>
                                    <p><?php echo $_SESSION['succ'];
                                    unset($_SESSION['succ']); ?></p>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Error Message -->
                        <?php if (!empty($_SESSION['fail'])) { ?>
                            <div class="row" style="padding: 0 15%;" id="content_alert">
                                <div class="alert-danger">
                                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                                    <p><?php echo $_SESSION['fail'];
                                    unset($_SESSION['fail']); ?></p>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (empty($list)) { ?>
                            <!-- Empty State -->
                            <div class="empty-state">
                                <i class="material-icons">drafts</i>
                                <h4>No Draft Applications</h4>
                                <p>There are no draft member applications at the moment.</p>
                                <br>
                                <a href="<?php echo base_url(); ?>/member/add" class="btn btn-primary btn-lg">
                                    <i class="material-icons">add</i> Add New Member
                                </a>
                            </div>
                        <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="draftsTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">S.No</th>
                                            <th style="width: 15%;">Name</th>
                                            <th style="width: 10%;">IC No</th>
                                            <th style="width: 10%;">Mobile</th>
                                            <th style="width: 12%;">Email</th>
                                            <th style="width: 10%;">Member Type</th>
                                            <th style="width: 10%;">Created Date</th>
                                            <th style="width: 10%;">Last Modified</th>
                                            <th style="width: 8%;">Status</th>
                                            <th style="width: 10%;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                        foreach ($list as $row) { ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td>
                                                    <strong>
                                                        <?php
                                                        $name = trim(dv($row['prefix'], '') . ' ' . dv($row['first_name'], '') . ' ' . dv($row['last_name'], ''));
                                                        echo !empty($name) ? $name : dv($row['name']);
                                                        ?>
                                                    </strong>
                                                </td>
                                                <td><?php echo dv($row['ic_no']); ?></td>
                                                <td><?php echo dv($row['tel_phone_mobile'] ?? $row['mobile'] ?? null); ?></td>
                                                <td><?php echo dv($row['email_address'] ?? $row['email'] ?? null); ?></td>
                                                <td>
                                                    <?php
                                                    if (!empty($row['tname'])) {
                                                        echo '<span class="badge badge-info">' . $row['tname'] . '</span>';
                                                    } elseif (!empty($row['member_type'])) {
                                                        echo $row['member_type'] == 3 ? '<span class="badge badge-success">Life Member</span>' : '<span class="badge badge-primary">Ordinary</span>';
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo dvDate($row['created'] ?? null); ?></td>
                                                <td><?php echo dvDate($row['modified'] ?? null); ?></td>
                                                <td>
                                                    <span class="draft-badge">
                                                        <i class="material-icons"
                                                            style="font-size: 12px; vertical-align: middle;">edit</i> DRAFT
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <!-- Continue Editing -->
                                                        <a href="<?php echo base_url(); ?>/member/edit/<?php echo $row['id']; ?>"
                                                            class="btn btn-sm btn-warning" title="Continue Editing">
                                                            <i class="material-icons">edit</i>
                                                        </a>

                                                        <!-- Submit for Approval -->
                                                        <button type="button" class="btn btn-sm btn-success"
                                                            onclick="submitDraft(<?php echo $row['id']; ?>, '<?php echo addslashes(dv($row['first_name'] ?? $row['name'], 'this draft')); ?>')"
                                                            title="Submit for Approval">
                                                            <i class="material-icons">send</i>
                                                        </button>

                                                        <!-- View -->
                                                        <a href="<?php echo base_url(); ?>/member/view/<?php echo $row['id']; ?>"
                                                            class="btn btn-sm btn-info" title="View Details">
                                                            <i class="material-icons">visibility</i>
                                                        </a>

                                                        <!-- Delete Draft -->
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="deleteDraft(<?php echo $row['id']; ?>, '<?php echo addslashes(dv($row['first_name'] ?? $row['name'], 'this draft')); ?>')"
                                                            title="Delete Draft">
                                                            <i class="material-icons">delete</i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Submit Confirmation Modal -->
<div class="modal fade" id="submitModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-green" style="color: white;">
                <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                <h4 class="modal-title">
                    <i class="material-icons" style="vertical-align: middle;">send</i>
                    Submit for Approval
                </h4>
            </div>
            <form action="<?php echo base_url(); ?>/member/submit_draft" method="post">
                <div class="modal-body">
                    <input type="hidden" name="member_id" id="submit_member_id">
                    <div class="alert alert-info">
                        <i class="material-icons" style="vertical-align: middle;">info</i>
                        Are you sure you want to submit <strong id="submit_member_name"></strong> for approval?
                    </div>
                    <p>Once submitted, the application will move to <strong>Pending Approvals</strong> for review.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="material-icons">send</i> Submit for Approval
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-red" style="color: white;">
                <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                <h4 class="modal-title">
                    <i class="material-icons" style="vertical-align: middle;">warning</i>
                    Delete Draft
                </h4>
            </div>
            <form action="<?php echo base_url(); ?>/member/delete_draft" method="post">
                <div class="modal-body">
                    <input type="hidden" name="member_id" id="delete_member_id">
                    <div class="alert alert-warning">
                        <i class="material-icons" style="vertical-align: middle;">info</i>
                        Are you sure you want to delete draft for <strong id="delete_member_name"></strong>?
                    </div>
                    <p>This action cannot be undone. All draft data will be permanently removed.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="material-icons">delete_forever</i> Delete Draft
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DataTable Scripts -->
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/jquery.dataTables.js"></script>
<script
    src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>

<script>
    $(document).ready(function () {
        // Initialize DataTable
        $('#draftsTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[7, 'desc']], // Order by last modified date descending
            language: {
                search: "Search Drafts:",
                lengthMenu: "Show _MENU_ drafts per page",
                info: "Showing _START_ to _END_ of _TOTAL_ drafts",
                emptyTable: "No draft applications found"
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function () {
            $('#content_alert').fadeOut('slow');
        }, 5000);
    });

    // Submit draft function
    function submitDraft(memberId, memberName) {
        $('#submit_member_id').val(memberId);
        $('#submit_member_name').text(memberName);
        $('#submitModal').modal('show');
    }

    // Delete draft function
    function deleteDraft(memberId, memberName) {
        $('#delete_member_id').val(memberId);
        $('#delete_member_name').text(memberName);
        $('#deleteModal').modal('show');
    }
</script>