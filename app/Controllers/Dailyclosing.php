<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Dailyclosing extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		helper('common');
		$this->model = new PermissionModel();
		if (($this->session->get('log_id')) == false && $this->session->get('role') != 1) {
			$data['dn_msg'] = 'Please Login';
			header('Location: ' . base_url() . '/login');
			exit;
		}
	}
	public function index()
	{
		if (!empty($_POST['dailyclosing_start_date']))
			$dailyclosing_start_date = $_POST['dailyclosing_start_date'];
		else
			$dailyclosing_start_date = date("Y-m-d");
		if (!empty($_POST['dailyclosing_end_date']))
			$dailyclosing_end_date = $_POST['dailyclosing_end_date'];
		else
			$dailyclosing_end_date = date("Y-m-d");
		// $booking_type = '';
		if (!empty($_POST['booking_type']))
			$booking_type = $_POST['booking_type'];
		/* $archanai_data_direct = daily_archanai_booking_withcurrentdate_overall($current_date, $booking_type = "DIRECT");
					$archanai_data_online = daily_archanai_booking_withcurrentdate_overall($current_date, $booking_type = "ONLINE"); */
		$data['archanai_details'] = daily_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		/* $hallbooking_data_direct = daily_hall_booking_withcurrentdate($current_date, $booking_type = "DIRECT");
					$hallbooking_data_online = daily_hall_booking_withcurrentdate($current_date, $booking_type = "ONLINE");
					$data['hallbooking_details'] = array_merge($hallbooking_data_direct,$hallbooking_data_online); */
		$data['archanai_group_details'] = daily_group_deity_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['hallbooking_details'] = daily_hall_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);

		$ubayam_data = daily_ubayam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['ubayam_details'] = $ubayam_data;
		$donation_data = daily_donation_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['donation_details'] = $donation_data;
		$prasadam_data = daily_prasadam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['prasadam_details'] = $prasadam_data;
		$annathanam_data = daily_annathanam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['annathanam_details'] = $annathanam_data;
		$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['product_offering_details'] = $product_offering_data;
		$repayment_data = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['repayment_details'] = $repayment_data;
		$payment_voucher_data = daily_payment_voucher_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$repayment_data = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['repayment_details'] = $repayment_data;

		$data['payment_voucher_details'] = $payment_voucher_data;
		$data['dailyclosing_start_date'] = $dailyclosing_start_date;
		$data['dailyclosing_end_date'] = $dailyclosing_end_date;
		$data['booking_type'] = $booking_type;

		echo view('template/header');
		echo view('template/sidebar');
		echo view('daily_closing/index', $data);
		echo view('template/footer');
	}
	public function print($fromdate, $todate, $booking_type = '')
	{
		$tmpid = $this->session->get('profile_id');
		if (!empty($fromdate))
			$dailyclosing_start_date = date('Y-m-d', $fromdate);
		else
			$dailyclosing_start_date = date("Y-m-d");
		if (!empty($todate))
			$dailyclosing_end_date = date('Y-m-d', $todate);
		else
			$dailyclosing_end_date = date("Y-m-d");

		// Pass $booking_type to all functions
		$data['archanai_details'] = daily_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['archanai_group_details'] = daily_group_deity_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['hallbooking_details'] = daily_hall_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$ubayam_data = daily_ubayam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['ubayam_details'] = $ubayam_data;
		$donation_data = daily_donation_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['donation_details'] = $donation_data;
		$prasadam_data = daily_prasadam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['prasadam_details'] = $prasadam_data;
		$annathanam_data = daily_annathanam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['annathanam_details'] = $annathanam_data;
		$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['product_offering_details'] = $product_offering_data;
		$payment_voucher_data = daily_payment_voucher_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['payment_voucher_details'] = $payment_voucher_data;
		$repayment_data = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['repayment_details'] = $repayment_data;

		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		$data['dailyclosing_start_date'] = $dailyclosing_start_date;
		$data['dailyclosing_end_date'] = $dailyclosing_end_date;
		$data['booking_type'] = $booking_type;

		echo view('daily_closing/print_page', $data);
	}
	
// ============================================================
// ADD THIS METHOD inside the Dailyclosing controller class
// File: app/Controllers/Dailyclosing.php
// Requires: composer require phpoffice/phpspreadsheet
// ============================================================

public function exportExcel($fromdate, $todate, $booking_type = '')
{
    helper('common');

    $dailyclosing_start_date = !empty($fromdate) ? date('Y-m-d', $fromdate) : date("Y-m-d");
    $dailyclosing_end_date   = !empty($todate)    ? date('Y-m-d', $todate)  : date("Y-m-d");

    // ── Gather all data (same as print()) ──────────────────────────────
    $archanai_group_details  = daily_group_deity_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
    $archanai_details        = daily_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
    $hallbooking_details     = daily_hall_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
    $ubayam_details          = daily_ubayam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
    $donation_details        = daily_donation_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
    $prasadam_details        = daily_prasadam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
    $annathanam_details      = daily_annathanam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
    $product_offering_details = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
    $repayment_details       = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
    $payment_voucher_details = daily_payment_voucher_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);

    // ── PhpSpreadsheet setup ───────────────────────────────────────────
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $spreadsheet->getProperties()
        ->setTitle("Daily Closing Report")
        ->setDescription("Daily Closing Report: $dailyclosing_start_date to $dailyclosing_end_date");

    // ── Shared styles ──────────────────────────────────────────────────
    $headerFill  = ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '3F51B5']];
    $headerFont  = ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'name' => 'Arial', 'size' => 10];
    $titleFont   = ['bold' => true, 'size' => 12, 'name' => 'Arial'];
    $footerFill  = ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'A1A09F']];
    $footerFont  = ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'name' => 'Arial', 'size' => 10];
    $totalFill   = ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8EAF6']];
    $borderThin  = ['borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];
    $numFormat   = '#,##0.00';
    $amtAlign    = ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT];
    $centerAlign = ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER];

    // Helper: apply header row style
    $applyHeaderStyle = function ($sheet, $range) use ($headerFill, $headerFont, $borderThin) {
        $sheet->getStyle($range)->applyFromArray([
            'fill'      => $headerFill,
            'font'      => $headerFont,
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders'   => $borderThin['borders'],
        ]);
    };

    // Helper: normalise payment mode label
    $payLabel = function ($mode) {
        $map = [
            'ipay_merch_qr'     => 'QR PAYMENT',
            'ipay_merch_online' => 'ONLINE PAYMENT',
            'cash'              => 'CASH',
            'nets_pay'          => 'NETS',
            'pay_now'           => 'PAY NOW',
        ];
        $key = strtolower(trim($mode));
        return isset($map[$key]) ? $map[$key] : strtoupper(str_replace('_', ' ', $mode));
    };

    $summary_total = ['sales' => [], 'expense' => []];

    // ══════════════════════════════════════════════════════════════════
    // SHEET 1 – Archanai
    // ══════════════════════════════════════════════════════════════════
    $sheet = $spreadsheet->getActiveSheet()->setTitle('Archanai');

    // Report title
    $sheet->mergeCells('A1:F1');
    $sheet->setCellValue('A1', "Daily Closing Report – Archanai  ({$dailyclosing_start_date} to {$dailyclosing_end_date})");
    $sheet->getStyle('A1')->applyFromArray(['font' => $titleFont, 'alignment' => $centerAlign]);
    $sheet->getRowDimension(1)->setRowHeight(20);

    // Headers
    $arcHeaders = ['S.No', 'Archanai', 'Pay Mode', 'Paid Through', 'Quantity', 'Amount (RM)'];
    foreach ($arcHeaders as $ci => $h) {
        $sheet->setCellValueByColumnAndRow($ci + 1, 2, $h);
    }
    $applyHeaderStyle($sheet, 'A2:F2');

    $row = 3;
    $archanai_total    = 0;
    $archanai_discount = 0;

    foreach ($archanai_group_details as $group) {
        if (empty($group['deities'])) continue;

        // Group title row
        $sheet->mergeCells("A{$row}:F{$row}");
        $sheet->setCellValue("A{$row}", '--- ' . strtoupper($group['title']) . ' ---');
        $sheet->getStyle("A{$row}")->applyFromArray([
            'font'      => ['bold' => true, 'name' => 'Arial'],
            'alignment' => $centerAlign,
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'C5CAE9']],
        ]);
        $row++;

        $sannathi_total = 0;
        $sannathi_qty   = 0;
        $ar_i = 1;

        foreach ($group['deities'] as $deity) {
            // Deity sub-title
            $sheet->mergeCells("A{$row}:F{$row}");
            $sheet->setCellValue("A{$row}", strtoupper($deity['deity_name']));
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'italic' => true, 'name' => 'Arial'],
                'alignment' => $centerAlign,
                'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8EAF6']],
            ]);
            $row++;

            foreach ($deity['products'] as $p) {
                $sheet->setCellValueByColumnAndRow(1, $row, $ar_i);
                $sheet->setCellValueByColumnAndRow(2, $row, $p['name_in_english'] . ' / ' . $p['name_in_tamil']);
                $sheet->setCellValueByColumnAndRow(3, $row, strtoupper($p['paymentmode']));
                $sheet->setCellValueByColumnAndRow(4, $row, strtoupper($p['paid_through']));
                $sheet->setCellValueByColumnAndRow(5, $row, $p['qty']);
                $sheet->setCellValueByColumnAndRow(6, $row, $p['amount']);
                $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode($numFormat);
                $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($borderThin);

                $archanai_total    += $p['amount'];
                $archanai_discount += $p['discount_amount'];
                $sannathi_total    += $p['amount'];
                $sannathi_qty      += $p['qty'];

                $pm = strtolower(trim($p['paymentmode']));
                if (!isset($summary_total['sales'][$pm])) $summary_total['sales'][$pm] = 0;
                $summary_total['sales'][$pm] += $p['amount'];

                $ar_i++;
                $row++;
            }
        }

        // Sub-total for group
        $sheet->mergeCells("A{$row}:D{$row}");
        $sheet->setCellValue("A{$row}", strtoupper($group['title']) . ' Total');
        $sheet->setCellValueByColumnAndRow(5, $row, $sannathi_qty);
        $sheet->setCellValueByColumnAndRow(6, $row, $sannathi_total);
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
            'font'    => ['bold' => true, 'name' => 'Arial'],
            'fill'    => $footerFill,
            'borders' => $borderThin['borders'],
        ]);
        $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode($numFormat);
        $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $row++;
    }

    $archanai_final = $archanai_total - $archanai_discount;

    // Footer totals
    foreach ([
        ['Archanai Sales Sub Total', $archanai_total],
        ['Archanai Discount Total',  -$archanai_discount],
        ['Archanai Grand Total',      $archanai_final],
    ] as $ft) {
        $sheet->mergeCells("A{$row}:E{$row}");
        $sheet->setCellValue("A{$row}", $ft[0]);
        $sheet->setCellValueByColumnAndRow(6, $row, $ft[1]);
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
            'font'    => ['bold' => true, 'name' => 'Arial'],
            'fill'    => $totalFill,
            'borders' => $borderThin['borders'],
        ]);
        $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode($numFormat);
        $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $row++;
    }

    // Column widths
    $sheet->getColumnDimension('A')->setWidth(6);
    $sheet->getColumnDimension('B')->setWidth(40);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(16);
    $sheet->getColumnDimension('E')->setWidth(10);
    $sheet->getColumnDimension('F')->setWidth(14);

    // ══════════════════════════════════════════════════════════════════
    // SHEET 2 – Hall Booking
    // ══════════════════════════════════════════════════════════════════
    $sheet2 = $spreadsheet->createSheet()->setTitle('Hall Booking');
    $sheet2->mergeCells('A1:H1');
    $sheet2->setCellValue('A1', "Hall Booking Details ({$dailyclosing_start_date} to {$dailyclosing_end_date})");
    $sheet2->getStyle('A1')->applyFromArray(['font' => $titleFont, 'alignment' => $centerAlign]);

    $hbHeaders = ['S.No', 'Name', 'Event Date', 'Payment Mode', 'Booked Through', 'Payment Type', 'Total Amount (RM)', 'Paid Amount (RM)'];
    foreach ($hbHeaders as $ci => $h) {
        $sheet2->setCellValueByColumnAndRow($ci + 1, 2, $h);
    }
    $applyHeaderStyle($sheet2, 'A2:H2');

    $row2 = 3;
    $hb_total = 0;
    foreach ($hallbooking_details as $i => $hb) {
        $hb_total += $hb['paidamount'];
        $pm = strtolower(trim($hb['paymentmode']));
        if (!isset($summary_total['sales'][$pm])) $summary_total['sales'][$pm] = 0;
        $summary_total['sales'][$pm] += $hb['paidamount'];

        $bkgMethod = ($hb['booking_through'] == 'DIRECT') ? 'ADMIN' : 'COUNTER';
        $sheet2->setCellValueByColumnAndRow(1, $row2, $i + 1);
        $sheet2->setCellValueByColumnAndRow(2, $row2, $hb['customer_name']);
        $sheet2->setCellValueByColumnAndRow(3, $row2, $hb['date']);
        $sheet2->setCellValueByColumnAndRow(4, $row2, $payLabel($hb['paymentmode']));
        $sheet2->setCellValueByColumnAndRow(5, $row2, $bkgMethod);
        $sheet2->setCellValueByColumnAndRow(6, $row2, $hb['payment_type']);
        $sheet2->setCellValueByColumnAndRow(7, $row2, $hb['amount']);
        $sheet2->setCellValueByColumnAndRow(8, $row2, $hb['paidamount']);
        $sheet2->getStyle("G{$row2}")->getNumberFormat()->setFormatCode($numFormat);
        $sheet2->getStyle("H{$row2}")->getNumberFormat()->setFormatCode($numFormat);
        $sheet2->getStyle("G{$row2}:H{$row2}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet2->getStyle("A{$row2}:H{$row2}")->applyFromArray($borderThin);
        $row2++;
    }
    $sheet2->mergeCells("A{$row2}:G{$row2}");
    $sheet2->setCellValue("A{$row2}", 'Grand Total');
    $sheet2->setCellValueByColumnAndRow(8, $row2, $hb_total);
    $sheet2->getStyle("A{$row2}:H{$row2}")->applyFromArray(['font' => ['bold' => true, 'name' => 'Arial'], 'fill' => $footerFill, 'borders' => $borderThin['borders']]);
    $sheet2->getStyle("H{$row2}")->getNumberFormat()->setFormatCode($numFormat);
    $sheet2->getStyle("H{$row2}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    foreach (['A' => 6, 'B' => 28, 'C' => 14, 'D' => 20, 'E' => 14, 'F' => 14, 'G' => 18, 'H' => 18] as $col => $w) {
        $sheet2->getColumnDimension($col)->setWidth($w);
    }

    // ══════════════════════════════════════════════════════════════════
    // SHEET 3 – Ubayam
    // ══════════════════════════════════════════════════════════════════
    $sheet3 = $spreadsheet->createSheet()->setTitle('Ubayam');
    $sheet3->mergeCells('A1:H1');
    $sheet3->setCellValue('A1', "Ubayam Details ({$dailyclosing_start_date} to {$dailyclosing_end_date})");
    $sheet3->getStyle('A1')->applyFromArray(['font' => $titleFont, 'alignment' => $centerAlign]);

    $ubHeaders = ['S.No', 'Name', 'Event Date', 'Pay Mode', 'Type', 'Paid Through', 'Total Amount (RM)', 'Paid Amount (RM)'];
    foreach ($ubHeaders as $ci => $h) {
        $sheet3->setCellValueByColumnAndRow($ci + 1, 2, $h);
    }
    $applyHeaderStyle($sheet3, 'A2:H2');

    $row3 = 3;
    $ub_total = 0;
    foreach ($ubayam_details as $i => $ub) {
        $ub_total += $ub['paidamount'];
        $pm = strtolower(trim($ub['paymentmode']));
        if (!isset($summary_total['sales'][$pm])) $summary_total['sales'][$pm] = 0;
        $summary_total['sales'][$pm] += $ub['paidamount'];

        $sheet3->setCellValueByColumnAndRow(1, $row3, $i + 1);
        $sheet3->setCellValueByColumnAndRow(2, $row3, $ub['customer_name']);
        $sheet3->setCellValueByColumnAndRow(3, $row3, $ub['date']);
        $sheet3->setCellValueByColumnAndRow(4, $row3, $payLabel($ub['paymentmode']));
        $sheet3->setCellValueByColumnAndRow(5, $row3, $ub['payment_type']);
        $sheet3->setCellValueByColumnAndRow(6, $row3, $ub['booking_through']);
        $sheet3->setCellValueByColumnAndRow(7, $row3, $ub['amount']);
        $sheet3->setCellValueByColumnAndRow(8, $row3, $ub['paidamount']);
        $sheet3->getStyle("G{$row3}:H{$row3}")->getNumberFormat()->setFormatCode($numFormat);
        $sheet3->getStyle("G{$row3}:H{$row3}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet3->getStyle("A{$row3}:H{$row3}")->applyFromArray($borderThin);
        $row3++;
    }
    $sheet3->mergeCells("A{$row3}:G{$row3}");
    $sheet3->setCellValue("A{$row3}", 'Grand Total');
    $sheet3->setCellValueByColumnAndRow(8, $row3, $ub_total);
    $sheet3->getStyle("A{$row3}:H{$row3}")->applyFromArray(['font' => ['bold' => true, 'name' => 'Arial'], 'fill' => $footerFill, 'borders' => $borderThin['borders']]);
    $sheet3->getStyle("H{$row3}")->getNumberFormat()->setFormatCode($numFormat);
    $sheet3->getStyle("H{$row3}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    foreach (['A' => 6, 'B' => 28, 'C' => 14, 'D' => 20, 'E' => 14, 'F' => 16, 'G' => 18, 'H' => 18] as $col => $w) {
        $sheet3->getColumnDimension($col)->setWidth($w);
    }

    // ══════════════════════════════════════════════════════════════════
    // SHEET 4 – Donation
    // ══════════════════════════════════════════════════════════════════
    $sheet4 = $spreadsheet->createSheet()->setTitle('Donation');
    $sheet4->mergeCells('A1:E1');
    $sheet4->setCellValue('A1', "Donation Details ({$dailyclosing_start_date} to {$dailyclosing_end_date})");
    $sheet4->getStyle('A1')->applyFromArray(['font' => $titleFont, 'alignment' => $centerAlign]);

    $dnHeaders = ['S.No', 'Name', 'Pay Mode', 'Paid Through', 'Amount (RM)'];
    foreach ($dnHeaders as $ci => $h) {
        $sheet4->setCellValueByColumnAndRow($ci + 1, 2, $h);
    }
    $applyHeaderStyle($sheet4, 'A2:E2');

    $row4 = 3;
    $dn_total = 0;
    foreach ($donation_details as $i => $dn) {
        $dn_total += $dn['paidamount'];
        $pm = strtolower(trim($dn['paymentmode']));
        if (!isset($summary_total['sales'][$pm])) $summary_total['sales'][$pm] = 0;
        $summary_total['sales'][$pm] += $dn['paidamount'];

        $sheet4->setCellValueByColumnAndRow(1, $row4, $i + 1);
        $sheet4->setCellValueByColumnAndRow(2, $row4, $dn['person_name']);
        $sheet4->setCellValueByColumnAndRow(3, $row4, $payLabel($dn['paymentmode']));
        $sheet4->setCellValueByColumnAndRow(4, $row4, $dn['paid_through']);
        $sheet4->setCellValueByColumnAndRow(5, $row4, $dn['paidamount']);
        $sheet4->getStyle("E{$row4}")->getNumberFormat()->setFormatCode($numFormat);
        $sheet4->getStyle("E{$row4}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet4->getStyle("A{$row4}:E{$row4}")->applyFromArray($borderThin);
        $row4++;
    }
    $sheet4->mergeCells("A{$row4}:D{$row4}");
    $sheet4->setCellValue("A{$row4}", 'Grand Total');
    $sheet4->setCellValueByColumnAndRow(5, $row4, $dn_total);
    $sheet4->getStyle("A{$row4}:E{$row4}")->applyFromArray(['font' => ['bold' => true, 'name' => 'Arial'], 'fill' => $footerFill, 'borders' => $borderThin['borders']]);
    $sheet4->getStyle("E{$row4}")->getNumberFormat()->setFormatCode($numFormat);
    $sheet4->getStyle("E{$row4}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    foreach (['A' => 6, 'B' => 30, 'C' => 20, 'D' => 16, 'E' => 16] as $col => $w) {
        $sheet4->getColumnDimension($col)->setWidth($w);
    }

    // ══════════════════════════════════════════════════════════════════
    // SHEET 5 – Prasadam
    // ══════════════════════════════════════════════════════════════════
    $sheet5 = $spreadsheet->createSheet()->setTitle('Prasadam');
    $sheet5->mergeCells('A1:G1');
    $sheet5->setCellValue('A1', "Prasadam Details ({$dailyclosing_start_date} to {$dailyclosing_end_date})");
    $sheet5->getStyle('A1')->applyFromArray(['font' => $titleFont, 'alignment' => $centerAlign]);

    $psHeaders = ['S.No', 'Name', 'Pay Mode', 'Payment Type', 'Paid Through', 'Total Amount (RM)', 'Paid Amount (RM)'];
    foreach ($psHeaders as $ci => $h) {
        $sheet5->setCellValueByColumnAndRow($ci + 1, 2, $h);
    }
    $applyHeaderStyle($sheet5, 'A2:G2');

    $row5 = 3;
    $ps_total = 0;
    foreach ($prasadam_details as $i => $ps) {
        $ps_total += $ps['paidamount'];
        $pm = strtolower(trim($ps['paymentmode']));
        if (!isset($summary_total['sales'][$pm])) $summary_total['sales'][$pm] = 0;
        $summary_total['sales'][$pm] += $ps['paidamount'];

        $sheet5->setCellValueByColumnAndRow(1, $row5, $i + 1);
        $sheet5->setCellValueByColumnAndRow(2, $row5, $ps['customer_name']);
        $sheet5->setCellValueByColumnAndRow(3, $row5, $payLabel($ps['paymentmode']));
        $sheet5->setCellValueByColumnAndRow(4, $row5, $ps['payment_type']);
        $sheet5->setCellValueByColumnAndRow(5, $row5, $ps['paid_through']);
        $sheet5->setCellValueByColumnAndRow(6, $row5, $ps['amount']);
        $sheet5->setCellValueByColumnAndRow(7, $row5, $ps['paidamount']);
        $sheet5->getStyle("F{$row5}:G{$row5}")->getNumberFormat()->setFormatCode($numFormat);
        $sheet5->getStyle("F{$row5}:G{$row5}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet5->getStyle("A{$row5}:G{$row5}")->applyFromArray($borderThin);
        $row5++;
    }
    $sheet5->mergeCells("A{$row5}:F{$row5}");
    $sheet5->setCellValue("A{$row5}", 'Grand Total');
    $sheet5->setCellValueByColumnAndRow(7, $row5, $ps_total);
    $sheet5->getStyle("A{$row5}:G{$row5}")->applyFromArray(['font' => ['bold' => true, 'name' => 'Arial'], 'fill' => $footerFill, 'borders' => $borderThin['borders']]);
    $sheet5->getStyle("G{$row5}")->getNumberFormat()->setFormatCode($numFormat);
    $sheet5->getStyle("G{$row5}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    foreach (['A' => 6, 'B' => 28, 'C' => 20, 'D' => 14, 'E' => 16, 'F' => 18, 'G' => 18] as $col => $w) {
        $sheet5->getColumnDimension($col)->setWidth($w);
    }

    // ══════════════════════════════════════════════════════════════════
    // SHEET 6 – Annathanam
    // ══════════════════════════════════════════════════════════════════
    $sheet6 = $spreadsheet->createSheet()->setTitle('Annathanam');
    $sheet6->mergeCells('A1:H1');
    $sheet6->setCellValue('A1', "Annathanam Details ({$dailyclosing_start_date} to {$dailyclosing_end_date})");
    $sheet6->getStyle('A1')->applyFromArray(['font' => $titleFont, 'alignment' => $centerAlign]);

    $anHeaders = ['S.No', 'Name', 'Time', 'Payment Mode', 'Payment Type', 'Paid Through', 'Total Amount (RM)', 'Paid Amount (RM)'];
    foreach ($anHeaders as $ci => $h) {
        $sheet6->setCellValueByColumnAndRow($ci + 1, 2, $h);
    }
    $applyHeaderStyle($sheet6, 'A2:H2');

    $row6 = 3;
    $an_total = 0;
    foreach ($annathanam_details as $i => $an) {
        $an_total += $an['paidamount'];
        $pm = strtolower(trim($an['paymentmode']));
        if (!isset($summary_total['sales'][$pm])) $summary_total['sales'][$pm] = 0;
        $summary_total['sales'][$pm] += $an['paidamount'];

        $sheet6->setCellValueByColumnAndRow(1, $row6, $i + 1);
        $sheet6->setCellValueByColumnAndRow(2, $row6, $an['customer_name']);
        $sheet6->setCellValueByColumnAndRow(3, $row6, $an['time']);
        $sheet6->setCellValueByColumnAndRow(4, $row6, $payLabel($an['paymentmode']));
        $sheet6->setCellValueByColumnAndRow(5, $row6, $an['payment_type']);
        $sheet6->setCellValueByColumnAndRow(6, $row6, $an['booking_through']);
        $sheet6->setCellValueByColumnAndRow(7, $row6, $an['amount']);
        $sheet6->setCellValueByColumnAndRow(8, $row6, $an['paidamount']);
        $sheet6->getStyle("G{$row6}:H{$row6}")->getNumberFormat()->setFormatCode($numFormat);
        $sheet6->getStyle("G{$row6}:H{$row6}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet6->getStyle("A{$row6}:H{$row6}")->applyFromArray($borderThin);
        $row6++;
    }
    $sheet6->mergeCells("A{$row6}:G{$row6}");
    $sheet6->setCellValue("A{$row6}", 'Grand Total');
    $sheet6->setCellValueByColumnAndRow(8, $row6, $an_total);
    $sheet6->getStyle("A{$row6}:H{$row6}")->applyFromArray(['font' => ['bold' => true, 'name' => 'Arial'], 'fill' => $footerFill, 'borders' => $borderThin['borders']]);
    $sheet6->getStyle("H{$row6}")->getNumberFormat()->setFormatCode($numFormat);
    $sheet6->getStyle("H{$row6}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    foreach (['A' => 6, 'B' => 28, 'C' => 12, 'D' => 20, 'E' => 14, 'F' => 16, 'G' => 18, 'H' => 18] as $col => $w) {
        $sheet6->getColumnDimension($col)->setWidth($w);
    }

    // ══════════════════════════════════════════════════════════════════
    // SHEET 7 – Repayment
    // ══════════════════════════════════════════════════════════════════
    $sheet7 = $spreadsheet->createSheet()->setTitle('Repayment');
    $sheet7->mergeCells('A1:H1');
    $sheet7->setCellValue('A1', "Repayment Details ({$dailyclosing_start_date} to {$dailyclosing_end_date})");
    $sheet7->getStyle('A1')->applyFromArray(['font' => $titleFont, 'alignment' => $centerAlign]);

    $rpHeaders = ['S.No', 'Type', 'Name', 'Booked Date', 'Payment Mode', 'Paid Through', 'Booking Amount (RM)', 'Repaid Amount (RM)'];
    foreach ($rpHeaders as $ci => $h) {
        $sheet7->setCellValueByColumnAndRow($ci + 1, 2, $h);
    }
    $applyHeaderStyle($sheet7, 'A2:H2');

    $row7 = 3;
    $rp_total = 0;
    foreach ($repayment_details as $i => $rp) {
        $rp_total += $rp['repaid_amount'];
        $pm = strtolower(trim($rp['paymentmode']));
        if (!isset($summary_total['sales'][$pm])) $summary_total['sales'][$pm] = 0;
        $summary_total['sales'][$pm] += $rp['repaid_amount'];

        $sheet7->setCellValueByColumnAndRow(1, $row7, $i + 1);
        $sheet7->setCellValueByColumnAndRow(2, $row7, $rp['type']);
        $sheet7->setCellValueByColumnAndRow(3, $row7, $rp['customer_name']);
        $sheet7->setCellValueByColumnAndRow(4, $row7, $rp['date']);
        $sheet7->setCellValueByColumnAndRow(5, $row7, $payLabel($rp['paymentmode']));
        $sheet7->setCellValueByColumnAndRow(6, $row7, $rp['paid_through']);
        $sheet7->setCellValueByColumnAndRow(7, $row7, $rp['amount']);
        $sheet7->setCellValueByColumnAndRow(8, $row7, $rp['repaid_amount']);
        $sheet7->getStyle("G{$row7}:H{$row7}")->getNumberFormat()->setFormatCode($numFormat);
        $sheet7->getStyle("G{$row7}:H{$row7}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet7->getStyle("A{$row7}:H{$row7}")->applyFromArray($borderThin);
        $row7++;
    }
    $sheet7->mergeCells("A{$row7}:G{$row7}");
    $sheet7->setCellValue("A{$row7}", 'Grand Total');
    $sheet7->setCellValueByColumnAndRow(8, $row7, $rp_total);
    $sheet7->getStyle("A{$row7}:H{$row7}")->applyFromArray(['font' => ['bold' => true, 'name' => 'Arial'], 'fill' => $footerFill, 'borders' => $borderThin['borders']]);
    $sheet7->getStyle("H{$row7}")->getNumberFormat()->setFormatCode($numFormat);
    $sheet7->getStyle("H{$row7}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    foreach (['A' => 6, 'B' => 16, 'C' => 28, 'D' => 14, 'E' => 20, 'F' => 16, 'G' => 20, 'H' => 20] as $col => $w) {
        $sheet7->getColumnDimension($col)->setWidth($w);
    }

    // ══════════════════════════════════════════════════════════════════
    // SHEET 8 – Payment Voucher
    // ══════════════════════════════════════════════════════════════════
    $sheet8 = $spreadsheet->createSheet()->setTitle('Payment Voucher');
    $sheet8->mergeCells('A1:E1');
    $sheet8->setCellValue('A1', "Payment Voucher Details ({$dailyclosing_start_date} to {$dailyclosing_end_date})");
    $sheet8->getStyle('A1')->applyFromArray(['font' => $titleFont, 'alignment' => $centerAlign]);

    $pvHeaders = ['S.No', 'Paid To', 'Payment Mode', 'Remarks', 'Amount (RM)'];
    foreach ($pvHeaders as $ci => $h) {
        $sheet8->setCellValueByColumnAndRow($ci + 1, 2, $h);
    }
    $applyHeaderStyle($sheet8, 'A2:E2');

    $row8 = 3;
    $pv_total = 0;
    foreach ($payment_voucher_details as $i => $pv) {
        $pv_total += $pv['paidamount'];
        $pm = strtolower(trim($pv['paymentmode']));
        if (!isset($summary_total['expense'][$pm])) $summary_total['expense'][$pm] = 0;
        $summary_total['expense'][$pm] += $pv['paidamount'];

        $sheet8->setCellValueByColumnAndRow(1, $row8, $i + 1);
        $sheet8->setCellValueByColumnAndRow(2, $row8, strtoupper($pv['booking_id']));
        $sheet8->setCellValueByColumnAndRow(3, $row8, strtoupper($pv['paymentmode']));
        $sheet8->setCellValueByColumnAndRow(4, $row8, strtoupper($pv['details']));
        $sheet8->setCellValueByColumnAndRow(5, $row8, $pv['paidamount']);
        $sheet8->getStyle("E{$row8}")->getNumberFormat()->setFormatCode($numFormat);
        $sheet8->getStyle("E{$row8}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet8->getStyle("A{$row8}:E{$row8}")->applyFromArray($borderThin);
        $row8++;
    }
    $sheet8->mergeCells("A{$row8}:D{$row8}");
    $sheet8->setCellValue("A{$row8}", 'Grand Total');
    $sheet8->setCellValueByColumnAndRow(5, $row8, $pv_total);
    $sheet8->getStyle("A{$row8}:E{$row8}")->applyFromArray(['font' => ['bold' => true, 'name' => 'Arial'], 'fill' => $footerFill, 'borders' => $borderThin['borders']]);
    $sheet8->getStyle("E{$row8}")->getNumberFormat()->setFormatCode($numFormat);
    $sheet8->getStyle("E{$row8}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    foreach (['A' => 6, 'B' => 30, 'C' => 20, 'D' => 40, 'E' => 16] as $col => $w) {
        $sheet8->getColumnDimension($col)->setWidth($w);
    }

    // ══════════════════════════════════════════════════════════════════
    // SHEET 9 – Summary
    // ══════════════════════════════════════════════════════════════════
    $sheet9 = $spreadsheet->createSheet()->setTitle('Summary');
    $sheet9->mergeCells('A1:C1');
    $sheet9->setCellValue('A1', "Daily Closing Summary ({$dailyclosing_start_date} to {$dailyclosing_end_date})");
    $sheet9->getStyle('A1')->applyFromArray(['font' => $titleFont, 'alignment' => $centerAlign]);

    // Income section header
    $sheet9->mergeCells('A2:C2');
    $sheet9->setCellValue('A2', 'INCOME');
    $sheet9->getStyle('A2')->applyFromArray([
        'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'name' => 'Arial'],
        'fill'      => $headerFill,
        'alignment' => $centerAlign,
        'borders'   => $borderThin['borders'],
    ]);

    $sheet9->setCellValue('A3', 'Payment Mode');
    $sheet9->setCellValue('B3', 'Amount (RM)');
    $applyHeaderStyle($sheet9, 'A3:B3');

    $sumRow = 4;
    $income_total = 0;
    $summary_totals_income = [];
    foreach ($summary_total['sales'] as $vl => $st) {
        $label = $payLabel($vl);
        $summary_totals_income[$label] = ($summary_totals_income[$label] ?? 0) + $st;
    }
    foreach ($summary_totals_income as $label => $amt) {
        if ($amt == 0) continue;
        $sheet9->setCellValue("A{$sumRow}", $label);
        $sheet9->setCellValue("B{$sumRow}", $amt);
        $sheet9->getStyle("B{$sumRow}")->getNumberFormat()->setFormatCode($numFormat);
        $sheet9->getStyle("B{$sumRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet9->getStyle("A{$sumRow}:B{$sumRow}")->applyFromArray($borderThin);
        $income_total += $amt;
        $sumRow++;
    }

    // Income totals block
    $income_sub_total = $archanai_final + $hb_total + $ub_total + $dn_total + $ps_total + $an_total + $rp_total;
    foreach ([
        ['Archanai Grand Total',  $archanai_final],
        ['Hall Booking Total',    $hb_total],
        ['Ubayam Total',          $ub_total],
        ['Donation Total',        $dn_total],
        ['Prasadam Total',        $ps_total],
        ['Annathanam Total',      $an_total],
        ['Repayment Total',       $rp_total],
    ] as $row_data) {
        $sheet9->setCellValue("A{$sumRow}", $row_data[0]);
        $sheet9->setCellValue("B{$sumRow}", $row_data[1]);
        $sheet9->getStyle("B{$sumRow}")->getNumberFormat()->setFormatCode($numFormat);
        $sheet9->getStyle("B{$sumRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet9->getStyle("A{$sumRow}:B{$sumRow}")->applyFromArray($borderThin);
        $sumRow++;
    }

    $sheet9->setCellValue("A{$sumRow}", 'Income Sub Total');
    $sheet9->setCellValue("B{$sumRow}", $income_sub_total);
    $sheet9->getStyle("A{$sumRow}:B{$sumRow}")->applyFromArray(['font' => ['bold' => true, 'name' => 'Arial'], 'fill' => $totalFill, 'borders' => $borderThin['borders']]);
    $sheet9->getStyle("B{$sumRow}")->getNumberFormat()->setFormatCode($numFormat);
    $sheet9->getStyle("B{$sumRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $sumRow += 2;

    // Expense section
    $sheet9->mergeCells("A{$sumRow}:C{$sumRow}");
    $sheet9->setCellValue("A{$sumRow}", 'EXPENSES');
    $sheet9->getStyle("A{$sumRow}")->applyFromArray([
        'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'name' => 'Arial'],
        'fill'      => $headerFill,
        'alignment' => $centerAlign,
        'borders'   => $borderThin['borders'],
    ]);
    $sumRow++;

    $sheet9->setCellValue("A{$sumRow}", 'Payment Mode');
    $sheet9->setCellValue("B{$sumRow}", 'Amount (RM)');
    $applyHeaderStyle($sheet9, "A{$sumRow}:B{$sumRow}");
    $sumRow++;

    $summary_totals_expense = [];
    foreach ($summary_total['expense'] as $vl => $st) {
        $label = $payLabel($vl);
        $summary_totals_expense[$label] = ($summary_totals_expense[$label] ?? 0) + $st;
    }
    foreach ($summary_totals_expense as $label => $amt) {
        if ($amt == 0) continue;
        $sheet9->setCellValue("A{$sumRow}", $label);
        $sheet9->setCellValue("B{$sumRow}", $amt);
        $sheet9->getStyle("B{$sumRow}")->getNumberFormat()->setFormatCode($numFormat);
        $sheet9->getStyle("B{$sumRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet9->getStyle("A{$sumRow}:B{$sumRow}")->applyFromArray($borderThin);
        $sumRow++;
    }

    $sheet9->setCellValue("A{$sumRow}", 'Expense Sub Total');
    $sheet9->setCellValue("B{$sumRow}", $pv_total);
    $sheet9->getStyle("A{$sumRow}:B{$sumRow}")->applyFromArray(['font' => ['bold' => true, 'name' => 'Arial'], 'fill' => $totalFill, 'borders' => $borderThin['borders']]);
    $sheet9->getStyle("B{$sumRow}")->getNumberFormat()->setFormatCode($numFormat);
    $sheet9->getStyle("B{$sumRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $sumRow += 2;

    $grand_total = $income_sub_total - $pv_total;
    $sheet9->mergeCells("A{$sumRow}:B{$sumRow}");
    $sheet9->setCellValue("A{$sumRow}", 'GRAND TOTAL: ' . number_format($grand_total, 2));
    $sheet9->getStyle("A{$sumRow}")->applyFromArray([
        'font'      => ['bold' => true, 'size' => 13, 'name' => 'Arial'],
        'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF9C4']],
        'alignment' => $centerAlign,
        'borders'   => $borderThin['borders'],
    ]);

    $sheet9->getColumnDimension('A')->setWidth(30);
    $sheet9->getColumnDimension('B')->setWidth(18);
    $sheet9->getColumnDimension('C')->setWidth(18);

    // ── Activate first sheet ───────────────────────────────────────────
    $spreadsheet->setActiveSheetIndex(0);

    // ── Output ────────────────────────────────────────────────────────
    $filename = 'DailyClosing_' . $dailyclosing_start_date . '_to_' . $dailyclosing_end_date . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
public function exportExcelConsolidated($fromdate, $todate, $booking_type = '')
	{
		helper('common');

		$dailyclosing_start_date = !empty($fromdate) ? date('Y-m-d', $fromdate) : date("Y-m-d");
		$dailyclosing_end_date = !empty($todate) ? date('Y-m-d', $todate) : date("Y-m-d");

		// ── Gather all data ────────────────────────────────────────────
		$archanai_group_details = daily_group_deity_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$hallbooking_details = daily_hall_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$ubayam_details = daily_ubayam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$donation_details = daily_donation_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$prasadam_details = daily_prasadam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$annathanam_details = daily_annathanam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$repayment_details = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$payment_voucher_details = daily_payment_voucher_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);

		// ── PhpSpreadsheet setup ───────────────────────────────────────
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$spreadsheet->getProperties()->setTitle("Daily Closing Report");
		$ws = $spreadsheet->getActiveSheet()->setTitle('Daily Closing');

		// ── Constants ─────────────────────────────────────────────────
		$numFmt = '#,##0.00';
		$RALIGN = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
		$CALIGN = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
		$VALIGN_MID = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;
		$SOLID = \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID;
		$THIN = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;
		$MEDIUM = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM;

		// Colours
		$C_TITLE = '1A237E'; // dark indigo   – report title
		$C_SUBTITLE = '283593'; // indigo        – period row
		$C_SEC = '3F51B5'; // mid indigo    – section headers (income modules)
		$C_SEC_EXP = 'B71C1C'; // dark red      – expense section header
		$C_GRP = '5C6BC0'; // light indigo  – group headers (inside Archanai)
		$C_DEITY = 'E8EAF6'; // very light    – deity sub-header
		$C_COLHDR = '283593'; // deep indigo   – column header row
		$C_SUB = 'C5CAE9'; // pale indigo   – group sub-totals
		$C_SECTOT = '9FA8DA'; // medium indigo – section totals
		$C_INC_TOTAL = '1A237E'; // dark indigo   – INCOME SUB TOTAL
		$C_EXP_TOTAL = 'B71C1C'; // dark red      – EXPENSE SUB TOTAL
		$C_EXP_ROW = 'FFEBEE'; // pink          – expense data rows
		$C_GRAND = 'FFF9C4'; // yellow        – GRAND TOTAL
		$C_SUM_HDR = '1B5E20'; // dark green    – summary header
		$C_SUM_ROW = 'E8F5E9'; // light green   – summary rows
		$C_SUM_INC = '66BB6A'; // green         – income sub in summary
		$C_SUM_EXP = 'FFCDD2'; // light red     – expense row in summary

		// ── Column widths  A–H ────────────────────────────────────────
		$ws->getColumnDimension('A')->setWidth(6);   // S.No
		$ws->getColumnDimension('B')->setWidth(32);  // Name / Description
		$ws->getColumnDimension('C')->setWidth(20);  // Pay Mode
		$ws->getColumnDimension('D')->setWidth(18);  // Paid Through / Type
		$ws->getColumnDimension('E')->setWidth(16);  // Date / Time / Extra
		$ws->getColumnDimension('F')->setWidth(16);  // Payment Type / Extra
		$ws->getColumnDimension('G')->setWidth(14);  // Qty / Total Amt
		$ws->getColumnDimension('H')->setWidth(16);  // Paid Amount

		// ── Reusable style helpers ─────────────────────────────────────

		// Full-width banner row (merges A–H)
		$banner = function ($row, $text, $bg, $fg, $sz, $bold = true, $ht = 20) use ($ws, $SOLID, $THIN, $CALIGN, $VALIGN_MID) {
			$ws->mergeCells("A{$row}:H{$row}");
			$ws->setCellValue("A{$row}", $text);
			$ws->getStyle("A{$row}:H{$row}")->applyFromArray([
				'font' => ['bold' => $bold, 'size' => $sz, 'name' => 'Arial', 'color' => ['rgb' => $fg]],
				'fill' => ['fillType' => $SOLID, 'startColor' => ['rgb' => $bg]],
				'alignment' => ['horizontal' => $CALIGN, 'vertical' => $VALIGN_MID],
				'borders' => ['allBorders' => ['borderStyle' => $THIN]],
			]);
			$ws->getRowDimension($row)->setRowHeight($ht);
		};

		// Column header row
		$colHdr = function ($row, array $labels) use ($ws, $SOLID, $THIN, $CALIGN, $C_COLHDR) {
			foreach ($labels as $i => $lbl) {
				$ws->setCellValueByColumnAndRow($i + 1, $row, $lbl);
			}
			$ws->getStyle("A{$row}:H{$row}")->applyFromArray([
				'font' => ['bold' => true, 'size' => 9, 'name' => 'Arial', 'color' => ['rgb' => 'FFFFFF']],
				'fill' => ['fillType' => $SOLID, 'startColor' => ['rgb' => $C_COLHDR]],
				'alignment' => ['horizontal' => $CALIGN],
				'borders' => ['allBorders' => ['borderStyle' => $THIN]],
			]);
		};

		// Plain data row, right-align specified 1-based column indices
		$dataRow = function ($row, array $cells, $bg = null, $bold = false, array $rCols = []) use ($ws, $SOLID, $THIN, $numFmt, $RALIGN) {
			foreach ($cells as $i => $val) {
				$ws->setCellValueByColumnAndRow($i + 1, $row, $val);
			}
			$style = [
				'font' => ['name' => 'Arial', 'size' => 9, 'bold' => $bold],
				'borders' => ['allBorders' => ['borderStyle' => $THIN]],
			];
			if ($bg)
				$style['fill'] = ['fillType' => $SOLID, 'startColor' => ['rgb' => $bg]];
			$ws->getStyle("A{$row}:H{$row}")->applyFromArray($style);
			foreach ($rCols as $c) {
				$ws->getCellByColumnAndRow($c, $row)->getStyle()->getAlignment()->setHorizontal($RALIGN);
				$ws->getCellByColumnAndRow($c, $row)->getStyle()->getNumberFormat()->setFormatCode($numFmt);
			}
		};

		// Total / sub-total row  (label merges A–mergeEnd, amount in col H)
		$totRow = function ($row, $label, $amount, $bg, $fg = '000000', $mergeEnd = 'G', $sz = 9, $ht = 15) use ($ws, $SOLID, $THIN, $numFmt, $RALIGN) {
			$ws->mergeCells("A{$row}:{$mergeEnd}{$row}");
			$ws->setCellValue("A{$row}", $label);
			$ws->setCellValueByColumnAndRow(8, $row, $amount);
			$ws->getStyle("A{$row}:H{$row}")->applyFromArray([
				'font' => ['bold' => true, 'size' => $sz, 'name' => 'Arial', 'color' => ['rgb' => $fg]],
				'fill' => ['fillType' => $SOLID, 'startColor' => ['rgb' => $bg]],
				'borders' => ['allBorders' => ['borderStyle' => $THIN]],
			]);
			$ws->getStyle("A{$row}")->getAlignment()->setHorizontal($RALIGN);
			$ws->getStyle("H{$row}")->getAlignment()->setHorizontal($RALIGN);
			$ws->getStyle("H{$row}")->getNumberFormat()->setFormatCode($numFmt);
			$ws->getRowDimension($row)->setRowHeight($ht);
		};

		// "No records" placeholder
		$noData = function ($row) use ($ws, $THIN) {
			$ws->mergeCells("A{$row}:H{$row}");
			$ws->setCellValue("A{$row}", 'No records found for this period.');
			$ws->getStyle("A{$row}:H{$row}")->applyFromArray([
				'font' => ['italic' => true, 'name' => 'Arial', 'size' => 9, 'color' => ['rgb' => '888888']],
				'borders' => ['allBorders' => ['borderStyle' => $THIN]],
			]);
		};

		// Payment mode label normaliser
		$pmLabel = function ($mode) {
			$map = [
				'cash' => 'CASH',
				'qr' => 'QR PAYMENT',
				'ipay_merch_qr' => 'QR PAYMENT',
				'online' => 'ONLINE PAYMENT',
				'ipay_merch_online' => 'ONLINE PAYMENT',
				'nets_pay' => 'NETS',
				'pay_now' => 'PAY NOW',
				'cheque' => 'CHEQUE',
				'transfer' => 'BANK TRANSFER',
			];
			$k = strtolower(trim((string) $mode));
			return $map[$k] ?? strtoupper(str_replace('_', ' ', (string) $mode));
		};

		// ── Running totals ─────────────────────────────────────────────
		$grand_income = 0;
		$grand_expense = 0;
		$pm_totals = []; // payment-mode breakdown for summary

		$row = 1;

		// ════════════════════════════════════════════════════════════════
		// REPORT HEADER  (rows 1–4)
		// ════════════════════════════════════════════════════════════════
		$banner($row, 'DAILY CLOSING REPORT', $C_TITLE, 'FFFFFF', 14, true, 26);
		$row++;
		$banner(
			$row,
			'Period : ' . $dailyclosing_start_date . '  to  ' . $dailyclosing_end_date
			. (!empty($booking_type) ? '     |     Booking Type : ' . strtoupper($booking_type) : '     |     All Booking Types'),
			$C_SUBTITLE,
			'FFFFFF',
			10,
			false,
			16
		);
		$row++;
		$banner($row, 'Generated : ' . date('d-m-Y  H:i:s'), 'EEEEEE', '555555', 9, false, 13);
		$row++;
		$row++; // blank gap

		// ════════════════════════════════════════════════════════════════
		// SECTION 1 – ARCHANAI
		// ════════════════════════════════════════════════════════════════
		$banner($row, '1.  ARCHANAI DETAILS', $C_SEC, 'FFFFFF', 11, true, 20);
		$row++;
		$colHdr($row, ['S.No', 'Archanai Name', 'Pay Mode', 'Paid Through', 'Date', 'Pay Type', 'Qty', 'Amount (RM)']);
		$row++;

		$archanai_total = 0;
		$archanai_discount = 0;
		$sno = 1;

		foreach ($archanai_group_details as $grp) {
			if (empty($grp['deities']))
				continue;

			// Group header
			$banner($row, '— ' . strtoupper($grp['title']) . ' —', $C_GRP, 'FFFFFF', 10, true, 16);
			$row++;

			$grp_total = 0;
			$grp_qty = 0;

			foreach ($grp['deities'] as $deity) {
				// Deity sub-header
				$ws->mergeCells("A{$row}:H{$row}");
				$ws->setCellValue("A{$row}", strtoupper($deity['deity_name']));
				$ws->getStyle("A{$row}:H{$row}")->applyFromArray([
					'font' => ['bold' => true, 'italic' => true, 'name' => 'Arial', 'size' => 9],
					'fill' => ['fillType' => $SOLID, 'startColor' => ['rgb' => $C_DEITY]],
					'alignment' => ['horizontal' => $CALIGN],
					'borders' => ['allBorders' => ['borderStyle' => $THIN]],
				]);
				$row++;

				foreach ($deity['products'] as $p) {
					$amt = (float) $p['amount'];
					$dis = (float) ($p['discount_amount'] ?? 0);
					$dataRow($row, [
						$sno,
						$p['name_in_english'] . ' / ' . $p['name_in_tamil'],
						strtoupper($p['paymentmode']),
						strtoupper($p['paid_through']),
						'',
						'',
						(int) $p['qty'],
						$amt,
					], null, false, [7, 8]);

					$archanai_total += $amt;
					$archanai_discount += $dis;
					$grp_total += $amt;
					$grp_qty += (int) $p['qty'];

					$pmk = strtolower(trim((string) $p['paymentmode']));
					$pm_totals[$pmk] = ($pm_totals[$pmk] ?? 0) + $amt;

					$sno++;
					$row++;
				}
			}

			// Group sub-total (qty in col G, amount in col H)
			$ws->mergeCells("A{$row}:F{$row}");
			$ws->setCellValue("A{$row}", strtoupper($grp['title']) . ' Sub Total');
			$ws->setCellValueByColumnAndRow(7, $row, $grp_qty);
			$ws->setCellValueByColumnAndRow(8, $row, $grp_total);
			$ws->getStyle("A{$row}:H{$row}")->applyFromArray([
				'font' => ['bold' => true, 'name' => 'Arial', 'size' => 9],
				'fill' => ['fillType' => $SOLID, 'startColor' => ['rgb' => $C_SUB]],
				'borders' => ['allBorders' => ['borderStyle' => $THIN]],
			]);
			$ws->getStyle("A{$row}")->getAlignment()->setHorizontal($RALIGN);
			$ws->getStyle("G{$row}")->getAlignment()->setHorizontal($RALIGN);
			$ws->getStyle("H{$row}")->getAlignment()->setHorizontal($RALIGN);
			$ws->getStyle("H{$row}")->getNumberFormat()->setFormatCode($numFmt);
			$row++;
		}

		if (empty($archanai_group_details)) {
			$noData($row);
			$row++;
		}

		$archanai_final = $archanai_total - $archanai_discount;
		$totRow($row, 'Archanai Sales Sub Total', $archanai_total, $C_SECTOT);
		$row++;
		$totRow($row, 'Archanai Discount Total', -$archanai_discount, $C_SECTOT);
		$row++;
		$totRow($row, 'ARCHANAI GRAND TOTAL', $archanai_final, $C_SEC, 'FFFFFF', 'G', 10, 18);
		$row++;
		$grand_income += $archanai_final;
		$row++; // gap

		// ════════════════════════════════════════════════════════════════
		// SECTION 2 – HALL BOOKING
		// ════════════════════════════════════════════════════════════════
		$banner($row, '2.  HALL BOOKING DETAILS', $C_SEC, 'FFFFFF', 11, true, 20);
		$row++;
		$colHdr($row, ['S.No', 'Customer Name', 'Pay Mode', 'Booked Through', 'Event Date', 'Pay Type', 'Total Amt (RM)', 'Paid Amt (RM)']);
		$row++;

		$hb_total = 0;
		$sno = 1;
		foreach ($hallbooking_details as $hb) {
			$paid = (float) $hb['paidamount'];
			$dataRow($row, [
				$sno,
				$hb['customer_name'],
				$pmLabel($hb['paymentmode']),
				($hb['booking_through'] == 'DIRECT') ? 'ADMIN' : 'COUNTER',
				$hb['date'],
				$hb['payment_type'],
				(float) $hb['amount'],
				$paid,
			], null, false, [7, 8]);
			$hb_total += $paid;
			$pmk = strtolower(trim((string) $hb['paymentmode']));
			$pm_totals[$pmk] = ($pm_totals[$pmk] ?? 0) + $paid;
			$sno++;
			$row++;
		}
		if (empty($hallbooking_details)) {
			$noData($row);
			$row++;
		}
		$totRow($row, 'HALL BOOKING TOTAL', $hb_total, $C_SEC, 'FFFFFF', 'G', 10, 18);
		$row++;
		$grand_income += $hb_total;
		$row++;

		// ════════════════════════════════════════════════════════════════
		// SECTION 3 – UBAYAM
		// ════════════════════════════════════════════════════════════════
		$banner($row, '3.  UBAYAM DETAILS', $C_SEC, 'FFFFFF', 11, true, 20);
		$row++;
		$colHdr($row, ['S.No', 'Customer Name', 'Pay Mode', 'Paid Through', 'Event Date', 'Pay Type', 'Total Amt (RM)', 'Paid Amt (RM)']);
		$row++;

		$ub_total = 0;
		$sno = 1;
		foreach ($ubayam_details as $ub) {
			$paid = (float) $ub['paidamount'];
			$dataRow($row, [
				$sno,
				$ub['customer_name'],
				$pmLabel($ub['paymentmode']),
				$ub['booking_through'],
				$ub['date'],
				$ub['payment_type'],
				(float) $ub['amount'],
				$paid,
			], null, false, [7, 8]);
			$ub_total += $paid;
			$pmk = strtolower(trim((string) $ub['paymentmode']));
			$pm_totals[$pmk] = ($pm_totals[$pmk] ?? 0) + $paid;
			$sno++;
			$row++;
		}
		if (empty($ubayam_details)) {
			$noData($row);
			$row++;
		}
		$totRow($row, 'UBAYAM TOTAL', $ub_total, $C_SEC, 'FFFFFF', 'G', 10, 18);
		$row++;
		$grand_income += $ub_total;
		$row++;

		// ════════════════════════════════════════════════════════════════
		// SECTION 4 – DONATION
		// ════════════════════════════════════════════════════════════════
		$banner($row, '4.  DONATION DETAILS', $C_SEC, 'FFFFFF', 11, true, 20);
		$row++;
		$colHdr($row, ['S.No', 'Donor Name', 'Pay Mode', 'Paid Through', 'Package', 'Date', '', 'Amount (RM)']);
		$row++;

		$dn_total = 0;
		$sno = 1;
		foreach ($donation_details as $dn) {
			$paid = (float) $dn['paidamount'];
			$dataRow($row, [
				$sno,
				$dn['person_name'],
				$pmLabel($dn['paymentmode']),
				$dn['paid_through'],
				$dn['package_name'] ?? '',
				'',
				'',
				$paid,
			], null, false, [8]);
			$dn_total += $paid;
			$pmk = strtolower(trim((string) $dn['paymentmode']));
			$pm_totals[$pmk] = ($pm_totals[$pmk] ?? 0) + $paid;
			$sno++;
			$row++;
		}
		if (empty($donation_details)) {
			$noData($row);
			$row++;
		}
		$totRow($row, 'DONATION TOTAL', $dn_total, $C_SEC, 'FFFFFF', 'G', 10, 18);
		$row++;
		$grand_income += $dn_total;
		$row++;

		// ════════════════════════════════════════════════════════════════
		// SECTION 5 – PRASADAM
		// ════════════════════════════════════════════════════════════════
		$banner($row, '5.  PRASADAM DETAILS', $C_SEC, 'FFFFFF', 11, true, 20);
		$row++;
		$colHdr($row, ['S.No', 'Customer Name', 'Pay Mode', 'Paid Through', 'Pay Type', 'Date', 'Total Amt (RM)', 'Paid Amt (RM)']);
		$row++;

		$ps_total = 0;
		$sno = 1;
		foreach ($prasadam_details as $ps) {
			// NULL fix: paidamount subquery returns NULL for DIRECT bookings
			$paid = ($ps['paidamount'] !== null && $ps['paidamount'] !== '')
				? (float) $ps['paidamount'] : (float) $ps['amount'];
			$totalamt = (float) $ps['amount'];
			$dataRow($row, [
				$sno,
				$ps['customer_name'],
				$pmLabel($ps['paymentmode'] ?? ''),
				$ps['paid_through'],
				$ps['payment_type'],
				'',
				$totalamt,
				$paid,
			], null, false, [7, 8]);
			$ps_total += $paid;
			$pmk = strtolower(trim((string) ($ps['paymentmode'] ?? '')));
			$pm_totals[$pmk] = ($pm_totals[$pmk] ?? 0) + $paid;
			$sno++;
			$row++;
		}
		if (empty($prasadam_details)) {
			$noData($row);
			$row++;
		}
		$totRow($row, 'PRASADAM TOTAL', $ps_total, $C_SEC, 'FFFFFF', 'G', 10, 18);
		$row++;
		$grand_income += $ps_total;
		$row++;

		// ════════════════════════════════════════════════════════════════
		// SECTION 6 – ANNATHANAM
		// ════════════════════════════════════════════════════════════════
		$banner($row, '6.  ANNATHANAM DETAILS', $C_SEC, 'FFFFFF', 11, true, 20);
		$row++;
		$colHdr($row, ['S.No', 'Customer Name', 'Pay Mode', 'Paid Through', 'Time Slot', 'Pay Type', 'Total Amt (RM)', 'Paid Amt (RM)']);
		$row++;

		$an_total = 0;
		$sno = 1;
		foreach ($annathanam_details as $an) {
			$paid = (float) $an['paidamount'];
			$dataRow($row, [
				$sno,
				$an['customer_name'],
				$pmLabel($an['paymentmode']),
				$an['booking_through'],
				$an['time'],
				$an['payment_type'],
				(float) $an['amount'],
				$paid,
			], null, false, [7, 8]);
			$an_total += $paid;
			$pmk = strtolower(trim((string) $an['paymentmode']));
			$pm_totals[$pmk] = ($pm_totals[$pmk] ?? 0) + $paid;
			$sno++;
			$row++;
		}
		if (empty($annathanam_details)) {
			$noData($row);
			$row++;
		}
		$totRow($row, 'ANNATHANAM TOTAL', $an_total, $C_SEC, 'FFFFFF', 'G', 10, 18);
		$row++;
		$grand_income += $an_total;
		$row++;

		// ════════════════════════════════════════════════════════════════
		// SECTION 7 – REPAYMENT
		// ════════════════════════════════════════════════════════════════
		$banner($row, '7.  REPAYMENT DETAILS', $C_SEC, 'FFFFFF', 11, true, 20);
		$row++;
		$colHdr($row, ['S.No', 'Customer Name', 'Pay Mode', 'Paid Through', 'Type', 'Booked Date', 'Booking Amt (RM)', 'Repaid Amt (RM)']);
		$row++;

		$rp_total = 0;
		$sno = 1;
		foreach ($repayment_details as $rp) {
			$repaid = (float) $rp['repaid_amount'];
			$dataRow($row, [
				$sno,
				$rp['customer_name'],
				$pmLabel($rp['paymentmode']),
				$rp['paid_through'],
				$rp['type'],
				$rp['date'],
				(float) $rp['amount'],
				$repaid,
			], null, false, [7, 8]);
			$rp_total += $repaid;
			$pmk = strtolower(trim((string) $rp['paymentmode']));
			$pm_totals[$pmk] = ($pm_totals[$pmk] ?? 0) + $repaid;
			$sno++;
			$row++;
		}
		if (empty($repayment_details)) {
			$noData($row);
			$row++;
		}
		$totRow($row, 'REPAYMENT TOTAL', $rp_total, $C_SEC, 'FFFFFF', 'G', 10, 18);
		$row++;
		$grand_income += $rp_total;
		$row++;

		// ════════════════════════════════════════════════════════════════
		// INCOME SUB TOTAL  ▓▓▓
		// ════════════════════════════════════════════════════════════════
		$totRow($row, 'INCOME SUB TOTAL', $grand_income, $C_INC_TOTAL, 'FFFFFF', 'G', 12, 22);
		$ws->getStyle("A{$row}:H{$row}")->getBorders()->getOutline()
			->setBorderStyle($MEDIUM);
		$row += 2;

		// ════════════════════════════════════════════════════════════════
		// SECTION 8 – PAYMENT VOUCHER (EXPENSES)
		// ════════════════════════════════════════════════════════════════
		$banner($row, '8.  PAYMENT VOUCHER  (EXPENSES)', $C_SEC_EXP, 'FFFFFF', 11, true, 20);
		$row++;
		$colHdr($row, ['S.No', 'Paid To', 'Pay Mode', 'Paid Through', 'Remarks', '', '', 'Amount (RM)']);
		$ws->getStyle("A{$row}:H{$row}")->getFill()->setFillType($SOLID);
		$ws->getStyle("A{$row}:H{$row}")->getFill()->getStartColor()->setRGB('C62828');
		$row++;

		$pv_total = 0;
		$sno = 1;
		foreach ($payment_voucher_details as $pv) {
			$amt = (float) $pv['paidamount'];
			$dataRow($row, [
				$sno,
				strtoupper($pv['booking_id']),
				strtoupper($pv['paymentmode']),
				$pv['paid_through'] ?? '',
				strtoupper($pv['details']),
				'',
				'',
				$amt,
			], 'FFEBEE', false, [8]);
			$pv_total += $amt;
			$grand_expense += $amt;
			$sno++;
			$row++;
		}
		if (empty($payment_voucher_details)) {
			$noData($row);
			$row++;
		}
		$totRow($row, 'PAYMENT VOUCHER TOTAL  (EXPENSES)', $pv_total, $C_SEC_EXP, 'FFFFFF', 'G', 10, 18);
		$row++;
		$row++;

		// ════════════════════════════════════════════════════════════════
		// EXPENSE SUB TOTAL  ▓▓▓
		// ════════════════════════════════════════════════════════════════
		$totRow($row, 'EXPENSE SUB TOTAL', $grand_expense, $C_EXP_TOTAL, 'FFFFFF', 'G', 12, 22);
		$ws->getStyle("A{$row}:H{$row}")->getBorders()->getOutline()
			->setBorderStyle($MEDIUM);
		$row += 2;

		// ════════════════════════════════════════════════════════════════
		// SUMMARY TABLE
		// ════════════════════════════════════════════════════════════════
		$banner($row, 'SUMMARY', $C_SUM_HDR, 'FFFFFF', 11, true, 20);
		$row++;

		// Summary header
		$ws->mergeCells("A{$row}:G{$row}");
		$ws->setCellValue("A{$row}", 'Module / Description');
		$ws->setCellValueByColumnAndRow(8, $row, 'Amount (RM)');
		$ws->getStyle("A{$row}:H{$row}")->applyFromArray([
			'font' => ['bold' => true, 'size' => 9, 'name' => 'Arial', 'color' => ['rgb' => 'FFFFFF']],
			'fill' => ['fillType' => $SOLID, 'startColor' => ['rgb' => $C_SUM_HDR]],
			'alignment' => ['horizontal' => $CALIGN],
			'borders' => ['allBorders' => ['borderStyle' => $THIN]],
		]);
		$row++;

		// Per-module rows
		$summaryItems = [
			['Archanai Grand Total', $archanai_final],
			['Hall Booking Total', $hb_total],
			['Ubayam Total', $ub_total],
			['Donation Total', $dn_total],
			['Prasadam Total', $ps_total],
			['Annathanam Total', $an_total],
			['Repayment Total', $rp_total],
		];
		foreach ($summaryItems as $si) {
			$ws->mergeCells("A{$row}:G{$row}");
			$ws->setCellValue("A{$row}", $si[0]);
			$ws->setCellValueByColumnAndRow(8, $row, $si[1]);
			$ws->getStyle("A{$row}:H{$row}")->applyFromArray([
				'font' => ['name' => 'Arial', 'size' => 9],
				'fill' => ['fillType' => $SOLID, 'startColor' => ['rgb' => $C_SUM_ROW]],
				'borders' => ['allBorders' => ['borderStyle' => $THIN]],
			]);
			$ws->getStyle("A{$row}")->getAlignment()->setHorizontal($RALIGN);
			$ws->getStyle("H{$row}")->getAlignment()->setHorizontal($RALIGN);
			$ws->getStyle("H{$row}")->getNumberFormat()->setFormatCode($numFmt);
			$row++;
		}

		// Income sub total in summary
		$ws->mergeCells("A{$row}:G{$row}");
		$ws->setCellValue("A{$row}", 'Income Sub Total');
		$ws->setCellValueByColumnAndRow(8, $row, $grand_income);
		$ws->getStyle("A{$row}:H{$row}")->applyFromArray([
			'font' => ['bold' => true, 'size' => 10, 'name' => 'Arial'],
			'fill' => ['fillType' => $SOLID, 'startColor' => ['rgb' => $C_SUM_INC]],
			'borders' => ['allBorders' => ['borderStyle' => $THIN]],
		]);
		$ws->getStyle("A{$row}")->getAlignment()->setHorizontal($RALIGN);
		$ws->getStyle("H{$row}")->getAlignment()->setHorizontal($RALIGN);
		$ws->getStyle("H{$row}")->getNumberFormat()->setFormatCode($numFmt);
		$row++;

		// Expense row in summary
		$ws->mergeCells("A{$row}:G{$row}");
		$ws->setCellValue("A{$row}", 'Payment Voucher (Expenses)');
		$ws->setCellValueByColumnAndRow(8, $row, -$grand_expense);
		$ws->getStyle("A{$row}:H{$row}")->applyFromArray([
			'font' => ['bold' => true, 'size' => 10, 'name' => 'Arial', 'color' => ['rgb' => 'B71C1C']],
			'fill' => ['fillType' => $SOLID, 'startColor' => ['rgb' => $C_SUM_EXP]],
			'borders' => ['allBorders' => ['borderStyle' => $THIN]],
		]);
		$ws->getStyle("A{$row}")->getAlignment()->setHorizontal($RALIGN);
		$ws->getStyle("H{$row}")->getAlignment()->setHorizontal($RALIGN);
		$ws->getStyle("H{$row}")->getNumberFormat()->setFormatCode($numFmt);
		$row += 2;

		// Payment mode breakdown
		$banner($row, 'Income by Payment Mode', $C_SUM_HDR, 'FFFFFF', 10, true, 16);
		$row++;
		foreach ($pm_totals as $pmk => $amt) {
			if ($amt == 0 || empty($pmk))
				continue;
			$ws->mergeCells("A{$row}:G{$row}");
			$ws->setCellValue("A{$row}", $pmLabel($pmk));
			$ws->setCellValueByColumnAndRow(8, $row, $amt);
			$ws->getStyle("A{$row}:H{$row}")->applyFromArray([
				'font' => ['name' => 'Arial', 'size' => 9],
				'fill' => ['fillType' => $SOLID, 'startColor' => ['rgb' => $C_SUM_ROW]],
				'borders' => ['allBorders' => ['borderStyle' => $THIN]],
			]);
			$ws->getStyle("A{$row}")->getAlignment()->setHorizontal($RALIGN);
			$ws->getStyle("H{$row}")->getAlignment()->setHorizontal($RALIGN);
			$ws->getStyle("H{$row}")->getNumberFormat()->setFormatCode($numFmt);
			$row++;
		}
		$row++;

		// ════════════════════════════════════════════════════════════════
		// GRAND TOTAL  ★
		// ════════════════════════════════════════════════════════════════
		$grand_total = $grand_income - $grand_expense;
		$ws->mergeCells("A{$row}:G{$row}");
		$ws->setCellValue("A{$row}", 'GRAND TOTAL   (Income − Expenses)');
		$ws->setCellValueByColumnAndRow(8, $row, $grand_total);
		$ws->getStyle("A{$row}:H{$row}")->applyFromArray([
			'font' => ['bold' => true, 'size' => 13, 'name' => 'Arial'],
			'fill' => ['fillType' => $SOLID, 'startColor' => ['rgb' => $C_GRAND]],
			'borders' => [
				'allBorders' => ['borderStyle' => $THIN],
				'outline' => ['borderStyle' => $MEDIUM],
			],
		]);
		$ws->getStyle("A{$row}")->getAlignment()->setHorizontal($RALIGN);
		$ws->getStyle("H{$row}")->getAlignment()->setHorizontal($RALIGN);
		$ws->getStyle("H{$row}")->getNumberFormat()->setFormatCode($numFmt);
		$ws->getRowDimension($row)->setRowHeight(24);

		// ── Freeze header rows, output ─────────────────────────────────
		$ws->freezePane('A5');
		$spreadsheet->setActiveSheetIndex(0);

		$filename = 'DailyClosing_' . $dailyclosing_start_date . '_to_' . $dailyclosing_end_date . '.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}
}
