<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Stock Details - <?php echo $location['name']; ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8">
                                <h2>Items in <?php echo $location['name']; ?></h2>
                            </div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/invlocation">
                                    <button type="button" class="btn bg-deep-purple waves-effect">Back to List</button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>UOM</th>
                                        <th>Total Qty</th>
                                        <th>Reserved Qty</th>
                                        <th>Available Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $total_items = 0;
                                    $total_qty = 0;
                                    $total_available = 0;
                                    $total_reserved = 0;

                                    foreach ($items as $row) {
                                        $total_items++;
                                        $total_qty += $row['quantity'];
                                        $total_available += $row['available_qty'];
                                        $total_reserved += $row['reserved_qty'];
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $row['item_code']; ?></td>
                                            <td>
                                                <strong><?php echo $row['name']; ?></strong>
                                                <?php if (!empty($row['name_tamil'])) { ?>
                                                    <br><small><?php echo $row['name_tamil']; ?></small>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $row['category_name']; ?></td>
                                            <td><?php echo $row['uom_name']; ?></td>
                                            <td><?php echo number_format($row['quantity'], 2); ?></td>
                                            <td><?php echo number_format($row['reserved_qty'], 2); ?></td>
                                            <td><?php echo number_format($row['available_qty'], 2); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr style="background: #f5f5f5; font-weight: bold;">
                                        <td colspan="5" align="right">Total:</td>
                                        <td><?php echo number_format($total_qty, 2); ?></td>
                                        <td><?php echo number_format($total_reserved, 2); ?></td>
                                        <td><?php echo number_format($total_available, 2); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>