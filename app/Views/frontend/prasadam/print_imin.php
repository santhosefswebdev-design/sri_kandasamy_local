<?php /*
<script src="https://cdnjs.cloudflare.com/ajax/libs/mui/3.7.1/js/mui.min.js"
integrity="sha512-5LSZkoyayM01bXhnlp2T6+RLFc+dE4SIZofQMxy/ydOs3D35mgQYf6THIQrwIMmgoyjI+bqjuuj4fQcGLyJFYg=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/vConsole/3.9.1/vconsole.min.js"></script>
*/ ?>
<script src="<?php echo base_url(); ?>/assets/js/mui.min.js"
	integrity="sha512-5LSZkoyayM01bXhnlp2T6+RLFc+dE4SIZofQMxy/ydOs3D35mgQYf6THIQrwIMmgoyjI+bqjuuj4fQcGLyJFYg=="
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?php echo base_url(); ?>/assets/js/vconsole.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/imin-printer.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/dom-to-image.js"></script>
<?php
function getNotesDisplayText($noteValue)
{
	$notesMap = [
		'keep_till_they_come' => 'Keep Till They Come',
		'will_come' => 'Will Come',
		'can_distribute' => 'Can Distribute',
		'taking_outside' => 'Taking Outside',
		'half_to_temple_half_to_thithi' => '1/2 To Temple & 1/2 To Thithi',
		'1_pack_balance_distribute' => '1 Pack & Balance Distribute',
		'2_pack_balance_distribute' => '2 Pack & Balance Distribute',
		'3_pack_balance_distribute' => '3 Pack & Balance Distribute',
	];
	return isset($notesMap[$noteValue]) ? $notesMap[$noteValue] : $noteValue;
}
?>
<div style="width: 150mm;font-weight: 600;font-family: monospace; display: none;" id="booking_tickets">
	<?php /*
	<div style="width: 150mm;font-weight: 600;font-family: monospace;" class="booking_ticket" id="booking_ticket_1">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="<?php echo base_url(); ?>/assets/css/Barlow.css" rel="stylesheet">
		<style>
			body {
				font-family: 'Barlow', sans-serif;
				background: #fff;
				box-sizing: border-box;
			}

			table {
				border-collapse: collapse;
			}

			table td {
				padding: 5px;
			}

			hr {
				border: none;
				border-top: 1px dashed #000;
				color: #fff;
				background-color: #fff;
				height: 1px;
			}

			p {
				font-size: 26px;
				text-align: center;
				font-weight: 600;
				font-family: monospace;
				margin: 0px
			}

			h3 {
				font-size: 34px;
				text-align: center;
				font-weight: 600;
				font-family: monospace;
				text-transform: uppercase;
			}

			tr td,
			tr th {
				font-size: 26px;
			}

			.booking_ticket {
				color: #000;
				background: #fff;
				padding: 5px;
				display: block;
			}

			img {
				max-width: 100%;
			}
		</style>
		<p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
		<h3 style="max-width: 150mm;margin: 5px 0;">Office Copy</h3>
		<p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
		<br>

		<p><img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>" style="width:120px;"
				align="center"></p>
		<h2 style="text-align:center; margin:0;font-size: 26px;"><?php echo $temp_details['name']; ?></h2>
		<p style="font-size: 26px;"><?php echo $temp_details['address1']; ?>,
			<?php echo $temp_details['address2']; ?></br>
			<?php echo $temp_details['city'] . '-' . $temp_details['postcode']; ?>.
			Tel: <?= $temp_details['telephone']; ?>
		</p>
		<hr>
		<p style="text-align: center;">Bill No:
			<?php echo $qry1['ref_no']; ?>
		</p>
		<p style="text-align: center;">Date:
			<?php echo date('d-m-Y', strtotime($qry1['date'])); ?>
		</p>
		<p style="text-align: center;">Customer Name:
			<?php echo $qry1['customer_name']; ?>
		</p>
		<p style="text-align: center;">Mobile No:
			<?php echo $qry1['mobile_no']; ?>
		</p>
		<?php if (!empty($qry1['collection_date']) && $qry1['collection_date'] != '0000-00-00'): ?>
			<p style="text-align: center;">
				Collection Date: <?php echo date('d-m-Y', strtotime($qry1['collection_date'])); ?>
			</p>
		<?php endif; ?>

		<?php if (!empty($qry1['serve_time'])): ?>
			<p style="text-align: center;">Collection Time: <?php echo $qry1['serve_time']; ?></p>
		<?php endif; ?>
		<p style="text-align: center;">Remarks:
			<?php echo $qry1['desciption']; ?>
		</p>
		<p style="text-align: center;">
			Deity Name (English): <?= $qry1['diety_name_eng']; ?><br>
			Deity Name (Tamil): <?= $qry1['diety_name_tamil']; ?>
		</p>
		<?php if (!empty($qry1['prasadam_notes'])): ?>
			<p style="text-align: center; padding: 5px; margin: 10px 0; border-radius: 5px;">
				<strong>Distribution Notes:</strong><br>
				<?php echo getNotesDisplayText($qry1['prasadam_notes']); ?>
			</p>
		<?php endif; ?>

		<hr>
		<p style="text-align: left;">SNO&nbsp;&nbsp;PARTICULARS</p>
		<hr>
		<?php $total = 0;
		$i = 1;
		$printed_groups = [];
		foreach ($qry1_payfor as $row) {
			if (!in_array($row['groupname'], $printed_groups) && $row['groupname'] !== 'General') {
				echo '<div style="text-align: center; font-size: 30px; border: 2px solid #000; padding: 10px; margin: 20px auto; width: 75%; border-radius: 10px; background-color: #f9f9f9;">
						<p style="font-size: 25px;">' . $row['groupname'] . '</p>
					</div>';
				$printed_groups[] = $row['groupname'];
			}
			?>

			<p style="text-align: left;">
				<?= $i++; ?>&nbsp;&nbsp;
				<?= $row['name_eng']; ?><br>&nbsp;&nbsp;
				<?= $row['name_tamil']; ?><br>&nbsp;&nbsp;
				<span style="">[RM
					<?= $row['amount']; ?> x
					<?= $row['quantity']; ?> = RM
					<?= number_format($row['quantity'] * $row['amount'], 2); ?>]
				</span>
			</p>

			<p style="text-align: center;"><span> ----- </span> </p>
			<br>
			<?php $total += $row['quantity'] * $row['amount'];
		} ?><br><br>

		<hr>
		<p style="text-align: center; font-size: 26px;">Total: RM
			<?= number_format($total, 2); ?>
		</p>
		<?php if (!empty($qry1['payment_mode_name'])): ?>
			<p style="text-align: center;">Payment Mode: <?= $qry1['payment_mode_name']; ?></p>
		<?php endif; ?>

		<?php if (!empty($qry1['discount_amount'])) { ?>
			<p style="text-align: center; font-size: 26px;">Discount: RM
				-<?= number_format($qry1['discount_amount'], 2); ?>
			</p>
			<?php
			$grand_total = $total - $qry1['discount_amount'];
			?>
			<p style="text-align: center; font-size: 26px;">Grand Total: RM
				<?= number_format($grand_total, 2); ?>
			</p>
		<?php } ?>

		<hr>
		<?php if ($qry1['payment_type'] == 'partial') { ?>
			<?php $balance = $qry1['amount'] - $qry1['paid_amount']; ?>
			<p style="text-align: center; font-size: 26px;">Paid Amount: RM
				<?= number_format($qry1['paid_amount'], 2); ?>
			</p>
			<p style="text-align: center; font-size: 26px;">Balance Amount: RM
				<?= number_format($balance, 2); ?>
			</p>
			<hr>
		<?php } ?>

	</div>
	*/?>
	<div style="width: 150mm;font-weight: 600;font-family: monospace;" class="booking_ticket" id="booking_ticket_2">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="<?php echo base_url(); ?>/assets/css/Barlow.css" rel="stylesheet">
		<style>
			body {
				font-family: 'Barlow', sans-serif;
				background: #fff;
				box-sizing: border-box;
			}

			table {
				border-collapse: collapse;
			}

			table td {
				padding: 5px;
			}

			hr {
				border: none;
				border-top: 1px dashed #000;
				color: #fff;
				background-color: #fff;
				height: 1px;
			}

			p {
				font-size: 22px;
				text-align: center;
				font-weight: 600;
				font-family: monospace;
				margin: 0px
			}

			h3 {
				font-size: 34px;
				text-align: center;
				font-weight: 600;
				font-family: monospace;
				text-transform: uppercase;
			}

			tr td,
			tr th {
				font-size: 22px;
			}

			.booking_ticket {
				color: #000;
				background: #fff;
				padding: 5px;
				display: block;
			}

			img {
				max-width: 100%;
			}
		</style>
		<p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
		<h3 style="max-width: 150mm;margin: 5px 0;">Customer Copy</h3>
		<p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
		<br>

		<p><img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>" style="width:120px;"
				align="center"></p>
		<h2 style="text-align:center; margin:0"><?php echo $temp_details['name']; ?></h2>
		<p><?php echo $temp_details['address1']; ?>, <?php echo $temp_details['address2']; ?></br>
			<?php echo $temp_details['city'] . '-' . $temp_details['postcode']; ?>.
			Tel: <?= $temp_details['telephone']; ?></p>
		<hr>
		<p style="text-align: center;">Bill No:
			<?php echo $qry1['ref_no']; ?>
		</p>
		<p style="text-align: center;">Date:
			<?php echo date('d-m-Y', strtotime($qry1['date'])); ?>
		</p>
		<p style="text-align: center;">Customer Name:
			<?php echo $qry1['customer_name']; ?>
		</p>
		<p style="text-align: center;">Mobile No:
			<?php echo $qry1['mobile_no']; ?>
		</p>
		<?php if (!empty($qry1['collection_date']) && $qry1['collection_date'] != '0000-00-00'): ?>
			<p style="text-align: center;">
				Collection Date: <?php echo date('d-m-Y', strtotime($qry1['collection_date'])); ?>
			</p>
		<?php endif; ?>

		<?php if (!empty($qry1['serve_time'])): ?>
			<p style="text-align: center;">Collection Time: <?php echo $qry1['serve_time']; ?></p>
		<?php endif; ?>
	
		<p style="text-align: center;">Remarks:
			<?php echo $qry1['desciption']; ?>
		</p>
		<p style="text-align: center;">
			Deity Name (English): <?= $qry1['diety_name_eng']; ?><br>
			Deity Name (Tamil): <?= $qry1['diety_name_tamil']; ?>
		</p>
		<?php if (!empty($qry1['prasadam_notes'])): ?>
			<p style="text-align: center; padding: 5px; margin: 10px 0; border-radius: 5px;">
				<strong>Distribution Notes:</strong><br>
				<?php echo getNotesDisplayText($qry1['prasadam_notes']); ?>
			</p>
		<?php endif; ?>

		<hr>
		<p style="text-align: left;">S.NO&nbsp;&nbsp;PARTICULARS</p>
		<hr>
		<?php $total = 0;
		$i = 1;
		$printed_groups = [];
		foreach ($qry1_payfor as $row) {
			if (!in_array($row['groupname'], $printed_groups) && $row['groupname'] !== 'General') {
				echo '<div style="text-align: center; font-size: 30px; border: 2px solid #000; padding: 10px; margin: 20px auto; width: 75%; border-radius: 10px; background-color: #f9f9f9;">
						<p style="font-size: 25px;">' . $row['groupname'] . '</p>
					</div>';
				$printed_groups[] = $row['groupname'];
			}
			?>

			<p style="text-align: left;">
				<?= $i++; ?>&nbsp;&nbsp;
				<?= $row['name_eng']; ?><br>&nbsp;&nbsp;
				<?= $row['name_tamil']; ?><br>&nbsp;&nbsp;
				<span style="">[RM
					<?= $row['amount']; ?> x
					<?= $row['quantity']; ?> = RM
					<?= number_format($row['quantity'] * $row['amount'], 2); ?>]
				</span>
			</p>

			<p style="text-align: center;"><span> ----- </span> </p>
			<br>
			<?php $total += $row['quantity'] * $row['amount'];
		} ?>
		<hr>
		<p style="text-align: center; font-size: 26px;">Total: RM
			<?= number_format($total, 2); ?>
		</p>
		<?php if (!empty($qry1['payment_mode_name'])): ?>
			<p style="text-align: center;">Payment Mode: <?= $qry1['payment_mode_name']; ?></p>
		<?php endif; ?>
		<?php if (!empty($qry1['discount_amount'])) { ?>
			<p style="text-align: center; font-size: 26px;">Discount: RM
				-<?= number_format($qry1['discount_amount'], 2); ?>
			</p>
			<?php
			$grand_total = $total - $qry1['discount_amount'];
			?>
			<p style="text-align: center; font-size: 26px;">Grand Total: RM
				<?= number_format($grand_total, 2); ?>
			</p>
		<?php } ?>

		<hr>
		<?php if ($qry1['payment_type'] == 'partial') { ?>
			<?php $balance = $qry1['amount'] - $qry1['paid_amount']; ?>
			<p style="text-align: center; font-size: 26px;">Paid Amount: RM
				<?= number_format($qry1['paid_amount'], 2); ?>
			</p>
			<p style="text-align: center; font-size: 26px;">Balance Amount: RM
				<?= number_format($balance, 2); ?>
			</p>
			<hr>
		<?php } ?>

	</div>
	<div style="width: 150mm;font-weight: 600;font-family: monospace;" class="booking_ticket" id="booking_ticket_3">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="<?php echo base_url(); ?>/assets/css/Barlow.css" rel="stylesheet">
		<style>
			body {
				font-family: 'Barlow', sans-serif;
				background: #fff;
				box-sizing: border-box;
			}

			table {
				border-collapse: collapse;
			}

			table td {
				padding: 5px;
			}

			hr {
				border: none;
				border-top: 1px dashed #000;
				color: #fff;
				background-color: #fff;
				height: 1px;
			}

			p {
				font-size: 26px;
				text-align: center;
				font-weight: 600;
				font-family: monospace;
				margin: 0px
			}

			h3 {
				font-size: 34px;
				text-align: center;
				font-weight: 600;
				font-family: monospace;
				text-transform: uppercase;
			}

			tr td,
			tr th {
				font-size: 26px;
			}

			.booking_ticket {
				color: #000;
				background: #fff;
				padding: 5px;
				display: block;
			}

			img {
				max-width: 100%;
			}
		</style>
		<p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
		<h3 style="max-width: 150mm;margin: 5px 0;">Madapalli Report</h3>
		<p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
		<br>
<p style="text-align: center;">Customer Name:
			<?php echo $qry1['customer_name']; ?>
		</p>
		<p style="text-align: center;">Mobile No:
			<?php echo $qry1['mobile_no']; ?>
		</p>
		<p style="text-align: center;">Date:
			<?php echo date('d-m-Y', strtotime($qry1['date'])); ?>
		</p>
		<p style="text-align: center;">Collection Date:
			<?php echo date('d-m-Y', strtotime($qry1['collection_date'])); ?>
		</p>
		<p style="text-align: center;">Session:
			<?php echo $qry1['session']; ?>
		</p>
		<p style="text-align: center;">
			Time: <?php echo $qry1['serve_time']; ?>
		</p>
		<?php if (!empty($qry1['prasadam_notes'])): ?>
			<p style="text-align: center; padding: 5px; margin: 10px 0; border-radius: 5px;">
				<strong>Distribution Notes:</strong><br>
				<?php echo getNotesDisplayText($qry1['prasadam_notes']); ?>
			</p>
		<?php endif; ?>
		<hr>
		<table border="1" width="100%" style="border-collapse: collapse; text-align: left;">
			<thead>
				<tr>
					<th style="padding: 5px;text-align: center;">S.No</th>
					<th style="padding: 5px;text-align: center;">Particulars</th>
					<th style="padding: 5px;text-align: center;">Qty</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$total = 0;
				$i = 1;
				foreach ($qry1_payfor as $row) { ?>
					<tr>
						<td style="padding: 5px;text-align: center;"><?= $i++; ?></td>
						<td style="padding: 5px;text-align: center;">
							<?= $row['name_eng']; ?><br>
							<?= $row['name_tamil']; ?>
						</td>
						<td style="padding: 5px;text-align: center;"><?= $row['quantity']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<hr>
	</div>
</div>

<div id="ticket_loader" style="display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;">
	<img src="<?php echo base_url(); ?>/assets/images/loader.gif" />
</div>

<div id="print_status"
	style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 2px solid #333; border-radius: 10px; font-size: 18px; display: none; z-index: 10000; text-align: center;">
	<div>Printing Receipt <span id="current_receipt">1</span> of <span id="total_receipts">3</span></div>
	<div style="margin-top: 10px;">Please wait...</div>
</div>

<script>
	var vConsole = new VConsole();

	$(document).ready(function () {
		console.log('Document ready, starting printer connection...');
		var IminPrintInstance = new IminPrinter();
		console.log('IminPrintInstance created');

		IminPrintInstance.connect().then(async (connect) => {
			if (connect) {
				console.log('Printer connected successfully');

				// Initialize printer
				IminPrintInstance.initPrinter();
				console.log('Printer initialized');

				// Wait for initialization
				await new Promise(resolve => setTimeout(resolve, 1000));

				// Check printer status
				try {
					const status = await IminPrintInstance.getPrinterStatus();
					console.log('Printer status:', status);

					if (status && status.value && status.value !== '0') {
						console.log('Printer error detected, waiting...');
						await new Promise(resolve => setTimeout(resolve, 2000));
					}
				} catch (error) {
					console.log('Could not get printer status:', error);
				}

				// Hide loader and show tickets
				$('#ticket_loader').hide();
				$('#booking_tickets').show();
				$('#print_status').show();

				// Wait for DOM to render
				await new Promise(resolve => setTimeout(resolve, 500));

				// Start the printing process
				initiate_load(IminPrintInstance);
			} else {
				console.error('Failed to connect to printer');
				alert('Error: Cannot connect to printer. Please check if the printer is connected and powered on.');
			}
		}).catch(function (error) {
			console.error('Connection error:', error);
			alert('Printer connection error: ' + error.message);
		});
	});

	async function initiate_load(IminPrintInstance) {
		var tickets = [];
		var bookingTickets = $('#booking_tickets .booking_ticket');
		var totalCount = bookingTickets.length;

		console.log('Total receipts to print: ' + totalCount);
		$('#total_receipts').text(totalCount);

		// Convert each ticket to image sequentially with proper timing
		for (let i = 0; i < bookingTickets.length; i++) {
			let node = bookingTickets[i];
			console.log('Converting receipt ' + (i + 1) + ' to image...');

			try {
				// Add a small delay between conversions
				if (i > 0) {
					await new Promise(resolve => setTimeout(resolve, 200));
				}

				let dataUrl = await domtoimage.toJpeg(node, {
					quality: 0.95,
					bgcolor: '#ffffff',
					width: node.scrollWidth,
					height: node.scrollHeight
				});

				tickets.push(dataUrl);
				console.log('Converted receipt ' + (i + 1) + ' successfully');
			} catch (error) {
				console.error('Error converting receipt ' + (i + 1), error);
				tickets.push(null);
			}
		}

		console.log('All receipts converted, starting print queue');

		// Start printing after a short delay
		await new Promise(resolve => setTimeout(resolve, 500));
		await print_queue(IminPrintInstance, tickets, 0);
	}

	async function print_queue(IminPrintInstance, tickets, currentIndex) {
		if (currentIndex < tickets.length) {
			// Skip if no image data
			if (!tickets[currentIndex]) {
				console.log('Skipping receipt ' + (currentIndex + 1) + ' - no image data');
				await print_queue(IminPrintInstance, tickets, currentIndex + 1);
				return;
			}

			// Update status
			$('#current_receipt').text(currentIndex + 1);

			console.log('Printing receipt ' + (currentIndex + 1) + ' of ' + tickets.length);

			try {
				// Check printer status only on first receipt
				if (currentIndex === 0) {
					try {
						const status = await IminPrintInstance.getPrinterStatus();
						console.log('Initial printer status:', status);

						if (status && status.value && status.value !== '0') {
							console.log('Printer issue detected, waiting...');
							await new Promise(resolve => setTimeout(resolve, 2000));
						}
					} catch (error) {
						console.log('Status check error:', error);
					}
				}

				console.log('Sending image to printer...');

				// Print the bitmap
				await IminPrintInstance.printSingleBitmap(tickets[currentIndex]);

				console.log('Image sent successfully');

				// Wait for image to be fully printed
				await new Promise(resolve => setTimeout(resolve, 200));

				// Feed paper
				console.log('Feeding paper...');
				IminPrintInstance.printAndFeedPaper(100);

				// Wait for feed to complete
				await new Promise(resolve => setTimeout(resolve, 150));

				// Cut the paper
				console.log('Cutting paper...');
				IminPrintInstance.partialCut();

				// Check if this is the last receipt
				const isLastReceipt = currentIndex === tickets.length - 1;

				if (isLastReceipt) {
					console.log('Last receipt printed');

					// Hide status
					$('#print_status').hide();

					// Open cash box
					IminPrintInstance.openCashBox();
					console.log('Cash box opened');

					// Close window after a short delay
					setTimeout(function () {
						console.log('Closing window');
						window.close();
					}, 500);
				} else {
					// Wait between receipts
					console.log('Waiting before next receipt...');

					// Dynamic wait time based on receipt number
					let waitTime = 300;
					if (currentIndex >= 1) {
						waitTime = 400;
					}

					await new Promise(resolve => setTimeout(resolve, waitTime));

					// Process next receipt
					await print_queue(IminPrintInstance, tickets, currentIndex + 1);
				}

			} catch (error) {
				console.error('Error printing receipt ' + (currentIndex + 1), error);
				alert('Print error: ' + error.message);

				// Hide status on error
				$('#print_status').hide();

				// Wait a bit before continuing
				await new Promise(resolve => setTimeout(resolve, 500));

				// Continue with next receipt
				await print_queue(IminPrintInstance, tickets, currentIndex + 1);
			}

		} else {
			// All receipts printed
			console.log('All receipts printed successfully');
			$('#print_status').hide();
			IminPrintInstance.openCashBox();
			setTimeout(function () {
				window.close();
			}, 500);
		}
	}
</script>