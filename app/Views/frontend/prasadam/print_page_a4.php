<?php $db = db_connect(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prasadam Receipt</title>
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
        }

        h2,
        h4 {
            text-align: center;
        }

        /* Temple header styles */
        .temple-header {
            border: none;
            width: 100%;
            text-align: center;
        }

        .temple-header td {
            border: none;
        }

        .temple-logo {
            padding-bottom: 0;
        }

        .temple-info {
            padding-top: 0;
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

        /* Print-specific styles */
        @media print {
            /* Set up page margins */
            @page {
                margin-top: 0.3in;
                margin-bottom: 0.5in;
                margin-left: 0.5in;
                margin-right: 0.5in;
            }

            /* Hide the print-only header and use original header */
            .temple-header {
                display: none;
            }

            /* Keep original header visible in print */
            .original-header {
                display: table !important;
                page-break-inside: avoid;
                margin-bottom: 10px;
            }

            /* Make header repeat using CSS table-header-group */
            .original-header {
                display: table-header-group;
            }

            .content {
                display: table-row-group;
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
            
            /* Prevent orphaned content */
            table {
                page-break-inside: avoid;
            }
            
            tr {
                page-break-inside: avoid;
            }
            
            h2, h4 {
                page-break-after: avoid;
            }

            /* Ensure content doesn't overlap with fixed header */
            .content {
                margin-top: 0;
                display: table-row-group;
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
    <!-- Wrap everything in a table structure for proper header repetition -->
    <div style="display: table; width: 100%;">

    <!-- Original Temple Header (will repeat on print using table-header-group) -->
    <table class="original-header" style="border: none; width: 100%; text-align: center; display: table-header-group;">
        <tr>
            <td style="border: none; padding-bottom: 0;" align="center">
                <img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>"
                    style="width: 200px; max-width: 100%; display: block; margin: auto;">
            </td>
        </tr>
        <tr>
            <td style="border: none; padding-top: 0;">
                <h2 style="text-align: center; margin: 0; font-size: 18px; font-weight: bold; width: 100%;">
                    <?php echo $temp_details['name_tamil']; ?>
                </h2>
                <h2 style="text-align: center; margin: 0; font-size: 22px; font-weight: bold; width: 100%;">
                    <?php echo $temp_details['name']; ?>
                </h2>
                <p style="text-align: center; font-size: 14px; margin: 5px 0; width: 80%; margin-left: auto; margin-right: auto;">
                    <?php echo $temp_details['address1'], $temp_details['address2'] ?> <br>
                    <?php echo $temp_details['city']; ?> - <?php echo $temp_details['postcode']; ?> <br>
                    Tel:<?php echo $temp_details['telephone']; ?>
                </p>
                <hr>
            </td>
        </tr>
    </table>

    <div class="content" style="display: table-row-group;">
        <h2>Prasadam Receipt</h2>

        <table>
            <tr>
                <td><b>Entry Date:</b></td>
                <td><?php echo date('d/m/Y', strtotime($data['date'])); ?></td>
                <?php
                // Add Deity information
                if (!empty($data['diety_id'])) {
                    $deity = $db->table('archanai_diety')->where('id', $data['diety_id'])->get()->getRowArray();
                    if (!empty($deity)) {
                        ?>
                        <td><b>Deity:</b></td>
                        <td><?php echo $deity['name']; ?></td>
                        <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <td><b>Invoice:</b></td>
                <td><?php echo $data['ref_no']; ?></td>
                <td><b>Total Amount (RM):</b></td>
                <td><?php echo number_format($data['total_amount'], 2); ?></td>
            </tr>
            <tr>
                <td><b>Name:</b></td>
                <td><?php echo $data['customer_name']; ?></td>
                <?php if (!empty($data['prasadam_notes'])) {
                    // Define the labels for distribution notes
                    $notes_labels = [
                        'keep_till_they_come' => 'Keep Till They Come',
                        'will_come' => 'Will Come',
                        'can_distribute' => 'Can Distribute',
                        'taking_outside' => 'Taking Outside',
                        'half_to_temple_half_to_thithi' => '1/2 To Temple & 1/2 To Thithi',
                        '1_pack_balance_distribute' => '1 Pack & Balance Distribute',
                        '2_pack_balance_distribute' => '2 Pack & Balance Distribute',
                        '3_pack_balance_distribute' => '3 Pack & Balance Distribute'
                    ];
                    ?>
                    <td><b>Distribution Notes:</b></td>
                    <td><?php echo $notes_labels[$data['prasadam_notes']] ?? $data['prasadam_notes']; ?></td>
                <?php } ?>
            </tr>
            <tr>
                <td><b>Collection Date:</b></td>
                <td><?php echo date('d/m/Y', strtotime($data['collection_date'])); ?></td>
                <?php
                // Add Serve Time information
                if (!empty($data['serve_time'])) {
                    ?>
                    <td><b>Serve Time:</b></td>
                    <td><?php echo $data['serve_time']; ?></td>
                <?php } ?>
            </tr>
            <tr>
                <?php
                // Add Session information
                if (!empty($data['session'])) {
                    ?>
                    <td><b>Slot:</b></td>
                    <td><?php echo $data['session']; ?></td>
                <?php } ?>
                <td><b>Mobile Number:</b></td>
                <td><?php echo $data['mobile_no']; ?></td>
            </tr>
            <tr>
                
                <td><b>Remarks:</b></td>
                <td colspan="3"><?php echo $data['desciption']; ?></td>
            </tr>
            
            
            
            
        </table>

        <h4>Prasadam Details:</h4>
        <table>
            <tr>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price (RM)</th>
                <th>Amount (RM)</th>
            </tr>
            <?php foreach ($booking_details as $bd) { ?>
                <tr>
                    <?php if ($bd['groupname'] == 'General') { ?>
                        <td><?php echo $bd['name_eng'] . ' / ' . $bd['name_tamil']; ?></td>
                    <?php } else { ?>
                        <td><?php echo $bd['name_eng'] . ' / ' . $bd['name_tamil'] . ' - ' . $bd['groupname']; ?></td>
                    <?php } ?>
                    <td><?php echo $bd['quantity']; ?></td>
                    <td><?php echo number_format($bd['amount'], 2); ?></td>
                    <td><?php echo number_format($bd['quantity'] * $bd['amount'], 2); ?></td>
                </tr>
            <?php } ?>
        </table>

        <h4>Payment Details:</h4>
        <table>
            <tr>
                <th>Payment Date</th>
                <th>Payment Mode</th>
                <th>Paid Amount (RM)</th>
                <th>Outstanding Amount (RM)</th>
            </tr>
            <?php
            $totalPaid = 0;
            foreach ($pay_details as $payment) {
                $payment_mode = $db->table("payment_mode")->where('id', $payment['payment_mode_id'])->get()->getRowArray();
                $totalPaid += $payment['amount'];
                ?>
                <tr>
                    <td><?php echo date("d/m/Y", strtotime($payment['paid_date'])); ?></td>
                    <td><?php echo $payment_mode['name']; ?></td>
                    <td><?php echo number_format($payment['amount'], 2); ?></td>
                    <td><?php echo number_format(max(0, $data['total_amount'] - $totalPaid), 2); ?></td>
                </tr>
            <?php } ?>
        </table>

        <br>
        <br>
        <br>
        <div style="text-align: right;">
            <p>__________________________</p>
            <p><b>Signature</b></p>
        </div>
        <br>
    </div>

    </div> <!-- End table wrapper -->

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