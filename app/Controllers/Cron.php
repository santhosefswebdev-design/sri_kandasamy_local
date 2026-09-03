<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use App\Models\HallbookingModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Cron extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		helper('common_helper');
		$this->model = new PermissionModel();
	}
	public function hallbook_remainder_notification()
	{
		$email = \Config\Services::email();
		$profile_id = 1;
		$query = $this->db->table('admin_profile')->where('id', $profile_id)->get()->getRowArray();
		$days = $query['hall_remind'];
		if ($days != 0 || !empty($days)) {
			$hallremind_days = $days;
		} else {
			$hallremind_days = 5;
		}
		$lists = $this->db->query("SELECT *, abs(datediff(DATE_FORMAT(hall_booking.booking_date, '%Y-%m-%d'), NOW())) as interval_date FROM hall_booking WHERE DATE_FORMAT(hall_booking.booking_date, '%Y-%m-%d') >= NOW() AND DATE_FORMAT(hall_booking.booking_date, '%Y-%m-%d')  < NOW() + INTERVAL $hallremind_days DAY and paid_amount < total_amount;")->getResultArray();
		foreach ($lists as $row) {
			if (!empty($row['email'])) {
				$interval_date = $row['interval_date'];
				$html = "Hi, ";
				$html .= "Your booking has remaining $interval_date days to schedule, You need to pay the remaining amount.";
				$to = $row['email'];
				$subject = "Hall Booking Reminder";
				$message = $html;
				$email->setTo($to);
				$email->setFrom('templetest@grasp.com.my', 'Temple Rajamariamman');
				// $email->setNewline("\r\n");
				$email->setSubject($subject);
				$email->setMessage($message);
				$email->send();
			}
		}
	}
	function testmail()
	{
		$email = \Config\Services::email();
		$html = "Hi, ";
		$html .= "this is test mail";
		$to = "rajkumar.bizsoft@gmail.com";
		$subject = "Test Mail";
		$message = $html;
		$email->setTo($to);
		$email->setFrom('templetest@grasp.com.my', 'Test Mail');
		// $email->setNewline("\r\n");
		$email->setSubject($subject);
		$email->setMessage($message);
		$email->send();
		//echo $email->print_debugger();
	}
	function daily_closing($mobile = '')
	{
		$tmpid = 1;
		$dailyclosing_start_date = date('Y-m-d');
		$dailyclosing_end_date = date("Y-m-d");
		$archanai_data_online = daily_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$archanai_diety_data_online = daily_diety_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['archanai_diety_details'] = $archanai_diety_data_online;
		$data['archanai_details'] = $archanai_data_online;
		$hallbooking_data_online = daily_hall_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['hallbooking_details'] = $hallbooking_data_online;
		$ubayam_data = daily_ubayam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['ubayam_details'] = $ubayam_data;
		$donation_data = daily_donation_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['donation_details'] = $donation_data;
		$prasadam_data = daily_prasadam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['prasadam_details'] = $prasadam_data;
		$data['temp_details'] = $temp_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		$mobile_number = array();
		if (empty($mobile) && !empty($temp_details['daily_closing_phone'])) {
			$daily_closing_phone = json_decode($temp_details['daily_closing_phone'], true);
			if (!empty($daily_closing_phone[0]['phonecode']) && !empty($daily_closing_phone[0]['phoneno'])) {
				foreach ($daily_closing_phone as $dcp) {
					$mobile_number[] = $dcp['phonecode'] . $dcp['phoneno'];
				}
			}
		} else $mobile_number[] = $mobile;
		if (count($mobile_number) > 0) {
			foreach ($mobile_number as $mn) {
				$html = view('daily_closing/pdf', $data);
				// echo $html;
				// die;
				$options = new Options();
				$options->set('isHtml5ParserEnabled', true);
				$options->set(array('isRemoteEnabled' => true));
				$options->set('isPhpEnabled', true);
				$dompdf = new Dompdf($options);
				$dompdf->loadHtml($html);
				$dompdf->setPaper('A4', 'portrait');
				$dompdf->render();
				$filePath = FCPATH . 'uploads/documents/daily_closing_' . time() . '.pdf';
				file_put_contents($filePath, $dompdf->output());
				$message_params = array();
				$media['url'] = base_url() . '/uploads/documents/daily_closing_' . time() . '.pdf';
				$media['filename'] = 'daily_closing.pdf';
				$mobile = '+919092615446';
				// print_r($mobile);
				// print_r($message_params);
				// print_r($media);
				// die; 
				$whatsapp_resp = whatsapp_aisensy($mn, $message_params, 'daily_closing_live', $media);
				//print_r($whatsapp_resp);
			}
		}
	}
	function hall_booking_remainder($mobile = '')
	{
		$this->hallmodal = new HallbookingModel();
		$profile_id = 1;
		$query = $this->db->table('admin_profile')->where('id', $profile_id)->get()->getRowArray();
		$days = $query['hall_remind'];
		if ($days != 0 || !empty($days)) {
			$hallremind_days = $days;
		} else {
			$hallremind_days = 5;
		}
		$remainder = $this->db->query("SELECT *, abs(datediff(DATE_FORMAT(hall_booking.booking_date, '%Y-%m-%d'), NOW())) as interval_date FROM hall_booking WHERE DATE_FORMAT(hall_booking.booking_date, '%Y-%m-%d') >= NOW() AND DATE_FORMAT(hall_booking.booking_date, '%Y-%m-%d')  < NOW() + INTERVAL $hallremind_days DAY and paid_amount < total_amount;")->getResultArray();
		if (count($remainder) > 0) {
			foreach ($remainder as $rm) {
				$this->hallmodal->send_whatsapp_msg($rm['id']);
			}
		}
	}
	function rental_remainder($mobile = '')
	{
		$str_to_date_convert = date("Y-m");
		$datalist = $this->db->query("SELECT tennant.phone as phone_no,tennant.name as tennant_name, properties.id as property_id, properties.name as property_name,properties.rental_value as amount, properties.due_date FROM properties JOIN tennant ON tennant.property_id = properties.id WHERE '$str_to_date_convert' BETWEEN DATE_FORMAT(tennant.start_date,'%Y-%m') AND DATE_FORMAT(tennant.end_date,'%Y-%m') AND tennant.status = 1 ")->getResultArray();
		if (count($datalist) > 0) {
			foreach ($datalist as $roww) {
				$paid_rental = $this->db->table("rental")->join('rental_pay_details', 'rental_pay_details.rental_id = rental.id')->select('SUM(rental_pay_details.amount) as paidamt')->where("rental.property_id", $roww['property_id'])->where("rental.month_year", $rental_monthyear)->groupBy('rental_pay_details.rental_id')->get()->getRowArray();
				if (floatval($paid_rental['paidamt']) == floatval($roww['amount']) || floatval($paid_rental['paidamt']) > floatval($roww['amount'])) {
					//echo "Full Paid";
				} else {
					//echo "Half Paid";
					$pending_amt = $roww['amount'] - $paid_rental['paidamt'];
					$due_date = $str_to_date_convert . "-01";
					$converted_month = date("M", strtotime($due_date));
					$retn_array[] = array("phone_no" => $roww['phone_no'], "tennant_name" => $roww['tennant_name'], "property_id" => $roww['property_id'], "property_name" => $roww['property_name'], "amount" => $roww['amount'], "pending_amount" => $pending_amt, "due_month" => $converted_month);
					$due_date = !empty($roww['due_date']) ? str_pad($roww['due_date'], 2, "0", STR_PAD_LEFT) . '/' . date("m/Y") : '';
					if (!empty($due_date)) {
						if (date("Y-m") . '-' . str_pad($roww['due_date'], 2, "0", STR_PAD_LEFT) >= date("Y-m-d")) {
							$message_params = array();
							$message_params[] = $roww['tennant_name'];
							$message_params[] = date("M, Y");
							$message_params[] = ' ' . $due_date;
							$message_params[] = (string) $pending_amt;
							$mobile_number = $roww['phone_no'];
							$mobile_number = '+60146488869';
							//$mobile_number = '+919092615446';
							/* print_r($mobile_number);
							print_r($message_params);
							die;  */
							$whatsapp_resp = whatsapp_aisensy($mobile_number, $message_params, 'rental_live');
							/* print_r($whatsapp_resp);
							die; */
						}
					} else {
						$message_params = array();
						$message_params[] = $roww['tennant_name'];
						$message_params[] = date("M, Y");
						$message_params[] = $due_date;
						$message_params[] = (string) $pending_amt;
						$mobile_number = $roww['phone_no'];
						$mobile_number = '+60146488869';
						//$mobile_number = '+919092615446';
						/* print_r($mobile_number);
						print_r($message_params);
						die;  */
						$whatsapp_resp = whatsapp_aisensy($mobile_number, $message_params, 'rental_live');
						/* print_r($whatsapp_resp);
						die; */
					}
				}
			}
		}
	}
	function member_renewal_update()
	{
		$current_date = date("Y-m-d");
		$member_renewal_data = $this->db->table('member')->where('member.end_date <=', $current_date)->get()->getResultArray();
		if (count($member_renewal_data) > 0) {
			foreach ($member_renewal_data as $row) {
				$member_id = $row['id'];
				$data['status'] = 2;
				$this->db->table('member')->where('id', $member_id)->update($data);
				return true;
			}
		}
	}
	public function prasadam_payment_reminder()
	{
		// Fixed reminder: 3 days before collection date
		$reminder_date = date('Y-m-d', strtotime("+3 days"));

		// Get prasadam bookings with partial payments and upcoming collection dates
		$lists = $this->db->query("
        SELECT p.*, 
               (p.total_amount - p.paid_amount) as pending_amount,
               ABS(DATEDIFF(p.collection_date, NOW())) as days_remaining
        FROM prasadam p
        WHERE p.collection_date = '$reminder_date'
          AND p.payment_status = 1
          AND p.paid_amount < p.total_amount
          AND p.mobile_no IS NOT NULL
          AND p.mobile_no != ''
    ")->getResultArray();

		$sent_count = 0;
		$failed_count = 0;

		foreach ($lists as $row) {
			if (!empty($row['mobile_no'])) {
				$pending_amount = number_format($row['pending_amount'], 2);
				$collection_date = date('d M Y', strtotime($row['collection_date']));

				// Prepare WhatsApp message parameters
				$message_params = array();
				$message_params[':devotee'] = $row['customer_name'];
				$message_params[':ref_no'] = $row['ref_no'];
				$message_params[':collection_date'] = $collection_date;
				$message_params[':collection_time'] = $row['serve_time'];
				$message_params[':pending_amount'] = $pending_amount;
				$message_params[':total_amount'] = number_format($row['total_amount'], 2);
				$message_params[':paid_amount'] = number_format($row['paid_amount'], 2);

				// Send WhatsApp message
				$mobile_number = $row['mobile_no'];
				$whatsapp_resp = whatsapp_ultramsg(
					[$mobile_number],
					'prasadam_payment_reminder',
					$message_params
				);

				// Log the response
				if (isset($whatsapp_resp['sent']) && $whatsapp_resp['sent']) {
					$sent_count++;
					log_message('info', "Prasadam payment reminder sent to {$mobile_number} for booking {$row['ref_no']}");
				} else {
					$failed_count++;
					log_message('error', "Failed to send prasadam payment reminder to {$mobile_number} for booking {$row['ref_no']}");
				}
			}
		}

		// Log summary
		log_message('info', "Prasadam Payment Reminders: Sent={$sent_count}, Failed={$failed_count}");

		// Return JSON response
		echo json_encode([
			'status' => 'completed',
			'sent_count' => $sent_count,
			'failed_count' => $failed_count,
			'reminder_date' => $reminder_date,
			'total_processed' => count($lists),
			'timestamp' => date('Y-m-d H:i:s')
		]);
	}


	// In app/Controllers/Cron.php
	public function master_run()
	{
		// Security check
		$request = \Config\Services::request();
		$secret_key = 'temple_master_cron_2024_secure';
		$provided_key = $request->getGet('key') ?: $request->getPost('key');

		if ($provided_key !== $secret_key) {
			echo json_encode(['error' => 'Unauthorized access']);
			return;
		}

		// Copy all the CronMaster run logic here
		$this->lockFile = WRITEPATH . 'cache/cron_master.lock';
		$this->maxExecutionTime = 270;
		$this->startTime = microtime(true);

		// Check if already running
		if (file_exists($this->lockFile)) {
			$lockTime = filemtime($this->lockFile);
			if (time() - $lockTime > 600) {
				unlink($this->lockFile);
			} else {
				echo json_encode([
					'status' => 'skipped',
					'reason' => 'Another cron instance is already running',
					'timestamp' => date('Y-m-d H:i:s')
				]);
				return;
			}
		}
		file_put_contents($this->lockFile, getmypid());

		$results = [
			'status' => 'started',
			'timestamp' => date('Y-m-d H:i:s'),
			'jobs_processed' => []
		];

		try {
			// Initialize CronProcessor if needed
			if (class_exists('App\Libraries\CronProcessor')) {
				$this->processor = new \App\Libraries\CronProcessor();
			}

			// Get active jobs count
			$testQuery = $this->db->query("SELECT COUNT(*) as count FROM cron_jobs WHERE is_active = 1");
			$result = $testQuery->getRow();
			$results['active_jobs'] = $result->count;

			// Process instant jobs
			$results['instant'] = $this->process_instant_jobs();

			// Process scheduled jobs
			$results['scheduled'] = $this->process_scheduled_jobs();

			// Process queue
			$results['queue'] = $this->process_queue_items();

			$results['status'] = 'completed';
		} catch (\Exception $e) {
			$results['status'] = 'error';
			$results['error'] = $e->getMessage();
		} finally {
			if (file_exists($this->lockFile)) {
				unlink($this->lockFile);
			}
		}

		$results['execution_time'] = round(microtime(true) - $this->startTime, 2);
		echo json_encode($results);
	}

	private function process_instant_jobs()
	{
		$results = [];

		$jobs = $this->db->table('cron_jobs')
			->where('is_active', 1)
			->where('schedule_type', 'instant')
			->get()->getResultArray();

		foreach ($jobs as $job) {
			if (isset($this->processor)) {
				$jobResult = $this->processor->processInstantJob($job);
			} else {
				$jobResult = ['processed' => 0, 'failed' => 0];
			}
			$results[$job['module'] . '_' . $job['action']] = $jobResult;
		}

		return $results;
	}

	private function process_scheduled_jobs()
	{
		$results = [];
		$now = date('Y-m-d H:i:s');

		$jobs = $this->db->table('cron_jobs')
			->where('is_active', 1)
			->whereIn('schedule_type', ['scheduled', 'recurring'])
			->where('next_run_at <=', $now)
			->orderBy('priority', 'DESC')
			->get()->getResultArray();

		foreach ($jobs as $job) {
			try {
				// Process the job based on module
				$jobResult = $this->execute_job($job);

				// Update next run time for recurring jobs
				if ($job['schedule_type'] === 'recurring') {
					$this->update_next_run_time($job['id'], $job['execute_at']);
				}

				$results[$job['job_name']] = $jobResult;
			} catch (\Exception $e) {
				$results[$job['job_name']] = ['status' => 'failed', 'error' => $e->getMessage()];
			}
		}

		return $results;
	}

	private function process_queue_items()
	{
		$results = ['processed' => 0, 'failed' => 0];

		$items = $this->db->table('cron_queue')
			->where('status', 'pending')
			->where('attempts <', 3)
			->orderBy('created_at', 'ASC')
			->limit(10)
			->get()->getResultArray();

		foreach ($items as $item) {
			try {
				$this->db->table('cron_queue')
					->where('id', $item['id'])
					->update(['status' => 'processing', 'attempts' => $item['attempts'] + 1]);

				if (isset($this->processor)) {
					$processed = $this->processor->processQueueItem($item);
				} else {
					$processed = false;
				}

				if ($processed) {
					$this->db->table('cron_queue')
						->where('id', $item['id'])
						->update(['status' => 'completed', 'processed_at' => date('Y-m-d H:i:s')]);
					$results['processed']++;
				} else {
					throw new \Exception('Failed to process queue item');
				}
			} catch (\Exception $e) {
				$this->db->table('cron_queue')
					->where('id', $item['id'])
					->update([
						'status' => $item['attempts'] >= 2 ? 'failed' : 'pending',
						'error_message' => $e->getMessage()
					]);
				$results['failed']++;
			}

			sleep(2);
		}

		return $results;
	}

	private function execute_job($job)
	{
		// Add your job execution logic here
		// For now, just call your existing prasadam_payment_reminder if needed
		if ($job['module'] == 'prasadam' && $job['action'] == 'payment_reminder') {
			$this->prasadam_payment_reminder();
			return ['sent' => 1, 'status' => 'completed'];
		}

		return ['status' => 'skipped', 'message' => 'Job execution not implemented'];
	}

	private function update_next_run_time($jobId, $executeAt)
	{
		$nextRun = date('Y-m-d') . ' ' . $executeAt;

		if (strtotime($nextRun) <= time()) {
			$nextRun = date('Y-m-d', strtotime('+1 day')) . ' ' . $executeAt;
		}

		$this->db->table('cron_jobs')
			->where('id', $jobId)
			->update([
				'last_run_at' => date('Y-m-d H:i:s'),
				'next_run_at' => $nextRun
			]);
	}
}
