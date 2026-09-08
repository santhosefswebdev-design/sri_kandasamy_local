<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\RequestModel;
use Exception;
use App\Models\Common_model;

class Ajax extends BaseController
{
	function __construct()
	{
		parent::__construct();
		$this->common_model = new Common_model();
		helper('url');
		helper('common_helper');
	}

	public function save_booking()
	{
		// echo "<pre>";
		// print_r($_POST);
		// exit;
		$resp = array('success' => true, 'data' => array('status' => true));
		try {
			if (!empty($_REQUEST['save_booking']))
				$request_all_data = $_REQUEST;
			else
				$request_all_data = json_decode(file_get_contents('php://input'), true);
			// $request = \Config\Services::request();
			// $request_all_data = array_merge($request->getPost(), $request->getGet());
			// print_r($request_all_data);
			// die;
			if (!empty($request_all_data['save_booking'])) {
				$this->db->transStart();
				if (!empty($this->session->get('log_id')) || !empty($request_all_data['user_id'])) {

					$user_id = !empty($this->session->get('log_id')) ? $this->session->get('log_id') : $request_all_data['user_id'];
					if (!empty($request_all_data['booking_slot']) && !empty($request_all_data['booking_date']) && !empty($request_all_data['booking_type']) && !empty($request_all_data['name']) && !empty($request_all_data['mobile_code']) && !empty($request_all_data['mobile_no']) && !empty($request_all_data['packages']) && !empty($request_all_data['payment_type'])) {
						$payment_type = trim($request_all_data['payment_type']);
						$payment_mode = $request_all_data['payment_mode'];
						$paymode_details = $this->db->table('payment_mode')->where("id", $payment_mode)->get()->getRowArray();
						$is_payment_gateway = $paymode_details['is_payment_gateway'];
						$payment_key = $paymode_details['pay_key'];
						if ($payment_type == 'partial') {
							if (!empty($request_all_data['payment_details']))
								$payment_details = $request_all_data['payment_details'];
							else {
								$resp['success'] = true;
								$resp['data']['status'] = false;
								$resp['data']['message'] = 'Please Fill Payment details.';
								$resp['data']['message_type'] = 'error';
								header('Content-Type: application/json; charset=utf-8');
								echo json_encode($resp);
								exit;
							}
						} elseif ($payment_type == 'full') {
							if (!empty($request_all_data['payment_mode']))
								$payment_mode = $request_all_data['payment_mode'];
							else {
								$resp['success'] = true;
								$resp['data']['status'] = false;
								$resp['data']['message'] = 'Please Fill Payment details.';
								$resp['data']['message_type'] = 'error';
								header('Content-Type: application/json; charset=utf-8');
								echo json_encode($resp);
								exit;
							}
						} elseif ($payment_type == 'only_booking') {
							if (!empty($request_all_data['total_amt']))
								$payment_mode = '';
							else {
								$resp['success'] = true;
								$resp['data']['status'] = false;
								$resp['data']['message'] = 'Please Fill Amount details.';
								$resp['data']['message_type'] = 'error';
								header('Content-Type: application/json; charset=utf-8');
								echo json_encode($resp);
								exit;
							}
						} else {
							$resp['success'] = true;
							$resp['data']['status'] = false;
							$resp['data']['message'] = 'Invalid Payment Type.';
							$resp['data']['message_type'] = 'error';
							header('Content-Type: application/json; charset=utf-8');
							echo json_encode($resp);
							exit;
						}
						$booking_ins_data = array();
						$booking_ins_data['tpri_ref_no'] = !empty($request_all_data['tpri_ref_no']) ? trim($request_all_data['tpri_ref_no']) : null;
						$booking_ins_data['booking_type'] = $booking_type = trim($request_all_data['booking_type']);
						$booking_ins_data['booking_date'] = trim($request_all_data['booking_date']);
						$booking_ins_data['name'] = trim($request_all_data['name']);
						$booking_ins_data['mobile_code'] = trim($request_all_data['mobile_code']);
						$booking_ins_data['mobile_no'] = trim($request_all_data['mobile_no']);
						$booking_ins_data['rasi_id'] = trim($request_all_data['rasi_id']);
						$booking_ins_data['natchathiram_id'] = trim($request_all_data['natchathra_id']);
						$booking_ins_data['deposit_amount'] = $deposit_amt = !empty($request_all_data['deposit_amt']) ? (float) $request_all_data['deposit_amt'] : 0;
						$booking_ins_data['payment_type'] = $payment_type;
						$booking_ins_data['entry_date'] = !empty($request_all_data['entry_date']) ? trim($request_all_data['entry_date']) : date('Y-m-d');
						$booking_ins_data['booking_through'] = !empty($request_all_data['booking_through']) ? trim($request_all_data['booking_through']) : 'DIRECT';
						$booking_ins_data['booking_status'] = 0;
						$booking_ins_data['payment_status'] = empty($is_payment_gateway) ? 0 : 1;

						if (!empty($request_all_data['brideName']))
							$booking_brides_data['brideName'] = trim($request_all_data['brideName']);
						if (!empty($request_all_data['brideDOB']))
							$booking_brides_data['brideDOB'] = trim($request_all_data['brideDOB']);
						if (!empty($request_all_data['brideIC']))
							$booking_brides_data['brideIC'] = trim($request_all_data['brideIC']);
						if (!empty($request_all_data['groomName']))
							$booking_brides_data['groomName'] = trim($request_all_data['groomName']);
						if (!empty($request_all_data['groomDOB']))
							$booking_brides_data['groomDOB'] = trim($request_all_data['groomDOB']);
						if (!empty($request_all_data['groomIC']))
							$booking_brides_data['groomIC'] = trim($request_all_data['groomIC']);
						if (!empty($request_all_data['address']))
							$booking_ins_data['address'] = trim($request_all_data['address']);
						if (!empty($request_all_data['email']))
							$booking_ins_data['email'] = trim($request_all_data['email']);
						if (!empty($request_all_data['description']))
							$booking_ins_data['description'] = trim($request_all_data['description']);
						if ($booking_type == 1)
							$booking_ins_data['venue'] = trim($request_all_data['venue']);
						if ($booking_type == 2)
							$booking_ins_data['deity_id'] = trim($request_all_data['deity_id']);
						if ($booking_ins_data['entry_date'] <= $booking_ins_data['booking_date']) {
							if (in_array($booking_ins_data['booking_through'], array('DIRECT', 'COUNTER', 'ONLINE', 'KIOSK', 'APP'))) {
								$this->requestmodel = new RequestModel();
								$ip = $this->requestmodel->getIpAddress();
								$booking_ins_data['ip'] = $ip;
								if ($ip != 'unknown') {
									$ip_details = $this->requestmodel->getLocation($ip);
									$booking_ins_data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
									$booking_ins_data['ip_details'] = json_encode($ip_details);
								}
								$booking_ins_data['created_by'] = $user_id;
								$booking_ins_data['modified_by'] = $user_id;

								$res = $this->db->table("templebooking")->insert($booking_ins_data);
								//$whatsapp_resp = whatsapp_aisensy($data['mobile_number'], [], 'success_message1');
								if ($res) {
									$booking_id = $this->db->insertID();
									$this->sub_total = 0;
									$this->total_amount = 0;
									$this->paid_amount = 0;
									$this->final_total = 0;
									if ($booking_ins_data['booking_type'] == 1)
										$ref_no = 'HALL';
									elseif ($booking_ins_data['booking_type'] == 2)
										$ref_no = 'UBAY';
									elseif ($booking_ins_data['booking_type'] == 3)
										$ref_no = 'SANN';
									else
										$ref_no = 'TEMP';
									$ref_no .= str_pad($booking_id, 16, 0, STR_PAD_LEFT);
									$booking_ref_data = array();
									$booking_ref_data['ref_no'] = $ref_no;
									$this->db->table("templebooking")->where('id', $booking_id)->update($booking_ref_data);

									if (!empty($request_all_data['extra_charges'])) {
										$extra_charges = isset($request_all_data['extra_charges']) ? json_decode($request_all_data['extra_charges'], true) : [];
										$max_count = count($extra_charges);
										for ($i = 0; $i < $max_count; $i++) {
											$total_amt = 0;
											$extra_charge_data = array();
											$extra_charge_data['booking_id'] = $booking_id;
											if (isset($extra_charges[$i])) {
												$extra_charge_data['booking_type'] = $booking_type;
												$extra_charge_data['description'] = $extra_charges[$i]['description'];
												$extra_charge_data['amount'] = $extra_charges[$i]['amount'];
												$extra_charge_data['ledger_id'] = 223;
												$total_amt += $extra_charges[$i]['amount'];
											}
											$this->total_amount += $total_amt;
											$this->db->table("booked_extra_charges")->insert($extra_charge_data);
										}
									}
									if (!$this->save_booking_slot($booking_id, $request_all_data['booking_slot'])) {
										$this->db->transRollback();
										$resp['success'] = true;
										$resp['data']['status'] = false;
										$resp['data']['message'] = 'Invalid Slot';
										// $resp['data']['message_type'] = 'error';
										header('Content-Type: application/json; charset=utf-8');
										echo json_encode($resp);
										exit;
									}
									if (!empty($request_all_data['add_on'])) {
										if (!$this->save_booking_addon($booking_id, $request_all_data['add_on'])) {
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Invalid Add on';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
									}
									if (!empty($request_all_data['free_prasadam'])) {
										if (!$this->save_free_prasadam($booking_id, $request_all_data)) {
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Invalid Free Prasadam';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
									}
									if (!empty($request_all_data['prasadam'])) {
										if (!$this->save_prasadam($booking_id, $request_all_data)) {
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Invalid Prasadam';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
									}
									$this->db->table('booked_bride_details')->delete(['booking_id' => $booking_id]);
									if (!empty($booking_brides_data['brideName']) && !empty($booking_brides_data['brideDOB']) && !empty($booking_brides_data['brideIC'])) {
										$booking_brides_ins_data = array();
										$booking_brides_ins_data['booking_id'] = $booking_id;
										$booking_brides_ins_data['booking_type'] = $booking_ins_data['booking_type'];
										$booking_brides_ins_data['bride_type'] = 'bride';
										$booking_brides_ins_data['name'] = $booking_brides_data['brideName'];
										$booking_brides_ins_data['nric'] = $booking_brides_data['brideIC'];
										$booking_brides_ins_data['dob'] = $booking_brides_data['brideDOB'];
										$this->db->table("booked_bride_details")->insert($booking_brides_ins_data);
									}
									if (!empty($booking_brides_data['groomName']) && !empty($booking_brides_data['groomDOB']) && !empty($booking_brides_data['groomIC'])) {
										$booking_brides_ins_data = array();
										$booking_brides_ins_data['booking_id'] = $booking_id;
										$booking_brides_ins_data['booking_type'] = $booking_ins_data['booking_type'];
										$booking_brides_ins_data['bride_type'] = 'groom';
										$booking_brides_ins_data['name'] = $booking_brides_data['groomName'];
										$booking_brides_ins_data['nric'] = $booking_brides_data['groomIC'];
										$booking_brides_ins_data['dob'] = $booking_brides_data['groomDOB'];
										$this->db->table("booked_bride_details")->insert($booking_brides_ins_data);
									}
									if (!$this->save_booking_packages($booking_id, $request_all_data['packages'], $request_all_data['pack_amount'], $booking_ins_data['booking_type'])) {
										$this->db->transRollback();
										$resp['success'] = true;
										$resp['data']['status'] = false;
										$resp['data']['message'] = 'Invalid Package';
										// $resp['data']['message_type'] = 'error';
										header('Content-Type: application/json; charset=utf-8');
										echo json_encode($resp);
										exit;
									}
									$this->sub_total = $this->total_amount;
									if (!empty($request_all_data['discount_amount'])) {
										$this->total_amount -= $request_all_data['discount_amount'];
									}
									if (!empty($deposit_amt)) {
										if (!$this->save_deposit_payment($deposit_amt, $booking_id, $payment_type, $payment_details, $booking_ins_data['booking_type'], $ref_no, $booking_ins_data['booking_through'], $payment_mode)) {
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Invalid Deposit Payment';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
									}

									if ($payment_type == 'partial') {
										$booked_pay_id = $this->save_booking_payment($deposit_amt, $booking_id, $payment_type, $payment_details, $booking_ins_data['booking_type'], $ref_no, $booking_ins_data['booking_through']);
										if ($booked_pay_id === false) {
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Invalid Payment, Please check the amount first';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
									} elseif ($payment_type == 'full') {
										$booked_pay_id = $this->save_booking_payment($deposit_amt, $booking_id, $payment_type, array(), $booking_ins_data['booking_type'], $ref_no, $booking_ins_data['booking_through'], $payment_mode);
										if ($booked_pay_id === false) {
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Invalid Payment, Please check the amount first';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
									} elseif ($payment_type !== 'only_booking') {
										$this->db->transRollback();
										$resp['success'] = true;
										$resp['data']['status'] = false;
										$resp['data']['message'] = 'Invalid Payment';
										// $resp['data']['message_type'] = 'error';
										header('Content-Type: application/json; charset=utf-8');
										echo json_encode($resp);
										exit;
									}

									if (!empty($this->total_amount)) {
										$booking_ref_data = array();
										if (!empty($request_all_data['discount_amount'])) {
											$booking_ref_data['discount_amount'] = $request_all_data['discount_amount'];
										} else
											$booking_ref_data['discount_amount'] = 0;
										$this->final_total = $this->total_amount;
										$this->total_amount -= $deposit_amt;
										$booking_ref_data['amount'] = $this->total_amount;
										$booking_ref_data['paid_amount'] = !empty($this->paid_amount) ? $this->paid_amount : 0;
										$booking_ref_data['total_amount'] = $this->final_total;
										if ($this->final_total < $this->paid_amount) {
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Payment amount is not greater than total amount';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
										if ($payment_type != 'only_booking' && empty($this->paid_amount)) {
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Payment amount is not empty';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
									} else {
										$this->db->transRollback();
										$resp['success'] = true;
										$resp['data']['status'] = false;
										$resp['data']['message'] = 'Invalid Package or Payment';
										header('Content-Type: application/json; charset=utf-8');
										echo json_encode($resp);
										exit;
									}

									$module_name = $booking_type == 1 ? '_HALL_' : '_UBAYAM_';
									if ($payment_key == 'rhb_qr') {
										$ref_no = PAYMENT_PREFIX . $module_name . $booking_id;
										if (PAYMENT_TEST) {
											$pay_amount = 1;
										} else {
											$pay_amount = bcdiv($this->paid_amount, '1', 2);
										}

										$bill_num = (string) ($booking_id + 200000000000);
										$url = 'https://dnqr.synexisasia.com/v1/qr';

										$json_data = [
											"merchantCode" => RHB_MERCHANTCODE,
											"userId" => RHB_USERID,
											"amount" => $pay_amount,
											"billNumber" => $bill_num,
											"transactionReference" => $ref_no,
										];

										$response = $this->common_model->postJson($url, $json_data);
										$response = json_decode($response);

										if (!empty($response->qrCode)) {
											$booked_pay_details = ['request_data' => json_encode($json_data)];
											$this->db->table('booked_pay_details')->where('id', $booked_pay_id)->update($booked_pay_details);

											$resp['data']['qr_code'] = $response->qrCode;
											$resp['data']['total_amount'] = bcdiv($this->paid_amount, '1', 2);
										} else {
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['pay_status'] = false;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'QR code not generated. Kindly rebook the ticket.';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}

										$this->db->table("templebooking")->where('id', $booking_id)->update($booking_ref_data);
										$resp['success'] = true;
										$resp['data']['pay_status'] = false;
										$resp['data']['payment_key'] = $payment_key;
										$resp['data']['status'] = true;
										$resp['data']['booking_id'] = $booking_id;
										$resp['data']['message'] = 'Proceed to RHB Payment';
									} elseif ($payment_key == 'eghl_qr') {
										try {
											if (empty(EGHL_MERCHANTID) || empty(EGHL_TERMINALID)) {
												throw new \RuntimeException('EGHL credentials are not configured.');
											}

											$pay_amount = EGHL_TEST ? 1 : bcdiv($this->paid_amount, '1', 2);

											$txnPMT = new \App\Libraries\MahJsonAPI(EGHL_SERVER_CERT_PATH, EGHL_CLIENT_KEY_PATH);
											$txnPMT->Amount = $pay_amount;
											$txnPMT->setNewRetTxnRef(EGHL_PREFIX . $module_name . $booking_id . '_' . $booked_pay_id);
											$txnPMT->MerchantID = EGHL_MERCHANTID;
											$txnPMT->OperatorID = 'SALE';
											$txnPMT->TerminalID = $this->resolveEghlTerminalId();
											$txnPMT->ProductCode = 'DUITNOWDQR';

											$raw_response = $txnPMT->paymentAsynchronous();
											if ($raw_response === 'Invalid signature') {
												throw new \RuntimeException('EGHL response failed signature verification.');
											}
											$eghl_response = json_decode($raw_response);

											if (!empty($eghl_response->msg->DisplayInfo[0]->Value)) {
												$booked_pay_details = ['request_data' => json_encode($eghl_response)];
												$this->db->table('booked_pay_details')->where('id', $booked_pay_id)->update($booked_pay_details);

												$resp['data']['qr_code'] = $eghl_response->msg->DisplayInfo[0]->Value; // base64 PNG
												$resp['data']['total_amount'] = $pay_amount;
											} else {
												$error_code = $eghl_response->msg->ResponseCode ?? null;
												$error_msg  = $eghl_response->msg->ResponseMsg ?? 'No QR returned';
												throw new \RuntimeException("EGHL Sale failed [{$error_code}]: {$error_msg}");
											}
										} catch (\Throwable $e) {
											log_message('error', 'EGHL_QR_SALE_FAILURE | user_id=' . ($this->session->get('log_id_frend') ?? '')
												. ' | booking_id=' . $booking_id
												. ' | amount=' . $this->paid_amount
												. ' | status=failed'
												. ' | error_code=' . $e->getCode()
												. ' | message=' . $e->getMessage()
												. ' | datetime=' . date('Y-m-d H:i:s'));

											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['pay_status'] = false;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'QR code not generated. Kindly rebook the ticket.';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}

										$this->db->table("templebooking")->where('id', $booking_id)->update($booking_ref_data);
										$resp['success'] = true;
										$resp['data']['pay_status'] = false;
										$resp['data']['payment_key'] = $payment_key;
										$resp['data']['status'] = true;
										$resp['data']['booking_id'] = $booking_id;
										$resp['data']['message'] = 'Proceed to EGHL Payment';
									} else {
										if ($this->final_total <= $this->paid_amount)
											$booking_ref_data['payment_status'] = 2;
										elseif ($this->paid_amount == 0)
											$booking_ref_data['payment_status'] = 0;
										else
											$booking_ref_data['payment_status'] = 1;
										$booking_ref_data['booking_status'] = 1;
										$this->db->table("templebooking")->where('id', $booking_id)->update($booking_ref_data);
										unset($this->total_amount);
										unset($this->paid_amount);

										if (!$this->account_migration($booking_id)) {
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Invalid Account Migration';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
										if ($booking_ref_data['payment_status'] == 1 || $booking_ref_data['payment_status'] == 2) {
											// Send WhatsApp confirmation for paid bookings
											$this->send_ubayam_booking_confirmation($booking_id);
										}
										$resp['success'] = true;
										$resp['data']['pay_status'] = true;
										$resp['data']['status'] = true;
										$resp['data']['message'] = 'Your Booking confirmed.';
										$resp['data']['booking_id'] = $booking_id;
									}
								} else {
									$resp['success'] = true;
									$resp['data']['status'] = false;
									$resp['data']['message'] = 'Please contact Administrator.';
									// $resp['data']['message_type'] = 'error';
									header('Content-Type: application/json; charset=utf-8');
									echo json_encode($resp);
									exit;
								}
							} else {
								$resp['success'] = true;
								$resp['data']['status'] = false;
								$resp['data']['message'] = 'Booking only allowed in DIRECT, COUNTER, KIOSK, APP';
								// $resp['data']['message_type'] = 'error';
								header('Content-Type: application/json; charset=utf-8');
								echo json_encode($resp);
								exit;
							}
						} else {
							$resp['success'] = true;
							$resp['data']['status'] = false;
							$resp['data']['message'] = 'Can\'t book the previous day';
							// $resp['data']['message_type'] = 'error';
							header('Content-Type: application/json; charset=utf-8');
							echo json_encode($resp);
							exit;
						}
					} else {
						$resp['success'] = true;
						$resp['data']['status'] = false;
						$resp['data']['message'] = 'Please Fill All Required fields(some details)';
						// $resp['data']['message_type'] = 'error';
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($resp);
						exit;
					}
				} else {
					$resp['success'] = true;
					$resp['data']['status'] = false;
					$resp['data']['message'] = 'Please Login or Pass Auth id';
					// $resp['data']['message_type'] = 'error';
					header('Content-Type: application/json; charset=utf-8');
					echo json_encode($resp);
					exit;
				}
				$this->db->transComplete();
				if ($this->db->transStatus() === FALSE) {
					throw new Exception('Transaction Failed');
				}
				// $resp['success'] = true;
				// $resp['data']['status'] = true;
				// $resp['data']['message'] = 'Your Booking confirmed.';
				// $resp['data']['booking_id'] = $booking_id;
				if (!empty($request_all_data['print']))
					$resp['data']['print'] = 1;
				//if(!empty($booking_id) && !empty($booking_type)) $this->send_whatsapp_msg_booking($booking_id, $booking_type);

			} else {
				throw new Exception("Missing save_booking parameter.");
			}
		} catch (\Throwable $e) {
			$this->db->transRollback(); // Rollback the transaction if an error occurs
			$resp['success'] = false;
			$resp['data']['status'] = false;
			$resp['data']['message'] = $e->getMessage();
			// $resp['data']['message_type'] = 'error';
			log_message('error', 'SAVE_BOOKING_FAILURE | user_id=' . ($this->session->get('log_id') ?? ($request_all_data['user_id'] ?? ''))
				. ' | message=' . $e->getMessage()
				. ' | file=' . $e->getFile() . ':' . $e->getLine()
				. ' | trace=' . $e->getTraceAsString()
				. ' | request=' . json_encode($request_all_data ?? []));
		}

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($resp);
		exit;
	}

	public function payment_check()
	{
		$data = [];
		if (!empty($_REQUEST['booking_id'])) {
			$booking_id = $_REQUEST['booking_id'];
			$user_id = $_SESSION['log_id_frend'];

			$booking_cnt = $this->db->table('templebooking')->where('id', $booking_id)->countAllResults(false);

			if ($booking_cnt > 0) {
				try {
					// Get booking record
					$booking = $this->db->table('templebooking')->where('id', $booking_id)->get()->getRow();

					// Get payment gateway data
					$booked_pay_details = $this->db->table('booked_pay_details')->select('booked_pay_details.*, payment_mode.pay_key')->join('payment_mode', 'payment_mode.id = booked_pay_details.payment_mode_id', 'left')->where('booked_pay_details.booking_id', $booking_id)->get()->getRowArray();
					if ($booking->payment_status == 1) {
						if ($booked_pay_details['pay_key'] == 'rhb_qr' || $booked_pay_details['pay_key'] == 'eghl_qr') {
							$rtn = $booked_pay_details['pay_key'] == 'eghl_qr'
								? $this->initiate_eghl_qr($booking_id, $booking, $booked_pay_details)
								: $this->initiate_rhb_qr($booking_id, $booking, $booked_pay_details);
							if ($rtn['status'] == 'pending') {
								$data = array(
									'status' => true,
									'pay_status' => false,
									'order_status' => 'pending',
									'org_msg' => $rtn['org_msg'],
									'error_msg' => "Transaction is still pending",
								);
								return json_encode($data);
							} elseif ($rtn['status'] == 'success') {
								$data = array(
									'status' => true,
									'pay_status' => true,
									'order_status' => 'success',
									'org_msg' => $rtn['org_msg'],
									'error_msg' => "Thank you for using SMMDT Self Kiosk",
								);
								return json_encode($data);
							} elseif ($rtn['status'] == 'failed') {
								$data = array(
									'status' => false,
									'pay_status' => false,
									'order_status' => 'failed',
									'org_msg' => $rtn['org_msg'],
									'error_msg' => "We’re sorry! your payment is failed. Kindly try again.",
								);
								return json_encode($data);
							} else {
								$data = array(
									'status' => false,
									'pay_status' => false,
									'order_status' => 'unidentify',
									'org_msg' => $rtn['org_msg'],
									'error_msg' => "We’re sorry! your payment didn’t went through, kindly try again. If payment has been deducted but Prasadam didn’t print. Kindly contact the Bank or Payment Gateway",
								);
								return json_encode($data);
							}
						} else {
							$data = [
								'status' => true,
								'pay_status' => false,
								'order_status' => 'failed',
								'org_msg' => 'Invalid Payment',
								'error_msg' => "Invalid Payment",
							];
							return json_encode($data);
						}
					} elseif ($booking->payment_status == 3) {
						$data = [
							'status' => true,
							'pay_status' => false,
							'order_status' => 'failed',
							'org_msg' => 'Transaction Failed',
							'error_msg' => "We’re sorry! your payment is failed. Kindly try again.",
						];
						return json_encode($data);
					} else {
						$data = [
							'status' => true,
							'pay_status' => true,
							'order_status' => 'success',
							'org_msg' => 'Transaction Successful',
							'error_msg' => "Thank you for using SMMDT Self Kiosk",
						];
						return json_encode($data);
					}
				} catch (\Exception $e) {
					$data = [
						'status' => false,
						'pay_status' => false,
						'error_msg' => $e->getMessage(),
					];
					return json_encode($data);
				}
			} else {
				$data = [
					'status' => false,
					'pay_status' => false,
					'org_msg' => 'Transaction Failed',
					'error_msg' => "Invalid Archani.",
				];
				return json_encode($data);
			}
		} else {
			$data = [
				'status' => false,
				'pay_status' => false,
				'org_msg' => 'Transaction Failed',
				'error_msg' => "Invalid Archani.",
			];
			return json_encode($data);
		}
	}
	public function initiate_rhb_qr($booking_id, $booking, $booked_pay_details)
	{
		$request_data = json_decode($booked_pay_details['request_data']);
		$booked_pay_details_id = $booked_pay_details['id'];
		$rtn = [];

		if (!empty($request_data->billNumber) && !empty($request_data->transactionReference)) {
			$merchant_id = config('Variables')->rhb_userId;  // Adjust if needed

			$json_data = [
				'billNumber' => $request_data->billNumber,
				'userId' => RHB_USERID,
				'referenceNo' => $request_data->transactionReference,
			];

			$url = 'https://dnqr.synexisasia.com/v1/qr/status';
			$response = $this->common_model->getJson($url, $json_data);

			$this->db->table('booked_pay_details')->where('id', $booked_pay_details_id)->update(['response_data' => $response]);
			$response_data = json_decode($response);

			if (!empty($response_data->paymentStatus)) {
				if ($response_data->paymentStatus === 'FOUND') {
					$rtn['status'] = 'success';

					// Update archanai_booking payment_status = 2
					$this->db->table('templebooking')->where('id', $booking_id)->update(['payment_status' => 2, 'booking_status' => 1]);

					// Call migration functions
					$this->account_migration($booking_id);
					// $this->send_whatsapp_msg($prasadam_id);
					// $this->send_mail_to_customer($prasadam_id);

					$rtn['org_msg'] = 'Transaction Successful';
				} else {
					$rtn['status'] = 'pending';
					$rtn['org_msg'] = 'Transaction Pending';
				}
			} else {
				$rtn['status'] = 'unidentify';
				$rtn['org_msg'] = 'Server Down';
			}
		} else {
			$rtn['status'] = 'unidentify';
			$rtn['org_msg'] = 'Server Down';
		}

		return $rtn;
	}

	private function resolveEghlTerminalId(){
		$login_id = $this->session->get('log_id_frend');
		$login = $this->db->table('login')
			->select('eghl_terminal_id')
			->where('id', $login_id)
			->get()
			->getRowArray();
		return !empty($login['eghl_terminal_id']) ? $login['eghl_terminal_id'] : EGHL_TERMINALID;
	}

	public function initiate_eghl_qr($booking_id, $booking, $booked_pay_details){
		$request_data = json_decode($booked_pay_details['request_data']);
		$booked_pay_details_id = $booked_pay_details['id'];
		$rtn = [];

		if (empty($request_data->msg->RetTxnRef) || empty($request_data->msg->TxnRef)) {
			$rtn['status'] = 'unidentify';
			$rtn['org_msg'] = 'Server Down';
			return $rtn;
		}

		try {
			$pay_amount = EGHL_TEST ? 1 : bcdiv($booking->paid_amount, '1', 2);

			$txnPMT = new \App\Libraries\MahJsonAPI(EGHL_SERVER_CERT_PATH, EGHL_CLIENT_KEY_PATH);
			$txnPMT->Amount = $pay_amount;
			$txnPMT->setLatRetTxnRef($request_data->msg->RetTxnRef);
			$txnPMT->MerchantID = EGHL_MERCHANTID;
			$txnPMT->OperatorID = 'SALE';
			$txnPMT->TerminalID = $request_data->msg->TerminalID ?? $this->resolveEghlTerminalId();
			$txnPMT->ProductCode = 'DUITNOWDQR';

			$response = $txnPMT->paymentQuery($request_data->msg->TxnRef);

			if ($response === 'Invalid signature') {
				log_message('error', 'EGHL_QR_QUERY_INVALID_SIGNATURE | user_id=' . ($this->session->get('log_id_frend') ?? '')
					. ' | booking_id=' . $booking_id
					. ' | amount=' . $booking->paid_amount
					. ' | status=unidentify'
					. ' | error_code=SIGNATURE'
					. ' | message=EGHL query response failed signature verification'
					. ' | datetime=' . date('Y-m-d H:i:s'));
				$rtn['status'] = 'unidentify';
				$rtn['org_msg'] = 'Server Down';
				return $rtn;
			}

			$this->db->table('booked_pay_details')->where('id', $booked_pay_details_id)->update(['response_data' => $response]);
			$response_data = json_decode($response);

			if (isset($response_data->msg->OrgResponseCode) && isset($response_data->msg->OrgResponseMsg)) {
				if ($response_data->msg->OrgResponseCode != 'PN') {
					if ($response_data->msg->OrgResponseCode == '00') {
						$rtn['status'] = 'success';
						$this->db->table('templebooking')->where('id', $booking_id)->update(['payment_status' => 2, 'booking_status' => 1]);
						$this->account_migration($booking_id);
					} else {
						$this->db->table('templebooking')->where('id', $booking_id)->update(['payment_status' => 3]);
						$rtn['status'] = 'failed';
						log_message('error', 'EGHL_QR_QUERY_FAILED | user_id=' . ($this->session->get('log_id_frend') ?? '')
							. ' | booking_id=' . $booking_id
							. ' | amount=' . $booking->paid_amount
							. ' | status=failed'
							. ' | error_code=' . $response_data->msg->OrgResponseCode
							. ' | message=' . $response_data->msg->OrgResponseMsg
							. ' | datetime=' . date('Y-m-d H:i:s'));
					}
				} else {
					$rtn['status'] = 'pending';
				}
				$rtn['org_msg'] = $response_data->msg->OrgResponseMsg;
			} else {
				$rtn['status'] = 'unidentify';
				$rtn['org_msg'] = 'Server Down';
			}
		} catch (\Throwable $e) {
			log_message('error', 'EGHL_QR_QUERY_EXCEPTION | user_id=' . ($this->session->get('log_id_frend') ?? '')
				. ' | booking_id=' . $booking_id
				. ' | amount=' . $booking->paid_amount
				. ' | status=unidentify'
				. ' | error_code=' . $e->getCode()
				. ' | message=' . $e->getMessage()
				. ' | datetime=' . date('Y-m-d H:i:s'));
			$rtn['status'] = 'unidentify';
			$rtn['org_msg'] = 'Server Down';
		}

		return $rtn;
	}

	public function cancel_booking()
	{
		$data = [];

		if (!empty($_REQUEST['booking_id'])) {
			$booking_id = $_REQUEST['booking_id'];
			$user_id = $_SESSION['log_id_frend'];

			$booking_cnt = $this->db->table('templebooking')->where('id', $booking_id)->countAllResults(false);
			if ($booking_cnt > 0) {
				try {
					$booking = $this->db->table('templebooking')->where('id', $booking_id)->get()->getRow();
					$booked_pay_details = $this->db->table('booked_pay_details')->where('booking_id', $booking_id)->get()->getRowArray();

					if ($booking->payment_status == 1) {
						if (!empty($booked_pay_details) && ($booked_pay_details['pay_method'] === 'RHB QR' || $booked_pay_details['pay_method'] === 'EGHL QR')) {
							$rtn = $booked_pay_details['pay_method'] === 'EGHL QR'
								? $this->initiate_eghl_qr($booking_id, $booking, $booked_pay_details)
								: $this->initiate_rhb_qr($booking_id, $booking, $booked_pay_details);

							if ($rtn['status'] == 'success') {
								$data = [
									'status' => true,
									'pay_status' => true,
									'order_status' => 'success',
									'org_msg' => $rtn['org_msg'],
									'error_msg' => "Thank you for using SMMDT Self Kiosk",
								];
								return json_encode($data);
							} else {
								// Update payment_status to 3 = failed
								$this->db->table('templebooking')->where('id', $booking_id)->update(['payment_status' => 3]);

								$data = [
									'status' => true,
									'pay_status' => false,
									'order_status' => 'failed',
									'org_msg' => 'Transaction Failed',
									'error_msg' => "We’re sorry! your payment is failed. Kindly try again.",
								];
								return json_encode($data);
							}
						} else {
							// Update payment_status to 3 = failed
							$this->db->table('templebooking')->where('id', $booking_id)->update(['payment_status' => 3]);

							$data = [
								'status' => true,
								'pay_status' => false,
								'order_status' => 'failed',
								'org_msg' => 'Transaction Failed',
								'error_msg' => "We’re sorry! your payment is failed. Kindly try again.",
							];
							return json_encode($data);
						}
					} elseif ($booking->payment_status == 3) {
						$data = [
							'status' => true,
							'pay_status' => false,
							'order_status' => 'failed',
							'org_msg' => 'Transaction Failed',
							'error_msg' => "We’re sorry! your payment is failed. Kindly try again.",
						];
						return json_encode($data);
					} else {
						$data = [
							'status' => true,
							'pay_status' => true,
							'order_status' => 'success',
							'org_msg' => 'Transaction Successful',
							'error_msg' => "Thank you for using SMMDT Self Kiosk",
						];
						return json_encode($data);
					}
				} catch (\Exception $e) {
					$data = [
						'status' => false,
						'pay_status' => false,
						'error_msg' => $e->getMessage(),
					];
					return json_encode($data);
				}
			} else {
				$data = [
					'status' => false,
					'pay_status' => false,
					'org_msg' => 'Transaction Failed',
					'error_msg' => "Invalid Booking1.",
				];
				return json_encode($data);
			}
		} else {
			$data = [
				'status' => false,
				'pay_status' => false,
				'org_msg' => 'Transaction Failed',
				'error_msg' => "Invalid Booking2.",
			];
			return json_encode($data);
		}
	}


	public function save_prasadam($booking_id, $request_all_data)
	{
		$succ = true;

		if (!empty($request_all_data['prasadam'])) {
			$customer_name = $request_all_data['name'];
			$date = $request_all_data['dt'];
			$collection_date = $request_all_data['booking_date'];
			$payment_mode = $request_all_data['payment_mode'];

			$yr = date('Y');
			$mon = date('m');
			$query = $this->db->query("SELECT ref_no FROM prasadam WHERE id=(SELECT MAX(id) FROM prasadam WHERE YEAR(date)='" . $yr . "' AND MONTH(date)='" . $mon . "')")->getRowArray();
			$data['ref_no'] = 'PR' . date('y') . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
			$data['date'] = date('Y-m-d', strtotime($request_all_data['dt']));
			$data['for_ubayam'] = 1;
			$data['ubayam_id'] = $booking_id;
			$data['customer_name'] = $customer_name;
			$mble_phonecode = !empty($request_all_data['mobile_code']) ? $request_all_data['mobile_code'] : "";
			$mble_number = !empty($request_all_data['mobile_no']) ? $request_all_data['mobile_no'] : "";
			$data['mobile_no'] = $mble_phonecode . $mble_number;
			$data['amount'] = 0;
			$data['paid_amount'] = 0;
			$data['collection_date'] = $collection_date;
			$data['booking_status'] = 1;
			$data['payment_type'] = 'full';
			$data['payment_status'] = 2;
			$data['paid_through'] = !empty($request_all_data['booking_through']) ? trim($request_all_data['booking_through']) : 'DIRECT';
			$data['added_by'] = $request_all_data['user_id'];
			$data['payment_mode'] = $payment_mode;
			$data['created_at'] = date('Y-m-d H:i:s');
			$data['updated_at'] = date('Y-m-d H:i:s');
			$slots = $this->db->table("booked_slot")->where('booking_id', $booking_id)->get()->getRowArray();
			$data['collection_session'] = $slots['slot_name'];
			$this->db->transStart();

			$res = $this->db->table('prasadam')->insert($data);
			$ins_id = $this->db->insertID();

			if ($res) {
				if (!empty($request_all_data['prasadam'])) {
					$prasadam_details = isset($request_all_data['prasadam']) ? $request_all_data['prasadam'] : [];
					foreach ($prasadam_details as $prasadam) {
						$prsm_set = $this->db->table('prasadam_setting')->where('id', $prasadam['id'])->get()->getRowArray();
						$prasadam_tot_amt += $prasadam['total_amount'];
						$data_prdm_book = [
							'prasadam_booking_id' => $ins_id,
							'prasadam_id' => $prasadam['id'],
							'quantity' => $prasadam['quantity'],
							'created' => date('Y-m-d H:i:s'),
							'amount' => $prasadam['amount'],
							'total_amount' => $prasadam['total_amount']
						];
						$res_2 = $this->db->table('prasadam_booking_details')->insert($data_prdm_book);

						$settings = $this->db->table('settings')->where('type', 5)->where('setting_name', 'enable_madapalli')->get()->getRowArray();

						if ($settings['setting_value'] == 1) {
							$madapalli_details['date'] = $collection_date;
							$madapalli_details['type'] = 1;
							$madapalli_details['booking_id'] = $ins_id;
							$madapalli_details['product_id'] = $prasadam['id'];
							$madapalli_details['quantity'] = $prasadam['quantity'];
							$madapalli_details['amount'] = $prasadam['total_amount'];
							$madapalli_details['session'] = $data['collection_session'];
							$madapalli_details['customer_name'] = $customer_name;
							$madapalli_details['customer_mobile'] = $mble_phonecode . $mble_number;
							$madapalli_details['status'] = 0;
							$madapalli_details['created_by'] = $request_all_data['user_id'];
							$madapalli_details['created_at'] = date('Y-m-d H:i:s');
							$madapalli_details['updated_at'] = date('Y-m-d H:i:s');
							$res_m1 = $this->db->table('madapalli_booking_details')->insert($madapalli_details);

							if ($res_m1) {
								$preparation_details = $this->db->table('madapalli_preparation_details')->where('date', $collection_date)->where('type', 1)->get()->getResultArray();
								$product_found = false;

								foreach ($preparation_details as $detail) {
									if ($detail['product_id'] == $madapalli_details['product_id'] && $detail['session'] == $madapalli_details['session']) {
										$new_quantity = $detail['quantity'] + $madapalli_details['quantity'];
										$update_data = [
											'quantity' => $new_quantity,
											'updated_at' => date('Y-m-d H:i:s')
										];
										$this->db->table('madapalli_preparation_details')->where('id', $detail['id'])->update($update_data);
										$product_found = true;
										break;
									}
								}

								if (!$product_found) {
									$insert_data = [
										'date' => $collection_date,
										'type' => 1,
										'session' => $data['collection_session'],
										'product_id' => $madapalli_details['product_id'],
										'pro_name_eng' => $prsm_set['name_eng'],
										'pro_name_tamil' => $prsm_set['name_tamil'],
										'quantity' => $madapalli_details['quantity'],
										'status' => 0,
										'created_by' => $request_all_data['user_id'],
										'created_at' => date('Y-m-d H:i:s'),
										'updated_at' => date('Y-m-d H:i:s')
									];
									$this->db->table('madapalli_preparation_details')->insert($insert_data);
								}
							}
						}
					}
					$this->db->query("UPDATE prasadam SET amount = amount + ? WHERE id = ?", [$prasadam_tot_amt, $ins_id]);
					$this->total_amount += $prasadam_tot_amt;
				}
				if ($this->db->transStatus() === FALSE) {
					$this->db->transRollback();
					$succ = false;
				} else {
					$this->db->transComplete();
					$succ = true;
				}
			} else {
				$succ = false;
			}
		} else {
			$succ = false;
		}

		return $succ;
	}

	public function save_free_prasadam($booking_id, $request_all_data)
	{
		$succ = true;

		if (!empty($request_all_data['free_prasadam'])) {
			$customer_name = $request_all_data['name'];
			$date = $request_all_data['dt'];
			$collection_date = $request_all_data['booking_date'];
			$payment_mode = $request_all_data['payment_mode'];

			$yr = date('Y');
			$mon = date('m');
			$query = $this->db->query("SELECT ref_no FROM prasadam WHERE id=(SELECT MAX(id) FROM prasadam WHERE YEAR(date)='" . $yr . "' AND MONTH(date)='" . $mon . "')")->getRowArray();
			$data['ref_no'] = 'PR' . date('y') . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
			$data['date'] = date('Y-m-d', strtotime($request_all_data['dt']));
			$data['for_ubayam'] = 1;
			$data['ubayam_id'] = $booking_id;
			$data['is_free'] = 1;
			$data['customer_name'] = $customer_name;
			$mble_phonecode = !empty($request_all_data['mobile_code']) ? $request_all_data['mobile_code'] : "";
			$mble_number = !empty($request_all_data['mobile_no']) ? $request_all_data['mobile_no'] : "";
			$data['mobile_no'] = $mble_phonecode . $mble_number;
			$data['total_amount'] = 0;
			$data['paid_amount'] = 0;
			$data['collection_date'] = $collection_date;
			$data['booking_status'] = 1;
			$data['payment_type'] = 'full';
			$data['payment_status'] = 2;
			$data['paid_through'] = !empty($request_all_data['booking_through']) ? trim($request_all_data['booking_through']) : 'DIRECT';
			$data['added_by'] = $request_all_data['user_id'];
			$data['payment_mode'] = $payment_mode;
			$data['created_at'] = date('Y-m-d H:i:s');
			$data['updated_at'] = date('Y-m-d H:i:s');
			$slots = $this->db->table("booked_slot")->where('booking_id', $booking_id)->get()->getRowArray();
			$data['collection_session'] = $slots['slot_name'];
			$this->db->transStart();

			$res = $this->db->table('prasadam')->insert($data);
			$ins_id = $this->db->insertID();

			if ($res) {
				if (!empty($request_all_data['free_prasadam'])) {
					$prasadam_details = isset($request_all_data['free_prasadam']) ? $request_all_data['free_prasadam'] : [];
					foreach ($prasadam_details as $prasadam) {
						$prsm_set = $this->db->table('prasadam_setting')->where('id', $prasadam['id'])->get()->getRowArray();
						$data_prdm_book = [
							'prasadam_booking_id' => $ins_id,
							'prasadam_id' => $prasadam['id'],
							'quantity' => $prasadam['quantity'],
							'created' => date('Y-m-d H:i:s'),
							'amount' => 0,
							'total_amount' => 0
						];
						$res_2 = $this->db->table('prasadam_booking_details')->insert($data_prdm_book);

						$settings = $this->db->table('settings')->where('type', 5)->where('setting_name', 'enable_madapalli')->get()->getRowArray();

						if ($settings['setting_value'] == 1) {
							$madapalli_details['date'] = $collection_date;
							$madapalli_details['type'] = 1;
							$madapalli_details['booking_id'] = $ins_id;
							$madapalli_details['product_id'] = $prasadam['id'];
							$madapalli_details['quantity'] = $prasadam['quantity'];
							$madapalli_details['amount'] = 0;
							$madapalli_details['session'] = $data['collection_session'];
							$madapalli_details['customer_name'] = $customer_name;
							$madapalli_details['customer_mobile'] = $mble_phonecode . $mble_number;
							$madapalli_details['status'] = 0;
							$madapalli_details['created_by'] = $request_all_data['user_id'];
							$madapalli_details['created_at'] = date('Y-m-d H:i:s');
							$madapalli_details['updated_at'] = date('Y-m-d H:i:s');
							$res_m1 = $this->db->table('madapalli_booking_details')->insert($madapalli_details);

							if ($res_m1) {
								$preparation_details = $this->db->table('madapalli_preparation_details')->where('date', $collection_date)->where('type', 1)->get()->getResultArray();
								$product_found = false;
								foreach ($preparation_details as $detail) {
									if ($detail['product_id'] == $madapalli_details['product_id'] && $detail['session'] == $madapalli_details['session']) {
										$new_quantity = $detail['quantity'] + $madapalli_details['quantity'];
										$update_data = [
											'quantity' => $new_quantity,
											'updated_at' => date('Y-m-d H:i:s')
										];
										$this->db->table('madapalli_preparation_details')->where('id', $detail['id'])->update($update_data);
										$product_found = true;
										break;
									}
								}
								if (!$product_found) {
									$insert_data = [
										'date' => $collection_date,
										'type' => 1,
										'session' => $data['collection_session'],
										'product_id' => $madapalli_details['product_id'],
										'pro_name_eng' => $prsm_set['name_eng'],
										'pro_name_tamil' => $prsm_set['name_tamil'],
										'quantity' => $madapalli_details['quantity'],
										'status' => 0,
										'created_by' => $request_all_data['user_id'],
										'created_at' => date('Y-m-d H:i:s'),
										'updated_at' => date('Y-m-d H:i:s')
									];
									$this->db->table('madapalli_preparation_details')->insert($insert_data);
								}
							}
						}
					}
				}
				if ($this->db->transStatus() === FALSE) {
					$this->db->transRollback();
					$succ = false;
				} else {
					$this->db->transComplete();
					$succ = true;
				}
			} else {
				$succ = false;
			}
		} else {
			$succ = false;
		}

		return $succ;
	}

	public function save_booking_slot($booking_id, $slots)
	{
		$succ = true;
		if (count($slots) > 0) {
			$this->db->table('booked_slot')->delete(['booking_id' => $booking_id]);
			foreach ($slots as $slot) {
				$count = $this->db->table("booking_slot_new")->where('id', $slot)->get()->getNumRows();
				if ($count > 0) {
					$booking_slot_details = $this->db->table('booking_slot_new')->where('id', $slot)->get()->getRowArray();
					$booking_slot_ins_data = array();
					$booking_slot_ins_data['booking_id'] = $booking_id;
					$booking_slot_ins_data['booking_slot_id'] = $slot;
					$booking_slot_ins_data['slot_name'] = $booking_slot_details['slot_name'];
					$res = $this->db->table("booked_slot")->insert($booking_slot_ins_data);
					if (!$res) {
						$succ = false;
						break;
					}
				} else {
					$succ = false;
					break;
				}
			}
		}
		return $succ;
	}

	public function save_booking_addon($booking_id, $addons)
	{
		$succ = true;
		if (count($addons) > 0) {
			$this->db->table('booked_addon')->delete(['booking_id' => $booking_id]);
			foreach ($addons as $addon) {
				if (!empty($addon['id'])) {
					$count = $this->db->table("temple_services")->where('id', $addon['id'])->get()->getNumRows();
					if ($count > 0) {
						$booking_addon_details = $this->db->table('temple_services')->where('id', $addon['id'])->get()->getRowArray();
						$booking_addon_ins_data = array();
						$booking_addon_ins_data['booking_id'] = $booking_id;
						$booking_addon_ins_data['service_id'] = $addon['id'];
						$booking_addon_ins_data['name'] = $booking_addon_details['name'];
						$booking_addon_ins_data['description'] = $booking_addon_details['description'];
						$booking_addon_ins_data['service_type'] = $booking_addon_details['service_type'];
						$booking_addon_ins_data['ledger_id'] = $booking_addon_details['ledger_id'];
						$booking_addon_ins_data['quantity'] = !empty($addon['quantity']) ? $addon['quantity'] : 1;
						$booking_addon_ins_data['amount'] = $addon['amount'];
						$this->total_amount += $booking_addon_ins_data['quantity'] * $addon['amount'];
						$res = $this->db->table("booked_addon")->insert($booking_addon_ins_data);
						if (!$res) {
							$succ = false;
							break;
						}
					} else {
						$succ = false;
						break;
					}
				} else {
					$succ = false;
					break;
				}
			}
		} else
			$succ = false;
		return $succ;
	}

	public function save_booking_packages($booking_id, $packages, $amount, $booking_type)
	{
		$succ = true;
		if (count($packages) > 0) {
			$this->db->table('booked_packages')->delete(['booking_id' => $booking_id]);
			foreach ($packages as $package) {
				if (!empty($package['id'])) {
					$count = $this->db->table("temple_packages")->where('id', $package['id'])->get()->getNumRows();
					$serv_count = $this->db->table("temple_package_services")->where('package_id', $package['id'])->get()->getNumRows();
					if ($count > 0 && $serv_count > 0) {
						$booking_package_details = $this->db->table('temple_packages')->where('id', $package['id'])->get()->getRowArray();
						if ($booking_type == $booking_package_details['package_type']) {
							$booking_package_ins_data = array();
							$booking_package_ins_data['booking_id'] = $booking_id;
							$booking_package_ins_data['package_id'] = $package['id'];
							$booking_package_ins_data['booking_type'] = $booking_type;
							$booking_package_ins_data['name'] = $booking_package_details['name'];
							$booking_package_ins_data['description'] = $booking_package_details['description'];
							$booking_package_ins_data['package_type'] = $booking_package_details['package_type'];
							$booking_package_ins_data['package_mode'] = $booking_package_details['package_mode'];
							$booking_package_ins_data['pack_date'] = $booking_package_details['pack_date'];
							$booking_package_ins_data['ledger_id'] = $booking_package_details['ledger_id'];
							$booking_package_ins_data['quantity'] = !empty($package['quantity']) ? $package['quantity'] : 1;
							// $booking_package_ins_data['amount'] = $booking_package_details['amount'];
							$booking_package_ins_data['amount'] = $amount;
							// $this->total_amount += $booking_package_ins_data['quantity'] * $booking_package_ins_data['amount'];
							$this->total_amount += $booking_package_ins_data['quantity'] * $amount;
							$res = $this->db->table("booked_packages")->insert($booking_package_ins_data);
							if ($res) {
								$package_id = $this->db->insertID();
								$booking_pack_service_rows = $this->db->table('temple_package_services')->where('package_id', $package['id'])->get()->getNumRows();
								if ($booking_pack_service_rows > 0) {
									$this->db->table('booked_services')->delete(['booking_id' => $booking_id]);
									$booking_pack_service_details = $this->db->table('temple_package_services')->where('package_id', $package['id'])->get()->getResultArray();
									foreach ($booking_pack_service_details as $booking_pack_service_detail) {
										$sd_count = $this->db->table('temple_services')->where('id', $booking_pack_service_detail['service_id'])->get()->getNumRows();
										if ($sd_count > 0) {
											$booking_service_details = $this->db->table('temple_services')->where('id', $booking_pack_service_detail['service_id'])->get()->getRowArray();
											$booking_service_ins_data = array();
											$booking_service_ins_data['booking_id'] = $booking_id;
											$booking_service_ins_data['booked_package_id'] = $package_id;
											$booking_service_ins_data['service_id'] = $booking_pack_service_detail['service_id'];
											$booking_service_ins_data['quantity'] = $booking_pack_service_detail['quantity'];
											$booking_service_ins_data['amount'] = $booking_pack_service_detail['amount'];
											$booking_service_ins_data['name'] = $booking_service_details['name'];
											$booking_service_ins_data['description'] = $booking_service_details['description'];
											$booking_service_ins_data['ledger_id'] = $booking_service_details['ledger_id'];
											$booking_service_ins_data['service_type'] = $booking_service_details['service_type'];
											$res_new = $this->db->table("booked_services")->insert($booking_service_ins_data);
											if (!$res_new) {
												$succ = false;
												break;
											}
										} else {
											$succ = false;
											break;
										}
									}
								} else {
									$succ = false;
									break;
								}
							} else {
								$succ = false;
								break;
							}
						} else {
							$succ = false;
							break;
						}
					} else {
						$succ = false;
						break;
					}
				} else {
					$succ = false;
					break;
				}
			}
		}
		return $succ;
	}

	public function save_deposit_payment($deposit_amt, $booking_id, $payment_type, $payments = array(), $booking_type, $booking_ref_no, $paid_through, $payment_mode = '')
	{
		$succ = true;
		$counter = $this->db->table('booked_deposit_details')->where('booking_id', $booking_id)->get()->getNumRows();
		if ($counter == 0) {
			$count = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getNumRows();
			if ($count > 0) {
				$payment_mode_details = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getRowArray();
				$booking_payment_ins_data = array(
					'booking_id' => $booking_id,
					'booking_type' => $booking_type,
					'booking_ref_no' => $booking_ref_no,
					'payment_mode_id' => $payment_mode,
					'paid_date' => date('Y-m-d'),
					'amount' => $deposit_amt,
					'deposit_status' => 1,
					'payment_mode_title' => $payment_mode_details['name'],
					'paid_through' => $paid_through,
					'pay_status' => ($paid_through == 'DIRECT' || $paid_through == 'COUNTER') ? 2 : 1,
				);

				$this->requestmodel = new RequestModel();
				$ip = $this->requestmodel->getIpAddress();
				$booking_payment_ins_data['ip'] = $ip;
				if ($ip != 'unknown') {
					$ip_details = $this->requestmodel->getLocation($ip);
					$booking_payment_ins_data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
					$booking_payment_ins_data['ip_details'] = json_encode($ip_details);
				}
				// $this->paid_amount += $booking_payment_ins_data['amount'];
				$this->total_amount += $booking_payment_ins_data['amount'];

				$res = $this->db->table("booked_deposit_details")->insert($booking_payment_ins_data);
				if (!$res) {
					$succ = false;
				}
			} else {
				$succ = false;
			}
		} else {
			$succ = false;
		}
		return $succ;
	}

	public function save_booking_payment($deposit_amt, $booking_id, $payment_type, $payments = array(), $booking_type, $booking_ref_no, $paid_through, $payment_mode = '')
	{
		$booked_pay_id = false;
		$succ = true;
		if ($payment_type == 'full') {
			if (!empty($payment_mode)) {
				$this->db->table('booked_pay_details')->delete(['booking_id' => $booking_id]);
				$count = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getNumRows();
				if ($count > 0) {
					$payment_mode_details = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getRowArray();
					$booking_payment_ins_data = array();
					$booking_payment_ins_data['booking_id'] = $booking_id;
					$booking_payment_ins_data['booking_type'] = $booking_type;
					$booking_payment_ins_data['booking_ref_no'] = $booking_ref_no;
					$booking_payment_ins_data['payment_mode_id'] = $payment_mode;
					$booking_payment_ins_data['paid_date'] = date('Y-m-d');
					$booking_payment_ins_data['amount'] = $this->total_amount;
					$booking_payment_ins_data['payment_mode_title'] = $payment_mode_details['name'];
					if ($paid_through != 'DIRECT' && $paid_through != 'COUNTER')
						$booking_payment_ins_data['payment_ref_no'] = $booking_ref_no;
					$booking_payment_ins_data['paid_through'] = $paid_through;
					$booking_payment_ins_data['pay_status'] = ($paid_through == 'DIRECT' || $paid_through == 'COUNTER') ? 2 : 1;
					$this->requestmodel = new RequestModel();
					$ip = $this->requestmodel->getIpAddress();
					$booking_payment_ins_data['ip'] = $ip;
					if ($ip != 'unknown') {
						$ip_details = $this->requestmodel->getLocation($ip);
						$booking_payment_ins_data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
						$booking_payment_ins_data['ip_details'] = json_encode($ip_details);
					}
					$this->paid_amount += $booking_payment_ins_data['amount'];

					$res = $this->db->table("booked_pay_details")->insert($booking_payment_ins_data);
					$booked_pay_id = $this->db->insertID();
					if (!$res) {
						$succ = false;
					}
				} else {
					$succ = false;
				}
			} else {
				$succ = false;
			}
		} elseif ($payment_type == 'partial') {
			if (count($payments) > 0) {
				$this->db->table('booked_pay_details')->delete(['booking_id' => $booking_id]);
				foreach ($payments as $payment) {
					if (!empty($payment['payment_mode']) && !empty($payment['amount'])) {
						$payment_amount = (float) $payment['amount'];
						if (!empty($payment_amount)) {
							$count = $this->db->table("payment_mode")->where('id', $payment['payment_mode'])->get()->getNumRows();
							if ($count > 0) {
								$payment_mode_details = $this->db->table("payment_mode")->where('id', $payment['payment_mode'])->get()->getRowArray();
								$booking_payment_ins_data = array();
								$booking_payment_ins_data['booking_id'] = $booking_id;
								$booking_payment_ins_data['booking_type'] = $booking_type;
								$booking_payment_ins_data['booking_ref_no'] = $booking_ref_no;
								$booking_payment_ins_data['payment_mode_id'] = $payment['payment_mode'];
								$booking_payment_ins_data['paid_date'] = !empty($payment['paid_date']) ? $payment['paid_date'] : date('Y-m-d');
								$booking_payment_ins_data['amount'] = $payment_amount;
								$booking_payment_ins_data['payment_mode_title'] = $payment_mode_details['name'];
								if ($paid_through != 'DIRECT' && $paid_through != 'COUNTER')
									$booking_payment_ins_data['payment_ref_no'] = $booking_ref_no;
								$booking_payment_ins_data['paid_through'] = $paid_through;
								$booking_payment_ins_data['pay_status'] = ($paid_through == 'DIRECT' || $paid_through == 'COUNTER') ? 2 : 1;
								$this->requestmodel = new RequestModel();
								$ip = $this->requestmodel->getIpAddress();
								$booking_payment_ins_data['ip'] = $ip;
								if ($ip != 'unknown') {
									$ip_details = $this->requestmodel->getLocation($ip);
									$booking_payment_ins_data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
									$booking_payment_ins_data['ip_details'] = json_encode($ip_details);
								}
								$this->paid_amount += $booking_payment_ins_data['amount'];
								// if($this->paid_amount > $this->total_amount){
								// 	$succ = false;
								// 	break;
								// }
								$res = $this->db->table("booked_pay_details")->insert($booking_payment_ins_data);
								$booked_pay_id = $this->db->insertID();
								if (!$res) {
									$succ = false;
									break;
								}
							} else {
								$succ = false;
								break;
							}
						} else {
							$succ = false;
							break;
						}
					} else {
						$succ = false;
						break;
					}
				}
			} else {
				$succ = false;
			}
		}

		if ($succ) {
			return $booked_pay_id; // Successfully return the booked payment ID
		} else {
			return false; // Return false if failure
		}
	}

	public function account_migration($booking_id)
	{
		$succ = true;
		$templeubayam = $this->db->table("templebooking")->where("id", $booking_id)->get()->getRowArray();
		$booking_settings = $this->db->table('booking_setting')->get()->getResultArray();
		$setting = array();
		if (count($booking_settings) > 0) {
			foreach ($booking_settings as $bs) {
				$setting[$bs['meta_key']] = $bs['meta_value'];
			}
		}
		$entry_date = date('Y-m-d', strtotime($templeubayam['entry_date']));
		$date = explode('-', $entry_date);
		$yr = $date[0];
		$mon = $date[1];
		$td_ledger = $this->db->table('ledgers')->where('name', 'TRADE RECEIVABLE')->where('group_id', 3)->where('left_code', '1200')->get()->getRowArray();
		if (!empty($td_ledger)) {
			$cr_id1 = $td_ledger['id'];
		} else {
			$cled1['group_id'] = 3;
			$cled1['name'] = 'TRADE RECEIVABLE';
			$cled1['code'] = '1200/005';
			$cled1['op_balance'] = '0';
			$cled1['op_balance_dc'] = 'D';
			$cled1['left_code'] = '1200';
			$cled1['right_code'] = '005';
			$this->db->table('ledgers')->insert($cled1);
			$cr_id1 = $this->db->insertID();
		}
		$tp_ledger = $this->db->table('ledgers')->where('name', 'TRADE PAYABLE')->where('group_id', 14)->where('left_code', '2100')->get()->getRowArray();
		if (!empty($tp_ledger)) {
			$cr_id2 = $tp_ledger['id'];
		} else {
			$cled1['group_id'] = 14;
			$cled1['name'] = 'TRADE PAYABLE';
			$cled1['code'] = '2100/2102';
			$cled1['op_balance'] = '0';
			$cled1['op_balance_dc'] = 'D';
			$cled1['left_code'] = '2100';
			$cled1['right_code'] = '2102';
			$this->db->table('ledgers')->insert($cled1);
			$cr_id2 = $this->db->insertID();
		}
		$incomes_group = $this->db->table('groups')->where('code', '8000')->get()->getRowArray();
		if (!empty($incomes_group)) {
			$sls_id = $incomes_group['id'];
		} else {
			$sls1['parent_id'] = 0;
			$sls1['name'] = 'Incomes';
			$sls1['code'] = '8000';
			$sls1['added_by'] = $this->session->get('log_id');
			$led_ins1 = $this->db->table('groups')->insert($sls1);
			$sls_id = $this->db->insertID();
		}
		$booked_packages_cnt = $this->db->table("booked_packages")->where("booking_id", $booking_id)->get()->getNumRows();
		$booked_addon_cnt = $this->db->table("booked_addon")->where("booking_id", $booking_id)->get()->getNumRows();
		$booked_deposit_cnt = $this->db->table("booked_deposit_details")->where("booking_id", $booking_id)->get()->getNumRows();
		$booked_prasadam_cnt = $this->db->table("prasadam")->where('is_free', 0)->where("ubayam_id", $booking_id)->get()->getNumRows();
		$booked_extra_cnt = $this->db->table("booked_extra_charges")->where("booking_id", $booking_id)->get()->getNumRows();

		if ($booked_packages_cnt > 0) {
			$booked_packages_details = $this->db->table("booked_packages")->where("booking_id", $booking_id)->get()->getResultArray();
			$booked_addon_details = $this->db->table("booked_addon")->join('temple_services', 'temple_services.id = booked_addon.service_id')->select('temple_services.*, booked_addon.quantity')->where("booked_addon.booking_id", $booking_id)->get()->getResultArray();
			$booked_prasadam_details = $this->db->table("prasadam")->where('is_free', 0)->where("ubayam_id", $booking_id)->get()->getResultArray();
			$booked_extra_details = $this->db->table("booked_extra_charges")->where("booking_id", $booking_id)->get()->getResultArray();
			$over_all_tot_amt = 0;

			foreach ($booked_packages_details as $row)
				$over_all_tot_amt += (float) $row['amount'];
			if ($booked_addon_cnt > 0) {
				foreach ($booked_addon_details as $row)
					$over_all_tot_amt += (float) $row['amount'] * (int) $row['quantity'];
			}
			if ($booked_prasadam_cnt > 0) {
				foreach ($booked_prasadam_details as $row)
					$over_all_tot_amt += (float) $row['amount'];
			}
			if ($booked_extra_cnt > 0) {
				foreach ($booked_extra_details as $row)
					$over_all_tot_amt += (float) $row['amount'];
			}
			$debtor_amount = 0;

			$number1 = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
			if (empty($number1))
				$num1 = 1;
			else
				$num1 = $number1['number'] + 1;
			// Get Entry Code
			$qry1 = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();

			$entries1['entry_code'] = 'JOR' . date('y', strtotime($entry_date)) . $mon . (sprintf("%05d", (((float) substr($qry1['entry_code'], -5)) + 1)));
			$entries1['entrytype_id'] = '4';
			$entries1['number'] = $num1;
			$entries1['date'] = $entry_date;
			$entries1['dr_total'] = $over_all_tot_amt;
			$entries1['cr_total'] = $over_all_tot_amt;
			if ($templeubayam['booking_type'] == 2)
				$narration = 'Ubayam Amout';
			elseif ($templeubayam['booking_type'] == 1)
				$narration = 'Hall Booking Amount';
			else
				$narration = 'Sannathi AMount';
			$entries1['narration'] = $narration . '(' . $templeubayam['ref_no'] . ')' . "\n" . 'name:' . $templeubayam['name'] . "\n" . 'NRIC:' . "\n" . 'email:' . $templeubayam['email'] . "\n";
			$entries1['inv_id'] = $booking_id;
			if ($templeubayam['booking_type'] == 2)
				$entries1['type'] = 1;
			else
				$entries1['type'] = 8;
			//Insert Entries
			$ent = $this->db->table('entries')->insert($entries1);
			$en_id1 = $this->db->insertID();
			if (!empty($en_id1)) {
				foreach ($booked_packages_details as $row) {
					if (!empty($row['ledger_id'])) {
						$led_book_id = $row['ledger_id'];
					} else {
						$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
						if (!empty($ledger1)) {
							$led_book_id = $ledger1['id'];
						} else {
							$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '8913')->orderBy('right_code', 'desc')->get()->getRowArray();
							$set_right_code = (int) $right_code['right_code'] + 1;
							$set_right_code = sprintf("%04d", $set_right_code);
							$led1['group_id'] = $sls_id;
							$led1['name'] = 'All Incomes';
							$led1['left_code'] = '8913';
							$led1['right_code'] = $set_right_code;
							$led1['op_balance'] = '0';
							$led1['op_balance_dc'] = 'D';
							$led_ins1 = $this->db->table('ledgers')->insert($led1);
							$led_book_id = $this->db->insertID();
						}
					}
					// Hall Booking => Credit
					$eitems_hall_book['entry_id'] = $en_id1;
					$eitems_hall_book['ledger_id'] = $led_book_id;
					$eitems_hall_book['amount'] = $row['amount'];
					$eitems_hall_book['dc'] = 'C';
					$eitems_hall_book['details'] = 'Amount for ' . $row['name'];
					$this->db->table('entryitems')->insert($eitems_hall_book);
					//  Trade Debtors => Debit 
					$debtor_amount += $row['amount'];
				}
			} else {
				$succ = false;
				return $succ;
			}
		}

		if ($booked_addon_cnt > 0) {
			$booked_addon_details = $this->db->table("booked_addon")->join('temple_services', 'temple_services.id = booked_addon.service_id')->select('temple_services.*, booked_addon.quantity, booked_addon.amount as new_amount')->where("booked_addon.booking_id", $booking_id)->get()->getResultArray();
			foreach ($booked_addon_details as $row) {
				if (!empty($row['ledger_id'])) {
					$led_book_id = $row['ledger_id'];
				} else {
					$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
					if (!empty($ledger1)) {
						$led_book_id = $ledger1['id'];
					} else {
						$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '8913')->orderBy('right_code', 'desc')->get()->getRowArray();
						$set_right_code = (int) $right_code['right_code'] + 1;
						$set_right_code = sprintf("%04d", $set_right_code);
						$led1['group_id'] = $sls_id;
						$led1['name'] = 'All Incomes';
						$led1['left_code'] = '8913';
						$led1['right_code'] = $set_right_code;
						$led1['op_balance'] = '0';
						$led1['op_balance_dc'] = 'D';
						$led_ins1 = $this->db->table('ledgers')->insert($led1);
						$led_book_id = $this->db->insertID();
					}
				}
				$amount = (float) $row['new_amount'] * $row['quantity'];
				// Hall Booking => Credit
				$eitems_hall_book['entry_id'] = $en_id1;
				$eitems_hall_book['ledger_id'] = $led_book_id;
				$eitems_hall_book['amount'] = $amount;
				$eitems_hall_book['dc'] = 'C';
				$eitems_hall_book['details'] = 'Amount for ' . $row['name'];
				$this->db->table('entryitems')->insert($eitems_hall_book);
				//  Trade Debtors => Debit 
				$debtor_amount += $amount;
			}
		}
		if ($booked_prasadam_cnt > 0) {
			$booked_prasadam_details = $this->db->table("prasadam p")->select('pbd.*, ps.ledger_id, ps.name_eng')->join('prasadam_booking_details pbd', 'pbd.prasadam_booking_id = p.id')->join('prasadam_setting ps', 'pbd.prasadam_id = ps.id')->where('is_free', 0)->where("ubayam_id", $booking_id)->get()->getResultArray();

			foreach ($booked_prasadam_details as $row) {
				if (!empty($row['ledger_id'])) {
					$led_book_id = $row['ledger_id'];
				} else {
					$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
					if (!empty($ledger1)) {
						$led_book_id = $ledger1['id'];
					} else {
						$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '8913')->orderBy('right_code', 'desc')->get()->getRowArray();
						$set_right_code = (int) $right_code['right_code'] + 1;
						$set_right_code = sprintf("%04d", $set_right_code);
						$led1['group_id'] = $sls_id;
						$led1['name'] = 'All Incomes';
						$led1['left_code'] = '8913';
						$led1['right_code'] = $set_right_code;
						$led1['op_balance'] = '0';
						$led1['op_balance_dc'] = 'D';
						$led_ins1 = $this->db->table('ledgers')->insert($led1);
						$led_book_id = $this->db->insertID();
					}
				}
				$amount = (float) $row['amount'] * $row['quantity'];
				// Hall Booking => Credit
				$eitems_hall_book['entry_id'] = $en_id1;
				$eitems_hall_book['ledger_id'] = $led_book_id;
				$eitems_hall_book['amount'] = $amount;
				$eitems_hall_book['dc'] = 'C';
				$eitems_hall_book['details'] = 'Amount for ' . $row['name_eng'];
				$this->db->table('entryitems')->insert($eitems_hall_book);
				//  Trade Debtors => Debit 
				$debtor_amount += $amount;
			}
		}
		if ($booked_extra_cnt > 0) {
			$booked_extra_details = $this->db->table("booked_extra_charges")->where("booking_id", $booking_id)->get()->getResultArray();

			foreach ($booked_extra_details as $row) {
				if (!empty($row['ledger_id'])) {
					$led_book_id = $row['ledger_id'];
				} else {
					$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
					if (!empty($ledger1)) {
						$led_book_id = $ledger1['id'];
					} else {
						$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '8913')->orderBy('right_code', 'desc')->get()->getRowArray();
						$set_right_code = (int) $right_code['right_code'] + 1;
						$set_right_code = sprintf("%04d", $set_right_code);
						$led1['group_id'] = $sls_id;
						$led1['name'] = 'All Incomes';
						$led1['left_code'] = '8913';
						$led1['right_code'] = $set_right_code;
						$led1['op_balance'] = '0';
						$led1['op_balance_dc'] = 'D';
						$led_ins1 = $this->db->table('ledgers')->insert($led1);
						$led_book_id = $this->db->insertID();
					}
				}
				$amount = (float) $row['amount'];
				// Hall Booking => Credit
				$eitems_hall_book['entry_id'] = $en_id1;
				$eitems_hall_book['ledger_id'] = $led_book_id;
				$eitems_hall_book['amount'] = $amount;
				$eitems_hall_book['dc'] = 'C';
				$eitems_hall_book['details'] = 'Amount for Extra Charges';
				$this->db->table('entryitems')->insert($eitems_hall_book);
				//  Trade Debtors => Debit 
				$debtor_amount += $amount;
			}
		}
		//  Trade Debtors => Debit 
		$eitems_cash_led = array();
		$eitems_cash_led['entry_id'] = $en_id1;
		$eitems_cash_led['ledger_id'] = $cr_id1;
		$eitems_cash_led['amount'] = $debtor_amount;
		$eitems_cash_led['dc'] = 'D';
		$eitems_cash_led['details'] = 'Amount for ' . $booking_type_name . '(' . $templeubayam['ref_no'] . ')';
		$this->db->table('entryitems')->insert($eitems_cash_led);

		$discount_amount = !empty($templeubayam['discount_amount']) ? (float) $templeubayam['discount_amount'] : 0;
		if (!empty($discount_amount)) {
			$number1 = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
			if (empty($number1))
				$num1 = 1;
			else
				$num1 = $number1['number'] + 1;
			$qry1 = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();

			$entries1['entry_code'] = 'JOR' . date('y', strtotime($entry_date)) . $mon . (sprintf("%05d", (((float) substr($qry1['entry_code'], -5)) + 1)));
			$entries1['entrytype_id'] = '4';
			$entries1['number'] = $num1;
			$entries1['date'] = $entry_date;
			$entries1['dr_total'] = $discount_amount;
			$entries1['cr_total'] = $discount_amount;
			if ($templeubayam['booking_type'] == 2)
				$narration = 'Ubayam Amout';
			elseif ($templeubayam['booking_type'] == 1)
				$narration = 'Hall Booking Amount';
			else
				$narration = 'Sannathi AMount';
			$entries1['narration'] = $narration . '(' . $templeubayam['ref_no'] . ')' . "\n" . 'name:' . $templeubayam['name'] . "\n" . 'NRIC:' . "\n" . 'email:' . $templeubayam['email'] . "\n";
			$entries1['inv_id'] = $booking_id;
			if ($templeubayam['booking_type'] == 2)
				$entries1['type'] = 1;
			else
				$entries1['type'] = 8;
			//Insert Entries
			$ent = $this->db->table('entries')->insert($entries1);
			$en_id2 = $this->db->insertID();
			if (!empty($en_id2)) {

				if ($templeubayam['booking_type'] == 1) {
					$discount_ledger_id = !empty($setting['discount_hall_ledger_id']) ? $setting['discount_hall_ledger_id'] : 1097;
					$booking_type_name = 'Hall';
					$entry_type = 8;
				} elseif ($templeubayam['booking_type'] == 2) {
					$discount_ledger_id = !empty($setting['discount_ubayam_ledger_id']) ? $setting['discount_ubayam_ledger_id'] : 1097;
					$booking_type_name = 'Ubayam';
					$entry_type = 1;
				} else {
					$discount_ledger_id = !empty($setting['discount_ubayam_ledger_id']) ? $setting['discount_ubayam_ledger_id'] : 1097;
					$booking_type_name = 'Sannathi';
					$entry_type = 13;
				}

				// Hall Booking => Credit
				$eitems_hall_book['entry_id'] = $en_id2;
				$eitems_hall_book['ledger_id'] = $cr_id1;
				$eitems_hall_book['amount'] = $discount_amount;
				$eitems_hall_book['dc'] = 'C';
				$eitems_hall_book['details'] = 'Discount for ' . $booking_type_name . '(' . $templeubayam['ref_no'] . ')';
				$this->db->table('entryitems')->insert($eitems_hall_book);

				//  Trade Debtors => Debit 
				$eitems_disc_ent = array();
				$eitems_disc_ent['entry_id'] = $en_id2;
				$eitems_disc_ent['ledger_id'] = $discount_ledger_id;
				$eitems_disc_ent['amount'] = $discount_amount;
				$eitems_disc_ent['is_discount'] = 1;
				$eitems_disc_ent['dc'] = 'D';
				$eitems_disc_ent['details'] = 'Discount for ' . $booking_type_name . '(' . $templeubayam['ref_no'] . ')';
				$this->db->table('entryitems')->insert($eitems_disc_ent);
				$debtor_amount -= $discount_amount;
			} else {
				$succ = false;
				return $succ;
			}
		}

		if ($booked_deposit_cnt > 0) {
			$booked_deposit_details = $this->db->table("booked_deposit_details")->where("booking_id", $booking_id)->get()->getResultArray();

			foreach ($booked_deposit_details as $row) {
				$paymentmode = $this->db->table('payment_mode')->where('id', $row['payment_mode_id'])->get()->getRowArray();
				if (!empty($paymentmode['ledger_id'])) {
					$number1 = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
					if (empty($number1))
						$num1 = 1;
					else
						$num1 = $number1['number'] + 1;
					$qry1 = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();

					$entries['entry_code'] = 'JOR' . date('y', strtotime($entry_date)) . $mon . (sprintf("%05d", (((float) substr($qry1['entry_code'], -5)) + 1)));
					$entries['entrytype_id'] = '4';
					$entries['number'] = $num;
					$entries['date'] = $row['paid_date'];
					$entries['dr_total'] = $row['amount'];
					$entries['cr_total'] = $row['amount'];
					if ($templeubayam['booking_type'] == 2)
						$narration = 'Ubayam deposit';
					elseif ($templeubayam['booking_type'] == 1)
						$narration = 'Hall Booking deposit';
					else
						$narration = 'Sannathi deposit';
					$entries['narration'] = $narration . '(' . $templeubayam['ref_no'] . ')' . "\n" . 'name:' . $templeubayam['name'] . "\n" . 'email:' . $templeubayam['email'] . "\n";
					$entries['inv_id'] = $booking_id;
					$entries['type'] = 8;
					//Insert Entries
					$ent = $this->db->table('entries')->insert($entries);
					$en_id = $this->db->insertID();
					if (!empty($en_id)) {
						// PETTY CASH => Debit 
						$eitems_cash_led['entry_id'] = $en_id;
						$eitems_cash_led['ledger_id'] = $cr_id2;
						$eitems_cash_led['amount'] = $row['amount'];
						$eitems_cash_led['dc'] = 'C';
						// $eitems_cash_led['details'] = 'Hall Booking Amount';
						if ($templeubayam['booking_type'] == 2) {
							$eitems_cash_led['details'] = 'Ubayam deposit';
						} elseif ($templeubayam['booking_type'] == 1) {
							$eitems_cash_led['details'] = 'Hall Booking deposit';
						} else {
							$eitems_cash_led['details'] = 'Sannathi desposit';
						}
						$this->db->table('entryitems')->insert($eitems_cash_led);

						// Trade Debtors => Credit
						$eitems_hall_book['entry_id'] = $en_id;
						$eitems_hall_book['ledger_id'] = $cr_id1;
						$eitems_hall_book['amount'] = $row['amount'];
						$eitems_hall_book['dc'] = 'D';
						if ($templeubayam['booking_type'] == 2) {
							$eitems_hall_book['details'] = 'Ubayam deposit';
						} elseif ($templeubayam['booking_type'] == 1) {
							$eitems_hall_book['details'] = 'Hall Booking deposit';
						} else {
							$eitems_hall_book['details'] = 'Sannathi deposit';
						}
						$this->db->table('entryitems')->insert($eitems_hall_book);
					}
				} else {
					$succ = false;
					return $succ;
				}
			}
		}


		if ($templeubayam['payment_type'] == 'full' || $templeubayam['payment_type'] == 'partial') {
			$booked_pay_details_cnt = $this->db->table("booked_pay_details")->where("booking_id", $booking_id)->get()->getNumRows();
			// $booked_pay_details_cnt = $this->db->table("booked_pay_details")->where("booking_id", $booking_id)->where("booking_type", 2)->get()->getNumRows();	
			if ($booked_pay_details_cnt > 0) {
				$booked_pay_details = $this->db->table("booked_pay_details")->where("booking_id", $booking_id)->get()->getResultArray();
				// $booked_pay_details = $this->db->table("booked_pay_details")->where("booking_id", $booking_id)->where("booking_type", 2)->get()->getResultArray();
				foreach ($booked_pay_details as $row) {
					$paymentmode = $this->db->table('payment_mode')->where('id', $row['payment_mode_id'])->get()->getRowArray();
					if (!empty($paymentmode['ledger_id'])) {
						$number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
						if (empty($number))
							$num = 1;
						else
							$num = $number['number'] + 1;
						// Get Entry Code
						$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();

						$entries['entry_code'] = 'REC' . date('y', strtotime($row['paid_date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
						$entries['entrytype_id'] = '1';
						$entries['number'] = $num;
						$entries['date'] = $row['paid_date'];
						$entries['dr_total'] = $row['amount'];
						$entries['cr_total'] = $row['amount'];
						$entries['narration'] = 'Hall Booking(' . $templeubayam['ref_no'] . ')' . "\n" . 'name:' . $templeubayam['name'] . "\n" . 'NRIC:' . $templeubayam['ic_number'] . "\n" . 'email:' . $templeubayam['email'] . "\n";
						$entries['inv_id'] = $booking_id;
						if ($templeubayam['booking_type'] == 1)
							$entries['type'] = 8;
						else
							$entries['type'] = 1;
						//Insert Entries
						$ent = $this->db->table('entries')->insert($entries);
						$en_id = $this->db->insertID();
						if (!empty($en_id)) {
							// Trade Debtors => Credit
							$eitems_hall_book['entry_id'] = $en_id;
							$eitems_hall_book['ledger_id'] = $cr_id1;
							$eitems_hall_book['amount'] = $row['amount'];
							$eitems_hall_book['dc'] = 'C';
							if ($templeubayam['booking_type'] == 2) {
								$eitems_hall_book['details'] = 'Ubayam Amount';
							} elseif ($templeubayam['booking_type'] == 1) {
								$eitems_hall_book['details'] = 'Hall Booking Amount';
							} else {
								$eitems_hall_book['details'] = 'Sannathi Amount';
							}
							$this->db->table('entryitems')->insert($eitems_hall_book);
							// PETTY CASH => Debit 
							$eitems_cash_led['entry_id'] = $en_id;
							$eitems_cash_led['ledger_id'] = $paymentmode['ledger_id'];
							$eitems_cash_led['amount'] = $row['amount'];
							$eitems_cash_led['dc'] = 'D';
							// $eitems_cash_led['details'] = 'Hall Booking Amount';
							if ($templeubayam['booking_type'] == 2) {
								$eitems_cash_led['details'] = 'Ubayam Amount';
							} elseif ($templeubayam['booking_type'] == 1) {
								$eitems_cash_led['details'] = 'Hall Booking Amount';
							} else {
								$eitems_cash_led['details'] = 'Sannathi Amount';
							}
							$this->db->table('entryitems')->insert($eitems_cash_led);
						}
					} else {
						$succ = false;
						return $succ;
					}
				}
			} else {
				$succ = false;
				return $succ;
			}
		}
		return $succ;
	}

	public function get_packages_list()
	{
		$resp = array('success' => true, 'data' => array('status' => true));
		try {
			if (!empty($_REQUEST['booking_date']))
				$request_all_data = $_REQUEST;
			else
				$request_all_data = json_decode(file_get_contents('php://input'), true);
			if ($request_all_data['booking_date'] && $request_all_data['slot_id'] && $request_all_data['package_type']) {
				$booking_date = trim($request_all_data['booking_date']);
				$slot_id = trim($request_all_data['slot_id']);
				$package_type = trim($request_all_data['package_type']);
				$query = $this->db->query("
							SELECT COUNT(*) as count FROM (
								SELECT bp.package_id 
								FROM templebooking tb
								LEFT JOIN booked_packages bp ON bp.booking_id = tb.id
								LEFT JOIN temple_packages tp ON tp.id = bp.package_id
								LEFT JOIN booked_slot bs ON bs.booking_id = tb.id
								WHERE tb.booking_date = '$booking_date'
								AND bs.booking_slot_id = $slot_id
								AND tp.package_type = $package_type
								AND tb.booking_status NOT IN (3)
								GROUP BY bp.package_id
							) AS subquery
						");

				$result = $query->getRowArray();
				$pack_rows = isset($result->count) ? $result->count : 0;

				// echo $pack_rows;
				// exit;
				if ($pack_rows > 1) {
					$resp['data']['packages'] = array();
					$resp['data']['addons'] = array();
				} elseif ($pack_rows == 1) {
					$packages_result = $this->db->query("
										SELECT 
											MAX(bp.package_id) AS package_id, 
											MAX(tp.package_type) AS package_type, 
											MAX(tp.package_mode) AS package_mode
										FROM 
											templebooking tb
										LEFT JOIN 
											booked_packages bp ON bp.booking_id = tb.id
										LEFT JOIN 
											temple_packages tp ON tp.id = bp.package_id
										LEFT JOIN 
											booked_slot bs ON bs.booking_id = tb.id
										WHERE 
											tb.booking_date = '$booking_date'
											AND bs.booking_slot_id = $slot_id
											AND tp.package_type = $package_type
											AND tb.booking_status NOT IN (3)
										GROUP BY 
											bp.package_id
									")->getRowArray();

					// 	echo $packages_result;
					// exit;

					if ($packages_result['package_mode'] == '1') {
						// Check for existing bookings for the same package on the same slot and date
						$booking_check_query = "
							SELECT COUNT(*) AS count 
							FROM booked_packages bp
							JOIN booked_slot bs ON bp.booking_id = bs.booking_id
							JOIN templebooking tb ON tb.id = bp.booking_id
							WHERE bp.package_id = ?
							AND bs.booking_slot_id = ?
							AND tb.booking_date = ?
							AND tb.booking_status NOT IN (3)
						";
						$result = $this->db->query($booking_check_query, [
							$packages_result['package_id'],
							$slot_id,
							$booking_date
						])->getRow();
						$act_pack_cnt = $result->count;
					} elseif ($packages_result['package_mode'] == '2') {
						$sql = "
							SELECT COUNT(*) as count
							FROM temple_packages
							WHERE id = ?
							AND package_type = ?
							AND status = 1
						";
						$result = $this->db->query($sql, [$packages_result['package_id'], $package_type])->getRow();
						$act_pack_cnt = $result->count;
					} else {
						$act_pack_cnt = 0;
					}

					if ($act_pack_cnt > 0) {
						$sql = "
							SELECT * 
							FROM temple_packages
							WHERE id = (
								SELECT package_id
								FROM temple_package_date
								WHERE package_type = ? 
								AND pack_date = ?
								LIMIT 1
							)
							AND package_type = ?
							AND status = 1
						";
						$packages = $this->db->query($sql, [$package_type, $booking_date, $package_type])->getResultArray();
						$resp['data']['packages'] = $packages;
						$resp['data']['addons'] = $this->db->query("SELECT * FROM temple_services WHERE id IN (SELECT service_id FROM temple_package_addons WHERE package_id = ?)", [$packages_result['package_id']])->getResultArray();
					} else {
						$resp['data']['packages'] = array();
						$resp['data']['addons'] = array();
					}
				} else {


					$spl_pack_query = $this->db->query("
												SELECT *
												FROM temple_packages
												WHERE package_type = ?
												AND status = 1
												AND id IN (
													SELECT package_id
													FROM temple_package_date
													WHERE package_type = ?
													AND pack_date = ?
												)
												AND id IN (
													SELECT package_id
													FROM temple_package_slots
													WHERE slot_id = ?
												)
											", [$package_type, $package_type, $booking_date, $slot_id])->getResultArray();

					$spl_pack_cnt = count($spl_pack_query);
					// 			echo $spl_pack_cnt;
					// exit;
					if ($spl_pack_cnt > 0) {
						$resp['data']['packages'] = $this->db->query("
													SELECT *
													FROM temple_packages
													WHERE package_type = ?
													AND status = 1
													AND id IN (
														SELECT package_id
														FROM temple_package_date
														WHERE package_type = ?
														AND pack_date = ?
													)
													AND id IN (
														SELECT package_id
														FROM temple_package_slots
														WHERE slot_id = ?
													)
												", [$package_type, $package_type, $booking_date, $slot_id])->getResultArray();
						$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id in(select id from temple_packages where pack_date = '$booking_date' and package_type = '$package_type' and status = 1))")->getResultArray();
					} else {
						$tot_pack_cnt = $this->db->query("
										SELECT COUNT(*) as count
										FROM temple_packages
										WHERE package_type = ?
										AND id NOT IN (
											SELECT DISTINCT package_id
											FROM temple_package_date
											WHERE package_type = ?
											AND pack_date IS NOT NULL
											AND pack_date != '0000-00-00'
										)
										AND id IN (
											SELECT package_id
											FROM temple_package_slots
											WHERE slot_id = ?
										)
									", [$package_type, $package_type, $slot_id])->getRow()->count;
						// echo $tot_pack_cnt;
						// 						exit;
						if ($tot_pack_cnt > 0) {
							// echo $tot_pack_cnt;
							// exit;
							$resp['data']['packages'] = $this->db->query("
						SELECT *
						FROM temple_packages
						WHERE package_type = ?
						AND id NOT IN (
							SELECT DISTINCT package_id
							FROM temple_package_date
							WHERE package_type = ?
							AND pack_date IS NOT NULL
							AND pack_date != '0000-00-00'
						)
						AND id IN (
							SELECT package_id
							FROM temple_package_slots
							WHERE slot_id = ?
						)
					", [$package_type, $package_type, $slot_id])->getResultArray();
							$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id in (select id from temple_packages where (pack_date IS NULL OR pack_date = '') and package_type = '$package_type' and status = 1))")->getResultArray();
						} else {
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					}
				}
			} else
				$resp['data']['packages'] = array();
		} catch (Exception $e) {
			$resp['success'] = false;
			$resp['data']['status'] = false;
			$resp['data']['message'] = $e->getMessage();
			// $resp['data']['message_type'] = 'error';
			/* log_message('error', $e->getMessage());
			throw $e; */
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($resp);
		exit;
	}

	public function get_packages_list_current()
	{
		$resp = array('success' => true, 'data' => array('status' => true));
		try {
			if (!empty($_REQUEST['booking_date']))
				$request_all_data = $_REQUEST;
			else
				$request_all_data = json_decode(file_get_contents('php://input'), true);
			if ($request_all_data['booking_date'] && $request_all_data['slot_id'] && $request_all_data['package_type']) {
				$booking_date = trim($request_all_data['booking_date']);
				$slot_id = trim($request_all_data['slot_id']);
				$deity_id = trim($request_all_data['deity_id']);
				$package_type = trim($request_all_data['package_type']);
				$query = $this->db->query("
							SELECT COUNT(*) as count FROM (
								SELECT bp.package_id 
								FROM templebooking tb
								LEFT JOIN booked_packages bp ON bp.booking_id = tb.id
								LEFT JOIN temple_packages tp ON tp.id = bp.package_id
								LEFT JOIN booked_slot bs ON bs.booking_id = tb.id
								WHERE tb.booking_date = '$booking_date'
								AND bs.booking_slot_id = $slot_id
								AND tp.package_type = $package_type
								AND tp.deity_id = $deity_id
								AND tb.booking_status NOT IN (3)
								GROUP BY bp.package_id
							) AS subquery
						");

				$result = $query->getRowArray();
				$pack_rows = isset($result->count) ? $result->count : 0;

				// echo $pack_rows;
				// exit;
				if ($pack_rows > 1) {
					$resp['data']['packages'] = array();
					$resp['data']['addons'] = array();
				} elseif ($pack_rows == 1) {
					$packages_result = $this->db->query("
										SELECT 
											MAX(bp.package_id) AS package_id, 
											MAX(tp.package_type) AS package_type, 
											MAX(tp.package_mode) AS package_mode
										FROM 
											templebooking tb
										LEFT JOIN 
											booked_packages bp ON bp.booking_id = tb.id
										LEFT JOIN 
											temple_packages tp ON tp.id = bp.package_id
										LEFT JOIN 
											booked_slot bs ON bs.booking_id = tb.id
										WHERE 
											tb.booking_date = '$booking_date'
											AND bs.booking_slot_id = $slot_id
											AND tp.package_type = $package_type
											AND tp.deity_id = $deity_id
											AND tb.booking_status NOT IN (3)
										GROUP BY 
											bp.package_id
									")->getRowArray();

					// 	echo $packages_result;
					// exit;

					if ($packages_result['package_mode'] == 2) {
						$sql = "
						SELECT COUNT(*) as count
						FROM temple_packages
						WHERE id = ? 
						AND package_type = ?
						AND status = 1
						AND deity_id = ?
					";

						$query = $this->db->query($sql, [$packages_result['package_id'], $package_type, $deity_id]);
						$result = $query->getRow();

						$act_pack_cnt = $result->count;
						if ($act_pack_cnt > 0) {
							$sql = "
									SELECT * 
									FROM temple_packages
									WHERE id = (
										SELECT package_id
										FROM temple_package_date
										WHERE package_type = ? 
										AND pack_date = ?
										LIMIT 1
									)
									AND package_type = ?
									AND status = 1
									AND deity_id = ?
								";

							$packages = $this->db->query($sql, [$package_type, $booking_date, $package_type, $deity_id])
								->getResultArray();

							$resp['data']['packages'] = $packages;
							$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id = '" . $packages_result['package_id'] . "')")->getResultArray();
						} else {
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					} else {
						// echo "packages_result";
						// exit;

						$resp['data']['packages'] = array();
						$resp['data']['addons'] = array();
					}
				} else {


					$spl_pack_query = $this->db->query("
												SELECT *
												FROM temple_packages
												WHERE package_type = ?
												AND status = 1
												AND id IN (
													SELECT package_id
													FROM temple_package_date
													WHERE package_type = ?
													AND pack_date = ?
												)
												AND id IN (
													SELECT package_id
													FROM temple_package_slots
													WHERE slot_id = ?
													AND deity_id = ?
												)
											", [$package_type, $package_type, $booking_date, $slot_id, $deity_id])->getResultArray();

					$spl_pack_cnt = count($spl_pack_query);
					// echo $spl_pack_cnt;
					// exit;
					if ($spl_pack_cnt > 0) {
						$resp['data']['packages'] = $this->db->query("
													SELECT *
													FROM temple_packages
													WHERE package_type = ?
													AND status = 1
													AND id IN (
														SELECT package_id
														FROM temple_package_date
														WHERE package_type = ?
														AND pack_date = ?
													)
													AND id IN (
														SELECT package_id
														FROM temple_package_slots
														WHERE slot_id = ?
														AND deity_id = ?
													)
												", [$package_type, $package_type, $booking_date, $slot_id, $deity_id])->getResultArray();
						$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id in(select id from temple_packages where pack_date = '$booking_date' and package_type = '$package_type' and status = 1))")->getResultArray();
					} else {
						$tot_pack_cnt = $this->db->query("
										SELECT COUNT(*) as count
										FROM temple_packages
										WHERE package_type = ?
										AND id NOT IN (
											SELECT DISTINCT package_id
											FROM temple_package_date
											WHERE package_type = ?
											AND pack_date IS NOT NULL
											AND pack_date != '0000-00-00'
										)
										AND id IN (
											SELECT package_id
											FROM temple_package_slots
											WHERE slot_id = ?
											AND deity_id = ?
										)
									", [$package_type, $package_type, $slot_id, $deity_id])->getRow()->count;
						// echo $tot_pack_cnt;
						// exit;
						if ($tot_pack_cnt > 0) {
							// echo $tot_pack_cnt;
							// exit;
							$resp['data']['packages'] = $this->db->query("
						SELECT *
						FROM temple_packages
						WHERE package_type = ?
						AND id NOT IN (
							SELECT DISTINCT package_id
							FROM temple_package_date
							WHERE package_type = ?
							AND deity_id = ?
							AND pack_date IS NOT NULL
							AND pack_date != '0000-00-00'
						)
						AND id IN (
							SELECT package_id
							FROM temple_package_slots
							WHERE slot_id = ?
							AND deity_id = ?
						)
					", [$package_type, $package_type, $deity_id, $slot_id, $deity_id])->getResultArray();
							$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id in (select id from temple_packages where (pack_date IS NULL OR pack_date = '') and package_type = '$package_type' and status = 1))")->getResultArray();
						} else {
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					}
				}
			} else
				$resp['data']['packages'] = array();
		} catch (Exception $e) {
			$resp['success'] = false;
			$resp['data']['status'] = false;
			$resp['data']['message'] = $e->getMessage();
			// $resp['data']['message_type'] = 'error';
			/* log_message('error', $e->getMessage());
							  throw $e; */
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($resp);
		exit;
	}

	public function get_packages_list_oldold()
	{
		$resp = array('success' => true, 'data' => array('status' => true));
		try {
			if (!empty($_REQUEST['booking_date']))
				$request_all_data = $_REQUEST;
			else
				$request_all_data = json_decode(file_get_contents('php://input'), true);
			if ($request_all_data['booking_date'] && $request_all_data['slot_id'] && $request_all_data['package_type']) {
				$booking_date = trim($request_all_data['booking_date']);
				$slot_id = trim($request_all_data['slot_id']);
				$package_type = trim($request_all_data['package_type']);
				$pack_rows = $this->db->query("SELECT max(bp.package_id) as package_id, max(tp.package_type) as package_type, max(tp.package_mode) as package_mode, max(tp.pack_date) as pack_date FROM `templebooking` tb left join booked_packages bp on bp.booking_id = tb.id left join temple_packages tp on tp.id = bp.package_id left join booked_slot bs on bs.booking_id = tb.id  WHERE `booking_date` = '$booking_date' and bs.booking_slot_id = $slot_id and tp.package_type = $package_type and tb.booking_status not in (3) GROUP by bp.package_id")->getNumRows();
				if ($pack_rows > 1) {
					$resp['data']['packages'] = array();
					$resp['data']['addons'] = array();
				} elseif ($pack_rows == 1) {
					$packages_result = $this->db->query("SELECT max(bp.package_id) as package_id, max(tp.package_type) as package_type, max(tp.package_mode) as package_mode, max(tp.pack_date) as pack_date FROM `templebooking` tb left join booked_packages bp on bp.booking_id = tb.id left join temple_packages tp on tp.id = bp.package_id left join booked_slot bs on bs.booking_id = tb.id  WHERE `booking_date` = '$booking_date' and bs.booking_slot_id = $slot_id and tp.package_type = $package_type and tb.booking_status not in (3) GROUP by bp.package_id")->getRowArray();
					if ($packages_result['package_mode'] == 2) {
						$act_pack_cnt = $this->db->table("temple_packages")->where("id", $packages_result['package_id'])->where("package_type", $package_type)->where("status", 1)->get()->getNumRows();
						// echo $act_pack_cnt;
						// exit;
						if ($act_pack_cnt > 0) {
							$resp['data']['packages'] = $this->db->table("temple_packages")->where("id", $packages_result['package_id'])->where("package_type", $package_type)->where("status", 1)->get()->getResultArray();
							$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id = '" . $packages_result['package_id'] . "')")->getResultArray();
						} else {
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					} else {
						$resp['data']['packages'] = array();
						$resp['data']['addons'] = array();
					}
				} else {
					$spl_pack_cnt = $this->db->table("temple_packages")->where("pack_date", $booking_date)->where("package_type", $package_type)->where("status", 1)->get()->getNumRows();
					if ($spl_pack_cnt > 0) {
						$resp['data']['packages'] = $this->db->table("temple_packages")->where("pack_date", $booking_date)->where("package_type", $package_type)->where("status", 1)->get()->getResultArray();
						$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id in(select id from temple_packages where pack_date = '$booking_date' and package_type = '$package_type' and status = 1))")->getResultArray();
					} else {
						$tot_pack_cnt = $this->db->table("temple_packages")->where("package_type", $package_type)->where("(pack_date IS NULL OR pack_date = '')")->where("status", 1)->get()->getNumRows();
						if ($tot_pack_cnt > 0) {
							$resp['data']['packages'] = $this->db->table("temple_packages")->where("package_type", $package_type)->where("(pack_date IS NULL OR pack_date = '')")->where("status", 1)->get()->getResultArray();
							$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id in (select id from temple_packages where (pack_date IS NULL OR pack_date = '') and package_type = '$package_type' and status = 1))")->getResultArray();
						} else {
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					}
				}
			} else
				$resp['data']['packages'] = array();
		} catch (Exception $e) {
			$resp['success'] = false;
			$resp['data']['status'] = false;
			$resp['data']['message'] = $e->getMessage();
			// $resp['data']['message_type'] = 'error';
			/* log_message('error', $e->getMessage());
			throw $e; */
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($resp);
		exit;
	}
	public function get_hallpackages_list_old()
	{
		$resp = array('success' => true, 'data' => array('status' => true));
		try {
			if (!empty($_REQUEST['booking_date']))
				$request_all_data = $_REQUEST;
			else
				$request_all_data = json_decode(file_get_contents('php://input'), true);
			if ($request_all_data['booking_date'] && $request_all_data['slot_id'] && $request_all_data['package_type']) {
				$booking_date = trim($request_all_data['booking_date']);
				$slot_id = trim($request_all_data['slot_id']);
				$venue_id = trim($request_all_data['venue_id']);
				$package_type = trim($request_all_data['package_type']);
				$pack_rows = $this->db->query("SELECT max(bp.package_id) as package_id, max(tp.package_type) as package_type, max(tp.package_mode) as package_mode, max(tp.pack_date) as pack_date FROM `templebooking` tb left join booked_packages bp on bp.booking_id = tb.id left join temple_packages tp on tp.id = bp.package_id left join booked_slot bs on bs.booking_id = tb.id  WHERE `booking_date` = '$booking_date' and bs.booking_slot_id = $slot_id and tp.package_type = $package_type and tb.booking_status not in (3) GROUP by bp.package_id")->getNumRows();
				if ($pack_rows > 1) {
					$resp['data']['packages'] = array();
					$resp['data']['addons'] = array();
				} elseif ($pack_rows == 1) {
					$packages_result = $this->db->query("SELECT max(bp.package_id) as package_id, max(tp.package_type) as package_type, max(tp.package_mode) as package_mode, max(tp.pack_date) as pack_date FROM `templebooking` tb left join booked_packages bp on bp.booking_id = tb.id left join temple_packages tp on tp.id = bp.package_id left join booked_slot bs on bs.booking_id = tb.id  WHERE `booking_date` = '$booking_date' and bs.booking_slot_id = $slot_id and tp.package_type = $package_type and tb.booking_status not in (3) GROUP by bp.package_id")->getRowArray();
					if ($packages_result['package_mode'] == 2) {
						$act_pack_cnt = $this->db->table("temple_packages")->where("id", $packages_result['package_id'])->where("package_type", $package_type)->where("status", 1)->get()->getNumRows();
						if ($act_pack_cnt > 0) {
							$resp['data']['packages'] = $this->db->table("temple_packages")->where("id", $packages_result['package_id'])->where("package_type", $package_type)->where("status", 1)->get()->getResultArray();
							$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id = '" . $packages_result['package_id'] . "')")->getResultArray();
						} else {
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					} else {
						$resp['data']['packages'] = array();
						$resp['data']['addons'] = array();
					}
				} else {
					$spl_pack_cnt = $this->db->table("temple_packages")->where("pack_date", $booking_date)->where("package_type", $package_type)->where("status", 1)->get()->getNumRows();
					if ($spl_pack_cnt > 0) {
						$resp['data']['packages'] = $this->db->table("temple_packages")->where("pack_date", $booking_date)->where("package_type", $package_type)->where("status", 1)->get()->getResultArray();
						$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id in(select id from temple_packages where pack_date = '$booking_date' and package_type = '$package_type' and status = 1))")->getResultArray();
					} else {
						$tot_pack_cnt = $this->db->table("temple_packages")->where("package_type", $package_type)->where("(pack_date IS NULL OR pack_date = '')")->where("status", 1)->get()->getNumRows();
						if ($tot_pack_cnt > 0) {
							$resp['data']['packages'] = $this->db->table("temple_packages")->where("package_type", $package_type)->where("(pack_date IS NULL OR pack_date = '')")->where("status", 1)->get()->getResultArray();
							$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id in (select id from temple_packages where (pack_date IS NULL OR pack_date = '') and package_type = '$package_type' and status = 1))")->getResultArray();
						} else {
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					}
				}
			} else
				$resp['data']['packages'] = array();
		} catch (Exception $e) {
			$resp['success'] = false;
			$resp['data']['status'] = false;
			$resp['data']['message'] = $e->getMessage();
			// $resp['data']['message_type'] = 'error';
			/* log_message('error', $e->getMessage());
			throw $e; */
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($resp);
		exit;
	}

	// public function get_hallpackages_list(){
	// 	$resp = array('success' => true, 'data' => array('status' => true));
	// 	try{
	// 		$request_all_data = !empty($_REQUEST['booking_date']) ? $_REQUEST : json_decode(file_get_contents('php://input'), true);
	// 		if($request_all_data['booking_date'] && $request_all_data['slot_id'] && $request_all_data['package_type'] && $request_all_data['venue_id']){
	// 			$booking_date = trim($request_all_data['booking_date']);
	// 			$slot_id = trim($request_all_data['slot_id']);
	// 			$venue_id = trim($request_all_data['venue_id']);
	// 			$package_type = trim($request_all_data['package_type']);

	// 			// Query to count package availability
	// 			$query = $this->db->query("
	// 						SELECT COUNT(*) as count FROM (
	// 							SELECT bp.package_id 
	// 							FROM templebooking tb
	// 							LEFT JOIN booked_packages bp ON bp.booking_id = tb.id
	// 							LEFT JOIN temple_packages tp ON tp.id = bp.package_id
	// 							LEFT JOIN booked_slot bs ON bs.booking_id = tb.id
	// 							LEFT JOIN temple_package_venues tpv ON tpv.package_id = tp.id
	// 							WHERE tb.booking_date = '$booking_date'
	// 							AND bs.booking_slot_id = $slot_id
	// 							AND tp.package_type = $package_type
	// 							AND tpv.venue_id = $venue_id
	// 							AND tb.booking_status NOT IN (3)
	// 							GROUP BY bp.package_id
	// 						) AS subquery
	// 					");

	// 			$result = $query->getRowArray();
	// 			$pack_rows = isset($result['count']) ? $result['count'] : 0;

	// 			// echo $pack_rows;
	// 			// exit;

	// 			if($pack_rows > 1){
	// 				$resp['data']['packages'] = array();
	// 				$resp['data']['addons'] = array();
	// 			} elseif($pack_rows == 1){
	// 				$packages_result = $this->db->query("
	// 									SELECT 
	// 										MAX(bp.package_id) AS package_id, 
	// 										MAX(tp.package_type) AS package_type, 
	// 										MAX(tp.package_mode) AS package_mode
	// 									FROM 
	// 										templebooking tb
	// 									LEFT JOIN 
	// 										booked_packages bp ON bp.booking_id = tb.id
	// 									LEFT JOIN 
	// 										temple_packages tp ON tp.id = bp.package_id
	// 									LEFT JOIN 
	// 										booked_slot bs ON bs.booking_id = tb.id
	// 									LEFT JOIN 
	// 										temple_package_venues tpv ON tpv.package_id = tp.id
	// 									WHERE 
	// 										tb.booking_date = '$booking_date'
	// 										AND bs.booking_slot_id = $slot_id
	// 										AND tp.package_type = $package_type
	// 										AND tpv.venue_id = $venue_id
	// 										AND tb.booking_status NOT IN (3)
	// 									GROUP BY 
	// 										bp.package_id
	// 								")->getRowArray();

	// 				if($packages_result['package_mode'] == 2){
	// 					$sql = "
	// 						SELECT * 
	// 						FROM temple_packages tp
	// 						LEFT JOIN 
	// 							temple_package_venues tpv ON tpv.package_id = tp.id
	// 						WHERE tp.id = ?
	// 						AND tp.package_type = ?
	// 						AND tp.status = 1
	// 						AND tpv.venue_id = ?
	// 					";

	// 					$packages = $this->db->query($sql, [$packages_result['package_id'], $package_type, $venue_id])->getResultArray();

	// 					$resp['data']['packages'] = $packages;
	// 					$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id = '" . $packages_result['package_id'] . "')")->getResultArray();
	// 				} else {
	// 					$resp['data']['packages'] = array();
	// 					$resp['data']['addons'] = array();
	// 				}
	// 			// Additional logic if no packages are found directly matching the criteria
	// 			} else {
	// 				$spl_pack_query = $this->db->query("
	// 					SELECT tp.*
	// 					FROM temple_packages tp
	// 					JOIN temple_package_venues tpv ON tpv.package_id = tp.id
	// 					WHERE tp.package_type = ?
	// 					AND tp.status = 1
	// 					AND tp.id IN (
	// 						SELECT package_id
	// 						FROM temple_package_date
	// 						WHERE package_type = ?
	// 						AND pack_date = ?
	// 					)
	// 					AND tpv.venue_id = ?
	// 				", [$package_type, $package_type, $booking_date, $venue_id])->getResultArray();

	// 				$spl_pack_cnt = count($spl_pack_query);

	// 				if ($spl_pack_cnt > 0) {
	// 					$resp['data']['packages'] = $spl_pack_query;
	// 					$resp['data']['addons'] = $this->db->query("
	// 						SELECT ts.*
	// 						FROM temple_services ts
	// 						WHERE ts.id IN (
	// 							SELECT service_id
	// 							FROM temple_package_addons
	// 							WHERE package_id IN (
	// 								SELECT id
	// 								FROM temple_packages
	// 								WHERE package_type = ?
	// 								AND status = 1
	// 								AND id IN (
	// 									SELECT package_id
	// 									FROM temple_package_date
	// 									WHERE pack_date = ?
	// 									AND package_type = ?
	// 								)
	// 							)
	// 						)
	// 					", [$package_type, $booking_date, $package_type])->getResultArray();
	// 				} else {
	// 					$tot_pack_cnt = $this->db->query("
	// 						SELECT COUNT(*) as count
	// 						FROM temple_packages tp
	// 						JOIN temple_package_venues tpv ON tpv.package_id = tp.id
	// 						WHERE tp.package_type = ?
	// 						AND tp.id NOT IN (
	// 							SELECT package_id
	// 							FROM temple_package_date
	// 							WHERE package_type = ?
	// 							AND pack_date IS NOT NULL
	// 							AND pack_date != '0000-00-00'
	// 						)
	// 						AND tpv.venue_id = ?
	// 					", [$package_type, $package_type, $venue_id])->getRow()->count;

	// 					if ($tot_pack_cnt > 0) {
	// 						$resp['data']['packages'] = $this->db->query("
	// 							SELECT tp.*
	// 							FROM temple_packages tp
	// 							JOIN temple_package_venues tpv ON tpv.package_id = tp.id
	// 							WHERE tp.package_type = ?
	// 							AND tp.status = 1
	// 							AND tp.id IN (
	// 								SELECT package_id
	// 								FROM temple_package_date
	// 								WHERE package_type = ?
	// 								AND pack_date IS NULL
	// 								OR pack_date = ''
	// 							)
	// 							AND tpv.venue_id = ?
	// 						", [$package_type, $package_type, $venue_id])->getResultArray();
	// 						$resp['data']['addons'] = $this->db->query("
	// 							SELECT ts.*
	// 							FROM temple_services ts
	// 							WHERE ts.id IN (
	// 								SELECT service_id
	// 								FROM temple_package_addons
	// 								WHERE package_id IN (
	// 									SELECT id
	// 									FROM temple_packages
	// 									WHERE package_type = ?
	// 									AND status = 1
	// 									AND id IN (
	// 										SELECT package_id
	// 										FROM temple_package_date
	// 										WHERE package_type = ?
	// 										AND pack_date IS NULL
	// 										OR pack_date = ''
	// 									)
	// 								)
	// 							)
	// 						", [$package_type, $package_type])->getResultArray();
	// 					} else {
	// 						$resp['data']['packages'] = array();
	// 						$resp['data']['addons'] = array();
	// 					}
	// 				}
	// 			}

	// 		} else {
	// 			$resp['data']['packages'] = array();
	// 		}
	// 	} catch (Exception $e) {
	// 		$resp['success'] = false;
	// 		$resp['data']['status'] = false;
	// 		$resp['data']['message'] = $e->getMessage();
	// 	}
	// 	header('Content-Type: application/json; charset=utf-8');
	// 	echo json_encode($resp);
	// 	exit;
	// }

	public function get_hallpackages_list()
	{
		$resp = array('success' => true, 'data' => array('status' => true));
		try {
			$request_all_data = !empty($_REQUEST['booking_date']) ? $_REQUEST : json_decode(file_get_contents('php://input'), true);
			if ($request_all_data['booking_date'] && $request_all_data['slot_id'] && $request_all_data['package_type'] && $request_all_data['venue_id']) {
				$booking_date = trim($request_all_data['booking_date']);
				$slot_id = trim($request_all_data['slot_id']);
				$venue_id = trim($request_all_data['venue_id']);
				$package_type = trim($request_all_data['package_type']);

				// Query to count package availability
				$query = $this->db->query("
								SELECT COUNT(*) as count FROM (
									SELECT bp.package_id 
									FROM templebooking tb
									LEFT JOIN booked_packages bp ON bp.booking_id = tb.id
									LEFT JOIN temple_packages tp ON tp.id = bp.package_id
									LEFT JOIN booked_slot bs ON bs.booking_id = tb.id
									LEFT JOIN temple_package_venues tpv ON tpv.package_id = tp.id
									WHERE tb.booking_date = '$booking_date'
									AND bs.booking_slot_id = $slot_id
									AND tp.package_type = $package_type
									AND tpv.venue_id = $venue_id
									AND tb.booking_status NOT IN (3)
									GROUP BY bp.package_id
								) AS subquery
							");

				$result = $query->getRowArray();
				$pack_rows = isset($result['count']) ? $result['count'] : 0;

				// echo $pack_rows;
				// exit;

				if ($pack_rows > 1) {
					$resp['data']['packages'] = array();
					$resp['data']['addons'] = array();
				} elseif ($pack_rows == 1) {
					$packages_result = $this->db->query("
											SELECT 
												MAX(bp.package_id) AS package_id, 
												MAX(tp.package_type) AS package_type, 
												MAX(tp.package_mode) AS package_mode
											FROM 
												templebooking tb
											LEFT JOIN 
												booked_packages bp ON bp.booking_id = tb.id
											LEFT JOIN 
												temple_packages tp ON tp.id = bp.package_id
											LEFT JOIN 
												booked_slot bs ON bs.booking_id = tb.id
											LEFT JOIN 
												temple_package_venues tpv ON tpv.package_id = tp.id
											WHERE 
												tb.booking_date = '$booking_date'
												AND bs.booking_slot_id = $slot_id
												AND tp.package_type = $package_type
												AND tpv.venue_id = $venue_id
												AND tb.booking_status NOT IN (3)
											GROUP BY 
												bp.package_id
										")->getRowArray();

					// echo $packages_result['package_id'];
					// exit;

					if ($packages_result['package_mode'] == 2) {
						// $sql = "
						// 	SELECT * 
						// 	FROM temple_packages tp
						// 	LEFT JOIN 
						// 		temple_package_venues tpv ON tpv.package_id = tp.id
						// 	WHERE tp.id = ?
						// 	AND tp.package_type = ?
						// 	AND tp.status = 1
						// 	AND tpv.venue_id = ?
						// ";

						$sql = "
								SELECT * 
								FROM temple_packages tp
								WHERE tp.id = ?
								AND tp.package_type = ?
								AND tp.status = 1
							";

						$packages = $this->db->query($sql, [$packages_result['package_id'], $package_type])->getResultArray();

						// echo $packages_result['package_id'];
						// exit;

						$resp['data']['packages'] = $packages;
						$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id = '" . $packages_result['package_id'] . "')")->getResultArray();
					} else {
						$resp['data']['packages'] = array();
						$resp['data']['addons'] = array();
					}
					// Additional logic if no packages are found directly matching the criteria
				} else {
					$spl_pack_query = $this->db->query("
							SELECT tp.*
							FROM temple_packages tp
							JOIN temple_package_venues tpv ON tpv.package_id = tp.id
							JOIN temple_package_slots tps ON tps.package_id = tp.id
							WHERE tp.package_type = ?
							AND tp.status = 1
							AND tp.id IN (
								SELECT package_id
								FROM temple_package_date
								WHERE package_type = ?
								AND pack_date = ?
							)
							AND tpv.venue_id = ?
							AND tps.slot_id = ?
						", [$package_type, $package_type, $booking_date, $venue_id, $slot_id])->getResultArray();

					$spl_pack_cnt = count($spl_pack_query);
					if ($spl_pack_cnt > 0) {
						$resp['data']['packages'] = $spl_pack_query;
						$resp['data']['addons'] = $this->db->query("
								SELECT ts.*
								FROM temple_services ts
								WHERE ts.id IN (
									SELECT service_id
									FROM temple_package_addons
									WHERE package_id IN (
										SELECT id
										FROM temple_packages
										WHERE package_type = ?
										AND status = 1
										AND id IN (
											SELECT package_id
											FROM temple_package_date
											WHERE pack_date = ?
											AND package_type = ?
										)
									)
								)
							", [$package_type, $booking_date, $package_type])->getResultArray();
					} else {
						$tot_pack_cnt = $this->db->query("
								SELECT COUNT(*) as count
								FROM temple_packages tp
								JOIN temple_package_venues tpv ON tpv.package_id = tp.id
								JOIN temple_package_slots tps ON tps.package_id = tp.id
								WHERE tp.package_type = ?
								AND tp.id NOT IN (
									SELECT distinct package_id
									FROM temple_package_date
									WHERE package_type = ?
									AND pack_date IS NOT NULL
									AND pack_date != '0000-00-00'
								)
								AND tp.status = 1
								AND tpv.venue_id = ?
								AND tps.slot_id = ?
							", [$package_type, $package_type, $venue_id, $slot_id])->getRow()->count;
						if ($tot_pack_cnt > 0) {
							$resp['data']['packages'] = $this->db->query("
									SELECT tp.*
									FROM temple_packages tp
									JOIN temple_package_venues tpv ON tpv.package_id = tp.id
									JOIN temple_package_slots tps ON tps.package_id = tp.id
									WHERE tp.package_type = ?
									AND tp.status = 1
									AND tp.id NOT IN (
										SELECT distinct package_id
										FROM temple_package_date
										WHERE package_type = ?
										AND pack_date IS NOT NULL
										OR pack_date != '0000-00-00'
									)
									AND tpv.venue_id = ?
									AND tps.slot_id = ?
								", [$package_type, $package_type, $venue_id, $slot_id])->getResultArray();
							/* echo  $this->db->getLastQuery();
								die; */
							$resp['data']['addons'] = $this->db->query("
									SELECT ts.*
									FROM temple_services ts
									WHERE ts.id IN (
										SELECT service_id
										FROM temple_package_addons
										WHERE package_id IN (
											SELECT id
											FROM temple_packages
											WHERE package_type = ?
											AND status = 1
											AND id IN (
												SELECT package_id
												FROM temple_package_date
												WHERE package_type = ?
												AND pack_date IS NULL
												OR pack_date = ''
											)
										)
									)
								", [$package_type, $package_type])->getResultArray();
						} else {
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					}
				}
			} else {
				$resp['data']['packages'] = array();
			}
		} catch (Exception $e) {
			$resp['success'] = false;
			$resp['data']['status'] = false;
			$resp['data']['message'] = $e->getMessage();
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($resp);
		exit;
	}


	public function get_hallpackages_list_oldddd()
	{
		$resp = array('success' => true, 'data' => array('status' => true));
		try {
			if (!empty($_REQUEST['booking_date']))
				$request_all_data = $_REQUEST;
			else
				$request_all_data = json_decode(file_get_contents('php://input'), true);
			if ($request_all_data['booking_date'] && $request_all_data['slot_id'] && $request_all_data['package_type'] && $request_all_data['venue_id'] && isset($request_all_data['is_weekend'])) {
				$booking_date = trim($request_all_data['booking_date']);
				$slot_id = trim($request_all_data['slot_id']);
				$package_type = trim($request_all_data['package_type']);
				$venue_id = trim($request_all_data['venue_id']);
				$is_weekend = trim($request_all_data['is_weekend']);

				// Modified query to include venue and conditional amount
				$pack_rows = $this->db->query("SELECT max(bp.package_id) as package_id, max(tp.package_type) as package_type, max(IF('$is_weekend' = '1', tp.weekend_amount, tp.amount)) as package_amount, max(tp.package_mode) as package_mode, max(tp.pack_date) as pack_date 
					FROM templebooking tb 
					LEFT JOIN booked_packages bp ON bp.booking_id = tb.id 
					LEFT JOIN temple_packages tp ON tp.id = bp.package_id 
					LEFT JOIN booked_slot bs ON bs.booking_id = tb.id 
					LEFT JOIN temple_package_venues tpv ON tpv.package_id = tp.id
					WHERE booking_date = '$booking_date' AND bs.booking_slot_id = $slot_id AND tp.package_type = $package_type AND tb.booking_status NOT IN (3) AND tpv.venue_id = '$venue_id' 
					GROUP BY bp.package_id")->getNumRows();

				if ($pack_rows > 1) {
					$resp['data']['packages'] = array();
					$resp['data']['addons'] = array();
				} elseif ($pack_rows == 1) {
					$packages_result = $this->db->query("SELECT max(bp.package_id) as package_id, max(tp.package_type) as package_type, max(IF('$is_weekend' = '1', tp.weekend_amount, tp.amount)) as package_amount, max(tp.package_mode) as package_mode, max(tp.pack_date) as pack_date 
						FROM templebooking tb 
						LEFT JOIN booked_packages bp ON bp.booking_id = tb.id 
						LEFT JOIN temple_packages tp ON tp.id = bp.package_id 
						LEFT JOIN booked_slot bs ON bs.booking_id = tb.id 
						LEFT JOIN temple_package_venues tpv ON tpv.package_id = tp.id
						WHERE booking_date = '$booking_date' AND bs.booking_slot_id = $slot_id AND tp.package_type = $package_type AND tb.booking_status NOT IN (3) AND tpv.venue_id = '$venue_id' 
						GROUP BY bp.package_id")->getRowArray();
					if ($packages_result['package_mode'] == 2) {
						$act_pack_cnt = $this->db->table("temple_packages")->where("id", $packages_result['package_id'])->where("package_type", $package_type)->where("status", 1)->get()->getNumRows();
						if ($act_pack_cnt > 0) {
							$resp['data']['packages'] = $this->db->table("temple_packages")->where("id", $packages_result['package_id'])->where("package_type", $package_type)->where("status", 1)->get()->getResultArray();
							$resp['data']['addons'] = $this->db->query("SELECT * FROM temple_services WHERE id IN (SELECT service_id FROM temple_package_addons WHERE package_id = '" . $packages_result['package_id'] . "')")->getResultArray();
						} else {
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					} else {
						$resp['data']['packages'] = array();
						$resp['data']['addons'] = array();
					}
				} else {
					$resp['data']['packages'] = array();
					$resp['data']['addons'] = array();
					$resp['data']['message'] = "No package available in this venue";
				}
			} else {
				$resp['data']['packages'] = array();
				$resp['data']['message'] = "Required parameters are missing";
			}
		} catch (Exception $e) {
			$resp['success'] = false;
			$resp['data']['status'] = false;
			$resp['data']['message'] = $e->getMessage();
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($resp);
		exit;
	}


	public function send_whatsapp_msg_booking($booking_id, $booking_type)
	{
		$booking_data = $this->db->table('templebooking')
			->where('id', $booking_id)
			->where('booking_type', $booking_type)
			->get()->getRowArray();
		$booked_slot = $this->db->table('booked_slot')->where('booking_id', $booking_id)->get()->getResultArray();
		$booked_slot_name = array();
		foreach ($booked_slot as $bs) {
			$booked_slot_name[] = $bs['slot_name'];
		}
		// $tmpid = 1;
		// $data['temple_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		// $data['payment'] = $this->db->table('ubayam_pay_details')->where('ubayam_id', $id)->get()->getResultArray();
		// $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
		// $data['pay_details'] = $this->db->table("ubayam_pay_details")->where("ubayam_id", $id)->get()->getResultArray();
		if (!empty($booking_data['mobile_code']) && !empty($booking_data['mobile_no'])) {
			// $html = view('ubayam/pdf', $data);
			// $options = new Options();
			// $options->set('isHtml5ParserEnabled', true);
			// $options->set(array('isRemoteEnabled' => true));
			// $options->set('isPhpEnabled', true);
			// $dompdf = new Dompdf($options);
			// $dompdf->loadHtml($html);
			// $dompdf->setPaper('A4', 'portrait');
			// $dompdf->render();
			// $filePath = FCPATH . 'uploads/documents/invoice_ubayam_' . $id . '.pdf';

			// file_put_contents($filePath, $dompdf->output());
			$mobile_code = $booking_data['mobile_code'];
			$mobile_no = $booking_data['mobile_no'];
			$mobile_number = $mobile_code . $mobile_no;
			$message_params = array();
			$media = array();
			$api_camp = 'ubayam_live';
			if ($booking_type == 2) {
				$message_params[] = date('d M, Y', strtotime($booking_data['booking_date']));
				$message_params[] = implode(', ', $booked_slot_name);
				// $message_params[] = number_format($booking_data['amount'], 2);
				$message_params[] = number_format($booking_data['paid_amount'], 2);
				$api_camp = 'ubayam_live';
			} elseif ($booking_type == 1) {
				$booked_packages = $this->db->table('booked_packages')->where('booking_id', $booking_id)->get()->getResultArray();
				$booked_pack_name = array();
				foreach ($booked_packages as $bp) {
					$booked_pack_name[] = $bp['name'];
				}
				$message_params[] = implode(', ', $booked_pack_name);
				$message_params[] = date('d M, Y', strtotime($booking_data['booking_date']));
				$message_params[] = implode(', ', $booked_slot_name);
				// $message_params[] = number_format($booking_data['amount'], 2);
				$message_params[] = number_format($booking_data['paid_amount'], 2);
				$api_camp = 'hall_booking';
			}
			// $message_params[] = $ubayam['balanceamount'];
			// $media['url'] = base_url() . '/uploads/documents/invoice_ubayam_' . $id . '.pdf';
			// $media['filename'] = 'ubayam_invoice.pdf';
			//$mobile_number = '+919092615446';
			// print_r($mobile_number);
			// print_r($message_params);
			// print_r($media);
			// die; 
			$whatsapp_resp = whatsapp_aisensy($mobile_number, $message_params, $api_camp, $media);
			// print_r($whatsapp_resp);
			//echo $whatsapp_resp['success'];
			// if($whatsapp_resp['success']) 
		}
	}
	public function partial_account_migration($booked_pay_id)
	{
		$succ = true;
		$booked_pay_details_cnt = $this->db->table("booked_pay_details")->where("id", $booked_pay_id)->get()->getNumRows();
		if ($booked_pay_details_cnt > 0) {
			$booked_pay_details = $this->db->table("booked_pay_details")->where("id", $booked_pay_id)->get()->getResultArray();
			foreach ($booked_pay_details as $row) {
				$paymentmode = $this->db->table('payment_mode')->where('id', $row['payment_mode_id'])->get()->getRowArray();
				if (!empty($paymentmode['ledger_id'])) {
					$number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
					if (empty($number))
						$num = 1;
					else
						$num = $number['number'] + 1;
					// Get Entry Code
					$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();

					$entries['entry_code'] = 'REC' . date('y', strtotime($entry_date)) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
					$entries['entrytype_id'] = '1';
					$entries['number'] = $num;
					$entries['date'] = $entry_date;
					$entries['dr_total'] = $row['amount'];
					$entries['cr_total'] = $row['amount'];
					$entries['narration'] = 'Hall Booking(' . $templeubayam['ref_no'] . ')' . "\n" . 'name:' . $templeubayam['name'] . "\n" . 'NRIC:' . $templeubayam['ic_number'] . "\n" . 'email:' . $templeubayam['email'] . "\n";
					$entries['inv_id'] = $booking_id;
					$entries['type'] = 8;
					//Insert Entries
					$ent = $this->db->table('entries')->insert($entries);
					$en_id = $this->db->insertID();
					if (!empty($en_id)) {
						// Trade Debtors => Credit
						$eitems_hall_book['entry_id'] = $en_id;
						$eitems_hall_book['ledger_id'] = $cr_id1;
						$eitems_hall_book['amount'] = $row['amount'];
						$eitems_hall_book['dc'] = 'C';
						$eitems_hall_book['details'] = 'Hall Booking Amount';
						$this->db->table('entryitems')->insert($eitems_hall_book);
						// PETTY CASH => Debit 
						$eitems_cash_led['entry_id'] = $en_id;
						$eitems_cash_led['ledger_id'] = $paymentmode['ledger_id'];
						$eitems_cash_led['amount'] = $row['amount'];
						$eitems_cash_led['dc'] = 'D';
						$eitems_cash_led['details'] = 'Hall Booking Amount';
						$this->db->table('entryitems')->insert($eitems_cash_led);
					}
				} else {
					$succ = false;
					return $succ;
				}
			}
		} else {
			$succ = false;
			return $succ;
		}
	}

	public function getDeitiesBySlot()
	{
		$package_type = $this->request->getPost('package_type');
		$slot_id = $this->request->getPost('slot_id');
		$package_id = $this->request->getPost('package_id');

		if ($package_type == '2' && !empty($slot_id)) {
			$deity_ids = $this->db->table('temple_package_slots')
				->select('deity_id')
				->where('slot_id', $slot_id)
				->where('package_id', $package_id)
				->get()
				->getRowArray();

			if (!empty($deity_ids['deity_id'])) {
				$deity_id_array = explode(',', $deity_ids['deity_id']);

				$deities = $this->db->table('archanai_diety')->whereIn('id', $deity_id_array)->get()->getResultArray();

				return $this->response->setJSON([
					'success' => true,
					'deities' => $deities
				]);
			} else {
				return $this->response->setJSON([
					'success' => true,
					'deities' => []
				]);
			}
		} else {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Invalid request parameters.'
			]);
		}
	}
	public function send_ubayam_booking_confirmation($booking_id)
	{
	

		// Get booking details
		$booking = $this->db->table('templebooking')
			->where('id', $booking_id)
			->get()->getRowArray();
	
		if (empty($booking['mobile_no']) || $booking['payment_status'] != 2) {
		
			return false;
		}

		// Check if WhatsApp already sent to avoid duplicates
		$whatsapp_sent = $this->check_ubayam_whatsapp_sent($booking_id, 'booking_confirmation');

		if ($whatsapp_sent) {

			return false;
		}

		// Generate PDF for WhatsApp
		$pdf_path = $this->generate_ubayam_pdf_for_whatsapp($booking_id);

		if (!$pdf_path) {
			return false;
		}

		// Get package name
		$package_details = $this->get_ubayam_package_details($booking_id);

		// Format mobile number
		$mobile_number = $booking['mobile_code'] . $booking['mobile_no'];

		// Prepare message parameters
		$params = [
			':devotee' => $booking['name'],
			':ref_no' => $booking['ref_no'],
			':booking_date' => date('d M Y', strtotime($booking['booking_date'])),
			':package' => $package_details['package_name'] ?? 'Ubayam Package',
			':amount' => number_format($booking['total_amount'], 2),
			':slot' => $package_details['slot_name'] ?? ''
		];

		// Media attachment
		$media = [
			'url' => base_url() . '/' . $pdf_path,
			'filename' => 'ubayam_booking_' . $booking['ref_no'] . '.pdf'
		];

		// Send WhatsApp message
		$numbers = [$mobile_number];
		$response = whatsapp_ultramsg($numbers, 'ubayam_booking_confirmation', $params, $media);

		// Log WhatsApp activity
		$this->log_ubayam_whatsapp_activity($booking_id, 'booking_confirmation', $response);

		return $response;
	}

	/**
	 * Generate PDF for WhatsApp sharing using existing view
	 */
	private function generate_ubayam_pdf_for_whatsapp($booking_id)
	{
		try {
			// Get booking data
			$data['booking'] = $this->db->table('templebooking')
				->where('id', $booking_id)
				->get()->getRowArray();

			// Get temple details
			$data['temple_details'] = $this->db->table('admin_profile')
				->where('id', 1)
				->get()->getRowArray();

			// Get booked packages - Fixed table names
			$data['packages'] = $this->db->table('booked_packages bp')
				->join('temple_packages p', 'p.id = bp.package_id')
				->select('bp.*, p.name, p.amount')
				->where('bp.booking_id', $booking_id)
				->get()->getResultArray();

			// Get booked slots
			$data['slots'] = $this->db->table('booked_slot bs')
				->select('bs.*')
				->where('bs.booking_id', $booking_id)
				->get()->getResultArray();

			// Get addons - Using temple_services for addons
			$data['addons'] = $this->db->table('booked_addon ba')
				->join('temple_services ts', 'ts.id = ba.service_id')
				->select('ba.*, ts.name as addon_name, ba.amount')
				->where('ba.booking_id', $booking_id)
				->get()->getResultArray();

			// Get payment details
			$data['payment_details'] = $this->db->table('booked_pay_details')
				->where('booking_id', $booking_id)
				->get()->getResultArray();

			// Get prasadam if any
			$data['prasadam'] = $this->db->query("
            SELECT ps.*, pbd.quantity 
            FROM prasadam_booking_details pbd
            JOIN prasadam_setting ps ON ps.id = pbd.prasadam_id
            WHERE pbd.prasadam_booking_id = ?", [$booking_id])->getResultArray();

			// Additional data that might be needed for the view
			$data['booking_id'] = $booking_id;
			$data['data'] = $data['booking']; // Some views expect 'data' instead of 'booking'

			// Use your existing view file to generate HTML
			$html = view('ubayam/whatsapp_pdf', $data);

			// Create PDF using Dompdf
			$options = new \Dompdf\Options();
			$options->set('isHtml5ParserEnabled', true);
			$options->set('isRemoteEnabled', true);
			$options->set('isPhpEnabled', true);
			$options->set('defaultFont', 'DejaVu Sans');

			$dompdf = new \Dompdf\Dompdf($options);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();

			// Save PDF file
			$upload_path = 'uploads/ubayam_pdfs/';
			if (!is_dir($upload_path)) {
				mkdir($upload_path, 0777, true);
			}

			$filename = 'ubayam_booking_' . $booking_id . '_' . time() . '.pdf';
			$file_path = $upload_path . $filename;

			file_put_contents($file_path, $dompdf->output());

			return $file_path;
		} catch (Exception $e) {
			log_message('error', 'Ubayam PDF Generation Error: ' . $e->getMessage());
			return false;
		}
	}

	private function get_ubayam_package_details($booking_id)
	{
		// Get main package - Fixed table name
		$package = $this->db->table('booked_packages bp')
			->join('temple_packages p', 'p.id = bp.package_id')
			->select('p.name as package_name')
			->where('bp.booking_id', $booking_id)
			->get()->getRowArray();

		// Get slot - Using booked_slot table directly as it stores slot_name
		$slot = $this->db->table('booked_slot')
			->select('slot_name')
			->where('booking_id', $booking_id)
			->get()->getRowArray();

		return [
			'package_name' => $package['package_name'] ?? 'Ubayam Package',
			'slot_name' => $slot['slot_name'] ?? ''
		];
	}

	/**
	 * Send reminder WhatsApp message
	 */
	// public function send_ubayam_reminder($booking_id)
	// {
	// 	$booking = $this->db->table('templebooking')
	// 		->where('id', $booking_id)
	// 		->get()->getRowArray();

	// 	if (empty($booking['mobile_no']) || $booking['payment_status'] != 2) {
	// 		return false;
	// 	}

	// 	// Check if reminder already sent
	// 	$reminder_sent = $this->check_ubayam_whatsapp_sent($booking_id, 'reminder');
	// 	if ($reminder_sent) {
	// 		return false;
	// 	}

	// 	$mobile_number = $booking['mobile_code'] . $booking['mobile_no'];
	// 	$package_details = $this->get_ubayam_package_details($booking_id);

	// 	$params = [
	// 		':devotee' => $booking['name'],
	// 		':booking_date' => date('d M Y', strtotime($booking['booking_date'])),
	// 		':ref_no' => $booking['ref_no'],
	// 		':package' => $package_details['package_name'],
	// 		':slot' => $package_details['slot_name']
	// 	];

	// 	$numbers = [$mobile_number];
	// 	$response = whatsapp_ultramsg($numbers, 'ubayam_reminder', $params);

	// 	$this->log_ubayam_whatsapp_activity($booking_id, 'reminder', $response);

	// 	return $response;
	// }

	/**
	 * Check if WhatsApp message already sent
	 */
	private function check_ubayam_whatsapp_sent($booking_id, $message_type)
	{

		$tables = $this->db->listTables();
		if (!in_array('ubayam_whatsapp_log', $tables)) {
			// Create table if it doesn't exist
			$this->create_ubayam_whatsapp_log_table();
		}

		$sent = $this->db->table('ubayam_whatsapp_log')
			->where('booking_id', $booking_id)
			->where('message_type', $message_type)
			->where('status', 'sent')
			->get()->getRowArray();

		return !empty($sent);
	}

	/**
	 * Log WhatsApp activity
	 */
	private function log_ubayam_whatsapp_activity($booking_id, $message_type, $response)
	{
		$tables = $this->db->listTables();
		if (!in_array('ubayam_whatsapp_log', $tables)) {
			$this->create_ubayam_whatsapp_log_table();
		}

		$log_data = [
			'booking_id' => $booking_id,
			'message_type' => $message_type,
			'response' => json_encode($response),
			'status' => (isset($response['sent']) && $response['sent']) ? 'sent' : 'failed',
			'created_at' => date('Y-m-d H:i:s')
		];

		try {
			$this->db->table('ubayam_whatsapp_log')->insert($log_data);
		} catch (Exception $e) {
			log_message('error', 'Failed to log Ubayam WhatsApp activity: ' . $e->getMessage());
		}
	}

	/**
	 * Create WhatsApp log table if it doesn't exist
	 */
	private function create_ubayam_whatsapp_log_table()
	{
		$forge = \Config\Database::forge();

		$fields = [
			'id' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'auto_increment' => true,
			],
			'booking_id' => [
				'type' => 'INT',
				'constraint' => 11,
			],
			'message_type' => [
				'type' => 'VARCHAR',
				'constraint' => 50,
			],
			'response' => [
				'type' => 'TEXT',
				'null' => true,
			],
			'status' => [
				'type' => 'VARCHAR',
				'constraint' => 20,
			],
			'created_at' => [
				'type' => 'DATETIME',
			],
		];

		$forge->addField($fields);
		$forge->addKey('id', true);
		$forge->addKey('booking_id');
		$forge->addKey('message_type');
		$forge->createTable('ubayam_whatsapp_log', true);
	}

	/**
	 * Cron job to send reminders
	 */
	public function send_ubayam_reminders_cron()
	{
		$secret_key = 'ubayam_cron_2024_secret';
		$provided_key = $this->request->getGet('key') ?: $this->request->getPost('key');

		if ($provided_key !== $secret_key) {
			http_response_code(403);
			echo json_encode(['error' => 'Unauthorized access']);
			return;
		}

		$reminder_date = date('Y-m-d', strtotime('+2 day'));

		// Get bookings that need reminders
		$bookings = $this->db->table('templebooking')
			->select('id')
			->where('booking_date', $reminder_date)
			->where('payment_status', 2)
			->where('booking_status', 1)
			->get()->getResultArray();

		$sent_count = 0;
		$failed_count = 0;

		foreach ($bookings as $booking) {
			if ($this->send_ubayam_reminder($booking['id'])) {
				$sent_count++;
			} else {
				$failed_count++;
			}
			sleep(1); // Avoid rate limiting
		}

		$response = [
			'status' => 'completed',
			'sent_count' => $sent_count,
			'failed_count' => $failed_count,
			'date' => date('Y-m-d H:i:s'),
			'reminder_date' => $reminder_date
		];

		log_message('info', "Ubayam reminders cron: " . json_encode($response));

		header('Content-Type: application/json');
		echo json_encode($response);
	}


	public function send_ubayam_reminder($booking_id)
	{
		$booking = $this->db->table('templebooking')
			->where('id', $booking_id)
			->get()->getRowArray();

		if (empty($booking['mobile_no']) || $booking['payment_status'] != 2) {
			log_message('error', "Ubayam reminder skipped - Invalid booking data for ID: $booking_id");
			return false;
		}

		// Check if reminder already sent
		$reminder_sent = $this->check_ubayam_whatsapp_sent($booking_id, 'reminder');
		if ($reminder_sent) {
			log_message('info', "Ubayam reminder already sent for ID: $booking_id");
			return false;
		}

		$mobile_number = $booking['mobile_code'] . $booking['mobile_no'];
		$package_details = $this->get_ubayam_package_details($booking_id);

		$params = [
			':devotee' => $booking['name'],
			':booking_date' => date('d M Y', strtotime($booking['booking_date'])),
			':ref_no' => $booking['ref_no'],
			':package' => $package_details['package_name'] ?? 'Ubayam Package',
			':slot' => $package_details['slot_name'] ?? ''
		];

		$numbers = [$mobile_number];
		$response = whatsapp_ultramsg($numbers, 'ubayam_reminder', $params);

		// Log WhatsApp activity
		$this->log_ubayam_whatsapp_activity($booking_id, 'reminder', $response);

		log_message('info', "Ubayam reminder sent for ID: $booking_id, Response: " . json_encode($response));

		return ['sent' => true, 'response' => $response];
	}
	/**
	 * Send payment reminder for Ubayam bookings
	 * Add this method to your Ajax controller
	 */
	public function send_payment_reminder($booking_id)
	{
		try {
			// Get booking details
			$booking = $this->db->table('templebooking')
				->where('id', $booking_id)
				->get()->getRowArray();

			if (empty($booking)) {
				log_message('error', "Payment reminder - Booking not found for ID: $booking_id");
				return ['status' => false, 'error' => 'Booking not found'];
			}

			// Check if mobile number exists
			if (empty($booking['mobile_no'])) {
				log_message('error', "Payment reminder - No mobile number for booking ID: $booking_id");
				return ['status' => false, 'error' => 'No mobile number'];
			}

			// Calculate due amount
			$due_amount = $booking['amount'] - $booking['paid_amount'];
			if ($due_amount <= 0) {
				log_message('info', "Payment reminder - No due amount for booking ID: $booking_id");
				return ['status' => false, 'error' => 'No due amount'];
			}

			// Get package details
			$package_details = $this->get_ubayam_package_details($booking_id);

			// Format mobile number
			$mobile_number = $booking['mobile_code'] . $booking['mobile_no'];

			// Prepare WhatsApp message parameters
			$params = [
				':devotee' => $booking['name'],
				':ref_no' => $booking['ref_no'],
				':booking_date' => date('d M Y', strtotime($booking['booking_date'])),
				':due_amount' => number_format($due_amount, 2),
				':package' => $package_details['package_name'] ?? 'Ubayam Package',
				':slot' => $package_details['slot_name'] ?? '',
				//':payment_link' => base_url() . '/payment/ubayam/' . $booking['ref_no'] // Optional payment link
			];

			// Send WhatsApp message
			$numbers = [$mobile_number];
			$response = whatsapp_ultramsg($numbers, 'ubayam_payment_reminder', $params);

			// Log the activity
			$this->log_ubayam_whatsapp_activity($booking_id, 'payment_reminder', $response);

			log_message('info', "Payment reminder sent for booking ID: $booking_id, Response: " . json_encode($response));

			return ['status' => true, 'response' => $response];

		} catch (\Exception $e) {
			log_message('error', 'Payment reminder error for ID ' . $booking_id . ': ' . $e->getMessage());
			return ['status' => false, 'error' => $e->getMessage()];
		}
	}
}
