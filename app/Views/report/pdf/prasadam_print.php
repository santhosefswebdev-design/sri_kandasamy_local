<?php        
$db = db_connect();
?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
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
		}

		h2,
		h4 {
			text-align: center;
		}

		/* Remove Header Borders */
		table[align="center"] {
			border: none !important;
			margin: 0 auto;
			max-width: 210mm;
		}

		table[align="center"] > tbody > tr:first-child > td,
		table[align="center"] > tbody > tr:nth-child(2) > td {
			border: none !important;
		}

		table[align="center"] > tbody > tr:first-child > td > table {
			border: none !important;
		}

		table[align="center"] > tbody > tr:first-child > td > table td {
			border: none !important;
		}

		table[align="center"] > tbody > tr:first-child > td > table tr {
			border: none !important;
		}

		/* Add line after header using HR */
		table[align="center"] > tbody > tr:nth-child(2) hr {
			display: block !important;
			border: none;
			border-top: 2px solid #333;
			margin: 10px 0;
		}

		/* Ensure data table has borders - ALL ROWS */
		table[border="1"] {
			border: 1px solid #CCC !important;
			border-collapse: collapse !important;
			width: 100% !important;
		}

		table[border="1"] th,
		table[border="1"] td {
			border: 1px solid #CCC !important;
			padding: 8px !important;
		}

		table[border="1"] thead {
			background-color: #f5f5f5;
		}

		table[border="1"] thead tr {
			border: 1px solid #CCC !important;
		}

		table[border="1"] thead th {
			font-weight: bold;
			border: 1px solid #CCC !important;
		}

		table[border="1"] tbody tr {
			border: 1px solid #CCC !important;
		}

		table[border="1"] tbody td {
			border: 1px solid #CCC !important;
		}

		/* Force borders on first rows specifically */
		table[border="1"] tbody tr:first-child td,
		table[border="1"] tbody tr:nth-child(2) td,
		table[border="1"] tbody tr:nth-child(3) td {
			border: 1px solid #CCC !important;
		}

		table[border="1"] tbody tr:first-child,
		table[border="1"] tbody tr:nth-child(2),
		table[border="1"] tbody tr:nth-child(3) {
			border: 1px solid #CCC !important;
		}

		/* Header Centering - Main Container */
		table[align="center"] > tbody > tr:first-child > td > table {
			width: 100%;
			margin: 0 auto;
		}

		/* Logo Column - Centered */
		table[align="center"] > tbody > tr:first-child > td > table td[width="15%"] {
			text-align: center !important;
			vertical-align: middle;
		}

		table[align="center"] > tbody > tr:first-child > td > table td[width="15%"] img {
			display: block;
			margin: 0 auto;
			max-width: 120px;
			height: auto;
		}

		/* Text Column - Centered */
		table[align="center"] > tbody > tr:first-child > td > table td[width="85%"] {
			text-align: center !important;
			vertical-align: middle;
		}

		table[align="center"] > tbody > tr:first-child > td > table td[width="85%"] h2 {
			text-align: center !important;
			margin-bottom: 0;
			font-family: "Baamini" !important;
		}

		table[align="center"] > tbody > tr:first-child > td > table td[width="85%"] p {
			text-align: center !important;
			font-size: 16px;
			margin: 5px 0px;
		}

		/* Responsive Design for Mobile */
		@media screen and (max-width: 768px) {
			div[style*="max-width: 210mm"] {
				max-width: 100% !important;
				padding: 10px;
			}
			
			table[align="center"] > tbody > tr:first-child > td > table {
				display: block !important;
			}
			
			table[align="center"] > tbody > tr:first-child > td > table tbody {
				display: block !important;
			}
			
			table[align="center"] > tbody > tr:first-child > td > table tr {
				display: block !important;
			}
			
			table[align="center"] > tbody > tr:first-child > td > table td[width="15%"],
			table[align="center"] > tbody > tr:first-child > td > table td[width="85%"] {
				display: block !important;
				width: 100% !important;
				text-align: center !important;
			}
			
			table[align="center"] > tbody > tr:first-child > td > table td[width="15%"] img {
				max-width: 100px;
				margin: 10px auto;
			}
			
			table[align="center"] > tbody > tr:first-child > td > table td[width="85%"] h2 {
				font-size: 18px;
				padding: 0 10px;
			}
			
			table[align="center"] > tbody > tr:first-child > td > table td[width="85%"] p {
				font-size: 14px;
				padding: 0 10px;
			}
			
			table[border="1"] {
				font-size: 12px;
			}
			
			table[border="1"] th,
			table[border="1"] td {
				padding: 5px !important;
				border: 1px solid #CCC !important;
			}

			table[border="1"] tbody tr td {
				border: 1px solid #CCC !important;
			}
		}

		/* Tablet View */
		@media screen and (min-width: 769px) and (max-width: 1024px) {
			div[style*="max-width: 210mm"] {
				max-width: 95% !important;
			}
			
			table[align="center"] > tbody > tr:first-child > td > table td[width="15%"] img {
				max-width: 100px;
			}
			
			table[align="center"] > tbody > tr:first-child > td > table td[width="85%"] h2 {
				font-size: 20px;
			}
			
			table[align="center"] > tbody > tr:first-child > td > table td[width="85%"] p {
				font-size: 15px;
			}
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
			@page {
				margin-top: 0.8in;
				margin-bottom: 0.5in;
				margin-left: 0.5in;
				margin-right: 0.5in;
			}

			/* Remove borders in print for header only */
			table[align="center"],
			table[align="center"] > tbody > tr:first-child > td,
			table[align="center"] > tbody > tr:first-child > td > table,
			table[align="center"] > tbody > tr:first-child > td > table td,
			table[align="center"] > tbody > tr:first-child > td > table tr {
				border: none !important;
			}

			/* Show HR line in print */
			table[align="center"] > tbody > tr:nth-child(2) {
				border: none !important;
			}

			table[align="center"] > tbody > tr:nth-child(2) td {
				border: none !important;
			}

			table[align="center"] > tbody > tr:nth-child(2) hr {
				display: block !important;
				border: none;
				border-top: 2px solid #333;
				margin: 10px 0;
			}

			/* Ensure data table borders in print - ALL ROWS */
			table[border="1"] {
				border: 1px solid #CCC !important;
				border-collapse: collapse !important;
			}

			table[border="1"] th,
			table[border="1"] td {
				border: 1px solid #CCC !important;
			}

			table[border="1"] tbody tr {
				border: 1px solid #CCC !important;
			}

			table[border="1"] tbody td {
				border: 1px solid #CCC !important;
			}

			/* Force borders on first rows in print */
			table[border="1"] tbody tr:first-child td,
			table[border="1"] tbody tr:nth-child(2) td,
			table[border="1"] tbody tr:nth-child(3) td {
				border: 1px solid #CCC !important;
			}

			/* Make header repeat on every page */
			table[align="center"] > tbody > tr:first-child {
				display: table-header-group !important;
				page-break-inside: avoid;
				page-break-after: avoid;
			}

			/* Center header in print */
			table[align="center"] > tbody > tr:first-child td {
				text-align: center !important;
			}

			table[align="center"] > tbody > tr:first-child > td > table td {
				text-align: center !important;
				vertical-align: middle !important;
			}

			table[align="center"] > tbody > tr:first-child > td > table td[width="15%"] {
				text-align: center !important;
			}

			table[align="center"] > tbody > tr:first-child > td > table td[width="85%"] {
				text-align: center !important;
			}

			table[align="center"] > tbody > tr:first-child > td > table h2 {
				text-align: center !important;
			}

			table[align="center"] > tbody > tr:first-child > td > table p {
				text-align: center !important;
			}

			table[align="center"] > tbody > tr:first-child > td > table img {
				display: block;
				margin: 0 auto;
			}

			/* HR line after header - keep it in header group */
			table[align="center"] > tbody > tr:nth-child(2) {
				display: table-header-group;
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
			
			.page-break {
				page-break-before: always;
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
			
			h2, h3, h4 {
				page-break-after: avoid;
			}
		}

		@media screen {
			.page-number {
				display: block;
				opacity: 0.7;
			}
		}
</style>
</head>
<body>
<div style="width: 100%;max-width: 210mm;margin:0 auto;">
<table align="center">
<tr>
	<td colspan="2">
		<table style="width:100%">
			<tr>
				<td width="15%" align="center">
					<img src="<?php echo base_url(); ?>/uploads/main/<?php echo $_SESSION['logo_img']; ?>" style="width:120px;" align="center">
				</td>
				<td width="85%" align="left">
					<h2 style="text-align:left;margin-bottom: 0;font-family: "Baamini" !important;"><?php echo $_SESSION['site_title']; ?></h2>
					<p style="text-align:left; font-size:16px; margin:5px 0px;"><?php echo $_SESSION['address1']; ?>,
					<br><?php echo $_SESSION['address2']; ?>,
					<br><?php echo $_SESSION['city']; ?> - <?php echo $_SESSION['postcode']; ?>
					<br>Tel : <?php echo $_SESSION['telephone']; ?></p>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr><td colspan="2"><hr></td></tr>
</table>
<div>
<?php 
$fdate = !empty($pdfdata['fdate']) && strtotime($pdfdata['fdate']) ? date('Y-m-d', strtotime($pdfdata['fdate'])) : null;
$tdate = !empty($pdfdata['tdate']) && strtotime($pdfdata['tdate']) ? date('Y-m-d', strtotime($pdfdata['tdate'])) : null;
$fltername = $pdfdata['fltername']; 
$collection_date = $pdfdata['collection_date']; 
?>


<?php if (!empty($fdate) && !empty($tdate)) { ?>
    <h3 style="text-align:center;"> PRASADAM VOUCHER <?php echo date("d/m/Y", strtotime($fdate)) . ' - ' . date("d/m/Y", strtotime($tdate)); ?></h3>
<?php } else { ?>
    <h3 style="text-align:center;"> PRASADAM VOUCHER </h3>
<?php } ?>

</td></tr>
</table>

<table border="1" width="100%" align="center">
    <thead>
		<tr>
			<th style="width:5%;text-align: center;">S.No</th>
			<th style="width:8%;text-align: center;">Date</th>
			<th style="width:9%;text-align: center;">Customer Name</th>
			<th style="width:12%;text-align: center;">Collection Date</th>
			<th style="width:15%;text-align:left;">Payfor</th>
			<th style="width:10%;text-align: center;">Amount</th>
    	</tr>
    </thead>
    <tbody>
     
    <?php 
		$total = 0; 
		// $fdt = !empty($fdate) ? date('Y-m-d', strtotime($fdate)) : null;
		// $tdt = !empty($tdate) ? date('Y-m-d', strtotime($tdate)) : null;
		$fdt = !empty($pdfdata['fdate']) && strtotime($pdfdata['fdate']) ? date('Y-m-d', strtotime($pdfdata['fdate'])) : null;
		$tdt = !empty($pdfdata['tdate']) && strtotime($pdfdata['tdate']) ? date('Y-m-d', strtotime($pdfdata['tdate'])) : null;
		$collection_dates = !empty($collection_date) ? date('Y-m-d', strtotime($collection_date)) : null;
		$fltername_fil= $fltername;
		$data = [];
		$dat = $db->table('prasadam')
        		->select('prasadam.id,prasadam.date,prasadam.customer_name,prasadam.collection_date,prasadam.total_amount as amount')
				->where('payment_status', 2);
        		// ->where('DATE_FORMAT(prasadam.date, "%Y-%m-%d") >=',$fdt);
        		// $dat = $dat->where('DATE_FORMAT(prasadam.date, "%Y-%m-%d") <=',$tdt);
        		// $dat = $dat->where('DATE_FORMAT(prasadam.collection_date, "%Y-%m-%d")',$collection_date);
				if (!empty($fdt) && !empty($tdt)) {
					$dat = $dat->where('prasadam.date >=', $fdt)
							->where('prasadam.date <=', $tdt);
				}
				// if (!empty($collection_dates)) {
				// 	$builder->where('prasadam.collection_date', $collection_dates);
				// }
				if($fltername_fil)
        		{
        		    $dat = $dat->where('prasadam.customer_name',$fltername_fil);
        		}
				$dat = $dat->orderBy('prasadam.date', 'asc');
        		$dat = $dat->get()->getResultArray();
              $sn=1;
        foreach($dat as $row){
			$payfors = $db->table('prasadam_booking_details')
				->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
				->select('prasadam_setting.name_eng, prasadam_setting.name_tamil, prasadam_booking_details.quantity')
				->where('prasadam_booking_details.prasadam_booking_id', $row['id'])
				->get()->getResultArray();

			$html = "";
			foreach ($payfors as $payfor) {
				$html .= "&#x2022; " . $payfor['name_eng']  . " (Quantity: " . $payfor['quantity'] . ")<br>";
			}
	?>
	<tr>
		<td style='text-align: center;'><?php echo $sn++; ?></td>
		<td style='text-align: center;'><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
		<td style='text-align: center;'> <?php echo $row ['customer_name']; ?></td>
		<td style='text-align: center;'><?php echo date('d-m-Y', strtotime($row['collection_date'])); ?></td>
		<td style='text-align: left;'><?php echo $html; ?></td>
		<td style='text-align: center;'><?php if($row['amount'] =='') 
		{ echo $row['amount']; } 
		else { echo number_format($row['amount'], '2','.',','); } ?></td>
	</tr>
	<?php } ?>   

    </tbody>
</table>
</div></div>
</body>
</html>