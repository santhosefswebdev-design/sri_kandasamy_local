<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\RequestModel;
use App\Models\Common_model;

class Donation_online extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		helper('common_helper');
		$this->model = new PermissionModel();
		$this->common_model = new Common_model();
		if (($this->session->get('log_id_frend')) == false) {
			$data['dn_msg'] = 'Please Login';
			header('Location: ' . base_url() . '/member_login');
			exit;
		}
	}
	public function index()
	{
		exit;
		$data['list'] = $this->db->table('donation', 'donation_setting.name as pname')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname')
			->select('donation.*')
			->orderBy('date', 'DESC')
			->get()->getResultArray();
		echo view('frontend/layout/header');
		echo view('frontend/donation/index', $data);
		echo view('frontend/layout/footer');
	}
	public function add()
	{
		$login_id = $_SESSION['log_id_frend'];
		$data['payment_mode'] = $this->db->table('payment_mode')->where("paid_through", "COUNTER")->where("donation", 1)->where('status', 1)->get()->getResultArray();
		$login_eghl_terminal = $this->db->table('login')->select('eghl_terminal_id')->where('id', $login_id)->get()->getRowArray();
		if (empty($login_eghl_terminal['eghl_terminal_id'])) {
			$data['payment_mode'] = array_values(array_filter($data['payment_mode'], function ($pm) {
				return $pm['pay_key'] !== 'eghl_qr';
			}));
		}
		$default_group = $this->db->query("SELECT * FROM cashdonation_group ORDER BY id ASC LIMIT 1")->getRowArray();
		$data['default'] = str_replace(' ', '_', strtolower($default_group['name']));

		// Get all groups
		$group = $this->db->query("SELECT * FROM cashdonation_group ORDER BY name ASC")->getResultArray();

		// Initialize the sett_don array
		$data['sett_don'] = [];
		$settings = $this->db->table('settings')->where('type', 2)->get()->getResultArray();
		$setting_array = array();
		if (count($settings) > 0) {
			foreach ($settings as $item) {
				$setting_array[$item['setting_name']] = $item['setting_value'];
			}
		}
		$data['setting'] = $setting_array;
		// ADD THIS: Get profile tax_no to check if Tax Exempt Receipt should be shown
		$profile = $this->db->table('admin_profile')->select('tax_no')->where('id', 1)->get()->getRowArray();
		$data['profile_tax_no'] = !empty($profile['tax_no']) ? $profile['tax_no'] : '';
		// Fetch donation settings for each group with non-empty ledger_id
		foreach ($group as $row) {
			$settings = $this->db->table('donation_setting')
				->where('groupname', $row['name'])
				->where('ledger_id !=', '')
				->get()
				->getResultArray();
			if (!empty($settings)) {
				$data['sett_don'][$row['name']] = $settings;
			}
		}

		// Fetch phone codes
		$data['phone_codes'] = $this->db->table("phone_code")
			->orderBy('dailing_code', 'ASC')
			->get()
			->getResultArray();
		$data['donation_names'] = $this->db->query("SELECT DISTINCT name FROM donation WHERE name IS NOT NULL AND name != '' ORDER BY name ASC")->getResultArray();

		// Fetch recent donations made by the current user
		$data['reprintlists'] = $this->db->query("
        SELECT id, amount, ref_no, date 
        FROM donation 
        WHERE added_by = '$login_id' 
        AND paid_through = 'COUNTER' 
        AND payment_status = 2 
        ORDER BY id DESC 
        LIMIT 3
    	")->getResultArray();

		// Render views
		echo view('frontend/layout/header');
		echo view('frontend/donation_new/index', $data);
		// echo view('frontend/layout/footer');
	}

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
	// Save function
	public function save()
	{
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$date = explode('-', $_POST['date']);
		$yr = $date[0];
		$mon = $date[1];
		$query = $this->db->query("SELECT ref_no FROM donation where id=(select max(id) from donation where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();
		$data['ref_no'] = 'DO' . date('y', strtotime($_POST['date'])) . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
		$data['is_tax_redemption'] = !empty($_POST['is_tax_redemption']) ? 1 : 0;

		if ($data['is_tax_redemption'] == 1) {
			$data['tax_receipt_no'] = $this->generateTaxReceiptNumber(!empty($_POST['date']) ? $_POST['date'] : date('Y-m-d'));
		} else {
			$data['tax_receipt_no'] = null; // Ensure it's null if not tax exempt
		}
		$data['date'] = $_POST['date'];
		$data['pay_for'] = trim($_POST['pay_for']);
		$data['name'] = trim($_POST['name']);
		$data['address'] = trim($_POST['address']);
		$data['ic_number'] = trim($_POST['ic_number']);
		$mble_phonecode = !empty($_POST['phonecode']) ? $_POST['phonecode'] : "";
		$mble_number = !empty($_POST['mobile']) ? $_POST['mobile'] : "";
		$data['payment_mode'] = $pay_id = $_POST['pay_method'];
		$payment_mode = $this->db->table('payment_mode')->where("id", $pay_id)->get()->getRowArray();
		$pay_method = $payment_mode['name'];
		$is_payment_gateway = $payment_mode['is_payment_gateway'];
		$payment_key = $payment_mode['pay_key'];

		$data['mobile'] = $mble_phonecode . $mble_number;
		$data['email'] = trim($_POST['email_id']);
		$data['description'] = trim($_POST['description']);
		$data['amount'] = trim($_POST['total_amount']);
		$data['target_amount'] = 0;
		$data['collected_amount'] = 0;
		$data['paid_through'] = "COUNTER";
		$data['payment_status'] = empty($is_payment_gateway) ? 2 : 1;
		$data['added_by'] = $this->session->get('log_id_frend');
		$data['created'] = date('Y-m-d H:i:s');
		$data['modified'] = date('Y-m-d H:i:s');
		$data['is_tax_redemption'] = !empty($_POST['is_tax_redemption']) ? 1 : 0;
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

		// Proceed with saving data
		$this->db->table('donation')->insert($data);
		$ins_id = $this->db->insertID();
		$payment_gateway_data = array();
		$payment_gateway_data['donation_booking_id'] = $ins_id;
		$payment_gateway_data['pay_method'] = $pay_method;
		$payment_gateway_data['payment_mode'] = $pay_id;
		$this->db->table('donation_payment_gateway_datas')->insert($payment_gateway_data);
		$donation_payment_gateway_id = $this->db->insertID();

		if ($payment_key == 'rhb_qr') {
			$ref_no = PAYMENT_PREFIX . '_DONA_' . $ins_id;
			if (PAYMENT_TEST) {
				$pay_amount = 1;
			} else {
				$pay_amount = bcdiv($data['amount'], '1', 2);
			}

			$bill_num = (string) ($ins_id + 200000000000);
			$url = 'https://dnqr.synexisasia.com/v1/qr';

			$json_data = [
				"merchantCode" => RHB_MERCHANTCODE,
				"userId" => RHB_USERID,
				"amount" => $pay_amount,
				"billNumber" => $bill_num,
				"transactionReference" => $ref_no,
			];

			// Assuming common_repository->postJson() posts JSON and returns JSON string
			$response = $this->common_model->postJson($url, $json_data);
			$response = json_decode($response);

			if (!empty($response->qrCode)) {
				$payment_gateway_up_data = [
					'request_data' => json_encode($json_data),
				];

				$this->db->table('donation_payment_gateway_datas')
					->where('id', $donation_payment_gateway_id)
					->update($payment_gateway_up_data);

				$msg_data['qr_code'] = $response->qrCode;
				$msg_data['total_amount'] = bcdiv($data['amount'], '1', 2);
			} else {
				$msg_data = [
					'err' => 'QR code not generated. Kindly rebook the ticket.',
				];
				// return json_encode($msg_data);
			}
			$msg_data['pay_status'] = false;
			$msg_data['payment_key'] = $payment_key;
		} elseif ($payment_key == 'eghl_qr') {
			try {
				if (empty(EGHL_MERCHANTID) || empty(EGHL_TERMINALID)) {
					throw new \RuntimeException('EGHL credentials are not configured.');
				}

				$pay_amount = EGHL_TEST ? 1 : bcdiv($data['amount'], '1', 2);

				$txnPMT = new \App\Libraries\MahJsonAPI(EGHL_SERVER_CERT_PATH, EGHL_CLIENT_KEY_PATH);
				$txnPMT->Amount = $pay_amount;
				$txnPMT->setNewRetTxnRef(EGHL_PREFIX . '_DONA_' . $ins_id . '_' . $donation_payment_gateway_id);
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
					$this->db->table('donation_payment_gateway_datas')
						->where('id', $donation_payment_gateway_id)
						->update(['request_data' => json_encode($eghl_response)]);

					$msg_data['qr_code'] = $eghl_response->msg->DisplayInfo[0]->Value; // base64 PNG
					$msg_data['total_amount'] = $pay_amount;
				} else {
					$error_code = $eghl_response->msg->ResponseCode ?? null;
					$error_msg  = $eghl_response->msg->ResponseMsg ?? 'No QR returned';
					throw new \RuntimeException("EGHL Sale failed [{$error_code}]: {$error_msg}");
				}
			} catch (\Throwable $e) {
				log_message('error', 'EGHL_QR_SALE_FAILURE | user_id=' . ($this->session->get('log_id_frend') ?? '')
					. ' | email=' . ($data['email'] ?? '')
					. ' | donation_id=' . $ins_id
					. ' | amount=' . $data['amount']
					. ' | status=failed'
					. ' | error_code=' . $e->getCode()
					. ' | message=' . $e->getMessage()
					. ' | datetime=' . date('Y-m-d H:i:s'));

				$this->db->table('donation')->where('id', $ins_id)->update(['payment_status' => 3]);
				$msg_data['err'] = 'QR code not generated. Kindly rebook the ticket.';
				echo json_encode($msg_data);
				exit();
			}
			$msg_data['pay_status'] = false;
			$msg_data['payment_key'] = $payment_key;
		} else
			$msg_data['pay_status'] = true;

		if ($data['payment_status'] == 2) {
			$this->account_migration($ins_id);
			// $this->send_whatsapp_msg($ins_id);
			$this->send_whatsapp_ultramsg($ins_id);
			$this->send_mail_to_customer($ins_id);
		}
		$this->session->setFlashdata('succ', 'Donation Added Successfully');
		$msg_data['succ'] = 'Donation Added Successfully';
		$msg_data['id'] = $ins_id;
		$msg_data['is_tax_redemption'] = $data['is_tax_redemption'];
		echo json_encode($msg_data);
		exit();
	}
	public function update()
	{
		$msg_data = array();
		$this->db->transStart();
		try {
			if (!empty($_POST['total_amount']) && !empty($_POST['pay_method']) && !empty($_POST['pay_for'])) {
				$msg_data['err'] = '';
				$msg_data['succ'] = '';
				$date = !empty($_POST['date']) ? explode('-', $_POST['date']) : explode('-', date('Y-m-d'));
				$yr = $date[0];
				$mon = $date[1];
				//$data['date'] = !empty($_POST['date']) ? $_POST['date'] : date('Y-m-d');
				$data['pay_for'] = trim($_POST['pay_for']);
				$data['name'] = trim($_POST['name']);
				$data['address'] = trim($_POST['address']);
				$data['ic_number'] = trim($_POST['ic_number']);
				$mble_phonecode = !empty($_POST['phonecode']) ? $_POST['phonecode'] : "";
				$mble_number = !empty($_POST['mobile']) ? $_POST['mobile'] : "";
				$data['payment_mode'] = $pay_id = $_POST['pay_method'];
				$payment_mode = $this->db->table('payment_mode')->where("id", $pay_id)->get()->getRowArray();
				$pay_method = $payment_mode['name'];
				$is_payment_gateway = $payment_mode['is_payment_gateway'];
				$payment_key = $payment_mode['pay_key'];

				$data['mobile'] = $mble_phonecode . $mble_number;
				$data['email'] = trim($_POST['email_id']);
				$data['description'] = trim($_POST['description']);
				$data['amount'] = trim($_POST['total_amount']);
				$data['target_amount'] = 0;
				$data['collected_amount'] = 0;
				//$data['paid_through'] = "COUNTER";
				//$data['payment_status'] = empty($is_payment_gateway) ? 2 : 1;
				$data['added_by'] = $this->session->get('log_id_frend');
				$data['created'] = date('Y-m-d H:i:s');
				$data['modified'] = date('Y-m-d H:i:s');

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


				// Proceed with saving data
				$this->db->table('donation')->where("pledge_id", $id)->update($data);


				if ($data['payment_status'] == 2) {
					//$this->account_migration($ins_id);
					//$this->send_whatsapp_msg($ins_id);
					//$this->send_mail_to_customer($ins_id);
				}
				$this->db->transComplete();
				// $this->session->setFlashdata('succ', 'Donation Added Successfully');
				$msg_data['succ'] = 'Donation Updated Successfully';
				$msg_data['id'] = $ins_id;
			} else {
				$this->db->transRollback();
				$msg_data['pay_status'] = false;
				$msg_data['err'] = 'Please fill all required fields';
			}
		} catch (Exception $e) {
			$this->db->transRollback(); // Rollback the transaction if an error occurs
			$msg_data['err'] = $e->getMessage();
		}
		echo json_encode($msg_data);
		exit();
	}
	public function print_tax_exempt($donation_id)
	{
		$donation = $this->db->query("
        SELECT d.*, ds.name as donation_name, pm.name as payment_method_name
        FROM donation d 
        LEFT JOIN donation_setting ds ON d.pay_for = ds.id
        LEFT JOIN payment_mode pm ON d.payment_mode = pm.id
        WHERE d.id = ?", [$donation_id])->getRowArray();

		// if (!$donation) {
		// 	die('Donation not found');
		// }

		// Get admin profile data including tax_no
		$admin_profile = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

		$data['donation'] = $donation;
		$data['admin_profile'] = $admin_profile;
		//$data['amount_in_words'] = $this->numberToWords($donation['amount']);

		echo view('frontend/donation/tax_exempt_receipt', $data);
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
	public function payment_process($don_book_id)
	{
		$donation_booking = $this->db->table('donation')->where('id', $don_book_id)->get()->getRowArray();
		$donation_payment_gateway_datas = $this->db->table('donation_payment_gateway_datas')->where('donation_booking_id', $don_book_id)->get()->getResultArray();
		if (count($donation_payment_gateway_datas) > 0) {
			if ($donation_payment_gateway_datas[0]['pay_method'] == 'adyen') {
				if (!empty($donation_payment_gateway_datas[0]['request_data'])) {
					$request_data = $donation_payment_gateway_datas[0]['request_data'];
					$response = json_decode($request_data, true);
				} else {
					$tmpid = 1;
					$temple_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
					$result = $this->initiatePayment($donation_booking['amount'], $don_book_id, $temple_details['address1'] . $temple_details['address2'], $temple_details['city'], $temple_details['email']);
					$response = json_decode($result, true);
					$payment_gateway_up_data = array();
					$payment_gateway_up_data['request_data'] = $result;
					$payment_gateway_up_data['reference_id'] = $response['id'];
					$this->db->table('donation_payment_gateway_datas')->where('id', $donation_payment_gateway_datas[0]['id'])->update($payment_gateway_up_data);
				}
				if (!empty($response['url']) && !empty($response['id'])) {
					header('Location: ' . $response['url']);
					exit;
				}
			} elseif ($donation_payment_gateway_datas[0]['pay_method'] == 'ipay_merch_qr') {
				//$view_file = 'frontend/ipay88/ipay_merch_qr';
				$view_file = 'frontend/ipay88/ipay_merch_qr_camera';
				$data['don_book_id'] = $don_book_id;
				$data['list'] = $this->db->table('payment_option')->where('status', 1)->get()->getResultArray();
				$data['submit_url'] = '/donation_online/initiate_ipay_merch_qr/' . $don_book_id;
				echo view($view_file, $data);
			} elseif ($donation_payment_gateway_datas[0]['pay_method'] == 'ipay_merch_online') {
				$view_file = 'frontend/ipay88/ipay_merch_online';
				$data['id'] = $don_book_id;
				$data['controller'] = 'donation_online';
				echo view($view_file, $data);
			} else {
				// $redirect_url = base_url() . '/donation_online/print_booking/' . $don_book_id;
				$redirect_url = base_url() . '/donation_online/print_report_a5/' . $don_book_id;
				header('Location: ' . $redirect_url);
				exit;
			}
		} else {
			$tmpid = 1;
			$temple_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
			$result = $this->initiatePayment($donation_booking['amount'], $don_book_id, $temple_details['address1'] . $temple_details['address2'], $temple_details['city'], $temple_details['email']);
			$response = json_decode($result, true);
			if (!empty($response['url']) && !empty($response['id'])) {
				$payment_gateway_data = array();
				$payment_gateway_data['donation_booking_id'] = $don_book_id;
				$payment_gateway_data['pay_method'] = 'adyen';
				$payment_gateway_data['request_data'] = $result;
				$payment_gateway_data['reference_id'] = $response['id'];
				$this->db->table('donation_payment_gateway_datas')->insert($payment_gateway_data);
				$donation_payment_gateway_id = $this->db->insertID();
				if (!empty($donation_payment_gateway_id)) {
					header('Location: ' . $response['url']);
					exit;
				}
			}
		}
	}
	public function ipay88_online_response($donation_id)
	{
		include_once FCPATH . 'app/Libraries/ipay88-master/IPay88.class.php';
		$MerchantCode = 'M01236';
		$MerchantKey = 'HQgUUZLVzg';
		$ipay88 = new \IPay88($MerchantCode);
		$ipay88->setMerchantKey($MerchantKey);
		$response = $ipay88->getResponse();
		//print_r($response);
		if ($response['status']) {
			$donation_up_data = array();
			$donation_up_data['payment_status'] = 2;
			$this->db->table('donation')->where('id', $donation_id)->update($donation_up_data);
			$this->account_migration($donation_id);
			$this->session->setFlashdata('succ', 'Donation Successfully Completed');
			$redirect_url = base_url() . '/archanai_booking/print_booking/' . $donation_id;
			header('Location: ' . $redirect_url);
			exit;
		} else {
			$this->session->setFlashdata('fail', 'Payment Failed');
			echo '<script>
    window.onunload = refreshParent;
	window.close();
    function refreshParent() {
        window.opener.location.reload();
    }
</script>';
		}
	}
	public function initiate_ipay_merch_online($donation_id)
	{
		include_once FCPATH . 'app/Libraries/ipay88-master/IPay88.class.php';
		$payment_id = !empty($_REQUEST['payment_id']) ? $_REQUEST['payment_id'] : '';
		$donation_booking = $this->db->table('donation')->where('id', $donation_id)->get()->getRowArray();
		$email = !empty($donation_booking['email']) ? $donation_booking['email'] : 'dd@ipay88.com.my';
		$name = !empty($donation_booking['name']) ? $donation_booking['name'] : 'Prithivi';
		$mobile_no = !empty($donation_booking['mobile']) ? $donation_booking['mobile'] : '9856734562';
		$description = 'Donation';
		$final_amt = $donation_booking['amount'];
		$final_amount = number_format($final_amt, '2', '.', '');
		$final_amt_str = (string) ($final_amt * 1000);
		$MerchantCode = 'M01236';
		$MerchantKey = 'HQgUUZLVzg';
		$ref_no = 'DON_' . $donation_id;
		$refno_pay = $ref_no;
		$module = 'archanai';
		// $final_amount = '1.00';
		// $final_amt_str = '1000';
		$ipay88 = new \IPay88($MerchantCode);
		$ipay88->setMerchantKey($MerchantKey);
		$ipay88->setField('PaymentId', 16);
		$ipay88->setField('RefNo', $refno_pay);
		$ipay88->setField('Amount', $final_amount);
		$ipay88->setField('Currency', 'MYR');
		$ipay88->setField('ProdDesc', $description);
		$ipay88->setField('UserName', $name);
		$ipay88->setField('UserEmail', $email);
		$ipay88->setField('UserContact', $mobile_no);
		$ipay88->setField('Remark', $description);
		$ipay88->setField('Lang', 'utf-8');
		$ipay88->setField('ResponseURL', base_url() . '/donation_online/ipay88_online_response/' . $donation_id);
		$ipay88->setField('BackendURL', base_url() . '/donation_online/ipay88_online_response/' . $donation_id);
		$ipay88->generateSignature();
		$ipay88_fields = $ipay88->getFields();
		$data['ipay88_fields'] = $ipay88_fields;
		$data['epayment_url'] = \Ipay88::$epayment_url;
		$view_file = 'frontend/ipay88/ipay_merch_online_process';
		echo view($view_file, $data);
	}
	public function initiatePayment($amount, $orderid, $address, $city, $email)
	{
		if (file_get_contents('php://input') != '') {
			$request = json_decode(file_get_contents('php://input'), true);
		} else {
			$request = array();
		}
		$apikey = "AQExhmfuXNWTK0Qc+iSGm3I5puqPTYhFHpxGTXFfyXa4nWlGJfnh+XuzwV6dTmmMJv6GnBDBXVsNvuR83LVYjEgiTGAH-09p02SzaBtpvbU0D3ZRFu8cWY44ivj4mqeMXogk0Ogk=-@e*vZIt9AWvaNN:.";
		$merchantAccount = "VivaantechsolutionscomECOM";
		$url = "https://checkout-test.adyen.com/v70/paymentLinks";
		$final_amt = $amount * 100;
		$data = [
			'amount' => [
				'currency' => 'MYR',
				'value' => $final_amt
			],
			"reference" => $orderid,
			'countryCode' => "MY",
			'shopperReference' => "order_" . $orderid,
			'shopperEmail' => $email,
			'shopperLocale' => "en-US",
			"billingAddress" => [
				"street" => $address,
				"postalCode" => "46000",
				"city" => $city,
				"houseNumberOrName" => "1/23",
				"country" => "MY",
				"stateOrProvince" => "KL"
			],
			"deliveryAddress" => [
				"street" => $address,
				"postalCode" => "46000",
				"city" => $city,
				"houseNumberOrName" => "1/23",
				"country" => "MY",
				"stateOrProvince" => "KL"
			],
			'returnUrl' => base_url() . '/donation_online/print_booking/' . $orderid,
			'merchantAccount' => $merchantAccount
		];
		$json_data = json_encode($data);
		$curlAPICall = curl_init();
		curl_setopt($curlAPICall, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curlAPICall, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlAPICall, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($curlAPICall, CURLOPT_URL, $url);
		curl_setopt(
			$curlAPICall,
			CURLOPT_HTTPHEADER,
			array(
				"x-api-key: " . $apikey,
				"Content-Type: application/json",
				"Content-Length: " . strlen($json_data)
			)
		);
		$result = curl_exec($curlAPICall);
		if ($result === false) {
			throw new Exception(curl_error($curlAPICall), curl_errno($curlAPICall));
		}
		curl_close($curlAPICall);
		return $result;
	}
	public function initiatePayment_response($pay_id)
	{
		if (file_get_contents('php://input') != '') {
			$request = json_decode(file_get_contents('php://input'), true);
		} else {
			$request = array();
		}
		$apikey = "AQExhmfuXNWTK0Qc+iSGm3I5puqPTYhFHpxGTXFfyXa4nWlGJfnh+XuzwV6dTmmMJv6GnBDBXVsNvuR83LVYjEgiTGAH-09p02SzaBtpvbU0D3ZRFu8cWY44ivj4mqeMXogk0Ogk=-@e*vZIt9AWvaNN:.";
		$merchantAccount = "VivaantechsolutionscomECOM";
		$url = "https://checkout-test.adyen.com/v70/paymentLinks/" . $pay_id;
		$curlAPICall = curl_init();
		curl_setopt($curlAPICall, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($curlAPICall, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlAPICall, CURLOPT_URL, $url);
		// Api key
		curl_setopt(
			$curlAPICall,
			CURLOPT_HTTPHEADER,
			array(
				"x-api-key: " . $apikey
			)
		);
		$result = curl_exec($curlAPICall);
		if ($result === false) {
			throw new Exception(curl_error($curlAPICall), curl_errno($curlAPICall));
		}
		curl_close($curlAPICall);
		return $result;
	}
	public function account_migration($donation_id)
	{
		$donation = $this->db->table('donation')->where('id', $donation_id)->get()->getRowArray();
		if ($donation['paid_through'] == 'COUNTER') {
			// $donation_payment_gateway_datas = $this->db->table('donation_payment_gateway_datas')->where('donation_booking_id', $donation_id)->get()->getRowArray();
			// if ($donation_payment_gateway_datas['pay_method'] == 'cash')
			// 	$payment_id = 6; ////  goto cash Ledger
			// elseif ($donation_payment_gateway_datas['pay_method'] == 'online')
			// 	$payment_id = 8; ////  goto online Ledger
			// elseif ($donation_payment_gateway_datas['pay_method'] == 'qr')
			// 	$payment_id = 9; ////  goto qr Ledger
			// elseif ($donation_payment_gateway_datas['pay_method'] == 'nets_pay')
			// 	$payment_id = 10; ////  goto nets Ledger
			// elseif ($donation_payment_gateway_datas['pay_method'] == 'pay_now')
			// 	$payment_id = 13; ////  goto Pay Now Ledger
			// elseif ($donation_payment_gateway_datas['pay_method'] == 'cheque')
			// 	$payment_id = 12; ////  goto Cheque Ledger
			// else
			// 	$payment_id = 4; ////  goto Qr or Online Payment Ledger

			$payment_mode_details = $this->db->table('payment_mode')->where('id', $donation['payment_mode'])->get()->getRowArray();
			if (empty($payment_mode_details['id']))
				$payment_mode_details = $this->db->table('payment_mode')->get()->getRowArray();

			/* $ledger = $this->db->table('ledgers')->where('name', 'Donation')->where('group_id', 29)->where('left_code', '7012')->get()->getRowArray();
			if (!empty ($ledger)) {
				$dr_id = $ledger['id'];
			} else {
				$led['group_id'] = 29;
				$led['name'] = 'Donation';
				$led['left_code'] = '7012';
				$led['right_code'] = '000';
				$led['op_balance'] = '0';
				$led['op_balance_dc'] = 'D';
				$led_ins = $this->db->table('ledgers')->insert($led);
				$dr_id = $this->db->insertID();
			} */
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
			$donation_details = $this->db->table('donation_setting')->where('id', $donation['pay_for'])->get()->getRowArray();
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
			$number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
			if (empty($number)) {
				$num = 1;
			} else {
				$num = $number['number'] + 1;
			}
			$date = explode('-', $donation['date']);
			$yr = $date[0];
			$mon = $date[1];
			$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();
			$entries['entry_code'] = 'REC' . date('y', strtotime($donation['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));

			$entries['entrytype_id'] = '1';
			$entries['number'] = $num;
			$entries['date'] = $donation['date'];
			$entries['dr_total'] = $donation['amount'];
			$entries['cr_total'] = $donation['amount'];
			$entries['narration'] = 'Cash Donation(' . $donation['ref_no'] . ')' . "\n" . 'name:' . $donation['name'] . "\n" . 'NRIC:' . $donation['ic_number'] . "\n" . 'email:' . $donation['email'] . "\n";
			$entries['inv_id'] = $donation_id;
			$entries['type'] = '2';
			$ent = $this->db->table('entries')->insert($entries);
			$en_id = $this->db->insertID();
			if (!empty($en_id)) {
				$eitems_d['entry_id'] = $en_id;
				$eitems_d['ledger_id'] = $dr_id;
				$eitems_d['amount'] = $donation['amount'];
				$eitems_d['details'] = 'Cash Donation(' . $donation['ref_no'] . ')';
				$eitems_d['dc'] = 'C';
				$this->db->table('entryitems')->insert($eitems_d);

				$eitems_c['entry_id'] = $en_id;
				$eitems_c['ledger_id'] = $payment_mode_details['ledger_id'];
				$eitems_c['details'] = 'Cash Donation(' . $donation['ref_no'] . ')';
				$eitems_c['amount'] = $donation['amount'];
				$eitems_c['dc'] = 'D';
				$this->db->table('entryitems')->insert($eitems_c);
			}
		}
	}
	public function print_booking($don_book_id)
	{

		$id = $this->request->uri->getSegment(3);

		$data['qry1'] = $donation = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname')
			->select('donation.*')
			->where('donation.id', $id)
			->get()->getRowArray();
		// $view_file = 'frontend/donation/print_page';
		$view_file = 'frontend/donation/print_imin';
		if ($donation['paid_through'] == 'COUNTER') {
			if ($donation['payment_status'] == '2') {
				//$data['qry2'] = $this->db->table('donation_details')->where('donation_id', $id)->get()->getResultArray();
				//echo "<pre>"; print_r($id); exit();
				$tmpid = $this->session->get('profile_id');
				$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
				$data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
				//echo $this->db->getLastQuery();
				//echo "<pre>"; print_r($data); exit();
				echo view($view_file, $data);
			} elseif ($donation['payment_status'] == '1') {
				$donation_payment_gateway_datas = $this->db->table('donation_payment_gateway_datas')->where('donation_booking_id', $don_book_id)->get()->getRowArray();
				if (!empty($donation_payment_gateway_datas['reference_id'])) {
					$reference_id = $donation_payment_gateway_datas['reference_id'];
					$result_data = $this->initiatePayment_response($reference_id);
					$response_data = json_decode($result_data, true);
					$payment_gateway_up_data = array();
					$payment_gateway_up_data['response_data'] = $result_data;
					$this->db->table('donation_payment_gateway_datas')->where('id', $donation_payment_gateway_datas['id'])->update($payment_gateway_up_data);
					if (!empty($response_data['status'])) {
						if ($response_data['status'] == 'completed') {
							$donation_up_data = array();
							$donation_up_data['payment_status'] = 2;
							$this->db->table('donation')->where('id', $id)->update($donation_up_data);
							$this->account_migration($id);
							$tmpid = $this->session->get('profile_id');
							$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
							$data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
							echo view($view_file, $data);
						} else {
							$donation_up_data = array();
							$donation_up_data['payment_status'] = 3;
							$this->db->table('donation')->where('id', $id)->update($donation_up_data);
							redirect()->to("/cancelled_booking");
							exit;
						}
					}
				} else {
					redirect()->to("/cancelled_booking");
					exit;
				}
			}
		} else {
			$tmpid = 1;
			$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
			//echo $this->db->getLastQuery();
			//echo "<pre>"; print_r($data); exit();
			echo view($view_file, $data);
		}
	}

	public function print_report($don_book_id)
	{
		$id = $this->request->uri->getSegment(3);
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		$data['data'] = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->join('payment_mode as pm', 'pm.id = donation.payment_mode', 'left')
			->select('donation_setting.name as pname, donation.amount, donation.name, donation.mobile')
			->select('donation.*')
			->where('donation.id', $id)
			->get()->getRowArray();
		$data['pay_details'] = $this->db->table("donation_payment_gateway_datas")->where("donation_booking_id", $id)->get()->getResultArray();
		// print_r($data['data']);
		// exit;
		echo view('frontend/donation/print_report', $data);
	}
	public function print_report_a5($id)
	{
		// Fetch the donation details by ID
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		$data['data'] = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->join('payment_mode as pm', 'pm.id = donation.payment_mode', 'left')
			->join('donation_payment_gateway_datas as dpgd', 'dpgd.donation_booking_id = donation.id')
			->select('donation.*, donation_setting.name as pname, dpgd.pay_method')
			->where('donation.id', $id)
			->get()
			->getRowArray();



		echo view('frontend/report/donation_print_a5', $data);
	}

	public function get_donation_amount()
	{
		$id = $_POST['id'];
		$res = $this->db->table('donation_setting')->where('id', $id)->get()->getRowArray();
		echo !empty($res['amount']) ? $res['amount'] : 0;
	}
	public function reprint_booking($id)
	{
		$data['qry1'] = $donation = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname')
			->select('donation.*')
			->where('donation.id', $id)
			->get()->getRowArray();
		// $view_file = 'frontend/donation/print_page';
		$view_file = 'frontend/donation/print_imin';
		$tmpid = 1;
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		$data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
		echo view($view_file, $data);
	}
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

	public function payment_check()
	{
		$data = [];
		if (!empty($_REQUEST['donation_id'])) {
			$booking_id = $_REQUEST['donation_id'];
			$user_id = $_SESSION['log_id_frend'];

			// Check if booking exists
			$donation_cnt = $this->db->table('donation')
				->where('id', $booking_id)
				->countAllResults(false); // false to NOT reset query builder to allow reuse if needed

			if ($donation_cnt > 0) {
				try {
					// Get booking record
					$donation = $this->db->table('donation')
						->where('id', $booking_id)
						->get()
						->getRow();

					// Get payment gateway data
					$payment_gateway_datas = $this->db->table('donation_payment_gateway_datas')->select('donation_payment_gateway_datas.*, payment_mode.pay_key')->join('payment_mode', 'payment_mode.id = donation_payment_gateway_datas.payment_mode', 'left')->where('donation_payment_gateway_datas.donation_booking_id', $booking_id)->get()->getRowArray();
					if ($donation->payment_status == 1) {
						if ($payment_gateway_datas['pay_key'] == 'rhb_qr' || $payment_gateway_datas['pay_key'] == 'eghl_qr') {
							$rtn = $payment_gateway_datas['pay_key'] == 'eghl_qr'
								? $this->initiate_eghl_qr($booking_id, $donation, $payment_gateway_datas)
								: $this->initiate_rhb_qr($booking_id, $donation, $payment_gateway_datas);
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
									'error_msg' => "We’re sorry! your payment is failed. Kindly try again1.",
								);
								return json_encode($data);
							} else {
								$data = array(
									'status' => false,
									'pay_status' => false,
									'order_status' => 'unidentify',
									'org_msg' => $rtn['org_msg'],
									'error_msg' => "We’re sorry! your payment didn’t went through, kindly try again. If payment has been deducted but Donation didn’t print. Kindly contact the Bank or Payment Gateway",
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
					} elseif ($donation->payment_status == 3) {
						$data = [
							'status' => true,
							'pay_status' => false,
							'order_status' => 'failed',
							'org_msg' => 'Transaction Failed',
							'error_msg' => "We’re sorry! your payment is failed. Kindly try again2.",
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
	public function initiate_rhb_qr($donation_id, $donation, $payment_gateway_datas)
	{
		$request_data = json_decode($payment_gateway_datas['request_data']);
		$payment_gateway_datas_id = $payment_gateway_datas['id'];
		$rtn = [];

		if (!empty($request_data->billNumber) && !empty($request_data->transactionReference)) {
			$merchant_id = config('Variables')->rhb_userId;  // Adjust if needed

			$json_data = [
				'billNumber' => $request_data->billNumber,
				'userId' => RHB_USERID,
				'referenceNo' => $request_data->transactionReference,
			];

			$url = 'https://dnqr.synexisasia.com/v1/qr/status';

			// Call your common_repository method to get JSON response
			$response = $this->common_model->getJson($url, $json_data);


			// Update archanai_payment_gateway_datas table with response_data
			$this->db->table('donation_payment_gateway_datas')
				->where('id', $payment_gateway_datas_id)
				->update(['response_data' => $response]);
			$response_data = json_decode($response);

			if (!empty($response_data->paymentStatus)) {
				if ($response_data->paymentStatus === 'FOUND') {
					$rtn['status'] = 'success';

					// Update archanai_booking payment_status = 2
					$this->db->table('donation')
						->where('id', $donation_id)
						->update(['payment_status' => 2]);

					// Call migration functions
					$this->account_migration($donation_id);
					$this->send_whatsapp_msg($donation_id);
					$this->send_mail_to_customer($donation_id);

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

	public function initiate_eghl_qr($donation_id, $donation, $payment_gateway_datas){
		$request_data = json_decode($payment_gateway_datas['request_data']);
		$payment_gateway_datas_id = $payment_gateway_datas['id'];
		$rtn = [];

		if (empty($request_data->msg->RetTxnRef) || empty($request_data->msg->TxnRef)) {
			$rtn['status'] = 'unidentify';
			$rtn['org_msg'] = 'Server Down';
			return $rtn;
		}

		try {
			$pay_amount = EGHL_TEST ? 1 : bcdiv($donation->amount, '1', 2);

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
					. ' | donation_id=' . $donation_id
					. ' | amount=' . $donation->amount
					. ' | status=unidentify'
					. ' | error_code=SIGNATURE'
					. ' | message=EGHL query response failed signature verification'
					. ' | datetime=' . date('Y-m-d H:i:s'));
				$rtn['status'] = 'unidentify';
				$rtn['org_msg'] = 'Server Down';
				return $rtn;
			}

			$this->db->table('donation_payment_gateway_datas')->where('id', $payment_gateway_datas_id)->update(['response_data' => $response]);
			$response_data = json_decode($response);

			if (isset($response_data->msg->OrgResponseCode) && isset($response_data->msg->OrgResponseMsg)) {
				if ($response_data->msg->OrgResponseCode != 'PN') {
					if ($response_data->msg->OrgResponseCode == '00') {
						$rtn['status'] = 'success';
						$this->db->table('donation')->where('id', $donation_id)->update(['payment_status' => 2]);
						$this->account_migration($donation_id);
						$this->send_whatsapp_msg($donation_id);
						$this->send_mail_to_customer($donation_id);
					} else {
						$this->db->table('donation')->where('id', $donation_id)->update(['payment_status' => 3]);
						$rtn['status'] = 'failed';
						log_message('error', 'EGHL_QR_QUERY_FAILED | user_id=' . ($this->session->get('log_id_frend') ?? '')
							. ' | donation_id=' . $donation_id
							. ' | amount=' . $donation->amount
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
				. ' | donation_id=' . $donation_id
				. ' | amount=' . $donation->amount
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

		if (!empty($_REQUEST['donation_id'])) {
			$booking_id = $_REQUEST['donation_id'];
			$user_id = $_SESSION['log_id_frend'];

			// Check if booking exists
			$donation_cnt = $this->db->table('donation')
				->where('id', $booking_id)
				->countAllResults(false); // false to NOT reset query builder to allow reuse if needed

			if ($donation_cnt > 0) {
				try {
					// Get booking record
					$donation = $this->db->table('donation')
						->where('id', $booking_id)
						->get()
						->getRow();

					// Get payment gateway data
					$payment_gateway_datas = $this->db->table('donation_payment_gateway_datas')
						->where('donation_booking_id', $booking_id)
						->get()
						->getRowArray();

					if ($donation->payment_status == 1) {
						if (!empty($payment_gateway_datas) && ($payment_gateway_datas['pay_method'] === 'RHB QR' || $payment_gateway_datas['pay_method'] === 'EGHL QR')) {
							$rtn = $payment_gateway_datas['pay_method'] === 'EGHL QR'
								? $this->initiate_eghl_qr($booking_id, $donation, $payment_gateway_datas)
								: $this->initiate_rhb_qr($booking_id, $donation, $payment_gateway_datas);

							if ($rtn['status'] == 'success') {
								$data = [
									'status' => true,
									'pay_status' => true,
									'order_status' => 'success',
									'org_msg' => $rtn['org_msg'],
									'error_msg' => "Thank you for using SMMDT Self Kiosk",
								];
								return json_encode($data);
							} elseif ($rtn['status'] == 'failed') {
								// Gateway genuinely declined the transaction - safe to close it out.
								$data = [
									'status' => true,
									'pay_status' => false,
									'order_status' => 'failed',
									'org_msg' => 'Transaction Failed',
									'error_msg' => "We’re sorry! your payment is failed. Kindly try again3.",
								];
								return json_encode($data);
							} else {
								// Still pending, or the gateway couldn't be reached - don't
								// mark it failed on a guess. Leave payment_status as-is so a
								// later check can still catch a genuine success.
								$data = [
									'status' => true,
									'pay_status' => false,
									'order_status' => 'unidentify',
									'org_msg' => $rtn['org_msg'] ?? 'Server Down',
									'error_msg' => "Payment failed. Kindly try again.",
								];
								return json_encode($data);
							}
						} else {
							// Update payment_status to 3 = failed
							$this->db->table('donation')->where('id', $booking_id)->update(['payment_status' => 3]);

							$data = [
								'status' => true,
								'pay_status' => false,
								'order_status' => 'failed',
								'org_msg' => 'Transaction Failed',
								'error_msg' => "We’re sorry! your payment is failed. Kindly try again4.",
							];
							return json_encode($data);
						}
					} elseif ($donation->payment_status == 3) {
						$data = [
							'status' => true,
							'pay_status' => false,
							'order_status' => 'failed',
							'org_msg' => 'Transaction Failed',
							'error_msg' => "We’re sorry! your payment is failed. Kindly try again5.",
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


	public function send_whatsapp_ultramsg($donation_id)
	{
		$donation = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->join('donation_payment_gateway_datas as dpgd', 'dpgd.donation_booking_id = donation.id', 'left')
			->select('donation_setting.name as pname, donation.*, dpgd.pay_method')
			->where('donation.id', $donation_id)
			->get()->getRowArray();

		if (!$donation || empty($donation['mobile']) || $donation['whatsapp_status'] == 1) {
			return false;
		}

		try {
			// Generate PDF
			helper('common_helper');
			$data['donation'] = $donation;
			$data['payment_mode'] = ucwords($donation['pay_method'] ?? 'Cash');

			$html = view('donation/pdf_template', $data);

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

			$mobile_number = $donation['mobile'];

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

			// Send WhatsApp
			$response = whatsapp_ultramsg([$mobile_number], 'donation', $message_params, $media);

			// Log for debugging (won't interfere with JSON response)
			log_message('info', 'WhatsApp Response: ' . json_encode($response));
			log_message('info', 'Mobile Number: ' . $mobile_number);

			// Update status if successful
			if (isset($response['sent']) && ($response['sent'] === 'true' || $response['sent'] === true)) {
				$this->db->table('donation')
					->where('id', $donation_id)
					->update(['whatsapp_status' => 1]);
			}

			return $response;
		} catch (\Exception $e) {
			log_message('error', 'WhatsApp error: ' . $e->getMessage());
			return false;
		}
	}
	public function test_whatsapp()
	{
		// Test function - you can call this via URL to test
		helper('common_helper');

		$test_number = ["+917094176551"]; // Replace with your test number
		$params = [
			':devotee' => 'Ajith',
			':amount' => '500000.00',
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
		helper('common_helper');

		// Test with your actual phone number (include country code)
		$test_number = ["+919655500357"]; // Replace with your actual number

		$params = [
			':devotee' => 'Test User',
			':amount' => '10.00',
			':ref_no' => 'TEST001',
			':donation_date' => date('d M Y')
		];

		echo "<h3>Testing WhatsApp Integration</h3>";
		echo "<p><strong>Phone Number:</strong> " . implode(", ", $test_number) . "</p>";
		echo "<p><strong>Parameters:</strong></p>";
		echo "<pre>" . print_r($params, true) . "</pre>";

		$response = whatsapp_ultramsg($test_number, 'donation', $params);

		echo "<p><strong>API Response:</strong></p>";
		echo "<pre>" . print_r($response, true) . "</pre>";

		// Also test with document
		echo "<hr><h4>Testing with Document</h4>";
		$media = [
			'url' => base_url() . '/uploads/documents/invoice_donation_35.pdf', // Use any existing PDF for testing
			'filename' => 'test_document.pdf'
		];

		$response2 = whatsapp_ultramsg($test_number, 'donation', $params, $media);
		echo "<pre>" . print_r($response2, true) . "</pre>";
	}
}
