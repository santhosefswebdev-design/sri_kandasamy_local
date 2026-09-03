<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Stock Out</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8">
                                <h2>Stock Out List</h2>
                            </div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/invstockout/add">
                                    <button type="button" class="btn bg-deep-purple waves-effect">
                                        <i class="material-icons">add</i> Add Stock Out
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
                                        <th>Doc No</th>
                                        <th>Date</th>
                                        <th>Location</th>
                                        <th>Out Type</th>
                                        <th>Department</th>
                                        <th>Issued To</th>
                                        <th>Amount</th>
                                        <th>Status</th>
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
                                            <td><span
                                                    class="label bg-purple"><?php echo strtoupper($row['out_type']); ?></span>
                                            </td>
                                            <td><?php echo !empty($row['department']) ? strtoupper($row['department']) : '-'; ?>
                                            </td>
                                            <td><?php echo !empty($row['issued_to']) ? $row['issued_to'] : '-'; ?></td>
                                            <td>RM <?php echo number_format($row['total_amount'], 2); ?></td>
                                            <td>
                                                <?php if ($row['status'] == 'approved') { ?>
                                                    <span class="label bg-green">APPROVED</span>
                                                <?php } elseif ($row['status'] == 'cancelled') { ?>
                                                    <span class="label bg-red">CANCELLED</span>
                                                <?php } else { ?>
                                                    <span class="label bg-orange">DRAFT</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-success btn-rad" title="View"
                                                    href="<?= base_url() ?>/invstockout/view/<?php echo $row['id']; ?>">
                                                    <i class="material-icons">&#xE417;</i>
                                                </a>
                                                <a class="btn btn-info btn-rad" title="Print"
                                                    href="<?= base_url() ?>/invstockout/print_page/<?php echo $row['id']; ?>"
                                                    target="_blank">
                                                    <i class="material-icons">print</i>
                                                </a>
                                                <?php if ($row['status'] == 'draft') { ?>
                                                    <a class="btn btn-danger btn-rad" title="Delete"
                                                        onclick="confirm_modal(<?php echo $row['id']; ?>)">
                                                        <i class="material-icons">&#xE872;</i>
                                                    </a>
                                                <?php } ?>
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

    <!-- Delete Modal -->
    <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-information h1 text-info"></i>
                        <h4 class="mt-2">Delete Stock Out</h4>
                        <p id="spndeddelid">Are you sure to Delete?</p>
                        <a href="#" id="del" class="btn btn-danger my-3" data-dismiss="modal">Yes</a> &nbsp;
                        <button type="button" class="btn btn-info my-3" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="delete-form"></div>
</section>

<script>
    function confirm_modal(id) {
        $('#alert-modal').modal('show', { backdrop: 'static' });
        document.getElementById('del').setAttribute('onclick', 'dedDel(' + id + ')');
    }

    function dedDel(id) {
        var act = "<?php echo base_url(); ?>/invstockout/delete/" + id;
        $("#delete-form").append("<form action='" + act + "'><button type='submit' id='delete" + id + "'>submit</button></form>");
        $("#delete" + id).trigger("click");
    }
</script>