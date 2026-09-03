<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Tax Exemption Donations Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #8B4513;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 5px 0;
            color: #8B4513;
            font-size: 18pt;
        }

        .header h3 {
            margin: 5px 0;
            color: #333;
            font-size: 14pt;
        }

        .header p {
            margin: 5px 0;
            font-size: 10pt;
        }

        .report-info {
            margin: 15px 0;
            padding: 10px;
            background: #f5f5f5;
            border: 1px solid #ddd;
            font-size: 9pt;
        }

        .report-info p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 8pt;
        }

        th {
            background-color: #797575ff;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #787474ff;
        }

        td {
            padding: 5px 4px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background-color: #e9ecef !important;
            font-weight: bold;
            font-size: 9pt;
        }

        .footer {
            margin-top: 30px;
            font-size: 8pt;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2><?= $pdfdata['temp_details']['name'] ?? 'Temple' ?></h2>
        <h3>Tax Exemption Donations Report</h3>
        <p><strong>Period: <?= date('d-m-Y', strtotime($pdfdata['fdate'])) ?> to
                <?= date('d-m-Y', strtotime($pdfdata['tdate'])) ?></strong></p>
    </div>

    <?php
    // Fetch the data
    $fdt = date('Y-m-d', strtotime($pdfdata['fdate']));
    $tdt = date('Y-m-d', strtotime($pdfdata['tdate']));

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

    if (!empty($pdfdata['payfor'])) {
        $builder->where('donation_setting.id', $pdfdata['payfor']);
    }
    if (!empty($pdfdata['fltername'])) {
        $builder->like('donation.name', $pdfdata['fltername']);
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
        <?php if (!empty($pdfdata['payfor'])): ?>
            <?php
            $donationType = $db->table('donation_setting')->where('id', $pdfdata['payfor'])->get()->getRowArray();
            ?>
            <p><strong>Donation Type:</strong> <?= $donationType['name'] ?? 'All' ?></p>
        <?php endif; ?>
        <?php if (!empty($pdfdata['fltername'])): ?>
            <p><strong>Donor Name Filter:</strong> <?= $pdfdata['fltername'] ?></p>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">S.No</th>
                <th style="width: 10%;">Date</th>
                <th style="width: 12%;">Tax Receipt No</th>
                <th style="width: 18%;">Name</th>
                <th style="width: 12%;">IC Number</th>
                <th style="width: 10%;">Mobile</th>
                <th style="width: 13%;">Donation Type</th>
                <th style="width: 10%;" class="text-right">Amount (RM)</th>
                <th style="width: 10%;">Payment</th>
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

    <div class="footer">
        <p><strong>Report Generated on:</strong> <?= date('d-m-Y H:i:s') ?></p>
        <p><em>This is a system-generated report of donations eligible for tax exemption.</em></p>
    </div>
</body>

</html>