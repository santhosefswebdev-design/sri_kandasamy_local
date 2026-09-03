<!DOCTYPE html>
<html>

<head>
    <title>Consumption Entry - <?php echo $data['doc_no']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 5px 0;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 5px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .items-table th {
            background: #f0f0f0;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="margin-bottom: 10px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px;">Print</button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 14px;">Close</button>
    </div>

    <div class="header">
        <h2><?php echo strtoupper($temple['temple_name']); ?></h2>
        <p><?php echo $temple['address1']; ?>, <?php echo $temple['city']; ?></p>
        <h3>CONSUMPTION ENTRY</h3>
    </div>

    <table class="info-table">
        <tr>
            <td width="20%"><strong>Doc No:</strong></td>
            <td width="30%"><?php echo $data['doc_no']; ?></td>
            <td width="20%"><strong>Date:</strong></td>
            <td width="30%"><?php echo date('d-M-Y', strtotime($data['doc_date'])); ?></td>
        </tr>
        <tr>
            <td><strong>Type:</strong></td>
            <td><?php echo strtoupper($data['consumption_type']); ?></td>
            <td><strong>Location:</strong></td>
            <td><?php echo $data['location_name']; ?></td>
        </tr>
        <tr>
            <td><strong>Reference No:</strong></td>
            <td><?php echo $data['reference_no']; ?></td>
            <td><strong>Status:</strong></td>
            <td><?php echo strtoupper($data['status']); ?></td>
        </tr>
        <?php if (!empty($data['remarks'])) { ?>
            <tr>
                <td><strong>Remarks:</strong></td>
                <td colspan="3"><?php echo $data['remarks']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="10%">S.No</th>
                <th width="15%">Item Code</th>
                <th width="35%">Item Name</th>
                <th width="15%">Category</th>
                <th width="10%">UOM</th>
                <th width="15%" class="text-right">Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($details as $item) { ?>
                <tr>
                    <td class="text-center"><?php echo $i++; ?></td>
                    <td><?php echo $item['item_code']; ?></td>
                    <td><?php echo $item['item_name']; ?></td>
                    <td><?php echo $item['category_name']; ?></td>
                    <td><?php echo $item['uom_name']; ?></td>
                    <td class="text-right"><?php echo number_format($item['quantity'], 2); ?></td>
                </tr>
                <?php if (!empty($item['remarks'])) { ?>
                    <tr>
                        <td colspan="6" style="padding-left: 30px; font-style: italic; background: #f9f9f9;">
                            Remarks: <?php echo $item['remarks']; ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <table style="width: 100%;">
            <tr>
                <td width="33%" style="text-align: center;">
                    <br><br><br>
                    ___________________<br>
                    Prepared By
                </td>
                <td width="33%" style="text-align: center;">
                    <br><br><br>
                    ___________________<br>
                    Checked By
                </td>
                <td width="33%" style="text-align: center;">
                    <br><br><br>
                    ___________________<br>
                    Approved By
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 20px; text-align: center; font-size: 10px;">
        <p>Printed on: <?php echo date('d-M-Y h:i A'); ?></p>
    </div>
</body>

</html>