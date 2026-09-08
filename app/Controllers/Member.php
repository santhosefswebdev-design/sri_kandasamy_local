<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\RequestModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Member extends BaseController
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
		if (!$this->model->list_validate('member')) {
			header('Location: ' . base_url() . '/dashboard');
		}

		$data['permission'] = $this->model->get_permission('member');

		// Get filter parameters
		$filters = [
			'member_type' => $this->request->getGet('member_type') ?? '',
			'status' => $this->request->getGet('status') ?? '',
			'district' => $this->request->getGet('district') ?? '',
			'state' => $this->request->getGet('state') ?? '',
			'join_year' => $this->request->getGet('join_year') ?? '',
			'search' => $this->request->getGet('search') ?? '',
			'tamil_month' => $this->request->getGet('tamil_month') ?? '',
			'lunar_phase' => $this->request->getGet('lunar_phase') ?? '',
			'thithi' => $this->request->getGet('thithi') ?? '',
			'ic_no' => $this->request->getGet('ic_no') ?? ''
		];

		// Build query with all columns
		$qry = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type', 'left')
			->select('
        member.id,
        member.member_no,
        member.old_membership_no,
        member.membership_status,
        member.membership_code,
        member.prefix,
        member.first_name,
        member.last_name,
        member.name,
        member.titles,
        member.gender,
        member.ic_no,
        member.house_no_street,
        member.district,
        member.postal_code,
        member.locality,
        member.state,
        member.country,
        member.date_of_birth,
        member.tel_phone_house,
        member.tel_phone_mobile,
        member.mobile,
        member.email_address,
        member.next_of_kin_name,
        member.next_of_kin_contact,
        member.next_of_kin_relationship,
        member.mailing_preference,
        member.joining_date,
        member.start_date,
        member.end_date,
        member.death_date,
        member.natchathram,
        member.rasi,
        member.date_of_sivapatham,
        member.time_of_death,
        member.occupation,
        member.company,
        member.office_address,
        member.home_address,
        member.address,
        member.marital_status,
        member.proposer_1_name,
        member.proposer_2_name,
        member.proposer_1_member_no,
        member.proposer_2_member_no,
        member.member_type,
        member.status,
        member.approval_status,
        member.member_card_issued,
        member.member_card_issue_date,
        member.payment,
        member.payment_mode,
        member.remarks,
        member.created,
        member.modified,
        member.date_of_expiry,
        member.tamil_month,
        member.tamil_day,
        member.krishna_poorva,
        member.thithi,
        member_type.name as tname
    ')
			->where('member.approval_status', 1)
			->orderBy('member.member_no', 'ASC');

		// ============================================================
		// CHANGE 1: Updated Member Type Filter
		// ============================================================
		if (!empty($filters['member_type'])) {
			if ($filters['member_type'] == 'DM') {
				// Filter for Deceased Members based on membership_code starting with 'DM'
				$qry->groupStart()
					->like('member.membership_code', 'DM', 'after')
					->orWhere('member.status', 'demise')
					->orWhere('member.status', 'deceased')
					->orWhere('member.membership_status', 'Deceased')
					->groupEnd();
			} else {
				// Regular member type filtering (1 = Ordinary, 3 = Life Member)
				$qry->where('member.member_type', $filters['member_type']);
			}
		}

		// ============================================================
		// CHANGE 2: Updated Status Filter - SUPPORTS DECEASED
		// ============================================================
		if (!empty($filters['status'])) {
			// Support both 'deceased' and legacy 'demise' values
			if ($filters['status'] == 'deceased' || $filters['status'] == 'demise') {
				$qry->groupStart()
					->where('member.status', 'deceased')
					->orWhere('member.status', 'demise')
					->orWhere('member.membership_status', 'Deceased')
					->orLike('member.membership_code', 'DM', 'after')
					->groupEnd();
			} else {
				$qry->where('member.status', $filters['status']);
			}
		}

		if (!empty($filters['district'])) {
			$qry->where('member.district', $filters['district']);
		}

		if (!empty($filters['state'])) {
			$qry->where('member.state', $filters['state']);
		}

		if (!empty($filters['join_year'])) {
			$qry->where('YEAR(member.joining_date)', $filters['join_year']);
		}

		// Tamil Calendar Filters
		if (!empty($filters['tamil_month'])) {
			$qry->where('member.tamil_month', $filters['tamil_month']);
		}

		if (!empty($filters['lunar_phase'])) {
			$qry->where('member.krishna_poorva', $filters['lunar_phase']);
		}

		if (!empty($filters['thithi'])) {
			$qry->where('member.thithi', $filters['thithi']);
		}

		// IC Number Filter
		if (!empty($filters['ic_no'])) {
			$qry->like('member.ic_no', $filters['ic_no'], 'after');
		}

		$data['draft_count'] = $this->db->table('member')
			->where('approval_status', -1)
			->countAllResults();

		if (!empty($filters['search'])) {
			$qry->groupStart()
				->like('member.name', $filters['search'])
				->orLike('member.first_name', $filters['search'])
				->orLike('member.last_name', $filters['search'])
				->orLike('member.member_no', $filters['search'])
				->orLike('member.old_membership_no', $filters['search'])
				->orLike('member.ic_no', $filters['search'])
				->orLike('member.mobile', $filters['search'])
				->orLike('member.tel_phone_mobile', $filters['search'])
				->orLike('member.email_address', $filters['search'])
				->groupEnd();
		}

		$res = $qry->get()->getResultArray();
		$data['list'] = $res;
		$data['filters'] = $filters;

		// Get pending count for notification
		$data['pending_count'] = $this->db->table('member')
			->where('approval_status', 0)
			->countAllResults();

		// ============================================================
		// CHANGE 3: Updated Status Counts - SUPPORTS DECEASED
		// ============================================================
		$data['status_counts'] = [
			'active' => $this->db->table('member')
				->where('status', 'active')
				->where('approval_status', 1)
				->countAllResults(),

			'inactive' => $this->db->table('member')
				->where('status', 'inactive')
				->where('approval_status', 1)
				->countAllResults(),

			// New 'deceased' count - includes both old and new terminology
			'deceased' => $this->db->table('member')
				->groupStart()
				->where('status', 'deceased')
				->orWhere('status', 'demise')
				->orWhere('membership_status', 'Deceased')
				->orLike('membership_code', 'DM', 'after')
				->groupEnd()
				->where('approval_status', 1)
				->countAllResults(),

			// Keep old 'demise' key for backward compatibility with views
			'demise' => $this->db->table('member')
				->groupStart()
				->where('status', 'deceased')
				->orWhere('status', 'demise')
				->orWhere('membership_status', 'Deceased')
				->orLike('membership_code', 'DM', 'after')
				->groupEnd()
				->where('approval_status', 1)
				->countAllResults(),

			'terminated' => $this->db->table('member')
				->where('status', 'terminated')
				->where('approval_status', 1)
				->countAllResults(),

			// ============================================================
			// CHANGE 4: Exclude deceased from Life and Ordinary counts
			// ============================================================
			'life' => $this->db->table('member')
				->where('member_type', 3)
				->where('approval_status', 1)
				->where('status !=', 'demise')
				->where('status !=', 'deceased')
				->where('membership_status !=', 'Deceased')
				->countAllResults(),

			'ordinary' => $this->db->table('member')
				->where('member_type', 1)
				->where('approval_status', 1)
				->where('status !=', 'demise')
				->where('status !=', 'deceased')
				->where('membership_status !=', 'Deceased')
				->countAllResults()
		];

		// Get unique districts and states for filter dropdowns
		$data['districts'] = $this->db->table('member')
			->select('DISTINCT(district) as district')
			->where('district IS NOT NULL')
			->where('district !=', '')
			->where('approval_status', 1)
			->orderBy('district', 'ASC')
			->get()
			->getResultArray();

		$data['districts'] = array_column($data['districts'], 'district');

		$data['states'] = $this->db->table('member')
			->select('DISTINCT(state) as state')
			->where('state IS NOT NULL')
			->where('state !=', '')
			->where('approval_status', 1)
			->orderBy('state', 'ASC')
			->get()
			->getResultArray();

		$data['states'] = array_column($data['states'], 'state');

		// Get unique Tamil months for filter dropdown
		$data['tamil_months'] = $this->db->table('member')
			->select('DISTINCT(tamil_month) as tamil_month')
			->where('tamil_month IS NOT NULL')
			->where('tamil_month !=', '')
			->where('approval_status', 1)
			->orderBy('tamil_month', 'ASC')
			->get()
			->getResultArray();
		$data['tamil_months'] = array_column($data['tamil_months'], 'tamil_month');

		// Get unique Lunar phases for filter dropdown
		$data['lunar_phases'] = $this->db->table('member')
			->select('DISTINCT(krishna_poorva) as lunar_phase')
			->where('krishna_poorva IS NOT NULL')
			->where('krishna_poorva !=', '')
			->where('approval_status', 1)
			->orderBy('krishna_poorva', 'ASC')
			->get()
			->getResultArray();
		$data['lunar_phases'] = array_column($data['lunar_phases'], 'lunar_phase');

		// Get unique Thithis for filter dropdown
		$data['thithis'] = $this->db->table('member')
			->select('DISTINCT(thithi) as thithi')
			->where('thithi IS NOT NULL')
			->where('thithi !=', '')
			->where('approval_status', 1)
			->orderBy('thithi', 'ASC')
			->get()
			->getResultArray();
		$data['thithis'] = array_column($data['thithis'], 'thithi');

		// Process data for better display
		foreach ($data['list'] as &$member) {
			// Combine first and last name if name is empty
			if (empty($member['name']) && (!empty($member['first_name']) || !empty($member['last_name']))) {
				$member['name'] = trim($member['first_name'] . ' ' . $member['last_name']);
			}

			// Set mobile number preference
			if (!empty($member['tel_phone_mobile'])) {
				$member['display_mobile'] = $member['tel_phone_mobile'];
			} else {
				$member['display_mobile'] = $member['mobile'];
			}

			// Check if member needs renewal
			$member['needs_renewal'] = false;
			if ($member['member_type'] == 1 && !empty($member['end_date']) && $member['status'] == 'active') {
				$days_until_expiry = (strtotime($member['end_date']) - time()) / (60 * 60 * 24);
				$member['needs_renewal'] = ($days_until_expiry <= 30 && $days_until_expiry > 0);
				$member['days_until_expiry'] = $days_until_expiry;
			}

			// Check if member has complete address
			$member['has_complete_address'] = !empty($member['house_no_street']) || !empty($member['address']);

			// Format dates for display
			$member['formatted_join_date'] = !empty($member['joining_date'])
				? date('d/m/Y', strtotime($member['joining_date']))
				: (!empty($member['start_date']) ? date('d/m/Y', strtotime($member['start_date'])) : '-');

			$member['formatted_dob'] = !empty($member['date_of_birth'])
				? date('d/m/Y', strtotime($member['date_of_birth']))
				: '-';

			$member['formatted_death_date'] = !empty($member['death_date'])
				? date('d/m/Y', strtotime($member['death_date']))
				: '-';

			// Calculate age if DOB is available
			if (!empty($member['date_of_birth'])) {
				$dob = new \DateTime($member['date_of_birth']);
				$now = new \DateTime();
				$age = $now->diff($dob);
				$member['age'] = $age->y;
			} else {
				$member['age'] = null;
			}

			// Member type badge
			$member['type_badge'] = $member['member_type'] == 3 ? 'Life Member' : 'Ordinary Member';

			// ============================================================
			// CHANGE 5: Updated Status Badge - SUPPORTS DECEASED
			// ============================================================
			switch ($member['status']) {
				case 'active':
					$member['status_color'] = 'success';
					break;
				case 'inactive':
					$member['status_color'] = 'secondary';
					break;
				case 'deceased':
				case 'demise':
					$member['status_color'] = 'dark';
					break;
				case 'terminated':
					$member['status_color'] = 'danger';
					break;
				default:
					$member['status_color'] = 'secondary';
			}
		}

		// Statistics for dashboard
		$data['total_members'] = count($data['list']);
		$data['new_members_this_month'] = $this->db->table('member')
			->where('approval_status', 1)
			->where('MONTH(joining_date)', date('m'))
			->where('YEAR(joining_date)', date('Y'))
			->countAllResults();

		$data['renewals_due'] = $this->db->table('member')
			->where('member_type', 1)
			->where('approval_status', 1)
			->where('status', 'active')
			->where('end_date <', date('Y-m-d', strtotime('+30 days')))
			->where('end_date >=', date('Y-m-d'))
			->countAllResults();

		// Get member types for filters
		$data['member_types'] = $this->db->table('member_type')->get()->getResultArray();

		// Export functionality data
		$data['export_formats'] = ['excel', 'pdf', 'csv'];

		// Page title and breadcrumb
		$data['page_title'] = 'Member Registration - Complete List';
		$data['breadcrumb'] = [
			['title' => 'Dashboard', 'link' => base_url() . '/dashboard'],
			['title' => 'Members', 'link' => base_url() . '/member'],
			['title' => 'Complete List', 'link' => '#']
		];

		echo view('template/header');
		echo view('template/sidebar');
		echo view('member/index', $data);
		echo view('template/footer');
	}
	public function export($format = 'excel')
	{
		if (!$this->model->permission_validate('member', 'view')) {
			header('Location: ' . base_url() . '/dashboard');
			exit;
		}

		// Get all member data with all columns
		$members = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type', 'left')
			->select('
            member.member_no as "Membership No",
            member.old_membership_no as "Old Membership No",
            member.membership_status as "Membership Status",
            member.membership_code as "Membership Code",
            member.prefix as "Prefix",
            member.first_name as "First Name",
            member.last_name as "Last Name",
            CONCAT(member.prefix, " ", member.first_name, " ", member.last_name) as "Full Name",
            member.titles as "Titles",
            member.gender as "Gender",
            member.ic_no as "Identity Card No",
            member.house_no_street as "House No & Street",
            member.district as "District",
            member.postal_code as "Postal Code",
            member.locality as "Locality",
            member.state as "State",
            member.country as "Country",
            member.date_of_birth as "Date of Birth",
            member.tel_phone_house as "Tel Phone House",
            member.tel_phone_mobile as "Tel Phone Mobile",
            member.email_address as "Email Address",
            member.next_of_kin_name as "Next of Kin Name",
            member.next_of_kin_contact as "Next of Kin Contact",
            member.mailing_preference as "Mailing Preference",
            member.joining_date as "Join Date",
            member.death_date as "Deceased Date",
            member.natchathram as "Natchathiram",
            member.rasi as "Raasi",
            member.date_of_sivapatham as "Date of Sivapatham",
            member.time_of_death as "Time of Death",
            member_type.name as "Member Type",
            member.status as "Status"
		
member.date_of_expiry as "Date of Expiry",
member.tamil_month as "Tamil Month",
member.tamil_day as "Tamil Day",
member.krishna_poorva as "Lunar Phase (Krishna/Poorva)",
member.thithi as "Thithi"
        ')
			->where('member.approval_status', 1)
			->orderBy('member.member_no', 'ASC')
			->get()
			->getResultArray();

		if ($format == 'excel') {
			$this->exportToExcel($members);
		} elseif ($format == 'pdf') {
			$this->exportToPDF($members);
		} elseif ($format == 'csv') {
			$this->exportToCSV($members);
		}
	}

	private function exportToExcel($data)
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// Set headers
		$headers = array_keys($data[0]);
		$col = 1;
		foreach ($headers as $header) {
			$sheet->setCellValueByColumnAndRow($col, 1, $header);
			$sheet->getStyleByColumnAndRow($col, 1)->getFont()->setBold(true);
			$sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
			$col++;
		}

		// Add data
		$row = 2;
		foreach ($data as $member) {
			$col = 1;
			foreach ($member as $value) {
				$sheet->setCellValueByColumnAndRow($col, $row, $value);
				$col++;
			}
			$row++;
		}

		// Style the header row
		$styleArray = [
			'font' => ['bold' => true],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => ['argb' => 'FFE0E0E0']
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
				]
			]
		];

		$sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($styleArray);

		// Output
		$writer = new Xlsx($spreadsheet);
		$filename = 'member_list_complete_' . date('Y-m-d_H-i-s') . '.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
		exit;
	}
	private function exportToCSV($data)
	{
		$filename = 'member_list_complete_' . date('Y-m-d_H-i-s') . '.csv';

		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $filename . '"');

		$output = fopen('php://output', 'w');

		// Add headers
		fputcsv($output, array_keys($data[0]));

		// Add data
		foreach ($data as $row) {
			fputcsv($output, $row);
		}

		fclose($output);
		exit;
	}

	private function exportToPDF($data)
	{
		$html = '<h1>Member List - Complete Report</h1>';
		$html .= '<p>Generated on: ' . date('d/m/Y H:i:s') . '</p>';
		$html .= '<table border="1" cellpadding="5" cellspacing="0" style="font-size: 8px;">';

		// Headers
		$html .= '<tr>';
		foreach (array_keys($data[0]) as $header) {
			$html .= '<th style="background-color: #e0e0e0; font-weight: bold;">' . $header . '</th>';
		}
		$html .= '</tr>';

		// Data
		foreach ($data as $row) {
			$html .= '<tr>';
			foreach ($row as $value) {
				$html .= '<td>' . ($value ?? '-') . '</td>';
			}
			$html .= '</tr>';
		}

		$html .= '</table>';

		$dompdf = new \Dompdf\Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A2', 'landscape');
		$dompdf->render();

		$filename = 'member_list_complete_' . date('Y-m-d_H-i-s') . '.pdf';
		$dompdf->stream($filename, ['Attachment' => true]);
		exit;
	}
	// Add method to update member status
	public function update_status()
	{
		if (!$this->model->permission_validate('member', 'edit')) {
			echo json_encode(['success' => false, 'message' => 'Access denied']);
			return;
		}

		$member_id = $this->request->getPost('member_id');
		$new_status = $this->request->getPost('status');
		$death_date = $this->request->getPost('death_date');
		$remarks = $this->request->getPost('remarks');

		if (empty($member_id) || empty($new_status)) {
			echo json_encode(['success' => false, 'message' => 'Missing required fields']);
			return;
		}

		// Validate status values
		$valid_statuses = ['active', 'inactive', 'demise', 'terminated'];
		if (!in_array($new_status, $valid_statuses)) {
			echo json_encode(['success' => false, 'message' => 'Invalid status value']);
			return;
		}

		// Get current member data
		$member = $this->db->table('member')->where('id', $member_id)->get()->getRowArray();
		if (!$member) {
			echo json_encode(['success' => false, 'message' => 'Member not found']);
			return;
		}

		// Prepare update data
		$update_data = [
			'status' => $new_status,
			'modified' => date('Y-m-d H:i:s'),
			'added_by' => $this->session->get('log_id') // Track who made the change
		];

		// Handle death date for demise status
		if ($new_status === 'demise') {
			if (empty($death_date)) {
				echo json_encode(['success' => false, 'message' => 'Death date is required for demise status']);
				return;
			}
			$update_data['death_date'] = $death_date;
		} else {
			// Clear death date if status is not demise
			$update_data['death_date'] = null;
		}

		// Update member status
		$result = $this->db->table('member')->where('id', $member_id)->update($update_data);

		if ($result) {
			// Log the status change

			echo json_encode([
				'success' => true,
				'message' => 'Member status updated successfully',
				'new_status' => $new_status,
				'death_date' => $death_date ?? null
			]);
		} else {
			echo json_encode(['success' => false, 'message' => 'Failed to update member status']);
		}
	}
	public function pending_approvals()
	{
		if (!$this->model->list_validate('member')) {
			header('Location: ' . base_url() . '/dashboard');
		}

		$data['permission'] = $this->model->get_permission('member');

		// Get pending member applications with proper join
		$qry = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type', 'left')
			->select('
        member.*,
        member_type.name as tname,
        member_type.amount as member_type_amount
    ')
			->where('member.approval_status', 0)
			->orderBy('member.created', 'DESC');

		$data['list'] = $qry->get()->getResultArray();

		// Load payment modes for approval modal
		$data['payment_modes'] = $this->db->table('payment_mode')
			->where('status', 1)
			->where('paid_through', 'DIRECT')
			->get()
			->getResultArray();

		// Load ledgers for approval modal
		$data['ledgers'] = $this->db->query("
			SELECT * FROM `ledgers` 
			WHERE group_id IN (
				SELECT id FROM `groups` WHERE code IN (4000, 8000) 
				OR parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000)) 
				OR parent_id IN (
					SELECT id FROM `groups` 
					WHERE parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000))
				)
			)
			ORDER BY name ASC
		")->getResultArray();

		echo view('template/header');
		echo view('template/sidebar');
		echo view('member/pending_approvals', $data);
		echo view('template/footer');
	}

		public function approve()
	{
		$id = $this->request->getPost('member_id');
		$meeting_date = $this->request->getPost('meeting_date');
		$meeting_no = $this->request->getPost('meeting_no');
		$meeting_details = $this->request->getPost('meeting_details');

		// Payment information
		$payment_amount = $this->request->getPost('payment_amount');
		$payment_mode = $this->request->getPost('payment_mode');
		$payment_date = $this->request->getPost('payment_date');
		$payment_reference = $this->request->getPost('payment_reference');
		$payment_status = $this->request->getPost('payment_status');
		$payment_remarks = $this->request->getPost('payment_remarks');

		// Ledger information
		$ledger_id = $this->request->getPost('ledger_id');

		// Generate member number BEFORE updating the database
		$member_no = $this->generate_member_number($id);

		// Base data array
		$data = [
			'approval_status' => 1,
			'approval_date' => date('Y-m-d'),
			'approval_meeting_date' => $meeting_date,
			'approved_by' => $this->session->get('log_id'),
			'status' => 'active',
			'member_no' => $member_no,
			'ledger_id' => $ledger_id,
			'payment' => $payment_amount,
			'payment_mode' => $payment_mode,
			'payment_status' => $payment_status ?? 2, // Default to confirmed
			'modified' => date('Y-m-d H:i:s')
		];

		// Add optional fields only if they have values
		if (!empty($meeting_details)) {
			$data['approval_meeting_details'] = $meeting_details;
		}

		// Check if payment-related columns exist before adding
		try {
			$fields = $this->db->getFieldNames('member');

			if (in_array('payment_reference', $fields) && !empty($payment_reference)) {
				$data['payment_reference'] = $payment_reference;
			}

			if (in_array('payment_date', $fields) && !empty($payment_date)) {
				$data['payment_date'] = $payment_date;
			}

			if (in_array('payment_remarks', $fields) && !empty($payment_remarks)) {
				$data['payment_remarks'] = $payment_remarks;
			}
		} catch (\Exception $e) {
			log_message('error', 'Error checking member table fields: ' . $e->getMessage());
		}

		$res = $this->db->table('member')->where('id', $id)->update($data);

		if ($res) {
			// Get member details
			$member = $this->db->table('member')->where('id', $id)->get()->getRowArray();

			// Create annual dues for ordinary members
			if ($member['member_type'] == 1) { // Ordinary member
				$current_year = date('Y');
				$this->create_annual_dues($id, $current_year);
			}

			// Account migration with ledger
			$this->account_migration($id, "Member Approved - Payment Confirmed: RM " . $payment_amount);

			// Save to committee meeting log if meeting number provided
			if (!empty($meeting_no)) {
				$this->save_committee_meeting($id, $meeting_date, $meeting_no, 'approved', $meeting_details);
			}

			// Log payment transaction
			$this->log_payment_transaction($id, $payment_amount, $payment_mode, $payment_reference, $payment_date);

			// Send WhatsApp notification
			if (method_exists($this, 'send_member_approval_whatsapp')) {
				$this->send_member_approval_whatsapp($id);
			}

			// Set success message
			$this->session->setFlashdata('succ', 'Member Approved Successfully - Member No: ' . $member_no . ' | Payment: RM ' . number_format($payment_amount, 2));
			$this->session->setFlashdata('auto_print', true);

			// Redirect to print registration page
			return redirect()->to('/member/print_registration/' . $id);

		} else {
			$this->session->setFlashdata('fail', 'Approval Failed. Please Try Again');
			return redirect()->to('/member/pending_approvals');
		}
	}

	// Updated helper method to log payment transactions
	private function log_payment_transaction($member_id, $amount, $payment_mode, $reference = null, $payment_date = null)
	{
		try {
			$payment_log = [
				'member_id' => $member_id,
				'pay_method' => 'manual_approval',
				'request_data' => json_encode([
					'amount' => $amount,
					'payment_mode' => $payment_mode,
					'reference_no' => $reference,
					'payment_date' => $payment_date,
					'approved_by' => $this->session->get('log_id'),
					'approval_date' => date('Y-m-d H:i:s')
				]),
				'response_data' => json_encode([
					'status' => 'confirmed',
					'message' => 'Payment confirmed during member approval'
				]),
				'reference_id' => $reference,
				'created' => date('Y-m-d H:i:s')
			];

			// Log to payment gateway data table
			$this->db->table('member_payment_gateway_datas')->insert($payment_log);

			log_message('info', 'Payment logged for member ID: ' . $member_id . ', Amount: ' . $amount);
		} catch (\Exception $e) {
			log_message('error', 'Payment logging failed for member ID ' . $member_id . ': ' . $e->getMessage());
		}
	}

	/**
	 * Log member actions to audits table
	 * @param int $member_id The member ID
	 * @param string $event The event/action type (e.g., 'direct_approval', 'status_change', 'update')
	 * @param array $data Additional data to log
	 */
	private function log_member_action($member_id, $event, $data = [])
	{
		try {
			$audit_data = [
				'user_type' => 'App\\Models\\PermissionModel',
				'user_id' => $this->session->get('log_id'),
				'event' => $event,
				'auditable_type' => 'App\\Models\\Member',
				'auditable_id' => $member_id,
				'old_values' => null,
				'new_values' => json_encode($data),
				'url' => current_url(),
				'ip_address' => $this->request->getIPAddress(),
				'user_agent' => $this->request->getUserAgent()->getAgentString(),
				'tags' => 'member_action',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			];

			// Insert audit log
			$this->db->table('audits')->insert($audit_data);

			log_message('info', 'Member action logged - Event: ' . $event . ', Member ID: ' . $member_id);
		} catch (\Exception $e) {
			log_message('error', 'Member action logging failed for member ID ' . $member_id . ': ' . $e->getMessage());
		}
	}

	// Updated save_committee_meeting method
	private function save_committee_meeting($member_id, $meeting_date, $meeting_no, $action = 'approved', $meeting_details = null)
	{
		try {
			// Check if committee meeting exists
			$committee = $this->db->table('member_approval_committee')
				->where('meeting_date', $meeting_date)
				->where('meeting_no', $meeting_no)
				->get()
				->getRowArray();

			if (!$committee) {
				// Create new committee meeting record
				$committee_data = [
					'meeting_date' => $meeting_date,
					'meeting_no' => $meeting_no,
					'minutes' => $meeting_details,
					'created_by' => $this->session->get('log_id'),
					'created_at' => date('Y-m-d H:i:s')
				];

				$this->db->table('member_approval_committee')->insert($committee_data);
				$committee_id = $this->db->insertID();
			} else {
				$committee_id = $committee['id'];
			}

			// Add member to committee details
			$detail_data = [
				'committee_id' => $committee_id,
				'member_id' => $member_id,
				'action' => $action,
				'remarks' => $meeting_details
			];

			$this->db->table('member_approval_committee_details')->insert($detail_data);

			log_message('info', 'Committee meeting logged for member ID: ' . $member_id);
		} catch (\Exception $e) {
			log_message('error', 'Committee meeting logging failed: ' . $e->getMessage());
		}
	}
	// Reject member application
	public function reject()
	{
		$id = $this->request->getPost('member_id');
		$reason = $this->request->getPost('rejection_reason');

		$data = [
			'approval_status' => 2,
			'rejection_reason' => $reason,
			'rejected_by' => $this->session->get('log_id'),
			'status' => 0
		];

		$res = $this->db->table('member')->where('id', $id)->update($data);

		if ($res) {
			$this->session->setFlashdata('succ', 'Member Application Rejected');
		}

		return redirect()->to('/member/pending_approvals');
	}

	private function generate_member_number($member_id)
	{
		$member = $this->db->table('member')->where('id', $member_id)->get()->getRowArray();

		if (!empty($member['member_no'])) {
			return $member['member_no'];
		}

		$prefix = ($member['member_type'] == 3) ? 'LM' : 'OM'; // LM for Life, OM for Ordinary
		$prefix_length = strlen($prefix);

		// Get all member numbers with this prefix
		$members = $this->db->table('member')
			->select('member_no')
			->like('member_no', $prefix, 'after')
			->where('member_no IS NOT NULL')
			->get()->getResultArray();

		$last_number = 0;

		// Find the highest number - ONLY simple format (exclude year-based formats)
		foreach ($members as $m) {
			if (!empty($m['member_no'])) {
				// Extract numeric part after prefix
				$number_part = substr($m['member_no'], $prefix_length);
				// Remove any non-numeric characters
				$current_number = intval(preg_replace('/[^0-9]/', '', $number_part));

				// Skip year-based formats (e.g., 20250001, 20240123)
				// Simple format: LM1334 (4 digits max), OM2117 (4 digits max)
				// Year format: LM20250001 (8+ digits) - SKIP THESE
				if ($current_number >= 10000000) { // Skip if 8+ digits (year-based)
					continue;
				}

				if ($current_number > $last_number) {
					$last_number = $current_number;
				}
			}
		}

		$new_number = $last_number + 1;

		// Return simple format: LM1335, OM2118, etc.
		return $prefix . $new_number;
	}
	
	public function dues_list()
	{
		if (!$this->model->list_validate('member')) {
			header('Location: ' . base_url() . '/dashboard');
		}

		// Get filter parameters
		$year = $this->request->getGet('year') ?? date('Y');
		$member_name = $this->request->getGet('member_name') ?? '';
		$member_type = $this->request->getGet('member_type') ?? '';
		$status_filter = $this->request->getGet('status_filter') ?? 'all';
		$payment_status_filter = $this->request->getGet('payment_status_filter') ?? 'all';
		$date_from = $this->request->getGet('date_from') ?? '';
		$date_to = $this->request->getGet('date_to') ?? '';

		// Base query for ordinary members
		$query = $this->db->table('member')
			->select('member.*, member_type.name as tname, member_type.amount as annual_fee')
			->join('member_type', 'member_type.id = member.member_type')
			->where('member.member_type', 1) // Ordinary members only
			->where('member.approval_status', 1);

		// Apply filters
		if ($member_name) {
			$query->groupStart()
				->like('member.name', $member_name)
				->orLike('member.member_no', $member_name)
				->orLike('member.ic_no', $member_name)
				->groupEnd();
		}

		if ($member_type && $member_type !== 'all') {
			$query->where('member.member_type', $member_type);
		}

		if ($status_filter !== 'all') {
			if ($status_filter === 'active') {
				$query->where('member.status', 'active');
			} elseif ($status_filter === 'inactive') {
				$query->where('member.status', 'inactive');
			}
		}

		if ($date_from) {
			$query->where('member.start_date >=', $date_from);
		}
		if ($date_to) {
			$query->where('member.start_date <=', $date_to);
		}

		$members = $query->orderBy('member.name', 'ASC')->get()->getResultArray();

		foreach ($members as &$member) {
			// Check dues status
			$dues = $this->db->table('member_dues')
				->where('member_id', $member['id'])
				->where('due_year', $year)
				->get()->getRowArray();

			if ($dues) {
				$member['dues_status'] = $dues['payment_status'];
				$member['due_amount'] = $dues['due_amount'];
				$member['paid_amount'] = $dues['paid_amount'];
				$member['payment_date'] = $dues['payment_date'];
				$member['payment_mode'] = $dues['payment_mode'];
				$member['payment_reference'] = $dues['payment_reference'];
				$member['dues_id'] = $dues['id'];
			} else {
				// Create dues record if not exists
				$this->create_annual_dues($member['id'], $year);
				$member['dues_status'] = 0;
				$member['due_amount'] = $member['annual_fee'];
				$member['paid_amount'] = 0;
				$member['payment_date'] = null;
				$member['payment_mode'] = null;
				$member['payment_reference'] = null;
				$member['dues_id'] = null;
			}
		}

		// Apply payment status filter after dues calculation
		if ($payment_status_filter !== 'all') {
			$members = array_filter($members, function ($member) use ($payment_status_filter) {
				switch ($payment_status_filter) {
					case 'paid':
						return $member['dues_status'] == 1;
					case 'pending':
						return $member['dues_status'] == 0;
					case 'partial':
						return $member['dues_status'] == 2;
					case 'overdue':
						return $member['dues_status'] == 0 && strtotime($member['end_date']) < time();
					default:
						return true;
				}
			});
		}

		// Calculate statistics
		$total_members = count($members);
		$paid_count = count(array_filter($members, function ($m) {
			return $m['dues_status'] == 1;
		}));
		$pending_count = count(array_filter($members, function ($m) {
			return $m['dues_status'] == 0;
		}));
		$partial_count = count(array_filter($members, function ($m) {
			return $m['dues_status'] == 2;
		}));
		$overdue_count = count(array_filter($members, function ($m) {
			return $m['dues_status'] == 0 && strtotime($m['end_date']) < time();
		}));

		$total_due = array_sum(array_map(function ($m) {
			return $m['due_amount'] - $m['paid_amount'];
		}, $members));

		$total_collected = array_sum(array_map(function ($m) {
			return $m['paid_amount'];
		}, $members));

		$data['members'] = $members;
		$data['current_year'] = $year;
		$data['available_years'] = range(date('Y') - 5, date('Y') + 1);
		$data['filters'] = [
			'member_name' => $member_name,
			'member_type' => $member_type,
			'status_filter' => $status_filter,
			'payment_status_filter' => $payment_status_filter,
			'date_from' => $date_from,
			'date_to' => $date_to
		];
		$data['member_types'] = $this->db->table('member_type')->get()->getResultArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->get()->getResultArray();

		// Statistics
		$data['statistics'] = [
			'total_members' => $total_members,
			'paid_count' => $paid_count,
			'pending_count' => $pending_count,
			'partial_count' => $partial_count,
			'overdue_count' => $overdue_count,
			'total_due' => $total_due,
			'total_collected' => $total_collected,
			'collection_percentage' => $total_members > 0 ? round(($paid_count / $total_members) * 100, 2) : 0
		];

		echo view('template/header');
		echo view('template/sidebar');
		echo view('member/dues_list', $data);
		echo view('template/footer');
	}

	// Enhanced dues history method
	public function dues_history($member_id)
	{
		$member = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type')
			->select('member.*, member_type.name as type_name')
			->where('member.id', $member_id)
			->get()->getRowArray();

		if (!$member) {
			show_404();
		}

		$dues_history = $this->db->table('member_dues')
			->join('payment_mode', 'payment_mode.id = member_dues.payment_mode', 'left')
			->select('member_dues.*, payment_mode.name as payment_mode_name')
			->where('member_dues.member_id', $member_id)
			->orderBy('member_dues.due_year', 'DESC')
			->get()->getResultArray();

		$data['member'] = $member;
		$data['dues_history'] = $dues_history;

		echo view('member/dues_history', $data);
	}


	// Record dues payment
	public function pay_dues()
	{
		$member_id = $this->request->getPost('member_id');
		$year = $this->request->getPost('year');
		$amount = $this->request->getPost('amount');
		$payment_mode = $this->request->getPost('payment_mode');
		$payment_reference = $this->request->getPost('payment_reference');

		// Get or create dues record
		$dues = $this->db->table('member_dues')
			->where('member_id', $member_id)
			->where('due_year', $year)
			->get()->getRowArray();

		if ($dues) {
			$total_paid = $dues['paid_amount'] + $amount;
			$payment_status = ($total_paid >= $dues['due_amount']) ? 1 : 2; // 1=Paid, 2=Partial

			$this->db->table('member_dues')
				->where('id', $dues['id'])
				->update([
					'paid_amount' => $total_paid,
					'payment_date' => date('Y-m-d'),
					'payment_mode' => $payment_mode,
					'payment_reference' => $payment_reference,
					'payment_status' => $payment_status
				]);
		}

		// Update member renewal dates if fully paid
		if ($payment_status == 1) {
			$this->update_member_renewal($member_id, $year);
		}

		// Account migration
		$this->account_migration($member_id, "Annual Dues Payment - Year " . $year);

		$this->session->setFlashdata('succ', 'Dues Payment Recorded Successfully');
		return redirect()->to('/member/dues_list?year=' . $year);
	}

	private function update_member_renewal($member_id, $year)
	{
		$member = $this->db->table('member')->where('id', $member_id)->get()->getRowArray();

		if ($member && $member['member_type'] == 1) { // Only for ordinary members
			$current_year = date('Y');

			// If paying for current year, update the end date
			if ($year == $current_year) {
				$new_end_date = date('Y-m-d', strtotime("$year-12-31"));

				// Update member end date
				$this->db->table('member')->where('id', $member_id)->update([
					'end_date' => $new_end_date,
					'status' => 1,
					'renewal_status' => 0
				]);

				// Add renewal record
				$this->db->table('member_renewal')->insert([
					'member_id' => $member_id,
					'renewal_start_date' => date("$year-01-01"),
					'renewal_end_date' => $new_end_date
				]);
			}
		}
	}
	// Create annual dues record
	private function create_annual_dues($member_id, $year)
	{
		$member = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type')
			->select('member.*, member_type.amount')
			->where('member.id', $member_id)
			->get()->getRowArray();

		if ($member && $member['member_type'] == 1) { // Ordinary member
			// Check if record already exists
			$existing = $this->db->table('member_dues')
				->where('member_id', $member_id)
				->where('due_year', $year)
				->countAllResults();

			if ($existing == 0) {
				$data = [
					'member_id' => $member_id,
					'due_year' => $year,
					'due_amount' => $member['amount'],
					'paid_amount' => 0,
					'payment_status' => 0
				];

				$this->db->table('member_dues')->insert($data);
			}
		}
	}

	// Import members from Excel
	public function import_members()
	{
		if (!$this->model->permission_validate('member', 'create_p')) {
			header('Location: ' . base_url() . '/dashboard');
			exit;
		}

		if ($this->request->getMethod() == 'post') {
			$file = $this->request->getFile('excel_file');
			$member_type = $this->request->getPost('import_member_type'); // 1=Ordinary, 3=Life
			$auto_approve = $this->request->getPost('auto_approve') ?? 1; // Default to auto-approve

			if ($file->isValid() && !$file->hasMoved()) {
				$newName = $file->getRandomName();
				$file->move('uploads/excel/', $newName);
				$inputFileName = 'uploads/excel/' . $newName;

				try {
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
					$spreadsheet = $reader->load($inputFileName);
					$worksheet = $spreadsheet->getActiveSheet();
					$rows = $worksheet->toArray();

					$imported = 0;
					$failed = 0;
					$errors = [];

					// Skip header row (row 0) and process data
					for ($i = 1; $i < count($rows); $i++) {
						$row = $rows[$i];

						// Skip completely empty rows
						if (empty(array_filter($row))) {
							continue;
						}

						// Basic column mapping - adjust indices based on your Excel structure
						// This assumes a simple structure: Name, Old_Member_No, IC_No, Mobile, Email, Address
						$name = isset($row[0]) ? trim($row[0]) : '';
						$old_member_no = isset($row[1]) ? trim($row[1]) : '';
						$ic_no = isset($row[2]) ? trim($row[2]) : '';
						$mobile = isset($row[3]) ? trim($row[3]) : '';
						$email = isset($row[4]) ? trim($row[4]) : '';
						$address = isset($row[5]) ? trim($row[5]) : '';

						// Additional columns if present
						$dob = isset($row[6]) ? $this->parseExcelDate($row[6]) : null;
						$gender = isset($row[7]) ? trim($row[7]) : null;
						$occupation = isset($row[8]) ? trim($row[8]) : null;
						$company = isset($row[9]) ? trim($row[9]) : null;
						$district = isset($row[10]) ? trim($row[10]) : null;
						$state = isset($row[11]) ? trim($row[11]) : null;
						$postal_code = isset($row[12]) ? trim($row[12]) : null;

						// Validate minimum required fields
						if (empty($name)) {
							$errors[] = "Row " . ($i + 1) . ": Missing name";
							$failed++;
							continue;
						}

						// Check for duplicate IC number if provided
						if (!empty($ic_no)) {
							$existing = $this->db->table('member')
								->where('ic_no', $ic_no)
								->countAllResults();

							if ($existing > 0) {
								$errors[] = "Row " . ($i + 1) . ": Member with IC " . $ic_no . " already exists";
								$failed++;
								continue;
							}
						}

						// Parse name into components
						$name_parts = explode(' ', $name);
						$first_name = $name_parts[0] ?? '';
						$last_name = isset($name_parts[1]) ? implode(' ', array_slice($name_parts, 1)) : '';

						// Prepare member data
						$member_data = [
							// Basic Information
							'name' => $name,
							'first_name' => $first_name,
							'last_name' => $last_name,
							'old_membership_no' => $old_member_no,
							'member_type' => $member_type,
							'ic_no' => $ic_no,

							// Contact Information
							'mobile' => $mobile,
							'tel_phone_mobile' => $mobile,
							'email_address' => $email,

							// Address Information
							'address' => $address,
							'district' => $district,
							'state' => $state ?: 'Selangor', // Default state
							'postal_code' => $postal_code,
							'country' => 'Malaysia', // Default country

							// Additional Information
							'date_of_birth' => $dob,
							'gender' => $gender,
							'occupation' => $occupation,
							'company' => $company,

							// Membership dates
							'joining_date' => date('Y-m-d'),
							'start_date' => date('Y-m-d'),
							'end_date' => ($member_type == 1) ? date('Y-m-d', strtotime('+1 year')) : null,

							// Payment information
							'payment' => ($member_type == 3) ? '500' : '50', // Default amounts
							'payment_mode' => 3, // Cash
							'payment_status' => 2, // Confirmed

							// Approval settings
							'approval_status' => $auto_approve ? 1 : 0,
							'status' => $auto_approve ? 'active' : 'inactive',
							'approved_by' => $auto_approve ? $this->session->get('log_id') : null,
							'approval_date' => $auto_approve ? date('Y-m-d') : null,

							// System fields
							'added_by' => $this->session->get('log_id'),
							'created' => date('Y-m-d H:i:s'),
							'modified' => date('Y-m-d H:i:s'),
							'ip' => $this->getClientIP(),
							'ip_location' => 'Import',
							'ip_details' => json_encode(['import_source' => 'excel', 'file' => $newName])
						];

						// Insert the member
						$res = $this->db->table('member')->insert($member_data);

						if ($res) {
							$imported++;
							$member_id = $this->db->insertID();

							// Generate member number
							if (!empty($old_member_no)) {
								// Use old membership number as member number
								$this->db->table('member')->where('id', $member_id)->update(['member_no' => $old_member_no]);
							} else {
								// Generate new member number
								$member_no = $this->generate_member_number($member_id);
								$this->db->table('member')->where('id', $member_id)->update(['member_no' => $member_no]);
							}

							// Account migration if auto-approved
							if ($auto_approve) {
								$this->account_migration($member_id, "Member Import - Auto Approved");

								// Create dues record for ordinary members
								if ($member_type == 1) {
									$this->create_annual_dues($member_id, date('Y'));
								}
							}
						} else {
							$errors[] = "Row " . ($i + 1) . ": Database insertion failed for " . $name;
							$failed++;
						}
					}

					// Delete uploaded file
					unlink($inputFileName);

					// Prepare result message
					$message = "Import Complete. Successfully imported: $imported members";
					if ($failed > 0) {
						$message .= ", Failed: $failed";
					}
					if ($auto_approve) {
						$message .= " (All imported members were auto-approved)";
					} else {
						$message .= " (Imported members require approval)";
					}

					$this->session->setFlashdata('succ', $message);

					// Store errors if any
					if (!empty($errors)) {
						$this->session->setFlashdata('import_errors', $errors);
					}

				} catch (\Exception $e) {
					// Clean up file if exists
					if (file_exists($inputFileName)) {
						unlink($inputFileName);
					}
					$this->session->setFlashdata('fail', 'Excel file processing failed: ' . $e->getMessage());
				}

				return redirect()->to('/member');
			} else {
				$this->session->setFlashdata('fail', 'File upload failed');
				return redirect()->to('/member');
			}
		}

		// Show import form
		$data['member_type_list'] = $this->db->table('member_type')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('member/import', $data);
		echo view('template/footer');
	}

	// Helper method to parse Excel date formats
	private function parseExcelDate($excelDate)
	{
		if (empty($excelDate)) {
			return null;
		}

		// If it's already a valid date string
		if (strtotime($excelDate)) {
			return date('Y-m-d', strtotime($excelDate));
		}

		// If it's an Excel serial date number
		if (is_numeric($excelDate)) {
			try {
				$date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($excelDate);
				return $date->format('Y-m-d');
			} catch (\Exception $e) {
				return null;
			}
		}

		return null;
	}
	private function getClientIP()
	{
		$ipkeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
		foreach ($ipkeys as $key) {
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					$ip = trim($ip);
					if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
						return $ip;
					}
				}
			}
		}
		return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
	}
	// Generate member card


	// Generate QR code
	private function generate_qr_code($data)
	{
		// You'll need to install a QR code library like endroid/qr-code
		// For now, returning placeholder
		return base64_encode($data);
	}


	// Send approval email
	// Update this method in your controller if needed
	private function send_approval_email($member_id)
	{
		$member = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type')
			->select('member.*, member_type.name as type_name')
			->where('member.id', $member_id)
			->get()->getRowArray();

		if (empty($member['email_address'])) {
			return false;
		}

		$temple_title = $_SESSION['site_title'] ?? 'Temple';
		$mail_data['member'] = $member;

		// Load the approval email template
		//$message = view('member/approval_email_template', $mail_data);

		$subject = $temple_title . " - Membership Approved";
		$to_mail = array($member['email_address']);

		// Assuming you have a send_mail_with_content helper function
		// return send_mail_with_content($to_mail, $message, $subject, $temple_title);
		return send_mail_with_content($to_mail, $subject, $temple_title);
	}

	// Existing methods remain the same...
	/**
	 * REPLACE your existing view() method in Member.php controller with this:
	 */
	public function view()
	{
		if (!$this->model->permission_validate('member', 'view')) {
			header('Location: ' . base_url() . '/dashboard');
			exit;
		}

		$id = $this->request->uri->getSegment(3);

		// Get member data
		$data['data'] = $this->db->table('member')->where('id', $id)->get()->getRowArray();

		// Get member type list
		$data['member_type_list'] = $this->db->table('member_type')->get()->getResultArray();

		// Get payment modes
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->get()->getResultArray();

		// Get phone codes
		$data['phone_codes'] = $this->db->table("phone_code")->orderBy('dailing_code', 'ASC')->get()->getResultArray();

		// Get Rasi data
		$data['rasi'] = $this->db->table('rasi')->orderBy('id', 'ASC')->get()->getResultArray();

		// Get Natchathram data
		$data['natchathram'] = $this->db->table('natchathram')->orderBy('id', 'ASC')->get()->getResultArray();

		// Get occupation list
		$data['occupation_list'] = $this->getOccupationList();

		// Get approved members for proposer dropdowns
		$data['approved_members'] = $this->db->table('member')
			->select('id, name, member_no, first_name, last_name')
			->where('approval_status', 1)
			->where('status', 'active')
			->where('member_no IS NOT NULL')
			->where('member_no !=', '')
			->orderBy('member_no', 'ASC')
			->get()->getResultArray();

		// Get ledgers
		$data['ledgers'] = $this->db->query("
        SELECT * FROM `ledgers` 
        WHERE group_id IN (
            SELECT id FROM `groups` WHERE code IN (4000, 8000) 
            OR parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000)) 
            OR parent_id IN (
                SELECT id FROM `groups` 
                WHERE parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000))
            )
        )
        ORDER BY name ASC
    ")->getResultArray();

		// Fetch countries and states
		$data['countries'] = $this->db->table('countries')
			->where('status', 1)
			->orderBy('name', 'ASC')
			->get()->getResultArray();

		$data['states'] = $this->db->table('states')
			->orderBy('name', 'ASC')
			->get()->getResultArray();

		// ========================================
		// DOCUMENT DATA - Load existing documents
		// ========================================
		$data['ic_document'] = $this->db->table('member_documents')
			->where('member_id', $id)
			->where('document_type', 'ic_copy')
			->get()->getRowArray();

		$data['signature_document'] = $this->db->table('member_documents')
			->where('member_id', $id)
			->where('document_type', 'signature')
			->get()->getRowArray();

		$data['other_documents'] = $this->db->table('member_documents')
			->where('member_id', $id)
			->where('document_type', 'other_document')
			->get()->getResultArray();

		// Set view mode flags
		$data['view'] = true;
		$data['edit'] = true;
		$data['mode'] = 'view';

		echo view('template/header');
		echo view('template/sidebar');
		echo view('member/add', $data);
		echo view('template/footer');
	}

	public function add()
	{
		if (!$this->model->permission_validate('member', 'create_p')) {
			header('Location: ' . base_url() . '/dashboard');
		}

		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->where('paid_through', 'DIRECT')->get()->getResultArray();
		$data['member_type_list'] = $this->db->table('member_type')->get()->getResultArray();
		$data['phone_codes'] = $this->db->table("phone_code")->orderBy('dailing_code', 'ASC')->get()->getResultArray();

		$data['ledgers'] = $this->db->query("
        SELECT * FROM `ledgers` 
        WHERE group_id IN (
            SELECT id FROM `groups` WHERE code IN (4000, 8000) 
            OR parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000)) 
            OR parent_id IN (
                SELECT id FROM `groups` 
                WHERE parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000))
            )
        )
        ORDER BY name ASC
    ")->getResultArray();

		$data['approved_members'] = $this->db->table('member')
			->select('id, name, member_no, first_name, last_name')
			->where('approval_status', 1)
			->where('status', 'active')
			->where('member_no IS NOT NULL')
			->where('member_no !=', '')
			->orderBy('member_no', 'ASC')
			->get()->getResultArray();

		$data['occupation_list'] = $this->getOccupationList();

		// Fetch from database - Order by ID for Archanai sequence
		$data['rasi'] = $this->db->table('rasi')->orderBy('id', 'ASC')->get()->getResultArray();
		$data['natchathram'] = $this->db->table('natchathram')->orderBy('id', 'ASC')->get()->getResultArray();

		// Fetch countries and states
		$data['countries'] = $this->db->table('countries')
			->where('status', 1)
			->orderBy('name', 'ASC')
			->get()->getResultArray();

		$data['states'] = $this->db->table('states')
			->orderBy('name', 'ASC')
			->get()->getResultArray();

		// ========================================
		// DOCUMENT DATA - Empty for new member
		// ========================================
		$data['ic_document'] = null;
		$data['signature_document'] = null;
		$data['other_documents'] = [];

		$data['mode'] = 'add';

		echo view('template/header');
		echo view('template/sidebar');
		echo view('member/add', $data);
		echo view('template/footer');
	}


	public function edit()
	{
		if (!$this->model->permission_validate('member', 'edit')) {
			header('Location: ' . base_url() . '/dashboard');
		}

		$id = $this->request->uri->getSegment(3);
		$data['data'] = $this->db->table('member')->where('id', $id)->get()->getRowArray();
		$data['member_type_list'] = $this->db->table('member_type')->get()->getResultArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->get()->getResultArray();
		$data['phone_codes'] = $this->db->table("phone_code")->orderBy('dailing_code', 'ASC')->get()->getResultArray();

		// Order by ID for Archanai sequence
		$data['rasi'] = $this->db->table('rasi')->orderBy('id', 'ASC')->get()->getResultArray();
		$data['natchathram'] = $this->db->table('natchathram')->orderBy('id', 'ASC')->get()->getResultArray();

		// Fetch countries and states
		$data['countries'] = $this->db->table('countries')
			->where('status', 1)
			->orderBy('name', 'ASC')
			->get()->getResultArray();

		$data['states'] = $this->db->table('states')
			->orderBy('name', 'ASC')
			->get()->getResultArray();

		$data['approved_members'] = $this->db->table('member')
			->select('id, name, member_no, first_name, last_name')
			->where('approval_status', 1)
			->where('status', 'active')
			->where('member_no IS NOT NULL')
			->where('member_no !=', '')
			->orderBy('member_no', 'ASC')
			->get()->getResultArray();

		$data['occupation_list'] = $this->getOccupationList();

		$data['ledgers'] = $this->db->query("
        SELECT * FROM `ledgers` 
        WHERE group_id IN (
            SELECT id FROM `groups` WHERE code IN (4000, 8000) 
            OR parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000)) 
            OR parent_id IN (
                SELECT id FROM `groups` 
                WHERE parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000))
            )
        )
        ORDER BY name ASC
    ")->getResultArray();

		// ========================================
		// DOCUMENT DATA - Load existing documents
		// ========================================
		$data['ic_document'] = $this->db->table('member_documents')
			->where('member_id', $id)
			->where('document_type', 'ic_copy')
			->get()->getRowArray();

		$data['signature_document'] = $this->db->table('member_documents')
			->where('member_id', $id)
			->where('document_type', 'signature')
			->get()->getRowArray();

		$data['other_documents'] = $this->db->table('member_documents')
			->where('member_id', $id)
			->where('document_type', 'other_document')
			->get()->getResultArray();

		$data['mode'] = 'edit';
		$data['edit'] = true;

		echo view('template/header');
		echo view('template/sidebar');
		echo view('member/add', $data);
		echo view('template/footer');
	}

	// Add this new method for AJAX call
	public function get_states_by_country()
	{
		$country_id = $this->request->getPost('country_id');

		if ($country_id) {
			// Get country code from countries table
			$country = $this->db->table('countries')
				->where('id', $country_id)
				->get()->getRowArray();

			if ($country) {
				// Assuming states table has 'code' field that matches country code
				// Adjust the where condition based on your database structure
				$states = $this->db->table('states')
					->where('code', $country['code']) // or use country_id if you have that field
					->orderBy('name', 'ASC')
					->get()->getResultArray();

				echo json_encode(['success' => true, 'states' => $states]);
			} else {
				echo json_encode(['success' => false, 'message' => 'Country not found']);
			}
		} else {
			echo json_encode(['success' => false, 'message' => 'Country ID required']);
		}
	}
	private function getOccupationList()
	{
		return [
			'Administrative & Office' => [
				'Administrative Assistant',
				'Office Manager',
				'Executive Secretary',
				'Receptionist',
				'Data Entry Clerk',
				'Personal Assistant',
				'Office Clerk',
				'Administrative Officer'
			],

			'Business & Finance' => [
				'Accountant',
				'Auditor',
				'Financial Analyst',
				'Investment Banker',
				'Tax Consultant',
				'Business Analyst',
				'Company Secretary',
				'Financial Controller',
				'Bank Manager',
				'Insurance Agent',
				'Stock Broker',
				'Credit Analyst',
				'Bookkeeper',
				'Payroll Officer',
				'Treasury Manager'
			],

			'Education & Training' => [
				'Teacher',
				'Lecturer',
				'Professor',
				'Tutor',
				'Principal',
				'Education Consultant',
				'Training Manager',
				'Academic Coordinator',
				'School Administrator',
				'Librarian',
				'Research Fellow',
				'Education Officer'
			],

			'Engineering & Technical' => [
				'Civil Engineer',
				'Mechanical Engineer',
				'Electrical Engineer',
				'Chemical Engineer',
				'Software Engineer',
				'Network Engineer',
				'Quality Engineer',
				'Project Engineer',
				'Site Engineer',
				'Maintenance Engineer',
				'Production Engineer',
				'Safety Engineer',
				'Technician',
				'Quantity Surveyor'
			],

			'Healthcare & Medical' => [
				'Doctor',
				'Specialist Doctor',
				'Surgeon',
				'General Practitioner',
				'Dentist',
				'Pharmacist',
				'Nurse',
				'Medical Officer',
				'Physiotherapist',
				'Occupational Therapist',
				'Radiologist',
				'Laboratory Technologist',
				'Medical Assistant',
				'Healthcare Administrator',
				'Clinical Research Coordinator',
				'Dietitian',
				'Psychologist',
				'Counselor'
			],

			'Information Technology' => [
				'IT Manager',
				'System Administrator',
				'Database Administrator',
				'Web Developer',
				'Mobile App Developer',
				'UI/UX Designer',
				'Data Scientist',
				'IT Support Specialist',
				'Network Administrator',
				'Cybersecurity Specialist',
				'DevOps Engineer',
				'Business Intelligence Analyst',
				'IT Consultant',
				'Project Manager (IT)',
				'Software Tester',
				'System Analyst'
			],

			'Legal & Compliance' => [
				'Lawyer',
				'Legal Advisor',
				'Compliance Officer',
				'Legal Executive',
				'Corporate Lawyer',
				'Legal Assistant',
				'Paralegal',
				'Judge',
				'Magistrate'
			],

			'Management & Leadership' => [
				'Chief Executive Officer (CEO)',
				'Chief Operating Officer (COO)',
				'Chief Financial Officer (CFO)',
				'Managing Director',
				'General Manager',
				'Operations Manager',
				'Department Head',
				'Branch Manager',
				'Project Manager',
				'Business Owner',
				'Entrepreneur',
				'Director'
			],

			'Marketing & Sales' => [
				'Marketing Manager',
				'Sales Manager',
				'Digital Marketing Specialist',
				'Marketing Executive',
				'Sales Executive',
				'Business Development Manager',
				'Brand Manager',
				'Product Manager',
				'Public Relations Officer',
				'Social Media Manager',
				'Account Manager',
				'Sales Representative',
				'Marketing Coordinator',
				'Advertising Executive'
			],

			'Manufacturing & Production' => [
				'Production Manager',
				'Factory Manager',
				'Quality Control Manager',
				'Manufacturing Engineer',
				'Production Supervisor',
				'Plant Manager',
				'Operations Supervisor',
				'Assembly Worker',
				'Machine Operator',
				'Production Planner'
			],

			'Hospitality & Tourism' => [
				'Hotel Manager',
				'Restaurant Manager',
				'Chef',
				'Front Office Manager',
				'Housekeeping Manager',
				'Travel Agent',
				'Tour Guide',
				'Event Manager',
				'Catering Manager',
				'Food & Beverage Manager',
				'Concierge',
				'Receptionist (Hotel)'
			],

			'Government & Public Service' => [
				'Government Officer',
				'Civil Servant',
				'Administrative Officer (Government)',
				'Public Works Officer',
				'Immigration Officer',
				'Customs Officer',
				'Police Officer',
				'Military Personnel',
				'Firefighter',
				'Public Health Officer',
				'Social Welfare Officer'
			],

			'Construction & Property' => [
				'Architect',
				'Interior Designer',
				'Project Manager (Construction)',
				'Property Manager',
				'Real Estate Agent',
				'Property Valuer',
				'Site Supervisor',
				'Building Inspector',
				'Contractor',
				'Facilities Manager',
				'Landscape Architect'
			],

			'Media & Communications' => [
				'Journalist',
				'Editor',
				'Content Writer',
				'Copywriter',
				'Video Producer',
				'Photographer',
				'Graphic Designer',
				'Broadcasting Officer',
				'Communications Manager',
				'Media Planner',
				'Public Relations Manager'
			],

			'Transportation & Logistics' => [
				'Logistics Manager',
				'Supply Chain Manager',
				'Warehouse Manager',
				'Transport Manager',
				'Shipping Coordinator',
				'Procurement Officer',
				'Inventory Manager',
				'Delivery Driver',
				'Airline Pilot',
				'Ship Captain',
				'Train Driver'
			],

			'Arts & Entertainment' => [
				'Artist',
				'Musician',
				'Actor',
				'Director',
				'Producer',
				'Choreographer',
				'Stage Manager',
				'Event Coordinator',
				'Art Director',
				'Creative Director'
			],

			'Science & Research' => [
				'Research Scientist',
				'Laboratory Scientist',
				'Research Officer',
				'Chemist',
				'Biologist',
				'Physicist',
				'Environmental Scientist',
				'Agronomist',
				'Veterinarian',
				'Marine Biologist'
			],

			'Social Services & Community' => [
				'Social Worker',
				'Community Development Officer',
				'Youth Worker',
				'Welfare Officer',
				'NGO Worker',
				'Charity Coordinator',
				'Volunteer Coordinator',
				'Family Support Worker'
			],

			'Skilled Trades' => [
				'Electrician',
				'Plumber',
				'Carpenter',
				'Welder',
				'Mechanic',
				'Air Conditioning Technician',
				'Painter',
				'Mason',
				'Locksmith',
				'Automotive Technician'
			],

			'Agriculture & Farming' => [
				'Farmer',
				'Agricultural Officer',
				'Plantation Manager',
				'Fisherman',
				'Livestock Farmer',
				'Agricultural Consultant',
				'Farm Manager'
			],

			'Security & Defence' => [
				'Security Guard',
				'Security Manager',
				'Security Consultant',
				'Bodyguard',
				'CCTV Operator',
				'Loss Prevention Officer'
			],

			'Retail & Customer Service' => [
				'Retail Manager',
				'Store Manager',
				'Shop Assistant',
				'Cashier',
				'Customer Service Representative',
				'Merchandiser',
				'Store Supervisor',
				'Sales Associate'
			],

			'Religious & Spiritual' => [
				'Priest',
				'Religious Teacher',
				'Temple Administrator',
				'Religious Counselor',
				'Chaplain'
			],

			'Self-Employed & Others' => [
				'Self-Employed',
				'Freelancer',
				'Consultant',
				'Business Owner',
				'Contractor',
				'Housewife',
				'Homemaker',
				'Retired',
				'Student',
				'Unemployed',
				'Others'
			]
		];
	}
	public function save()
	{
		$id = $_POST['id'] ?? '';
		$save_type = $this->request->getPost('save_type') ?? 'submit'; // Get save type (draft, submit, or direct_approve)

		// All 29 columns from Excel mapping
		// Membership Information
		$data['member_type'] = trim($_POST['member_type'] ?? '');
		$data['old_membership_no'] = !empty($_POST['old_membership_no']) ? trim($_POST['old_membership_no']) : null;
		$data['membership_status'] = !empty($_POST['membership_status']) ? $_POST['membership_status'] : null;
		$data['membership_code'] = !empty($_POST['membership_code']) ? trim($_POST['membership_code']) : null;

		// Personal Information
		if (!empty($_POST['prefix_ordered'])) {
			$data['prefix'] = trim($_POST['prefix_ordered']);
		} elseif (!empty($_POST['prefix']) && is_array($_POST['prefix'])) {
			$data['prefix'] = implode(', ', array_filter($_POST['prefix']));
		} else {
			$data['prefix'] = null;
		}
		$data['first_name'] = !empty($_POST['first_name']) ? trim($_POST['first_name']) : null;
		$data['last_name'] = !empty($_POST['last_name']) ? trim($_POST['last_name']) : null;
		$data['name'] = trim($_POST['name'] ?? ''); // Full name
		$data['titles'] = !empty($_POST['titles']) ? trim($_POST['titles']) : null;
		$data['ic_no'] = trim($_POST['ic_number'] ?? '');
		$data['date_of_birth'] = !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null;
		$data['gender'] = !empty($_POST['gender']) ? $_POST['gender'] : null;
		$data['marital_status'] = !empty($_POST['marital_status']) ? $_POST['marital_status'] : null;

		// Contact Information
		$data['tel_phone_house'] = !empty($_POST['tel_phone_house']) ? trim($_POST['tel_phone_house']) : null;

		// ========================================
		// AUTO-UPDATE STATUS FOR DECEASED MEMBERS - CRITICAL SECTION
		// ========================================
		// Priority 1: If Membership Status = Deceased
		/*if (isset($data['membership_status']) && $data['membership_status'] == 'Deceased') {
			$data['status'] = 'demise';
		}*/

        // Sync status with membership_status
        if (!empty($data['membership_status'])) {
        
            if ($data['membership_status'] == 'Active') {
                $data['status'] = 'active';
            } elseif ($data['membership_status'] == 'Inactive') {
                $data['status'] = 'inactive';
            } elseif ($data['membership_status'] == 'Deceased') {
                $data['status'] = 'demise';
            }
        }

		// Priority 2: If Member Type = Deceased Member (ID = 4)
		if (isset($data['member_type']) && $data['member_type'] == '4') {
			$data['status'] = 'demise';
			$data['membership_status'] = 'Deceased';
		}

		// Handle mobile number
		if (empty($_POST['edit_status'])) {
			$mble_phonecode = !empty($_POST['phonecode']) ? $_POST['phonecode'] : "";
			$mble_number = !empty($_POST['mobile']) ? $_POST['mobile'] : "";
			$data['mobile'] = $mble_phonecode . $mble_number;
			$data['tel_phone_mobile'] = $data['mobile'];
		} else {
			$data['mobile'] = $_POST['mobile'] ?? '';
			$data['tel_phone_mobile'] = $_POST['mobile'] ?? '';
		}

		$data['email_address'] = !empty($_POST['email_address']) ? $_POST['email_address'] : null;
		$data['mailing_preference'] = !empty($_POST['mailing_preference']) ? $_POST['mailing_preference'] : 'email';

		// Address Information
		$data['house_no_street'] = !empty($_POST['house_no_street']) ? trim($_POST['house_no_street']) : null;
		$data['district'] = !empty($_POST['district']) ? trim($_POST['district']) : null;
		$data['postal_code'] = !empty($_POST['postal_code']) ? trim($_POST['postal_code']) : null;
		$data['locality'] = !empty($_POST['locality']) ? trim($_POST['locality']) : null;
		// $data['state'] = !empty($_POST['state']) ? trim($_POST['state']) : null;
		// STATE HANDLING - Check manual input first, then dropdown
		if (!empty($_POST['state_manual'])) {
			// Manual state entry takes priority
			$data['state'] = trim($_POST['state_manual']);
		} elseif (!empty($_POST['state'])) {
			// Use dropdown selection
			$data['state'] = trim($_POST['state']);
		} else {
			$data['state'] = null;
		}
		$data['country'] = !empty($_POST['country']) ? trim($_POST['country']) : 'Malaysia';
		$data['address'] = !empty($_POST['address']) ? trim($_POST['address']) : null;
		$data['office_address'] = !empty($_POST['office_address']) ? $_POST['office_address'] : null;
		$data['home_address'] = !empty($_POST['home_address']) ? $_POST['home_address'] : null;

		// Professional & Religious Information
		$data['occupation'] = !empty($_POST['occupation']) ? $_POST['occupation'] : null;
		$data['company'] = !empty($_POST['company']) ? $_POST['company'] : null;
		$data['rasi'] = !empty($_POST['rasi']) ? trim($_POST['rasi']) : null;
		$data['natchathram'] = !empty($_POST['natchathram']) ? trim($_POST['natchathram']) : null;

		// Next of Kin Information
		$data['next_of_kin_name'] = !empty($_POST['next_of_kin_name']) ? trim($_POST['next_of_kin_name']) : null;
		$data['next_of_kin_contact'] = !empty($_POST['next_of_kin_contact']) ? trim($_POST['next_of_kin_contact']) : '';
		$data['next_of_kin_relationship'] = !empty($_POST['next_of_kin_relationship']) ? trim($_POST['next_of_kin_relationship']) : null;

		// Membership Dates
		$data['joining_date'] = !empty($_POST['start_date']) ? trim($_POST['start_date']) : null;
		$data['start_date'] = !empty($_POST['start_date']) ? trim($_POST['start_date']) : null;

		if (($_POST['member_type'] ?? '') === '3') { // Life member
			$data['end_date'] = null;
		} else {
			$data['end_date'] = !empty($_POST['end_date']) ? date('Y-m-d', strtotime(trim($_POST['end_date']))) : null;
		}

		$data['tel_phone_mobile'] = $data['mobile']; // Copy mobile to tel_phone_mobile field

		// Payment Information
		$data['payment'] = !empty($_POST['payment']) ? trim($_POST['payment']) : 0;
		$data['payment_mode'] = !empty($_POST['payment_mode']) ? trim($_POST['payment_mode']) : null;
		$data['payment_status'] = 2; // Confirmed payment
		$data['paid_through'] = 'DIRECT';

		// Tamil Calendar Information
		if (!empty($_POST['date_of_sivapatham'])) {
			$data['date_of_sivapatham'] = $_POST['date_of_sivapatham'];
			$data['date_of_expiry'] = $_POST['date_of_sivapatham']; // ALWAYS sync to expiry
		} else {
			$data['date_of_sivapatham'] = null;
			$data['date_of_expiry'] = null;
		}
		$data['tamil_month'] = !empty($_POST['tamil_month']) ? trim($_POST['tamil_month']) : null;
		$data['tamil_day'] = !empty($_POST['tamil_day']) ? trim($_POST['tamil_day']) : null;
		$data['krishna_poorva'] = !empty($_POST['krishna_poorva']) ? trim($_POST['krishna_poorva']) : null;
		$data['thithi'] = !empty($_POST['thithi']) ? trim($_POST['thithi']) : null;

		// For Deceased Members (if applicable)
		if (!empty($_POST['death_date'])) {
			$data['death_date'] = $_POST['death_date'];
			$data['status'] = 'demise';
			$data['membership_status'] = 'Deceased';
		}

		if (!empty($_POST['date_of_sivapatham'])) {
			$data['date_of_sivapatham'] = $_POST['date_of_sivapatham'];
		}
		if (!empty($_POST['time_of_death'])) {
			$data['time_of_death'] = $_POST['time_of_death'];
		}
		if (!empty($_POST['remarks'])) {
			$data['remarks'] = $_POST['remarks'];
		}

		// Proposer details - now optional
		if (!empty($_POST['proposer_1_id'])) {
			$proposer1 = $this->db->table('member')->where('id', $_POST['proposer_1_id'])->get()->getRowArray();
			if ($proposer1) {
				$data['proposer_1_id'] = $_POST['proposer_1_id'];
				$data['proposer_1_name'] = $proposer1['name'];
				$data['proposer_1_member_no'] = $proposer1['member_no'];
			}
		} else {
			$data['proposer_1_id'] = NULL;
			$data['proposer_1_name'] = NULL;
			$data['proposer_1_member_no'] = NULL;
		}

		if (!empty($_POST['proposer_2_id'])) {
			$proposer2 = $this->db->table('member')->where('id', $_POST['proposer_2_id'])->get()->getRowArray();
			if ($proposer2) {
				$data['proposer_2_id'] = $_POST['proposer_2_id'];
				$data['proposer_2_name'] = $proposer2['name'];
				$data['proposer_2_member_no'] = $proposer2['member_no'];
			}
		} else {
			$data['proposer_2_id'] = NULL;
			$data['proposer_2_name'] = NULL;
			$data['proposer_2_member_no'] = NULL;
		}

		// System fields
		$data['added_by'] = $this->session->get('log_id');

		// Ledger assignment
		if (!empty($_POST['ledger_id'])) {
			$data['ledger_id'] = $_POST['ledger_id'];
		}

		// IP tracking
		$data['ip'] = $this->getClientIP();
		$data['ip_location'] = 'Manual Entry';
		$data['ip_details'] = json_encode(['source' => 'web_form', 'timestamp' => date('Y-m-d H:i:s')]);

		// Handle photo upload
		if (!empty($_FILES['member_photo']['name'])) {
			$photo = $this->request->getFile('member_photo');
			if ($photo->isValid() && !$photo->hasMoved()) {
				$newName = 'photo_' . time() . '_' . $photo->getRandomName();
				$photo->move('uploads/member_photos/', $newName);
				$data['member_photo'] = $newName;
			}
		}

		// ========================================
		// SAVE TYPE: DIRECT APPROVE (SKIP PENDING)
		// ========================================
		if ($save_type === 'direct_approve') {
			// Only allow direct approval for new members
			if (!empty($id)) {
				$this->session->setFlashdata('fail', 'Direct approval is only available for new member registrations.');
				return redirect()->to('/member/edit/' . $id);
			}

			// ========================================
			// GET APPROVAL DETAILS FROM MODAL
			// ========================================
			$approval_date_override = $this->request->getPost('approval_date_override');
			$approved_by_name_override = $this->request->getPost('approved_by_name_override');

			// Validate approval details
			if (empty($approval_date_override)) {
				$this->session->setFlashdata('fail', 'Approval date is required for direct approval.');
				return redirect()->to('/member/add')->withInput();
			}

			if (empty($approved_by_name_override) || trim($approved_by_name_override) === '') {
				$this->session->setFlashdata('fail', 'Approved by name is required for direct approval.');
				return redirect()->to('/member/add')->withInput();
			}

			// Validate name length
			if (strlen(trim($approved_by_name_override)) < 3) {
				$this->session->setFlashdata('fail', 'Approved by name must be at least 3 characters long.');
				return redirect()->to('/member/add')->withInput();
			}

			// Set approval fields
			$data['approval_status'] = 1; // Approved
			$data['approval_date'] = $approval_date_override;
			$data['approved_by'] = $this->session->get('log_id'); // User ID

			// Set status based on member type
			if (!isset($data['status']) || $data['status'] !== 'demise') {
				$data['status'] = 'active'; // Active for approved members (unless deceased)
			}

			$data['created'] = date('Y-m-d H:i:s');
			$data['modified'] = date('Y-m-d H:i:s');

			// Insert the member
			$res = $this->db->table('member')->insert($data);

			if ($res) {
				$member_id = $this->db->insertID();

				// Generate member number
				$member_no = $this->generate_member_number($member_id);
				$this->db->table('member')->where('id', $member_id)->update([
					'member_no' => $member_no
				]);

				// Save documents
				$this->save_member_documents($member_id);

				if (method_exists($this, 'save_multiple_member_documents')) {
					$this->save_multiple_member_documents($member_id);
				}

				// Create annual dues for ordinary members (member_type = 1)
				if ($data['member_type'] == 1) {
					$current_year = date('Y');
					if (method_exists($this, 'create_annual_dues')) {
						$this->create_annual_dues($member_id, $current_year);
					}
				}

				// Account migration - record the payment
				if (method_exists($this, 'account_migration')) {
					$payment_amount = $data['payment'] ?? 0;
					$approver_name = trim($approved_by_name_override);
					$this->account_migration(
						$member_id,
						"Direct Approval by {$approver_name} on " . date('d/m/Y', strtotime($approval_date_override)) . " - Payment: RM " . number_format($payment_amount, 2)
					);
				}

				// Send WhatsApp notification for approval
				if (method_exists($this, 'send_member_approval_whatsapp')) {
					$this->send_member_approval_whatsapp($member_id);
				}

				// Send email notification for approval
				if (!empty($data['email_address']) && method_exists($this, 'send_approval_email')) {
					$this->send_approval_email($member_id);
				}

				// Log the direct approval action
				$this->log_member_action($member_id, 'direct_approval', [
					'approved_by_name' => $approved_by_name_override,
					'approval_date' => $approval_date_override,
					'approved_by_user_id' => $this->session->get('log_id'),
					'member_no' => $member_no,
					'payment_amount' => $data['payment'] ?? 0
				]);

				// Success message with approval details
				$approval_date_display = date('d M Y', strtotime($approval_date_override));
				$this->session->setFlashdata(
					'succ',
					'âœ… <strong>Member Directly Approved Successfully!</strong><br>' .
					'<i class="material-icons" style="vertical-align:middle;">person</i> Member No: <strong>' . $member_no . '</strong><br>' .
					'<i class="material-icons" style="vertical-align:middle;">event</i> Approval Date: <strong>' . $approval_date_display . '</strong><br>' .
					'<i class="material-icons" style="vertical-align:middle;">verified_user</i> Approved By: <strong>' . htmlspecialchars($approved_by_name_override) . '</strong><br>' .
					'<i class="material-icons" style="vertical-align:middle;">check_circle</i> Status: <strong>Active</strong>'
				);
				$this->session->setFlashdata('auto_print', true);

				return redirect()->to('/member/print_registration/' . $member_id);
			} else {
				$this->session->setFlashdata('fail', 'Direct Approval Failed. Please Try Again');
				return redirect()->to('/member/add')->withInput();
			}
		}

		// ========================================
		// SAVE TYPE: DRAFT
		// ========================================
		if ($save_type === 'draft') {
			$data['approval_status'] = -1; // Draft status
			$data['status'] = 'draft';

			if (empty($id)) {
				// NEW DRAFT
				$data['created'] = date('Y-m-d H:i:s');
				$data['modified'] = date('Y-m-d H:i:s');

				$res = $this->db->table('member')->insert($data);

				if ($res) {
					$member_id = $this->db->insertID();
					$this->save_member_documents($member_id);

					if (method_exists($this, 'save_multiple_member_documents')) {
						$this->save_multiple_member_documents($member_id);
					}

					$this->session->setFlashdata('succ', 'Draft saved successfully. You can continue editing later.');
					return redirect()->to('/member/drafts');
				} else {
					$this->session->setFlashdata('fail', 'Failed to save draft. Please try again.');
					return redirect()->to('/member/add');
				}
			} else {
				// UPDATE EXISTING DRAFT
				$data['modified'] = date('Y-m-d H:i:s');

				$res = $this->db->table('member')->where('id', $id)->update($data);

				if ($res) {
					$this->save_member_documents($id);

					if (method_exists($this, 'save_multiple_member_documents')) {
						$this->save_multiple_member_documents($id);
					}

					$this->session->setFlashdata('succ', 'Draft updated successfully.');
					return redirect()->to('/member/drafts');
				} else {
					$this->session->setFlashdata('fail', 'Failed to update draft. Please try again.');
					return redirect()->to('/member/edit/' . $id);
				}
			}
		}

		// ========================================
		// SAVE TYPE: SUBMIT (Normal Save)
		// ========================================

		// DATABASE SAVE OPERATIONS
		if (empty($id)) {
			// NEW MEMBER REGISTRATION
			$data['created'] = date('Y-m-d H:i:s');
			$data['modified'] = date('Y-m-d H:i:s');
			$data['approval_status'] = 0; // Pending approval for new members

			// Only set to inactive if not deceased
			if (!isset($data['status']) || $data['status'] !== 'demise') {
				$data['status'] = 'inactive'; // Inactive until approved
			}

			$res = $this->db->table('member')->insert($data);

			if ($res) {
				$member_id = $this->db->insertID();

				// Save documents if any
				$this->save_member_documents($member_id);

				// Send WhatsApp notification
				if (method_exists($this, 'send_member_registration_whatsapp')) {
					$this->send_member_registration_whatsapp($member_id);
				}

				// Send notification email to admin about new application
				if (method_exists($this, 'notify_admin_new_application')) {
					$this->notify_admin_new_application($member_id);
				}

				if (method_exists($this, 'save_multiple_member_documents')) {
					$this->save_multiple_member_documents($member_id);
				}

				// Send acknowledgment to applicant if email provided
				if (!empty($data['email_address']) && method_exists($this, 'send_acknowledgment_email')) {
					$this->send_acknowledgment_email($member_id);
				}

				$this->session->setFlashdata('succ', 'Member Registration Submitted Successfully. Your application is pending approval.');

				// Redirect to print page with auto-print flag
				$this->session->setFlashdata('auto_print', true);
				return redirect()->to('/member/print_registration/' . $member_id);

			} else {
				$this->session->setFlashdata('fail', 'Registration Failed. Please Try Again');
				return redirect()->to('/member/add');
			}

		} else {
			// UPDATE EXISTING MEMBER
			$data['modified'] = date('Y-m-d H:i:s');

			// Get existing member to check status
			$existing_member = $this->db->table('member')->where('id', $id)->get()->getRowArray();

			// Handle status based on existing approval_status
			if ($existing_member) {
				if ($existing_member['approval_status'] == -1) {
					// Was a DRAFT - now submitting for approval
					$data['approval_status'] = 0; // Move to pending

					// Only set to inactive if not deceased
					if (!isset($data['status']) || $data['status'] !== 'demise') {
						$data['status'] = 'inactive';
					}

				} elseif ($existing_member['approval_status'] == 1) {
					// Already APPROVED - don't change approval_status
					unset($data['approval_status']);

					// ========================================
					// CRITICAL FIX: Keep existing status unless explicitly changed OR deceased
					// ========================================
					/*if (empty($_POST['status'])) {
						// IMPORTANT: Don't unset status if it's been set to 'demise' earlier
						if (!isset($data['status']) || $data['status'] !== 'demise') {
							unset($data['status']);
						}
						// If status IS 'demise', it will be kept and saved
					} else {
						$data['status'] = $_POST['status'];
					}*/
					
					if (!empty($_POST['status'])) {
                        $data['status'] = $_POST['status'];
                    }

				} elseif ($existing_member['approval_status'] == 0) {
					// PENDING - keep as pending
					$data['approval_status'] = 0;

					// Only set to inactive if not deceased
					if (!isset($data['status']) || $data['status'] !== 'demise') {
						$data['status'] = 'inactive';
					}
				}
			}

			$res = $this->db->table('member')->where('id', $id)->update($data);

			if ($res) {
				// Save documents if any
				$this->save_member_documents($id);

				if (method_exists($this, 'save_multiple_member_documents')) {
					$this->save_multiple_member_documents($id);
				}

				// If moved from draft to pending, send notifications
				if ($existing_member && $existing_member['approval_status'] == -1) {
					// Send WhatsApp notification
					if (method_exists($this, 'send_member_registration_whatsapp')) {
						$this->send_member_registration_whatsapp($id);
					}

					// Send notification email to admin
					if (method_exists($this, 'notify_admin_new_application')) {
						$this->notify_admin_new_application($id);
					}

					// Send acknowledgment to applicant
					if (!empty($data['email_address']) && method_exists($this, 'send_acknowledgment_email')) {
						$this->send_acknowledgment_email($id);
					}

					$this->session->setFlashdata('succ', 'Application submitted successfully. Pending approval.');
				} else {
					$this->session->setFlashdata('succ', 'Member Updated Successfully');
				}

				// Redirect to print page with auto-print flag
				$this->session->setFlashdata('auto_print', true);
				return redirect()->to('/member/print_registration/' . $id);

			} else {
				$this->session->setFlashdata('fail', 'Update Failed. Please Try Again');
				return redirect()->to('/member/edit/' . $id);
			}
		}
	}


	// Add this method for manual print access
	public function print_application()
	{
		if (!$this->model->permission_validate('member', 'view')) {
			header('Location: ' . base_url() . '/dashboard');
			exit;
		}

		$id = $this->request->uri->getSegment(3);

		if (!$id || !is_numeric($id)) {
			$this->session->setFlashdata('fail', 'Member ID is required');
			header('Location: ' . base_url() . '/member');
			exit;
		}

		$member = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type', 'left')
			->select('member.*, member_type.name as tname')
			->where('member.id', $id)
			->get()->getRowArray();

		if (!$member) {
			$this->session->setFlashdata('fail', 'Member not found');
			header('Location: ' . base_url() . '/member');
			exit;
		}

		$data['member'] = $member;
		$data['temple_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		$data['auto_print'] = true; // Always auto-print when accessed directly

		// Get proposer details if available
		if ($member['proposer_1_id']) {
			$data['proposer1'] = $this->db->table('member')->where('id', $member['proposer_1_id'])->get()->getRowArray();
		}
		if ($member['proposer_2_id']) {
			$data['proposer2'] = $this->db->table('member')->where('id', $member['proposer_2_id'])->get()->getRowArray();
		}

		echo view('member/print_registration', $data);
	}

	// Helper method to notify admin about new application
	private function notify_admin_new_application($member_id)
	{
		$member = $this->db->table('member')->where('id', $member_id)->get()->getRowArray();

		// You can implement email notification here
		// Log the new application
		log_message('info', 'New member application received: ' . $member['name'] . ' (ID: ' . $member_id . ')');
	}

	// Helper method to send acknowledgment email
	private function send_acknowledgment_email($member_id)
	{
		$member = $this->db->table('member')->where('id', $member_id)->get()->getRowArray();

		if (!empty($member['email_address'])) {
			// Implement your email sending logic here
			// You can use the existing mail template system
			$temple_title = $_SESSION['site_title'] ?? 'Temple';
			$subject = "Membership Application Received - " . $temple_title;

			// Send email using your existing mail function
			// send_mail_with_content(...);
		}
	}
	public function print_registration()
	{
		if (!$this->model->permission_validate('member', 'view')) {
			header('Location: ' . base_url() . '/dashboard');
			exit;
		}

		// Get ID from multiple sources
		$id = null;
		$id = $this->request->uri->getSegment(3);
		if (!$id) {
			$id = $this->request->getGet('id');
		}
		if (!$id) {
			$id = $this->request->getPost('id');
		}

		// Validate ID
		if (!$id || !is_numeric($id)) {
			$this->session->setFlashdata('fail', 'Member ID is required');
			header('Location: ' . base_url() . '/member');
			exit;
		}

		// Get main member data
		$member = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type', 'left')
			->select('member.*, member_type.name as tname')
			->where('member.id', $id)
			->get()->getRowArray();

		if (!$member) {
			$this->session->setFlashdata('fail', 'Member not found');
			header('Location: ' . base_url() . '/member');
			exit;
		}

		// ========================================
		// FETCH PROPOSER 1 FULL NAME
		// ========================================
		if (!empty($member['proposer_1_id'])) {
			$proposer1 = $this->db->table('member')
				->select('id, name, first_name, last_name, member_no, prefix')
				->where('id', $member['proposer_1_id'])
				->get()->getRowArray();

			if ($proposer1) {
				// Build full name with prefix
				$proposer1_full_name = '';
				if (!empty($proposer1['prefix'])) {
					$proposer1_full_name .= $proposer1['prefix'] . ' ';
				}

				// Use name field if available, otherwise combine first and last name
				if (!empty($proposer1['name'])) {
					$proposer1_full_name .= $proposer1['name'];
				} else {
					if (!empty($proposer1['first_name'])) {
						$proposer1_full_name .= $proposer1['first_name'];
					}
					if (!empty($proposer1['last_name'])) {
						$proposer1_full_name .= ' ' . $proposer1['last_name'];
					}
				}

				// Override the proposer_1_name with fetched full name
				$member['proposer_1_name'] = trim($proposer1_full_name);
				$member['proposer_1_member_no'] = $proposer1['member_no'];
			}
		}

		// ========================================
		// FETCH PROPOSER 2 FULL NAME
		// ========================================
		if (!empty($member['proposer_2_id'])) {
			$proposer2 = $this->db->table('member')
				->select('id, name, first_name, last_name, member_no, prefix')
				->where('id', $member['proposer_2_id'])
				->get()->getRowArray();

			if ($proposer2) {
				// Build full name with prefix
				$proposer2_full_name = '';
				if (!empty($proposer2['prefix'])) {
					$proposer2_full_name .= $proposer2['prefix'] . ' ';
				}

				// Use name field if available, otherwise combine first and last name
				if (!empty($proposer2['name'])) {
					$proposer2_full_name .= $proposer2['name'];
				} else {
					if (!empty($proposer2['first_name'])) {
						$proposer2_full_name .= $proposer2['first_name'];
					}
					if (!empty($proposer2['last_name'])) {
						$proposer2_full_name .= ' ' . $proposer2['last_name'];
					}
				}

				// Override the proposer_2_name with fetched full name
				$member['proposer_2_name'] = trim($proposer2_full_name);
				$member['proposer_2_member_no'] = $proposer2['member_no'];
			}
		}

		$data['member'] = $member;
		$data['temple_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		$data['show_navigation'] = true;

		// Pass the auto_print flag to the view
		$data['auto_print'] = session()->getFlashdata('auto_print');

		echo view('member/print_registration', $data);
	}
	
	// Save uploaded documents
	private function save_member_documents($member_id)
	{
		$documents = ['ic_copy', 'signature', 'other_document'];

		foreach ($documents as $doc_type) {
			// Check if remove flag is set
			$remove_key = 'remove_' . ($doc_type === 'ic_copy' ? 'ic' : ($doc_type === 'signature' ? 'signature' : $doc_type));
			if ($this->request->getPost($remove_key) == '1') {
				// Delete existing document
				$existing = $this->db->table('member_documents')
					->where('member_id', $member_id)
					->where('document_type', $doc_type)
					->get()->getRowArray();

				if ($existing) {
					// Delete physical file
					if (!empty($existing['file_path']) && file_exists(FCPATH . $existing['file_path'])) {
						unlink(FCPATH . $existing['file_path']);
					}
					// Delete from database
					$this->db->table('member_documents')->where('id', $existing['id'])->delete();
				}
				continue;
			}

			// Handle new file upload
			if (!empty($_FILES[$doc_type]['name'])) {
				$file = $this->request->getFile($doc_type);
				if ($file->isValid() && !$file->hasMoved()) {
					$newName = $file->getRandomName();
					$file->move('uploads/member_documents/', $newName);

					// Check if document already exists for this member
					$existing = $this->db->table('member_documents')
						->where('member_id', $member_id)
						->where('document_type', $doc_type)
						->get()->getRowArray();

					if ($existing) {
						// Delete old file
						if (!empty($existing['file_path']) && file_exists(FCPATH . $existing['file_path'])) {
							unlink(FCPATH . $existing['file_path']);
						}
						// Update record
						$this->db->table('member_documents')
							->where('id', $existing['id'])
							->update([
								'document_name' => $_FILES[$doc_type]['name'],
								'file_path' => 'uploads/member_documents/' . $newName,
								'uploaded_by' => $this->session->get('log_id'),
								'uploaded_at' => date('Y-m-d H:i:s')
							]);
					} else {
						// Insert new record
						$this->db->table('member_documents')->insert([
							'member_id' => $member_id,
							'document_type' => $doc_type,
							'document_name' => $_FILES[$doc_type]['name'],
							'file_path' => 'uploads/member_documents/' . $newName,
							'uploaded_by' => $this->session->get('log_id'),
							'uploaded_at' => date('Y-m-d H:i:s')
						]);
					}
				}
			}
		}

		// Handle member photo separately (stored in member table)
		$remove_photo = $this->request->getPost('remove_photo');
		if ($remove_photo == '1') {
			$member = $this->db->table('member')->where('id', $member_id)->get()->getRowArray();
			if ($member && !empty($member['member_photo'])) {
				$photo_path = FCPATH . 'uploads/member_photos/' . $member['member_photo'];
				if (file_exists($photo_path)) {
					unlink($photo_path);
				}
				$this->db->table('member')->where('id', $member_id)->update(['member_photo' => null]);
			}
		}
	}
	public function findmembernoExists()
	{
		$member_no = $this->request->getPost('member_no');
		$updateid = $this->request->getPost('update_id');
		if (!empty($updateid)) {
			$query = $this->db->table('member')->where(['member_no' => $member_no, 'id !=' => $updateid, 'status' => 1])->countAllResults();
		} else {
			$query = $this->db->table('member')->where(['member_no' => $member_no, 'status' => 1])->countAllResults();
		}
		if ($query > 0) {
			echo "false";
		} else {
			echo "true";
		}
	}
	
	public function renewal_save()
	{
		$id = $_POST['id'];
		$ip = 'unknown';
		$this->requestmodel = new RequestModel();
		$ip = $this->requestmodel->getIpAddress();
		if ($ip != 'unknown') {
			$ip_details = $this->requestmodel->getLocation($ip);
			$renewal_data['ip'] = $ip;
			$renewal_data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
			$renewal_data['ip_details'] = json_encode($ip_details);
		}

		if (!empty($id)) {
			$data['start_date'] = date('Y-m-d');
			$endDate = date("Y-m-d", strtotime("+1 year -1 day", strtotime($data['start_date'])));
			$data['end_date'] = $endDate;
			$data['status'] = 'active';  // Changed from 1 to 'active'
			$data['renewal_status'] = 0;
			$data['added_by'] = $this->session->get('log_id');
			$data['payment_status'] = 2;
			$data['payment_mode'] = trim($_POST['paymentmode']);
			$data['modified'] = date('Y-m-d H:i:s');

			$res = $this->db->table('member')->where('id', $id)->update($data);

			if ($res) {
				$renewal_data['member_id'] = $id;
				$renewal_data['renewal_start_date'] = date("Y-m-d");
				$renewal_data['renewal_end_date'] = $endDate;
				$this->db->table('member_renewal')->insert($renewal_data);

				$this->account_migration($id, $content = "Member Renewal");

				if (!empty($_POST['email_address'])) {
					// Email sending code...
				}

				$this->session->setFlashdata('succ', 'Member Renewal Successfully completed');
				header("Location: " . base_url() . "/member");
			} else {
				$this->session->setFlashdata('fail', 'Please Try Again');
				header("Location: " . base_url() . "/member");
			}
		}
	}
	public function account_migration($member_id, $content)
	{
		$member_datas = $this->db->table('member')->where('id', $member_id)->get()->getRowArray();
		$payment_mode_details = $this->db->table('payment_mode')->where('id', 3)->get()->getRowArray();
		if (empty($payment_mode_details['id']))
			$payment_mode_details = $this->db->table('payment_mode')->get()->getRowArray();
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
		// Debit ledger
		if (!empty($member_datas['ledger_id'])) {
			$dr_id = $member_datas['ledger_id'];
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
		if (!empty($member_datas['payment'])) {
			$number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
			if (empty($number)) {
				$num = 1;
			} else {
				$num = $number['number'] + 1;
			}
			$entry_date = date('Y-m-d');
			$yr = date('Y', $entry_date);
			$mon = date('m', $entry_date);
			$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();
			$entries['entry_code'] = 'REC' . date('y', strtotime($entry_date)) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));

			$entries['entrytype_id'] = '1';
			$entries['number'] = $num;
			$entries['date'] = $member_datas['start_date'];

			$entries['dr_total'] = $member_datas['payment'];
			$entries['cr_total'] = $member_datas['payment'];
			$entries['narration'] = $content;
			$entries['inv_id'] = $member_id;
			$entries['type'] = '11';
			$ent = $this->db->table('entries')->insert($entries);
			$en_id = $this->db->insertID();
			if (!empty($en_id)) {
				$eitems_d['entry_id'] = $en_id;
				$eitems_d['ledger_id'] = $dr_id;
				$eitems_d['amount'] = $member_datas['payment'];
				$eitems_d['dc'] = 'C';
				$this->db->table('entryitems')->insert($eitems_d);

				$eitems_c['entry_id'] = $en_id;
				$eitems_c['ledger_id'] = $payment_mode_details['ledger_id'];
				$eitems_c['amount'] = $member_datas['payment'];
				$eitems_c['dc'] = 'D';
				$this->db->table('entryitems')->insert($eitems_c);
			}
			return true;
		} else
			return false;
	}
	public function delete()
	{
		if (!$this->model->permission_validate('member', 'delete_p')) {
			header('Location: ' . base_url() . '/dashboard');
		}
		$id = $this->request->uri->getSegment(3);
		$res = $this->db->table('ubayam')->delete(['id' => $id]);
		if ($res) {
			$this->session->setFlashdata('succ', 'Ubayam Deleted Successfully');
			header("Location: " . base_url() . "/ubayam");
		} else {
			$this->session->setFlashdata('fail', 'Please Try Again');
			header("Location: " . base_url() . "/ubayam");
		}
		header("Location: " . base_url() . "/ubayam");

	}
	public function renewal_report()
	{
		if (!$this->model->list_validate('member')) {
			header('Location: ' . base_url() . '/dashboard');
		}
		$data['permission'] = $this->model->get_permission('member');

		$data['list'] = $res;
		// echo '<pre>'; print_r($data); die;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('member/renewal_report', $data);
		echo view('template/footer');
	}
	public function sticker()
    {
        echo view('template/header');
		echo view('template/sidebar');
		echo view('member/sticker_filter');
		echo view('template/footer');
    }
    
    public function print_stickers()
    {
        $membership_status = $this->request->getPost('membership_status');
        $country = $this->request->getPost('country');
    
        $builder = $this->db->table('member');
    
        if (!empty($membership_status)) {
            $builder->where('membership_status', $membership_status);
        }
    
        /*if (!empty($country)) {
            if ($country == 'Malaysia') {
                $builder->where('country', 'Malaysia');
            } else {
                $builder->where('country !=', 'Malaysia');
            }
        }*/
        
        // Country filter
        if (!empty($country)) {
            if ($country === 'Others') {
                $builder->where('country !=', 'Malaysia');
            } else {
                $builder->where('country', $country);
            }
        }
    
        $members = $builder->get()->getResultArray();
    
        return view('member/member_sticker', ['members' => $members]);
    }
	public function member_sticker()
    {
        $members = $this->db->table('member')
            ->select('member_no, prefix, first_name, last_name, house_no_street, postal_code, locality, district, state, country')
            ->get()
            ->getResultArray();
    
        return view('member/member_sticker', ['members' => $members]);
    }
	public function get_renewal_report()
	{
		$fdata = $_REQUEST['fdt'];
		$tdata = $_REQUEST['tdt'];
		$qry = $this->db->table('member', 'member_type.name as tname')
			->join('member_type', 'member_type.id = member.member_type')
			->join('member_renewal', 'member_renewal.member_id = member.id')
			->select('member_type.name as tname,member_renewal.renewal_end_date,member_renewal.renewal_start_date')
			->select('member.*')
			->where('member_renewal.renewal_start_date >=', $fdata)
			->where('member_renewal.renewal_start_date <=', $tdata);
		$res = $qry->get()->getResultArray();
		$i = 1;
		$data = array();
		if (!empty($res)) {
			foreach ($res as $r) {
				$data[] = array(
					$i++,
					$r['name'],
					$r['member_no'],
					$r['tname'],
					date('d/m/Y', strtotime($r['renewal_start_date'])),
					date('d/m/Y', strtotime($r['renewal_end_date'])),
					$r['payment']
				);
			}
		}
		$result = array(
			"draw" => 0,
			"recordsTotal" => $i - 1,
			"recordsFiltered" => $i - 1,
			"data" => $data,
		);
		echo json_encode($result);
		exit();
	}
	public function print_renewalreport()
	{
		if (!$this->model->list_validate('member')) {
			header('Location: ' . base_url() . '/dashboard');
		}
		$data['fdate'] = $_REQUEST['fdt'];
		$data['tdate'] = $_REQUEST['tdt'];
		$fdata = $_REQUEST['fdt'];
		$tdata = $_REQUEST['tdt'];
		$i = 0;
		$qry = $this->db->table('member', 'member_type.name as tname')
			->join('member_type', 'member_type.id = member.member_type')
			->join('member_renewal', 'member_renewal.member_id = member.id')
			->select('member_type.name as tname,member_renewal.renewal_end_date,member_renewal.renewal_start_date')
			->select('member.*')
			->where('member_renewal.renewal_start_date >=', $fdata)
			->where('member_renewal.renewal_start_date <=', $tdata);
		$res = $qry->get()->getResultArray();
		$data['member_data'] = $res;
		if ($_REQUEST['pdf_renewalreport'] == "PDF") {
			$file_name = "Member_Renewal_Report_" . $data['fdate'] . "_to_" . $data['tdate'];
			$dompdf = new \Dompdf\Dompdf();
			$options = $dompdf->getOptions();
			$options->set(array('isRemoteEnabled' => true));
			$dompdf->setOptions($options);
			$dompdf->loadHtml(view('member/pdf/member_renewal_print', ["pdfdata" => $data]), 'UTF-8');
			$dompdf->setPaper('LEGAL', 'portrait');
			$dompdf->render();
			$dompdf->stream($file_name);
		} elseif ($_REQUEST['excel_renewalreport'] == "EXCEL") {
			$fileName = "Member_Renewal_Report_" . $data['fdate'] . "_to_" . $data['tdate'];
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
			$style = array(
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				)
			);
			$sheet->getStyle("A1:G1")->applyFromArray($style);
			$sheet->mergeCells('A1:G1');
			$sheet->setCellValue('A1', $_SESSION['site_title']);
			$sheet->setCellValue('A2', 'S.No');
			$sheet->setCellValue('B2', 'Member Name');
			$sheet->setCellValue('C2', 'Member No');
			$sheet->setCellValue('D2', 'Member Type');
			$sheet->setCellValue('E2', 'Renewal Start Date');
			$sheet->setCellValue('F2', 'Renewal End Date');
			$sheet->setCellValue('G2', 'Amount');
			$rows = 3;
			$si = 1;
			if (count($data['member_data']) > 0) {
				foreach ($data['member_data'] as $val) {
					$sheet->setCellValue('A' . $rows, $si);
					$sheet->setCellValue('B' . $rows, $val['name']);
					$sheet->setCellValue('C' . $rows, $val['member_no']);
					$sheet->setCellValue('D' . $rows, $val['tname']);
					$sheet->setCellValue('E' . $rows, date('d/m/Y', strtotime($val['renewal_start_date'])));
					$sheet->setCellValue('F' . $rows, date('d/m/Y', strtotime($val['renewal_end_date'])));
					$sheet->setCellValue('G' . $rows, $val['payment']);
					$rows++;
					$si++;
				}
			}
			$writer = new Xlsx($spreadsheet);
			$writer->save('uploads/excel/' . $fileName . '.xlsx');
			return $this->response->download('uploads/excel/' . $fileName . '.xlsx', null)->setFileName($fileName . '.xlsx');
		} else {
			echo view('member/member_renewal_print', $data);
		}
	}
	public function get_member_amount()
	{
		$id = $_POST['id'];
		$data = $this->db->table('member_type')->select('amount')->where('id', $id)->get()->getRowArray();
		echo json_encode($data);
	}
	public function print_page()
	{
		if (!$this->model->permission_validate('member', 'print')) {
			header('Location: ' . base_url() . '/dashboard');
		}
		$id = $this->request->uri->getSegment(3);
		$qry = $this->db->table('member', 'member_type.name as tname')
			->join('member_type', 'member_type.id = member.member_type')
			->select('member_type.name as tname')
			->select('member.*')
			->where("member.id", $id);

		$res = $qry->get()->getRowArray();

		$data['qry1'] = $res;
		echo view('member/print_page', $data);
	}








/**
 * ============================================================
 * STICKER PRINT - A4 Sheet with 12 Name Tag Stickers
 * Sticker Size: 105mm x 48mm | Layout: 2 columns x 6 rows
 * Route (auto-route): /member/sticker_print
 * ============================================================
 */
public function sticker_print()
{
    if (!$this->model->permission_validate('member', 'view')) {
        header('Location: ' . base_url() . '/dashboard');
        exit;
    }

    // Get selected member IDs from POST (from member list checkboxes)
    $member_ids = $this->request->getPost('member_ids') ?? $this->request->getGet('ids') ?? [];

    if (is_string($member_ids)) {
        $member_ids = array_filter(explode(',', $member_ids));
    }

    // Get all approved members for the selection dropdown/search
    $data['all_members'] = $this->db->table('member')
        ->join('member_type', 'member_type.id = member.member_type', 'left')
        ->select('member.id, member.member_no, member.name, member.first_name, 
                  member.last_name, member.ic_no, member.mobile, member.tel_phone_mobile,
                  member.house_no_street, member.district, member.postal_code, 
                  member.locality, member.state, member.country, member.joining_date,
                  member.start_date, member.end_date, member.member_type, member.status,
                  member_type.name as tname')
        ->where('member.approval_status', 1)
        ->where('member.status', 'active')
        ->orderBy('member.member_no', 'ASC')
        ->get()->getResultArray();

    // If specific members were selected, fetch their full data
    $data['selected_members'] = [];
    if (!empty($member_ids)) {
        $data['selected_members'] = $this->db->table('member')
            ->join('member_type', 'member_type.id = member.member_type', 'left')
            ->select('member.*, member_type.name as tname')
            ->whereIn('member.id', $member_ids)
            ->orderBy('member.member_no', 'ASC')
            ->get()->getResultArray();
    }

    // Temple details for sticker header
    $data['temple_details'] = $this->db->table('admin_profile')
        ->where('id', 1)->get()->getRowArray();

    $data['permission'] = $this->model->get_permission('member');

    echo view('template/header');
    echo view('template/sidebar');
    echo view('member/sticker_print', $data);
    echo view('template/footer');
}

	public function renewal_page()
	{

		$id = $this->request->uri->getSegment(3);
		$data['data'] = $this->db->table('member')->where('id', $id)->get()->getRowArray();
		$data['member_type_list'] = $this->db->table('member_type')->get()->getResultArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->where('paid_through', 'DIRECT')->get()->getResultArray();


		echo view('template/header');
		echo view('template/sidebar');
		echo view('member/renewal_page', $data);
		echo view('template/footer');
	}

	public function renewal()
	{
		$currentDate = date("Y-m-d");

		// First, let's debug what we have
		$debug_active_members = $this->db->table('member')
			->select('id, name, member_no, end_date, status, renewal_status, approval_status, member_type')
			->where('member_type', 1) // Only ordinary members
			->where('approval_status', 1) // Only approved members
			->where('end_date <', $currentDate)
			->get()->getResultArray();

		// Debug: uncomment this line to see what members should be renewed
		// echo "<pre>"; print_r($debug_active_members); die();

		// Deactivate members with end date below the current date
		// Only for ordinary members (member_type = 1)
		$updated = $this->db->table('member')
			->where('end_date <', $currentDate)
			->where('end_date IS NOT NULL')  // Don't check life members
			->where('member_type', 1)  // Only ordinary members
			->where('approval_status', 1)  // Only approved members
			->where('status', 'active')  // Only currently active members
			->update([
				'renewal_status' => 1,
				'status' => 'inactive'
			]);

		// Debug: uncomment to see how many records were updated
		// echo "Updated records: " . $updated; die();

		// Retrieve members who need renewal
		$query = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type', 'left')
			->select('member.*, member_type.name as type_name')
			->where('member.renewal_status', 1)
			->where('member.status', 'inactive')
			->where('member.member_type', 1)  // Only ordinary members need renewal
			->where('member.approval_status', 1)  // Only approved members
			->orderBy('member.end_date', 'DESC')
			->get();

		$data['inactiveMembers'] = $query->getResultArray();
		// var_dump($data['inactiveMembers']); // Debug: uncomment to see renewal candidates
		// exit;

		// Debug: uncomment to see renewal candidates
		// echo "<pre>Renewal candidates: "; print_r($data['inactiveMembers']); die();

		// Load the view with the data
		echo view('template/header');
		echo view('template/sidebar');
		echo view('member/renewal', $data);
		echo view('template/footer');
	}



	public function cron()
	{
		// Load the database library
		$db = \Config\Database::connect();

		// Get the current date
		$currentDate = date("Y-m-d");

		// Query to retrieve active members whose end date is before the current date
		$query = $db->query("SELECT * FROM member WHERE end_date < '$currentDate' AND status = 1");

		// Deactivate members
		foreach ($query->getResultArray() as $row) {
			$memberId = $row['id']; // replace 'id' with your actual primary key field
			$db->table('member')->set('status', 2)->where('id', $memberId)->update();
		}

		echo "Cron job executed successfully.";
	}
	public function send_whatsapp_msg($id)
	{
		$tmpid = 1;
		$data['temple_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		$qry = $this->db->table('member', 'member_type.name as tname')
			->join('member_type', 'member_type.id = member.member_type')
			->select('member_type.name as tname')
			->select('member.*')
			->where("member.id", $id);
		$member = $qry->get()->getRowArray();

		$data['qry1'] = $member;
		if (!empty($member['mobile'])) {
			$html = view('member/pdf', $data);
			$options = new Options();
			$options->set('isHtml5ParserEnabled', true);
			$options->set(array('isRemoteEnabled' => true));
			$options->set('isPhpEnabled', true);
			$dompdf = new Dompdf($options);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			$filePath = FCPATH . 'uploads/documents/member_card_' . $id . '.pdf';

			file_put_contents($filePath, $dompdf->output());
			$message_params = array();
			$message_params[] = $member['name'];
			$message_params[] = $member['tname'];
			$message_params[] = $member['member_no'];
			$message_params[] = date('d M, Y', strtotime($member['start_date']));
			$media['url'] = base_url() . '/uploads/documents/member_card_' . $id . '.pdf';
			$media['filename'] = 'member_card.pdf';
			$mobile_number = $member['mobile'];
			//$mobile_number = '+919092615446';
			// print_r($mobile_number);
			// print_r($message_params);
			// print_r($media);
			// die; 
			$whatsapp_resp = whatsapp_aisensy($mobile_number, $message_params, 'member_reg_live', $media);
			//print_r($whatsapp_resp);
			//echo $whatsapp_resp['success'];
			/* if($whatsapp_resp['success']) 
					 //echo 'success';
					 echo view('hallbooking/whatsapp_resp_suc');
					 else 
					 //echo 'fail'; 
					 echo view('hallbooking/whatsapp_resp_fail'); */
		}
	}

	// Add these methods to the Member controller

	// Card customization configuration
	public function card_settings()
	{
		if (!$this->model->permission_validate('member', 'view')) {
			header('Location: ' . base_url() . '/dashboard');
			exit;
		}

		// Get temple details
		$data['temple_details'] = $this->db->table('admin_profile')
			->where('id', 1)
			->get()
			->getRowArray();

		// Get sample members for testing
		$data['members'] = $this->db->table('member')
			->select('id, name, member_no')
			->where('status', 'active')
			->where('member_no IS NOT NULL')
			->orderBy('name', 'ASC')
			->limit(50)
			->get()
			->getResultArray();

		// Get current default settings
		$data['card_settings'] = $this->db->table('card_configuration')
			->where('is_active', 1)
			->groupStart()
			->where('member_type_id IS NULL')
			->orWhere('member_type_id', 0)
			->groupEnd()
			->get()
			->getRowArray();

		echo view('template/header');
		echo view('template/sidebar');
		echo view('member/card_settings', $data);
		echo view('template/footer');
	}

	public function save_card_settings()
	{
		$response = ['success' => false, 'message' => ''];

		try {
			$memberTypeId = $this->request->getPost('member_type_id');
			if ($memberTypeId === '' || $memberTypeId === '0') {
				$memberTypeId = null;
			}

			// Build gradient from colors
			$frontColor1 = $this->request->getPost('front_color_1') ?? '#8B0000';
			$frontColor2 = $this->request->getPost('front_color_2') ?? '#3d0000';
			$backColor1 = $this->request->getPost('back_color_1') ?? '#8B7500';
			$backColor2 = $this->request->getPost('back_color_2') ?? '#4a3f00';

			$data = [
				'member_type_id' => $memberTypeId,
				'is_active' => 1,

				// Front colors - NEW columns
				'front_color_1' => $frontColor1,
				'front_color_2' => $frontColor2,
				'border_color' => $this->request->getPost('border_color') ?? '#D4AF37',

				// Existing columns - update them too
				'background_gradient' => "linear-gradient(145deg, {$frontColor1} 0%, {$frontColor2} 100%)",
				'temple_name_color' => $this->request->getPost('temple_name_color') ?? '#D4AF37',
				'text_color' => '#ffffff',

				// Back colors - NEW columns
				'back_color_1' => $backColor1,
				'back_color_2' => $backColor2,
				'back_title_color' => $this->request->getPost('back_title_color') ?? '#1a1a1a',

				// Existing columns
				'back_background' => $backColor1,
				'back_text_color' => $this->request->getPost('back_text_color') ?? '#1a1a1a',

				// Display options - Existing columns
				'show_logo' => $this->request->getPost('show_logo') ?? 1,
				'show_temple_subtitle' => $this->request->getPost('show_temple_name') ?? 1,
				'show_temple_address' => $this->request->getPost('show_temple_address') ?? 1,
				'show_photo' => $this->request->getPost('show_photo') ?? 1,
				'show_member_name' => $this->request->getPost('show_member_name') ?? 1,
				'show_member_no' => $this->request->getPost('show_member_no') ?? 1,
				'show_member_type' => $this->request->getPost('show_member_type_badge') ?? 1,
				'show_ic_number' => $this->request->getPost('show_ic_number') ?? 1,
				'show_join_date' => $this->request->getPost('show_join_date') ?? 1,
				'show_validity' => $this->request->getPost('show_validity') ?? 1,

				// Display options - NEW columns
				'show_status_badge' => $this->request->getPost('show_status_badge') ?? 1,
				'show_member_type_badge' => $this->request->getPost('show_member_type_badge') ?? 1,
				'show_corner_decorations' => $this->request->getPost('show_corner_decorations') ?? 1,
				'show_om_watermark' => $this->request->getPost('show_om_watermark') ?? 1,

				// Back side - Existing columns
				'show_back_side' => $this->request->getPost('show_back_side') ?? 1,
				'back_title' => $this->request->getPost('back_title') ?? 'Terms & Conditions',
				'custom_rules' => $this->request->getPost('back_content') ?? '',
				'show_qr_code' => $this->request->getPost('show_qr_code') ?? 1,
				'show_contact_info' => $this->request->getPost('show_contact_info') ?? 1,
				'show_signature_section' => $this->request->getPost('show_signature') ?? 1,

				'updated_at' => date('Y-m-d H:i:s'),
				'updated_by' => session()->get('user_id') ?? 1,
			];

			// Check if settings exist for this member type
			if ($memberTypeId === null) {
				$existing = $this->db->table('card_configuration')
					->where('is_active', 1)
					->groupStart()
					->where('member_type_id IS NULL')
					->orWhere('member_type_id', 0)
					->groupEnd()
					->get()
					->getRowArray();
			} else {
				$existing = $this->db->table('card_configuration')
					->where('member_type_id', $memberTypeId)
					->get()
					->getRowArray();
			}

			if ($existing) {
				// Update existing
				$this->db->table('card_configuration')
					->where('id', $existing['id'])
					->update($data);
			} else {
				// Insert new
				$data['created_at'] = date('Y-m-d H:i:s');
				$data['card_name'] = 'Default Template';
				$data['template_type'] = 'default';
				$this->db->table('card_configuration')->insert($data);
			}

			$response['success'] = true;
			$response['message'] = 'Settings saved successfully';

		} catch (\Exception $e) {
			$response['message'] = 'Error: ' . $e->getMessage();
		}

		return $this->response->setJSON($response);
	}

	public function get_card_settings($memberTypeId = null)
	{
		$response = ['success' => false, 'settings' => null];

		try {
			if ($memberTypeId === '0' || $memberTypeId === '') {
				$memberTypeId = null;
			}

			// First try to get specific settings
			if ($memberTypeId === null) {
				$settings = $this->db->table('card_configuration')
					->where('is_active', 1)
					->groupStart()
					->where('member_type_id IS NULL')
					->orWhere('member_type_id', 0)
					->groupEnd()
					->get()
					->getRowArray();
			} else {
				$settings = $this->db->table('card_configuration')
					->where('member_type_id', $memberTypeId)
					->where('is_active', 1)
					->get()
					->getRowArray();
			}

			// If no specific settings, get default
			if (!$settings && $memberTypeId !== null) {
				$settings = $this->db->table('card_configuration')
					->where('is_active', 1)
					->groupStart()
					->where('member_type_id IS NULL')
					->orWhere('member_type_id', 0)
					->groupEnd()
					->get()
					->getRowArray();
			}

			// If still no settings, return defaults
			if (!$settings) {
				$settings = $this->getDefaultCardSettings();
			} else {
				// Map existing fields to view field names
				$settings['show_temple_name'] = $settings['show_temple_subtitle'] ?? 1;
				$settings['back_content'] = $settings['custom_rules'] ?? '';
				$settings['show_signature'] = $settings['show_signature_section'] ?? 1;

				// Extract colors from gradient if front_color_1 is not set
				if (empty($settings['front_color_1']) && !empty($settings['background_gradient'])) {
					preg_match_all('/#[a-fA-F0-9]{6}/', $settings['background_gradient'], $matches);
					if (!empty($matches[0])) {
						$settings['front_color_1'] = $matches[0][0] ?? '#8B0000';
						$settings['front_color_2'] = $matches[0][1] ?? '#3d0000';
					}
				}

				// Set defaults for new columns if null
				$settings['front_color_1'] = $settings['front_color_1'] ?? '#8B0000';
				$settings['front_color_2'] = $settings['front_color_2'] ?? '#3d0000';
				$settings['border_color'] = $settings['border_color'] ?? '#D4AF37';
				$settings['back_color_1'] = $settings['back_color_1'] ?? '#8B7500';
				$settings['back_color_2'] = $settings['back_color_2'] ?? '#4a3f00';
				$settings['back_title_color'] = $settings['back_title_color'] ?? '#1a1a1a';
				$settings['show_status_badge'] = $settings['show_status_badge'] ?? 1;
				$settings['show_member_type_badge'] = $settings['show_member_type_badge'] ?? $settings['show_member_type'] ?? 1;
				$settings['show_corner_decorations'] = $settings['show_corner_decorations'] ?? 1;
				$settings['show_om_watermark'] = $settings['show_om_watermark'] ?? 1;
			}

			$response['success'] = true;
			$response['settings'] = $settings;

		} catch (\Exception $e) {
			$response['message'] = 'Error: ' . $e->getMessage();
		}

		return $this->response->setJSON($response);
	}

	private function getDefaultCardSettings()
	{
		return [
			'front_color_1' => '#8B0000',
			'front_color_2' => '#3d0000',
			'border_color' => '#D4AF37',
			'temple_name_color' => '#D4AF37',
			'back_color_1' => '#8B7500',
			'back_color_2' => '#4a3f00',
			'back_title_color' => '#1a1a1a',
			'back_text_color' => '#1a1a1a',
			'show_logo' => 1,
			'show_temple_name' => 1,
			'show_temple_address' => 1,
			'show_status_badge' => 1,
			'show_member_type_badge' => 1,
			'show_photo' => 1,
			'show_corner_decorations' => 1,
			'show_om_watermark' => 1,
			'show_member_name' => 1,
			'show_member_no' => 1,
			'show_ic_number' => 1,
			'show_join_date' => 1,
			'show_validity' => 1,
			'show_back_side' => 1,
			'back_title' => 'Terms & Conditions',
			'back_content' => "This card remains the property of the temple.\nMembers must present this card during temple events.\nLost or damaged cards must be reported immediately.\nThis card is non-transferable and for personal use only.\nAnnual renewal is required for Ordinary Members.",
			'show_qr_code' => 1,
			'show_contact_info' => 1,
			'show_signature' => 1,
		];
	}
	public function preview_card($member_id)
	{
		$member = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type')
			->select('member.*, member_type.name as type_name')
			->where('member.id', $member_id)
			->get()->getRowArray();

		if (!$member) {
			show_404();
		}

		// Get card settings for this member type
		$settings = $this->db->table('card_configuration')
			->where('member_type_id', $member['member_type'])
			->get()->getRowArray();

		// If no specific settings, get default
		if (!$settings) {
			$settings = $this->db->table('card_configuration')
				->where('member_type_id', null)
				->get()->getRowArray();
		}

		$data['member'] = $member;
		$data['settings'] = $settings;
		$data['temple_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

		echo view('member/card_preview', $data);
	}

	// Enhanced generate member card with customization
	// public function generate_member_card($id)
	// {
	// 	// Fetch complete member data with proper join
	// 	$member = $this->db->table('member')
	// 		->join('member_type', 'member_type.id = member.member_type', 'left')
	// 		->select('member.*, member_type.name as type_name')
	// 		->where('member.id', $id)
	// 		->get()->getRowArray();

	// 	if (!$member) {
	// 		log_message('error', 'Member not found with ID: ' . $id);
	// 		return false;
	// 	}

	// 	// Debug: Log the member data to see what we're getting
	// 	log_message('info', 'Member data: ' . json_encode($member));

	// 	// Get card configuration
	// 	$card_config = $this->db->table('card_configuration')
	// 		->where('member_type_id', $member['member_type'])
	// 		->where('is_active', 1)
	// 		->get()->getRowArray();

	// 	if (!$card_config) {
	// 		$card_config = $this->db->table('card_configuration')
	// 			->where('member_type_id', null)
	// 			->where('is_active', 1)
	// 			->get()->getRowArray();
	// 	}

	// 	// Default configuration with modern design
	// 	if (!$card_config) {
	// 		$card_config = [
	// 			'font_family' => 'Arial, sans-serif',
	// 			'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
	// 			'text_color' => 'white',
	// 			'show_logo' => 1,
	// 			'show_photo' => 1,
	// 			'show_member_no' => 1,
	// 			'show_member_name' => 1,
	// 			'show_member_type' => 1,
	// 			'show_ic_number' => 1,
	// 			'show_join_date' => 1,
	// 			'show_validity' => 1,
	// 			'show_qr_code' => 0,
	// 			'show_field_labels' => 1,
	// 			'show_temple_subtitle' => 1,
	// 			'show_temple_address' => 1,
	// 			'show_back_side' => 1,
	// 			'card_orientation' => 'landscape',
	// 			'temple_name_font_size' => '12pt',
	// 			'temple_name_color' => '#ffffff',
	// 			'header_alignment' => 'center',
	// 			'photo_width' => '20mm',
	// 			'photo_height' => '25mm',
	// 			'card_padding' => '5mm',
	// 			'back_title' => 'Terms & Conditions',
	// 			'custom_rules' => "1. This card is the property of the temple.\n2. Must be carried during temple visits.\n3. Lost cards should be reported immediately.\n4. Annual renewal required for ordinary members.\n5. Non-transferable.",
	// 			'back_background' => '#f8f9fa',
	// 			'back_text_color' => '#333',
	// 			'show_signature_section' => 1,
	// 			'show_contact_info' => 1,
	// 			'detail_row_margin' => '2mm',
	// 			'label_font_size' => '8pt',
	// 			'value_font_size' => '9pt'
	// 		];
	// 	}

	// 	$data['member'] = $member;
	// 	$data['card_config'] = $card_config;
	// 	$data['temple_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

	// 	// Generate HTML
	// 	$html = view('member/member_card_customizable', $data);

	// 	// Configure DomPDF
	// 	$options = new Options();
	// 	$options->set('isHtml5ParserEnabled', true);
	// 	$options->set('isRemoteEnabled', true);
	// 	$options->set('isPhpEnabled', true);
	// 	$options->set('defaultFont', 'DejaVu Sans');
	// 	$options->set('dpi', 300);

	// 	$dompdf = new Dompdf($options);
	// 	$dompdf->loadHtml($html);

	// 	// Set paper size
	// 	if ($card_config['card_orientation'] == 'portrait') {
	// 		$dompdf->setPaper([0, 0, 153.07, 242.65], 'portrait');
	// 	} else {
	// 		$dompdf->setPaper([0, 0, 242.65, 153.07], 'portrait');
	// 	}

	// 	$dompdf->render();

	// 	// Create directory if it doesn't exist
	// 	$upload_dir = FCPATH . 'uploads/member_cards/';
	// 	if (!is_dir($upload_dir)) {
	// 		mkdir($upload_dir, 0755, true);
	// 	}

	// 	// Save the PDF
	// 	$filePath = $upload_dir . 'member_card_' . ($member['member_no'] ?? $id) . '.pdf';
	// 	file_put_contents($filePath, $dompdf->output());

	// 	// Update member record
	// 	$this->db->table('member')->where('id', $id)->update([
	// 		'member_card_issued' => 1,
	// 		'member_card_issue_date' => date('Y-m-d')
	// 	]);

	// 	return $filePath;
	// }
	public function generate_member_card($id)
	{
		// Fetch member data with join
		$member = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type', 'left')
			->select('member.*, member_type.name as type_name')
			->where('member.id', $id)
			->get()->getRowArray();

		if (!$member) {
			log_message('error', 'Member not found with ID: ' . $id);
			return false;
		}

		// Get temple details
		$temple_details = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

		// Create directory if it doesn't exist
		$upload_dir = FCPATH . 'uploads/member_cards/';
		if (!is_dir($upload_dir)) {
			mkdir($upload_dir, 0755, true);
		}

		// Generate the card image
		$imagePath = $this->createMemberCardImage($member, $temple_details, $upload_dir);

		if ($imagePath) {
			// Update member record
			$this->db->table('member')->where('id', $id)->update([
				'member_card_issued' => 1,
				'member_card_issue_date' => date('Y-m-d')
			]);
		}

		return $imagePath;
	}

	private function createMemberCardImage($member, $temple_details, $upload_dir)
	{
		// Card dimensions (credit card size at 300 DPI)
		$width = 1011;  // 85.6mm at 300 DPI
		$height = 638;  // 54mm at 300 DPI

		// Create image
		$card = imagecreatetruecolor($width, $height);

		// Define colors
		$white = imagecolorallocate($card, 255, 255, 255);
		$purple = imagecolorallocate($card, 107, 70, 193);
		$darkPurple = imagecolorallocate($card, 88, 28, 135);
		$black = imagecolorallocate($card, 0, 0, 0);
		$gray = imagecolorallocate($card, 107, 114, 128);
		$lightGray = imagecolorallocate($card, 229, 231, 235);

		// Fill background
		imagefill($card, 0, 0, $white);

		// Draw header gradient (simplified)
		for ($i = 0; $i < 120; $i++) {
			$color = imagecolorallocate(
				$card,
				107 + ($i * 0.3),
				70 - ($i * 0.35),
				193 - ($i * 0.48)
			);
			imagefilledrectangle($card, 0, $i, $width, $i, $color);
		}

		// Font settings (using built-in fonts or TrueType fonts)
		$fontBold = 5; // Built-in font (largest)
		$fontRegular = 4;
		$fontSmall = 3;

		// If you have TrueType fonts available, use them for better quality:
		$ttf_font = FCPATH . 'assets/fonts/arial.ttf'; // Path to your TTF font
		$ttf_font_bold = FCPATH . 'assets/fonts/arialbd.ttf'; // Bold font

		// Header Text
		if (file_exists($ttf_font_bold)) {
			// Using TrueType font
			imagettftext($card, 24, 0, 50, 50, $white, $ttf_font_bold, 'MEMBERSHIP CARD');
			imagettftext($card, 16, 0, 50, 80, $white, $ttf_font, strtoupper($temple_details['name'] ?? 'TEMPLE NAME'));

			// Member ID
			imagettftext($card, 14, 0, $width - 200, 50, $white, $ttf_font, 'ID NO:');
			imagettftext($card, 20, 0, $width - 200, 80, $white, $ttf_font_bold, $member['member_no'] ?? '0000');

			// Member Name
			imagettftext($card, 28, 0, 50, 180, $purple, $ttf_font_bold, strtoupper($member['name'] ?? 'MEMBER NAME'));

			// Member Details
			$y_pos = 230;
			$details = [
				'DOB' => !empty($member['date_of_birth']) ? date('d/m/Y', strtotime($member['date_of_birth'])) : 'N/A',
				'JOINED' => !empty($member['start_date']) ? date('d/m/Y', strtotime($member['start_date'])) : 'N/A',
				'GENDER' => strtoupper($member['gender'] ?? 'N/A'),
				'NATIONALITY' => 'MALAYSIAN',
				'CONTACT' => $member['mobile'] ?? 'N/A'
			];

			foreach ($details as $label => $value) {
				imagettftext($card, 14, 0, 50, $y_pos, $gray, $ttf_font_bold, $label . ':');
				imagettftext($card, 14, 0, 200, $y_pos, $black, $ttf_font, $value);
				$y_pos += 40;
			}

			// Member Type Badge
			$badge_text = $member['member_type'] == 3 ? 'LIFE MEMBER' : 'ORDINARY';
			$badge_x = $width - 350;
			$badge_y = $height - 100;
			imagefilledrectangle($card, $badge_x, $badge_y, $badge_x + 200, $badge_y + 40, $purple);
			imagettftext($card, 14, 0, $badge_x + 20, $badge_y + 28, $white, $ttf_font_bold, $badge_text);

		} else {
			// Fallback to built-in fonts if TTF not available
			imagestring($card, $fontBold, 50, 30, 'MEMBERSHIP CARD', $white);
			imagestring($card, $fontRegular, 50, 60, strtoupper($temple_details['name'] ?? 'TEMPLE NAME'), $white);

			// Member ID
			imagestring($card, $fontSmall, $width - 200, 30, 'ID NO:', $white);
			imagestring($card, $fontBold, $width - 200, 50, $member['member_no'] ?? '0000', $white);

			// Member Name
			imagestring($card, $fontBold, 50, 150, strtoupper($member['name'] ?? 'MEMBER NAME'), $purple);

			// Member Details
			$y_pos = 200;
			imagestring($card, $fontRegular, 50, $y_pos, 'DOB: ' . (!empty($member['date_of_birth']) ? date('d/m/Y', strtotime($member['date_of_birth'])) : 'N/A'), $black);
			$y_pos += 30;
			imagestring($card, $fontRegular, 50, $y_pos, 'JOINED: ' . (!empty($member['start_date']) ? date('d/m/Y', strtotime($member['start_date'])) : 'N/A'), $black);
			$y_pos += 30;
			imagestring($card, $fontRegular, 50, $y_pos, 'GENDER: ' . strtoupper($member['gender'] ?? 'N/A'), $black);
			$y_pos += 30;
			imagestring($card, $fontRegular, 50, $y_pos, 'NATIONALITY: MALAYSIAN', $black);
			$y_pos += 30;
			imagestring($card, $fontRegular, 50, $y_pos, 'CONTACT: ' . ($member['mobile'] ?? 'N/A'), $black);
		}

		// Photo placeholder box
		$photo_x = $width - 320;
		$photo_y = 200;
		$photo_width = 250;
		$photo_height = 300;

		// Draw photo border
		imagefilledrectangle($card, $photo_x - 2, $photo_y - 2, $photo_x + $photo_width + 2, $photo_y + $photo_height + 2, $lightGray);
		imagefilledrectangle($card, $photo_x, $photo_y, $photo_x + $photo_width, $photo_y + $photo_height, $white);

		// Try to load member photo if exists
		if (!empty($member['member_photo'])) {
			$photo_path = FCPATH . 'uploads/member_photos/' . $member['member_photo'];
			if (file_exists($photo_path)) {
				$photo_info = getimagesize($photo_path);
				if ($photo_info !== false) {
					$photo_type = $photo_info[2];

					switch ($photo_type) {
						case IMAGETYPE_JPEG:
							$photo_img = imagecreatefromjpeg($photo_path);
							break;
						case IMAGETYPE_PNG:
							$photo_img = imagecreatefrompng($photo_path);
							break;
						case IMAGETYPE_GIF:
							$photo_img = imagecreatefromgif($photo_path);
							break;
						default:
							$photo_img = null;
					}

					if ($photo_img) {
						// Resize and copy photo to card
						imagecopyresampled(
							$card,
							$photo_img,
							$photo_x,
							$photo_y,
							0,
							0,
							$photo_width,
							$photo_height,
							imagesx($photo_img),
							imagesy($photo_img)
						);
						imagedestroy($photo_img);
					}
				}
			}
		} else {
			// Draw "PHOTO" text if no photo
			if (file_exists($ttf_font)) {
				imagettftext($card, 20, 0, $photo_x + 85, $photo_y + 150, $gray, $ttf_font, 'PHOTO');
			} else {
				imagestring($card, $fontBold, $photo_x + 85, $photo_y + 140, 'PHOTO', $gray);
			}
		}

		// Bottom barcode simulation
		$barcode_y = $height - 50;
		for ($i = 0; $i < 30; $i++) {
			$bar_width = ($i % 3 == 0) ? 4 : 2;
			$bar_x = 50 + ($i * 8);
			imagefilledrectangle($card, $bar_x, $barcode_y, $bar_x + $bar_width, $barcode_y + 20, $black);
		}

		// Save the image
		$filename = 'member_card_' . ($member['member_no'] ?? $id) . '.png';
		$filepath = $upload_dir . $filename;
		imagepng($card, $filepath, 9); // Save as PNG with best compression
		imagedestroy($card);

		return $filepath;
	}

	// Optional: Method to download the card image
	public function download_member_card($id)
	{
		if (!$this->model->permission_validate('member', 'view')) {
			header('Location: ' . base_url() . '/dashboard');
			exit;
		}

		// Get member data
		$member = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type', 'left')
			->select('member.*, member_type.name as type_name')
			->where('member.id', $id)
			->get()->getRowArray();

		if (!$member) {
			$this->session->setFlashdata('fail', 'Member not found');
			header('Location: ' . base_url() . '/member');
			exit;
		}

		// Get temple details
		$temple_details = $this->db->table('admin_profile')
			->where('id', 1)
			->get()
			->getRowArray();

		// Get card settings based on member type
		$memberTypeId = $member['member_type'];
		$cardSettings = $this->db->table('card_configuration')
			->where('member_type_id', $memberTypeId)
			->where('is_active', 1)
			->get()
			->getRowArray();

		// If no specific settings, get default
		if (!$cardSettings) {
			$cardSettings = $this->db->table('card_configuration')
				->where('is_active', 1)
				->groupStart()
				->where('member_type_id IS NULL')
				->orWhere('member_type_id', 0)
				->groupEnd()
				->get()
				->getRowArray();
		}

		// Set defaults if no settings found
		if (!$cardSettings) {
			$cardSettings = [
				'front_color_1' => '#8B0000',
				'front_color_2' => '#3d0000',
				'border_color' => '#D4AF37',
				'temple_name_color' => '#D4AF37',
				'back_color_1' => '#8B7500',
				'back_color_2' => '#4a3f00',
				'back_title_color' => '#1a1a1a',
				'back_text_color' => '#1a1a1a',
				'show_logo' => 1,
				'show_temple_subtitle' => 1,
				'show_temple_address' => 1,
				'show_status_badge' => 1,
				'show_member_type' => 1,
				'show_photo' => 1,
				'show_corner_decorations' => 1,
				'show_om_watermark' => 1,
				'show_member_name' => 1,
				'show_member_no' => 1,
				'show_ic_number' => 1,
				'show_join_date' => 1,
				'show_validity' => 1,
				'show_back_side' => 1,
				'back_title' => 'Terms & Conditions',
				'custom_rules' => "This card remains the property of the temple.\nMembers must present this card during temple events.\nLost or damaged cards must be reported immediately.\nThis card is non-transferable and for personal use only.\nAnnual renewal is required for Ordinary Members.",
				'show_qr_code' => 1,
				'show_contact_info' => 1,
				'show_signature_section' => 1,
				'signature_label' => 'Authorized Signature',
			];
		} else {
			// Map fields
			$cardSettings['show_temple_name'] = $cardSettings['show_temple_subtitle'] ?? 1;
			$cardSettings['back_content'] = $cardSettings['custom_rules'] ?? '';
			$cardSettings['show_signature'] = $cardSettings['show_signature_section'] ?? 1;
			$cardSettings['show_member_type_badge'] = $cardSettings['show_member_type_badge'] ?? $cardSettings['show_member_type'] ?? 1;

			// Set defaults for new columns if null
			$cardSettings['front_color_1'] = $cardSettings['front_color_1'] ?? '#8B0000';
			$cardSettings['front_color_2'] = $cardSettings['front_color_2'] ?? '#3d0000';
			$cardSettings['border_color'] = $cardSettings['border_color'] ?? '#D4AF37';
			$cardSettings['back_color_1'] = $cardSettings['back_color_1'] ?? '#8B7500';
			$cardSettings['back_color_2'] = $cardSettings['back_color_2'] ?? '#4a3f00';
			$cardSettings['back_title_color'] = $cardSettings['back_title_color'] ?? '#1a1a1a';
			$cardSettings['show_status_badge'] = $cardSettings['show_status_badge'] ?? 1;
			$cardSettings['show_corner_decorations'] = $cardSettings['show_corner_decorations'] ?? 1;
			$cardSettings['show_om_watermark'] = $cardSettings['show_om_watermark'] ?? 1;
		}

		// Prepare data for view
		$data = [
			'member' => $member,
			'temple_details' => $temple_details,
			'settings' => $cardSettings,
		];

		// Generate HTML
		$html = view('member/member_card_pdf', $data);

		// Load DomPDF
		$options = new \Dompdf\Options();
		$options->set('isRemoteEnabled', true);
		$options->set('isHtml5ParserEnabled', true);
		$options->set('defaultFont', 'DejaVu Sans');

		$dompdf = new \Dompdf\Dompdf($options);
		$dompdf->loadHtml($html);

		// Set paper size to A4 Portrait
		$dompdf->setPaper('A4', 'portrait');

		// Render PDF
		$dompdf->render();

		// Generate filename
		$filename = 'Member_Card_' . preg_replace('/[^A-Za-z0-9]/', '_', $member['name']) . '_' . ($member['member_no'] ?? 'N_A') . '.pdf';

		// Output PDF for download
		$dompdf->stream($filename, ['Attachment' => true]);
		exit;
	}

	/**
	 * Preview Member Card PDF in browser (without downloading)
	 */
	public function preview_member_card_pdf($id)
	{
		if (!$this->model->permission_validate('member', 'view')) {
			header('Location: ' . base_url() . '/dashboard');
			exit;
		}

		// Get member data
		$member = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type', 'left')
			->select('member.*, member_type.name as type_name')
			->where('member.id', $id)
			->get()->getRowArray();

		if (!$member) {
			$this->session->setFlashdata('fail', 'Member not found');
			header('Location: ' . base_url() . '/member');
			exit;
		}

		// Get temple details
		$temple_details = $this->db->table('admin_profile')
			->where('id', 1)
			->get()
			->getRowArray();

		// Get card settings
		$memberTypeId = $member['member_type'];
		$cardSettings = $this->db->table('card_configuration')
			->where('member_type_id', $memberTypeId)
			->where('is_active', 1)
			->get()
			->getRowArray();

		if (!$cardSettings) {
			$cardSettings = $this->db->table('card_configuration')
				->where('is_active', 1)
				->groupStart()
				->where('member_type_id IS NULL')
				->orWhere('member_type_id', 0)
				->groupEnd()
				->get()
				->getRowArray();
		}

		if (!$cardSettings) {
			$cardSettings = [
				'front_color_1' => '#8B0000',
				'front_color_2' => '#3d0000',
				'border_color' => '#D4AF37',
				'temple_name_color' => '#D4AF37',
				'back_color_1' => '#8B7500',
				'back_color_2' => '#4a3f00',
				'back_title_color' => '#1a1a1a',
				'back_text_color' => '#1a1a1a',
				'show_logo' => 1,
				'show_temple_subtitle' => 1,
				'show_temple_address' => 1,
				'show_status_badge' => 1,
				'show_member_type' => 1,
				'show_photo' => 1,
				'show_corner_decorations' => 1,
				'show_om_watermark' => 1,
				'show_member_name' => 1,
				'show_member_no' => 1,
				'show_ic_number' => 1,
				'show_join_date' => 1,
				'show_validity' => 1,
				'show_back_side' => 1,
				'back_title' => 'Terms & Conditions',
				'custom_rules' => "This card remains the property of the temple.\nMembers must present this card during temple events.\nLost or damaged cards must be reported immediately.\nThis card is non-transferable and for personal use only.\nAnnual renewal is required for Ordinary Members.",
				'show_qr_code' => 1,
				'show_contact_info' => 1,
				'show_signature_section' => 1,
				'signature_label' => 'Authorized Signature',
			];
		} else {
			$cardSettings['show_temple_name'] = $cardSettings['show_temple_subtitle'] ?? 1;
			$cardSettings['back_content'] = $cardSettings['custom_rules'] ?? '';
			$cardSettings['show_signature'] = $cardSettings['show_signature_section'] ?? 1;
			$cardSettings['show_member_type_badge'] = $cardSettings['show_member_type_badge'] ?? $cardSettings['show_member_type'] ?? 1;
			$cardSettings['front_color_1'] = $cardSettings['front_color_1'] ?? '#8B0000';
			$cardSettings['front_color_2'] = $cardSettings['front_color_2'] ?? '#3d0000';
			$cardSettings['border_color'] = $cardSettings['border_color'] ?? '#D4AF37';
			$cardSettings['back_color_1'] = $cardSettings['back_color_1'] ?? '#8B7500';
			$cardSettings['back_color_2'] = $cardSettings['back_color_2'] ?? '#4a3f00';
			$cardSettings['back_title_color'] = $cardSettings['back_title_color'] ?? '#1a1a1a';
			$cardSettings['show_status_badge'] = $cardSettings['show_status_badge'] ?? 1;
			$cardSettings['show_corner_decorations'] = $cardSettings['show_corner_decorations'] ?? 1;
			$cardSettings['show_om_watermark'] = $cardSettings['show_om_watermark'] ?? 1;
		}

		$data = [
			'member' => $member,
			'temple_details' => $temple_details,
			'settings' => $cardSettings,
		];

		$html = view('member/member_card_pdf', $data);

		$options = new \Dompdf\Options();
		$options->set('isRemoteEnabled', true);
		$options->set('isHtml5ParserEnabled', true);
		$options->set('defaultFont', 'DejaVu Sans');

		$dompdf = new \Dompdf\Dompdf($options);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		// Output PDF in browser (inline, not download)
		$dompdf->stream('Member_Card_Preview.pdf', ['Attachment' => false]);
		exit;
	}





	public function view_member_card($id)
	{
		if (!$this->model->permission_validate('member', 'view')) {
			header('Location: ' . base_url() . '/dashboard');
			exit;
		}

		$member = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type', 'left')
			->select('member.*, member_type.name as type_name')
			->where('member.id', $id)
			->get()->getRowArray();

		if (!$member) {
			$this->session->setFlashdata('fail', 'Member not found');
			header('Location: ' . base_url() . '/member');
			exit;
		}

		$data['member'] = $member;
		$data['temple_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

		// Get card settings
		$memberTypeId = $member['member_type'];
		$cardSettings = $this->db->table('card_configuration')
			->where('member_type_id', $memberTypeId)
			->where('is_active', 1)
			->get()
			->getRowArray();

		if (!$cardSettings) {
			$cardSettings = $this->db->table('card_configuration')
				->where('is_active', 1)
				->groupStart()
				->where('member_type_id IS NULL')
				->orWhere('member_type_id', 0)
				->groupEnd()
				->get()
				->getRowArray();
		}

		if (!$cardSettings) {
			$cardSettings = $this->getDefaultCardSettings();
		} else {
			$cardSettings['show_temple_name'] = $cardSettings['show_temple_subtitle'] ?? 1;
			$cardSettings['back_content'] = $cardSettings['custom_rules'] ?? '';
			$cardSettings['show_signature'] = $cardSettings['show_signature_section'] ?? 1;
			$cardSettings['front_color_1'] = $cardSettings['front_color_1'] ?? '#8B0000';
			$cardSettings['front_color_2'] = $cardSettings['front_color_2'] ?? '#3d0000';
			$cardSettings['border_color'] = $cardSettings['border_color'] ?? '#D4AF37';
			$cardSettings['back_color_1'] = $cardSettings['back_color_1'] ?? '#8B7500';
			$cardSettings['back_color_2'] = $cardSettings['back_color_2'] ?? '#4a3f00';
			$cardSettings['back_title_color'] = $cardSettings['back_title_color'] ?? '#1a1a1a';
			$cardSettings['show_status_badge'] = $cardSettings['show_status_badge'] ?? 1;
			$cardSettings['show_member_type_badge'] = $cardSettings['show_member_type_badge'] ?? 1;
			$cardSettings['show_corner_decorations'] = $cardSettings['show_corner_decorations'] ?? 1;
			$cardSettings['show_om_watermark'] = $cardSettings['show_om_watermark'] ?? 1;
		}

		$data['settings'] = $cardSettings;

		echo view('member/member_card', $data);
	}

	// Preview card functionality
	// public function preview_card($member_id = null)
	// {
	// 	// Use sample data if no member specified
	// 	if (!$member_id) {
	// 		$member = [
	// 			'id' => 0,
	// 			'member_no' => 'OM2024001',
	// 			'name' => 'SAMPLE MEMBER',
	// 			'ic_no' => '123456-78-9012',
	// 			'start_date' => date('Y-m-d'),
	// 			'end_date' => date('Y-m-d', strtotime('+1 year')),
	// 			'member_type' => 1,
	// 			'type_name' => 'Ordinary Member',
	// 			'member_photo' => ''
	// 		];
	// 	} else {
	// 		$member = $this->db->table('member')
	// 			->join('member_type', 'member_type.id = member.member_type')
	// 			->select('member.*, member_type.name as type_name')
	// 			->where('member.id', $member_id)
	// 			->get()->getRowArray();
	// 	}

	// 	$card_config = $this->db->table('card_configuration')->where('id', 1)->get()->getRowArray();
	// 	$temple_details = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

	// 	$data['member'] = $member;
	// 	$data['card_config'] = $card_config;
	// 	$data['temple_details'] = $temple_details;
	// 	$data['qr_code'] = '';

	// 	echo view('member/card_preview', $data);
	// }

	// Bulk card generation
	public function bulk_generate_cards()
	{
		if (!$this->model->permission_validate('member', 'edit')) {
			echo json_encode(['success' => false, 'message' => 'Access denied']);
			return;
		}

		$member_type = $this->request->getPost('member_type');
		$status_filter = $this->request->getPost('status_filter');

		$query = $this->db->table('member')
			->where('approval_status', 1)
			->where('member_card_issued', 0);

		if ($member_type && $member_type !== 'all') {
			$query->where('member_type', $member_type);
		}

		if ($status_filter && $status_filter !== 'all') {
			$query->where('status', $status_filter);
		}

		$members = $query->get()->getResultArray();

		$generated_count = 0;
		$failed_count = 0;

		foreach ($members as $member) {
			if ($this->generate_member_card($member['id'])) {
				$generated_count++;
			} else {
				$failed_count++;
			}
		}

		echo json_encode([
			'success' => true,
			'generated_count' => $generated_count,
			'failed_count' => $failed_count,
			'total_processed' => count($members)
		]);
	}

	// Member photo upload and resize
	public function upload_member_photo()
	{
		$member_id = $this->request->getPost('member_id');

		if (!$member_id) {
			echo json_encode(['success' => false, 'message' => 'Member ID is required']);
			return;
		}

		$photo = $this->request->getFile('member_photo');

		if ($photo->isValid() && !$photo->hasMoved()) {
			// Validate file type
			$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
			if (!in_array($photo->getMimeType(), $allowedTypes)) {
				echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.']);
				return;
			}

			// Validate file size (max 5MB)
			if ($photo->getSize() > 5 * 1024 * 1024) {
				echo json_encode(['success' => false, 'message' => 'File size too large. Maximum 5MB allowed.']);
				return;
			}

			$newName = 'member_' . $member_id . '_' . time() . '.' . $photo->getExtension();

			// Create directory if it doesn't exist
			if (!is_dir(FCPATH . 'uploads/member_photos/')) {
				mkdir(FCPATH . 'uploads/member_photos/', 0755, true);
			}

			if ($photo->move('uploads/member_photos/', $newName)) {
				// Resize image
				$this->resize_member_photo('uploads/member_photos/' . $newName, 300, 400);

				// Update member record
				$this->db->table('member')->where('id', $member_id)->update([
					'member_photo' => $newName,
					'modified' => date('Y-m-d H:i:s')
				]);

				echo json_encode([
					'success' => true,
					'message' => 'Photo uploaded successfully',
					'photo_url' => base_url() . '/uploads/member_photos/' . $newName
				]);
			} else {
				echo json_encode(['success' => false, 'message' => 'Failed to upload photo']);
			}
		} else {
			echo json_encode(['success' => false, 'message' => 'Invalid file upload']);
		}
	}

	private function resize_member_photo($file_path, $max_width = 300, $max_height = 400)
	{
		$image_info = getimagesize($file_path);
		$mime_type = $image_info['mime'];

		switch ($mime_type) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg($file_path);
				break;
			case 'image/png':
				$image = imagecreatefrompng($file_path);
				break;
			case 'image/gif':
				$image = imagecreatefromgif($file_path);
				break;
			default:
				return false;
		}

		$original_width = imagesx($image);
		$original_height = imagesy($image);

		// Calculate new dimensions
		$ratio = min($max_width / $original_width, $max_height / $original_height);
		$new_width = $original_width * $ratio;
		$new_height = $original_height * $ratio;

		// Create new image
		$new_image = imagecreatetruecolor($new_width, $new_height);

		// Preserve transparency for PNG and GIF
		if ($mime_type == 'image/png' || $mime_type == 'image/gif') {
			imagealphablending($new_image, false);
			imagesavealpha($new_image, true);
		}

		imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

		// Save resized image
		switch ($mime_type) {
			case 'image/jpeg':
				imagejpeg($new_image, $file_path, 90);
				break;
			case 'image/png':
				imagepng($new_image, $file_path, 9);
				break;
			case 'image/gif':
				imagegif($new_image, $file_path);
				break;
		}

		imagedestroy($image);
		imagedestroy($new_image);

		return true;
	}


	public function get_card_preview($member_id)
	{
		if (!$this->model->permission_validate('member', 'view')) {
			echo '<div class="alert alert-danger">Permission denied</div>';
			return;
		}

		$member = $this->db->table('member')
			->join('member_type', 'member_type.id = member.member_type', 'left')
			->select('member.*, member_type.name as type_name')
			->where('member.id', $member_id)
			->get()->getRowArray();

		if (!$member) {
			echo '<div class="alert alert-danger">Member not found</div>';
			return;
		}

		$data['member'] = $member;
		$data['temple_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

		// Get card settings based on member type
		$memberTypeId = $member['member_type'];
		$cardSettings = $this->db->table('card_configuration')
			->where('member_type_id', $memberTypeId)
			->where('is_active', 1)
			->get()
			->getRowArray();

		// If no specific settings, get default
		if (!$cardSettings) {
			$cardSettings = $this->db->table('card_configuration')
				->where('is_active', 1)
				->groupStart()
				->where('member_type_id IS NULL')
				->orWhere('member_type_id', 0)
				->groupEnd()
				->get()
				->getRowArray();
		}

		// Set defaults if still no settings
		if (!$cardSettings) {
			$cardSettings = $this->getDefaultCardSettings();
		} else {
			// Map fields and set defaults
			$cardSettings['show_temple_name'] = $cardSettings['show_temple_subtitle'] ?? 1;
			$cardSettings['back_content'] = $cardSettings['custom_rules'] ?? '';
			$cardSettings['show_signature'] = $cardSettings['show_signature_section'] ?? 1;
			$cardSettings['front_color_1'] = $cardSettings['front_color_1'] ?? '#8B0000';
			$cardSettings['front_color_2'] = $cardSettings['front_color_2'] ?? '#3d0000';
			$cardSettings['border_color'] = $cardSettings['border_color'] ?? '#D4AF37';
			$cardSettings['back_color_1'] = $cardSettings['back_color_1'] ?? '#8B7500';
			$cardSettings['back_color_2'] = $cardSettings['back_color_2'] ?? '#4a3f00';
			$cardSettings['back_title_color'] = $cardSettings['back_title_color'] ?? '#1a1a1a';
			$cardSettings['show_status_badge'] = $cardSettings['show_status_badge'] ?? 1;
			$cardSettings['show_member_type_badge'] = $cardSettings['show_member_type_badge'] ?? 1;
			$cardSettings['show_corner_decorations'] = $cardSettings['show_corner_decorations'] ?? 1;
			$cardSettings['show_om_watermark'] = $cardSettings['show_om_watermark'] ?? 1;
		}

		$data['settings'] = $cardSettings;

		echo view('member/member_card_modal', $data);
	}

	private function renderCardPreview($member, $card_config, $temple_details)
	{
		$html = '<div style="background: #f0f0f0; padding: 20px; border-radius: 5px;">';

		// Front Side
		$html .= '<div style="width: 85.6mm; height: 54mm; background: white; margin: 0 auto 20px; border: 1px solid #000; border-radius: 5px; padding: 5mm; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">';
		$html .= '<div style="text-align: center; margin-bottom: 3mm;">';
		$html .= '<h4 style="margin: 0; font-size: ' . ($card_config['temple_name_font_size'] ?? '12pt') . ';">' . strtoupper($temple_details['name'] ?? 'TEMPLE NAME') . '</h4>';
		$html .= '<small>' . ($temple_details['address1'] ?? 'Temple Address') . '</small>';
		$html .= '</div>';
		$html .= '<hr style="margin: 2mm 0;">';
		$html .= '<div style="display: flex; justify-content: space-between;">';

		// Details
		$html .= '<div style="font-size: 8pt;">';
		if ($card_config['show_member_name'] ?? 1) {
			$html .= '<p style="margin: 2mm 0;"><strong>Name:</strong> ' . strtoupper($member['name']) . '</p>';
		}
		if ($card_config['show_member_no'] ?? 1) {
			$html .= '<p style="margin: 2mm 0;"><strong>Member No:</strong> ' . ($member['member_no'] ?? 'PENDING') . '</p>';
		}
		if ($card_config['show_ic_number'] ?? 1) {
			$html .= '<p style="margin: 2mm 0;"><strong>IC No:</strong> ' . $member['ic_no'] . '</p>';
		}
		if ($card_config['show_member_type'] ?? 1) {
			$html .= '<p style="margin: 2mm 0;"><strong>Type:</strong> ' . $member['type_name'] . '</p>';
		}
		if ($card_config['show_join_date'] ?? 1) {
			$html .= '<p style="margin: 2mm 0;"><strong>Joined:</strong> ' . date('d/m/Y', strtotime($member['start_date'])) . '</p>';
		}
		if (($card_config['show_validity'] ?? 1) && $member['member_type'] != 3) {
			$html .= '<p style="margin: 2mm 0;"><strong>Valid Till:</strong> ' . date('d/m/Y', strtotime($member['end_date'])) . '</p>';
		}
		$html .= '</div>';

		// Photo
		if ($card_config['show_photo'] ?? 1) {
			$html .= '<div style="width: 18mm; height: 24mm; border: 1px solid #999; display: flex; align-items: center; justify-content: center; background: #f0f0f0;">';
			if (!empty($member['member_photo'])) {
				$html .= '<img src="' . base_url() . '/uploads/member_photos/' . $member['member_photo'] . '" style="width: 100%; height: 100%; object-fit: cover;">';
			} else {
				$html .= 'PHOTO';
			}
			$html .= '</div>';
		}

		$html .= '</div>';
		$html .= '</div>';

		// Back Side
		if ($card_config['show_back_side'] ?? 1) {
			$html .= '<div style="width: 85.6mm; height: 54mm; background: white; margin: 0 auto; border: 1px solid #000; border-radius: 5px; padding: 5mm; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">';
			$html .= '<h4 style="text-align: center; margin: 0 0 5mm 0;">' . ($card_config['back_title'] ?? 'Terms & Conditions') . '</h4>';
			$html .= '<div style="font-size: 7pt; line-height: 1.4;">';
			if (!empty($card_config['custom_rules'])) {
				$html .= nl2br($card_config['custom_rules']);
			} else {
				$html .= '1. This card is the property of the temple.<br>';
				$html .= '2. Must be carried during temple visits.<br>';
				$html .= '3. Lost cards should be reported immediately.<br>';
				$html .= '4. Annual renewal required for ordinary members.<br>';
				$html .= '5. Non-transferable.';
			}
			$html .= '</div>';
			$html .= '</div>';
		}

		$html .= '</div>';

		return $html;
	}
	/**
	 * Send WhatsApp notification for member registration
	 */
	private function send_member_registration_whatsapp($member_id)
	{
		try {
			// Get member details
			$member = $this->db->table('member')
				->join('member_type', 'member_type.id = member.member_type', 'left')
				->select('member.*, member_type.name as type_name')
				->where('member.id', $member_id)
				->get()->getRowArray();

			if (empty($member)) {
				log_message('error', 'Member not found for WhatsApp notification: ' . $member_id);
				return false;
			}

			// Check if mobile number exists
			if (empty($member['mobile']) && empty($member['tel_phone_mobile'])) {
				log_message('warning', 'No mobile number for member: ' . $member_id);
				return false;
			}

			// Use tel_phone_mobile if available, otherwise use mobile
			$mobile = !empty($member['tel_phone_mobile']) ? $member['tel_phone_mobile'] : $member['mobile'];

			// Ensure number format is correct (remove spaces, add country code if needed)
			$mobile = preg_replace('/\s+/', '', $mobile);
			if (!preg_match('/^\+/', $mobile) && !preg_match('/^60/', $mobile)) {
				// Add Malaysia country code if not present
				$mobile = '60' . ltrim($mobile, '0');
			}

			// Generate registration receipt PDF
			$pdf_path = $this->generate_registration_receipt_pdf($member_id);

			if (!$pdf_path || !file_exists($pdf_path)) {
				log_message('error', 'Failed to generate PDF for member: ' . $member_id);
				// Still send message without attachment
				$media = [];
			} else {
				// Prepare media attachment
				$media = [
					'url' => base_url() . '/' . str_replace(FCPATH, '', $pdf_path),
					'filename' => 'Member_Registration_' . ($member['member_no'] ?? $member_id) . '.pdf'
				];
			}

			// Prepare template parameters
			$params = [
				':devotee' => $member['name'],
				':ref_no' => 'REG-' . str_pad($member_id, 6, '0', STR_PAD_LEFT),
				':registration_date' => date('d/m/Y', strtotime($member['created'])),
				':full_name' => ($member['prefix'] ?? '') . ' ' . $member['name'],
				':ic_no' => $member['ic_no'],
				':mobile' => $member['mobile'] ?? $member['tel_phone_mobile'],
				':email' => $member['email_address'] ?? 'Not provided',
				':member_type' => $member['type_name'],
				':amount' => number_format($member['payment'], 2),
				':payment_mode' => $this->get_payment_mode_name($member['payment_mode'])
			];

			// Send WhatsApp message
			$result = whatsapp_ultramsg(
				[$mobile],
				'member_registration_confirmation',
				$params,
				$media
			);

			// Log result
			if (isset($result['sent']) && $result['sent'] === true) {
				log_message('info', 'WhatsApp sent successfully to member: ' . $member_id);

				// Update member record with WhatsApp status
				$this->db->table('member')->where('id', $member_id)->update([
					'whatsapp_sent' => 1,
					'whatsapp_sent_date' => date('Y-m-d H:i:s')
				]);

				return true;
			} else {
				log_message('error', 'WhatsApp failed for member: ' . $member_id . ' - ' . json_encode($result));
				return false;
			}

		} catch (\Exception $e) {
			log_message('error', 'WhatsApp exception for member ' . $member_id . ': ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Send WhatsApp notification after approval
	 */
	private function send_member_approval_whatsapp($member_id)
	{
		try {
			$member = $this->db->table('member')
				->join('member_type', 'member_type.id = member.member_type', 'left')
				->select('member.*, member_type.name as type_name')
				->where('member.id', $member_id)
				->get()->getRowArray();

			if (empty($member['mobile']) && empty($member['tel_phone_mobile'])) {
				return false;
			}

			$mobile = !empty($member['tel_phone_mobile']) ? $member['tel_phone_mobile'] : $member['mobile'];
			$mobile = preg_replace('/\s+/', '', $mobile);
			if (!preg_match('/^\+/', $mobile) && !preg_match('/^60/', $mobile)) {
				$mobile = '60' . ltrim($mobile, '0');
			}

			// Generate member card PDF
			$card_path = $this->generate_member_card($member_id);

			$media = [];
			if ($card_path && file_exists($card_path)) {
				$media = [
					'url' => base_url() . '/' . str_replace(FCPATH, '', $card_path),
					'filename' => 'Member_Card_' . $member['member_no'] . '.pdf'
				];
			}

			$params = [
				':devotee' => $member['name'],
				':member_no' => $member['member_no'],
				':member_type' => $member['type_name'],
				':join_date' => date('d/m/Y', strtotime($member['start_date'])),
				':valid_until' => !empty($member['end_date']) ? date('d/m/Y', strtotime($member['end_date'])) : 'Lifetime'
			];

			$result = whatsapp_ultramsg(
				[$mobile],
				'member_approval_notification',
				$params,
				$media
			);

			return isset($result['sent']) && $result['sent'] === true;

		} catch (\Exception $e) {
			log_message('error', 'Approval WhatsApp exception: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Generate registration receipt PDF
	 */
	private function generate_registration_receipt_pdf($member_id)
	{
		try {
			$member = $this->db->table('member')
				->join('member_type', 'member_type.id = member.member_type', 'left')
				->select('member.*, member_type.name as type_name')
				->where('member.id', $member_id)
				->get()->getRowArray();

			$data['member'] = $member;
			$data['temple_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

			// Generate HTML for PDF
			$html = view('member/registration_receipt_pdf', $data);

			// Configure DomPDF
			$options = new \Dompdf\Options();
			$options->set('isHtml5ParserEnabled', true);
			$options->set('isRemoteEnabled', true);
			$options->set('isPhpEnabled', true);

			$dompdf = new \Dompdf\Dompdf($options);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();

			// Save PDF
			$upload_dir = FCPATH . 'uploads/member_receipts/';
			if (!is_dir($upload_dir)) {
				mkdir($upload_dir, 0755, true);
			}

			$filename = 'registration_receipt_' . $member_id . '_' . time() . '.pdf';
			$filepath = $upload_dir . $filename;

			file_put_contents($filepath, $dompdf->output());

			return $filepath;

		} catch (\Exception $e) {
			log_message('error', 'PDF generation failed: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Helper to get payment mode name
	 */
	private function get_payment_mode_name($payment_mode_id)
	{
		if (empty($payment_mode_id)) {
			return 'N/A';
		}

		$payment_mode = $this->db->table('payment_mode')
			->where('id', $payment_mode_id)
			->get()->getRowArray();

		return $payment_mode['name'] ?? 'N/A';
	}

// ============================================
// ADD THESE METHODS TO YOUR Member.php CONTROLLER
// ============================================

/**
 * Draft List - Shows all draft member applications
 */
public function drafts()
{
    if (!$this->model->list_validate('member')) {
        header('Location: ' . base_url() . '/dashboard');
        exit;
    }

    $data['permission'] = $this->model->get_permission('member');

    // Get draft member applications (approval_status = -1)
    $qry = $this->db->table('member')
        ->join('member_type', 'member_type.id = member.member_type', 'left')
        ->select('member.*, member_type.name as tname')
        ->where('member.approval_status', -1) // Draft status
        ->orderBy('member.modified', 'DESC'); // Latest modified first

    $data['list'] = $qry->get()->getResultArray();

    echo view('template/header');
    echo view('template/sidebar');
    echo view('member/drafts', $data);
    echo view('template/footer');
}

/**
 * Save as Draft - Saves member without submitting for approval
 */
public function save_draft()
{
    $id = $this->request->getPost('id');
    
    // Collect all form data
    $data = $this->collect_member_form_data();
    
    // Set draft status
    $data['approval_status'] = -1; // Draft
    $data['status'] = 'draft';
    
    // IP tracking
    $data['ip'] = $this->getClientIP();
    $data['ip_location'] = 'Manual Entry';
    $data['ip_details'] = json_encode(['source' => 'web_form', 'timestamp' => date('Y-m-d H:i:s')]);
    
    // System fields
    $data['added_by'] = $this->session->get('log_id');
    
    if (empty($id)) {
        // NEW DRAFT
        $data['created'] = date('Y-m-d H:i:s');
        $data['modified'] = date('Y-m-d H:i:s');
        
        $res = $this->db->table('member')->insert($data);
        
        if ($res) {
            $member_id = $this->db->insertID();
            
            // Save documents if any
            $this->save_member_documents($member_id);
            
            $this->session->setFlashdata('succ', 'Draft saved successfully. You can continue editing later.');
            return redirect()->to('/member/drafts');
        } else {
            $this->session->setFlashdata('fail', 'Failed to save draft. Please try again.');
            return redirect()->to('/member/add');
        }
    } else {
        // UPDATE EXISTING DRAFT
        $data['modified'] = date('Y-m-d H:i:s');
        
        $res = $this->db->table('member')->where('id', $id)->update($data);
        
        if ($res) {
            // Save documents if any
            $this->save_member_documents($id);
            
            $this->session->setFlashdata('succ', 'Draft updated successfully.');
            return redirect()->to('/member/drafts');
        } else {
            $this->session->setFlashdata('fail', 'Failed to update draft. Please try again.');
            return redirect()->to('/member/edit/' . $id);
        }
    }
}

/**
 * Submit Draft - Moves draft to pending approval
 */
public function submit_draft()
{
    $id = $this->request->getPost('member_id');
    
    if (empty($id)) {
        $this->session->setFlashdata('fail', 'Invalid member ID');
        return redirect()->to('/member/drafts');
    }
    
    // Get member data
    $member = $this->db->table('member')->where('id', $id)->get()->getRowArray();
    
    if (!$member) {
        $this->session->setFlashdata('fail', 'Member not found');
        return redirect()->to('/member/drafts');
    }
    
    // Validate required fields before submission
    $errors = $this->validate_member_for_submission($member);
    
    if (!empty($errors)) {
        $this->session->setFlashdata('fail', 'Cannot submit: ' . implode(', ', $errors));
        return redirect()->to('/member/edit/' . $id);
    }
    
    // Update to pending approval status
    $update_data = [
        'approval_status' => 0, // Pending
        'status' => 'inactive', // Inactive until approved
        'modified' => date('Y-m-d H:i:s'),
        'submitted_date' => date('Y-m-d H:i:s'),
        'submitted_by' => $this->session->get('log_id')
    ];
    
    $res = $this->db->table('member')->where('id', $id)->update($update_data);
    
    if ($res) {
        // Send WhatsApp notification
        if (method_exists($this, 'send_member_registration_whatsapp')) {
            $this->send_member_registration_whatsapp($id);
        }
        
        // Notify admin
        $this->notify_admin_new_application($id);
        
        $this->session->setFlashdata('succ', 'Application submitted successfully. Pending approval.');
        
        // Redirect to print registration
        $this->session->setFlashdata('auto_print', true);
        return redirect()->to('/member/print_registration/' . $id);
    } else {
        $this->session->setFlashdata('fail', 'Failed to submit application. Please try again.');
        return redirect()->to('/member/drafts');
    }
}

/**
 * Delete Draft
 */
public function delete_draft()
{
    if (!$this->model->permission_validate('member', 'delete_p')) {
        $this->session->setFlashdata('fail', 'Permission denied');
        return redirect()->to('/member/drafts');
    }
    
    $id = $this->request->getPost('member_id');
    
    if (empty($id)) {
        $this->session->setFlashdata('fail', 'Invalid member ID');
        return redirect()->to('/member/drafts');
    }
    
    // Check if it's actually a draft
    $member = $this->db->table('member')->where('id', $id)->get()->getRowArray();
    
    if (!$member || $member['approval_status'] != -1) {
        $this->session->setFlashdata('fail', 'Can only delete draft applications');
        return redirect()->to('/member/drafts');
    }
    
    // Delete associated documents first
    $this->db->table('member_documents')->where('member_id', $id)->delete();
    
    // Delete the draft
    $res = $this->db->table('member')->where('id', $id)->delete();
    
    if ($res) {
        $this->session->setFlashdata('succ', 'Draft deleted successfully');
    } else {
        $this->session->setFlashdata('fail', 'Failed to delete draft');
    }
    
    return redirect()->to('/member/drafts');
}

/**
 * Validate member data before submission
 */
private function validate_member_for_submission($member)
{
    $errors = [];
    
    // Required fields validation
    if (empty($member['name']) && empty($member['first_name'])) {
        $errors[] = 'Name is required';
    }
    
    if (empty($member['ic_no'])) {
        $errors[] = 'IC Number is required';
    }
    
    if (empty($member['member_type'])) {
        $errors[] = 'Member Type is required';
    }
    
    if (empty($member['mobile']) && empty($member['tel_phone_mobile'])) {
        $errors[] = 'Mobile number is required';
    }
    
    if (empty($member['payment']) || $member['payment'] <= 0) {
        $errors[] = 'Payment amount is required';
    }
    
    if (empty($member['start_date'])) {
        $errors[] = 'Start date is required';
    }
    
    return $errors;
}

/**
 * Helper method to collect all form data
 */
private function collect_member_form_data()
{
    $data = [];
    
    // Membership Information
    $data['member_type'] = trim($this->request->getPost('member_type') ?? '');
    $data['old_membership_no'] = $this->request->getPost('old_membership_no') ? trim($this->request->getPost('old_membership_no')) : null;
    $data['membership_status'] = $this->request->getPost('membership_status') ?? null;
    $data['membership_code'] = $this->request->getPost('membership_code') ? trim($this->request->getPost('membership_code')) : null;

    // Personal Information - Handle prefix
    if ($this->request->getPost('prefix_ordered')) {
        $data['prefix'] = trim($this->request->getPost('prefix_ordered'));
    } elseif ($this->request->getPost('prefix') && is_array($this->request->getPost('prefix'))) {
        $data['prefix'] = implode(', ', array_filter($this->request->getPost('prefix')));
    } else {
        $data['prefix'] = null;
    }
    
    $data['first_name'] = $this->request->getPost('first_name') ? trim($this->request->getPost('first_name')) : null;
    $data['last_name'] = $this->request->getPost('last_name') ? trim($this->request->getPost('last_name')) : null;
    $data['name'] = trim($this->request->getPost('name') ?? '');
    $data['titles'] = $this->request->getPost('titles') ? trim($this->request->getPost('titles')) : null;
    $data['ic_no'] = trim($this->request->getPost('ic_number') ?? '');
    $data['date_of_birth'] = $this->request->getPost('date_of_birth') ?? null;
    $data['gender'] = $this->request->getPost('gender') ?? null;
    $data['marital_status'] = $this->request->getPost('marital_status') ?? null;

    // Contact Information
    $data['tel_phone_house'] = $this->request->getPost('tel_phone_house') ? trim($this->request->getPost('tel_phone_house')) : null;
    
    // Handle mobile number
    if (empty($this->request->getPost('edit_status'))) {
        $mble_phonecode = $this->request->getPost('phonecode') ?? '';
        $mble_number = $this->request->getPost('mobile') ?? '';
        $data['mobile'] = $mble_phonecode . $mble_number;
    } else {
        $data['mobile'] = $this->request->getPost('mobile') ?? '';
    }
    $data['tel_phone_mobile'] = $data['mobile'];
    
    $data['email_address'] = $this->request->getPost('email_address') ?? null;
    $data['mailing_preference'] = $this->request->getPost('mailing_preference') ?? 'email';

    // Address Information
    $data['house_no_street'] = $this->request->getPost('house_no_street') ? trim($this->request->getPost('house_no_street')) : null;
    $data['district'] = $this->request->getPost('district') ? trim($this->request->getPost('district')) : null;
    $data['postal_code'] = $this->request->getPost('postal_code') ? trim($this->request->getPost('postal_code')) : null;
    $data['locality'] = $this->request->getPost('locality') ? trim($this->request->getPost('locality')) : null;
    $data['state'] = $this->request->getPost('state') ? trim($this->request->getPost('state')) : null;
    $data['country'] = $this->request->getPost('country') ? trim($this->request->getPost('country')) : 'Malaysia';
    $data['address'] = $this->request->getPost('address') ? trim($this->request->getPost('address')) : null;
    $data['office_address'] = $this->request->getPost('office_address') ?? null;
    $data['home_address'] = $this->request->getPost('home_address') ?? null;

    // Professional & Religious Information
    $data['occupation'] = $this->request->getPost('occupation') ?? null;
    $data['company'] = $this->request->getPost('company') ?? null;
    $data['rasi'] = $this->request->getPost('rasi') ? trim($this->request->getPost('rasi')) : null;
    $data['natchathram'] = $this->request->getPost('natchathram') ? trim($this->request->getPost('natchathram')) : null;

    // Next of Kin Information
    $data['next_of_kin_name'] = $this->request->getPost('next_of_kin_name') ? trim($this->request->getPost('next_of_kin_name')) : null;
    $data['next_of_kin_contact'] = $this->request->getPost('next_of_kin_contact') ? trim($this->request->getPost('next_of_kin_contact')) : '';
    $data['next_of_kin_relationship'] = $this->request->getPost('next_of_kin_relationship') ? trim($this->request->getPost('next_of_kin_relationship')) : null;

    // Membership Dates
    $data['joining_date'] = $this->request->getPost('start_date') ? trim($this->request->getPost('start_date')) : null;
    $data['start_date'] = $this->request->getPost('start_date') ? trim($this->request->getPost('start_date')) : null;
    
    if ($this->request->getPost('member_type') === '3') { // Life member
        $data['end_date'] = null;
    } else {
        $data['end_date'] = $this->request->getPost('end_date') ? date('Y-m-d', strtotime(trim($this->request->getPost('end_date')))) : null;
    }

    // Payment Information
    $data['payment'] = $this->request->getPost('payment') ? trim($this->request->getPost('payment')) : 0;
    $data['payment_mode'] = $this->request->getPost('payment_mode') ? trim($this->request->getPost('payment_mode')) : null;
    $data['payment_status'] = 2; // Confirmed payment
    $data['paid_through'] = 'DIRECT';

    // Tamil Calendar Information
    $data['date_of_expiry'] = $this->request->getPost('date_of_expiry') ?? null;
    $data['tamil_month'] = $this->request->getPost('tamil_month') ? trim($this->request->getPost('tamil_month')) : null;
    $data['tamil_day'] = $this->request->getPost('tamil_day') ? trim($this->request->getPost('tamil_day')) : null;
    $data['krishna_poorva'] = $this->request->getPost('krishna_poorva') ? trim($this->request->getPost('krishna_poorva')) : null;
    $data['thithi'] = $this->request->getPost('thithi') ? trim($this->request->getPost('thithi')) : null;

    // Deceased member fields
    if ($this->request->getPost('death_date')) {
        $data['death_date'] = $this->request->getPost('death_date');
        $data['status'] = 'demise';
    }
    
    if ($this->request->getPost('date_of_sivapatham')) {
        $data['date_of_sivapatham'] = $this->request->getPost('date_of_sivapatham');
    }
    if ($this->request->getPost('time_of_death')) {
        $data['time_of_death'] = $this->request->getPost('time_of_death');
    }
    if ($this->request->getPost('remarks')) {
        $data['remarks'] = $this->request->getPost('remarks');
    }

    // Proposer details
    if ($this->request->getPost('proposer_1_id')) {
        $proposer1 = $this->db->table('member')->where('id', $this->request->getPost('proposer_1_id'))->get()->getRowArray();
        if ($proposer1) {
            $data['proposer_1_id'] = $this->request->getPost('proposer_1_id');
            $data['proposer_1_name'] = $proposer1['name'];
            $data['proposer_1_member_no'] = $proposer1['member_no'];
        }
    }

    if ($this->request->getPost('proposer_2_id')) {
        $proposer2 = $this->db->table('member')->where('id', $this->request->getPost('proposer_2_id'))->get()->getRowArray();
        if ($proposer2) {
            $data['proposer_2_id'] = $this->request->getPost('proposer_2_id');
            $data['proposer_2_name'] = $proposer2['name'];
            $data['proposer_2_member_no'] = $proposer2['member_no'];
        }
    }

    // Ledger assignment
    if ($this->request->getPost('ledger_id')) {
        $data['ledger_id'] = $this->request->getPost('ledger_id');
    }

    return $data;
}

// ============================================
// UPDATE YOUR EXISTING save() METHOD
// ============================================
	public function delete_document()
	{
		$response = ['success' => false, 'message' => ''];

		if (!$this->model->permission_validate('member', 'edit')) {
			$response['message'] = 'Permission denied';
			return $this->response->setJSON($response);
		}

		$doc_id = $this->request->getPost('doc_id');

		if (empty($doc_id)) {
			$response['message'] = 'Invalid document ID';
			return $this->response->setJSON($response);
		}

		// Get document info
		$doc = $this->db->table('member_documents')
			->where('id', $doc_id)
			->get()->getRowArray();

		if (!$doc) {
			$response['message'] = 'Document not found';
			return $this->response->setJSON($response);
		}

		// Delete physical file
		if (!empty($doc['file_path'])) {
			$file_path = FCPATH . $doc['file_path'];
			if (file_exists($file_path)) {
				unlink($file_path);
			}
		}

		// Delete from database
		$res = $this->db->table('member_documents')->where('id', $doc_id)->delete();

		if ($res) {
			$response['success'] = true;
			$response['message'] = 'Document deleted successfully';
		} else {
			$response['message'] = 'Failed to delete document';
		}

		return $this->response->setJSON($response);
	}

}