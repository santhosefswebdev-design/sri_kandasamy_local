<?php $db = db_connect(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Booking Voucher</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Barlow', sans-serif;
            font-size: 8px;
            margin: 10mm auto;
            max-width: 100%;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }

        table,
        th,
        td {
            border: 1px solid #CCC;
            padding: 6px;
        }

        th {
            background-color: #f9f9f9;
            text-align: center;
        }

        td,
        th {
            vertical-align: top;
        }

        h2,
        h4 {
            text-align: center;
            margin: 5px 0;
        }

        h4 {
            font-size: 9px;
            margin-top: 1px;
            text-align: left;
        }

        p {
            font-size: 8px;
            margin: 4px 0;
        }

       .temple-logo {
        width: 100px;
        display: block;
        margin: 0 auto 5px auto;
    }

        .no-border {
            border: none !important;
        }

        .signature {
            text-align: right;
            margin-top: 30px;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            @page {
                size: A5 portrait;
                margin: 10mm;
            }

            body {
                margin: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- Temple Header -->
    <table class="no-border">
        <!-- <tr>
            <td class="no-border" align="center">
               
            </td>
        </tr> -->
        <tr>
            <td class="no-border" align="center">
                 <img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>" class="temple-logo">
                <h2 style="font-size: 9px; font-weight: bold;"><?php echo $temp_details['name_tamil']; ?></h2>
                <h2 style="font-size: 9px; font-weight: bold;"><?php echo $temp_details['name']; ?></h2>
                <p style="text-align: center; font-size: 9px;">
                    <?php echo $temp_details['address1'], ' ', $temp_details['address2']; ?>
                    <?php echo $temp_details['city']; ?> - <?php echo $temp_details['postcode']; ?><br>
                    Tel: <?php echo $temp_details['telephone']; ?>
                </p>
    
            </td>
        </tr>
    </table>


    <!-- <hr> -->

    <h2>Wedding Receipt</h2>

    <!-- Booking Details -->
    <table>
        <tr>
            <td><b>Wedding Hall Name:</b></td>
            <td><?php echo htmlspecialchars($data['venue_name']); ?></td>
        </tr>
        <tr>
            <td><b>Entry Date:</b></td>
            <td><?php echo date('d-m-Y', strtotime($data['entry_date'])); ?></td>
        </tr>
        <tr>
            <td><b>Invoice:</b></td>
            <td><?php echo $data['ref_no']; ?></td>
        </tr>
        <tr>
            <td><b>Name:</b></td>
            <td><?php echo $data['name']; ?></td>
        </tr>
        <tr>
            <td><b>Wedding Date:</b></td>
            <td><?php echo date('d-m-Y', strtotime($data['booking_date'])); ?></td>
        </tr>
        <tr>
            <td><b>Wedding Time:</b></td>
            <td><?php echo $booked_slot['slot_name']; ?></td>
        </tr>
        <tr>
            <td><b>Package Name:</b></td>
            <td>
                <?php echo !empty($packages) ? htmlspecialchars(implode(', ', array_column($packages, 'name'))) : 'No packages selected'; ?>
            </td>
        </tr>
        <tr>
            <td><b>Amount (RM):</b></td>
            <td><?php echo number_format($data['amount'], 2); ?></td>
        </tr>
        <tr>
            <td><b>Deposit Amount (RM):</b></td>
            <td><?php echo number_format($data['deposit_amount'], 2); ?></td>
        </tr>
        <tr>
            <td><b>Total Paid (RM):</b></td>
            <td><?php echo number_format($data['paid_amount'], 2); ?></td>
        </tr>
    </table>

    <!-- Package Details -->
    <?php if (!empty($booked_services)) { ?>
        <h4>Package Details:</h4>
        <table>
            <tr>
                <th>Particulars</th>
                <th>Quantity</th>
            </tr>
            <?php foreach ($booked_services as $service) { ?>
                <tr>
                    <td style="text-align: center;"><?php echo htmlspecialchars($service['name']); ?></td>
                    <td style="text-align: center;"><?php echo $service['quantity']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

    <!-- Add-on Details -->
    <?php if (!empty($booked_addon)) { ?>
        <h4>Add-on Details:</h4>
        <table>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Amount (RM)</th>
            </tr>
            <?php foreach ($booked_addon as $addon) { ?>
                <tr>
                    <td><?php echo $addon['name']; ?></td>
                    <td><?php echo $addon['quantity']; ?></td>
                    <td><?php echo number_format($addon['amount'], 2); ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

    <!-- Payment Details -->
    <h4>Payment Details:</h4>
    <table>
        <tr>
            <th>Payment Date</th>
            <th>Payment Mode</th>
            <th>Paid Amount (RM)</th>
            <th>Outstanding (RM)</th>
        </tr>
        <?php
        $totalPaid = 0;
        foreach ($pay_details as $payment) {
            $payment_mode = $db->table("payment_mode")->where('id', $payment['payment_mode_id'])->get()->getRowArray();
            $totalPaid += $payment['amount'];
            $outstandingAmount = $data['amount'] - $totalPaid;
            ?>
            <tr>
                <td><?php echo date("d/m/Y", strtotime($payment['paid_date'])); ?></td>
                <td><?php echo $payment_mode['name']; ?></td>
                <td><?php echo number_format($payment['amount'], 2); ?></td>
                <td><?php echo number_format($outstandingAmount, 2); ?></td>
            </tr>
        <?php } ?>
    </table>

    <!-- Bride & Groom Details -->
    <?php if (!empty($booked_bride_details) || !empty($booked_groom_details)) { ?>
        <h4>Bride & Groom Details:</h4>
        <table>
            <tr>
                <th>Bride Details</th>
                <th>Groom Details</th>
            </tr>
            <tr>
                <td>
                    <b>Name:</b> <?php echo $booked_bride_details[0]['name'] ?? '-'; ?><br>
                    <b>NRIC:</b> <?php echo $booked_bride_details[0]['nric'] ?? '-'; ?><br>
                    <b>DOB:</b>
                    <?php echo !empty($booked_bride_details[0]['dob']) ? date("d/m/Y", strtotime($booked_bride_details[0]['dob'])) : '-'; ?>
                </td>
                <td>
                    <b>Name:</b> <?php echo $booked_groom_details[0]['name'] ?? '-'; ?><br>
                    <b>NRIC:</b> <?php echo $booked_groom_details[0]['nric'] ?? '-'; ?><br>
                    <b>DOB:</b>
                    <?php echo !empty($booked_groom_details[0]['dob']) ? date("d/m/Y", strtotime($booked_groom_details[0]['dob'])) : '-'; ?>
                </td>
            </tr>
        </table>
    <?php } ?>

    <!-- Signature -->
    <div class="signature">
        <p>__________________________</p>
        <p><b>Signature</b></p>
    </div>

    <!-- Terms Page -->
    <?php if (!empty($terms)) { ?>
        <div class="page-break">
            <div style="border: 1px solid #CCC; padding: 10px;">
                <h4>Terms & Conditions:</h4>
                <?php
                $decoded_terms = json_decode($terms['hall'], true);
                if (!empty($decoded_terms)) {
                    foreach ($decoded_terms as $term) {
                        echo "<p>" . (is_array($term) ? implode(" ", $term) : $term) . "</p>";
                    }
                } else {
                    echo "<p>No terms available.</p>";
                }
                ?>
            </div>
        </div>
    <?php } ?>

    <script>
        window.print();
    </script>

</body>

</html>