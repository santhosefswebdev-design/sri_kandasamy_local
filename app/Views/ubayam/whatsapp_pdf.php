
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubayam Booking Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #d4aa00;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .temple-name {
            font-size: 18px;
            font-weight: bold;
            color: #d4aa00;
            margin-bottom: 5px;
        }

        .temple-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .receipt-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-top: 10px;
        }

        .booking-info {
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 3px 0;
        }

        .info-row:nth-child(even) {
            background-color: #f9f9f9;
        }

        .info-label {
            font-weight: bold;
            width: 40%;
        }

        .info-value {
            width: 60%;
        }

        .packages-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .packages-table th {
            background-color: #d4aa00;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        .packages-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }

        .packages-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .payment-summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .total-row {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #d4aa00;
            padding-top: 10px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .contact-info {
            margin-top: 15px;
            text-align: center;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            color: white;
        }

        .status-confirmed {
            background-color: #28a745;
        }

        .status-partial {
            background-color: #ffc107;
            color: #333;
        }

        .status-pending {
            background-color: #6c757d;
        }

        .divine-blessing {
            text-align: center;
            font-style: italic;
            color: #d4aa00;
            margin: 20px 0;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="temple-name">
            <?= $temple_details['temple_name'] ?? 'Sri Kandaswamy Temple' ?>
        </div>
        <div class="temple-subtitle">Ubayam Booking Receipt</div>
        <div class="receipt-title">Receipt #<?= $booking['ref_no'] ?></div>
    </div>

    <div class="booking-info">
        <div class="info-row">
            <span class="info-label">Devotee Name:</span>
            <span class="info-value"><?= $booking['name'] ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Mobile Number:</span>
            <span class="info-value"><?= $booking['mobile_code'] ?> <?= $booking['mobile_no'] ?></span>
        </div>
        <?php if (!empty($booking['email'])): ?>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value"><?= $booking['email'] ?></span>
            </div>
        <?php endif; ?>
        <div class="info-row">
            <span class="info-label">Booking Date:</span>
            <span class="info-value"><?= date('d M, Y', strtotime($booking['entry_date'])) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Event Date:</span>
            <span class="info-value"><?= date('d M, Y', strtotime($booking['booking_date'])) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Time Slot:</span>
            <span class="info-value">
                <?php
                $slot_names = [];
                foreach ($slots as $slot) {
                    $slot_names[] = $slot['slot_name'];
                }
                echo implode(', ', $slot_names);
                ?>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Payment Type:</span>
            <span class="info-value"><?= ucfirst($booking['payment_type']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Payment Status:</span>
            <span class="info-value">
                <?php
                $status_class = 'status-pending';
                $status_text = 'Pending';

                if ($booking['payment_status'] == 2) {
                    $status_class = 'status-confirmed';
                    $status_text = 'Confirmed';
                } elseif ($booking['payment_status'] == 1) {
                    $status_class = 'status-partial';
                    $status_text = 'Partial Payment';
                }
                ?>
                <span class="status-badge <?= $status_class ?>"><?= $status_text ?></span>
            </span>
        </div>
    </div>

    <?php if (!empty($packages)): ?>
        <table class="packages-table">
            <thead>
                <tr>
                    <th>Package/Service</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Amount (RM)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packages as $package): ?>
                    <tr>
                        <td><?= $package['name'] ?></td>
                        <td><?= $package['description'] ?? 'Ubayam Service' ?></td>
                        <td><?= $package['quantity'] ?></td>
                        <td><?= number_format($package['amount'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="payment-summary">
        <div class="payment-row">
            <span>Subtotal:</span>
            <span>RM <?= number_format($booking['amount'] ?? $booking['total_amount'], 2) ?></span>
        </div>
        <?php if (!empty($booking['discount_amount']) && $booking['discount_amount'] > 0): ?>
            <div class="payment-row">
                <span>Discount:</span>
                <span>- RM <?= number_format($booking['discount_amount'], 2) ?></span>
            </div>
        <?php endif; ?>
        <div class="payment-row total-row">
            <span>Total Amount:</span>
            <span>RM <?= number_format($booking['total_amount'], 2) ?></span>
        </div>
        <div class="payment-row">
            <span>Amount Paid:</span>
            <span>RM <?= number_format($booking['paid_amount'], 2) ?></span>
        </div>
        <?php
        $balance = $booking['total_amount'] - $booking['paid_amount'];
        if ($balance > 0):
            ?>
            <div class="payment-row" style="color: #dc3545; font-weight: bold;">
                <span>Balance Due:</span>
                <span>RM <?= number_format($balance, 2) ?></span>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($payments)): ?>
        <h4>Payment Details:</h4>
        <table class="packages-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Payment Method</th>
                    <th>Amount (RM)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?= date('d M, Y', strtotime($payment['paid_date'])) ?></td>
                        <td><?= $payment['payment_mode_title'] ?></td>
                        <td><?= number_format($payment['amount'], 2) ?></td>
                        <td>
                            <span
                                class="status-badge <?= $payment['pay_status'] == 2 ? 'status-confirmed' : 'status-pending' ?>">
                                <?= $payment['pay_status'] == 2 ? 'Completed' : 'Pending' ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="divine-blessing">
        "May the divine blessings be always with you and your family"
    </div>

    <div class="contact-info">
        <strong>Temple Contact Information:</strong><br>
        Address: <?= $temple_details['address'] ?? 'Temple Address' ?><br>
        Phone: <?= $temple_details['phone'] ?? '+60 XX-XXX XXXX' ?><br>
        Email: <?= $temple_details['email'] ?? 'temple@example.com' ?><br>
        Website: <?= $temple_details['website'] ?? 'www.temple.com' ?>
    </div>

    <div class="footer">
        <p><strong>Important Notes:</strong></p>
        <p>• Please arrive 15 minutes before your scheduled time slot</p>
        <p>• This receipt serves as your booking confirmation</p>
        <p>• For any queries, please contact the temple office</p>
        <p>• May this sacred occasion bring you peace and divine blessings</p>

        <p style="margin-top: 20px; font-size: 9px;">
            Receipt generated on <?= date('d M, Y H:i:s') ?><br>
            System Reference: <?= $booking['ref_no'] ?>
        </p>
    </div>
</body>

</html>