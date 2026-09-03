<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Stock In - <?php echo $data['doc_no']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 24px;
        }

        .header p {
            margin: 3px 0;
            font-size: 11px;
        }

        .doc-info {
            width: 100%;
            margin-bottom: 20px;
        }

        .doc-info table {
            width: 100%;
        }

        .doc-info td {
            padding: 5px;
            vertical-align: top;
        }

        .doc-info .left {
            width: 50%;
        }

        .doc-info .right {
            width: 50%;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        .items-table td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        .items-table tfoot td {
            background: #f5f5f5;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .signature {
            width: 30%;
            display: inline-block;
            text-align: center;
            margin-top: 60px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }

        @media print {
            body {
                padding: 10px;
            }

            .no-print {
                display: none;
            }
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 14px;
        }

        .status-approved {
            background: #4CAF50;
            color: white;
        }

        .status-draft {
            background: #FF9800;
            color: white;
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: right; margin-bottom: 10px;">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #2196F3; color: white; border: none; cursor: pointer; border-radius: 3px;">
            <i class="material-icons" style="vertical-align: middle;">print</i> Print
        </button>
        <button onclick="window.close()"
            style="padding: 10px 20px; background: #f44336; color: white; border: none; cursor: pointer; border-radius: 3px; margin-left: 10px;">
            Close
        </button>
    </div>

    <!-- Header -->
    <div class="header">
        <?php if (!empty($temple['logo'])) { ?>
            <img src="<?php echo base_url(); ?>/uploads/<?php echo $temple['logo']; ?>" style="height: 60px;">
        <?php } ?>
        <h2><?php echo !empty($temple['name']) ? $temple['name'] : 'Temple Name'; ?></h2>
        <p><?php echo !empty($temple['address1']) ? $temple['address1'] : ''; ?></p>
        <p><?php echo !empty($temple['city']) ? $temple['city'] : ''; ?>
            <?php echo !empty($temple['state']) ? $temple['state'] : ''; ?></p>
        <p>Tel: <?php echo !empty($temple['mobile']) ? $temple['mobile'] : ''; ?></p>
    </div>

    <h3 style="text-align: center; margin: 20px 0;">STOCK IN</h3>

    <!-- Document Info -->
    <div class="doc-info">
        <table>
            <tr>
                <td class="left">
                    <strong>Document No:</strong> <?php echo $data['doc_no']; ?><br>
                    <strong>Document Date:</strong> <?php echo date('d-M-Y', strtotime($data['doc_date'])); ?><br>
                    <strong>Location:</strong> <?php echo $data['location_name']; ?><br>
                    <strong>Reference No:</strong>
                    <?php echo !empty($data['reference_no']) ? $data['reference_no'] : '-'; ?>
                </td>
                <td class="right" style="text-align: right;">
                    <strong>Status:</strong>
                    <span class="status-badge status-<?php echo $data['status']; ?>">
                        <?php echo strtoupper($data['status']); ?>
                    </span><br><br>

                    <?php if (!empty($data['supplier_name'])) { ?>
                        <strong>Supplier:</strong><br>
                        <?php echo $data['supplier_name']; ?><br>
                        <?php echo !empty($data['address1']) ? $data['address1'] : ''; ?><br>
                        <?php echo !empty($data['city']) ? $data['city'] : ''; ?><br>
                        Tel: <?php echo !empty($data['phone']) ? $data['phone'] : ''; ?><br>
                        <?php if (!empty($data['gst_no'])) { ?>
                            GST: <?php echo $data['gst_no']; ?>
                        <?php } ?>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </div>

    <?php if (!empty($data['supplier_invoice_no'])) { ?>
        <p style="margin: 10px 0;">
            <strong>Supplier Invoice No:</strong> <?php echo $data['supplier_invoice_no']; ?>
            <?php if (!empty($data['supplier_invoice_date'])) { ?>
                | <strong>Date:</strong> <?php echo date('d-M-Y', strtotime($data['supplier_invoice_date'])); ?>
            <?php } ?>
        </p>
    <?php } ?>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">S.No</th>
                <th width="12%">Item Code</th>
                <th width="28%">Item Name</th>
                <th width="12%">Category</th>
                <th width="8%">UOM</th>
                <th width="10%" class="text-right">Quantity</th>
                <th width="10%" class="text-right">Rate (RM)</th>
                <th width="15%" class="text-right">Amount (RM)</th>
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
                    <td class="text-center"><?php echo $i++; ?></td>
                    <td><?php echo $item['item_code']; ?></td>
                    <td><?php echo $item['item_name']; ?>
                        <?php if (!empty($item['batch_no'])) { ?>
                            <br><small><strong>Batch:</strong> <?php echo $item['batch_no']; ?></small>
                        <?php } ?>
                        <?php if (!empty($item['expiry_date'])) { ?>
                            <br><small><strong>Expiry:</strong>
                                <?php echo date('d-M-Y', strtotime($item['expiry_date'])); ?></small>
                        <?php } ?>
                    </td>
                    <td><?php echo $item['category_name']; ?></td>
                    <td class="text-center"><?php echo $item['uom_name']; ?></td>
                    <td class="text-right"><?php echo number_format($item['quantity'], 2); ?></td>
                    <td class="text-right"><?php echo number_format($item['rate'], 2); ?></td>
                    <td class="text-right"><?php echo number_format($item['amount'], 2); ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>Total:</strong></td>
                <td class="text-right"><strong><?php echo number_format($total_qty, 2); ?></strong></td>
                <td></td>
                <td class="text-right"><strong><?php echo number_format($total_amount, 2); ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Amount in Words -->
    <p style="margin: 10px 0;">
        <strong>Amount in Words:</strong>
        <?php
        // You can create a helper function for this
        echo ucwords(strtolower('Ringgit ' . number_format($total_amount, 2) . ' Only'));
        ?>
    </p>

    <?php if (!empty($data['remarks'])) { ?>
        <p style="margin: 10px 0;">
            <strong>Remarks:</strong> <?php echo $data['remarks']; ?>
        </p>
    <?php } ?>

    <!-- Footer -->
    <div class="footer">
        <div style="width: 100%;">
            <div class="signature">
                <div class="signature-line">Prepared By</div>
                <p><?php echo $data['created_by_name']; ?></p>
            </div>

            <?php if ($data['status'] == 'approved') { ?>
                <div class="signature">
                    <div class="signature-line">Approved By</div>
                    <p><?php echo $data['approved_by_name']; ?></p>
                </div>
            <?php } ?>

            <div class="signature">
                <div class="signature-line">Received By</div>
            </div>
        </div>
    </div>

    <p style="text-align: center; margin-top: 30px; font-size: 10px; color: #666;">
        Printed on <?php echo date('d-M-Y H:i:s'); ?> | System Generated Document
    </p>
</body>

</html>

<script>
    // Auto print when page loads
    window.onload = function () {
        // Uncomment the line below if you want auto print
        // window.print();
    }
</script>