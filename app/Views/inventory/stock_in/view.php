<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Stock In - View</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>Stock In Details - <?php echo $data['doc_no']; ?></h2>
                            </div>
                            <div class="col-md-6" align="right">
                                <?php if ($data['status'] == 'draft') { ?>
                                    <a href="<?php echo base_url(); ?>/invstockin/approve/<?php echo $data['id']; ?>">
                                        <button type="button" class="btn bg-green waves-effect">
                                            <i class="material-icons">check</i> Approve
                                        </button>
                                    </a>
                                <?php } ?>

                                <a href="<?php echo base_url(); ?>/invstockin/print/<?php echo $data['id']; ?>"
                                    target="_blank">
                                    <button type="button" class="btn bg-blue waves-effect">
                                        <i class="material-icons">print</i> Print
                                    </button>
                                </a>

                                <a href="<?php echo base_url(); ?>/invstockin">
                                    <button type="button" class="btn bg-deep-purple waves-effect">
                                        <i class="material-icons">list</i> List
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="body">
                        <!-- Header Info -->
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th width="40%">Document No:</th>
                                        <td><strong><?php echo $data['doc_no']; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Document Date:</th>
                                        <td><?php echo date('d-M-Y', strtotime($data['doc_date'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Location:</th>
                                        <td><?php echo $data['location_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Supplier:</th>
                                        <td><?php echo !empty($data['supplier_name']) ? $data['supplier_name'] : '-'; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th width="40%">Supplier Invoice:</th>
                                        <td><?php echo !empty($data['supplier_invoice_no']) ? $data['supplier_invoice_no'] : '-'; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Invoice Date:</th>
                                        <td><?php echo !empty($data['supplier_invoice_date']) ? date('d-M-Y', strtotime($data['supplier_invoice_date'])) : '-'; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Reference No:</th>
                                        <td><?php echo !empty($data['reference_no']) ? $data['reference_no'] : '-'; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            <?php if ($data['status'] == 'approved') { ?>
                                                <span class="label bg-green">APPROVED</span>
                                            <?php } elseif ($data['status'] == 'cancelled') { ?>
                                                <span class="label bg-red">CANCELLED</span>
                                            <?php } else { ?>
                                                <span class="label bg-orange">DRAFT</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Items</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead style="background: #f5f5f5;">
                                            <tr>
                                                <th width="5%">S.No</th>
                                                <th width="15%">Item Code</th>
                                                <th width="25%">Item Name</th>
                                                <th width="10%">Category</th>
                                                <th width="8%">UOM</th>
                                                <th width="10%">Quantity</th>
                                                <th width="10%">Rate</th>
                                                <th width="12%">Amount</th>
                                                <th width="10%">Batch No</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            $total_qty = 0;
                                            $total_amount = 0;
                                            foreach ($details as $item) {
                                                $total_qty += $item['quantity'];
                                                $total_amount += $item['amount'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $item['item_code']; ?></td>
                                                    <td><?php echo $item['item_name']; ?></td>
                                                    <td><?php echo $item['category_name']; ?></td>
                                                    <td><?php echo $item['uom_name']; ?></td>
                                                    <td align="right"><?php echo number_format($item['quantity'], 2); ?>
                                                    </td>
                                                    <td align="right"><?php echo number_format($item['rate'], 2); ?></td>
                                                    <td align="right"><?php echo number_format($item['amount'], 2); ?></td>
                                                    <td><?php echo !empty($item['batch_no']) ? $item['batch_no'] : '-'; ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot style="background: #f5f5f5; font-weight: bold;">
                                            <tr>
                                                <td colspan="5" align="right">Total:</td>
                                                <td align="right"><?php echo number_format($total_qty, 2); ?></td>
                                                <td></td>
                                                <td align="right">RM <?php echo number_format($total_amount, 2); ?></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Info -->
                        <div class="row">
                            <div class="col-md-6">
                                <?php if (!empty($data['remarks'])) { ?>
                                    <table class="table">
                                        <tr>
                                            <th>Remarks:</th>
                                            <td><?php echo $data['remarks']; ?></td>
                                        </tr>
                                    </table>
                                <?php } ?>
                            </div>

                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th width="40%">Created By:</th>
                                        <td><?php echo $data['created_by_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Created Date:</th>
                                        <td><?php echo date('d-M-Y H:i', strtotime($data['created'])); ?></td>
                                    </tr>
                                    <?php if ($data['status'] == 'approved') { ?>
                                        <tr>
                                            <th>Approved By:</th>
                                            <td><?php echo $data['approved_by_name']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Approved Date:</th>
                                            <td><?php echo date('d-M-Y H:i', strtotime($data['approved_date'])); ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        $("form").on("submit", function () {
            $("#loader").show();
        });
    });
</script>