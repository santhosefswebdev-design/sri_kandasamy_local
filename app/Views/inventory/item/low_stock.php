<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Low Stock Alert</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8">
                                <h2>Items Below Reorder Level</h2>
                            </div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/invitem">
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
                                        <th>Available Stock</th>
                                        <th>Reorder Level</th>
                                        <th>Shortage</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($list as $row) { ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><strong><?php echo $row['item_code']; ?></strong></td>
                                            <td>
                                                <?php echo $row['name']; ?>
                                                <?php if (!empty($row['name_tamil'])) { ?>
                                                    <br><small><?php echo $row['name_tamil']; ?></small>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $row['category_name']; ?></td>
                                            <td><?php echo $row['uom_name']; ?></td>
                                            <td><span
                                                    class="badge bg-red"><?php echo number_format($row['available_stock'], 2); ?></span>
                                            </td>
                                            <td><?php echo number_format($row['reorder_level'], 2); ?></td>
                                            <td><span
                                                    class="badge bg-orange"><?php echo number_format($row['shortage'], 2); ?></span>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-rad" title="Create Purchase Order"
                                                    href="<?= base_url() ?>/invstockin/add?item_id=<?php echo $row['id']; ?>"><i
                                                        class="material-icons">add_shopping_cart</i></a>
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
</section>