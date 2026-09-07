<?php $db = db_connect(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubayam Voucher</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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

        table td,
        table th {
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

        /* Page numbering styles */
        .page-number {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 12px;
            font-weight: bold;
            z-index: 1000;
            background: white;
            padding: 2px 5px;
            border: 1px solid #ccc;
        }

        @media print {
            #header {
                position: fixed;
                top: 0;
                width: 100%;
                background: white;
                padding-bottom: 10px;
                z-index: 1000;
            }

            body {
                margin-top: 200px;
                /* Adjust based on header height */
            }

            table,
            tr,
            td,
            th {
                page-break-inside: avoid;
            }

            .page-number {
                position: fixed;
                bottom: 0.3in;
                right: 0.3in;
                font-size: 9pt;
                background: transparent;
                border: none;
                padding: 0;
            }

            /* Better page break control */
            .page-break {
                page-break-before: always;
            }

            h2,
            h4 {
                page-break-after: avoid;
            }
        }

        /* Show page numbers on screen for testing */
        @media screen {
            .page-number {
                display: block;
                opacity: 0.7;
            }
        }
    </style>
</head>

<body>

    <!-- Temple Details Header -->
    <div id="header">
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
                        <?php echo $temp_details['address1'], $temp_details['address2'] ?>

                        <?php echo $temp_details['city']; ?> - <?php echo $temp_details['postcode']; ?> <br>
                        Tel:<?php echo $temp_details['telephone']; ?>
                    </p>
                </td>
            </tr>
        </table>
        <hr>
    </div>



    <h2>Ubayam Receipt</h2>

    <table style="width: 100%;">
        <tr>
            <td style="width: 25%;"><b>Entry Date:</b></td>
            <td style="width: 25%;"><?php echo date('d-m-Y', strtotime($data['entry_date'])); ?></td>
            <td style="width: 25%;"><b>Amount (RM):</b></td>
            <td style="width: 25%;"><?php echo number_format($data['amount'], 2); ?></td>
        </tr>
        <tr>
            <td><b>Invoice:</b></td>
            <td><?php echo $data['ref_no']; ?></td>
            <td><b>Deposit Amount (RM):</b></td>
            <td><?php echo number_format($data['deposit_amount'], 2); ?></td>
        </tr>
        <tr>
            <td><b>Name:</b></td>
            <td><?php echo $data['name']; ?></td>
            <td><b>Total Paid (RM):</b></td>
            <td><?php echo number_format($data['paid_amount'], 2); ?></td>
        </tr>

        <tr>
            <td><b>Ubayam Date:</b></td>
            <td><?php echo date('d-m-Y', strtotime($data['booking_date'])); ?></td>

            <td><b>Remarks:</b></td>
            <td><?php echo !empty($data['description']) ? $data['description'] : '-'; ?></td>


        </tr>
        <tr>
            <td><b>Ubayam Time:</b></td>
            <td><?php echo $booked_slot['slot_name']; ?></td>
            <td><b>TPRI Ref No:</b></td>
            <td><?php echo !empty($data['tpri_ref_no']) ? $data['tpri_ref_no'] : '-'; ?></td>
        </tr>
        <tr>

        </tr>
    </table>


    <!-- Package Details Section -->
    <h4>Package Details:</h4>
    <table>
        <tr>
            <th>Deity Name</th>
            <th>Ubayam Name</th>
            <th>FOC Prasadam</th>
            <th>Quantity</th>
        </tr>
        <?php foreach ($packages as $package) { ?>
            <tr>
                <td><?php echo isset($package['deity_name']) ? $package['deity_name'] : '-'; ?></td>
                <td><?php echo $package['name']; ?></td>

                <!-- Display FOC Prasadam -->
                <td>
                    <?php
                    if (!empty($foc_prasadam)) {
                        foreach ($foc_prasadam as $foc) {
                            echo $foc['name_eng'] . "<br>";
                        }
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if (!empty($foc_prasadam)) {
                        foreach ($foc_prasadam as $foc) {
                            echo $foc['quantity'] . "<br>";
                        }
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
            </tr>
        <?php } ?>
    </table>
    <!-- Add-on Details -->
    <?php if (!empty($booked_addon)) { ?>
        <h4>Add-on Details</h4>
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


    <?php
    // Check if there are any payment details before displaying the table
    if (!empty($pay_details) && count($pay_details) > 0) {
    ?>
        <h4>Payment Details:</h4>
        <table>
            <tr>
                <th>Payment Date</th>
                <th>Receipt No</th>  
                <th>Payment Mode</th>
                <th>Amount (RM)</th>
                <th>Outstanding Amount (RM)</th>
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
                      <td><?php echo !empty($payment['receipt_no']) ? $payment['receipt_no'] : '-'; ?>
                    </td>
                    <td><?php echo $payment_mode['name']; ?></td>
                    <td><?php echo number_format($payment['amount'], 2); ?></td>
                    <td><?php echo number_format($outstandingAmount, 2); ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php
    } else {
        // Optional: You can display a message for "Only Booking" status
        // echo '<p><em>No payment details available - Only Booking status.</em></p>';
    }
    ?>

    <br>
    <br>
    <br>
    <!-- <div style="text-align: right;">
        <p>__________________________</p>
        <p><b>Signature</b></p>
    </div> -->
    <br>
    <br>

    <!-- Page number container -->
    <div class="page-number" id="pageNumber">Page 1</div>

    <script>
        $(document).ready(function() {
            // Function to add page numbers using CSS print method
            function addPageNumbers() {
                // Remove existing page numbers
                $('.page-number').remove();

                // For print, we'll rely on CSS @page counter
                // For screen preview, we'll estimate pages
                var pageHeight = 1056; // Standard A4 height in pixels at 96 DPI
                var documentHeight = $(document).height();
                var totalPages = Math.max(1, Math.ceil(documentHeight / pageHeight));

                // Add CSS for print page numbering
                var printCSS = `
                    <style id="print-page-css">
                        @media print {
                            @page {
                                @bottom-right {
                                    content: "Page " counter(page) " of " counter(pages);
                                    font-family: 'Barlow', sans-serif;
                                    font-size: 9pt;
                                    margin-right: 0.3in;
                                    margin-bottom: 0.3in;
                                }
                            }
                            
                            /* Reset page counter */
                            body {
                                counter-reset: page;
                            }
                        }
                        
                        @media screen {
                            .page-number {
                                display: block !important;
                            }
                        }
                    </style>
                `;

                // Remove existing print CSS and add new one
                $('#print-page-css').remove();
                $('head').append(printCSS);

                // Create visible page number for screen preview
                $('body').append('<div class="page-number" id="pageNumber">Page 1 of ' + totalPages + '</div>');
            }

            // Add page numbers when document is ready
            addPageNumbers();

            // Update page numbers on window resize
            $(window).resize(function() {
                setTimeout(addPageNumbers, 100);
            });

            // Enhanced print handling
            var beforePrint = function() {
                console.log('Preparing for print...');
                // Remove screen page number before printing
                $('#pageNumber').hide();
            };

            var afterPrint = function() {
                console.log('Print dialog closed');
                // Show screen page number after printing
                $('#pageNumber').show();
            };

            if (window.matchMedia) {
                var mediaQueryList = window.matchMedia('print');
                mediaQueryList.addListener(function(mql) {
                    if (mql.matches) {
                        beforePrint();
                    } else {
                        afterPrint();
                    }
                });
            }

            window.onbeforeprint = beforePrint;
            window.onafterprint = afterPrint;

            // Auto print
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>

</body>

</html>