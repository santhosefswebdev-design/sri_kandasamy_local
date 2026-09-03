<?php
// ================================
// CREATE: app/Views/frontend/prasadam/pdf_whatsapp_template.php
// ================================
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Prasadam Booking Receipt - <?php echo $data['ref_no']; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 15px;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #d4aa00;
            padding-bottom: 20px;
        }

        .temple-name {
            font-size: 24px;
            font-weight: bold;
            color: #d4aa00;
            margin-bottom: 5px;
        }

        .temple-address {
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }

        .receipt-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 15px;
        }

        .booking-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 35%;
            padding: 8px 0;
            font-weight: bold;
            border-bottom: 1px dotted #ccc;
        }

        .info-value {
            display: table-cell;
            width: 65%;
            padding: 8px 0 8px 20px;
            border-bottom: 1px dotted #ccc;
        }

        .items-section {
            margin: 25px 0;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #d4aa00;
            margin-bottom: 10px;
            border-bottom: 1px solid #d4aa00;
            padding-bottom: 5px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
            /* Fixed table layout for consistent column widths */
        }

        .items-table th {
            background-color: #f7ebbb;
            border: 1px solid #d4aa00;
            padding: 6px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            word-wrap: break-word;
        }

        .items-table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            font-size: 10px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .total-section {
            background-color: #f7ebbb;
            padding: 15px;
            border: 2px solid #d4aa00;
            margin: 20px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .total-label {
            font-weight: bold;
        }

        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #d4aa00;
            border-top: 2px solid #d4aa00;
            padding-top: 10px;
            margin-top: 10px;
        }

        .payment-info {
            margin-top: 20px;
            background-color: #f0f8ff;
            padding: 15px;
            border-left: 4px solid #007bff;
        }

        .collection-info {
            margin-top: 20px;
            background-color: #fff8dc;
            padding: 15px;
            border-left: 4px solid #ffa500;
        }

        .note {
            margin-top: 20px;
            font-size: 10px;
            color: #666;
            font-style: italic;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .whatsapp-note {
            background-color: #e8f5e8;
            border: 1px solid #25d366;
            padding: 10px;
            margin: 15px 0;
            font-size: 11px;
            text-align: center;
        }

        .qr-section {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="temple-name"><?php echo $temp_details['name']; ?></div>
            <div class="temple-address">
                <?php echo $temp_details['address1']; ?><br>
                <?php echo $temp_details['address2']; ?>, <?php echo $temp_details['city']; ?> <?php echo $temp_details['postcode']; ?><br>
                Tel: <?php echo $temp_details['phone']; ?> | Email: <?php echo $temp_details['email']; ?>
            </div>
            <div class="receipt-title">PRASADAM BOOKING RECEIPT</div>
        </div>

        <!-- Booking Information -->
        <div class="booking-info">
            <div class="info-row">
                <div class="info-label">Reference Number:</div>
                <div class="info-value"><?php echo $data['ref_no']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Customer Name:</div>
                <div class="info-value"><?php echo $data['customer_name']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Mobile Number:</div>
                <div class="info-value"><?php echo $data['mobile_no']; ?></div>
            </div>
            <?php if (!empty($data['email_id'])): ?>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value"><?php echo $data['email_id']; ?></div>
                </div>
            <?php endif; ?>
            <div class="info-row">
                <div class="info-label">Booking Date:</div>
                <div class="info-value"><?php echo date('d M Y', strtotime($data['date'])); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Collection Date:</div>
                <div class="info-value"><?php echo date('d M Y', strtotime($data['collection_date'])); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Collection Time:</div>
                <div class="info-value"><?php echo $data['serve_time']; ?> (<?php echo $data['session']; ?>)</div>
            </div>
            <?php if (!empty($data['diety_name'])): ?>
                <div class="info-row">
                    <div class="info-label">Deity:</div>
                    <div class="info-value"><?php echo $data['diety_name']; ?></div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Booked Items -->
        <div class="items-section">
            <div class="section-title">BOOKED ITEMS</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 8%">#</th>
                        <th style="width: 52%">Item Description</th>
                        <th style="width: 12%">Qty</th>
                        <th style="width: 14%">Rate (RM)</th>
                        <th style="width: 14%">Amount (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sr_no = 1;
                    $subtotal = 0;
                    foreach ($booking_details as $item):
                        $item_total = $item['quantity'] * $item['amount'];
                        $subtotal += $item_total;
                    ?>
                        <tr>
                            <td style="text-align: center; vertical-align: middle;"><?php echo $sr_no++; ?></td>
                            <td style="vertical-align: middle;">
                                <strong><?php echo $item['name_eng']; ?></strong><br>
                                <small style="color: #666;"><?php echo $item['name_tamil']; ?></small>
                            </td>
                            <td style="text-align: center; vertical-align: middle;"><?php echo $item['quantity']; ?></td>
                            <td style="text-align: right; vertical-align: middle;"><?php echo number_format($item['amount'], 2); ?></td>
                            <td style="text-align: right; vertical-align: middle;"><?php echo number_format($item_total, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <span class="total-label">Subtotal:</span>
                <span>RM <?php echo number_format($data['sub_total'], 2); ?></span>
            </div>

            <?php if (!empty($data['discount_amount']) && $data['discount_amount'] > 0): ?>
                <div class="total-row">
                    <span class="total-label">Discount:</span>
                    <span>- RM <?php echo number_format($data['discount_amount'], 2); ?></span>
                </div>
            <?php endif; ?>

            <div class="total-row grand-total">
                <span class="total-label">GRAND TOTAL:</span>
                <span>RM <?php echo number_format($data['total_amount'], 2); ?></span>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="payment-info">
            <div class="section-title">PAYMENT INFORMATION</div>
            <?php if (!empty($pay_details)): ?>
                <?php foreach ($pay_details as $payment): ?>
                    <div class="total-row">
                        <span><?php echo $payment['payment_mode_title']; ?>:</span>
                        <span>RM <?php echo number_format($payment['amount'], 2); ?></span>
                    </div>
                    <div class="total-row">
                        <span>Payment Date:</span>
                        <span><?php echo date('d M Y', strtotime($payment['paid_date'])); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="total-row">
                    <span>Amount Paid:</span>
                    <span>RM <?php echo number_format($data['paid_amount'], 2); ?></span>
                </div>
            <?php endif; ?>

            <div class="total-row">
                <span><strong>Payment Status:</strong></span>
                <span><strong><?php echo ($data['payment_status'] == 2) ? 'PAID' : 'PENDING'; ?></strong></span>
            </div>
        </div>

        <!-- Collection Information -->
        <div class="collection-info">
            <div class="section-title">COLLECTION INSTRUCTIONS</div>
            <p><strong>Date & Time:</strong> <?php echo date('d M Y', strtotime($data['collection_date'])); ?> at <?php echo $data['serve_time']; ?></p>
            <p><strong>Please Note:</strong></p>
            <ul>
                <li>Arrive 15 minutes before your scheduled time</li>
                <li>Bring this receipt for collection</li>
                <li>Contact us if you need to reschedule</li>
            </ul>

            <?php if (!empty($data['prasadam_notes'])): ?>
                <p><strong>Special Instructions:</strong> <?php echo ucwords(str_replace('_', ' ', $data['prasadam_notes'])); ?></p>
            <?php endif; ?>
        </div>

        <!-- WhatsApp Note -->
        <div class="whatsapp-note">
            📱 This receipt was sent via WhatsApp for your convenience.
            Please save this PDF for your records.
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your devotion and support!</p>
            <p>Generated on: <?php echo date('d M Y, h:i A'); ?></p>
            <p>This is a computer-generated receipt and does not require a signature.</p>
        </div>
    </div>
</body>

</html>