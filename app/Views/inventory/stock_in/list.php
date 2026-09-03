<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Stock In</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8">
                                <h2>Stock In List</h2>
                            </div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/invstockin/add">
                                    <button type="button" class="btn bg-deep-purple waves-effect">New Stock In</button>
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
                                        <th>Doc No</th>
                                        <th>Date</th>
                                        <th>Location</th>
                                        <th>Supplier</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($list as $row) { ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><strong><?php echo $row['doc_no']; ?></strong></td>
                                            <td><?php echo date('d-M-Y', strtotime($row['doc_date'])); ?></td>
                                            <td><?php echo $row['location_name']; ?></td>
                                            <td><?php echo !empty($row['supplier_name']) ? $row['supplier_name'] : '-'; ?>
                                            </td>
                                            <td>RM <?php echo number_format($row['total_amount'], 2); ?></td>
                                            <td>
                                                <?php if ($row['status'] == 'draft') { ?>
                                                    <span class="label bg-orange">DRAFT</span>
                                                <?php } else if ($row['status'] == 'approved') { ?>
                                                        <span class="label bg-green">APPROVED</span>
                                                <?php } else { ?>
                                                        <span class="label bg-red">CANCELLED</span>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $row['created_by_name']; ?></td>
                                            <td>
                                                <a class="btn btn-success btn-rad" title="View"
                                                    href="<?= base_url() ?>/invstockin/view/<?php echo $row['id']; ?>"><i
                                                        class="material-icons">&#xE417;</i></a>

                                                <?php if ($row['status'] == 'draft') { ?>
                                                    <a class="btn btn-primary btn-rad" title="Edit"
                                                        href="<?= base_url() ?>/invstockin/edit/<?php echo $row['id']; ?>"><i
                                                            class="material-icons">&#xE3C9;</i></a>
                                                    <a class="btn btn-info btn-rad" title="Approve"
                                                        onclick="approve_modal(<?php echo $row['id']; ?>)"><i
                                                            class="material-icons">check_circle</i></a>
                                                    <a class="btn btn-danger btn-rad" title="Delete"
                                                        onclick="confirm_modal(<?php echo $row['id']; ?>)"><i
                                                            class="material-icons">&#xE872;</i></a>
                                                <?php } ?>

                                                <a class="btn btn-warning btn-rad" title="Print"
                                                    href="<?= base_url() ?>/invstockin/print/<?php echo $row['id']; ?>"
                                                    target="_blank"><i class="material-icons">print</i></a>
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

    <!-- Approve Modal -->
    <div id="approve-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="material-icons" style="font-size: 50px; color: #4CAF50;">check_circle</i>
                        <h4 class="mt-2">Approve Stock In</h4>
                        <p>Are you sure you want to approve this stock in?<br>Stock quantities will be updated.</p>
                        <a href="#" id="approve" class="btn btn-success my-3">Yes, Approve</a> &nbsp;
                        <button type="button" class="btn btn-danger my-3" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-information h1 text-info"></i>
                        <h4 class="mt-2">Delete Stock In</h4>
                        <p id="spndeddelid">Are you sure to Delete?</p>
                        <a href="#" id="del" class="btn btn-danger my-3" data-dismiss="modal">Yes</a> &nbsp;
                        <button type="button" class="btn btn-info my-3" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="delete-form"></div>
    <div id="approve-form"></div>
</section>

<script>
    function approve_modal(id) {
        $('#approve-modal').modal('show', { backdrop: 'static' });
        document.getElementById('approve').setAttribute('onclick', 'doApprove(' + id + ')');
    }

    function doApprove(id) {
        var act = "<?php echo base_url(); ?>/invstockin/approve/" + id;
        $("#approve-form").append("<form action='" + act + "'><button type='submit' id='approve" + id + "'>submit</button></form>");
        $("#approve" + id).trigger("click");
    }

    function confirm_modal(id) {
        $('#alert-modal').modal('show', { backdrop: 'static' });
        document.getElementById('del').setAttribute('onclick', 'dedDel(' + id + ')');
    }

    function dedDel(id) {
        var act = "<?php echo base_url(); ?>/invstockin/delete/" + id;
        $("#delete-form").append("<form action='" + act + "'><button type='submit' id='delete" + id + "'>submit</button></form>");
        $("#delete" + id).trigger("click");
    }
</script>