<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Stock Transfer - View</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>Stock Transfer - <?php echo $data['doc_no']; ?></h2>
                            </div>
                            <div class="col-md-6" align="right">
                                <?php if ($data['status'] == 'draft') { ?>
                                    <a href="<?php echo base_url(); ?>/invstocktransfer/approve/<?php echo $data['id']; ?>">
                                        <button type="button" class="btn bg-green waves-effect">
                                            <i class="material-icons">check</i> Approve
                                        </button>
                                    </a>
                                <?php } ?>

                                <?php if ($data['status'] == 'approved') { ?>
                                    <a href="<?php echo base_url(); ?>/invstocktransfer/receive/<?php echo $data['id']; ?>">
                                        <button type="button" class="btn bg-blue waves-effect">
                                            <i class="material-icons">done_all</i> Receive
                                        </button>
                                    </a>
                                <?php } ?>

                                <a href="<?php echo base_url(); ?>/invstocktransfer/print_page/<?php echo $data['id']; ?>"
                                    target="_blank">
                                    <button type="button" class="btn bg-cyan waves-effect">
                                        <i class="material-icons">print</i> Print
                                    </button>
                                </a>

                                <a href="<?php echo base_url(); ?>/invstocktransfer">
                                    <button type="button" class="btn bg-deep-purple waves-effect">
                                        <i class="material-icons">list</i> List
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th width="40%">Document No:</th>
                                        <td><strong><?php echo $data['doc_no']; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Date:</th>
                                        <td><?php echo date('d-M-Y', strtotime($data['doc_date'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>From Location:</th>
                                        <td><?php echo $data['from_location_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>To Location:</th>
                                        <td><?php echo $data['to_location_name']; ?></td>
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
                                            <?php if ($data['status'] == 'received') { ?>
                                                <span class="label bg-green">RECEIVED</span>
                                            <?php } elseif ($data['status'] == 'approved') { ?>
                                                <span class="label bg-blue">APPROVED</span>
                                            <?php } elseif ($data['status'] == 'in_transit') { ?>
                                                <span class="label bg-cyan">IN TRANSIT</span>
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
                                                <th width="15%">Category</th>
                                                <th width="10%">UOM</th>
                                                <th width="10%">Quantity</th>
                                                <th width="10%">Received</th>
                                                <th width="10%">Batch No</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            $total_qty = 0;
                                            $total_received = 0;

                                            foreach ($items as $item) {
                                                $total_qty += $item['quantity'];
                                                $total_received += $item['received_qty'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $item['item_code']; ?></td>
                                                    <td><?php echo $item['item_name']; ?></td>
                                                    <td><?php echo $item['category_name']; ?></td>
                                                    <td><?php echo $item['uom_name']; ?></td>
                                                    <td class="text-right">
                                                        <?php echo number_format($item['quantity'], 2); ?></td>
                                                    <td class="text-right">
                                                        <?php echo number_format($item['received_qty'], 2); ?></td>
                                                    <td><?php echo !empty($item['batch_no']) ? $item['batch_no'] : '-'; ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot style="background: #f5f5f5; font-weight: bold;">
                                            <tr>
                                                <td colspan="5" class="text-right">Total:</td>
                                                <td class="text-right"><?php echo number_format($total_qty, 2); ?></td>
                                                <td class="text-right"><?php echo number_format($total_received, 2); ?>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($data['remarks'])) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <p><strong>Remarks:</strong> <?php echo $data['remarks']; ?></p>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($data['status'] == 'approved' || $data['status'] == 'received') { ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table">
                                        <tr>
                                            <th width="40%">Approved By:</th>
                                            <td><?php echo $data['approved_by_name']; ?></td>
                                        </tr>
                                    </table>
                                </div>

                                <?php if ($data['status'] == 'received') { ?>
                                    <div class="col-md-6">
                                        <table class="table">
                                            <tr>
                                                <th width="40%">Received By:</th>
                                                <td><?php echo $data['received_by_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Received Date:</th>
                                                <td><?php echo date('d-M-Y H:i', strtotime($data['received_date'])); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>