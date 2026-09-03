<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Dashboard extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		helper('common');
		$this->model = new PermissionModel();
		if (($this->session->get('login')) == false && $this->session->get('role') != 1) {
			$data['dn_msg'] = 'Please Login';
			header('Location: ' . base_url() . '/login');
			exit;
		}
	}

	public function index()
	{
		$data['view'] = true;
		if (!$this->model->list_validate('dashboard')) {
			$data['view'] = false;
		}

		$dt = date('Y-m-d');
		$res['data'] = $this->db->table('admin_profile')->get()->getRowArray();

		// Archanai Data
		$data['archanai'] = $this->db->query("
            SELECT 
                archanai.id,
                archanai.name_eng,
                archanai.name_tamil,
                SUM(archanai_booking_details.quantity) as tQty, 
                SUM(archanai_booking_details.total_amount + archanai_booking_details.total_commision) as tAmt 
            FROM archanai 
            JOIN archanai_booking_details ON archanai.id = archanai_booking_details.archanai_id 
            JOIN archanai_booking ON archanai_booking.id = archanai_booking_details.archanai_booking_id 
            WHERE archanai_booking.date = '$dt' 
            AND (
                ((archanai_booking.paid_through = 'ONLINE' OR archanai_booking.paid_through = 'COUNTER') AND archanai_booking.payment_status = 2) 
                OR (archanai_booking.paid_through = 'Direct')
            ) 
            GROUP BY archanai.id 
            ORDER BY archanai.id
        ")->getResultArray();

		// Prasadam Data
		$data['prasadam'] = $this->db->query("
            SELECT 
                prasadam_setting.id,
                prasadam_setting.name_eng,
                prasadam_setting.name_tamil,
                SUM(prasadam_booking_details.quantity) as tQty, 
                SUM(prasadam_booking_details.total_amount) as tAmt 
            FROM prasadam_setting 
            JOIN prasadam_booking_details ON prasadam_setting.id = prasadam_booking_details.prasadam_id 
            JOIN prasadam ON prasadam.id = prasadam_booking_details.prasadam_booking_id 
            WHERE prasadam.date = '$dt' 
            AND (
                ((prasadam.paid_through = 'ONLINE' OR prasadam.paid_through = 'COUNTER') AND prasadam.payment_status = 2) 
                OR (prasadam.paid_through = 'Direct')
            ) 
            GROUP BY prasadam_setting.id 
            ORDER BY prasadam_setting.id
        ")->getResultArray();

		// Donation Data
		$data['donation'] = $this->db->query("
            SELECT 
                donation_setting.id,
                donation_setting.name as dname, 
                SUM(donation.amount) as amount 
            FROM donation 
            JOIN donation_setting ON donation_setting.id = donation.pay_for 
            WHERE donation.date = '$dt' 
            AND (
                ((donation.paid_through = 'ONLINE' OR donation.paid_through = 'COUNTER') AND donation.payment_status = 2) 
                OR (donation.paid_through = 'Direct')
            ) 
            GROUP BY donation.pay_for 
            ORDER BY donation.id DESC
        ")->getResultArray();

		// Ubayam Data (from templebooking where booking_type = 2)
		$data['ubayam'] = $this->db->query("
            SELECT 
                temple_packages.id,
                temple_packages.name as ubname, 
                COUNT(templebooking.id) as tQty,
                SUM(templebooking.paid_amount) as amount 
            FROM templebooking 
            JOIN temple_packages ON temple_packages.id = templebooking.venue 
            WHERE templebooking.booking_type = 2 
            AND templebooking.booking_date = '$dt' 
            AND templebooking.booking_status IN (1, 2)
            AND (
                ((templebooking.booking_through = 'ONLINE' OR templebooking.booking_through = 'COUNTER') AND templebooking.payment_status = 2) 
                OR (templebooking.booking_through = 'DIRECT')
            ) 
            GROUP BY temple_packages.id 
            ORDER BY temple_packages.id
        ")->getResultArray();

		// Calculate totals
		$data['archanai_total'] = array_sum(array_column($data['archanai'], 'tAmt'));
		$data['prasadam_total'] = array_sum(array_column($data['prasadam'], 'tAmt'));
		$data['donation_total'] = array_sum(array_column($data['donation'], 'amount'));
		$data['ubayam_total'] = array_sum(array_column($data['ubayam'], 'amount'));
		$data['grand_total'] = $data['archanai_total'] + $data['prasadam_total'] + $data['donation_total'] + $data['ubayam_total'];

		// Charts data
		$data["archanai_charts"] = $this->getMonthlyChartData('archanai');
		$data["prasadam_charts"] = $this->getMonthlyChartData('prasadam');
		$data["donation_charts"] = $this->getMonthlyChartData('donation');
		$data["ubayam_charts"] = $this->getMonthlyChartData('ubayam');

		echo view('template/header', $res);
		echo view('template/sidebar');
		echo view('template/content', $data);
		echo view('template/footer');
	}

	private function getMonthlyChartData($type)
	{
		$year = date('Y');
		$months = [];

		for ($m = 1; $m <= 12; $m++) {
			$startDate = "$year-" . str_pad($m, 2, '0', STR_PAD_LEFT) . "-01";
			$endDate = date("Y-m-t", strtotime($startDate));

			switch ($type) {
				case 'archanai':
					$query = $this->db->query("
                        SELECT COALESCE(SUM(archanai_booking_details.total_amount + archanai_booking_details.total_commision), 0) as total 
                        FROM archanai_booking 
                        JOIN archanai_booking_details ON archanai_booking.id = archanai_booking_details.archanai_booking_id 
                        WHERE archanai_booking.date BETWEEN '$startDate' AND '$endDate'
                        AND (
                            ((archanai_booking.paid_through = 'ONLINE' OR archanai_booking.paid_through = 'COUNTER') AND archanai_booking.payment_status = 2) 
                            OR (archanai_booking.paid_through = 'Direct')
                        )
                    ");
					break;
				case 'prasadam':
					$query = $this->db->query("
                        SELECT COALESCE(SUM(prasadam_booking_details.total_amount), 0) as total 
                        FROM prasadam 
                        JOIN prasadam_booking_details ON prasadam.id = prasadam_booking_details.prasadam_booking_id 
                        WHERE prasadam.date BETWEEN '$startDate' AND '$endDate'
                        AND (
                            ((prasadam.paid_through = 'ONLINE' OR prasadam.paid_through = 'COUNTER') AND prasadam.payment_status = 2) 
                            OR (prasadam.paid_through = 'Direct')
                        )
                    ");
					break;
				case 'donation':
					$query = $this->db->query("
                        SELECT COALESCE(SUM(amount), 0) as total 
                        FROM donation 
                        WHERE date BETWEEN '$startDate' AND '$endDate'
                        AND (
                            ((paid_through = 'ONLINE' OR paid_through = 'COUNTER') AND payment_status = 2) 
                            OR (paid_through = 'Direct')
                        )
                    ");
					break;
				case 'ubayam':
					$query = $this->db->query("
                        SELECT COALESCE(SUM(paid_amount), 0) as total 
                        FROM templebooking 
                        WHERE booking_type = 2 
                        AND booking_date BETWEEN '$startDate' AND '$endDate'
                        AND booking_status IN (1, 2)
                        AND (
                            ((booking_through = 'ONLINE' OR booking_through = 'COUNTER') AND payment_status = 2) 
                            OR (booking_through = 'DIRECT')
                        )
                    ");
					break;
			}

			$result = $query->getRowArray();
			$months[] = (float) $result['total'];
		}

		return json_encode($months);
	}

	public function reload_list()
	{
		$json_resp = array();
		$data = array();

		if (!empty($_POST['dt'])) {
			$dt = date('Y-m-d', strtotime($_POST['dt']));

			// Archanai Data
			$data['archanai'] = $this->db->query("
                SELECT 
                    archanai.id,
                    archanai.name_eng,
                    archanai.name_tamil,
                    SUM(archanai_booking_details.quantity) as tQty, 
                    SUM(archanai_booking_details.total_amount + archanai_booking_details.total_commision) as tAmt 
                FROM archanai 
                JOIN archanai_booking_details ON archanai.id = archanai_booking_details.archanai_id 
                JOIN archanai_booking ON archanai_booking.id = archanai_booking_details.archanai_booking_id 
                WHERE archanai_booking.date = '$dt' 
                AND (
                    ((archanai_booking.paid_through = 'ONLINE' OR archanai_booking.paid_through = 'COUNTER') AND archanai_booking.payment_status = 2) 
                    OR (archanai_booking.paid_through = 'Direct')
                ) 
                GROUP BY archanai.id 
                ORDER BY archanai.id
            ")->getResultArray();

			// Prasadam Data
			$data['prasadam'] = $this->db->query("
                SELECT 
                    prasadam_setting.id,
                    prasadam_setting.name_eng,
                    prasadam_setting.name_tamil,
                    SUM(prasadam_booking_details.quantity) as tQty, 
                    SUM(prasadam_booking_details.total_amount) as tAmt 
                FROM prasadam_setting 
                JOIN prasadam_booking_details ON prasadam_setting.id = prasadam_booking_details.prasadam_id 
                JOIN prasadam ON prasadam.id = prasadam_booking_details.prasadam_booking_id 
                WHERE prasadam.date = '$dt' 
                AND (
                    ((prasadam.paid_through = 'ONLINE' OR prasadam.paid_through = 'COUNTER') AND prasadam.payment_status = 2) 
                    OR (prasadam.paid_through = 'Direct')
                ) 
                GROUP BY prasadam_setting.id 
                ORDER BY prasadam_setting.id
            ")->getResultArray();

			// Donation Data
			$data['donation'] = $this->db->query("
                SELECT 
                    donation_setting.id,
                    donation_setting.name as dname, 
                    SUM(donation.amount) as amount 
                FROM donation 
                JOIN donation_setting ON donation_setting.id = donation.pay_for 
                WHERE donation.date = '$dt' 
                AND (
                    ((donation.paid_through = 'ONLINE' OR donation.paid_through = 'COUNTER') AND donation.payment_status = 2) 
                    OR (donation.paid_through = 'Direct')
                ) 
                GROUP BY donation.pay_for 
                ORDER BY donation.id DESC
            ")->getResultArray();

			// Ubayam Data
			$data['ubayam'] = $this->db->query("
                SELECT 
                    temple_packages.id,
                    temple_packages.name as ubname, 
                    COUNT(templebooking.id) as tQty,
                    SUM(templebooking.paid_amount) as amount 
                FROM templebooking 
                JOIN temple_packages ON temple_packages.id = templebooking.venue 
                WHERE templebooking.booking_type = 2 
                AND templebooking.booking_date = '$dt' 
                AND templebooking.booking_status IN (1, 2)
                AND (
                    ((templebooking.booking_through = 'ONLINE' OR templebooking.booking_through = 'COUNTER') AND templebooking.payment_status = 2) 
                    OR (templebooking.booking_through = 'DIRECT')
                ) 
                GROUP BY temple_packages.id 
                ORDER BY temple_packages.id
            ")->getResultArray();

			// Calculate totals
			$data['archanai_total'] = array_sum(array_column($data['archanai'], 'tAmt'));
			$data['prasadam_total'] = array_sum(array_column($data['prasadam'], 'tAmt'));
			$data['donation_total'] = array_sum(array_column($data['donation'], 'amount'));
			$data['ubayam_total'] = array_sum(array_column($data['ubayam'], 'amount'));
			$data['grand_total'] = $data['archanai_total'] + $data['prasadam_total'] + $data['donation_total'] + $data['ubayam_total'];
		}

		$json_resp['data'] = $data;
		$json_resp['success'] = true;
		echo json_encode($json_resp);
		exit;
	}
}