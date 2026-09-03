<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\RequestModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Prasadam extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		helper('common');
		helper('common_helper'); // Added for UltraMsg WhatsApp
		$this->model = new PermissionModel();
		if (($this->session->get('login')) == false && $this->session->get('role') != 1) {
			$data['dn_msg'] = 'Please Login';
			header('Location: ' . base_url() . '/login');
			exit;
		}
	}
	// function __construct()
	// {
	// 	parent::__construct();
	// 	helper('url');
	// 	helper('common');
	// 	$request = service('request');
	// 	$this->model = new PermissionModel();
	// 	$this->common_model = new Common_model();

	// 	// Get current method name
	// 	$router = service('router');
	// 	$current_method = $router->methodName();

	// 	// Skip authentication for cron methods
	// 	$cron_methods = ['admin_send_prasadam_reminders_cron', 'admin_send_prasadam_thankyou_cron', 'admin_test_cron_connection'];

	// 	if (!in_array($current_method, $cron_methods)) {
	// 		// Only check login for non-cron methods
	// 		if (($this->session->get('log_id_frend')) == false) {
	// 			if ($request->isAJAX()) {
	// 				echo json_encode([
	// 					"session_expired" => true,
	// 					"status" => 200
	// 				]);
	// 				exit;
	// 			}

	// 			$data['dn_msg'] = 'Please Login';
	// 			header('Location: ' . base_url() . '/member_login');
	// 			exit;
	// 		}
	// 	}
	// }
	public function index_old124()
	{
		//exit;
		if (!$this->model->list_validate('prasadam')) {
			header('Location: ' . base_url() . '/dashboard');
		}
		$data['list'] = $this->db->table('prasadam')
			->select('prasadam.*')
			->orderBy('date', 'DESC')
			->orderBy('created_at', 'DESC')
			->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('prasadam/index', $data);
		echo view('template/footer');
	}

	public function index()
	{
		// if (!$this->model->list_validate('prasadam')) {
		// 	header('Location: ' . base_url() . '/dashboard');
		// }
		$data['list'] = $this->db->table('prasadam')->select('prasadam.*')->orderBy('date', 'DESC')->orderBy('created_at', 'DESC')->get()->getResultArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->where('paid_through', 'DIRECT')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('prasadam/index', $data);
		echo view('template/footer');
	}

	public function add()
	{
		$default_group = $this->db->query("SELECT * FROM prasadam_group order by id asc limit 1")->getRowArray();
		$data['default'] = str_replace(' ', '_', strtolower($default_group['name']));
		$data['phone_codes'] = $this->db->table("phone_code")->orderBy('dailing_code', 'ASC')->get()->getResultArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where("paid_through", "DIRECT")->where("prasadam", 1)->where('status', 1)->get()->getResultArray();

		// Fetch dieties for the dropdown
		$data['dieties'] = $this->db->table('archanai_diety')->get()->getResultArray();

		$yr = date('Y');
		$mon = date('m');
		$query = $this->db->query("SELECT ref_no FROM prasadam where id=(select max(id) from prasadam where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();
		$data['bill_no'] = 'PR' . date('y') . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));

		$pras_sett = $this->db->table('prasadam_setting')->get()->getResultArray();
		foreach ($pras_sett as $row) {
			$group_ids = explode(',', $row['group_id']);
			foreach ($group_ids as $grp) {
				$group = $this->db->table('prasadam_group')->where('id', $grp)->get()->getRowArray();
				if ($group) {
					if (!isset($data['sett_data'][$group['name']])) {
						$data['sett_data'][$group['name']] = [];
					}
					$row['group_id'] = $grp;
					$data['sett_data'][$group['name']][] = $row;
				}
			}
		}

		// Sort groups by order_no
		$group_order = [];
		if (isset($data['sett_data'])) {
			foreach ($data['sett_data'] as $group_name => $settings) {
				$group_details = $this->db->table('prasadam_group')->where('name', $group_name)->get()->getRowArray();
				if ($group_details) {
					$group_order[$group_name] = $group_details['order_no'];
				}
			}
			asort($group_order);
			$sorted_data = [];
			foreach ($group_order as $group_name => $order_no) {
				$sorted_data[$group_name] = $data['sett_data'][$group_name];
			}
			$data['sett_data'] = $sorted_data;
		}

		$booking_settings = $this->db->table('booking_setting')->get()->getResultArray();
		$setting = array();
		if (count($booking_settings) > 0) {
			foreach ($booking_settings as $bs) {
				$setting[$bs['meta_key']] = $bs['meta_value'];
			}
		}
		$data['setting'] = $setting;

		echo view('template/header');
		echo view('template/sidebar');
		echo view('prasadam/add', $data);
		echo view('template/footer');
	}

	public function view()
	{

		$id = $this->request->uri->getSegment(3);
		$data['data'] = $this->db->table('prasadam')->where('id', $id)->get()->getRowArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->get()->getResultArray();
		$data['prasadam_settings'] = $this->db->table('prasadam_setting')->get()->getResultArray();
		$data['view'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('prasadam/add', $data);
		echo view('template/footer');
	}

	public function edit()
	{
		// if(!$this->model->permission_validate('prasadam','edit')){
		// 	header('Location: '.base_url().'/dashboard');			
		// }
		$id = $this->request->uri->getSegment(3);
		$data['data'] = $this->db->table('prasadam')->where('id', $id)->get()->getRowArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->get()->getResultArray();
		$data['prasadam_settings'] = $this->db->table('prasadam_setting')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('prasadam/add', $data);
		echo view('template/footer');
	}

	public function save()
	{
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$this->db->transStart();
		try {
			$cnt = $_POST['cnt'];
			$customer_name = $_POST['customer_name'];
			$date = $_POST['date'];
			$billno = $_POST['billno'];
			$collection_date = $_POST['collection_date'];
			$payment_mode = $_POST['paymentmode'];
			$tot_amt = $_POST['tot_amt'];
			$mble_number = !empty($_POST['mobile']) ? $_POST['mobile'] : "";
			$data['payment_type'] = !empty($_POST['payment_type']) ? $_POST['payment_type'] : 'full';

			// NEW: Get time and session data
			$time_slot = !empty($_POST['time']) ? $_POST['time'] : "";
			$hour = !empty($_POST['hour']) ? $_POST['hour'] : "";
			$minute = !empty($_POST['minute']) ? $_POST['minute'] : "";
			$serve_time = $hour . ':' . $minute;

			// Determine AM/PM based on slot selection
			$time_session = ($time_slot == 'Breakfast') ? "AM" : "PM";

			// NEW: Get diety_id
			$diety_id = !empty($_POST['diety_id']) ? $_POST['diety_id'] : null;

			if (empty($customer_name) || empty($collection_date) || empty($mble_number) || empty($payment_mode) || empty($time_slot) || empty($tot_amt)) {
				$this->session->setFlashdata('fail', 'Please enter required fields');
				$msg_data['err'] = 'Please enter required fields.';
			} else if ($cnt == 0 || $cnt == '') {
				$this->session->setFlashdata('fail', 'Please add atlease one item');
				$msg_data['err'] = 'Please add atlease one item';
			} else {
				$paid_amount = !empty($_POST['paid_amount']) ? (float) $_POST['paid_amount'] : 0;
				$tot_amt = !empty($_POST['tot_amt']) ? (float) $_POST['tot_amt'] : 0;

				if (($data['payment_type'] == 'partial' && !empty($paid_amount)) || $data['payment_type'] == 'full') {
					if ($data['payment_type'] == 'full')
						$paid_amount = $tot_amt;
					if ($paid_amount <= $tot_amt) {
						$yr = date('Y');
						$mon = date('m');
						$query = $this->db->query("SELECT ref_no FROM prasadam where id=(select max(id) from prasadam where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();
						$data['ref_no'] = 'PR' . date('y', strtotime($_POST['date'])) . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
						$data['date'] = date('Y-m-d', strtotime($_POST['date']));
						$data['customer_name'] = $customer_name;
						$mble_phonecode = !empty($_POST['phonecode']) ? $_POST['phonecode'] : "";
						$mble_number = !empty($_POST['mobile']) ? $_POST['mobile'] : "";
						$data['mobile_no'] = $mble_phonecode . $mble_number;
						$data['email_id'] = $_POST['email_id'];
						$data['ic_no'] = $_POST['ic_number'];
						$data['address'] = $_POST['address'];
						$data['prasadam_notes'] = !empty($_POST['prasadam_notes']) ? $_POST['prasadam_notes'] : null;
						$data['desciption'] = $_POST['description'];

						// NEW: Add diety_id
						$data['diety_id'] = $diety_id;

						// NEW: Add session and serve_time
						$data['session'] = $time_slot;
						$data['serve_time'] = $serve_time . ' ' . $time_session;

						$sub_total = $_POST['tot_amt'];
						if (!empty($_POST['discount_amount'])) {
							$data['discount_amount'] = $_POST['discount_amount'];
							$sub_total += $_POST['discount_amount'];
						}
						$data['sub_total'] = $sub_total;
						$data['total_amount'] = $tot_amt;
						$data['collection_date'] = $collection_date;

						// Set start_time and collection_session based on the serve_time
						$data['start_time'] = $serve_time;
						list($hours, $minutes) = explode(":", $serve_time);
						if ($time_slot == 'Lunch' || ($time_slot == 'Dinner' && intval($hours) >= 6)) {
							$data['collection_session'] = 'PM';
						} else {
							$data['collection_session'] = 'AM';
						}

						$data['added_by'] = $this->session->get('log_id');
						$data['payment_mode'] = $payment_mode;
						$data['created_at'] = date('Y-m-d H:i:s');
						$data['updated_at'] = date('Y-m-d H:i:s');
						$data['paid_amount'] = $paid_amount;
						$data['mobile_code'] = $mble_phonecode;

						$res = $this->db->table('prasadam')->insert($data);
						$ins_id = $this->db->insertID();

						$payment_mode_details = $this->db->table('payment_mode')->where('id', $payment_mode)->get()->getRowArray();

						if ($res) {
							if (!empty($_POST['prasadam'])) {
								foreach ($_POST['prasadam'] as $key => $prasadam) {
									$data_prdm_book['prasadam_booking_id'] = $ins_id;
									$data_prdm_book['prasadam_id'] = $prasadam['id'];
									$data_prdm_book['quantity'] = $prasadam['quantity'];
									$data_prdm_book['created'] = date('Y-m-d H:i:s');
									$prsm_set = $this->db->table('prasadam_setting')->where('id', $prasadam['id'])->get()->getRowArray();
									$data_prdm_book['amount'] = $prsm_set['amount'];
									$amt = $prasadam['quantity'] * $prsm_set['amount'];
									$data_prdm_book['total_amount'] = $amt;
									$res_2 = $this->db->table('prasadam_booking_details')->insert($data_prdm_book);

									$settings = $this->db->table('settings')->where('type', 3)->where('setting_name', 'enable_madapalli')->get()->getRowArray();

									if ($settings['setting_value'] == 1) {
										$madapalli_details['date'] = $_POST['collection_date'];
										$madapalli_details['type'] = 1;
										$madapalli_details['booking_id'] = $ins_id;
										$madapalli_details['product_id'] = $prasadam['id'];
										$madapalli_details['quantity'] = $prasadam['quantity'];
										$madapalli_details['amount'] = $amt;
										$madapalli_details['session'] = $time_slot; // Use the actual session value
										$madapalli_details['serve_time'] = $data['serve_time'];
										$madapalli_details['customer_name'] = $customer_name;
										$madapalli_details['customer_mobile'] = $mble_phonecode . $mble_number;
										// $madapalli_details['pro_name_eng'] = $prsm_set['name_eng'];
										// $madapalli_details['pro_name_tamil'] = $prsm_set['name_tamil'];
										$madapalli_details['status'] = 0;
										$madapalli_details['created_by'] = $this->session->get('log_id');
										$madapalli_details['created_at'] = date('Y-m-d H:i:s');
										$madapalli_details['updated_at'] = date('Y-m-d H:i:s');
										$res_m1 = $this->db->table('madapalli_booking_details')->insert($madapalli_details);

										if ($res_m1) {
											$preparation_details = $this->db->table('madapalli_preparation_details')->where('date', $_POST['collection_date'])->where('type', 1)->get()->getResultArray();
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
													'date' => $_POST['collection_date'],
													'type' => 1,
													'session' => $time_slot,
													'product_id' => $madapalli_details['product_id'],
													'pro_name_eng' => $prsm_set['name_eng'],
													'pro_name_tamil' => $prsm_set['name_tamil'],
													'quantity' => $madapalli_details['quantity'],
													'status' => 0,
													'created_by' => $this->session->get('log_id'),
													'created_at' => date('Y-m-d H:i:s'),
													'updated_at' => date('Y-m-d H:i:s')
												];
												$this->db->table('madapalli_preparation_details')->insert($insert_data);
											}
										}
									}
								}
							}

							$payment_gateway_data = array();
							$payment_gateway_data['prasadam_id'] = $ins_id;
							$payment_gateway_data['pay_method'] = $payment_mode_details['name'];
							$this->db->table('prasadam_payment_gateway_datas')->insert($payment_gateway_data);
							$prasadam_payment_gateway_id = $this->db->insertID();

							// Insert payment details
							$pay_details = array();
							$payment_mode_details = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getRowArray();
							$pay_details['prasadam_id'] = $ins_id;
							$pay_details['is_repayment'] = 0;
							$pay_details['payment_mode_id'] = $payment_mode;
							$pay_details['paid_through'] = 'ADMIN';
							$pay_details['pay_status'] = 2;
							$pay_details['payment_mode_title'] = $payment_mode_details['name'];
							$pay_details['booking_ref_no'] = $data['ref_no'];

							// Fix: Set the correct amount based on payment type
							if ($data['payment_type'] == 'partial') {
								$pay_details['amount'] = $paid_amount;
							} else {
								$pay_details['amount'] = $tot_amt;
							}

							$pay_details['paid_date'] = date('Y-m-d');

							$this->requestmodel = new RequestModel();
							$ip = $this->requestmodel->getIpAddress();
							$pay_details['ip'] = $ip;
							if ($ip != 'unknown') {
								$ip_details = $this->requestmodel->getLocation($ip);
								$pay_details['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
								$pay_details['ip_details'] = json_encode($ip_details);
							}

							$res_3 = $this->db->table('prasadam_booked_pay_details')->insert($pay_details);

							$booking_ref_data = array();
							$booking_ref_data['paid_amount'] = $pay_details['amount'];
							$booking_ref_data['booking_status'] = 1;

							if ($data['payment_type'] == 'partial') {
								$booking_ref_data['payment_status'] = 1; // Partial Paid
							} elseif ($data['payment_type'] == 'full') {
								$booking_ref_data['payment_status'] = 2; // Full Paid
							} else {
								$booking_ref_data['payment_status'] = 0; // Pending
							}

							$this->db->table("prasadam")->where('id', $ins_id)->update($booking_ref_data);

							if ($booking_ref_data['payment_status'] == 2 || $booking_ref_data['payment_status'] == 1) {
								$this->account_migration($ins_id);

								// NEW: Send WhatsApp booking confirmation for completed payments
								if ($booking_ref_data['payment_status'] == 2) {
									$this->send_prasadam_booking_confirmation($ins_id);
								}
							}

							if ($res_3) {
								$this->session->setFlashdata('succ', 'Prasadam Added Successfully');
								$msg_data['succ'] = 'Prasadam Added Successfully';
								$msg_data['id'] = $ins_id;
							} else {
								$this->session->setFlashdata('fail', 'Please Try Again');
								$msg_data['err'] = 'Please Try Again!';
							}
						} else {
							$this->session->setFlashdata('fail', 'Please Try Again');
							$msg_data['err'] = 'Please Try Again!';
						}
					} else {
						$msg_data['err'] = 'Payment amount must be less than or equal to Total Amount.';
					}
				} else {
					$msg_data['err'] = 'Invalid Paid Amount.';
				}
			}
			$this->db->transComplete();
		} catch (Exception $e) {
			$this->db->transRollback();
			$msg_data['err'] = $e->getMessage();
		}

		echo json_encode($msg_data);
		exit();
	}

	public function get_customer_suggestions()
	{
		$search_term = $this->request->getGet('term');

		if (!empty($search_term)) {
			// Get unique customer names that start with the search term
			$names = $this->db->table('prasadam')
				->select('DISTINCT(customer_name) as name')
				->where('customer_name !=', '')
				->where('customer_name IS NOT NULL')
				->like('customer_name', $search_term, 'after') // Matches names starting with search term
				->orderBy('customer_name', 'ASC')
				->limit(10) // Limit to 10 suggestions
				->get()
				->getResultArray();

			// Format for autocomplete
			$suggestions = array();
			foreach ($names as $row) {
				$suggestions[] = array(
					'label' => $row['name'],
					'value' => $row['name']
				);
			}

			echo json_encode($suggestions);
		} else {
			echo json_encode(array());
		}
		exit;
	}

	public function get_customer_details()
	{
		$name = $this->request->getPost('customer_name');

		if (!empty($name)) {
			// Get the most recent record for this customer
			$customer = $this->db->table('prasadam')
				->where('customer_name', $name)
				->orderBy('id', 'DESC')
				->limit(1)
				->get()
				->getRowArray();

			if ($customer) {
				$response = array(
					'address' => $customer['address'],
					'ic_number' => $customer['ic_no'],
					'mobile' => $customer['mobile_no'],
					'mobile_code' => $customer['mobile_code'],
					'email' => $customer['email_id']
				);
				echo json_encode($response);
			} else {
				echo json_encode(array());
			}
		} else {
			echo json_encode(array());
		}
		exit;
	}

	public function gtpaymentdata()
	{
		$id = $_POST['id'];
		$res = $this->db->table("prasadam")->where("id", $id)->get()->getRowArray();
		$amt = $res['amount'];
		$data['amt'] = $amt;
		$res1 = $this->db->table("prasadam_booked_pay_details")->selectSum('amount')->where("prasadam_id", $id)->get()->getRowArray();
		$paid_amount = $res1['amount'];
		$data['paid_amount'] = $paid_amount;
		$data['bal_amount'] = $amt - $paid_amount;

		echo json_encode($data);
	}

	public function save_repayment()
	{
		if (!empty($_POST['payment_mode']) && !empty($_POST['pay_amount']) && !empty($_POST['booking_id'])) {
			$date = $_POST['date'];
			$pay_amount = $_POST['pay_amount'];
			$payment_mode = $_POST['payment_mode'];
			$booking_id = $_POST['booking_id'];
			$count = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getNumRows();
			if ($count > 0) {
				$payment_mode_details = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getRowArray();
				$annathanam_details = $this->db->table("prasadam")->where('id', $booking_id)->get()->getRowArray();
				if ($annathanam_details['amount'] >= ($annathanam_details['paid_amount'] + $pay_amount)) {
					$booking_payment_ins_data = array();
					$booking_payment_ins_data['prasadam_id'] = $booking_id;
					$booking_payment_ins_data['booking_ref_no'] = $annathanam_details['ref_no'];
					$booking_payment_ins_data['is_repayment'] = 1;
					$booking_payment_ins_data['payment_mode_id'] = $payment_mode;
					$booking_payment_ins_data['paid_date'] = !empty($date) ? $date : date('Y-m-d');
					$booking_payment_ins_data['amount'] = $pay_amount;
					$booking_payment_ins_data['payment_mode_title'] = $payment_mode_details['name'];
					$paid_through = 'COUNTER';
					if ($paid_through != 'ADMIN' && $paid_through != 'COUNTER') $booking_payment_ins_data['payment_ref_no'] = $ubayam_details['ref_no'];
					$booking_payment_ins_data['paid_through'] = $paid_through;
					$booking_payment_ins_data['pay_status'] = ($paid_through == 'ADMIN' || $paid_through == 'COUNTER') ? 2 : 1;
					$this->requestmodel = new RequestModel();
					$ip = $this->requestmodel->getIpAddress();
					$booking_payment_ins_data['ip'] = $ip;
					if ($ip != 'unknown') {
						$ip_details = $this->requestmodel->getLocation($ip);
						$booking_payment_ins_data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
						$booking_payment_ins_data['ip_details'] = json_encode($ip_details);
					}
					// $this->paid_amount += $booking_payment_ins_data['amount'];
					$res = $this->db->table("prasadam_booked_pay_details")->insert($booking_payment_ins_data);
					$booked_pay_id = $this->db->insertID();
					$this->db->query("UPDATE prasadam SET paid_amount = paid_amount + ? WHERE id = ?", [$pay_amount, $booking_id]);
					$query = $this->db->table('prasadam')->where('id', $booking_id)->get()->getRowArray();
					if ($query['amount'] == $query['paid_amount']) {
						$this->db->query("UPDATE prasadam SET payment_status = 2 WHERE id = ?", [$booking_id]);

						// NEW: Send WhatsApp confirmation when payment is completed
						$this->send_prasadam_booking_confirmation($booking_id);
					}
					$this->partial_account_migration($booked_pay_id);

					echo json_encode(['status' => true, 'message' => 'Repayment saved successfully.']);
				} else {
					echo json_encode(['status' => false, 'message' => 'Payment amount not exceed Total.']);
				}
			} else {
				echo json_encode(['status' => false, 'message' => 'Failed to save repayment.']);
			}
		} else {
			echo json_encode(['status' => false, 'message' => 'Failed to save repayment.']);
		}
		exit;
	}

	public function partial_account_migration($booked_pay_id)
	{
		$succ = true;
		$yr = date('Y');
		$mon = date('m');
		$booked_pay_details_cnt = $this->db->table("prasadam_booked_pay_details")->where("id", $booked_pay_id)->get()->getNumRows();
		if ($booked_pay_details_cnt > 0) {
			$booked_pay_details = $this->db->table("prasadam_booked_pay_details")->where("id", $booked_pay_id)->get()->getResultArray();
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
			$booking_id = $booked_pay_details[0]['booking_id'];
			$prasadam = $this->db->table("prasadam")->where("id", $booking_id)->get()->getRowArray();
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
					$entries['narration'] = 'Prasadam(' . $prasadam['ref_no'] . ')' . "\n" . 'name:' . $prasadam['customer_name'] . "\n" . 'NRIC:' . $prasadam['ic_number'] . "\n" . 'email:' . $prasadam['email'] . "\n";
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
						$eitems_hall_book['details'] = 'Prasadam Amount';
						$this->db->table('entryitems')->insert($eitems_hall_book);
						// PETTY CASH => Debit 
						$eitems_cash_led['entry_id'] = $en_id;
						$eitems_cash_led['ledger_id'] = $paymentmode['ledger_id'];
						$eitems_cash_led['amount'] = $row['amount'];
						$eitems_cash_led['dc'] = 'D';
						$eitems_cash_led['details'] = 'Prasadam Amount';
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

	public function account_migration($ins_id)
	{
		$yr = date('Y');
		$mon = date('m');
		$data = $this->db->table('prasadam')->where('id', $ins_id)->get()->getRowArray();
		$booking_settings = $this->db->table('booking_setting')->get()->getResultArray();
		$setting = array();
		if (count($booking_settings) > 0) {
			foreach ($booking_settings as $bs) {
				$setting[$bs['meta_key']] = $bs['meta_value'];
			}
		}
		$payment_mode_details = $this->db->table('payment_mode')->where('id', $data['payment_mode'])->get()->getRowArray();
		$sales_group = $this->db->table('groups')->where('code', '4000')->get()->getRowArray();

		if (!empty($sales_group)) {
			$sls_id = $sales_group['id'];
		} else {
			$sls1['parent_id'] = 0;
			$sls1['name'] = 'Sales';
			$sls1['code'] = '4000';
			$sls1['added_by'] = $this->session->get('log_id');
			$this->db->table('groups')->insert($sls1);
			$sls_id = $this->db->insertID();
		}

		$td_ledger = $this->db->table('ledgers')->where('name', 'TRADE RECEIVABLE')->where('group_id', 3)->where('left_code', '1200')->get()->getRowArray();
		if (!empty($td_ledger)) {
			$trade_receivable_id = $td_ledger['id'];
		} else {
			$cled1['group_id'] = 3;
			$cled1['name'] = 'TRADE RECEIVABLE';
			$cled1['code'] = '1200/005';
			$cled1['op_balance'] = '0';
			$cled1['op_balance_dc'] = 'D';
			$cled1['left_code'] = '1200';
			$cled1['right_code'] = '005';
			$this->db->table('ledgers')->insert($cled1);
			$trade_receivable_id = $this->db->insertID();
		}
		$number = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
		if (empty($number) && empty($number1)) $num = 1;
		else $num = $number['number'] + 1;

		$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();
		$entries['entry_code'] = 'JOR' . date('y', strtotime($data['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
		$entries['date'] = date("Y-m-d", strtotime($data['date']));
		$entries['number'] = $num;
		$entries['entrytype_id'] = '4';
		$entries['dr_total'] = $data['sub_total']; // Assuming 'total_amount' is the field for total booking amount
		$entries['cr_total'] = $data['sub_total'];
		$entries['narration'] = 'Prasadam(' . $data['ref_no'] . ')' . "\n" . 'name:' . $data['customer_name'] . "\n" . 'NRIC:' . $data['ic_no'] . "\n" . 'email:' . $data['email_id'] . "\n";
		$entries['inv_id'] = $ins_id;
		$entries['type'] = '10';
		$ent = $this->db->table('entries')->insert($entries);
		$en_id1 = $this->db->insertID();

		$prasadam_booking_details = $this->db->table('prasadam_booking_details')->where('prasadam_booking_id', $ins_id)->get()->getResultArray();
		foreach ($prasadam_booking_details as $pbd) {
			$prasadam_details = $this->db->table('prasadam_setting')->where('id', $pbd['prasadam_id'])->get()->getRowArray();

			if (!empty($prasadam_details['ledger_id'])) {
				$dr_id = $prasadam_details['ledger_id'];
			} else {
				$ledger1 = $this->db->table('ledgers')->where('name', 'All Sales')->where('group_id', $sls_id)->get()->getRowArray();
				if (!empty($ledger1)) {
					$dr_id = $ledger1['id'];
				} else {
					$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '4913')->orderBy('right_code', 'desc')->get()->getRowArray();
					$set_right_code = (int) $right_code['right_code'] + 1;
					$set_right_code = sprintf("%04d", $set_right_code);
					$led1['group_id'] = $sls_id;
					$led1['name'] = 'All Sales';
					$led1['left_code'] = '4913';
					$led1['right_code'] = $set_right_code;
					$led1['op_balance'] = '0';
					$led1['op_balance_dc'] = 'D';
					$led_ins1 = $this->db->table('ledgers')->insert($led1);
					$dr_id = $this->db->insertID();
				}
			}
			// Debit the Product's Ledger (dr_id)
			$eitems_d['entry_id'] = $en_id1;
			$eitems_d['ledger_id'] = $dr_id;
			$eitems_d['amount'] = $pbd['total_amount'];
			$eitems_d['details'] = 'Prasadam(' . $data['ref_no'] . ')';
			$eitems_d['dc'] = 'C';
			$cr_res = $this->db->table('entryitems')->insert($eitems_d);
			$debtor_amount += $pbd['total_amount'];
		}

		// Credit Trade Receivable (trade_receivable_id)
		$eitems_c['entry_id'] = $en_id1;
		$eitems_c['ledger_id'] = $trade_receivable_id;
		$eitems_c['amount'] = $debtor_amount;
		$eitems_c['details'] = 'Prasadam(' . $data['ref_no'] . ')';
		$eitems_c['dc'] = 'D';
		$deb_res = $this->db->table('entryitems')->insert($eitems_c);

		$paid_amount = $pbd['total_amount'];


		if (!empty($data['discount_amount'])) {
			$number = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
			if (empty($number) && empty($number1)) $num = 1;
			else $num = $number['number'] + 1;

			$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();
			$entries['entry_code'] = 'JOR' . date('y', strtotime($data['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
			$entries['date'] = date("Y-m-d", strtotime($data['date']));
			$entries['number'] = $num;
			$entries['entrytype_id'] = '4';
			$entries['dr_total'] = $data['discount_amount']; // Assuming 'total_amount' is the field for total booking amount
			$entries['cr_total'] = $data['discount_amount'];
			$entries['narration'] = 'Prasadam(' . $data['ref_no'] . ')' . "\n" . 'name:' . $data['customer_name'] . "\n" . 'NRIC:' . $data['ic_no'] . "\n" . 'email:' . $data['email_id'] . "\n";
			$entries['inv_id'] = $ins_id;
			$entries['type'] = '10';
			$ent = $this->db->table('entries')->insert($entries);
			$en_id2 = $this->db->insertID();

			$eitems_c = array();
			$eitems_c['entry_id'] = $en_id2;
			$eitems_c['ledger_id'] = $trade_receivable_id;
			$eitems_c['amount'] = $data['discount_amount'];
			$eitems_c['details'] = 'Discount for Prasadam(' . $data['ref_no'] . ')';
			$eitems_c['dc'] = 'C';
			$deb_res = $this->db->table('entryitems')->insert($eitems_c);

			$eitems_disc_ent = array();
			$discount_ledger_id = !empty($setting['discount_prasadam_ledger_id']) ? $setting['discount_prasadam_ledger_id'] : 169;
			$eitems_disc_ent['entry_id'] = $en_id2;
			$eitems_disc_ent['ledger_id'] = $discount_ledger_id;
			$eitems_disc_ent['amount'] = $data['discount_amount'];
			// $eitems_disc_ent['is_discount'] = 1;
			$eitems_disc_ent['dc'] = 'D';
			$eitems_disc_ent['details'] = 'Discount for Prasadam(' . $data['ref_no'] . ')';
			$this->db->table('entryitems')->insert($eitems_disc_ent);
			// $tot_amount += $prasadam['discount_amount'];
			$debtor_amount -= $data['discount_amount'];
		}

		$prasadam_booked_count = $this->db->table('prasadam_booked_pay_details')->where('prasadam_id', $ins_id)->get()->getNumRows();
		if ($prasadam_booked_count > 0) {
			$prasadam_booked_detail = $this->db->table('prasadam_booked_pay_details')->where('prasadam_id', $ins_id)->get()->getRowArray();

			$cr_id = $payment_mode_details['ledger_id'];
			$number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
			if (empty($number) && empty($number1)) $num = 1;
			else $num = $number['number'] + 1;

			$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();
			$entries['entry_code'] = 'REC' . date('y', strtotime($data['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
			$entries['date'] = date("Y-m-d", strtotime($data['date']));
			$entries['number'] = $num;
			$entries['entrytype_id'] = '1';
			$entries['dr_total'] = $prasadam_booked_detail['amount']; // Assuming 'total_amount' is the field for total booking amount
			$entries['cr_total'] = $prasadam_booked_detail['amount'];
			$entries['narration'] = 'Prasadam(' . $data['ref_no'] . ')' . "\n" . 'name:' . $data['customer_name'] . "\n" . 'NRIC:' . $data['ic_no'] . "\n" . 'email:' . $data['email_id'] . "\n";
			$entries['inv_id'] = $ins_id;
			$entries['type'] = '10';
			$ent = $this->db->table('entries')->insert($entries);
			$en_id2 = $this->db->insertID();

			$eitems_d['entry_id'] = $en_id2;
			$eitems_d['ledger_id'] = $trade_receivable_id;
			$eitems_d['amount'] = $prasadam_booked_detail['amount'];
			$eitems_d['details'] = 'Prasadam(' . $data['ref_no'] . ')';
			$eitems_d['dc'] = 'C';
			$cr_res = $this->db->table('entryitems')->insert($eitems_d);

			// Credit Payment Mode (cr_id)
			$eitems_c['entry_id'] = $en_id2;
			$eitems_c['ledger_id'] = $cr_id;
			$eitems_c['amount'] = $prasadam_booked_detail['amount'];
			$eitems_c['details'] = 'Prasadam(' . $data['ref_no'] . ')';
			$eitems_c['dc'] = 'D';
			$deb_res = $this->db->table('entryitems')->insert($eitems_c);
		}
	}

	public function print_booking($prsm_id)
	{
		$id = $prsm_id;
		$data['data'] = $this->db->table('prasadam')->select('prasadam.*')->where('prasadam.id', $id)->get()->getRowArray();
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		$data['booking_details'] = $this->db->table('prasadam_booking_details')
			->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
			->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil')
			->where('prasadam_booking_details.prasadam_booking_id', $id)
			->get()->getResultArray();
		$data['pay_details'] = $this->db->table("prasadam_booked_pay_details")->where("prasadam_id", $id)->get()->getResultArray();
		echo view('frontend/prasadam/print_page_a4', $data);
	}

	public function get_available_prasadam()
	{
		$prasadamdate = $_POST['prasadamdate'];
		$ress = $this->db->table("prasadam")->select('prasadam_master_id')->where("date", $prasadamdate)->get()->getResultArray();
		$cmeterts = array();
		$implode_arry = array();
		foreach ($ress as $row) {
			$cmeterts[] = $row['prasadam_master_id'];
		}
		$prasadam_booking_slots = $this->db->table('prasadam_master')->where("status", 1)->where("date", $prasadamdate)->get()->getResultArray();
		$html = "<option value='' >--Select prasadam--</option>";
		foreach ($prasadam_booking_slots as $prasadam_booking_slot) {
			if (is_array($cmeterts) && in_array($prasadam_booking_slot['id'], $cmeterts)) {
				$disabled = "disabled";
				$selected = "selected";
			} else {
				$disabled = "";
				$selected = "";
			}
			$html .= '<option value=' . $prasadam_booking_slot['id'] . ' ' . $disabled . ' ' . $selected . ' >' . $prasadam_booking_slot['name'] . '</option>';
		}
		echo $html;
	}

	public function getbillno()
	{
		$yr = date('Y', strtotime($_POST['date']));
		$mon = date('m', strtotime($_POST['date']));
		$query = $this->db->query("SELECT ref_no FROM prasadam where id=(select max(id) from prasadam where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();
		echo 'PR' . date('y', strtotime($_POST['date'])) . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
	}

	public function show_product()
	{
		$prasadam_settings = $this->db->query("SELECT * FROM prasadam_setting WHERE name_eng LIKE '%" . $_POST['prod'] . "%' order by name_eng asc")->getResultArray();
		foreach ($prasadam_settings as $key => $value) {
			if (!empty($value)) {
				foreach ($value as $row) {
					$tr_row[] .= '<div class="col-md-3" style="padding-left: 0px;">
									<div class="prod" id="prod' . $row['id'] . '" data-id="prod' . $row['id'] . '" onclick="addtocart(' . $row['id'] . ')"><img src="' . base_url() . '/uploads/prasadam_setting/' . $row['image'] . '" width="200" height="80" alt="image" />
										<!--<div class="vl"></div>-->
										<div class="detail">
										<h5 id="nm_' . $row['id'] . '" data-id="' . $row['id'] . '"> ' . $row['name_tamil'] . ' <br>' . $row['name_eng'] . '</h5><h4 id="amt_' . $row['id'] . '" data-id="' . ($row['amount']) . '" >RM ' . number_format((float) ($row['amount']), 2) . '</h4>
										</div>
									</div>
								</div>';
				}
			}
		}

		$data['row'] = $tr_row;
		echo json_encode($data);
	}

	// ===================================================================
	// NEW: ULTRAMSG WHATSAPP INTEGRATION METHODS
	// ===================================================================

	/**
	 * Send WhatsApp booking confirmation with PDF
	 */
	public function send_prasadam_booking_confirmation($prasadam_id)
	{
		$prasadam = $this->db->table('prasadam')
			->select('prasadam.*')
			->where('prasadam.id', $prasadam_id)
			->get()->getRowArray();

		if (empty($prasadam['mobile_no']) || $prasadam['payment_status'] != 2) {
			return false;
		}

		// Check if WhatsApp already sent to avoid duplicates
		$whatsapp_sent = $this->check_whatsapp_sent($prasadam_id, 'booking_confirmation');
		if ($whatsapp_sent) {
			return false;
		}

		// Generate PDF for WhatsApp
		$pdf_path = $this->generate_prasadam_pdf_for_whatsapp($prasadam_id);

		if (!$pdf_path) {
			return false;
		}

		// Prepare message parameters
		$params = [
			':devotee' => $prasadam['customer_name'],
			':ref_no' => $prasadam['ref_no'],
			':booking_date' => date('d M Y', strtotime($prasadam['date'])),
			':collection_date' => date('d M Y', strtotime($prasadam['collection_date'])),
			':collection_time' => $prasadam['serve_time'],
			':amount' => number_format($prasadam['total_amount'], 2)
		];

		// Media attachment
		$media = [
			'url' => base_url() . '/' . $pdf_path,
			'filename' => 'prasadam_booking_' . $prasadam['ref_no'] . '.pdf'
		];

		// Send WhatsApp message
		$numbers = [$prasadam['mobile_no']];
		$response = whatsapp_ultramsg($numbers, 'prasadam_booking_confirmation', $params, $media);

		// Log WhatsApp activity
		$this->log_whatsapp_activity($prasadam_id, 'booking_confirmation', $response);

		return $response;
	}

	/**
	 * Generate PDF specifically for WhatsApp sharing
	 */
	private function generate_prasadam_pdf_for_whatsapp($prasadam_id)
	{
		$data['data'] = $this->db->table('prasadam')
			->select('prasadam.*')
			->where('prasadam.id', $prasadam_id)
			->get()->getRowArray();

		$tmpid = 1;
		$data['temp_details'] = $this->db->table('admin_profile')
			->where('id', $tmpid)
			->get()->getRowArray();

		$data['booking_details'] = $this->db->table('prasadam_booking_details')
			->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
			->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil')
			->where('prasadam_booking_details.prasadam_booking_id', $prasadam_id)
			->get()->getResultArray();

		$data['pay_details'] = $this->db->table("prasadam_booked_pay_details")
			->where("prasadam_id", $prasadam_id)
			->get()->getResultArray();

		// Get deity info
		$diety = $this->db->table('archanai_diety')
			->where('id', $data['data']['diety_id'])
			->get()->getRowArray();
		$data['data']['diety_name'] = $diety ? $diety['name'] : '';

		try {
			// Use existing PDF template but create WhatsApp version
			$html = view('prasadam/pdf', $data);

			$options = new Options();
			$options->set('isHtml5ParserEnabled', true);
			$options->set('isRemoteEnabled', true);
			$options->set('isPhpEnabled', true);

			$dompdf = new Dompdf($options);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();

			$upload_path = 'uploads/prasadam_pdfs/';
			if (!is_dir($upload_path)) {
				mkdir($upload_path, 0777, true);
			}

			$filename = 'prasadam_booking_' . $prasadam_id . '_' . time() . '.pdf';
			$file_path = $upload_path . $filename;

			file_put_contents($file_path, $dompdf->output());

			return $file_path;
		} catch (Exception $e) {
			log_message('error', 'PDF Generation Error: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Send reminder WhatsApp message (3 days before event)
	 */
	public function send_prasadam_reminder($prasadam_id)
	{
		$prasadam = $this->db->table('prasadam')
			->select('prasadam.*')
			->where('prasadam.id', $prasadam_id)
			->get()->getRowArray();

		if (empty($prasadam['mobile_no']) || $prasadam['payment_status'] != 2) {
			return false;
		}

		// Check if reminder already sent
		$reminder_sent = $this->check_whatsapp_sent($prasadam_id, 'reminder');
		if ($reminder_sent) {
			return false;
		}

		$params = [
			':devotee' => $prasadam['customer_name'],
			':collection_date' => date('d M Y', strtotime($prasadam['collection_date'])),
			':collection_time' => $prasadam['serve_time'],
			':ref_no' => $prasadam['ref_no']
		];

		$numbers = [$prasadam['mobile_no']];
		$response = whatsapp_ultramsg($numbers, 'prasadam_reminder', $params);

		$this->log_whatsapp_activity($prasadam_id, 'reminder', $response);

		return $response;
	}

	/**
	 * Send thank you WhatsApp message (after event)
	 */
	public function send_prasadam_thank_you($prasadam_id)
	{
		$prasadam = $this->db->table('prasadam')
			->select('prasadam.*')
			->where('prasadam.id', $prasadam_id)
			->get()->getRowArray();

		if (empty($prasadam['mobile_no']) || $prasadam['payment_status'] != 2) {
			return false;
		}

		// Check if thank you already sent
		$thankyou_sent = $this->check_whatsapp_sent($prasadam_id, 'thank_you');
		if ($thankyou_sent) {
			return false;
		}

		$temple_details = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

		$params = [
			':devotee' => $prasadam['customer_name'],
			':temple_name' => $temple_details['name'] ?? 'Temple'
		];

		$numbers = [$prasadam['mobile_no']];
		$response = whatsapp_ultramsg($numbers, 'prasadam_thank_you', $params);

		$this->log_whatsapp_activity($prasadam_id, 'thank_you', $response);

		return $response;
	}

	/**
	 * Check if WhatsApp message already sent
	 */
	private function check_whatsapp_sent($prasadam_id, $message_type)
	{
		// Check if prasadam_whatsapp_log table exists first
		$tables = $this->db->listTables();
		if (!in_array('prasadam_whatsapp_log', $tables)) {
			return false;
		}

		$sent = $this->db->table('prasadam_whatsapp_log')
			->where('prasadam_id', $prasadam_id)
			->where('message_type', $message_type)
			->where('status', 'sent')
			->get()->getRowArray();

		return !empty($sent);
	}

	/**
	 * Log WhatsApp activity
	 */
	private function log_whatsapp_activity($prasadam_id, $message_type, $response)
	{
		// Check if prasadam_whatsapp_log table exists first
		$tables = $this->db->listTables();
		if (!in_array('prasadam_whatsapp_log', $tables)) {
			// Table doesn't exist, skip logging (or create it)
			log_message('warning', 'prasadam_whatsapp_log table does not exist');
			return;
		}

		$log_data = [
			'prasadam_id' => $prasadam_id,
			'message_type' => $message_type,
			'response' => json_encode($response),
			'status' => isset($response['sent']) && $response['sent'] ? 'sent' : 'failed',
			'created_at' => date('Y-m-d H:i:s')
		];

		try {
			$this->db->table('prasadam_whatsapp_log')->insert($log_data);
		} catch (Exception $e) {
			log_message('error', 'Failed to log WhatsApp activity: ' . $e->getMessage());
		}
	}

	/**
	 * Cron job to send reminders (run daily)
	 */
	public function admin_send_prasadam_reminders_cron()
	{
		// Security check
		$secret_key = 'prasadam_admin_cron_2024_secret';
		$provided_key = $this->request->getGet('key') ?: $this->request->getPost('key');

		if ($provided_key !== $secret_key) {
			http_response_code(403);
			echo json_encode(['error' => 'Unauthorized access']);
			return;
		}

		$reminder_date = date('Y-m-d', strtotime('+3 days'));

		// Get prasadams without reminder sent
		$builder = $this->db->table('prasadam')
			->select('id')
			->where('collection_date', $reminder_date)
			->where('payment_status', 2);

		// Only exclude if log table exists
		$tables = $this->db->listTables();
		if (in_array('prasadam_whatsapp_log', $tables)) {
			$builder->whereNotIn('id', function ($sub) {
				return $sub->select('prasadam_id')
					->from('prasadam_whatsapp_log')
					->where('message_type', 'reminder')
					->where('status', 'sent');
			});
		}

		$prasadams = $builder->get()->getResultArray();

		$sent_count = 0;
		$failed_count = 0;
		$results = [];

		foreach ($prasadams as $prasadam) {
			if ($this->send_prasadam_reminder($prasadam['id'])) {
				$sent_count++;
				$results[] = "Reminder sent for booking ID: " . $prasadam['id'];
			} else {
				$failed_count++;
				$results[] = "Failed to send reminder for booking ID: " . $prasadam['id'];
			}
			// Add small delay to avoid rate limiting
			sleep(1);
		}

		$response = [
			'status' => 'completed',
			'sent_count' => $sent_count,
			'failed_count' => $failed_count,
			'date' => date('Y-m-d H:i:s'),
			'reminder_date' => $reminder_date,
			'details' => $results
		];

		log_message('info', "Admin Prasadam reminders cron: " . json_encode($response));

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	/**
	 * Send thank you messages - HTTP accessible with secret key
	 */
	public function admin_send_prasadam_thankyou_cron()
	{
		$secret_key = 'temple_prasadam_admin_thankyou_2024';
		$provided_key = $this->request->getGet('key') ?: $this->request->getPost('key');

		if ($provided_key !== $secret_key) {
			http_response_code(403);
			echo json_encode(['error' => 'Unauthorized access']);
			return;
		}

		$today = date('Y-m-d');
		$current_hour = (int)date('H');

		// Determine which slots to process based on current time
		$slots_to_process = [];

		if ($current_hour >= 14) { // 2:00 PM or later
			$slots_to_process[] = 'Breakfast';
		}

		if ($current_hour >= 18) { // 6:00 PM or later  
			$slots_to_process[] = 'Lunch';
		}

		if ($current_hour >= 22) { // 10:00 PM or later
			$slots_to_process[] = 'Dinner';
		}

		if (empty($slots_to_process)) {
			echo json_encode([
				'status' => 'skipped',
				'message' => 'Too early to send any thank you messages',
				'current_hour' => $current_hour,
				'date' => date('Y-m-d H:i:s')
			]);
			return;
		}

		// Get bookings for today that match our slot criteria
		$builder = $this->db->table('prasadam')
			->select('id, session, serve_time, customer_name')
			->where('collection_date', $today)
			->where('payment_status', 2)
			->whereIn('session', $slots_to_process);

		// Only exclude if log table exists
		$tables = $this->db->listTables();
		if (in_array('prasadam_whatsapp_log', $tables)) {
			$builder->whereNotIn('id', function ($sub) {
				return $sub->select('prasadam_id')
					->from('prasadam_whatsapp_log')
					->where('message_type', 'thank_you')
					->where('status', 'sent');
			});
		}

		$prasadams = $builder->get()->getResultArray();

		$sent_count = 0;
		$results = [];
		$processed_slots = [];

		foreach ($prasadams as $prasadam) {
			if ($this->send_prasadam_thank_you($prasadam['id'])) {
				$sent_count++;
				$results[] = "Thank you sent for booking ID: " . $prasadam['id'] . " (Session: " . $prasadam['session'] . ")";

				if (!in_array($prasadam['session'], $processed_slots)) {
					$processed_slots[] = $prasadam['session'];
				}
			}
			sleep(1); // Rate limiting
		}

		$response = [
			'status' => 'completed',
			'sent_count' => $sent_count,
			'current_hour' => $current_hour,
			'slots_processed' => $processed_slots,
			'available_slots' => $slots_to_process,
			'date' => date('Y-m-d H:i:s'),
			'collection_date' => $today,
			'details' => $results
		];

		log_message('info', "Admin Prasadam thank you cron: " . json_encode($response));

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	/**
	 * Test cron job connection
	 */
	public function admin_test_cron_connection()
	{
		$secret_key = 'temple_admin_cron_2024_secure';
		$provided_key = $this->request->getGet('key') ?: $this->request->getPost('key');

		if ($provided_key !== $secret_key) {
			http_response_code(403);
			echo json_encode(['error' => 'Unauthorized access']);
			return;
		}

		$response = [
			'status' => 'success',
			'message' => 'Admin Prasadam Cron job connection working!',
			'server_time' => date('Y-m-d H:i:s'),
			'php_version' => PHP_VERSION,
			'controller' => 'Prasadam (Admin)'
		];

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	/**
	 * Test WhatsApp integration
	 */
	public function test_whatsapp()
	{
		$test_number = ["+919655500357"]; // Replace with your test number
		$params = [
			':devotee' => 'Test Admin User',
			':amount' => '25.00',
			':ref_no' => 'PR240001',
			':booking_date' => date('d M Y'),
			':collection_date' => date('d M Y', strtotime('+1 day')),
			':collection_time' => '12:00 PM'
		];

		$response = whatsapp_ultramsg($test_number, 'prasadam_booking_confirmation', $params);

		echo "<pre>";
		print_r($response);
		echo "</pre>";
	}

	// ===================================================================
	// EXISTING METHODS (KEEPING OLD send_whatsapp_msg FOR COMPATIBILITY)
	// ===================================================================

	public function send_whatsapp_msg($id)
	{
		$data['qry1'] = $prasadam = $this->db->table('prasadam')
			->select('prasadam.*')
			->where('prasadam.id', $id)
			->get()->getRowArray();
		$tmpid = 1;
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		$data['qry1_payfor'] = $this->db->table('prasadam_booking_details')
			->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
			->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil')
			->where('prasadam_booking_details.prasadam_booking_id', $id)
			->get()->getResultArray();
		$url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
		$data['qrcdoee'] = qrcode_generation($id, $url, 95, 95);
		$tmpid = 1;
		$data['temple_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		if (!empty($prasadam['mobile_no'])) {
			$html = view('prasadam/pdf', $data);
			$options = new Options();
			$options->set('isHtml5ParserEnabled', true);
			$options->set(array('isRemoteEnabled' => true));
			$options->set('isPhpEnabled', true);
			$dompdf = new Dompdf($options);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			$filePath = FCPATH . 'uploads/documents/invoice_prasadam_' . $id . '.pdf';

			file_put_contents($filePath, $dompdf->output());
			$message_params = array();
			$message_params[] = date('d M, Y', strtotime($prasadam['date']));
			$message_params[] = date('d M, Y', strtotime($prasadam['collection_date']));
			$message_params[] = date('h:i A', strtotime($prasadam['collection_date'] . ' ' . $prasadam['start_time']));
			$message_params[] = $prasadam['amount'];
			$media['url'] = base_url() . '/uploads/documents/invoice_prasadam_' . $id . '.pdf';
			$media['filename'] = 'prasadam_invoice.pdf';
			$mobile_number = $prasadam['mobile_no'];
			//$mobile_number = '+919092615446';
			// print_r($mobile_number);
			// print_r($message_params);
			// print_r($media);
			// die; 
			$whatsapp_resp = whatsapp_aisensy($mobile_number, $message_params, 'prasadam_live', $media);
			//print_r($whatsapp_resp);
		}
	}

	// ALL OTHER EXISTING METHODS REMAIN UNCHANGED
	public function item_count_report()
	{
		$fdate = !empty($_REQUEST['fdate']) ? $_REQUEST['fdate'] : date('Y-m-01');
		$tdate = !empty($_REQUEST['tdate']) ? $_REQUEST['tdate'] : date('Y-m-d');
		$user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
		$product_id = !empty($_REQUEST['prasadam_id']) ? $_REQUEST['prasadam_id'] : '';
		$builder = $this->db->table('prasadam ab')
			->select("name_eng as prasadam_name,abd.prasadam_id as prasadam_id,ref_code,shortcode,min(sep_pras_sl_no) as sl_no,max(sep_pras_sl_no_to) as sl_to,sum(quantity) as quantity,sum(total_amount) as amount")
			->join("prasadam_booking_details abd", "ab.id = abd.prasadam_booking_id")
			->join("prasadam_setting a", "a.id = abd.prasadam_id")
			->where("payment_status", 2)
			->where("date BETWEEN '$fdate' AND '$tdate'")
			->groupby("prasadam_id,ref_code");
		if (!empty($user_id)) {
			$builder->where('ab.entry_by', $user_id);
		}
		if (!empty($product_id)) {
			$builder->where('abd.prasadam_id', $product_id);
		}
		$datas = $builder->get()
			->getResultArray();
		// echo $this->db->getLastQuery();
		// die("t");

		$paymodes = [];
		$datapaymode = $this->db->table("payment_mode")
			->get()
			->getResultArray();
		foreach ($datapaymode as $iter) {
			$paymodes[$iter["shortcode"]] = $iter["name"];
		}

		$res = [];
		foreach ($datas as $iter) {
			if (strlen($iter["ref_code"]) != 6) continue;

			$typ = substr($iter["ref_code"], 2, 2);
			if ($typ != "CT" && $typ != "KI") //counter or kios
				continue;

			$paymode = substr($iter["ref_code"], 4, 2); //paymentmode
			$paymode_name = (isset($paymodes[$paymode]) ? $paymodes[$paymode] : $paymode);
			//$deity_name = (isset($deitys[$iter["deity_id"]])?$deitys[$iter["deity_id"]]:$iter["deity_id"]);
			$res[($typ == "CT" ? "Counter" : "KIOSK")][$paymode_name][$iter["prasadam_id"]] = $iter;
		}
		//->select('ab.*, pm.name as payment_mode_name')->join('archanai_payment_gateway_datas abgd', 'abgd.archanai_booking_id = ab.id', 'left')->join('payment_mode pm', 'pm.id = abgd.payment_mode', 'left')->where("date BETWEEN '$fdate' AND '$tdate'")->where('payment_status', 3)->where("is_refund",1)->orderBy('ab.date', 'DESC');
		$data['list'] = $res;
		$data['users'] = $this->db->table('login')->select('id,name,member_comes')->where('member_comes', 'counter')->get()->getResultArray();
		$data['products'] = $this->db->table('prasadam_setting')->get()->getResultArray();
		$data['fdate'] = $fdate;
		$data['tdate'] = $tdate;
		$data['user_id'] = $user_id;
		$data['product_id'] = $product_id;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('report/prasadam_item_count_report', $data);
		echo view('template/footer');
	}

	public function item_count_report_print($arch_book_id = 0)
	{
		global $lang;
		if (!$this->model->list_validate('archanai_report')) {
			header('Location: ' . base_url() . '/dashboard');
		}
		$data = array();
		$from_date = !empty($_REQUEST['fdt']) ? $_REQUEST['fdt'] : date('Y-m-01');
		$to_date = !empty($_REQUEST['tdt']) ? $_REQUEST['tdt'] : date('Y-m-m');
		$user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
		$product_id = !empty($_REQUEST['product_id']) ? $_REQUEST['product_id'] : '';

		$builder = $this->db->table('prasadam ab')
			->select("name_eng as prasadam_name,abd.prasadam_id as prasadam_id,ref_code,shortcode,min(sep_pras_sl_no) as sl_no,max(sep_pras_sl_no_to) as sl_to,sum(quantity) as quantity,sum(total_amount) as amount")
			->join("prasadam_booking_details abd", "ab.id = abd.prasadam_booking_id")
			->join("prasadam_setting a", "a.id = abd.prasadam_id")
			->where("payment_status", 2)
			->where("date BETWEEN '$from_date' AND '$to_date'")
			->groupby("prasadam_id,ref_code");
		if (!empty($user_id)) {
			$builder->where('ab.entry_by', $user_id);
		}
		if (!empty($product_id)) {
			$builder->where('abd.prasadam_id', $product_id);
		}
		$datas = $builder->get()
			->getResultArray();

		$paymodes = [];
		$datapaymode = $this->db->table("payment_mode")
			->get()
			->getResultArray();
		foreach ($datapaymode as $iter) {
			$paymodes[$iter["shortcode"]] = $iter["name"];
		}

		$res = [];
		foreach ($datas as $iter) {
			if (strlen($iter["ref_code"]) != 6) continue;

			$typ = substr($iter["ref_code"], 2, 2);
			if ($typ != "CT" && $typ != "KI") //counter or kios
				continue;

			$paymode = substr($iter["ref_code"], 4, 2); //paymentmode
			$paymode_name = (isset($paymodes[$paymode]) ? $paymodes[$paymode] : $paymode);
			//$deity_name = (isset($deitys[$iter["deity_id"]])?$deitys[$iter["deity_id"]]:$iter["deity_id"]);
			$res[($typ == "CT" ? "Counter" : "KIOSK")][$paymode_name][$iter["prasadam_id"]] = $iter;
		}

		//print_r($res);
		$data['data'] = $res;
		$data['lang'] = $lang;
		$data['fdate'] = $from_date;
		$data['tdate'] = $to_date;

		$i = 1;
		if ($_REQUEST['pdf_item_count_report'] == "PDF") {
			// $file_name = "Item_Count_Report_" . $data['fdate'] . "_to_" . $data['tdate'];
			$file_name = "Prasadam_Item_Count_Report";
			$dompdf = new \Dompdf\Dompdf();
			$options = $dompdf->getOptions();
			$options->set(array('isRemoteEnabled' => true));
			$dompdf->setOptions($options);
			$dompdf->loadHtml(view('report/prasadam_item_count_user_report_pdf', ["pdfdata" => $data]), 'UTF-8');
			$dompdf->setPaper('LEGAL', 'portrait');
			$dompdf->render();
			$dompdf->stream($file_name);
		} elseif ($_REQUEST['excel_item_count_report'] == "EXCEL") {
			// $fileName = "Item_Count_Report_" . $data['fdate'] . "_to_" . $data['tdate'];
			$fileName = "Prasadam_Item_Count_Report";
			$spreadsheet = new Spreadsheet();

			$sheet = $spreadsheet->getActiveSheet();
			$sheet->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
			$sheet->getStyle('A2')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
			$sheet->getStyle('A3')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
			$style = array(
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				)
			);

			$sheet->getStyle("A1:F1")->applyFromArray($style);
			$sheet->mergeCells('A1:F1');
			$sheet->getStyle("A2:F2")->applyFromArray($style);
			$sheet->mergeCells('A2:F2');
			$sheet->getStyle("A3:F3")->applyFromArray($style);
			$sheet->mergeCells('A3:F3');
			$sheet->setCellValue('A1', $_SESSION['site_title']);
			$sheet->setCellValue('A2', 'PRASADAM ITEM COUNT REPORT');
			$sheet->setCellValue('A3', date('d-m-Y', strtotime($from_date)) . ' - ' . date('d-m-Y', strtotime($to_date)));
			$rows = 5;
			// die;
			if (!empty($data['data'])) {
				$totqtytyp = 0;
				$tottyp = 0;

				foreach ($data['data'] as $typ => $iter) {
					$sheet->setCellValue('A' . $rows++, $typ);
					$totqtypaymode = 0;
					$totpaymode = 0;
					foreach ($iter as $payment_mode => $iter1) {
						$sheet->setCellValue('A' . $rows++, $payment_mode);
						$totqty = 0;
						$tot = 0;
						$sheet->setCellValue('A' . $rows, $lang->sno);
						$sheet->setCellValue('B' . $rows, $lang->prasadam_name);
						$sheet->setCellValue('C' . $rows, $lang->sl_no);
						$sheet->setCellValue('D' . $rows, $lang->sl_to);
						$sheet->setCellValue('E' . $rows, $lang->quantity);
						$sheet->setCellValue('F' . $rows++, $lang->amount);
						$i = 1;
						foreach ($iter1 as $val) {
							$sheet->setCellValue('A' . $rows, $i);
							$sheet->setCellValue('B' . $rows, $val['shortcode'] . " - " . $val['prasadam_name']);
							$sheet->setCellValue('C' . $rows, $val['sl_no']);
							$sheet->setCellValue('D' . $rows, $val['sl_to']);
							$sheet->setCellValue('E' . $rows, $val['quantity']);
							$sheet->setCellValue('F' . $rows, $val['amount']);
							$sheet->getStyle('F' . $rows)->getNumberFormat()->setFormatCode('#,##0.00');
							$rows++;
							$i++;
							$totqty += floatval($val['quantity']);
							$tot += floatval($val['amount']);
						}
						$sheet->setCellValue('D' . $rows, 'SUB TOTAL');
						$sheet->setCellValue('E' . $rows, $totqty);
						$sheet->setCellValue('F' . $rows, $tot);
						$sheet->getStyle('F' . $rows)->getNumberFormat()->setFormatCode('#,##0.00');
						$totqtypaymode += floatval($totqty);
						$totpaymode += floatval($tot);

						$rows++;
					}
					//}
					$sheet->setCellValue('D' . $rows, 'TOTAL ' . strtoupper($typ));
					$sheet->setCellValue('E' . $rows, $totqtypaymode);
					$sheet->setCellValue('F' . $rows, $totpaymode);
					$sheet->getStyle('F' . $rows)->getNumberFormat()->setFormatCode('#,##0.00');
					$totqtytyp += floatval($totqtypaymode);
					$tottyp += floatval($totpaymode);
					$rows++;
				}
				$sheet->setCellValue('D' . $rows, 'TOTAL');
				$sheet->setCellValue('E' . $rows, $totqtytyp);
				$sheet->setCellValue('F' . $rows, $tottyp);
				$sheet->getStyle('F' . $rows)->getNumberFormat()->setFormatCode('#,##0.00');
			}
			$writer = new Xlsx($spreadsheet);
			$writer->save('uploads/excel/' . $fileName . '.xlsx');
			return $this->response->download('uploads/excel/' . $fileName . '.xlsx', null)->setFileName($fileName . '.xlsx');
		} else {
			echo view('report/prasadam_item_count_report_user_print.php', $data);
		}
	}
}