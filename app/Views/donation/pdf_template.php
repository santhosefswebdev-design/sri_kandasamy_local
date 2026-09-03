<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Donation Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #d4aa00;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #d4aa00;
            margin-bottom: 5px;
        }

        .details {
            margin: 20px 0;
        }

        .amount {
            font-size: 20px;
            font-weight: bold;
            color: #d4aa00;
            background: #f9f9f9;
            padding: 10px;
            border-left: 4px solid #d4aa00;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 30%;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .ref-number {
            font-size: 16px;
            font-weight: bold;
            color: #2c5aa0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Donation Receipt</h1>
        <p>Thank you for your generous contribution</p>
        <div class="ref-number">Receipt #<?= $donation['ref_no'] ?></div>
    </div>

    <div class="details">
        <table>
            <tr>
                <th>Date:</th>
                <td><?= date('d M Y', strtotime($donation['date'])) ?></td>
            </tr>
            <tr>
                <th>Donor Name:</th>
                <td><?= $donation['name'] ?></td>
            </tr>
            <?php if (!empty($donation['mobile_no'])): ?>
                <tr>
                    <th>Mobile:</th>
                    <td><?= $donation['mobile_code'] . $donation['mobile_no'] ?></td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($donation['email'])): ?>
                <tr>
                    <th>Email:</th>
                    <td><?= $donation['email'] ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <th>Donation Purpose:</th>
                <td><?= $donation['pname'] ?></td>
            </tr>
            <tr>
                <th>Payment Method:</th>
                <td><?= $payment_mode ?></td>
            </tr>
        </table>

        <div class="amount">
            <strong>Total Amount: RM <?= number_format($donation['amount'], 2) ?></strong>
        </div>
    </div>

    <div class="footer">
        <p>Generated on <?= date('d M Y H:i:s') ?></p>
        <p>This is a computer-generated receipt</p>
        <p>Thank you for your support and generosity</p>
    </div>
</body>

</html>