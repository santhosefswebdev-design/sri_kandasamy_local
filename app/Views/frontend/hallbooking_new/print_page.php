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
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid #CCC;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
table td, table th {
    padding: 5px;
    font-size: 12px;
}

        h2,
        h4 {
            text-align: center;
        }

        .no-border {
            border: none;
        }
        .page-break {
    page-break-before: always;
}

.avoid-break {
    page-break-inside: avoid;
}

    </style>
</head>

<body>

    <!-- Temple Details -->
<table style="border: none; width: 100%; text-align: center;">
    <tr>
        <td style="border: none; padding-bottom: 0;" align="center">
            <img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>"
                style="width: 150px; max-width: 100%; display: block; margin: auto;">
        </td>
    </tr>
    <tr>
        <td style="border: none; padding-top: 0;">
              <h2 style="text-align: center; margin: 0; font-size: 14px; font-weight: bold; width: 100%;">
                <?php echo $temp_details['name_tamil']; ?>
            </h2>
            <h2 style="text-align: center; margin: 0; font-size: 14px; font-weight: bold; width: 100%;">
                <?php echo $temp_details['name']; ?><br>
                <?= $temp_details['regno']; ?>
            </h2>
            <p
                style="text-align: center; font-size: 14px; margin: 5px 0; width: 80%; margin-left: auto; margin-right: auto;">
                <?php echo $temp_details['address1'] ,$temp_details['address2']?>
                
                <?php echo $temp_details['city']; ?> - <?php echo $temp_details['postcode']; ?> <br>
                Tel:<?php echo $temp_details['telephone']; ?>
            </p>
        </td>
    </tr>
</table>

    <h4>Wedding receipt</h4>

    <!-- Booking Details -->
<table>
   <tr>
    <td><b>Wedding Hall Name:</b></td>
    <td><?php echo htmlspecialchars($data['venue_name'], ENT_QUOTES, 'UTF-8'); ?></td>
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
     <!-- <tr>
        <td><b>Address:</b></td>
       <td><?php /*echo $data['address'];*/ ?></td>
    </tr> -->
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
        <?php
            if (!empty($packages)) {
                $package_names = array_column($packages, 'name'); // Extract only package names
                echo htmlspecialchars(implode(', ', $package_names), ENT_QUOTES, 'UTF-8'); // Show multiple names
            } else {
                echo "No packages selected";
            }
            ?>
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


<?php if (!empty($booked_services)) { ?>
    <h5 style="text-align: left;">Package Details:</h5>
    <table>
        <tr>
            <th style="text-align: center;">Particulars</th>
            <th style="text-align: center;">Quantity</th>
        </tr>
        <?php foreach ($booked_services as $service) { ?>
            <tr>
                <td style="text-align: center;"><?php echo htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td style="text-align: center;"><?php echo htmlspecialchars($service['quantity'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
        <?php } ?>
    </table>
<?php } else { ?>
    <p>No service details available.</p>
<?php } ?>


    <!-- Add-on Details -->
    <?php if (!empty($booked_addon)) { ?>
        <h5>Add-on Details</h5>
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
   <!-- Payment Details -->
<h5 style="text-align: left;">Payment Details:</h5>
<table>
    <tr >
        <th style="text-align: center;">Payment Date</th>
        <th style="text-align: center;">Payment Mode</th>
        <th style="text-align: center;">Paid Amount (RM)</th>
        <th style="text-align: center;">Outstanding Amount (RM)</th>
    </tr>
    <?php
    $totalPaid = 0; // Track total paid amount
    foreach ($pay_details as $payment) {
        $payment_mode = $db->table("payment_mode")->where('id', $payment['payment_mode_id'])->get()->getRowArray();
        $totalPaid += $payment['amount']; // Accumulate total paid
        $outstandingAmount = $data['amount'] - $totalPaid; // Calculate remaining amount
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
<h6 style="text-align: left; font-size: 14px; font-weight: bold; line-height: -0.5;">Bride & Groom Details:</h6>
<table style="width: 100%; border-collapse: collapse; font-size: 14px; line-height: -0.5;">

        <tr>
            <th style=" padding: 8px; text-align: center;">Bride Details</th>
            <th style=" padding: 8px; text-align: center;">Groom Details</th>
        </tr>
        <tr>
            <td style="border-left: none; border-right: none; padding: 0px;">
                <table style="width: 100%; border:none">
                    <tr style="border-bottom: 1px solid #CCC;">
                        <td style="padding: 5px;  border: none;"><b>Bride Name:</b></td>
                        <td style="padding: 5px;  border: none;">
                            <?php echo !empty($booked_bride_details[0]['name']) ? htmlspecialchars($booked_bride_details[0]['name'], ENT_QUOTES, 'UTF-8') : '-'; ?>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #CCC;">
                        <td style="padding: 5px;  border: none;"><b>Bride NRIC/Pass:</b></td>
                        <td style="padding: 5px;  border: none;">
                            <?php echo !empty($booked_bride_details[0]['nric']) ? htmlspecialchars($booked_bride_details[0]['nric'], ENT_QUOTES, 'UTF-8') : '-'; ?>
                        </td>
                    </tr>
                    <tr >
                        <td style="padding: 5px;  border: none;"><b>Bride DOB:</b></td>
                        <td style="padding: 5px;  border: none;">
                            <?php echo !empty($booked_bride_details[0]['dob']) ? date("d/m/Y", strtotime($booked_bride_details[0]['dob'])) : '-'; ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="border-left: none; border-right: none; padding: 0px; border-left: 0.2px solid #CCC;">
                <table style="width: 100%;  border: none;">
                    <tr style="border-bottom: 1px solid #CCC;">
                        <td style="padding: 5px; border: none;"><b>Groom Name:</b></td>
                        <td style="padding: 5px; border: none;">
                            <?php echo !empty($booked_groom_details[0]['name']) ? htmlspecialchars($booked_groom_details[0]['name'], ENT_QUOTES, 'UTF-8') : '-'; ?>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #CCC;">
                        <td style="padding: 5px; border: none;"><b>Groom NRIC/Pass:</b></td>
                        <td style="padding: 5px; border: none;">
                            <?php echo !empty($booked_groom_details[0]['nric']) ? htmlspecialchars($booked_groom_details[0]['nric'], ENT_QUOTES, 'UTF-8') : '-'; ?>
                        </td>
                    </tr>
                    <tr >
                        <td style="padding: 5px; border: none;"><b>Groom DOB:</b></td>
                        <td style="padding: 5px; border: none;">
                            <?php echo !empty($booked_groom_details[0]['dob']) ? date("d/m/Y", strtotime($booked_groom_details[0]['dob'])) : '-'; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
<?php } else { ?>
    <p style="font-size: 16px;">No bride or groom details available.</p>
<?php } ?>


<div class="avoid-break" style="display: flex; justify-content: flex-end; margin-top: 20px;">
    <div style="text-align: right;">
        <p>__________________________</p>
        <p><b>Signature</b></p>
    </div>
</div>



    <div  class="page-break" > <!-- Forces new page -->
    <?php if (!empty($terms)) { ?>
            <div style="border: 1px solid #CCC; padding: 10px; margin-top: 10px;">
                <h4 style="text-align: left;">Terms & Conditions:</h4>
                <?php
                $decoded_terms = json_decode($terms['hall'], true); // Decode JSON
                if (!empty($decoded_terms)) {
                    foreach ($decoded_terms as $term) {
                        if (is_array($term)) {
                            echo "<p>" . implode(" ", $term) . "</p>"; // Convert array to string
                        } else {
                            echo "<p>" . $term . "</p>"; // Output raw HTML
                        }
                    }
                } else {
                    echo "<p>No terms available.</p>";
                }
                ?>
            </div>
        <?php } ?>
    </div>

    <script>
        window.print();
    </script>

</body>

</html>