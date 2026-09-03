<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Priest Commission Receipt</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            margin: 0 auto;
        }

        .receipt {
            padding: 5mm;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }

        .temple-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .temple-address {
            font-size: 10px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }

        .info-section {
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .items-table {
            width: 100%;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            margin: 10px 0;
            padding: 5px 0;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .item-name {
            flex: 2;
        }

        .item-qty,
        .item-rate,
        .item-amount {
            flex: 1;
            text-align: right;
        }

        .total-section {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #000;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #000;
            font-size: 10px;
        }

        @media print {
            body {
                width: 80mm;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print();">
    <div class="receipt">
        <div class="header">
            <div class="temple-name"><?php echo strtoupper($temple_details['name']); ?></div>
            <div class="temple-address">
                <?php echo $temple_details['address1']; ?><br>
                <?php if (!empty($temple_details['address2']))
                    echo $temple_details['address2'] . '<br>'; ?>
                <?php echo $temple_details['city'] . ', ' . $temple_details['pincode']; ?><br>
                Tel: <?php echo $temple_details['phone']; ?>
            </div>
        </div>

        <div class="title">PRIEST COMMISSION RECEIPT</div>

        <div class="info-section">
            <div class="info-row">
                <span>Date:</span>
                <span><?php echo date('d-m-Y', strtotime($commission['date'])); ?></span>
            </div>
            <div class="info-row">
                <span>Receipt No:</span>
                <span>PC-<?php echo str_pad($commission['id'], 6, '0', STR_PAD_LEFT); ?></span>
            </div>
            <div class="info-row">
                <span>Priest:</span>
                <span><?php echo $commission['staff_name']; ?></span>
            </div>
        </div>

        <div class="items-table">
            <div class="item-row"
                style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 5px;">
                <div class="item-name">Service</div>
                <div class="item-qty">Qty</div>
                <div class="item-rate">Rate</div>
                <div class="item-amount">Amount</div>
            </div>

            <?php foreach ($details as $detail) { ?>
                <div class="item-row">
                    <div class="item-name"><?php echo $detail['archanai_name']; ?></div>
                    <div class="item-qty"><?php echo $detail['quantity']; ?></div>
                    <div class="item-rate"><?php echo number_format($detail['rate'], 2); ?></div>
                    <div class="item-amount"><?php echo number_format($detail['amount'], 2); ?></div>
                </div>
            <?php } ?>
        </div>

        <div class="total-section">
            <div class="total-row">
                <span>TOTAL COMMISSION:</span>
                <span>RM <?php echo number_format($commission['amount'], 2); ?></span>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for your service</p>
            <p>Printed on: <?php echo date('d-m-Y H:i:s'); ?></p>
        </div>
    </div>
</body>

</html>