<?php
// Start session if not already started
if (!isset($_SESSION)) {
    session_start();
}

// Establish database connection
$db = db_connect();

// Retrieve filter values (sanitize input)
$fdt = isset($_REQUEST['fdt']) ? $_REQUEST['fdt'] : '';
$tdt = isset($_REQUEST['tdt']) ? $_REQUEST['tdt'] : '';
$fltername = isset($_POST['fltername']) ? $_POST['fltername'] : '';

// Fetch site details from session
$site_title = $_SESSION['site_title'];
$logo_img = $_SESSION['logo_img'];
$name_tamil = $_SESSION['name_tamil'];
$city_tamil = $_SESSION['city_tamil'];
$since_tamil = $_SESSION['since_tamil'];
$city = $_SESSION['city'];
$since_eng = $_SESSION['since_eng'];
$address1 = $_SESSION['address1'];
$address2 = $_SESSION['address2'];
$postcode = $_SESSION['postcode'];
$telephone = $_SESSION['telephone'];

// Initialize query builder
$builder = $db->table('prasadam')
    ->select('prasadam.id, prasadam.date, prasadam.customer_name, 
                prasadam.collection_date, prasadam.serve_time, prasadam.total_amount as amount')
    ->where('payment_status', 2);

// Apply date range filter if both dates are provided
if ($fdt && $tdt) {
    $builder->where('DATE_FORMAT(prasadam.collection_date, "%Y-%m-%d") >=', $fdt)
            ->where('DATE_FORMAT(prasadam.collection_date, "%Y-%m-%d") <=', $tdt);
}

// Apply customer name filter if provided
if ($fltername) {
    $builder->where('prasadam.customer_name', $fltername);
}

// Fetch the filtered results
$dat = $builder->orderBy('prasadam.collection_date', 'asc')->get()->getResultArray();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?> - Prasadam Collection Report</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Barlow', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header Styles */
        .report-header {
            border: none;
            width: 100%;
            margin-bottom: 20px;
        }

        .report-header td {
            border: none;
        }

        .temple-info-table {
            width: 100%;
            border: none;
        }

        .temple-info-table td {
            border: none;
            vertical-align: middle;
        }

        .logo-cell {
            width: 15%;
            text-align: center;
        }

        .logo-cell img {
            width: 120px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .info-cell {
            width: 85%;
            text-align: center;
        }

        .info-cell h3 {
            text-align: center;
            margin-bottom: 5px;
            margin-top: 0;
            font-family: "Baamini";
            font-size: 22px;
        }

        .info-cell h2 {
            text-align: center;
            margin-bottom: 5px;
            margin-top: 8px;
            font-size: 24px;
            font-weight: 600;
        }

        .info-cell p {
            text-align: center;
            font-size: 16px;
            margin: 5px 0;
            line-height: 1.4;
        }

        hr {
            border: none;
            border-top: 2px solid #333;
            margin: 15px 0;
        }

        /* Report Title */
        .report-title {
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            margin: 20px 0;
            text-transform: uppercase;
            color: #333;
        }

        /* Table Styles */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table[border="1"] {
            border: 1px solid #333 !important;
        }

        table[border="1"] th,
        table[border="1"] td {
            border: 1px solid #333 !important;
            padding: 12px 8px !important;
        }

        table[border="1"] thead {
            background-color: #f5f5f5;
        }

        table[border="1"] thead th {
            font-weight: 600;
            text-align: center;
            font-size: 15px;
            color: #333;
        }

        table[border="1"] tbody td {
            vertical-align: top;
            font-size: 14px;
        }

        /* Column Alignments */
        table[border="1"] td:nth-child(1),  /* S.No */
        table[border="1"] td:nth-child(2),  /* Date */
        table[border="1"] td:nth-child(3),  /* Customer Name */
        table[border="1"] td:nth-child(4),  /* Collection Date */
        table[border="1"] td:nth-child(5)   /* Time */
        {
            text-align: center !important;
        }

        table[border="1"] td:nth-child(6) { /* Pay for */
            text-align: left !important;
            line-height: 1.8;
            padding-left: 12px !important;
        }

        table[border="1"] td:nth-child(7) { /* Amount */
            text-align: right !important;
            font-weight: 600;
            padding-right: 12px !important;
        }

        /* Pay For Items Styling */
        .payfor-items {
            margin: 0;
            padding: 0;
            line-height: 1.8;
        }

        .payfor-item {
            margin: 3px 0;
            padding-left: 5px;
        }

        /* Total Row */
        .total-row {
            background-color: #f0f0f0 !important;
            font-weight: 600;
            font-size: 15px;
        }

        .total-row td {
            padding: 14px 12px !important;
        }

        /* Print Styles */
        @media print {
            @page {
                margin: 0.5in;
                size: A4;
            }

            body {
                padding: 0;
            }

            .container {
                max-width: 100%;
            }

            table[border="1"] {
                page-break-inside: auto;
            }

            table[border="1"] tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            table[border="1"] thead {
                display: table-header-group;
            }

            table[border="1"] tbody {
                display: table-row-group;
            }

            .report-header {
                display: table-header-group;
            }

            /* Ensure borders print correctly */
            table[border="1"],
            table[border="1"] th,
            table[border="1"] td {
                border: 1px solid #333 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .total-row {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Responsive Styles */
        @media screen and (max-width: 768px) {
            body {
                padding: 10px;
            }

            .logo-cell img {
                width: 80px;
            }

            .info-cell h2 {
                font-size: 18px;
            }

            .info-cell h3 {
                font-size: 16px;
            }

            .info-cell p {
                font-size: 13px;
            }

            table[border="1"] th,
            table[border="1"] td {
                padding: 8px 5px !important;
                font-size: 12px;
            }

            .report-title {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header Section -->
    <table class="report-header" align="center">
        <tr>
            <td>
                <table class="temple-info-table">
                    <tr > 
                        <td class="logo-cell">
                            <img src="<?php echo base_url(); ?>/uploads/main/<?php echo $logo_img; ?>" alt="Temple Logo">
                        </td>
                        <td class="info-cell">
                            <h3><?php echo $name_tamil; ?></h3>
                            <p><?php echo $city_tamil; ?> <?php echo $since_tamil; ?></p>
                            <h2><?php echo $site_title; ?></h2>
                            <p>
                                <?php echo $city; ?> <?php echo $since_eng; ?><br>
                                <?php echo $address1; ?>, <?php echo $address2; ?>, <?php echo $postcode; ?> <?php echo $city; ?><br>
                                Tel: <?php echo $telephone; ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- <tr>
            <td>
                <hr>
            </td>
        </tr> -->
    </table>
<hr>
    <!-- Report Title -->
    <div class="report-title">
        <?php if (!empty($fdt) && !empty($tdt)) { ?>
            PRASADAM COLLECTION REPORT <?php echo date("d/m/Y", strtotime($fdt)) . ' - ' . date("d/m/Y", strtotime($tdt)); ?>
        <?php } else { ?>
            PRASADAM COLLECTION REPORT
        <?php } ?>
    </div>

    <!-- Data Table -->
    <table border="1">
        <thead>
            <tr>
                <th style="width:5%;">S.No</th>
                <th style="width:10%;">Date</th>
                <th style="width:15%;">Customer Name</th>
                <th style="width:12%;">Collection Date</th>
                <th style="width:10%;">Time</th>
                <th style="width:35%;">Pay for</th>
                <th style="width:13%;">Amount (RM)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            $i = 1;
            foreach ($dat as $row) {
                // Get associated payfor items
                $payfors = $db->table('prasadam_booking_details')
                    ->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
                    ->select('prasadam_setting.name_eng, prasadam_setting.name_tamil, prasadam_booking_details.quantity')
                    ->where('prasadam_booking_details.prasadam_booking_id', $row['id'])
                    ->get()->getResultArray();
                
                $html = '<div class="payfor-items">';
                foreach ($payfors as $payfor) {
                    $html .= '<div class="payfor-item">• ' . $payfor['name_eng'] . ' / ' . $payfor['name_tamil'] . ' (Qty: ' . $payfor['quantity'] . ')</div>';
                }
                $html .= '</div>';
                
                $total += floatval($row['amount']);
            ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['date'])); ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['collection_date'])); ?></td>
                    <td><?php echo !empty($row['serve_time']) ? date('h:i A', strtotime($row['serve_time'])) : '-'; ?></td>
                    <td><?php echo $html; ?></td>
                    <td><?php echo number_format($row['amount'], 2, '.', ','); ?></td>
                </tr>
            <?php } ?>
            
            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="6" style="text-align: right; padding-right: 20px;">
                    <strong>TOTAL AMOUNT:</strong>
                </td>
                <td style="text-align: right;">
                    <strong><?php echo number_format($total, 2, '.', ','); ?></strong>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    window.onload = function() {
        window.print();
    };
</script>

</body>
</html>