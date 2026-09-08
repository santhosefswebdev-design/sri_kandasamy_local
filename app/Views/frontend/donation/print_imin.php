<body>
	<script src="<?php echo base_url(); ?>/assets/js/mui.min.js"
		integrity="sha512-5LSZkoyayM01bXhnlp2T6+RLFc+dE4SIZofQMxy/ydOs3D35mgQYf6THIQrwIMmgoyjI+bqjuuj4fQcGLyJFYg=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="<?php echo base_url(); ?>/assets/js/vconsole.min.js"></script>
	<script src="<?php echo base_url(); ?>/assets/plugins/jquery/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>/assets/js/imin-printer.js"></script>
	<script src="<?php echo base_url(); ?>/assets/js/dom-to-image.js"></script>

	<div style="width: 150mm;font-weight: 600;font-family: monospace; display: none;" id="booking_tickets">
		<div style="width: 150mm;font-weight: 600;font-family: monospace;" class="booking_ticket">
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
					font-size: 32px;
					text-align: center;
					font-weight: 600;
					font-family: monospace;
					text-transform: uppercase;
				}

				tr td,
				tr th {
					font-size: 20px;
				}

				.booking_ticket {
					color: #000;
					background: #fff;
					padding: 5px;
					display: block;
				}

				#ticket_loader {
					display: flex;
					justify-content: center;
					align-items: center;
					width: 100%;
					height: 100%;
					position: fixed;
					top: 0;
					left: 0;
					background: white;
					z-index: 9999;
				}

				#print_status {
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
					z-index: 10000;
					text-align: center;
				}

				img {
					max-width: 100%;
				}
			</style>
			<p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
			<h3 style="max-width: 150mm;margin: 5px 0;">Office Copy</h3>
			<p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
			<br>

			<?php /*
	   <p><img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>" style="width:250px;" align="center"></p>
	   */ ?>
			<h2 style="text-align:center; margin:0;font-size: 26px;"><?php echo $temp_details['name']; ?></h2>
			<p><?php echo $temp_details['address1']; ?>, <?php echo $temp_details['address2']; ?></br>
				<?php echo $temp_details['city'] . '-' . $temp_details['postcode']; ?>.
				Tel: <?= $temp_details['telephone']; ?></p>
			<hr>
			<p style="text-align: center; font-size:28px;">Cash Donation Voucher</p>
			<hr>
			<p style="text-align: center;"><b>Date : </b>
				<?php $date = new DateTime($qry1['date']);
				echo $date->format('d-m-Y'); ?>
			</p>
			<p style="text-align: center;"><b>Invoice : </b>
				<?php echo $qry1['ref_no']; ?>
			</p>
			<p style="text-align: center;"><b>Name : </b>
				<?php echo $qry1['name']; ?>
			</p>
			<p style="text-align: center;"><b>Payfor : </b>
				<?php echo $qry1['pname']; ?>
			</p>
			<p style="text-align: center;"><b>Remarks : </b>
				<?php echo $qry1['description']; ?>
			</p>
			<p style="text-align: center;"><b>Amount(RM) : </b>
				<?php echo number_format($qry1['amount'], '2', '.', ','); ?>
			</p>
			<br>
			<hr>
		</div>

		<div style="width: 150mm;font-weight: 600;font-family: monospace;" class="booking_ticket">
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
					font-size: 32px;
					text-align: center;
					font-weight: 600;
					font-family: monospace;
					text-transform: uppercase;
				}

				tr td,
				tr th {
					font-size: 20px;
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
			<h3 style="max-width: 150mm;margin: 5px 0; font-size:28px;">Customer Copy</h3>
			<p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
			<br>

			<p><img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>"
					style="width:250px;" align="center"></p>
			<h2 style="text-align:center; margin:0;font-size: 26px;"><?php echo $temp_details['name']; ?></h2>
			<p><?php echo $temp_details['address1']; ?>, <?php echo $temp_details['address2']; ?></br>
				<?php echo $temp_details['city'] . '-' . $temp_details['postcode']; ?>.
				Tel: <?= $temp_details['telephone']; ?></p>
			<hr>
			<p style="text-align: center;">Cash Donation Voucher</p>
			<hr>
			<p style="text-align: center;"><b>Date : </b>
				<?php $date = new DateTime($qry1['date']);
				echo $date->format('d-m-Y'); ?>
			</p>
			<p style="text-align: center;"><b>Invoice : </b>
				<?php echo $qry1['ref_no']; ?>
			</p>
			<p style="text-align: center;"><b>Name : </b>
				<?php echo $qry1['name']; ?>
			</p>
			<p style="text-align: center;"><b>Payfor : </b>
				<?php echo $qry1['pname']; ?>
			</p>
			<p style="text-align: center;"><b>Amount(RM) : </b>
				<?php echo number_format($qry1['amount'], '2', '.', ','); ?>
			</p>
			<br>
			<hr>
		</div>
	</div>

	<div id="ticket_loader" class="ticket_loader">
		<img src="<?php echo base_url(); ?>/assets/images/loader.gif" />
	</div>

	<div id="print_status" class="print_status">
		<div>Printing Voucher <span id="current_voucher">1</span> of <span id="total_vouchers">2</span></div>
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

					// Initialize printer first
					IminPrintInstance.initPrinter();
					console.log('Printer initialized');

					// Wait for initialization to complete
					await new Promise(resolve => setTimeout(resolve, 1000));

					// Check printer status
					try {
						const status = await IminPrintInstance.getPrinterStatus();
						console.log('Printer status:', status);

						// If printer has error, wait and retry
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

		function initiate_load(IminPrintInstance) {
			var tickets = [];
			var $ticketElements = $('#booking_tickets .booking_ticket');
			var totalCount = $ticketElements.length;
			var processedCount = 0;

			console.log('Total vouchers to print: ' + totalCount);
			$('#total_vouchers').text(totalCount);

			// Process each voucher element with proper timing
			$ticketElements.each(function (index) {
				var node = this;

				// Stagger the image conversion to prevent memory issues
				setTimeout(function () {
					console.log('Converting voucher ' + (index + 1) + ' to image...');
					domtoimage.toJpeg(node, {
						quality: 0.95,
						bgcolor: '#ffffff',
						width: node.scrollWidth,
						height: node.scrollHeight
					}).then(function (dataUrl) {
						tickets[index] = dataUrl;
						processedCount++;

						console.log('Processed voucher image ' + (index + 1) + ' of ' + totalCount);

						// When all images are processed, start printing
						if (processedCount === totalCount) {
							console.log('All voucher images processed, starting print queue');
							// Add a small delay before starting print
							setTimeout(function () {
								print_queue(IminPrintInstance, tickets, 0);
							}, 500);
						}
					}).catch(function (error) {
						console.error('Error generating image for voucher ' + (index + 1), error);
						alert('Error generating voucher image: ' + error.message);
						processedCount++;

						// Continue even if one fails
						if (processedCount === totalCount) {
							setTimeout(function () {
								print_queue(IminPrintInstance, tickets, 0);
							}, 500);
						}
					});
				}, index * 200); // 200ms delay between each image conversion
			});
		}

		async function print_queue(IminPrintInstance, tickets, currentIndex) {
			if (currentIndex < tickets.length) {
				// Skip if no image data
				if (!tickets[currentIndex]) {
					console.log('Skipping voucher ' + (currentIndex + 1) + ' - no image data');
					print_queue(IminPrintInstance, tickets, currentIndex + 1);
					return;
				}

				// Update status
				$('#current_voucher').text(currentIndex + 1);

				console.log('Printing voucher ' + (currentIndex + 1) + ' of ' + tickets.length);

				try {
					// Check printer status only on first voucher
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

					console.log('Sending image to printer for voucher ' + (currentIndex + 1));

					// Print the bitmap (without auto-cut if imin-printer.js is fixed)
					await IminPrintInstance.printSingleBitmap(tickets[currentIndex]);

					console.log('Image sent successfully for voucher ' + (currentIndex + 1));

					// Wait for image to be fully printed
					await new Promise(resolve => setTimeout(resolve, 200));

					// Feed paper for proper spacing
					console.log('Feeding paper...');
					IminPrintInstance.printAndFeedPaper(100);

					// Wait for feed to complete
					await new Promise(resolve => setTimeout(resolve, 150));

					// Cut the paper
					console.log('Cutting paper...');
					IminPrintInstance.partialCut();

					// Check if this is the last voucher
					const isLastVoucher = currentIndex === tickets.length - 1;

					if (isLastVoucher) {
						console.log('Last voucher printed');

						// Hide status
						$('#print_status').hide();

						// Open cash box if needed
						IminPrintInstance.openCashBox();
						console.log('Cash box opened');

						// Close window after a short delay
						setTimeout(function () {
							console.log('Closing window');
							window.close();
						}, 500);
					} else {
						// Wait between vouchers to ensure proper cutting and prevent buffer overflow
						console.log('Waiting before next voucher...');
						await new Promise(resolve => setTimeout(resolve, 400));

						// Process next voucher
						print_queue(IminPrintInstance, tickets, currentIndex + 1);
					}

				} catch (error) {
					console.error('Error printing voucher ' + (currentIndex + 1), error);
					alert('Print error: ' + error.message);

					// Hide status on error
					$('#print_status').hide();

					// Wait a bit before continuing
					await new Promise(resolve => setTimeout(resolve, 500));

					// Continue with next voucher even if this one fails
					print_queue(IminPrintInstance, tickets, currentIndex + 1);
				}

			} else {
				// Fallback - should not normally reach here
				console.log('All vouchers printed successfully (fallback)');
				$('#print_status').hide();
				IminPrintInstance.openCashBox();
				setTimeout(function () {
					window.close();
				}, 500);
			}
		}
	</script>
</body>