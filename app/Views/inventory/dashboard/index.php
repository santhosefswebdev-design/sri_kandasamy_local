<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Inventory Dashboard</h2>
        </div>

        <!-- Stats Cards -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">inventory_2</i>
                    </div>
                    <div class="content">
                        <div class="text">Total Items</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $stats['total_items']; ?>"
                            data-speed="1000" data-fresh-interval="20"><?php echo $stats['total_items']; ?></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">location_on</i>
                    </div>
                    <div class="content">
                        <div class="text">Locations</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $stats['total_locations']; ?>"
                            data-speed="1000" data-fresh-interval="20"><?php echo $stats['total_locations']; ?></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">people</i>
                    </div>
                    <div class="content">
                        <div class="text">Suppliers</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $stats['total_suppliers']; ?>"
                            data-speed="1000" data-fresh-interval="20"><?php echo $stats['total_suppliers']; ?></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">warning</i>
                    </div>
                    <div class="content">
                        <div class="text">Low Stock Items</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo count($low_stock); ?>"
                            data-speed="1000" data-fresh-interval="20"><?php echo count($low_stock); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <!-- Low Stock Alert -->
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>LOW STOCK ALERT</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Current Stock</th>
                                        <th>Min. Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($low_stock)) {
                                        foreach ($low_stock as $item) { ?>
                                            <tr>
                                                <td><?php echo $item['item_code']; ?></td>
                                                <td><?php echo $item['name']; ?></td>
                                                <td><span
                                                        class="badge bg-red"><?php echo number_format($item['total_stock'], 2); ?></span>
                                                </td>
                                                <td><?php echo number_format($item['minimum_stock'], 2); ?></td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No low stock items</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock by Location -->
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>STOCK BY LOCATION</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Items</th>
                                        <th>Total Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($location_stock)) {
                                        foreach ($location_stock as $loc) { ?>
                                            <tr>
                                                <td><?php echo $loc['name']; ?></td>
                                                <td><span class="label bg-blue"><?php echo strtoupper($loc['type']); ?></span>
                                                </td>
                                                <td><?php echo $loc['item_count']; ?></td>
                                                <td><?php echo number_format($loc['total_qty'], 2); ?></td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No data available</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Movements -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>RECENT STOCK MOVEMENTS</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Doc No</th>
                                        <th>Item</th>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Qty</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recent_movements)) {
                                        foreach ($recent_movements as $move) { ?>
                                            <tr>
                                                <td><?php echo date('d-M-Y', strtotime($move['doc_date'])); ?></td>
                                                <td><?php echo $move['doc_no']; ?></td>
                                                <td><?php echo $move['item_name']; ?>
                                                    <small>(<?php echo $move['item_code']; ?>)</small></td>
                                                <td><?php echo $move['location_name']; ?></td>
                                                <td>
                                                    <?php if ($move['movement_type'] == 'in') { ?>
                                                        <span class="label bg-green">IN</span>
                                                    <?php } else { ?>
                                                        <span class="label bg-red">OUT</span>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo number_format($move['quantity'], 2); ?></td>
                                                <td><?php echo number_format($move['balance_qty'], 2); ?></td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No recent movements</td>
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