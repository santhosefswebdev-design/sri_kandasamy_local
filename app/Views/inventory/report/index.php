<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Inventory Reports</h2>
        </div>

        <div class="row clearfix">
            <!-- Stock Summary -->
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-pink">
                        <div class="font-bold m-b--35">STOCK SUMMARY</div>
                        <a href="<?php echo base_url(); ?>/invreport/stock_summary" class="btn btn-info waves-effect"
                            style="margin-top: 20px;">
                            <i class="material-icons">assessment</i> View Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stock Movement -->
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-cyan">
                        <div class="font-bold m-b--35">STOCK MOVEMENT</div>
                        <a href="<?php echo base_url(); ?>/invreport/stock_movement" class="btn btn-info waves-effect"
                            style="margin-top: 20px;">
                            <i class="material-icons">swap_horiz</i> View Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- Consumption Report -->
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-light-green">
                        <div class="font-bold m-b--35">CONSUMPTION REPORT</div>
                        <a href="<?php echo base_url(); ?>/invreport/consumption_report"
                            class="btn btn-info waves-effect" style="margin-top: 20px;">
                            <i class="material-icons">restaurant</i> View Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-orange">
                        <div class="font-bold m-b--35">LOW STOCK ALERT</div>
                        <a href="<?php echo base_url(); ?>/invreport/low_stock_alert" class="btn btn-info waves-effect"
                            style="margin-top: 20px;">
                            <i class="material-icons">warning</i> View Report
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <!-- Stock Valuation -->
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-purple">
                        <div class="font-bold m-b--35">STOCK VALUATION</div>
                        <a href="<?php echo base_url(); ?>/invreport/stock_valuation" class="btn btn-info waves-effect"
                            style="margin-top: 20px;">
                            <i class="material-icons">attach_money</i> View Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- Supplier Wise -->
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-deep-purple">
                        <div class="font-bold m-b--35">SUPPLIER WISE</div>
                        <a href="<?php echo base_url(); ?>/invreport/supplier_wise" class="btn btn-info waves-effect"
                            style="margin-top: 20px;">
                            <i class="material-icons">people</i> View Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- Location Wise -->
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-indigo">
                        <div class="font-bold m-b--35">LOCATION WISE</div>
                        <a href="<?php echo base_url(); ?>/invreport/location_wise" class="btn btn-info waves-effect"
                            style="margin-top: 20px;">
                            <i class="material-icons">location_on</i> View Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- Item Wise -->
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-blue">
                        <div class="font-bold m-b--35">ITEM WISE</div>
                        <a href="<?php echo base_url(); ?>/invreport/item_wise" class="btn btn-info waves-effect"
                            style="margin-top: 20px;">
                            <i class="material-icons">inventory_2</i> View Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .card .body {
        padding: 30px;
        min-height: 150px;
        color: #fff;
        text-align: center;
    }

    .font-bold {
        font-size: 18px;
        font-weight: bold;
    }
</style>