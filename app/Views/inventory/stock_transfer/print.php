<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Stock Transfer - <?php echo $data['doc_no']; ?></title>
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

        .location-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            background: #f9f9f9;
        }

        .location-box h4 {
            margin: 0 0 10px 0;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
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

        .status-received {
            background: #4CAF50;
            color: white;
        }

        .status-approved {
            background: #2196F3;
            color: white;
        }

        .status-in_transit {
            background: #00BCD4;
            color: white;
        }

        .status-draft {
            background: #FF9800;
            color: white;
        }

        .status-cancelled {
            background: #f44336;
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
        <?php if (!empty($temple['image'])) { ?>
            <img src="<?php echo base_url(); ?>/uploads/<?php echo $temple['image']; ?>" style="height: 60px;">
        <?php } ?>
        <h2><?php echo !empty($temple['name']) ? $temple['name'] : 'Temple Name'; ?></h2>
        <p><?php echo !empty($temple['address1']) ? $temple['address1'] : ''; ?></p>
        <p><?php echo !empty($temple['city']) ? $temple['city'] : ''; ?>
            <?php echo !empty($temple['state']) ? ', ' . $temple['state'] : ''; ?>
        </p>
        <p>Tel: <?php echo !empty($temple['mobile']) ? $temple['mobile'] : ''; ?></p>
    </div>

    <h3 style="text-align: center; margin: 20px 0;">STOCK TRANSFER</h3>

    <!-- Document Info -->
    <div class="doc-info">
        <table>
            <tr>
                <td class="left">
                    <strong>Document No:</strong> <?php echo $data['doc_no']; ?><br>
                    <strong>Transfer Date:</strong> <?php echo date('d-M-Y', strtotime($data['doc_date'])); ?><br>
                    <strong>Reference No:</strong>
                    <?php echo !empty($data['reference_no']) ? $data['reference_no'] : '-'; ?>
                </td>
                <td class="right" style="text-align: right;">
                    <strong>Status:</strong>
                    <span class="status-badge status-<?php echo $data['status']; ?>">
                        <?php echo strtoupper(str_replace('_', ' ', $data['status'])); ?>
                    </span><br><br>
                    <strong>Created By:</strong> <?php echo $data['created_by_name']; ?><br>
                    <strong>Created Date:</strong>
                    <?php echo date('d-M-Y H:i', strtotime($data['created'])); ?>
                </td>
            </tr>
        </table>
    </div>

    <!-- Location Details -->
    <div style="width: 100%; margin-bottom: 20px;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 48%; vertical-align: top;">
                    <div class="location-box">
                        <h4>📤 FROM LOCATION</h4>
                        <strong><?php echo $data['from_location_name']; ?></strong><br>
                        <?php if (!empty($data['from_location_address'])) { ?>
                            <?php echo $data['from_location_address']; ?>
                        <?php } ?>
                    </div>
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 48%; vertical-align: top;">
                    <div class="location-box">
                        <h4>📥 TO LOCATION</h4>
                        <strong><?php echo $data['to_location_name']; ?></strong><br>
                        <?php if (!empty($data['to_location_address'])) { ?>
                            <?php echo $data['to_location_address']; ?>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">S.No</th>
                <th width="12%">Item Code</th>
                <th width="30%">Item Name</th>
                <th width="15%">Category</th>
                <th width="8%">UOM</th>
                <th width="12%" class="text-right">Quantity</th>
                <th width="12%" class="text-right">Received</th>
                <th width="6%">Batch</th>
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
                    <td class="text-center"><?php echo $i++; ?></td>
                    <td><?php echo $item['item_code']; ?></td>
                    <td><?php echo $item['item_name']; ?>
                        <?php if (!empty($item['remarks'])) { ?>
                            <br><small style="color: #666;"><em><?php echo $item['remarks']; ?></em></small>
                        <?php } ?>
                    </td>
                    <td><?php echo $item['category_name']; ?></td>
                    <td class="text-center"><?php echo $item['uom_name']; ?></td>
                    <td class="text-right"><?php echo number_format($item['quantity'], 2); ?></td>
                    <td class="text-right">
                        <?php if ($data['status'] == 'received') { ?>
                            <strong style="color: #4CAF50;"><?php echo number_format($item['received_qty'], 2); ?></strong>
                        <?php } else { ?>
                            <?php echo number_format($item['received_qty'], 2); ?>
                        <?php } ?>
                    </td>
                    <td class="text-center"><?php echo !empty($item['batch_no']) ? $item['batch_no'] : '-'; ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>Total:</strong></td>
                <td class="text-right"><strong><?php echo number_format($total_qty, 2); ?></strong></td>
                <td class="text-right"><strong><?php echo number_format($total_received, 2); ?></strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

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

            <?php if ($data['status'] == 'approved' || $data['status'] == 'received') { ?>
                <div class="signature">
                    <div class="signature-line">Approved By</div>
                    <p><?php echo $data['approved_by_name']; ?></p>
                </div>
            <?php } ?>

            <?php if ($data['status'] == 'received') { ?>
                <div class="signature">
                    <div class="signature-line">Received By</div>
                    <p><?php echo $data['received_by_name']; ?></p>
                    <p style="font-size: 10px;"><?php echo date('d-M-Y H:i', strtotime($data['received_date'])); ?></p>
                </div>
            <?php } else { ?>
                <div class="signature">
                    <div class="signature-line">Received By</div>
                </div>
            <?php } ?>
        </div>
    </div>

    <p style="text-align: center; margin-top: 30px; font-size: 10px; color: #666;">
        Printed on <?php echo date('d-M-Y H:i:s'); ?> | System Generated Document
    </p>

    <?php if ($data['status'] == 'draft') { ?>
        <p style="text-align: center; color: #FF9800; font-weight: bold;">
            ⚠️ THIS IS A DRAFT DOCUMENT - NOT VALID FOR OFFICIAL USE
        </p>
    <?php } ?>
</body>

</html>

<script>
    // Auto print when page loads (optional)
    window.onload = function () {
        // Uncomment the line below if you want auto print
        // window.print();
    }
</script>