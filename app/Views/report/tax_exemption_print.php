<!DOCTYPE html>
<html>

<head>
    <title>Tax Exemption Donations Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            margin: 5px 0;
            color: #6e6d6bff;
        }

        .header h3 {
            margin: 5px 0;
            color: #333;
        }

        .report-info {
            margin: 20px 0;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .report-info p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #6e6d6bff;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background-color: #e9ecef !important;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        .print-button {
            margin: 20px 0;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .print-button:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <button class="print-button no-print" onclick="window.print()">Print Report</button>

    <div class="header">
        <h2><?= $temp_details['name'] ?? 'Temple' ?></h2>
        <h3>Tax Exemption Donations Report</h3>
        <p><strong>Period: <?= date('d-m-Y', strtotime($fdate)) ?> to <?= date('d-m-Y', strtotime($tdate)) ?></strong>
        </p>
    </div>

    <?php
    // Fetch the data
    $fdt = date('Y-m-d', strtotime($fdate));
    $tdt = date('Y-m-d', strtotime($tdate));

    $db = \Config\Database::connect();
    $builder = $db->table('donation')
        ->join('donation_setting', 'donation_setting.id = donation.pay_for')
        ->join('donation_payment_gateway_datas as dpgd', 'dpgd.donation_booking_id = donation.id', 'left')
        ->select('donation_setting.name as pname, dpgd.pay_method')
        ->select('donation.*')
        ->where('donation.date >=', $fdt)
        ->where('donation.date <=', $tdt)
        ->where('donation.is_tax_redemption', 1)
        ->where('donation.payment_status', 2);

    if (!empty($payfor)) {
        $builder->where('donation_setting.id', $payfor);
    }
    if (!empty($fltername)) {
        $builder->like('donation.name', $fltername);
    }

    $donations = $builder->orderBy('donation.date', 'desc')->get()->getResultArray();

    $totalAmount = 0;
    foreach ($donations as $donation) {
        $totalAmount += $donation['amount'];
    }
    ?>

    <div class="report-info">
        <p><strong>Total Donations:</strong> <?= count($donations) ?></p>
        <p><strong>Total Amount:</strong> RM <?= number_format($totalAmount, 2) ?></p>
        <?php if (!empty($payfor)): ?>
            <?php
            $donationType = $db->table('donation_setting')->where('id', $payfor)->get()->getRowArray();
            ?>
            <p><strong>Donation Type:</strong> <?= $donationType['name'] ?? 'All' ?></p>
        <?php endif; ?>
        <?php if (!empty($fltername)): ?>
            <p><strong>Donor Name Filter:</strong> <?= $fltername ?></p>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">S.No</th>
                <th>Date</th>
                <th>Tax Receipt No</th>
                <th>Name</th>
                <th>IC Number</th>
                <th>Mobile</th>
                <th>Donation Type</th>
                <th class="text-right">Amount (RM)</th>
                <th>Payment Method</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($donations)): ?>
                <tr>
                    <td colspan="9" class="text-center">No records found</td>
                </tr>
            <?php else: ?>
                <?php $i = 1; ?>
                <?php foreach ($donations as $donation): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= date('d-m-Y', strtotime($donation['date'])) ?></td>
                        <td><?= !empty($donation['tax_receipt_no']) ? $donation['tax_receipt_no'] : $donation['ref_no'] ?></td>
                        <td><?= $donation['name'] ?></td>
                        <td><?= $donation['ic_number'] ?: '-' ?></td>
                        <td><?= $donation['mobile'] ?: '-' ?></td>
                        <td><?= $donation['pname'] ?></td>
                        <td class="text-right"><?= number_format($donation['amount'], 2) ?></td>
                        <td><?= $donation['pay_method'] ?: 'Cash' ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="7" class="text-right"><strong>TOTAL:</strong></td>
                    <td class="text-right"><strong>RM <?= number_format($totalAmount, 2) ?></strong></td>
                    <td></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <p><strong>Generated on:</strong> <?= date('d-m-Y H:i:s') ?></p>
    </div>
</body>

</html>