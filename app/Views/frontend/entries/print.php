<!DOCTYPE html>
<html>

<head>
	<title><?php echo $temple_details['name']; ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Barlow&display=swap" rel="stylesheet">
	<style>
		table {
			border-collapse: collapse;
		}

		.inner_table tr:nth-child(even) {
			background: #F3F3F3;
		}

		.inner_table tr:last-child {
			background: #e2dfdf;
		}

		.tab tr th,
		.tab tr td {
			text-align: center;
		}

		h2 {
			font-size: 20px;
		}

		h3,
		h4 {
			margin: 5px;
		}

		hr {
			margin: 2px;
		}
	</style>
</head>

<body>
	<?php
	$db = db_connect();
	$pay_type = "";
	$results2 = $db->table("entryitems")->where('DC', 'C')->where('entry_id', $results['id'])->get()->getResultArray();
	if ($results['entrytype_id'] == 1) {
		$type = 'Receipt';
		$pay_type = "Received From";
	} else if ($results['entrytype_id'] == 2) {
		$type = 'Payment Voucher';
		$pay_type = "Paid To";
		$results2 = $db->table("entryitems")->where('DC', 'D')->where('entry_id', $results['id'])->get()->getResultArray();
	} else if ($results['entrytype_id'] == 3) {
		$type = 'Contra';
	} else if ($results['entrytype_id'] == 4) {
		$type = 'Journal';
	}
	if (!function_exists('AmountInWords')) {
		function AmountInWords(float $amount)
		{
			$amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
			$get_paise = ($amount_after_decimal > 0) ? " and Cents " . trim(NumToWords($amount_after_decimal)) : '';
			return (NumToWords($amount) ? 'Ringgit ' . trim(NumToWords($amount)) . '' : '') . $get_paise . ' Only';

		}
	}
	if (!function_exists('NumToWords')) {
		function NumToWords($num)
		{
			$num = floor($num);
			$amt_hundred = null;
			$count_length = strlen($num);
			$x = 0;
			$string = array();
			$change_words = array(
				0 => '',
				1 => 'One',
				2 => 'Two',
				3 => 'Three',
				4 => 'Four',
				5 => 'Five',
				6 => 'Six',
				7 => 'Seven',
				8 => 'Eight',
				9 => 'Nine',
				10 => 'Ten',
				11 => 'Eleven',
				12 => 'Twelve',
				13 => 'Thirteen',
				14 => 'Fourteen',
				15 => 'Fifteen',
				16 => 'Sixteen',
				17 => 'Seventeen',
				18 => 'Eighteen',
				19 => 'Nineteen',
				20 => 'Twenty',
				30 => 'Thirty',
				40 => 'Forty',
				50 => 'Fifty',
				60 => 'Sixty',
				70 => 'Seventy',
				80 => 'Eighty',
				90 => 'Ninety'
			);
			$here_digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
			while ($x < $count_length) {
				$get_divider = ($x == 2) ? 10 : 100;
				$amount = floor($num % $get_divider);
				$num = floor($num / $get_divider);
				$x += $get_divider == 10 ? 1 : 2;
				if ($amount) {
					$add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
					$amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
					$string[] = ($amount < 21) ? $change_words[$amount] . ' ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred : $change_words[floor($amount / 10) * 10] . ' ' . $change_words[$amount % 10] . ' ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred;
				} else
					$string[] = null;
			}
			//$implode_to_Rupees = implode('', array_reverse($string));
			return (implode('', array_reverse($string)));
		}
	}
	for ($i = 1; $i <= 1; $i++) {
		?>
		<table align="center" style="width: 100%;max-width: 800px;">
			<tr>
				<td colspan="2">
					<table style="width:100%">
                    <tr><td width="15%" align="left"><img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temple_details['image']; ?>" style="width:120px;"
									align="left"></td>
							<td width="85%" align="left">
								<h3 style="text-align:center;margin-bottom: 0;"><?php echo $temple_details['name_tamil']; ?></h3>
								<p style="text-align:center; font-size:13px; margin:0;"><?php echo $temple_details['city_tamil']; ?> <?php echo $temple_details['since_tamil']; ?>
								<h2 style="text-align:center;margin-bottom: 0; margin-top:2px;"><?php echo $temple_details['name']; ?></h2>
								<p style="text-align:center; font-size:16px; margin:0px;"><?php echo $temple_details['city']; ?> <span
										style="position:absolute; right:8.5%;"><?php echo $temple_details['since_eng']; ?></span><br>
									<?php echo $temple_details['address1']; ?>, <?php echo $temple_details['address2']; ?>,
									<?php echo $temple_details['postcode']; ?> <?php echo $temple_details['city']; ?> <br>
									Email : <?php echo $temple_details['email']; ?>, Tel : <?php echo $temple_details['telephone']; ?>, Whatsapp :
									<?php echo $temple_details['mobile']; ?><br>
									Website : <?php echo $temple_details['website']; ?>, ISO <?php echo $temple_details['iso']; ?>
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
					<h3 style="text-align:center;"><?= $type ?> </h3>

					<table style="width:100%">
						<tr>
							<td align="left">
								<h4>Official Receipt Number : <?= $results['entry_code'] ?> </h4>
							</td>
							<td align="right">
								<h4>Date : <?= date('d-m-Y', strtotime($results['date'])) ?> </h4>
							</td>
						</tr>
					</table>

					<table class="tab" border="1" width="100%">
						<tbody>
							<?php
							$total_amount = 0;
							foreach ($results2 as $key) {
								$total_amount += $key['amount'];
								$ledgername = get_ledger_name($key['ledger_id']);
								/* ?>
								<tr>
									<td style="text-align:left;padding-left: 5px;"><?= $ledgername ?></td>
									<td style="text-align:right;padding-right: 5px;"><?= number_format($key['amount'], 2) ?>
									</td>
								</tr>
							<?php */ } ?>
							<?php /* <tr>
								<td style="text-align:right;padding-right: 5px;"><b>Total : </b></td>
								<td style="text-align:right;padding-right: 5px;">
									<b><?= number_format($results['dr_total'], 2) ?></b>
								</td>
							</tr> */ ?>

							<tr>
								<td colspan="2" style="text-align:left; padding:5px 0;line-height: 1.3em;">
									<label style="padding-left: 20px;"
										class="control-label">Paid Amount</label> : <b>
										<?= number_format($total_amount, 2) ?></b>
									<br>
									<label style="padding-left: 20px;"
										class="control-label"><?php echo $pay_type; ?></label> : <b>
										<?= $results['paid_to'] ?></b>
									<br>
									<label style="padding-left: 20px;" class="control-label">Amount In Words</label> : <b>
										<?= AmountInWords($results['cr_total']) ?></b>
									<br>
									<label style="padding-left: 20px;" class="control-label">Narration</label> : <b>
										<?= $results['narration'] ?></b>
									<br>
									<label style="padding-left: 20px;" class="control-label">Payment Method </label> : <b>
										<?= $payment_method ?></b>
									<?php /* <label style="padding-left: 20px;" class="control-label">Payment Method </label> : <b>
										<?= $results['payment'] ?></b>
									<br>
									<?php
									if ($results['payment'] == 'cheque' || $results['payment'] == 'online') {
										echo '<label style="padding-left: 20px;" class="control-label">' . ($results['payment'] == 'cheque' ? 'Cheque No' : 'Transaction No') . ' </label> : <b>' . $results['cheque_no'] . '</b><br>';
										echo '<label style="padding-left: 20px;" class="control-label">' . ($results['payment'] == 'cheque' ? 'Cheque Date' : 'Transaction Date') . ' </label> : <b>' . date('d-m-Y', strtotime($results['cheque_date'])) . '</b><br>';
									}
									?>
								 <label style="padding-left: 20px;" class="control-label">SANCTION BY COMMITTED ON</label> : _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ */ ?>
				
								</td>
							</tr>

						</tbody>
					</table>


					<br>
					<?php
					if ($results['entrytype_id'] == 1) {
						?>
						<?php /* <table style="width:100%">
							<tr style="text-align:center">
								<td>
									<h4>ISSUED BY</h4>
									<br>
									<p>.....................</p>
									<h4>FINANCE</h4>
								</td>
								<td colspan="2">&nbsp;</td>
								<td>
									<h4>AUTHORIZED BY</h4>
									<br>
									<p>.....................</p>
									<h4>TREASURER</h4>
								</td>

							</tr>
						</table> */ ?>
						<br>
						<div style="text-align:center;margin-right: 45px;">
							<h3>No signature required as this is a computer generated receipt</h3>
						</div>
						<?php
					} elseif ($results['entrytype_id'] == 2) {
						?>
						<table style="width:100%">
							<tr style="text-align:left">
							<br><br>
								<td>
									<h4 style="margin:0; display:inline;">Received by:</h4> 
									<p style="display:inline; margin:0 0 0 5px;">..................................</p>
								</td>
							</tr>
						</table><br><br>
						<table style="width:100%">
						<br>
							<tr style="text-align:center">
								<td>
									
									<br>
									<div>.....................</div>
									<h4>President</h4>
								</td>
								<td>
									
									<br>
									<div>.....................</div>
									<h4>Secretary</h4>
								</td>
								<td>
									
									<br>
									<div>.....................</div>
									<h4>Treasurer</h4>
								</td>
							</tr>
						</table>
						<?php
					}
					?>
					<?php // <hr> ?>
					<?php
	}
	?>
				<script>
					window.print();
					// setTimeout(function(){window.close();},4500);
				</script>
</body>

</html>