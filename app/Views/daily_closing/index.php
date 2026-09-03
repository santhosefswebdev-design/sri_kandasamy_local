<?php global $lang; ?>
<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); ?>
<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2>
				<?php echo $lang->daily_closing; ?> <small>
					<?php echo $lang->print; ?> / <b>
						<?php echo $lang->daily_closing; ?>
					</b>
				</small>
			</h2>
		</div>
		<!-- Basic Examples -->
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<form action="<?php echo base_url(); ?>/dailyclosing" method="post">
							<div class="row">
								<div class="col-md-2">
									<input type="date" name="dailyclosing_start_date" id="dailyclosing_start_date"
										class="form-control" value="<?php echo $dailyclosing_start_date; ?>"
										max="<?php echo $booking_calendar_range_year; ?>">
								</div>
								<div class="col-md-2">
									<input type="date" name="dailyclosing_end_date" id="dailyclosing_end_date"
										class="form-control" value="<?php echo $dailyclosing_end_date; ?>"
										max="<?php echo $booking_calendar_range_year; ?>">
								</div>
								<div class="col-md-2">
									<div class="form-group form-float">
										<div class="form-line">
											<select class="form-control" name="booking_type" id="booking_type">
												<option value="">All Booking Types</option>
												<option value="DIRECT" <?= (isset($booking_type) && $booking_type == 'DIRECT') ? 'selected' : ''; ?>>DIRECT</option>
												<option value="COUNTER" <?= (isset($booking_type) && $booking_type == 'COUNTER') ? 'selected' : ''; ?>>COUNTER</option>
												<!-- <option value="KIOSK" <?= (isset($booking_type) && $booking_type == 'KIOSK') ? 'selected' : ''; ?>>KIOSK</option> -->
											</select>
											<label class="form-label"></label>
										</div>
									</div>
								</div>

								<div class="col-md-1">
									<button type="submit" id="dailyclosing_filter"
										class="btn btn-success">Filter</button>
								</div>

								<!-- Export Excel (Multi-Sheet with Debug) -->
								<div class="col-md-1" align="right">
									<a href="<?php echo base_url(); ?>/dailyclosing/exportExcel/<?php echo strtotime($dailyclosing_start_date); ?>/<?php echo strtotime($dailyclosing_end_date); ?><?php echo !empty($booking_type) ? '/' . $booking_type : ''; ?>"
										target="_blank">
										<button type="button" class="btn bg-teal waves-effect">
											<i class="material-icons"
												style="font-size:16px;vertical-align:middle;">table_chart</i>
											Export Excel
										</button>
									</a>
								</div>

								<!-- Export Excel Consolidated (Single Sheet) -->
								<div class="col-md-2" align="right">
									<a href="<?php echo base_url(); ?>/dailyclosing/exportExcelConsolidated/<?php echo strtotime($dailyclosing_start_date); ?>/<?php echo strtotime($dailyclosing_end_date); ?><?php echo !empty($booking_type) ? '/' . $booking_type : ''; ?>"
										target="_blank">
										<button type="button" class="btn bg-orange waves-effect">
											<i class="material-icons"
												style="font-size:16px;vertical-align:middle;">file_download</i>
											Consolidated Excel
										</button>
									</a>
								</div>

								<!-- Pay Details -->
								<div class="col-md-1" align="right">
									<a href="<?php echo base_url(); ?>/paydetails" target="_blank">
										<button type="button" class="btn bg-deep-purple waves-effect">Pay
											Details</button>
									</a>
								</div>

								<!-- Print -->
								<div class="col-md-1" align="right">
									<a href="<?php echo base_url(); ?>/dailyclosing/print/<?php echo strtotime($dailyclosing_start_date); ?>/<?php echo strtotime($dailyclosing_end_date); ?><?php echo !empty($booking_type) ? '/' . $booking_type : ''; ?>"
										target="_blank">
										<button type="button" class="btn bg-deep-purple waves-effect">Print</button>
									</a>
								</div>

							</div>
						</form>
					</div>
					<div class="body">
						<div class="col-md-12">
							<h3>
								<?php echo $lang->archanai; ?>
								<?php echo $lang->selling; ?>
								<?php echo $lang->details; ?>
							</h3>
						</div>
						<div class="table-responsive col-md-12 det"
							style="background:#FFF; float:none;margin-bottom:0px;">
							<table class="table table-bordered table-striped table-hover">
								<thead style="background: #3F51B5;color: #fff;">
									<tr>
										<th style="padding: 5px 10px!important;">S.No</th>
										<th style="padding: 5px 10px!important;">
											<?php echo $lang->archanai; ?>
										</th>
										<th style="padding: 5px 10px!important;">
											<?php echo $lang->pay_mode; ?>
										</th>
										<th style="padding: 5px 10px!important;">
											<?php echo $lang->paid_through; ?>
										</th>
										<th style="padding: 5px 10px!important;">
											<?php echo $lang->quantity; ?>
										</th>
										<th style="padding: 5px 10px!important;text-align:right;">
											<?php echo $lang->amount; ?>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$archanai_total = 0;
									$archanai_qty = 0;
									if (count($archanai_group_details) > 0) {
										foreach ($archanai_group_details as $archanai_detail_data) {
											if (count($archanai_detail_data['deities']) > 0) {
												$ar_i = 1;
												$sannathi_total = 0;
												$sannathi_qty = 0;
												echo '<tr><td colspan="5" style="text-align: center;"><h4>---  ' . strtoupper($archanai_detail_data['title']) . '  ---</h4></td></tr>';
												foreach ($archanai_detail_data['deities'] as $archanai_deity) {
													echo '<tr><td colspan="5" style="text-align: center;"><h5>' . strtoupper($archanai_deity['deity_name']) . '</h5></td></tr>';
													foreach ($archanai_deity['products'] as $archanai_detail) {
														$archanai_total = $archanai_total + $archanai_detail['amount'];
														$archanai_discount += $archanai_detail['discount_amount'];
														$sannathi_total = $sannathi_total + $archanai_detail['amount'];

														$sannathi_qty = $sannathi_qty + $archanai_detail['qty'];
														$archanai_qty = $archanai_qty + $archanai_detail['qty'];
														if (empty($summary_total['sales'][$archanai_detail['paymentmode']]))
															$summary_total['sales'][$archanai_detail['paymentmode']] = 0;
														$summary_total['sales'][$archanai_detail['paymentmode']] += $archanai_detail['amount'];
														?>

														<tr>
															<td style="padding: 5px 10px!important;">
																<?php echo $ar_i; ?>
															</td>
															<td style="padding: 5px 10px!important;">
																<?php
																echo $archanai_detail['name_in_english'] . " / " . $archanai_detail['name_in_tamil'];
																?>
															</td>
															<td style="padding: 5px 10px!important;text-transform: uppercase;">
																<?php echo $archanai_detail['paymentmode']; ?>
															</td>
															<td style="padding: 5px 10px!important;text-transform: uppercase;">
																<?php echo $archanai_detail['paid_through']; ?>
															</td>

															<td style="padding: 5px 10px!important;">
																<?php
																echo $archanai_detail['qty'];
																?>
															</td>
															<td style="padding: 5px 10px!important;text-align:right;">
																<?php
																echo number_format($archanai_detail['amount'], 2);
																?>
															</td>
														</tr>
														<?php
														$ar_i++;
													}
												}
												echo '<tr><td colspan="4" style="text-align: center;"><h6>' . strtoupper($archanai_detail_data['title']) . ' Total</h4></td><td style="padding: 5px 10px!important;text-align:right;font-weight:bold;">' . $sannathi_qty . '</td><td style="padding: 5px 10px!important;text-align:right;font-weight:bold;">' . number_format($sannathi_total, 2) . '</td></tr>';
											}
										}
									}
									?>
								</tbody>
								<tfoot>
									<tr>
										<td style="text-align: right" colspan="5"><strong>Archanai Sales Sub
												Total</strong></td>
										<td style="padding: 5px 10px!important;text-align:right;font-weight:bold;">
											<?php echo number_format($archanai_total, 2); ?>
										</td>
									</tr>
									<tr>
										<td style="text-align: right" colspan="5"><strong>Archanai Discount
												Total</strong></td>
										<td style="padding: 5px 10px!important;text-align:right;font-weight:bold;">-
											<?php echo number_format($archanai_discount, 2); ?>
										</td>
									</tr>
									<?php $archanai_final = 0;
									$archanai_final = $archanai_total - $archanai_discount; ?>
									<tr>
										<td style="text-align: right" colspan="5"><strong>Archanai Grand Total</strong>
										</td>
										<td style="padding: 5px 10px!important;text-align:right;font-weight:bold;">
											<?php echo number_format($archanai_final, 2); ?>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>
						<?php
						$hallbooking_total = 0;
						$sale_summary['hallbooking'] = array();
						$sale_summary['hallbooking']['total_amount'] = 0;
						$sale_summary['hallbooking']['paid_amount'] = 0;
						if (count($hallbooking_details) > 0) {
							?>
							<div class="col-md-12">
								<h3>
									<?php echo $lang->hall; ?>
									<?php echo $lang->booking; ?>
									<?php echo $lang->details; ?>
								</h3>
							</div>
							<div class="table-responsive col-md-12 det"
								style="background:#FFF; float:none;margin-bottom:0px;">
								<table class="table table-bordered table-striped table-hover">
									<thead style="background: #3F51B5;color: #fff;">
										<tr>
											<th style="padding: 5px 10px!important;">S.No</th>
											<th style="padding: 5px 10px!important;">Name</th>
											<th style="padding: 5px 10px!important;">Event date</th>
											<th style="padding: 5px 10px!important;">Payment Mode</th>
											<th style="padding: 5px 10px!important;">Booked Through</th>
											<th style="padding: 5px 10px!important;">Payment Type</th>
											<th style="padding: 5px 10px!important;text-align:right;">Total Amount
											<th style="padding: 5px 10px!important;text-align:right;">Paid Amount </th>

										</tr>
									</thead>
									<tbody>
										<?php
										$hb_i = 1;

										foreach ($hallbooking_details as $hallbooking_detail) {
											$hallbooking_total += $hallbooking_detail['paidamount'];

											foreach ($hallbooking_detail['products'] as $product) {
												$productName = $product['package_name'];

												if (!isset($sale_summary['hallbooking'][$productName])) {
													$sale_summary['hallbooking'][$productName] = [
														'name_eng' => $productName,
														'ledger_code' => $product['ledger_code'],
														'qty' => 0,
														'total' => 0
													];
												}
												$sale_summary['hallbooking'][$productName]['qty'] += $product['quantity'];
												$sale_summary['hallbooking'][$productName]['total'] += $product['amount'];
											}
											$sale_summary['hallbooking']['total_amount'] += $hallbooking_detail['amount'];
											$sale_summary['hallbooking']['paid_amount'] += $hallbooking_detail['paidamount'];


											if (empty($summary_total['sales'][$hallbooking_detail['paymentmode']]))
												$summary_total['sales'][$hallbooking_detail['paymentmode']] = 0;
											$summary_total['sales'][$hallbooking_detail['paymentmode']] += $hallbooking_detail['paidamount'];
											?>
											<tr>
												<td style="padding: 5px 10px!important;">
													<?php echo $hb_i; ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php echo $hallbooking_detail['customer_name']; ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php echo $hallbooking_detail['date']; ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php
													if ($hallbooking_detail['paymentmode'] == "ipay_merch_qr") {
														$paymentname = "QR PAYMENT";
													} elseif ($hallbooking_detail['paymentmode'] == "ipay_merch_online") {
														$paymentname = "ONLINE PAYMENT";
													} elseif ($hallbooking_detail['paymentmode'] == "cash") {
														$paymentname = "CASH";
													} elseif ($hallbooking_detail['paymentmode'] == "nets_pay") {
														$paymentname = 'NETS';
													} else
														$paymentname = strtoupper(str_replace('_', ' ', $hallbooking_detail['paymentmode']));

													echo $paymentname;
													?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php if ($hallbooking_detail['booking_through'] == 'DIRECT')
														$booking_method = 'ADMIN';
													else
														$booking_method = 'COUNTER';
													echo $booking_method;
													?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php
													echo $hallbooking_detail['payment_type'];
													?>
												</td>
												<td style="padding: 5px 10px!important;text-align:right;">
													<?php
													echo number_format($hallbooking_detail['amount'], 2);
													?>
												</td>
												<td style="padding: 5px 10px!important;text-align:right;">
													<?php
													echo number_format($hallbooking_detail['paidamount'], 2);
													?>
												</td>
											</tr>
											<?php
											$hb_i++;
										}
										?>
									</tbody>
									<tfoot style="background: #a1a09f;color: #fff;">
										<tr>
											<td colspan="7">&nbsp;</td>
											<td style="padding: 5px 10px!important;text-align:right;font-weight:bold;">
												<?php echo number_format($hallbooking_total, 2); ?>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<?php
						}
						?>
						<?php
						$ubayam_total = 0;
						$sale_summary['ubayam'] = array();
						$sale_summary['ubayam']['total_amount'] = 0;
						$sale_summary['ubayam']['paid_amount'] = 0;
						if (count($ubayam_details) > 0) {
							?>
							<div class="col-md-12">
								<h3>
									<?php echo $lang->ubayam; ?>
									<?php echo $lang->details; ?>
								</h3>
							</div>
							<div class="table-responsive col-md-12 det"
								style="background:#FFF; float:none;margin-bottom:0px;">
								<table class="table table-bordered table-striped table-hover">
									<thead style="background: #3F51B5;color: #fff;">
										<tr>
											<th style="padding: 5px 10px!important;">S.No</th>
											<th style="padding: 5px 10px!important;">
												<?php echo $lang->name; ?>
											</th>
											<th style="padding: 5px 10px!important;">Event date</th>
											<th style="padding: 5px 10px!important;">
												<?php echo $lang->pay_mode; ?>
											</th>
											<th style="padding: 5px 10px!important;">
												<?php echo $lang->type; ?>
											</th>
											<th style="padding: 5px 10px!important;">
												<?php echo $lang->paid_through; ?>
											</th>
											<th style="padding: 5px 10px!important;text-align:right;">Total Amount </th>
											<th style="padding: 5px 10px!important;text-align:right;">Paid Amount </th>
										</tr>
									</thead>
									<tbody>
										<?php
										$u_i = 1;
										$ubayam_total = 0;
										if (count($ubayam_details) > 0) {
											foreach ($ubayam_details as $ubayam_detail) {
												$ubayam_total += $ubayam_detail['paidamount'];

												foreach ($ubayam_detail['products'] as $product) {
													$productName = $product['package_name'];

													if (!isset($sale_summary['ubayam'][$productName])) {
														$sale_summary['ubayam'][$productName] = [
															'name_eng' => $productName,
															'ledger_code' => $product['ledger_code'],
															'qty' => 0,
															'total' => 0
														];
													}
													$sale_summary['ubayam'][$productName]['qty'] += $product['quantity'];
													$sale_summary['ubayam'][$productName]['total'] += $product['amount'];
												}
												$sale_summary['ubayam']['total_amount'] += $ubayam_detail['amount'];
												$sale_summary['ubayam']['paid_amount'] += $ubayam_detail['paidamount'];


												if (empty($summary_total['sales'][$ubayam_detail['paymentmode']]))
													$summary_total['sales'][$ubayam_detail['paymentmode']] = 0;
												$summary_total['sales'][$ubayam_detail['paymentmode']] += $ubayam_detail['paidamount'];

												?>
												<tr>
													<td style="padding: 5px 10px!important;">
														<?php echo $u_i; ?>
													</td>
													<td style="padding: 5px 10px!important;">
														<?php
														echo $ubayam_detail['customer_name'];
														?>
													</td>
													<td style="padding: 5px 10px!important;">
														<?php echo $ubayam_detail['date']; ?>
													</td>
													<td>
														<?php
														if ($ubayam_detail['paymentmode'] == "ipay_merch_qr") {
															$paymentname = "QR PAYMENT";
														} elseif ($ubayam_detail['paymentmode'] == "ipay_merch_online") {
															$paymentname = "ONLINE PAYMENT";
														} elseif ($ubayam_detail['paymentmode'] == "cash") {
															$paymentname = "CASH";
														} else
															$paymentname = strtoupper($ubayam_detail['paymentmode']);

														echo $paymentname;
														?>
													</td>
													<td style="padding: 5px 10px!important;">
														<?php
														echo $ubayam_detail['payment_type'];
														?>
													</td>
													<td style="padding: 5px 10px!important;">
														<?php
														echo $ubayam_detail['booking_through'];
														?>
													</td>
													<td style="padding: 5px 10px!important;text-align:right;">
														<?php
														echo number_format($ubayam_detail['amount'], 2);
														?>
													</td>
													<td style="padding: 5px 10px!important;text-align:right;">
														<?php
														echo number_format($ubayam_detail['paidamount'], 2);
														?>
													</td>
												</tr>
												<?php
												$u_i++;
											}
										}
										?>
									</tbody>
									<tfoot style="background: #a1a09f;color: #fff;">
										<tr>
											<td colspan="7">&nbsp;</td>
											<td style="padding: 5px 10px!important;text-align:right;font-weight:bold;">
												<?php echo number_format($ubayam_total, 2); ?>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<?php
						}
						?>
						<?php
						$donation_total = 0;
						$sale_summary['donation'] = array();
						$sale_summary['donation']['total_amount'] = 0;
						if (count($donation_details) > 0) {
							?>
							<div class="col-md-12">
								<h3>
									<?php echo $lang->cash; ?>
									<?php echo $lang->donation; ?>
									<?php echo $lang->details; ?>
								</h3>
							</div>
							<div class="table-responsive col-md-12 det"
								style="background:#FFF; float:none;margin-bottom:0px;">
								<table class="table table-bordered table-striped table-hover">
									<thead style="background: #3F51B5;color: #fff;">
										<tr>
											<th style="padding: 5px 10px!important;">S.No</th>
											<th style="padding: 5px 10px!important;">
												<?php echo $lang->name; ?>
											</th>
											<th style="padding: 5px 10px!important;">
												<?php echo $lang->pay_mode; ?>
											</th>
											<th style="padding: 5px 10px!important;">
												<?php echo $lang->paid_through; ?>
											</th>
											<th style="padding: 5px 10px!important;text-align:right;">
												<?php echo $lang->amount; ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$d_i = 1;
										$donation_total = 0;
										if (count($donation_details) > 0) {
											foreach ($donation_details as $donation_detail) {
												$donation_total = $donation_total + $donation_detail['paidamount'];
												if (empty($summary_total['sales'][$donation_detail['paymentmode']]))
													$summary_total['sales'][$donation_detail['paymentmode']] = 0;
												$summary_total['sales'][$donation_detail['paymentmode']] += $donation_detail['paidamount'];
												if (empty($sale_summary['donation'][$donation_detail['package_name']]['name_eng']))
													$sale_summary['donation'][$donation_detail['package_name']]['name_eng'] = $donation_detail['package_name'];
												if (empty($sale_summary['donation'][$donation_detail['package_name']]['name_tamil']))
													$sale_summary['donation'][$donation_detail['package_name']]['name_in_tamil'] = '';
												$sale_summary['donation'][$donation_detail['package_name']]['qty'] += 1;
												if (empty($sale_summary['donation'][$donation_detail['package_name']]['total']))
													$sale_summary['donation'][$donation_detail['package_name']]['total'] = 0;
												$sale_summary['donation'][$donation_detail['package_name']]['total'] += $donation_detail['paidamount'];
												if (empty($sale_summary['donation'][$donation_detail['package_name']]['ledger_code']))
													$sale_summary['donation'][$donation_detail['package_name']]['ledger_code'] = $donation_detail['ledger_code'];

												$sale_summary['donation']['total_amount'] += $donation_detail['paidamount'];

												?>
												<tr>
													<td style="padding: 5px 10px!important;">
														<?php echo $d_i; ?>
													</td>
													<td style="padding: 5px 10px!important;">
														<?php
														echo $donation_detail['person_name'];
														?>
													</td>
													<td style="padding: 5px 10px!important;">
														<?php
														if ($donation_detail['paymentmode'] == "ipay_merch_qr") {
															$paymentname = "QR PAYMENT";
														} elseif ($donation_detail['paymentmode'] == "ipay_merch_online") {
															$paymentname = "ONLINE PAYMENT";
														} elseif ($donation_detail['paymentmode'] == "cash") {
															$paymentname = "CASH";
														} else
															$paymentname = strtoupper($donation_detail['paymentmode']);

														echo $paymentname;
														?>
													</td>
													<td style="padding: 5px 10px!important;">
														<?php
														echo $donation_detail['paid_through'];
														?>
													</td>
													<td style="padding: 5px 10px!important;text-align:right;">
														<?php
														echo number_format($donation_detail['paidamount'], 2);
														?>
													</td>
												</tr>
												<?php
												$d_i++;
											}
										}
										?>
									</tbody>
									<tfoot style="background: #a1a09f;color: #fff;">
										<tr>
											<td colspan="4">&nbsp;</td>
											<td style="padding: 5px 10px!important;text-align:right;font-weight:bold;">
												<?php echo number_format($donation_total, 2); ?>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<?php
						}
						?>
						<?php
						$prasadam_total = 0;
						$sale_summary['prasadam'] = array();
						$sale_summary['prasadam']['total_amount'] = 0;
						$sale_summary['prasadam']['paid_amount'] = 0;
						if (count($prasadam_details) > 0) {
							?>
							<div class="col-md-12">
								<h3>
									<?php echo $lang->prasadam; ?>
									<?php echo $lang->details; ?>
								</h3>
							</div>
							<div class="table-responsive col-md-12 det"
								style="background:#FFF; float:none;margin-bottom:0px;">
								<table class="table table-bordered table-striped table-hover">
									<thead style="background: #3F51B5;color: #fff;">
										<tr>
											<th style="padding: 5px 10px!important;">S.No</th>
											<th style="padding: 5px 10px!important;">
												<?php echo $lang->name; ?>
											</th>
											<th style="padding: 5px 10px!important;">
												<?php echo $lang->pay_mode; ?>
											</th>
											<th style="padding: 5px 10px!important;">Payment Type</th>
											<th style="padding: 5px 10px!important;">
												<?php echo $lang->paid_through; ?>
											</th>
											<th style="padding: 5px 10px!important;text-align:right;">Total Amount</th>
											<th style="padding: 5px 10px!important;text-align:right;">Paid Amount </th>
										</tr>
									</thead>
									<tbody>
										<?php
										$ps_i = 1;
										$prasadam_total = 0;
										if (count($prasadam_details) > 0) {
											foreach ($prasadam_details as $prasadam_detail) {
												$prasadam_total += $prasadam_detail['paidamount'];

												foreach ($prasadam_detail['products'] as $product) {
													$productName = $product['package_name'];

													if (!isset($sale_summary['prasadam'][$productName])) {
														$sale_summary['prasadam'][$productName] = [
															'name_eng' => $productName,
															'ledger_code' => $product['ledger_code'],
															'qty' => 0,
															'total' => 0
														];
													}

													$sale_summary['prasadam'][$productName]['qty'] += $product['quantity'];
													$sale_summary['prasadam'][$productName]['total'] += $product['amount'];
												}
												$sale_summary['prasadam']['total_amount'] += $prasadam_detail['amount'];
												$sale_summary['prasadam']['paid_amount'] += $prasadam_detail['paidamount'];

												if (empty($summary_total['sales'][$prasadam_detail['paymentmode']]))
													$summary_total['sales'][$prasadam_detail['paymentmode']] = 0;
												$summary_total['sales'][$prasadam_detail['paymentmode']] += $prasadam_detail['paidamount'];

												?>
												<tr>
													<td style="padding: 5px 10px!important;">
														<?php echo $ps_i; ?>
													</td>
													<td style="padding: 5px 10px!important;">
														<?php
														echo $prasadam_detail['customer_name'];
														?>
													</td>
													<td style="padding: 5px 10px!important;">
														<?php
														if ($prasadam_detail['paymentmode'] == "ipay_merch_qr") {
															$paymentname = "QR PAYMENT";
														} elseif ($prasadam_detail['paymentmode'] == "ipay_merch_online") {
															$paymentname = "ONLINE PAYMENT";
														} elseif ($prasadam_detail['paymentmode'] == "cash") {
															$paymentname = "CASH";
														} else
															$paymentname = strtoupper($prasadam_detail['paymentmode']);

														echo $paymentname;
														?>
													</td>
													<td style="padding: 5px 10px!important;">
														<?php
														echo $prasadam_detail['payment_type'];
														?>
													</td>
													<td style="padding: 5px 10px!important;">
														<?php
														echo $prasadam_detail['paid_through'];
														?>
													</td>
													<td style="padding: 5px 10px!important;text-align:right;">
														<?php
														echo number_format($prasadam_detail['amount'], 2);
														?>
													</td>
													<td style="padding: 5px 10px!important;text-align:right;">
														<?php
														echo number_format($prasadam_detail['paidamount'], 2);
														?>
													</td>
												</tr>
												<?php
												$ps_i++;
											}
										}
										?>
									</tbody>
									<tfoot style="background: #a1a09f;color: #fff;">
										<tr>
											<td colspan="6">&nbsp;</td>
											<td style="padding: 5px 10px!important;text-align:right;font-weight:bold;">
												<?php echo number_format($prasadam_total, 2); ?>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<?php
						}
						?>

						<?php
						$annathanam_total = 0;
						$sale_summary['annathanam'] = array();
						$sale_summary['annathanam']['total_amount'] = 0;
						$sale_summary['annathanam']['paid_amount'] = 0;
						if (count($annathanam_details) > 0) {
							?>
							<div class="col-md-12">
								<h3>Annathanam Details</h3>
							</div>
							<div class="table-responsive col-md-12 det"
								style="background:#FFF; float:none;margin-bottom:0px;">
								<table class="table table-bordered table-striped table-hover">
									<thead style="background: #3F51B5;color: #fff;">
										<tr>
											<th style="padding: 5px 10px!important;">S.No</th>
											<th style="padding: 5px 10px!important;">Name</th>
											<th style="padding: 5px 10px!important;">Time</th>
											<th style="padding: 5px 10px!important;">Payment Mode</th>
											<th style="padding: 5px 10px!important;">Payment Type</th>
											<th style="padding: 5px 10px!important;">Paid Through</th>
											<th style="padding: 5px 10px!important;text-align:right;">Total Amount (S$)
											<th style="padding: 5px 10px!important;text-align:right;">Paid Amount (S$) </th>
										</tr>
									</thead>
									<tbody>
										<?php
										$ans_i = 1;

										foreach ($annathanam_details as $annathanam_detail) {
											$annathanam_total += $annathanam_detail['paidamount'];

											foreach ($annathanam_detail['products'] as $product) {
												$productName = $product['package_name'];

												if (!isset($sale_summary['annathanam'][$productName])) {
													$sale_summary['annathanam'][$productName] = [
														'name_eng' => $productName,
														'ledger_code' => $product['ledger_code'],
														'qty' => 0,
														'total' => 0
													];
												}
												$sale_summary['annathanam'][$productName]['qty'] += 1;
												$sale_summary['annathanam'][$productName]['total'] += $product['amount'];
											}
											$sale_summary['annathanam']['total_amount'] += $annathanam_detail['amount'];
											$sale_summary['annathanam']['paid_amount'] += $annathanam_detail['paidamount'];


											if (empty($summary_total['sales'][$annathanam_detail['paymentmode']]))
												$summary_total['sales'][$annathanam_detail['paymentmode']] = 0;
											$summary_total['sales'][$annathanam_detail['paymentmode']] += $annathanam_detail['paidamount'];
											?>
											<tr>
												<td style="padding: 5px 10px!important;">
													<?php echo $ans_i; ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php echo $annathanam_detail['customer_name']; ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php echo $annathanam_detail['time']; ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php
													if ($annathanam_detail['paymentmode'] == "ipay_merch_qr") {
														$paymentname = "QR PAYMENT";
													} elseif ($annathanam_detail['paymentmode'] == "ipay_merch_online") {
														$paymentname = "ONLINE PAYMENT";
													} elseif ($annathanam_detail['paymentmode'] == "cash") {
														$paymentname = "CASH";
													} elseif ($annathanam_detail['paymentmode'] == "nets_pay") {
														$paymentname = 'NETS';
													} else
														$paymentname = strtoupper(str_replace('_', ' ', $annathanam_detail['paymentmode']));

													echo $paymentname;
													?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php
													echo $annathanam_detail['payment_type'];
													?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php
													echo $annathanam_detail['booking_through'];
													?>
												</td>
												<td style="padding: 5px 10px!important;text-align:right;">
													<?php
													echo number_format($annathanam_detail['amount'], 2);
													?>
												</td>
												<td style="padding: 5px 10px!important;text-align:right;">
													<?php
													echo number_format($annathanam_detail['paidamount'], 2);
													?>
												</td>
											</tr>
											<?php
											$ans_i++;
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="6">&nbsp;</td>
											<td style="padding: 5px 10px!important;text-align:right;font-weight:bold;">
												<?php echo number_format($annathanam_total, 2); ?>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<?php
						}
						?>


						<?php
						$total_grams = 0;
						if (!empty($product_offering_details)) {
							?>
							<div class="col-md-12">
								<h3>Product Offering Details</h3>
							</div>
							<div class="table-responsive col-md-12 det"
								style="background:#FFF; float:none;margin-bottom:0px;">
								<table class="table table-bordered table-striped table-hover">
									<thead style="background: #3F51B5;color: #fff;">
										<tr>
											<th style="padding: 5px 10px!important;">S.No</th>
											<th style="padding: 5px 10px!important;">Name</th>
											<th style="padding: 5px 10px!important;">Category</th>
											<th style="padding: 5px 10px!important;">Product </th>
											<th style="padding: 5px 10px!important;">Grams</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$ans_i = 1;
										foreach ($product_offering_details as $details) {
											$total_grams += $details['grams'];
											$productName = $details['category_name'];

											if (!isset($sale_summary['Product Offering'][$productName])) {
												$sale_summary['Product Offering'][$productName] = [
													'name_eng' => $productName,
													'ledger_code' => 0,
													'qty' => 0,
													'total' => 0
												];
											}

											$sale_summary['Product Offering'][$productName]['qty'] += 1;
											$sale_summary['Product Offering'][$productName]['total'] += $details['grams'];

											if (empty($summary_total['sales'][$details['paymentmode']]))
												$summary_total['sales'][$details['paymentmode']] = 0;
											$summary_total['sales'][$details['paymentmode']] += $details['grams'];
											?>
											<tr>
												<td style="padding: 5px 10px!important;">
													<?php echo $ans_i; ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php echo $details['customer_name']; ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php echo $details['category_name']; ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php echo $details['product_name']; ?>
												</td>
												<td style="padding: 5px 10px!important;text-align:right;">
													<?php echo $details['grams']; ?>
												</td>
											</tr>
											<?php
											$ans_i++;
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="4">&nbsp;</td>
											<td style="padding: 5px 10px!important;text-align:right;font-weight:bold;">
												<?php echo number_format($total_grams, 2); ?>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<?php
						}
						?>
						<?php
						$repayment_total = 0;
						$sale_summary['Repayment'] = array();
						$sale_summary['Repayment']['total_amount'] = 0;

						if (!empty($repayment_details)) {
							?>
							<div class="col-md-12">
								<h3>Repayment Details</h3>
							</div>
							<div class="table-responsive col-md-12 det"
								style="background:#FFF; float:none;margin-bottom:0px;">
								<table class="table table-bordered table-striped table-hover">
									<thead style="background: #3F51B5;color: #fff;">
										<tr>
											<th style="padding: 5px 10px!important;">S.No</th>
											<th style="padding: 5px 10px!important;">Type</th>
											<th style="padding: 5px 10px!important;">Name</th>
											<th style="padding: 5px 10px!important;">Booked Date</th>
											<th style="padding: 5px 10px!important;">Payment Mode</th>
											<th style="padding: 5px 10px!important;">Paid Through</th>
											<th style="padding: 5px 10px!important; text-align:right;">Booking Amount</th>
											<th style="padding: 5px 10px!important; text-align:right;">Amount</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$ans_i = 1;
										foreach ($repayment_details as $details) {
											$repayment_total += $details['repaid_amount'];
											$productName = $details['type'];

											if (!isset($sale_summary['Repayment'][$productName])) {
												$sale_summary['Repayment'][$productName] = [
													'name_eng' => $productName,
													'ledger_code' => 0,
													'qty' => 0,
													'total' => 0
												];
											}

											$sale_summary['Repayment'][$productName]['qty'] += 1;
											$sale_summary['Repayment'][$productName]['total'] += $details['repaid_amount'];
											$sale_summary['Repayment']['total_amount'] += $details['repaid_amount'];

											if (empty($summary_total['sales'][$details['paymentmode']]))
												$summary_total['sales'][$details['paymentmode']] = 0;
											$summary_total['sales'][$details['paymentmode']] += $details['repaid_amount'];
											?>
											<tr>
												<td style="padding: 5px 10px!important;">
													<?php echo $ans_i; ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php echo $details['type']; ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php echo $details['customer_name']; ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php echo $details['date']; ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php
													if ($details['paymentmode'] == "ipay_merch_qr") {
														$paymentname = "QR PAYMENT";
													} elseif ($details['paymentmode'] == "ipay_merch_online") {
														$paymentname = "ONLINE PAYMENT";
													} elseif ($details['paymentmode'] == "cash") {
														$paymentname = "CASH";
													} elseif ($details['paymentmode'] == "nets_pay") {
														$paymentname = 'NETS';
													} else
														$paymentname = strtoupper(str_replace('_', ' ', $details['paymentmode']));

													echo $paymentname;
													?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php echo $details['paid_through']; ?>
												</td>
												<td style="padding: 5px 10px!important; text-align:right;">
													<?php echo $details['amount']; ?>
												</td>
												<td style="padding: 5px 10px!important;text-align:right;">
													<?php echo number_format($details['repaid_amount'], 2); ?>
												</td>
											</tr>
											<?php
											$ans_i++;
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="7">&nbsp;</td>
											<td style="padding: 5px 10px!important;text-align:right;font-weight:bold;">
												<?php echo number_format($repayment_total, 2); ?>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<?php
						}
						?>
						<?php
						$payment_voucher_total = 0;
						if (count($payment_voucher_details) > 0) {
							?>
							<div class="col-md-12">
								<h3>Payment Voucher Details</h3>
							</div>
							<div class="table-responsive col-md-12 det"
								style="background:#FFF; float:none;margin-bottom:0px;">
								<table class="table table-bordered table-striped table-hover">
									<thead style="background: #3F51B5;color: #fff;">
										<tr>
											<th style="padding: 5px 10px!important;">S.No</th>
											<th style="padding: 5px 10px!important;">Paid to</th>
											<th style="padding: 5px 10px!important;">Payment Mode</th>
											<th style="padding: 5px 10px!important;">Remarks</th>
											<th style="padding: 5px 10px!important;text-align:right;">Amount</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$pv_i = 1;
										foreach ($payment_voucher_details as $payment_voucher_detail) {
											$payment_voucher_total = $payment_voucher_total + $payment_voucher_detail['paidamount'];
											if (empty($summary_total['expense'][$payment_voucher_detail['paymentmode']]))
												$summary_total['expense'][$payment_voucher_detail['paymentmode']] = 0;
											$summary_total['expense'][$payment_voucher_detail['paymentmode']] += $payment_voucher_detail['paidamount'];

											?>
											<tr>
												<td style="padding: 5px 10px!important;">
													<?php echo $pv_i; ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php echo strtoupper($payment_voucher_detail['booking_id']); ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php echo strtoupper($payment_voucher_detail['paymentmode']); ?>
												</td>
												<td style="padding: 5px 10px!important;">
													<?php echo strtoupper($payment_voucher_detail['details']); ?>
												</td>
												<td style="padding: 5px 10px!important;text-align:right;">
													<?php echo number_format($payment_voucher_detail['paidamount'], 2); ?>
												</td>
											</tr>
											<?php
											$pv_i++;
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="4">&nbsp;</td>
											<td style="padding: 5px 10px!important;text-align:right;font-weight:bold;">
												<?php echo number_format($payment_voucher_total, 2); ?>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<?php
						}
						?>
						<div class="row">
							<div class="col-md-9"></div>
							<div class="col-md-3 det" style="margin-bottom:0px;">
								<h3 style="text-align:center;font-weight: bold;">
									Summary
								</h3>
								<?php
								$cash_total = 0;
								$summary_totals_income = [];
								$summary_totals_expense = [];
								if (!empty($summary_total['sales'])) {
									foreach ($summary_total['sales'] as $vl => $st) {
										if ($vl == 'cash' || $vl == 'Cash') {
											$paymentname = 'CASH';
											$cash_total += $st;
										} elseif ($vl == "ipay_merch_qr") {
											$paymentname = "QR PAYMENT";
										} elseif ($vl == "ipay_merch_online") {
											$paymentname = "ONLINE PAYMENT";
										} elseif ($vl == "nets_pay" || $vl == "Nets Pay") {
											$paymentname = 'NETS';
										} elseif ($vl == "pay_now" || $vl == "Pay Now") {
											$paymentname = 'PAY NOW';
										} else {
											$paymentname = strtoupper(str_replace('_', ' ', $vl));
										}

										if (isset($summary_totals_income[$paymentname])) {
											$summary_totals_income[$paymentname] += $st;
										} else {
											$summary_totals_income[$paymentname] = $st;
										}
									}
								}
								if (count($summary_totals_income)) {
									echo '<h4>Incomes</h4>';
									foreach ($summary_totals_income as $paymentname => $total) {
										if (!empty($paymentname) && $total != 0 && !empty($total) && $total != '0.00') {
											if ($paymentname == 'GOLD' || $paymentname == 'SILVER') {
												echo '<p>' . $paymentname . ' : ' . number_format($total, 2) . ' grams </p>';
											} else {
												echo '<p>' . $paymentname . ' : ' . number_format($total, 2) . '</p>';
											}
										}
									}
								}

								$total = $archanai_total + $hallbooking_total + $ubayam_total + $donation_total + $prasadam_total + $annathanam_total + $repayment_total;
								echo '<p><strong>Income Sub Total:</strong> ' . number_format($total, 2) . '</p>';

								if (!empty($summary_total['expense'])) {
									foreach ($summary_total['expense'] as $vl => $st) {
										if ($vl == 'cash' || $vl == 'Cash') {
											$paymentname = 'CASH';
											$cash_total += $st;
										} elseif ($vl == "ipay_merch_qr") {
											$paymentname = "QR PAYMENT";
										} elseif ($vl == "ipay_merch_online") {
											$paymentname = "ONLINE PAYMENT";
										} elseif ($vl == "nets_pay" || $vl == "Nets Pay") {
											$paymentname = 'NETS';
										} elseif ($vl == "pay_now" || $vl == "Pay Now") {
											$paymentname = 'PAY NOW';
										} else {
											$paymentname = strtoupper(str_replace('_', ' ', $vl));
										}

										if (isset($summary_totals_expense[$paymentname])) {
											$summary_totals_expense[$paymentname] += $st;
										} else {
											$summary_totals_expense[$paymentname] = $st;
										}
									}
								}
								if (count($summary_totals_expense)) {
									echo '<br><h4>Expenses</h4>';
									foreach ($summary_totals_expense as $paymentname => $total) {
										if ($total != 0 && !empty($total) && $total != '0.00') {
											if ($paymentname == 'GOLD' || $paymentname == 'SILVER') {
												echo '<p>' . $paymentname . ' : ' . number_format($total, 2) . ' grams </p>';
											} else {
												echo '<p>' . $paymentname . ' : ' . number_format($total, 2) . '</p>';
											}
										}
									}
								}

								echo '<p><strong>Expense Sub Total:</strong> ' . number_format($payment_voucher_total, 2) . '</p>';
								?>
							</div>
							<div class="col-md-12 det" style="background:#FFF; margin-bottom:0px;">
								<h3 style="text-align:center;">
									TOTAL AMOUNT :
									<?php
									$total = $archanai_final + $hallbooking_total + $ubayam_total + $donation_total + $prasadam_total + $annathanam_total + $repayment_total - $payment_voucher_total;
									echo number_format($total, 2);
									?>
								</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>