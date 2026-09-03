<title><?php echo $_SESSION['site_title']; ?></title>
<?php
$db = db_connect();
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow&display=swap" rel="stylesheet">
<style>
	body {
		font-family: 'Barlow', sans-serif;
	}

	.tbor,
	th {
		border: 1px solid;
	}

	table th {
		background-color: #444242 !important;
		color: #fff;
	}

	table {
		border-collapse: collapse;
	}

	table td,
	table th {
		padding: 5px;
	}

	.inner_table tr:nth-child(even) {
		background: #F3F3F3;
	}

	.inner_table tr:last-child {
		background: #e2dfdf;
	}

	.paid_text {
		color: green;
		font-weight: 600;
	}

	.unpaid_text {
		color: blue;
		font-weight: 600;
	}

	.cancel_text {
		color: red;
		font-weight: 600;
	}

	.only_booking_text {
		color: orange;
		font-weight: 600;
	}

	.header-table {
		width: 100%;
		max-width: 800px;
		margin: 0 auto;
	}

	.header-cell {
		text-align: center;
	}

	.logo {
		width: 150px;
		display: block;
		margin: 0 auto 6px;
	}

	.site-title {
		margin: 0;
		font-size: 16px;
		font-weight: 700;
	}

	.meta {
		margin: 6px 0 0;
		font-size: 13px;
		line-height: 1.35;
	}

	.hr {
		border: 0;
		border-top: 1px solid #333;
		margin: 10px 0 14px;
	}
</style>

<table align="center" style="width: 100%;max-width: 800px;">
	<tr>
		<td colspan="2">
			<table class="header-table">
				<tr>
					<td class="header-cell">
						<img class="logo"
							src="<?php echo base_url(); ?>/uploads/main/<?php echo $_SESSION['logo_img']; ?>"
							alt="Logo">
						<h2 class="site-title"><?php echo $_SESSION['site_title']; ?></h2>
						<p class="meta">
							<?php echo $_SESSION['address1']; ?>,<br>
							<?php echo $_SESSION['address2']; ?>,<br>
							<?php echo $_SESSION['city']; ?> - <?php echo $_SESSION['postcode']; ?><br>
							Tel : <?php echo $_SESSION['telephone']; ?>
						</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr>
		</td>
	</tr>

	<tr>
		<td colspan="2" style="width: 100%;">
			<h3 style="text-align:center;">UBAYAM REPORT
				<?php echo date("d/m/Y", strtotime($fdate)) . ' - ' . date("d/m/Y", strtotime($tdate)); ?>
			</h3>
			<?php if (!empty($event_date_from) && !empty($event_date_to)) { ?>
				<p style="text-align:center; margin:5px 0; font-size:14px;">
					<strong>Event Date:</strong>
					<?php echo date("d/m/Y", strtotime($event_date_from)) . ' - ' . date("d/m/Y", strtotime($event_date_to)); ?>
				</p>
			<?php } elseif (!empty($event_date_from)) { ?>
				<p style="text-align:center; margin:5px 0; font-size:14px;">
					<strong>Event Date From:</strong> <?php echo date("d/m/Y", strtotime($event_date_from)); ?>
				</p>
			<?php } elseif (!empty($event_date_to)) { ?>
				<p style="text-align:center; margin:5px 0; font-size:14px;">
					<strong>Event Date To:</strong> <?php echo date("d/m/Y", strtotime($event_date_to)); ?>
				</p>
			<?php } ?>
		</td>
	</tr>
</table>

<table border="1" width="100%" align="center">
	<thead>
		<tr>
			<th width="5%">S.No</th>
			<th align="left" width="10%">Booking Date</th>
			<th align="left" width="10%">Event Date</th>
			<th align="left" width="25%">Event Name</th>
			<th align="left" width="25%">Name</th>
			<th align="right" width="9%">Amount</th>
			<th align="right" width="8%">Status</th>
		</tr>
	</thead>
	<tbody>

		<?php
		$total = 0;
		$fdt = date('Y-m-d', strtotime($fdate));
		$tdt = date('Y-m-d', strtotime($tdate));

		// IMPORTANT: Extract event date filters
		$event_date_from = isset($event_date_from) && !empty($event_date_from) ? date('Y-m-d', strtotime($event_date_from)) : null;
		$event_date_to = isset($event_date_to) && !empty($event_date_to) ? date('Y-m-d', strtotime($event_date_to)) : null;

		$payfor_fil = $payfor;
		$booking_type = $booking_type;
		$fltername_fil = $fltername;
		$data = [];

		$dat = $db->table('templebooking', 'booked_packages.name as pname')
			->join('booked_packages', 'booked_packages.booking_id = templebooking.id')
			->select('booked_packages.name as pname')
			->select('templebooking.*')
			->where('DATE_FORMAT(templebooking.entry_date, "%Y-%m-%d") >=', $fdt)
			->where('DATE_FORMAT(templebooking.entry_date, "%Y-%m-%d") <=', $tdt);

		// APPLY EVENT DATE FILTERS - THIS WAS MISSING!
		if (!empty($event_date_from)) {
			$dat = $dat->where('templebooking.booking_date >=', $event_date_from);
		}
		if (!empty($event_date_to)) {
			$dat = $dat->where('templebooking.booking_date <=', $event_date_to);
		}

		if ($payfor_fil) {
			$dat = $dat->where('booked_packages.package_id', $payfor_fil);
		}
		if ($booking_type) {
			$dat = $dat->where('booked_packages.booking_type', $booking_type);
		}
		if ($fltername_fil) {
			$dat = $dat->where('templebooking.name', $fltername_fil);
		}

		$dat = $dat->orderBy('templebooking.entry_date', 'desc');
		$dat = $dat->get()->getResultArray();

		$sn = 1;
		$total_amount = 0;

		foreach ($dat as $row) {
			$amount = (float) ($row['amount'] ?? 0);
			$paid = (float) ($row['paid_amount'] ?? 0);
			$balance_amount = $amount - $paid;
			if ($balance_amount < 0)
				$balance_amount = 0;

			$total_amount += $amount;

			// Status logic
			if ((int) $row['booking_status'] === 3) {
				$statusHtml = '<span class="cancel_text">Cancelled</span>';
			} else {
				if ($paid <= 0) {
					$statusHtml = '<span class="only_booking_text">Only Booking</span>';
				} elseif ($balance_amount == 0) {
					$statusHtml = '<span class="paid_text">Paid</span>';
				} else {
					$statusHtml = '<span class="unpaid_text">Partially Paid</span>';
				}
			}
			?>
			<tr>
				<td><?php echo $sn++; ?></td>
				<td><?php echo date('d-m-Y', strtotime($row['entry_date'])); ?></td>
				<td><?php echo date('d-m-Y', strtotime($row['booking_date'])); ?></td>
				<td><?php echo $row['pname']; ?></td>
				<td><?php echo $row['name']; ?></td>
				<td align="right"><?php echo $row['amount'] ? number_format($row['amount'], 2, '.', ',') : '0.00'; ?></td>
				<td><?php echo $statusHtml; ?></td>
			</tr>
		<?php } ?>

		<?php if ($sn > 1) { ?>
			<!-- Total Row -->
			<tr style="background-color: #f0f0f0; font-weight: bold;">
				<td colspan="5" align="right">TOTAL:</td>
				<td align="right"><?php echo number_format($total_amount, 2, '.', ','); ?></td>
				<td></td>
			</tr>
		<?php } ?>

		<?php if ($sn == 1) { ?>
			<!-- No Records -->
			<tr>
				<td colspan="7" align="center" style="padding: 20px; color: red;">
					No records found for the selected criteria.
				</td>
			</tr>
		<?php } ?>

	</tbody>
</table>

<script>
	window.print();
</script>