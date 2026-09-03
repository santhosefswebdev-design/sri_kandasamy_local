<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/typicons/typicons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/mdi/css/materialdesignicons.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/css/vertical-layout-light/style.css">

<style>
    body {
        height: 100vh;
        width: 100%;
    }

    .back-btn {
        background: #6c757d;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 15px;
        height: auto;
    }

    .back-btn:hover {
        background: #5a6268;
        color: white;
        text-decoration: none;
    }

    .print-btn {
        background: #FF9800;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 15px;
        height: auto;
        margin-left: 10px;
    }

    .print-btn:hover {
        background: #e68900;
        color: white;
        text-decoration: none;
    }

    .info-row {
        margin-bottom: 15px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }

    .info-label {
        font-weight: bold;
        color: #495057;
    }

    .total-section {
        background: #4CAF50;
        color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        margin-top: 20px;
    }

    .table th {
        background: #f8f9fa;
    }
</style>

<body class="sidebar-icon-only">
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="mb-3">
                            <a href="<?php echo base_url(); ?>/commission_online" class="back-btn">
                                <i class="mdi mdi-arrow-left"></i> Back to List
                            </a>
                            <!-- <a href="<?php echo base_url(); ?>/commission_online/print_receipt/<?php echo $commission['id']; ?>"
                                                            target="_blank" class="print-btn">
                                                            <i class="mdi mdi-printer"></i> Print Receipt
                                                        </a> -->
                        </div>
                        <div class="col-12 stretch-card">


                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Commission Details</h4>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <span class="info-label">Commission ID:</span>
                                                <span>#<?php echo str_pad($commission['id'], 5, '0', STR_PAD_LEFT); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <span class="info-label">Date:</span>
                                                <span><?php echo date('d-m-Y', strtotime($commission['date'])); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <span class="info-label">Priest Name:</span>
                                                <span><?php echo $commission['staff_name']; ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <span class="info-label">Type:</span>
                                                <span><?php echo $commission['type']; ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="info-row">
                                                <span class="info-label">Created Date:</span>
                                                <span><?php echo date('d-m-Y h:i A', strtotime($commission['created'])); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="mt-4 mb-3">Service Details</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Service Name</th>
                                                    <th>Quantity</th>
                                                    <th>Rate (RM)</th>
                                                    <th>Amount (RM)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($details)) {
                                                    $i = 1;
                                                    foreach ($details as $detail) { ?>
                                                        <tr>
                                                            <td><?php echo $i++; ?></td>
                                                            <td><?php echo $detail['archanai_name']; ?></td>
                                                            <td><?php echo $detail['quantity']; ?></td>
                                                            <td><?php echo number_format($detail['rate'], 2); ?></td>
                                                            <td><strong><?php echo number_format($detail['amount'], 2); ?></strong>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">No details available</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="total-section">
                                        <h5 style="margin-bottom: 10px; color: white;">Total Commission</h5>
                                        <h2 style="margin: 0; color: white;">RM
                                            <?php echo number_format($commission['amount'], 2); ?>
                                        </h2>
                                    </div>

                                    <?php if (!empty($commission['remarks'])) { ?>
                                        <div class="mt-4">
                                            <h5>Remarks:</h5>
                                            <p class="text-muted"><?php echo $commission['remarks']; ?></p>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/vendors/js/vendor.bundle.base.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/js/off-canvas.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/js/hoverable-collapse.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/js/template.js"></script>
</body>