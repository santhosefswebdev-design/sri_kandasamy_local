<!DOCTYPE html>
<html>

<head>
	<title>Prasadam Invoice - <?= isset($temple_details['name']) ? $temple_details['name'] : 'Sri Kandaswamy Temple' ?>
	</title>
	<meta charset="UTF-8">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Barlow&display=swap" rel="stylesheet">
	<style>
		body {
			font-family: 'Barlow', Arial, sans-serif;
			margin: 0;
			padding: 20px;
		}

		table {
			border-collapse: collapse;
		}

		table td {
			padding: 5px;
			vertical-align: top;
		}

		.border-table {
			border: 1px solid #CCC;
		}

		.border-table td {
			border: 1px solid #CCC;
			padding: 8px;
		}

		h2 {
			margin: 10px 0;
		}

		.text-center {
			text-align: center;
		}

		.text-right {
			text-align: right;
		}

		.text-left {
			text-align: left;
		}

		.invoice-header {
			margin-bottom: 20px;
		}

		.temple-info {
			font-size: 16px;
			line-height: 1.4;
		}

		.invoice-details {
			margin: 20px 0;
		}

		hr {
			border: 1px solid #CCC;
		}
	</style>
</head>

<body>
	<table align="center" width="100%">
		<tr>
			<td colspan="2">
				<table style="width:100%" class="invoice-header">
					<tr>
						<td width="40%" align="left">
							<img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>"
								style="width:120px;" align="left">
							<p style="min-height: 90px;">&nbsp;</p>
						</td>
						<td width="60%" align="left">
							<h2 style="text-align:left; margin-bottom: 0; margin-top: 0;">
								<?php echo $temp_details['name']; ?>
							</h2>
							<p style="text-align:left; font-size:16px; margin:5px;">
								<?php echo $temp_details['address1']; ?>, <br>
								<?php echo $temp_details['address2']; ?>,<br>
								<?php echo $temp_details['city'] . '-' . $temp_details['postcode']; ?><br>
								Tel :<?= $temp_details['telephone']; ?>
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
			<td colspan="2">
				<h2 class="text-center">Prasadam Voucher</h2>
			</td>
		</tr>

		<tr class="invoice-details">
			<td align="left">
				<b>Date:</b> <?= isset($data['date']) ? date('d-m-Y', strtotime($data['date'])) : date('d-m-Y') ?>
			</td>
			<td align="right">
				<p class="text-right" style="line-height:1.7em;">
					<b>Invoice:</b> <?= isset($data['ref_no']) ? $data['ref_no'] : '' ?>
				</p>
			</td>
		</tr>

		<!-- Customer Details -->
		<tr>
			<td colspan="2">
				<table style="width: 100%; margin: 10px 0;">
					<tr>
						<td width="50%">
							<b>Customer Name:</b> <?= isset($data['customer_name']) ? $data['customer_name'] : '' ?>
						</td>
						<td width="50%">
							<b>Mobile:</b> <?= isset($data['mobile_no']) ? $data['mobile_no'] : '' ?>
						</td>
					</tr>
					<?php if (isset($data['email_id']) && !empty($data['email_id'])): ?>
						<tr>
							<td colspan="2">
								<b>Email:</b> <?= $data['email_id'] ?>
							</td>
						</tr>
					<?php endif; ?>
					<?php if (isset($data['collection_date'])): ?>
						<tr>
							<td>
								<b>Collection Date:</b> <?= date('d-m-Y', strtotime($data['collection_date'])) ?>
							</td>
							<td>
								<b>Collection Time:</b> <?= isset($data['serve_time']) ? $data['serve_time'] : '' ?>
							</td>
						</tr>
					<?php endif; ?>
				</table>
			</td>
		</tr>

		<!-- Prasadam Items -->
		<tr>
			<td colspan="2">
				<table class="border-table" style="width:100%; margin: 20px 0;">
					<tr style="background-color: #f5f5f5;">
						<th style="border: 1px solid #CCC; padding: 8px; text-align: left;"><b>Prasadam Name</b></th>
						<th style="border: 1px solid #CCC; padding: 8px; text-align: center;"><b>Quantity</b></th>
						<th style="border: 1px solid #CCC; padding: 8px; text-align: right;"><b>Amount (RM)</b></th>
					</tr>

					<?php if (isset($booking_details) && !empty($booking_details)): ?>
						<?php foreach ($booking_details as $item): ?>
							<tr>
								<td style="border: 1px solid #CCC; padding: 8px;">
									<?= isset($item['name_eng']) ? $item['name_eng'] : '' ?>
									<?php if (isset($item['name_tamil']) && !empty($item['name_tamil'])): ?>
										<br><small><?= $item['name_tamil'] ?></small>
									<?php endif; ?>
								</td>
								<td style="border: 1px solid #CCC; padding: 8px; text-align: center;">
									<?= isset($item['quantity']) ? $item['quantity'] : 0 ?>
								</td>
								<td style="border: 1px solid #CCC; padding: 8px; text-align: right;">
									<?= isset($item['total_amount']) ? number_format($item['total_amount'], 2) : '0.00' ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>

					<!-- Total Row -->
					<tr style="background-color: #f9f9f9; font-weight: bold;">
						<td colspan="2" style="border: 1px solid #CCC; padding: 8px; text-align: right;"><b>Total
								Amount:</b></td>
						<td style="border: 1px solid #CCC; padding: 8px; text-align: right;">
							<b>RM
								<?= isset($data['total_amount']) ? number_format($data['total_amount'], 2) : '0.00' ?></b>
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<!-- Payment Details -->
		<?php if (isset($pay_details) && !empty($pay_details)): ?>
			<tr>
				<td colspan="2">
					<h4>Payment Details:</h4>
					<table class="border-table" style="width:100%;">
						<tr style="background-color: #f5f5f5;">
							<th style="border: 1px solid #CCC; padding: 8px; text-align: left;"><b>Payment Mode</b></th>
							<th style="border: 1px solid #CCC; padding: 8px; text-align: center;"><b>Date</b></th>
							<th style="border: 1px solid #CCC; padding: 8px; text-align: right;"><b>Amount (RM)</b></th>
						</tr>
						<?php foreach ($pay_details as $payment): ?>
							<tr>
								<td style="border: 1px solid #CCC; padding: 8px;">
									<?= isset($payment['payment_mode_title']) ? $payment['payment_mode_title'] : 'Cash' ?>
								</td>
								<td style="border: 1px solid #CCC; padding: 8px; text-align: center;">
									<?= isset($payment['paid_date']) ? date('d-m-Y', strtotime($payment['paid_date'])) : '' ?>
								</td>
								<td style="border: 1px solid #CCC; padding: 8px; text-align: right;">
									<?= isset($payment['amount']) ? number_format($payment['amount'], 2) : '0.00' ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
				</td>
			</tr>
		<?php endif; ?>

		<!-- Notes -->
		<?php if (isset($data['prasadam_notes']) && !empty($data['prasadam_notes'])): ?>
			<tr>
				<td colspan="2" style="padding-top: 20px;">
					<b>Notes:</b><br>
					<?= htmlspecialchars($data['prasadam_notes']) ?>
				</td>
			</tr>
		<?php endif; ?>

		<!-- Terms and Conditions -->
		<?php if (isset($terms) && !empty($terms['terms_conditions'])): ?>
			<tr>
				<td colspan="2" style="padding-top: 20px;">
					<h4>Terms & Conditions:</h4>
					<div style="font-size: 12px;">
						<?= $terms['terms_conditions'] ?>
					</div>
				</td>
			</tr>
		<?php endif; ?>

		<!-- Footer -->
		<tr>
			<td colspan="2" class="text-center" style="padding-top: 30px;">
				<p style="font-size: 12px; color: #666;">
					Thank you for your devotion and support!<br>
					Generated on: <?= date('d-m-Y H:i:s') ?>
				</p>
			</td>
		</tr>
	</table>
</body>

</html>