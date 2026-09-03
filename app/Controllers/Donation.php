<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\RequestModel;

class Donation extends BaseController
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

	public function index()
	{
		//$data['list'] = $this->db->table('donation')->get()->getResultArray();
		if (!$this->model->list_validate('cash_donation')) {
			header('Location: ' . base_url() . '/dashboard');
		}
		$data['permission'] = $this->model->get_permission('cash_donation');
		$data['list'] = $this->db->table('donation', 'donation_setting.name as pname')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname')
			->select('donation.*')
			->orderBy('date', 'DESC')
			->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('donation/index', $data);
		echo view('template/footer');
	}

	public function add()
	{
		if (!$this->model->permission_validate('cash_donation', 'create_p')) {
			header('Location: ' . base_url() . '/dashboard');
		}
		$data['sett_don'] = $this->db->table('donation_setting')->get()->getResultArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where("donation", 1)->where('status', 1)->where('paid_through', 'DIRECT')->get()->getResultArray();
		$data['phone_codes'] = $this->db->table("phone_code")->orderBy('dailing_code', 'ASC')->get()->getResultArray();
		$profile = $this->db->table('admin_profile')->select('tax_no')->where('id', 1)->get()->getRowArray();
		$data['profile_tax_no'] = !empty($profile['tax_no']) ? $profile['tax_no'] : '';

		echo view('template/header');
		echo view('template/sidebar');
		echo view('donation/add', $data);
		echo view('template/footer');
	}

	public function edit()
	{
		if (!$this->model->permission_validate('cash_donation', 'edit')) {
			header('Location: ' . base_url() . '/dashboard');
		}

		$id = $this->request->uri->getSegment(3);
		$data['sett_don'] = $this->db->table('donation_setting')->get()->getResultArray();
		$data['data'] = $this->db->table('donation')->where('id', $id)->get()->getRowArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->get()->getResultArray();
		// Add this line
		$profile = $this->db->table('admin_profile')->select('tax_no')->where('id', 1)->get()->getRowArray();
		$data['profile_tax_no'] = !empty($profile['tax_no']) ? $profile['tax_no'] : '';

		$data['edit'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('donation/add', $data);
		echo view('template/footer');
	}

	public function view()
	{
		if (!$this->model->permission_validate('cash_donation', 'view')) {
			header('Location: ' . base_url() . '/dashboard');
		}

		$id = $this->request->uri->getSegment(3);
		$data['sett_don'] = $this->db->table('donation_setting')->get()->getResultArray();
		$data['data'] = $this->db->table('donation')->where('id', $id)->get()->getRowArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->get()->getResultArray();
		// Add this line
		$profile = $this->db->table('admin_profile')->select('tax_no')->where('id', 1)->get()->getRowArray();
		$data['profile_tax_no'] = !empty($profile['tax_no']) ? $profile['tax_no'] : '';

		$data['view'] = true;
		$data['edit'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('donation/add', $data);
		echo view('template/footer');
	}

	// Add this method to generate tax receipt numbers
	private function generateTaxReceiptNumber($date)
	{
		$date_parts = explode('-', $date);
		$yr = $date_parts[0];
		$mon = $date_parts[1];
		$year_short = date('y', strtotime($date));

		// Get the last tax receipt number for the given month and year
		$query = $this->db->query("
        SELECT tax_receipt_no 
        FROM donation 
        WHERE id = (
            SELECT MAX(id) 
            FROM donation 
            WHERE YEAR(date) = '$yr' 
            AND MONTH(date) = '$mon' 
            AND tax_receipt_no IS NOT NULL
            AND tax_receipt_no LIKE 'TX%'
        )
    ")->getRowArray();

		if (!empty($query['tax_receipt_no'])) {
			// Extract the number from the last receipt
			$last_number = (int) substr($query['tax_receipt_no'], -5);
			$next_number = $last_number + 1;
		} else {
			// First tax receipt for this month
			$next_number = 1;
		}

		// Format the receipt number
		$receipt_no = 'TX' . $year_short . $mon . sprintf("%05d", $next_number);

		return $receipt_no;
	}

	// Add this method for printing tax exempt receipts
	public function print_tax_exempt($donation_id)
	{
		$donation = $this->db->query("
        SELECT d.*, ds.name as donation_name, pm.name as payment_method_name
        FROM donation d 
        LEFT JOIN donation_setting ds ON d.pay_for = ds.id
        LEFT JOIN payment_mode pm ON d.payment_mode = pm.id
        WHERE d.id = ?", [$donation_id])->getRowArray();

		// Get admin profile data including tax_no
		$admin_profile = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

		$data['donation'] = $donation;
		$data['admin_profile'] = $admin_profile;

		echo view('donation/tax_exempt_receipt', $data);
	}

	public function save()
	{
		$email = \Config\Services::email();
		//var_dump($_POST);
		//echo '<pre>';
		//exit;
		$id = $_POST['id'];
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$date = explode('-', $_POST['date']);
		$yr = $date[0];
		$mon = $date[1];
		$query = $this->db->query("SELECT ref_no FROM donation where id=(select max(id) from donation where year (date)='" . $yr . "' and month (date)='" . $mon . "') ")->getRowArray();
		$data['ref_no'] = 'DO' . date('y', strtotime($_POST['date'])) . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
		// Add tax redemption handling
		$data['is_tax_redemption'] = !empty($_POST['is_tax_redemption']) ? 1 : 0;

		// Generate tax receipt number if it's a tax-exempt donation
		if ($data['is_tax_redemption'] == 1) {
			$data['tax_receipt_no'] = $this->generateTaxReceiptNumber($_POST['date']);
		} else {
			$data['tax_receipt_no'] = null; // Ensure it's null if not tax exempt
		}
		$data['date'] = $_POST['date'];
		$data['pay_for'] = trim($_POST['pay_for']);
		$data['name'] = trim($_POST['name']);
		$data['address'] = trim($_POST['address']);
		$data['ic_number'] = trim($_POST['ic_number']);

		if (empty($_POST['edit_status'])) {
			$mble_phonecode = !empty($_POST['phonecode']) ? $_POST['phonecode'] : "";
			$mble_number = !empty($_POST['mobile']) ? $_POST['mobile'] : "";
			$data['mobile']  = $mble_phonecode . $mble_number;
		} else {
			$data['mobile'] = $_POST['mobile'];
		}

		$data['description'] = trim($_POST['description']);
		$data['amount'] = trim($_POST['amount']);
		$data['target_amount'] = $_POST['targetamt'];
		$data['collected_amount'] = $_POST['collectedamt'];
		$data['payment_mode'] = $_POST['paymentmode'];
		$data['email'] = $_POST['email'];
		$data['added_by'] = $this->session->get('log_id');

		//ip location and ip details
		$ip = 'unknown';
		$this->requestmodel = new RequestModel();
		$ip = $this->requestmodel->getIpAddress();
		if ($ip != 'unknown') {
			$ip_details = $this->requestmodel->getLocation($ip);
			$data['ip'] = $ip;
			$data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
			$data['ip_details'] = json_encode($ip_details);
		}

		//print_r($data);die;
		if (!empty($data['pay_for']) && !empty($data['name']) && !empty($data['amount']) && !empty($data['date']) && $data['amount'] > 0) {
			// debit ledger
			$payment_mode_details = $this->db->table('payment_mode')->where('id', $data['payment_mode'])->get()->getRowArray();
			$incomes_group = $this->db->table('groups')->where('code', '8000')->get()->getRowArray();
			if (!empty($incomes_group)) {
				$sls_id = $incomes_group['id'];
			} else {
				$sls1['parent_id'] = 0;
				$sls1['name'] = 'Incomes';
				$sls1['code'] = '8000';
				$sls1['added_by'] = $this->session->get('log_id');
				$this->db->table('groups')->insert($sls1);
				$sls_id = $this->db->insertID();
			}
			$donation_details = $this->db->table('donation_setting')->where('id', $data['pay_for'])->get()->getRowArray();
			if (!empty($donation_details['ledger_id'])) {
				$dr_id = $donation_details['ledger_id'];
			} else {
				$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
				if (!empty($ledger1)) {
					$dr_id = $ledger1['id'];
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
					$dr_id = $this->db->insertID();
				}
			}
			if (empty($id)) {
				//var_dump($_POST);
				//exit;
				$builder = $this->db->table('donation')->insert($data);
				$ins_id = $this->db->insertID();
				if (!empty($data['mobile'])) {
					$users_all_data = array();
					if (substr($data['mobile'], 0, 1) == '+') {
						$users_all_data['mobile'] = substr($data['mobile'], 3);
						$users_all_data['country_phone_code'] = substr($data['mobile'], 0, 3);
					} else {
						$users_all_data['mobile'] = $data['mobile'];
						$users_all_data['country_phone_code'] = '+61';
					}
					$users_all_data['name'] = $data['name'];
					$users_all_data['address'] = $data['address'];
					$users_all_data['nric'] = $data['ic_number'];
					$users_all_data['email'] = $data['email'];
					sync_users_all_tag($users_all_data, 2);
				}
				// CHANGED: Using UltraMsg instead of old WhatsApp method
				$this->send_whatsapp_ultramsg($ins_id);
				//$whatsapp_resp = whatsapp_aisensy($data['mobile'], [], 'success_message1');
				$data['created'] = date('Y-m-d H:i:s');
				$data['modified'] = date('Y-m-d H:i:s');
				if ((!empty($dr_id)) && (!empty($payment_mode_details['ledger_id']))) {
					$number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
					if (empty($number)) {
						$num = 1;
					} else {
						$num = $number['number'] + 1;
					}
					$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();
					$entries['entry_code'] = 'REC' . date('y', strtotime($_POST['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));

					$entries['entrytype_id'] = '1';
					$entries['number'] = $num;
					$entries['date'] = $data['date'];
					$entries['dr_total'] = $data['amount'];
					$entries['cr_total'] = $data['amount'];
					$entries['narration'] = 'Cash Donation(' . $data['ref_no'] . ')' . "\n" . 'name:' . $data['name'] . "\n" . 'NRIC:' . $data['ic_number'] . "\n" . 'email:' . $data['email'] . "\n";
					$entries['inv_id'] = $ins_id;
					$entries['type'] = '2';
					$ent = $this->db->table('entries')->insert($entries);
					$en_id = $this->db->insertID();
					if (!empty($en_id)) {
						$eitems_d['entry_id'] = $en_id;
						$eitems_d['ledger_id'] = $dr_id;
						$eitems_d['amount'] = $data['amount'];
						$eitems_d['details'] = 'Cash Donation(' . $data['ref_no'] . ')';
						$eitems_d['dc'] = 'C';
						$this->db->table('entryitems')->insert($eitems_d);

						$eitems_c['entry_id'] = $en_id;
						$eitems_c['ledger_id'] = $payment_mode_details['ledger_id'];
						$eitems_c['amount'] = $data['amount'];
						$eitems_c['details'] = 'Cash Donation(' . $data['ref_no'] . ')';
						$eitems_c['dc'] = 'D';
						$this->db->table('entryitems')->insert($eitems_c);
						if ($builder) {
							if (!empty($_POST['email'])) {
								$temple_title = "Temple " . $_SESSION['site_title'];
								$qr_url = base_url() . "/donation/reg/";
								$mail_data['qr_image'] = qrcode_generation($ins_id, $qr_url);
								$mail_data['don_id'] = $ins_id;
								$tmpid = 1;
								$mail_data['temple_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
								$message = view('donation/mail_template', $mail_data);
								$to = $_POST['email'];
								$subject = $_SESSION['site_title'] . " Cash Donation";
								$email->setTo($to);
								$email->setFrom('templetest@grasp.com.my', $temple_title);
								$email->setSubject($subject);
								$email->setMessage($message);
								$email->send();
							}
							$msg_data['succ'] = 'Donation Added Successflly';
							$msg_data['id'] = $ins_id;
							$msg_data['is_tax_redemption'] = $data['is_tax_redemption'];
							//$this->session->setFlashdata('succ', 'Donation Added Successfully');
							//header("Location: ".base_url()."/donation");
						} else {
							$msg_data['err'] = 'Please Try Again';
						}
					} else {
						$msg_data['err'] = 'Please Try Again';
					}
				} else {
					$msg_data['err'] = 'Please Try Again Ledger is empty';
				}
			}
		} else {
			$msg_data['err'] = 'Please Fill Required Field';
		}
		echo json_encode($msg_data);
		exit();
	}

	public function reg()
	{
		echo "welcome";
	}

	public function delete()
	{
		if (!$this->model->permission_validate('cash_donation', 'delete_p')) {
			header('Location: ' . base_url() . '/dashboard');
		}

		$id = $this->request->uri->getSegment(3);
		$res = $this->db->table('donation')->delete(['id' => $id]);
		if ($res) {
			$this->session->setFlashdata('succ', 'Donation Delete Successfully');
			header("Location: " . base_url() . "/donation");
		} else {
			$this->session->setFlashdata('fail', 'Please Try Again');
			header("Location: " . base_url() . "/donation");
		}
	}

	public function print_page($don_book_id)
	{
		$id = $this->request->uri->getSegment(3);
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		$data['data'] = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname, donation.amount, donation.name, donation.mobile')
			->select('donation.*')
			->where('donation.id', $id)
			->get()->getRowArray();

		// echo view('donation/print_page', $data);
		echo view('donation/print_report', $data);
	}

	public function print_page_old()
	{

		if (!$this->model->permission_validate('cash_donation', 'print')) {
			header('Location: ' . base_url() . '/dashboard');
		}
		$id = $this->request->uri->getSegment(3);
		// echo  $id;
		//  exit ;
		$data['qry1'] = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname')
			->select('donation.*')
			->where('donation.id', $id)
			->get()->getRowArray();
		$data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
		echo view('donation/print_page', $data);
	}

	public function get_donation_amount()
	{
		$json_resp = array();
		if (!empty($_REQUEST['setting_id'])) {
			$id = $_REQUEST['setting_id'];
			$res = $this->db->table('donation_setting ds')->join('donation d', 'ds.id = d.pay_for', 'left')->select('max(ds.amount) as total_amount')->select('COALESCE(sum(d.amount), 0) as collected_amount')->where(['ds.id' => $id])->get()->getRowArray();
			if ($res)
				$json_resp['data'] = $res;
			else
				$json_resp['data'] = array();
		}
		echo json_encode($json_resp);
		exit;
	}

	// NEW: UltraMsg WhatsApp Integration
	public function send_whatsapp_ultramsg($donation_id)
	{
		$donation = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname, donation.*')
			->where('donation.id', $donation_id)
			->get()->getRowArray();

		if (!$donation || empty($donation['mobile']) || (!empty($donation['whatsapp_status']) && $donation['whatsapp_status'] == 1)) {
			return false;
		}

		try {
			// Generate PDF
			$data['donation'] = $donation;
			$data['qry1'] = $donation; // For compatibility with existing PDF template
			$tmpid = 1;
			$data['temple_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
			$data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();

			$html = view('donation/pdf', $data);

			$options = new \Dompdf\Options();
			$options->set('isHtml5ParserEnabled', true);
			$options->set(['isRemoteEnabled' => true]);
			$options->set('isPhpEnabled', true);

			$dompdf = new \Dompdf\Dompdf($options);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();

			$filePath = FCPATH . 'uploads/documents/donation_invoice_' . $donation_id . '.pdf';
			if (!is_dir(dirname($filePath))) {
				mkdir(dirname($filePath), 0755, true);
			}
			file_put_contents($filePath, $dompdf->output());

			$mobile_number = [$donation['mobile']]; // UltraMsg expects array format

			$message_params = [
				':devotee' => $donation['name'],
				':amount' => number_format($donation['amount'], 2),
				':ref_no' => $donation['ref_no'],
				':donation_date' => date('d M Y', strtotime($donation['date']))
			];

			$media = [
				'url' => base_url() . '/uploads/documents/donation_invoice_' . $donation_id . '.pdf',
				'filename' => 'donation_invoice.pdf'
			];

			// Send WhatsApp using UltraMsg
			$response = whatsapp_ultramsg($mobile_number, 'donation_confirmation', $message_params, $media);

			// Log for debugging
			log_message('info', 'WhatsApp Response for Donation ID ' . $donation_id . ': ' . json_encode($response));
			log_message('info', 'Mobile Number: ' . json_encode($mobile_number));

			// Update status if successful
			if (isset($response['sent']) && ($response['sent'] === 'true' || $response['sent'] === true)) {
				$this->db->table('donation')
					->where('id', $donation_id)
					->update(['whatsapp_status' => 1]);
			}

			return $response;
		} catch (\Exception $e) {
			log_message('error', 'WhatsApp error for Donation ID ' . $donation_id . ': ' . $e->getMessage());
			return false;
		}
	}

	// LEGACY: Keep old WhatsApp method for backward compatibility (not used)
	public function send_whatsapp_msg($id)
	{
		$data['qry1'] = $donation = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname')
			->select('donation.*')
			->where('donation.id', $id)
			->get()->getRowArray();
		$tmpid = 1;
		$data['temple_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		$data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
		if (!empty($donation['mobile'])) {
			$html = view('donation/pdf', $data);
			$options = new Options();
			$options->set('isHtml5ParserEnabled', true);
			$options->set(array('isRemoteEnabled' => true));
			$options->set('isPhpEnabled', true);
			$dompdf = new Dompdf($options);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			$filePath = FCPATH . 'uploads/documents/invoice_donation_' . $id . '.pdf';

			file_put_contents($filePath, $dompdf->output());
			$message_params = array();
			/* $message_params[] = date('d M, Y', strtotime($donation['dt']));
					 $message_params[] = date('h:i A', strtotime($donation['created_at']));
					 $message_params[] = $donation['amount'];
					 // $message_params[] = $ubayam['paidamount'];
					 $message_params[] = $donation['balanceamount']; */
			$media['url'] = base_url() . '/uploads/documents/invoice_donation_' . $id . '.pdf';
			$media['filename'] = 'donation_invoice.pdf';
			$mobile_number = $donation['mobile'];
			//$mobile_number = '+919092615446';
			// print_r($mobile_number);
			// print_r($message_params);
			// print_r($media);
			// die; 
			$whatsapp_resp = whatsapp_aisensy($mobile_number, $message_params, 'donation_live', $media);
			// print_r($whatsapp_resp);
			//echo $whatsapp_resp['success'];
			/* if($whatsapp_resp['success']) 
					 //echo 'success';
					 echo view('hallbooking/whatsapp_resp_suc');
					 else 
					 //echo 'fail'; 
					 echo view('hallbooking/whatsapp_resp_fail'); */
		}
	}

	public function get_name_suggestions()
	{
		$search_term = $this->request->getGet('term');

		if (!empty($search_term)) {
			// Get unique names that start with the search term
			$names = $this->db->table('donation')
				->select('DISTINCT(name) as name')
				->where('name !=', '')
				->where('name IS NOT NULL')
				->like('name', $search_term, 'after') // Matches names starting with search term
				->orderBy('name', 'ASC')
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

	// TEST METHODS for WhatsApp UltraMsg
	public function test_whatsapp()
	{
		// Test function - you can call this via URL to test
		$test_number = ["+917094176551"]; // Replace with your test number
		$params = [
			':devotee' => 'Test User Admin',
			':amount' => '100.00',
			':ref_no' => 'DO240001',
			':donation_date' => date('d M Y')
		];

		$response = whatsapp_ultramsg($test_number, 'donation', $params);

		echo "<pre>";
		print_r($response);
		echo "</pre>";
	}

	public function test_whatsapp_detailed()
	{
		// Test with your actual phone number (include country code)
		$test_number = ["+919655500357"]; // Replace with your actual number

		$params = [
			':devotee' => 'Test User Admin Interface',
			':amount' => '75.00',
			':ref_no' => 'TEST_ADMIN_001',
			':donation_date' => date('d M Y')
		];

		echo "<h3>Testing WhatsApp Integration - Admin Donation Interface</h3>";
		echo "<p><strong>Phone Number:</strong> " . implode(", ", $test_number) . "</p>";
		echo "<p><strong>Parameters:</strong></p>";
		echo "<pre>" . print_r($params, true) . "</pre>";

		$response = whatsapp_ultramsg($test_number, 'donation', $params);

		echo "<p><strong>API Response:</strong></p>";
		echo "<pre>" . print_r($response, true) . "</pre>";

		// Also test with document
		echo "<hr><h4>Testing with Document</h4>";
		$media = [
			'url' => base_url() . '/uploads/documents/donation_invoice_35.pdf', // Use any existing PDF for testing
			'filename' => 'test_admin_donation.pdf'
		];

		$response2 = whatsapp_ultramsg($test_number, 'donation', $params, $media);
		echo "<pre>" . print_r($response2, true) . "</pre>";
	}

	public function send_mail_to_customer($id)
	{
		$donation = $this->db->table("donation")->where("id", $id)->get()->getRowArray();
		if (!empty($donation['email'])) {
			$tmpid = 1;
			$temple_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
			$temple_title = "Temple " . $temple_details['name'];
			$qr_url = base_url() . "/donation/reg/";
			$mail_data['qr_image'] = qrcode_generation($id, $qr_url);
			$mail_data['don_id'] = $id;
			$mail_data['temple_details'] = $temple_details;
			$message = view('donation/mail_template', $mail_data);
			$to = $donation['email'];
			$subject = $temple_details['name'] . " Cash Donation";
			$to_mail = array("prithivitest@gmail.com", $to);
			send_mail_with_content($to_mail, $message, $subject, $temple_title);
		}
	}



	/**
	 * Send donation reminders cron (though donations typically don't need reminders)
	 */
	public function send_donation_reminders_cron()
	{
		$secret_key = 'donation_admin_cron_2024_secret';
		$provided_key = $this->request->getGet('key') ?: $this->request->getPost('key');

		if ($provided_key !== $secret_key) {
			http_response_code(403);
			echo json_encode(['error' => 'Unauthorized access']);
			return;
		}

		// Donations typically don't have future reminder dates
		$response = [
			'status' => 'completed',
			'message' => 'Donations typically do not require reminders',
			'sent_count' => 0,
			'date' => date('Y-m-d H:i:s')
		];

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	/**
	 * Send donation thank you messages cron
	 */
	public function send_donation_thankyou_cron()
	{
		$secret_key = 'temple_donation_admin_thankyou_2024';
		$provided_key = $this->request->getGet('key') ?: $this->request->getPost('key');

		if ($provided_key !== $secret_key) {
			http_response_code(403);
			echo json_encode(['error' => 'Unauthorized access']);
			return;
		}

		$today = date('Y-m-d');

		// Get donations from today without thank you sent
		$builder = $this->db->table('donation')
			->select('id')
			->where('date', $today)
			->where('payment_status', 2); // Only completed payments

		// Check if donation_whatsapp_log table exists
		$tables = $this->db->listTables();
		if (in_array('donation_whatsapp_log', $tables)) {
			$builder->whereNotIn('id', function ($sub) {
				return $sub->select('donation_id')
					->from('donation_whatsapp_log')
					->where('message_type', 'thank_you')
					->where('status', 'sent');
			});
		}

		$donations = $builder->get()->getResultArray();

		$sent_count = 0;
		$results = [];

		foreach ($donations as $donation) {
			if ($this->send_donation_thank_you($donation['id'])) {
				$sent_count++;
				$results[] = "Thank you sent for donation ID: " . $donation['id'];
			}
			sleep(1); // Rate limiting
		}

		$response = [
			'status' => 'completed',
			'sent_count' => $sent_count,
			'date' => date('Y-m-d H:i:s'),
			'details' => $results
		];

		log_message('info', "Admin Donation thank you cron: " . json_encode($response));

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	/**
	 * Test cron connection
	 */
	public function test_cron_connection()
	{
		$secret_key = 'temple_donation_admin_cron_2024_secure';
		$provided_key = $this->request->getGet('key') ?: $this->request->getPost('key');

		if ($provided_key !== $secret_key) {
			http_response_code(403);
			echo json_encode(['error' => 'Unauthorized access']);
			return;
		}

		$response = [
			'status' => 'success',
			'message' => 'Admin Donation Cron job connection working!',
			'server_time' => date('Y-m-d H:i:s'),
			'controller' => 'Donation (Admin)'
		];

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	/**
	 * Send thank you WhatsApp message for donations
	 */
	public function send_donation_thank_you($donation_id)
	{
		$donation = $this->db->table('donation')
			->where('id', $donation_id)
			->get()->getRowArray();

		if (empty($donation['mobile']) || empty($donation['whatsapp_status']) || $donation['whatsapp_status'] == 1) {
			return false;
		}

		$temple_details = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

		$params = [
			':devotee' => $donation['name'],
			':temple_name' => $temple_details['name'] ?? 'Temple',
			':amount' => number_format($donation['amount'], 2)
		];

		$numbers = [$donation['mobile']];
		$response = whatsapp_ultramsg($numbers, 'donation_thank_you', $params);

		// Update whatsapp_status if successful
		if (isset($response['sent']) && ($response['sent'] === 'true' || $response['sent'] === true)) {
			$this->db->table('donation')
				->where('id', $donation_id)
				->update(['whatsapp_status' => 1]);
		}

		return $response;
	}
}
