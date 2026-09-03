<?php $db = db_connect(); ?>

<body>
	<script src="<?php echo base_url(); ?>/assets/js/mui.min.js"
		integrity="sha512-5LSZkoyayM01bXhnlp2T6+RLFc+dE4SIZofQMxy/ydOs3D35mgQYf6THIQrwIMmgoyjI+bqjuuj4fQcGLyJFYg=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="<?php echo base_url(); ?>/assets/js/vconsole.min.js"></script>
	<script src="<?php echo base_url(); ?>/assets/plugins/jquery/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>/assets/js/imin-printer.js"></script>
	<script src="<?php echo base_url(); ?>/assets/js/dom-to-image.js"></script>

	<div id="archanai_ticket">
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
				font-size: 20px;
				text-align: center;
				font-weight: 600;
				font-family: monospace;
				margin: 0px
			}

			#archanai_ticket {
				color: #000;
				background: #fff;
				padding: 5px;
				font-weight: 600;
				font-family: monospace;
				display: none;
			}

			#archanai_loader {
				display: flex;
				justify-content: center;
				align-items: center;
				width: 100%;
				height: 100%;
			position: fixed;
		top: 0;
		left: 0;
		background: white;
	}
	#print_status{
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		background: white;
		padding: 20px;
		border: 2px solid #333;
		border-radius: 10px;
		font-size: 18px;
		display: none;
		z-index: 9999;
	}
	img{
		max-width: 100%;
	}
	</style>
	<?php
	$i = 1;
	foreach ($booking as $row) {
		$qty = $row['quantity'];
		for ($j = 0; $j < $qty; $j++) { ?>

			<div style="width: 150mm;font-weight: 600;font-family: monospace;" class="arc">
				<style>
					body { font-family: 'Barlow', sans-serif; background: #fff; box-sizing: border-box;}
					table { border-collapse:collapse; }
					table td { padding:5px; }
					hr {
					  border:none;
					  border-top:1px dashed #000;
					  color:#fff;
					  background-color:#fff;
					  height:1px;
					}
					p{font-size: 20px;text-align: center;font-weight: 600;font-family: monospace;margin: 0px}
					.arc{
						color: #000;
						background: #fff;
						padding: 5px;
						font-weight: 600;
						font-family: monospace;
					}
					img{
						max-width: 100%;
					}
				</style>
				<h2 style="text-align:center; margin:0; font-size:26px;"><?php echo $temp_details['name']; ?></h2>
				<p style="text-align:center; margin:0; font-size:26px;"><?php echo $temp_details['address1']; ?> 		<?php echo $temp_details['address2']; ?></br>
				<?php echo $temp_details['city'] . '-' . $temp_details['postcode']; ?>. 
				Tel: <?= $temp_details['telephone']; ?></p>
				<hr>
				<?php
				if (!empty($row['tharpanam_start']) && !empty($row['tharpanam_end'])) { ?>
					<br>
					<p><span style="font-size: 26px;">TOKEN NO:</span> <span style="border: 1px solid #000;padding: 7px 18px;border-radius: 100%;color: #000;font-size: 30px;font-weight: bold;"><?php echo $row['tharpanam_start'] + $j; ?></span></p>
					<br>
				<?php } ?>

				<p style="text-align: center;font-size:26px;">Date: <?php echo date('d-m-Y', strtotime($qry1['created'])); ?></p>
				<p style="text-align: center;font-size:26px;">Bill NO: <?php echo $qry1['ref_no']; ?></p>
				<hr>

				<?php
				$archanai_book_id = $qry1['id'];
				$payment_name = $db->table('archanai_payment_gateway_datas')->where('archanai_booking_id', $archanai_book_id)->get()->getRowArray();
				?>
		
				<?php if ($row['show_deity'] == 1) { ?>
						<div style="text-align: center;font-weight:bold; font-size: 35px; padding: 10px; margin: 20px auto; width: 75%;">
							<?= $row['diety_name']; ?>
							<p style="font-size: 25px;"><?= $row['diety_name_tamil']; ?></p>
						</div>
				<?php } ?>
		
				<?php if (!empty($row['watermark_image'])) { ?>
						<p style="text-align: center;"><img src="<?php echo base_url(); ?>/uploads/archanai/watermark/<?php echo $row['watermark_image']; ?>" style="width:160px;" align="center" alt="image" style="display:block;margin:0 auto;"></p>
				<?php } ?>
		
				<div style="display:flex; justify-content:center;align-items:center;border: 3px solid black; padding: 10px;">
					<div>
						<p style="font-size: 30px;text-align:left;"><?php echo $i++; ?></p>
					</div>
					<div style="width: 75%; text-align: center;">
						<p style="margin: 0; font-size: 40px;"><?= $row['name_eng']; ?></p>
						<p style="margin: 0; font-size: 40px;"><?= $row['name_tamil']; ?></p>
					</div>
				</div>
		
				<?php
				if ($row['archanai_category'] == 7) {
					$descriptions = json_decode($row['description'], true);
					if (is_array($descriptions) && !empty($descriptions)) {
						foreach ($descriptions as $description) {
							echo '<h3 style="text-align:center; font-weight: bold; font-size: 24px; margin-top: 5px; margin-bottom: 5px;">' . htmlspecialchars($description) . '</h3>';
						}
					}
				}
				?>
				<p><span style="font-size: 30px;">[RM<?= $row['amount']; ?> x 1 = RM<?= number_format($row['amount'], 2); ?>]</span></p>
				<p style="text-align: center; font-size: 40px;">PAID METHOD: <?php echo $payment_name['pay_method']; ?></p>
		
				<hr>
				<p style="text-align: center; font-size: 34px;">Total: RM <?= number_format($row['amount'], 2); ?></p>
		
				<?php
				if ($row['archanai_category'] == 2) {
					if (!empty($vehicles)) { ?>
							<hr>
							<br>
							<table style="width:100%;">
								<tr>
									<th align="left">Name</th>
									<th align="left">Vehicle No</th>
								</tr>
								<?php foreach ($vehicles as $vehicle) { ?>
										<tr>
											<td style="font-size:24px;"><?= $vehicle['name']; ?></td>
											<td style="font-size:24px;"><?= $vehicle['vehicle_no']; ?></td>
										</tr>
								<?php } ?>
							</table>
							<br>	
						<?php
					}
				}
				?>
		
				<?php
				foreach ($booking as $row1) {
					if ($row1['groupname'] == 'NAVAGRAHAM PEYERCHI') { ?>
							<p style="text-align:center;font-size:30px;text-transform:uppercase;">1 free small vilaku</p>
						<?php
					}
				}
				?>
		
				<?php if (!empty($rasi) && $row['archanai_category'] == 1) { ?>
						<hr>
						<table style="width:100%; font-size:20px">
							<tr><th align="left">Name</th><th align="left">Rasi</th><th align="left">Natchathram</th></tr>
							<?php foreach ($rasi as $res) { ?>
								<tr><td><?= $res['name']; ?></td>
								<td><?= $res['rasi_name_tamil']; ?><br><?= $res['rasi_name_eng']; ?></td>
								<td><?= $res['nat_name_tamil']; ?><br><?= $res['nat_name_eng']; ?></td></tr>
							<?php } ?>
						</table>
				<?php }
				echo ($setting["show_date"] ? "<div style='text-align:center'>Date " . Date("d-m-Y H:i:s") . "</div>" : "");
				?>
		
				<?php
				if (!empty($settings['archanai_slogan']))
					echo '<br><p style="text-align:center;">' . $settings['archanai_slogan'] . '</p>';
				?>
				<hr>
		
				<?php
				if (!empty($trans_details)) {
					echo '<br><hr>';
					foreach ($trans_details as $ky => $td) {
						echo '<p>' . $ky . ' ' . $td . '</p>';
					}
				}
				?>
			</div>
	
			<?php
			if ($row['archanai_category'] == 3) {
				echo '<div style="width: 150mm;font-weight: 600;font-family: monospace;" class="arc">
		<style>
			body { font-family: \'Barlow\', sans-serif; background: #fff; box-sizing: border-box;}
			table { border-collapse:collapse; }
			table td { padding:5px; }
			hr {
			  border:none;
			  border-top:1px dashed #000;
			  color:#fff;
			  background-color:#fff;
			  height:1px;
			}
			p{font-size: 20px;text-align: center;font-weight: 600;font-family: monospace;margin: 0px}
			.arc{
				color: #000;
				background: #fff;
				padding: 5px;
				font-weight: 600;
				font-family: monospace;
			}
			img{
				max-width: 100%;
			}
		</style>
		<br>';

				if (!empty($row['kazhanji_option'])) {
					if ($row['kazhanji_option'] == 'text') {
						echo '<p style="text-align:center;font-size:30px;">' . (!empty($row['kazhanji_option_text']) ? $row['kazhanji_option_text'] : '') . '</p>';
					} else {
						echo '<p><img src="' . (!empty($row['kazhanji_option_image']) ? base_url() . '/uploads/kazhanji/' . $row['kazhanji_option_image'] : '') . '" width="200" height="160" alt="image" style="display:block;margin:0 auto;"></p>';
					}
				}
				echo '<br></div>';
			}
			?>
	
		<?php
		}
	}
	?>
</div>

<div id="archanai_loader" class="archanai_loader">
	<img src="<?php echo base_url(); ?>/assets/images/loader.gif" />
</div>

<div id="print_status" class="print_status">
	<div>Printing Receipt <span id="current_receipt">1</span> of <span id="total_receipts">1</span></div>
	<div style="margin-top: 10px;">Please wait...</div>
</div>

<div class="test_div" style="display:none;"></div>

<script>
var vConsole = new VConsole();

$(document).ready(function(){
	var IminPrintInstance = new IminPrinter();
	console.log('IminPrintInstance created');
	
	IminPrintInstance.connect().then(async (connect) => {
		if (connect) {
			console.log('Printer connected successfully');
			$('#archanai_loader').hide();
			$('#archanai_ticket').show();
			
			// Initialize printer first
			IminPrintInstance.initPrinter();
			console.log('Printer initialized');
			
			// Wait longer for full initialization
			await new Promise(resolve => setTimeout(resolve, 1000));
			
			// Start the printing process
			initiate_load(IminPrintInstance);
		} else {
			alert('Error: Cannot connect to printer');
		}
	}).catch(function(error) {
		console.error('Connection error:', error);
		alert('Printer connection error: ' + error.message);
	});
});

function initiate_load(IminPrintInstance) {
	var tickets = [];
	var $arcElements = $('#archanai_ticket .arc');
	var totalCount = $arcElements.length;
	var processedCount = 0;
	
	console.log('Total receipts to print: ' + totalCount);
	
	// Show status
	$('#total_receipts').text(totalCount);
	$('#print_status').show();
	
	// Process each receipt element with a slight delay between conversions
	$arcElements.each(function(index) {
		var node = this;
		
		// Add delay between image conversions to prevent memory issues
		setTimeout(function() {
			domtoimage.toJpeg(node, {
				quality: 0.95,
				bgcolor: '#ffffff'
			}).then(function(dataUrl) {
				tickets[index] = dataUrl;
				processedCount++;
				
				console.log('Processed image ' + (index + 1) + ' of ' + totalCount);
				
				// When all images are processed, start printing
				if (processedCount === totalCount) {
					console.log('All images processed, starting print queue');
					// Add a small delay before starting print
					setTimeout(function() {
						print_queue(IminPrintInstance, tickets, 0);
					}, 500);
				}
			}).catch(function(error) {
				console.error('Error generating image for receipt ' + (index + 1), error);
				processedCount++;
				
				// Continue even if one fails
				if (processedCount === totalCount) {
					setTimeout(function() {
						print_queue(IminPrintInstance, tickets, 0);
					}, 500);
				}
			});
		}, index * 100); // 100ms delay between each image conversion
	});
}

async function print_queue(IminPrintInstance, tickets, currentIndex) {
	if (currentIndex < tickets.length) {
		// Skip if no image data
		if (!tickets[currentIndex]) {
			console.log('Skipping receipt ' + (currentIndex + 1) + ' - no image data');
			print_queue(IminPrintInstance, tickets, currentIndex + 1);
			return;
		}
		
		// Update status
		$('#current_receipt').text(currentIndex + 1);
		
		console.log('Printing receipt ' + (currentIndex + 1) + ' of ' + tickets.length);
		
		try {
			// Check printer status only every 3 receipts or on first
			if (currentIndex === 0 || currentIndex % 3 === 0) {
				const status = await IminPrintInstance.getPrinterStatus();
				console.log('Printer status check at receipt ' + (currentIndex + 1) + ':', status);
				
				// If printer has error, wait and retry
				if (status && status.value && status.value !== '0') {
					console.log('Printer error detected, waiting...');
					await new Promise(resolve => setTimeout(resolve, 2000));
				}
			}
			
			console.log('Image print start for receipt ' + (currentIndex + 1));
			
			// Print the bitmap
			await IminPrintInstance.printSingleBitmap(tickets[currentIndex]);
			
			console.log('Image print complete for receipt ' + (currentIndex + 1));
			
			// Small wait for image to be fully sent
			await new Promise(resolve => setTimeout(resolve, 100));
			
			// Feed and cut
			console.log('Sending feed and cut commands');
			IminPrintInstance.printAndFeedPaper(100);
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
				setTimeout(function() {
					console.log('Closing window');
					window.close();
				}, 500);
			} else {
				// IMPORTANT: Increased wait time between receipts for stability
				// This prevents buffer overflow and ensures each receipt completes
				console.log('Waiting before next receipt...');
				
				// Calculate dynamic wait time based on receipt number
				// More receipts = slightly longer wait to prevent issues
				let waitTime = 300; // Base wait time
				if (currentIndex >= 2) {
					waitTime = 400; // Increase wait after 3rd receipt
				}
				if (currentIndex >= 4) {
					waitTime = 500; // Even more wait after 5th receipt
				}
				
				await new Promise(resolve => setTimeout(resolve, waitTime));
				console.log('Wait complete, processing next receipt');
				
				// Process next receipt
				print_queue(IminPrintInstance, tickets, currentIndex + 1);
			}
			
		} catch (error) {
			console.error('Error printing receipt ' + (currentIndex + 1), error);
			
			// Hide status on error
			$('#print_status').hide();
			
			// Wait a bit before continuing
			await new Promise(resolve => setTimeout(resolve, 500));
			
			// Continue with next receipt even if this one fails
			print_queue(IminPrintInstance, tickets, currentIndex + 1);
		}
		
	} else {
		// Fallback - should not normally reach here
		console.log('All receipts printed successfully (fallback)');
		$('#print_status').hide();
		IminPrintInstance.openCashBox();
		setTimeout(function() {
			window.close();
		}, 500);
	}
}
</script>
</body>