<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Stock Adjustment - View</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>Stock Adjustment Details - <?php echo $data['doc_no']; ?></h2>
                            </div>
                            <div class="col-md-6" align="right">
                                <?php if ($data['status'] == 'draft') { ?>
                                    <a href="<?php echo base_url(); ?>/invstockadjustment/approve/<?php echo $data['id']; ?>"
                                        onclick="return confirm('Are you sure to approve this adjustment? This will update the stock quantities.')">
                                        <button type="button" class="btn bg-green waves-effect">
                                            <i class="material-icons">check</i> Approve
                                        </button>
                                    </a>
                                <?php } ?>

                                <a href="<?php echo base_url(); ?>/invstockadjustment/print_page/<?php echo $data['id']; ?>"
                                    target="_blank">
                                    <button type="button" class="btn bg-blue waves-effect">
                                        <i class="material-icons">print</i> Print
                                    </button>
                                </a>

                                <a href="<?php echo base_url(); ?>/invstockadjustment">
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
                                        <th>Adjustment Type:</th>
                                        <td><span
                                                class="label bg-blue"><?php echo strtoupper(str_replace('_', ' ', $data['adjustment_type'])); ?></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th width="40%">Reference No:</th>
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
                                    <tr>
                                        <th>Created By:</th>
                                        <td><?php echo $data['created_by_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Created Date:</th>
                                        <td><?php echo date('d-M-Y H:i', strtotime($data['created'])); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <?php if (!empty($data['remarks'])) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <p><strong>Remarks:</strong> <?php echo $data['remarks']; ?></p>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Items Table -->
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Adjustment Items</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead style="background: #f5f5f5;">
                                            <tr>
                                                <th width="5%">S.No</th>
                                                <th width="12%">Item Code</th>
                                                <th width="25%">Item Name</th>
                                                <th width="12%">Category</th>
                                                <th width="8%">UOM</th>
                                                <th width="10%">System Qty</th>
                                                <th width="10%">Physical Qty</th>
                                                <th width="10%">Difference</th>
                                                <th width="10%">Rate</th>
                                                <th width="12%">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            $total_positive = 0;
                                            $total_negative = 0;
                                            foreach ($items as $item) {
                                                if ($item['difference_qty'] > 0) {
                                                    $total_positive += $item['amount'];
                                                } else {
                                                    $total_negative += $item['amount'];
                                                }
                                                ?>
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $item['item_code']; ?></td>
                                                    <td><?php echo $item['item_name']; ?></td>
                                                    <td><?php echo $item['category_name']; ?></td>
                                                    <td><?php echo $item['uom_name']; ?></td>
                                                    <td align="right"><?php echo number_format($item['system_qty'], 2); ?>
                                                    </td>
                                                    <td align="right"><?php echo number_format($item['physical_qty'], 2); ?>
                                                    </td>
                                                    <td align="right"
                                                        style="color: <?php echo $item['difference_qty'] > 0 ? 'green' : 'red'; ?>; font-weight: bold;">
                                                        <?php echo number_format($item['difference_qty'], 2); ?>
                                                    </td>
                                                    <td align="right"><?php echo number_format($item['rate'], 2); ?></td>
                                                    <td align="right"
                                                        style="color: <?php echo $item['difference_qty'] > 0 ? 'green' : 'red'; ?>; font-weight: bold;">
                                                        <?php echo number_format($item['amount'], 2); ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot style="background: #f5f5f5;">
                                            <tr>
                                                <td colspan="9" align="right"><strong>Total Increase Value:</strong>
                                                </td>
                                                <td align="right" style="color: green; font-weight: bold;">
                                                    RM <?php echo number_format($total_positive, 2); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="9" align="right"><strong>Total Decrease Value:</strong>
                                                </td>
                                                <td align="right" style="color: red; font-weight: bold;">
                                                    RM <?php echo number_format($total_negative, 2); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="9" align="right"><strong>Net Adjustment Value:</strong>
                                                </td>
                                                <td align="right" style="font-weight: bold; font-size: 16px;">
                                                    RM
                                                    <?php echo number_format(abs($total_positive - $total_negative), 2); ?>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Info -->
                        <?php if ($data['status'] == 'approved') { ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table">
                                        <tr>
                                            <th width="40%">Approved By:</th>
                                            <td><?php echo $data['approved_by_name']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Approved Date:</th>
                                            <td><?php echo date('d-M-Y H:i', strtotime($data['approved_date'])); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>