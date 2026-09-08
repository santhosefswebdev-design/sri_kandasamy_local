<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\RequestModel;
use App\Models\Common_model;

class Prasadam_online extends BaseController
{
  function __construct()
  {
    parent::__construct();
    helper('url');
    helper('common');
    $request = service('request');
    $this->model = new PermissionModel();
    $this->common_model = new Common_model();
    if (($this->session->get('log_id_frend')) == false) {
      if ($request->isAJAX()) {
        echo json_encode([
          "session_expired" => true,
          "status" => 200
        ]);
        exit;
      }

      $data['dn_msg'] = 'Please Login';
      header('Location: ' . base_url() . '/member_login');
      exit;
    }
  }


  // function __construct()
  // {
  //   parent::__construct();
  //   helper('url');
  //   helper('common');
  //   $request = service('request');
  //   $this->model = new PermissionModel();
  //   $this->common_model = new Common_model();

  //   // Get current method name
  //   $router = service('router');
  //   $current_method = $router->methodName();

  //   // Skip authentication for cron methods
  //   $cron_methods = ['send_prasadam_reminders_cron', 'send_prasadam_thankyou_cron', 'test_cron_connection'];

  //   if (!in_array($current_method, $cron_methods)) {
  //     // Only check login for non-cron methods
  //     if (($this->session->get('log_id_frend')) == false) {
  //       if ($request->isAJAX()) {
  //         echo json_encode([
  //           "session_expired" => true,
  //           "status" => 200
  //         ]);
  //         exit;
  //       }

  //       $data['dn_msg'] = 'Please Login';
  //       header('Location: ' . base_url() . '/member_login');
  //       exit;
  //     }
  //   }
  // }

  public function index()
  {

    $login_id = $_SESSION['log_id_frend'];
    $default_group = $this->db->query("SELECT * FROM prasadam_group order by id asc limit 1")->getRowArray();
    $data['default'] = str_replace(' ', '_', strtolower($default_group['name']));
    $data['payment_mode'] = $this->db->table('payment_mode')->where("paid_through", "COUNTER")->where("prasadam", 1)->where('status', 1)->get()->getResultArray();
    $login_eghl_terminal = $this->db->table('login')->select('eghl_terminal_id')->where('id', $login_id)->get()->getRowArray();
    if (empty($login_eghl_terminal['eghl_terminal_id'])) {
      $data['payment_mode'] = array_values(array_filter($data['payment_mode'], function ($pm) {
        return $pm['pay_key'] !== 'eghl_qr';
      }));
    }
    $data['phone_codes'] = $this->db->table("phone_code")->orderBy('dailing_code', 'ASC')->get()->getResultArray();
    $data['prasadam_settings'] = $this->db->query("SELECT * FROM prasadam_setting WHERE ledger_id != '' AND ledger_id IS NOT NULL ORDER BY order_no ASC")->getResultArray();

    $data['reprintlists'] = $this->db->query("SELECT id,customer_name,total_amount,name,date FROM prasadam WHERE added_by = '" . $login_id . "' and paid_through = 'COUNTER' AND payment_status = 2 ORDER BY id DESC LIMIT 3")->getResultArray();

    // Add name suggestions for prasadam customers
    $data['prasadam_names'] = $this->db->query("SELECT DISTINCT customer_name as name FROM prasadam WHERE customer_name IS NOT NULL AND customer_name != '' ORDER BY customer_name ASC")->getResultArray();

    // $pras_sett = $this->db->table('prasadam_setting')->where("shortcode is not null and shortcode != ''")->get()->getResultArray();
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
    $group_order = [];
    $sorted_data = [];
    if (isset($data['sett_data'])) {
      foreach ($data['sett_data'] as $group_name => $settings) {
        $group_details = $this->db->table('prasadam_group')->where('name', $group_name)->get()->getRowArray();
        if ($group_details) {
          $group_order[$group_name] = $group_details['order_no'];
        }
      }
      asort($group_order);

      foreach ($group_order as $group_name => $order_no) {
        $sorted_data[$group_name] = $data['sett_data'][$group_name];
      }
    }
    $data['sett_data'] = $sorted_data;

    $data['dieties'] = $this->db->table('archanai_diety')->get()->getResultArray();

    $settings = $this->db->table('settings')->where('type', 3)->get()->getResultArray();
    $setting_array = array();
    if (count($settings) > 0) {
      foreach ($settings as $item) {
        $setting_array[$item['setting_name']] = $item['setting_value'];
      }
    }
    $data['setting'] = $setting_array;
    // echo '<pre>';
    // print_r($data['sett_data']);
    // exit;
    echo view('frontend/layout/header');
    echo view('frontend/prasadam/index', $data);
  }
  // public function save()
  // {
  //   // echo '<pre>';
  //   // print_r( $_POST); 
  //   // exit;
  //   $msg_data = array();
  //   $msg_data['err'] = '';
  //   $msg_data['succ'] = '';
  //   $this->db->transStart();
  //   try {
  //     $pay_id = $_POST['pay_method'];
  //     $payment_mode = $this->db->table('payment_mode')->where("id", $pay_id)->get()->getRowArray();
  //     $pay_method = $payment_mode['name'];
  //     $is_payment_gateway = $payment_mode['is_payment_gateway'];
  //     $payment_key = $payment_mode['pay_key'];
  //     $pay_code = !empty($payment_mode['shortcode']) ? substr($payment_mode['shortcode'], 0, 2) : 'PY';
  //     $data['date'] = $date = $_POST['date'];

  //     $yr = Date("Y");
  //     $mon = Date("m");
  //     $archanai_code = 'PR';
  //     $counter_code = 'CT';
  //     $ref_no_code = $archanai_code . $counter_code . $pay_code;

  //     $query = $this->db->query("SELECT ref_no FROM prasadam where id=(select max(id) from prasadam where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();
  //     //$data['ref_no'] = 'PR' . date('y') . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
  //     if (!empty($query['ref_no']))
  //       $data['ref_no'] = $ref_no_code . $yr . $mon . (sprintf("%06d", (((float) substr($query['ref_no'], -6)) + 1)));
  //     else
  //       $data['ref_no'] = $ref_no_code . $yr . $mon . sprintf("%06d", 1);

  //     $data["ref_code"] = $ref_no_code;
  //     $tot_amt = !empty($_POST['tot_amt']) ? (float) $_POST['tot_amt'] : 0;
  //     $data['date'] = date('Y-m-d');
  //     $data['customer_name'] = $_POST['name'];
  //     $data['diety_id'] = isset($_POST['diety_id']) ? $_POST['diety_id'] : null;

  //     $data['email_id'] = $_POST['email_id'];
  //     $data['ic_no'] = $_POST['ic_number'];
  //     $data['address'] = $_POST['address'];
  //     $data['desciption'] = $_POST['description'];
  //     $sub_total = $tot_amt;
  //     if (!empty($_POST['discount_amount'])) {
  //       $data['discount_amount'] = $_POST['discount_amount'];
  //       $sub_total += $_POST['discount_amount'];
  //     }
  //     $data['sub_total'] = $sub_total;
  //     $data['total_amount'] = $tot_amt;
  //     $data['session'] = $_POST['time'];
  //     $data['collection_date'] = $_POST['collection_date'];
  //     $time_session = ($data['session'] == 'Breakfast') ? "AM" : "PM";
  //     $data['serve_time'] = $_POST['hour'] . ':' . $_POST['minute'] . ' ' . $time_session;
  //     // if (!empty($_POST['s_time'])) {
  //     //   $data['start_time'] = trim($_POST['s_time']);
  //     //   list($hours, $minutes) = explode(":", $data['start_time']);
  //     //   if ($hours > 11)
  //     //     $data['collection_session'] = 'PM';
  //     //   else
  //     //     $data['collection_session'] = 'AM';
  //     // } elseif (!empty($_POST['c_session'])) {
  //     //   $data['collection_session'] = $_POST['c_session'];
  //     // }
  //     $mble_phonecode = !empty($_POST['phonecode']) ? $_POST['phonecode'] : "";
  //     $mble_number = !empty($_POST['mobile']) ? $_POST['mobile'] : "";
  //     $data['mobile_no'] = $mble_phonecode . $mble_number;

  //     $data['added_by'] = $this->session->get('log_id_frend');
  //     $data['sep_print'] = (!empty($_REQUEST['sep_print']) ? $_REQUEST['sep_print'] : 0);
  //     $data['paid_through'] = "COUNTER";
  //     $data['payment_type'] = !empty($_POST['payment_type']) ? $_POST['payment_type'] : 'full';
  //     $data['payment_mode'] = $pay_id = $_POST['pay_method'];
  //     $data['prasadam_notes'] = !empty($_POST['prasadam_notes']) ? $_POST['prasadam_notes'] : null;
  //     $payment_mode = $this->db->table('payment_mode')->where("id", $pay_id)->get()->getRowArray();
  //     $pay_method = $payment_mode['name'];
  //     $data['payment_status'] = empty($is_payment_gateway) ? 2 : 1;

  //     $data['created_at'] = date('Y-m-d H:i:s');
  //     $data['updated_at'] = date('Y-m-d H:i:s');

  //     $ip = 'unknown';
  //     $this->requestmodel = new RequestModel();
  //     $ip = $this->requestmodel->getIpAddress();
  //     if ($ip != 'unknown') {
  //       $ip_details = $this->requestmodel->getLocation($ip);
  //       $data['ip'] = $ip;
  //       $data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
  //       $data['ip_details'] = json_encode($ip_details);
  //     }
  //     $paid_amount = !empty($_POST['paid_amount']) ? (float) $_POST['paid_amount'] : 0;
  //     if (!empty($data['customer_name']) && !empty($data['mobile_no']) && !empty($_POST['prasadam'])) {
  //       if (($data['payment_type'] == 'partial' && !empty($paid_amount)) || $data['payment_type'] == 'full') {
  //         if ($data['payment_type'] == 'full')
  //           $paid_amount = $tot_amt;
  //         if ($paid_amount <= $tot_amt) {
  //           $data['paid_amount'] = $paid_amount;

  //           try {
  //             $res = $this->db->table('prasadam')->insert($data);
  //             if ($res) {
  //               $ins_id = $this->db->insertID();
  //               if (!empty($ins_id)) {
  //                 foreach ($_POST['prasadam'] as $prasadam) {
  //                   $data_prdm_book['prasadam_booking_id'] = $ins_id;
  //                   $data_prdm_book['group_id'] = $prasadam['group_id'];
  //                   $data_prdm_book['prasadam_id'] = $prasadam['id'];
  //                   $data_prdm_book['quantity'] = $prasadam['qty'];
  //                   $data_prdm_book['created'] = date('Y-m-d H:i:s');
  //                   $prsm_set = $this->db->table('prasadam_setting')->where('id', $prasadam['id'])->get()->getRowArray();
  //                   $data_prdm_book['amount'] = $prsm_set['amount'];
  //                   $amt = $prasadam['qty'] * $prsm_set['amount'];
  //                   $data_prdm_book['total_amount'] = $amt;

  //                   $whr_qr = " ref_code = '" . $ref_no_code . "' and prasadam_id = " . $data_prdm_book['prasadam_id'];
  //                   $query2 = $this->db->query("SELECT coalesce(max(sep_pras_sl_no_to), 0) + 1 as new_no FROM `prasadam_booking_details` join prasadam on prasadam.id=prasadam_booking_details.prasadam_booking_id where $whr_qr")->getRowArray();
  //                   $data_prdm_book['sep_pras_sl_no'] = $query2['new_no'];
  //                   $data_prdm_book['sep_pras_sl_no_to'] = $query2['new_no'] + ($data_prdm_book['quantity'] - 1);

  //                   $res_2 = $this->db->table('prasadam_booking_details')->insert($data_prdm_book);

  //                   $settings = $this->db->table('settings')->where('type', 3)->where('setting_name', 'enable_madapalli')->get()->getRowArray();

  //                   if ($settings['setting_value'] == 1) {
  //                     $madapalli_details['date'] = $_POST['collection_date'];
  //                     $madapalli_details['type'] = 1;
  //                     $madapalli_details['booking_id'] = $ins_id;
  //                     $madapalli_details['product_id'] = $prasadam['id'];
  //                     $madapalli_details['quantity'] = $prasadam['qty'];
  //                     $madapalli_details['amount'] = $amt;
  //                     $madapalli_details['session'] = $data['session'];
  //                     $madapalli_details['serve_time'] = $data['serve_time'];
  //                     $madapalli_details['customer_name'] = $_POST['name'];
  //                     $madapalli_details['customer_mobile'] = $mble_phonecode . $mble_number;
  //                     $madapalli_details['pro_name_eng'] = $prsm_set['name_eng'];
  //                     $madapalli_details['status'] = 0;
  //                     $madapalli_details['created_by'] = $this->session->get('log_id_frend');
  //                     $madapalli_details['created_at'] = date('Y-m-d H:i:s');
  //                     $madapalli_details['updated_at'] = date('Y-m-d H:i:s');
  //                     $res_m1 = $this->db->table('madapalli_booking_details')->insert($madapalli_details);

  //                     if ($res_m1) {
  //                       $preparation_details = $this->db->table('madapalli_preparation_details')->where('date', $_POST['collection_date'])->where('type', 1)->get()->getResultArray();
  //                       $product_found = false;

  //                       foreach ($preparation_details as $detail) {
  //                         if ($detail['product_id'] == $madapalli_details['product_id'] && $detail['session'] == $madapalli_details['session']) {
  //                           $new_quantity = $detail['quantity'] + $madapalli_details['quantity'];
  //                           $update_data = [
  //                             'quantity' => $new_quantity,
  //                             'updated_at' => date('Y-m-d H:i:s')
  //                           ];
  //                           $this->db->table('madapalli_preparation_details')->where('id', $detail['id'])->update($update_data);
  //                           $product_found = true;
  //                           break;
  //                         }
  //                       }

  //                       if (!$product_found) {
  //                         $insert_data = [
  //                           'date' => $_POST['collection_date'],
  //                           'type' => 1,
  //                           'session' => $data['session'],
  //                           'product_id' => $madapalli_details['product_id'],
  //                           'pro_name_eng' => $prsm_set['name_eng'],
  //                           'pro_name_tamil' => $prsm_set['name_tamil'],
  //                           'quantity' => $madapalli_details['quantity'],
  //                           'status' => 0,
  //                           'created_by' => $this->session->get('log_id_frend'),
  //                           'created_at' => date('Y-m-d H:i:s'),
  //                           'updated_at' => date('Y-m-d H:i:s')
  //                         ];
  //                         $this->db->table('madapalli_preparation_details')->insert($insert_data);
  //                       }
  //                     }
  //                   }
  //                 }

  //                 $payment_gateway_data = array();
  //                 $payment_gateway_data['prasadam_id'] = $ins_id;
  //                 $payment_gateway_data['pay_method'] = $pay_method;
  //                 $payment_gateway_data['payment_mode'] = $pay_id;
  //                 $this->db->table('prasadam_payment_gateway_datas')->insert($payment_gateway_data);
  //                 $prasadam_payment_gateway_id = $this->db->insertID();

  //                 if ($payment_key == 'rhb_qr') {
  //                   $ref_no = PAYMENT_PREFIX . '_PRAS_' . $ins_id;
  //                   if (PAYMENT_TEST) {
  //                     $pay_amount = 1;
  //                   } else {
  //                     $pay_amount = bcdiv($data['total_amount'], '1', 2);
  //                   }

  //                   $bill_num = (string) ($ins_id + 200000000000);
  //                   $url = 'https://dnqr.synexisasia.com/v1/qr';

  //                   $json_data = [
  //                     "merchantCode" => RHB_MERCHANTCODE,
  //                     "userId" => RHB_USERID,
  //                     "amount" => $pay_amount,
  //                     "billNumber" => $bill_num,
  //                     "transactionReference" => $ref_no,
  //                   ];

  //                   // Assuming common_repository->postJson() posts JSON and returns JSON string
  //                   $response = $this->common_model->postJson($url, $json_data);
  //                   $response = json_decode($response);

  //                   if (!empty($response->qrCode)) {
  //                     $payment_gateway_up_data = [
  //                       'request_data' => json_encode($json_data),
  //                     ];

  //                     $this->db->table('prasadam_payment_gateway_datas')
  //                       ->where('id', $prasadam_payment_gateway_id)
  //                       ->update($payment_gateway_up_data);

  //                     $msg_data['qr_code'] = $response->qrCode;
  //                     $msg_data['total_amount'] = bcdiv($data['total_amount'], '1', 2);
  //                   } else {
  //                     $msg_data = [
  //                       'err' => 'QR code not generated. Kindly rebook the ticket.',
  //                     ];
  //                   }
  //                   $msg_data['pay_status'] = false;
  //                   $msg_data['payment_key'] = $payment_key;
  //                 } else
  //                   $msg_data['pay_status'] = true;

  //                 $pay_details = array();
  //                 $payment_mode_details = $this->db->table("payment_mode")->where('id', $data['payment_mode'])->get()->getRowArray();
  //                 $pay_details['prasadam_id'] = $ins_id;
  //                 $pay_details['payment_mode_id'] = $data['payment_mode'];
  //                 $pay_details['is_repayment'] = 0;
  //                 $pay_details['paid_through'] = 'COUNTER';
  //                 $pay_details['pay_status'] = 2;
  //                 $pay_details['payment_mode_title'] = $payment_mode_details['name'];
  //                 $pay_details['booking_ref_no'] = $data['ref_no'];
  //                 $pay_details['amount'] = $paid_amount;
  //                 if (empty($pay_details['amount'])) {
  //                   $this->db->transRollback();
  //                   $msg_data['err'] = 'Invalid Amount';
  //                   echo json_encode($msg_data);
  //                   exit;
  //                 }
  //                 $pay_details['paid_date'] = date('Y-m-d');
  //                 $this->requestmodel = new RequestModel();
  //                 $ip = $this->requestmodel->getIpAddress();
  //                 $pay_details['ip'] = $ip;
  //                 if ($ip != 'unknown') {
  //                   $ip_details = $this->requestmodel->getLocation($ip);
  //                   $pay_details['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
  //                   $pay_details['ip_details'] = json_encode($ip_details);
  //                 }
  //                 $res_3 = $this->db->table('prasadam_booked_pay_details')->insert($pay_details);

  //                 $booking_ref_data = array();
  //                 $booking_ref_data['paid_amount'] = $pay_details['amount'];
  //                 $booking_ref_data['booking_status'] = 1;
  //                 $this->db->table("prasadam")->where('id', $ins_id)->update($booking_ref_data);
  //               }
  //               if ($data['payment_status'] == 2 || $data['payment_status'] == 1) {
  //                 $this->account_migration($ins_id);
  //                 // $this->send_whatsapp_msg($ins_id);
  //                 // $this->send_mail_to_customer($ins_id);
  //               }
  //               if ($res_2) {
  //                 $this->session->setFlashdata('succ', 'Prasadam Added Successflly');
  //                 $msg_data['succ'] = 'Prasadam Added Successflly';
  //                 $msg_data['id'] = $ins_id;
  //               } else {
  //                 $this->db->transRollback();
  //                 $this->session->setFlashdata('fail', 'Please Try Again');
  //                 $msg_data['err'] = 'Please Try Again';
  //               }
  //             } else {
  //               $this->db->transRollback();
  //               $msg_data['err'] = 'Please Try Again';
  //             }
  //           } catch (Exception $ex) {
  //             print_r($ex);
  //           }
  //         } else {
  //           $this->db->transRollback();
  //           $msg_data['err'] = 'Payment amount not greater than total amount';
  //         }
  //       } else {
  //         $this->db->transRollback();
  //         $msg_data['err'] = 'Invalid Paid Amount.';
  //       }
  //     } else {
  //       $this->db->transRollback();
  //       //$this->session->setFlashdata('fail', 'Please Try Again');
  //       $msg_data['err'] = 'Please Try Again. required user details.';
  //     }
  //     $this->db->transComplete();
  //   } catch (Exception $e) {
  //     $this->db->transRollback();
  //     $msg_data['err'] = $e->getMessage();
  //   }
  //   echo json_encode($msg_data);
  //   exit();
  // }




  public function save()
  {
    // echo '<pre>';
    // print_r( $_POST); 
    // exit;
    $msg_data = array();
    $msg_data['err'] = '';
    $msg_data['succ'] = '';
    $this->db->transStart();
    try {
      $pay_id = $_POST['pay_method'];
      $payment_mode = $this->db->table('payment_mode')->where("id", $pay_id)->get()->getRowArray();
      $pay_method = $payment_mode['name'];
      $is_payment_gateway = $payment_mode['is_payment_gateway'];
      $payment_key = $payment_mode['pay_key'];
      $pay_code = !empty($payment_mode['shortcode']) ? substr($payment_mode['shortcode'], 0, 2) : 'PY';
      $data['date'] = $date = $_POST['date'];

      $yr = Date("Y");
      $mon = Date("m");
      $archanai_code = 'PR';
      $counter_code = 'CT';
      $ref_no_code = $archanai_code . $counter_code . $pay_code;

      $query = $this->db->query("SELECT ref_no FROM prasadam where id=(select max(id) from prasadam where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();
      //$data['ref_no'] = 'PR' . date('y') . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
      if (!empty($query['ref_no']))
        $data['ref_no'] = $ref_no_code . $yr . $mon . (sprintf("%06d", (((float) substr($query['ref_no'], -6)) + 1)));
      else
        $data['ref_no'] = $ref_no_code . $yr . $mon . sprintf("%06d", 1);

      $data["ref_code"] = $ref_no_code;
      $tot_amt = !empty($_POST['tot_amt']) ? (float) $_POST['tot_amt'] : 0;
      $data['date'] = date('Y-m-d');
      $data['customer_name'] = $_POST['name'];
      $data['diety_id'] = isset($_POST['diety_id']) ? $_POST['diety_id'] : null;

      $data['email_id'] = $_POST['email_id'];
      $data['ic_no'] = $_POST['ic_number'];
      $data['address'] = $_POST['address'];
      $data['desciption'] = $_POST['description'];
      $sub_total = $tot_amt;
      if (!empty($_POST['discount_amount'])) {
        $data['discount_amount'] = $_POST['discount_amount'];
        $sub_total += $_POST['discount_amount'];
      }
      $data['sub_total'] = $sub_total;
      $data['total_amount'] = $tot_amt;
      $data['session'] = $_POST['time'];
      $data['collection_date'] = $_POST['collection_date'];
      $time_session = ($data['session'] == 'Breakfast') ? "AM" : "PM";
      $data['serve_time'] = $_POST['hour'] . ':' . $_POST['minute'] . ' ' . $time_session;

      $mble_phonecode = !empty($_POST['phonecode']) ? $_POST['phonecode'] : "";
      $mble_number = !empty($_POST['mobile']) ? $_POST['mobile'] : "";
      $data['mobile_no'] = $mble_phonecode . $mble_number;

      $data['added_by'] = $this->session->get('log_id_frend');
      $data['sep_print'] = (!empty($_REQUEST['sep_print']) ? $_REQUEST['sep_print'] : 0);
      $data['paid_through'] = "COUNTER";
      $data['payment_type'] = !empty($_POST['payment_type']) ? $_POST['payment_type'] : 'full';
      $data['payment_mode'] = $pay_id = $_POST['pay_method'];
      $data['prasadam_notes'] = !empty($_POST['prasadam_notes']) ? $_POST['prasadam_notes'] : null;
      $payment_mode = $this->db->table('payment_mode')->where("id", $pay_id)->get()->getRowArray();
      $pay_method = $payment_mode['name'];
      $data['payment_status'] = empty($is_payment_gateway) ? 2 : 1;

      $data['created_at'] = date('Y-m-d H:i:s');
      $data['updated_at'] = date('Y-m-d H:i:s');

      $ip = 'unknown';
      $this->requestmodel = new RequestModel();
      $ip = $this->requestmodel->getIpAddress();
      if ($ip != 'unknown') {
        $ip_details = $this->requestmodel->getLocation($ip);
        $data['ip'] = $ip;
        $data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
        $data['ip_details'] = json_encode($ip_details);
      }
      $paid_amount = !empty($_POST['paid_amount']) ? (float) $_POST['paid_amount'] : 0;
      if (!empty($data['customer_name']) && !empty($data['mobile_no']) && !empty($_POST['prasadam'])) {
        if (($data['payment_type'] == 'partial' && !empty($paid_amount)) || $data['payment_type'] == 'full') {
          if ($data['payment_type'] == 'full')
            $paid_amount = $tot_amt;
          if ($paid_amount <= $tot_amt) {
            $data['paid_amount'] = $paid_amount;

            try {
              $res = $this->db->table('prasadam')->insert($data);
              if ($res) {
                $ins_id = $this->db->insertID();
                if (!empty($ins_id)) {
                  foreach ($_POST['prasadam'] as $prasadam) {
                    $data_prdm_book['prasadam_booking_id'] = $ins_id;
                    $data_prdm_book['group_id'] = $prasadam['group_id'];
                    $data_prdm_book['prasadam_id'] = $prasadam['id'];
                    $data_prdm_book['quantity'] = $prasadam['qty'];
                    $data_prdm_book['created'] = date('Y-m-d H:i:s');
                    $prsm_set = $this->db->table('prasadam_setting')->where('id', $prasadam['id'])->get()->getRowArray();
                    $data_prdm_book['amount'] = $prsm_set['amount'];
                    $amt = $prasadam['qty'] * $prsm_set['amount'];
                    $data_prdm_book['total_amount'] = $amt;

                    $whr_qr = " ref_code = '" . $ref_no_code . "' and prasadam_id = " . $data_prdm_book['prasadam_id'];
                    $query2 = $this->db->query("SELECT coalesce(max(sep_pras_sl_no_to), 0) + 1 as new_no FROM `prasadam_booking_details` join prasadam on prasadam.id=prasadam_booking_details.prasadam_booking_id where $whr_qr")->getRowArray();
                    $data_prdm_book['sep_pras_sl_no'] = $query2['new_no'];
                    $data_prdm_book['sep_pras_sl_no_to'] = $query2['new_no'] + ($data_prdm_book['quantity'] - 1);

                    $res_2 = $this->db->table('prasadam_booking_details')->insert($data_prdm_book);

                    $settings = $this->db->table('settings')->where('type', 3)->where('setting_name', 'enable_madapalli')->get()->getRowArray();

                    if ($settings['setting_value'] == 1) {
                      $madapalli_details['date'] = $_POST['collection_date'];
                      $madapalli_details['type'] = 1;
                      $madapalli_details['booking_id'] = $ins_id;
                      $madapalli_details['product_id'] = $prasadam['id'];
                      $madapalli_details['quantity'] = $prasadam['qty'];
                      $madapalli_details['amount'] = $amt;
                      $madapalli_details['session'] = $data['session'];
                      $madapalli_details['serve_time'] = $data['serve_time'];
                      $madapalli_details['customer_name'] = $_POST['name'];
                      $madapalli_details['customer_mobile'] = $mble_phonecode . $mble_number;
                      $madapalli_details['pro_name_eng'] = $prsm_set['name_eng'];
                      $madapalli_details['status'] = 0;
                      $madapalli_details['created_by'] = $this->session->get('log_id_frend');
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
                            'session' => $data['session'],
                            'product_id' => $madapalli_details['product_id'],
                            'pro_name_eng' => $prsm_set['name_eng'],
                            'pro_name_tamil' => $prsm_set['name_tamil'],
                            'quantity' => $madapalli_details['quantity'],
                            'status' => 0,
                            'created_by' => $this->session->get('log_id_frend'),
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                          ];
                          $this->db->table('madapalli_preparation_details')->insert($insert_data);
                        }
                      }
                    }
                  }

                  $payment_gateway_data = array();
                  $payment_gateway_data['prasadam_id'] = $ins_id;
                  $payment_gateway_data['pay_method'] = $pay_method;
                  $payment_gateway_data['payment_mode'] = $pay_id;
                  $this->db->table('prasadam_payment_gateway_datas')->insert($payment_gateway_data);
                  $prasadam_payment_gateway_id = $this->db->insertID();

                  if ($payment_key == 'rhb_qr') {
                    $ref_no = PAYMENT_PREFIX . '_PRAS_' . $ins_id;
                    if (PAYMENT_TEST) {
                      $pay_amount = 1;
                    } else {
                      $pay_amount = bcdiv($data['total_amount'], '1', 2);
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

                      $this->db->table('prasadam_payment_gateway_datas')
                        ->where('id', $prasadam_payment_gateway_id)
                        ->update($payment_gateway_up_data);

                      $msg_data['qr_code'] = $response->qrCode;
                      $msg_data['total_amount'] = bcdiv($data['total_amount'], '1', 2);
                    } else {
                      $msg_data = [
                        'err' => 'QR code not generated. Kindly rebook the ticket.',
                      ];
                    }
                    $msg_data['pay_status'] = false;
                    $msg_data['payment_key'] = $payment_key;
                  } elseif ($payment_key == 'eghl_qr') {
                    try {
                      if (empty(EGHL_MERCHANTID) || empty(EGHL_TERMINALID)) {
                        throw new \RuntimeException('EGHL credentials are not configured.');
                      }

                      $pay_amount = EGHL_TEST ? 1 : bcdiv($data['total_amount'], '1', 2);

                      $txnPMT = new \App\Libraries\MahJsonAPI(EGHL_SERVER_CERT_PATH, EGHL_CLIENT_KEY_PATH);
                      $txnPMT->Amount = $pay_amount;
                      $txnPMT->setNewRetTxnRef(EGHL_PREFIX . '_PRAS_' . $ins_id . '_' . $prasadam_payment_gateway_id);
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
                        $this->db->table('prasadam_payment_gateway_datas')
                          ->where('id', $prasadam_payment_gateway_id)
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
                        . ' | email=' . ($data['email_id'] ?? '')
                        . ' | prasadam_id=' . $ins_id
                        . ' | amount=' . $data['total_amount']
                        . ' | status=failed'
                        . ' | error_code=' . $e->getCode()
                        . ' | message=' . $e->getMessage()
                        . ' | datetime=' . date('Y-m-d H:i:s'));

                      $this->db->table('prasadam')->where('id', $ins_id)->update(['payment_status' => 3]);
                      $msg_data['err'] = 'QR code not generated. Kindly rebook the ticket.';
                      echo json_encode($msg_data);
                      exit();
                    }
                    $msg_data['pay_status'] = false;
                    $msg_data['payment_key'] = $payment_key;
                  } else
                    $msg_data['pay_status'] = true;

                  $pay_details = array();
                  $payment_mode_details = $this->db->table("payment_mode")->where('id', $data['payment_mode'])->get()->getRowArray();
                  $pay_details['prasadam_id'] = $ins_id;
                  $pay_details['payment_mode_id'] = $data['payment_mode'];
                  $pay_details['is_repayment'] = 0;
                  $pay_details['paid_through'] = 'COUNTER';
                  $pay_details['pay_status'] = 2;
                  $pay_details['payment_mode_title'] = $payment_mode_details['name'];
                  $pay_details['booking_ref_no'] = $data['ref_no'];
                  $pay_details['amount'] = $paid_amount;
                  if (empty($pay_details['amount'])) {
                    $this->db->transRollback();
                    $msg_data['err'] = 'Invalid Amount';
                    echo json_encode($msg_data);
                    exit;
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
                  $this->db->table("prasadam")->where('id', $ins_id)->update($booking_ref_data);
                }

                // ================================
                // ADD WHATSAPP INTEGRATION HERE
                // ================================
                if ($data['payment_status'] == 2 || $data['payment_status'] == 1) {
                  $this->account_migration($ins_id);

                  // Send WhatsApp booking confirmation for completed payments
                  if ($data['payment_status'] == 2) {
                    $this->send_prasadam_booking_confirmation($ins_id);
                  }
                }

                if ($res_2) {
                  $this->session->setFlashdata('succ', 'Prasadam Added Successflly');
                  $msg_data['succ'] = 'Prasadam Added Successflly';
                  $msg_data['id'] = $ins_id;
                } else {
                  $this->db->transRollback();
                  $this->session->setFlashdata('fail', 'Please Try Again');
                  $msg_data['err'] = 'Please Try Again';
                }
              } else {
                $this->db->transRollback();
                $msg_data['err'] = 'Please Try Again';
              }
            } catch (Exception $ex) {
              print_r($ex);
            }
          } else {
            $this->db->transRollback();
            $msg_data['err'] = 'Payment amount not greater than total amount';
          }
        } else {
          $this->db->transRollback();
          $msg_data['err'] = 'Invalid Paid Amount.';
        }
      } else {
        $this->db->transRollback();
        //$this->session->setFlashdata('fail', 'Please Try Again');
        $msg_data['err'] = 'Please Try Again. required user details.';
      }
      $this->db->transComplete();
    } catch (Exception $e) {
      $this->db->transRollback();
      $msg_data['err'] = $e->getMessage();
    }
    echo json_encode($msg_data);
    exit();
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
      $pay_amount = (float) $_POST['pay_amount'];
      $payment_mode = $_POST['payment_mode'];
      $booking_id = $_POST['booking_id'];
      $count = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getNumRows();
      if ($count > 0) {
        $payment_mode_details = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getRowArray();
        $annathanam_details = $this->db->table("prasadam")->where('id', $booking_id)->get()->getRowArray();
        if ($annathanam_details['total_amount'] >= ($annathanam_details['paid_amount'] + $pay_amount)) {

          if ($payment_mode_details['pay_key'] == 'eghl_qr') {
            // No DB row is written here. A repayment record is only ever
            // created once EGHL confirms success (see repayment_payment_check()).
            // This avoids orphaned "pending" rows if the browser tab is closed
            // or refreshed before the QR is scanned.
            try {
              if (empty(EGHL_MERCHANTID) || empty(EGHL_TERMINALID)) {
                throw new \RuntimeException('EGHL credentials are not configured.');
              }

              $eghl_pay_amount = EGHL_TEST ? 1 : bcdiv($pay_amount, '1', 2);
              $terminal_id = $this->resolveEghlTerminalId();
              $ret_txn_ref = md5(uniqid('PRASADAMREPAY_' . $booking_id . '_', true));

              $txnPMT = new \App\Libraries\MahJsonAPI(EGHL_SERVER_CERT_PATH, EGHL_CLIENT_KEY_PATH);
              $txnPMT->Amount = $eghl_pay_amount;
              $txnPMT->setNewRetTxnRef($ret_txn_ref);
              $txnPMT->MerchantID = EGHL_MERCHANTID;
              $txnPMT->OperatorID = 'SALE';
              $txnPMT->TerminalID = $terminal_id;
              $txnPMT->ProductCode = 'DUITNOWDQR';

              $raw_response = $txnPMT->paymentAsynchronous();
              if ($raw_response === 'Invalid signature') {
                throw new \RuntimeException('EGHL response failed signature verification.');
              }
              $eghl_response = json_decode($raw_response);

              if (!empty($eghl_response->msg->DisplayInfo[0]->Value)) {
                echo json_encode([
                  'status' => true,
                  'pay_status' => false,
                  'payment_key' => 'eghl_qr',
                  'qr_code' => $eghl_response->msg->DisplayInfo[0]->Value,
                  'total_amount' => $eghl_pay_amount,
                  'booking_id' => $booking_id,
                  'pay_amount' => $pay_amount,
                  'payment_mode' => $payment_mode,
                  'paid_date' => !empty($date) ? $date : date('Y-m-d'),
                  'ret_txn_ref' => $eghl_response->msg->RetTxnRef,
                  'txn_ref' => $eghl_response->msg->TxnRef,
                  'terminal_id' => $terminal_id,
                  'message' => 'Proceed to EGHL Payment',
                ]);
              } else {
                $error_code = $eghl_response->msg->ResponseCode ?? null;
                $error_msg  = $eghl_response->msg->ResponseMsg ?? 'No QR returned';
                throw new \RuntimeException("EGHL Sale failed [{$error_code}]: {$error_msg}");
              }
            } catch (\Throwable $e) {
              log_message('error', 'EGHL_QR_REPAY_SALE_FAILURE | user_id=' . ($this->session->get('log_id_frend') ?? '')
                . ' | booking_id=' . $booking_id
                . ' | amount=' . $pay_amount
                . ' | status=failed'
                . ' | error_code=' . $e->getCode()
                . ' | message=' . $e->getMessage()
                . ' | datetime=' . date('Y-m-d H:i:s'));
              echo json_encode(['status' => false, 'message' => 'QR code not generated. Kindly try again.']);
            }
            exit;
          }

          $booking_payment_ins_data = array();
          $booking_payment_ins_data['prasadam_id'] = $booking_id;
          $booking_payment_ins_data['booking_ref_no'] = $annathanam_details['ref_no'];
          $booking_payment_ins_data['is_repayment'] = 1;
          $booking_payment_ins_data['payment_mode_id'] = $payment_mode;
          $booking_payment_ins_data['paid_date'] = !empty($date) ? $date : date('Y-m-d');
          $booking_payment_ins_data['amount'] = $pay_amount;
          $booking_payment_ins_data['payment_mode_title'] = $payment_mode_details['name'];
          $paid_through = 'COUNTER';
          if ($paid_through != 'ADMIN' && $paid_through != 'COUNTER')
            $booking_payment_ins_data['payment_ref_no'] = $ubayam_details['ref_no'];
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

  public function repayment_payment_check()
  {
    $required = ['ret_txn_ref', 'txn_ref', 'booking_id', 'pay_amount', 'payment_mode'];
    foreach ($required as $field) {
      if (empty($_REQUEST[$field])) {
        echo json_encode(['status' => false, 'pay_status' => false, 'error_msg' => 'Invalid request.']);
        exit;
      }
    }
    $booking_id = $_REQUEST['booking_id'];
    $pay_amount = (float) $_REQUEST['pay_amount'];
    $payment_mode = $_REQUEST['payment_mode'];
    $paid_date = !empty($_REQUEST['paid_date']) ? $_REQUEST['paid_date'] : date('Y-m-d');
    $ret_txn_ref = $_REQUEST['ret_txn_ref'];
    $txn_ref = $_REQUEST['txn_ref'];
    $terminal_id = !empty($_REQUEST['terminal_id']) ? $_REQUEST['terminal_id'] : $this->resolveEghlTerminalId();

    try {
      $eghl_pay_amount = EGHL_TEST ? 1 : bcdiv($pay_amount, '1', 2);

      $txnPMT = new \App\Libraries\MahJsonAPI(EGHL_SERVER_CERT_PATH, EGHL_CLIENT_KEY_PATH);
      $txnPMT->Amount = $eghl_pay_amount;
      $txnPMT->setLatRetTxnRef($ret_txn_ref);
      $txnPMT->MerchantID = EGHL_MERCHANTID;
      $txnPMT->OperatorID = 'SALE';
      $txnPMT->TerminalID = $terminal_id;
      $txnPMT->ProductCode = 'DUITNOWDQR';

      $response = $txnPMT->paymentQuery($txn_ref);

      if ($response === 'Invalid signature') {
        log_message('error', 'EGHL_QR_REPAY_QUERY_INVALID_SIGNATURE | booking_id=' . $booking_id . ' | datetime=' . date('Y-m-d H:i:s'));
        echo json_encode(['status' => false, 'pay_status' => false, 'error_msg' => 'Server Down']);
        exit;
      }

      $response_data = json_decode($response);

      if (isset($response_data->msg->OrgResponseCode) && isset($response_data->msg->OrgResponseMsg)) {
        if ($response_data->msg->OrgResponseCode == 'PN') {
          // Still pending - nothing is saved until this resolves.
          echo json_encode(['status' => true, 'pay_status' => false, 'error_msg' => 'Transaction is still pending']);
          exit;
        } elseif ($response_data->msg->OrgResponseCode == '00') {
          // Success - only now does a repayment record get created.
          $payment_mode_details = $this->db->table('payment_mode')->where('id', $payment_mode)->get()->getRowArray();
          $annathanam_details = $this->db->table('prasadam')->where('id', $booking_id)->get()->getRowArray();
          if (empty($payment_mode_details) || empty($annathanam_details)) {
            echo json_encode(['status' => false, 'pay_status' => false, 'error_msg' => 'Booking not found.']);
            exit;
          }

          $booking_payment_ins_data = array();
          $booking_payment_ins_data['prasadam_id'] = $booking_id;
          $booking_payment_ins_data['booking_ref_no'] = $annathanam_details['ref_no'];
          $booking_payment_ins_data['is_repayment'] = 1;
          $booking_payment_ins_data['payment_mode_id'] = $payment_mode;
          $booking_payment_ins_data['paid_date'] = $paid_date;
          $booking_payment_ins_data['amount'] = $pay_amount;
          $booking_payment_ins_data['payment_mode_title'] = $payment_mode_details['name'];
          $booking_payment_ins_data['payment_key'] = 'eghl_qr';
          $booking_payment_ins_data['paid_through'] = 'ONLINE';
          $booking_payment_ins_data['pay_status'] = 2;
          $booking_payment_ins_data['response_data'] = $response;
          $this->requestmodel = new RequestModel();
          $ip = $this->requestmodel->getIpAddress();
          $booking_payment_ins_data['ip'] = $ip;
          if ($ip != 'unknown') {
            $ip_details = $this->requestmodel->getLocation($ip);
            $booking_payment_ins_data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
            $booking_payment_ins_data['ip_details'] = json_encode($ip_details);
          }
          $this->db->table('prasadam_booked_pay_details')->insert($booking_payment_ins_data);
          $booked_pay_id = $this->db->insertID();

          $this->db->query("UPDATE prasadam SET paid_amount = paid_amount + ? WHERE id = ?", [$pay_amount, $booking_id]);
          $query = $this->db->table('prasadam')->where('id', $booking_id)->get()->getRowArray();
          if ($query['total_amount'] == $query['paid_amount']) {
            $this->db->query("UPDATE prasadam SET payment_status = 2 WHERE id = ?", [$booking_id]);
          } elseif ($query['paid_amount'] > 0 && $query['payment_status'] == 0) {
            $this->db->query("UPDATE prasadam SET payment_status = 1 WHERE id = ?", [$booking_id]);
          }
          $this->partial_account_migration($booked_pay_id);
          echo json_encode(['status' => true, 'pay_status' => true, 'error_msg' => 'Payment successful.', 'booking_id' => $booking_id]);
          exit;
        } else {
          // Genuine EGHL-declined transaction (backend/gateway failure) -
          // keep a record for diagnostics. User-initiated cancels never reach here.
          $this->createFailedRepaymentRecord($booking_id, $payment_mode, $pay_amount, $paid_date, $response);
          log_message('error', 'EGHL_QR_REPAY_QUERY_FAILED | booking_id=' . $booking_id
            . ' | error_code=' . $response_data->msg->OrgResponseCode
            . ' | message=' . $response_data->msg->OrgResponseMsg
            . ' | datetime=' . date('Y-m-d H:i:s'));
          echo json_encode(['status' => false, 'pay_status' => false, 'error_msg' => 'Payment failed. Kindly try again.']);
          exit;
        }
      } else {
        echo json_encode(['status' => false, 'pay_status' => false, 'error_msg' => 'Server Down']);
        exit;
      }
    } catch (\Throwable $e) {
      log_message('error', 'EGHL_QR_REPAY_QUERY_EXCEPTION | booking_id=' . $booking_id
        . ' | message=' . $e->getMessage()
        . ' | datetime=' . date('Y-m-d H:i:s'));
      echo json_encode(['status' => false, 'pay_status' => false, 'error_msg' => 'Server Down']);
      exit;
    }
  }

  /**
   * Logs a genuine gateway-declined repayment attempt as a failed
   * prasadam_booked_pay_details row. Never called for a user-initiated
   * cancel/timeout - only for a real EGHL failure response.
   */
  private function createFailedRepaymentRecord($booking_id, $payment_mode, $pay_amount, $paid_date, $response = null)
  {
    $payment_mode_details = $this->db->table('payment_mode')->where('id', $payment_mode)->get()->getRowArray();
    $annathanam_details = $this->db->table('prasadam')->where('id', $booking_id)->get()->getRowArray();
    if (empty($payment_mode_details) || empty($annathanam_details)) {
      return;
    }
    $booking_payment_ins_data = array();
    $booking_payment_ins_data['prasadam_id'] = $booking_id;
    $booking_payment_ins_data['booking_ref_no'] = $annathanam_details['ref_no'];
    $booking_payment_ins_data['is_repayment'] = 1;
    $booking_payment_ins_data['payment_mode_id'] = $payment_mode;
    $booking_payment_ins_data['paid_date'] = $paid_date;
    $booking_payment_ins_data['amount'] = $pay_amount;
    $booking_payment_ins_data['payment_mode_title'] = $payment_mode_details['name'];
    $booking_payment_ins_data['payment_key'] = 'eghl_qr';
    $booking_payment_ins_data['paid_through'] = 'ONLINE';
    $booking_payment_ins_data['pay_status'] = 3;
    $booking_payment_ins_data['response_data'] = $response;
    $this->db->table('prasadam_booked_pay_details')->insert($booking_payment_ins_data);
  }

  public function cancel_repayment()
  {
    // User-initiated cancel/timeout - nothing is written to prasadam_booked_pay_details.
    // Only kept as a log line for support/diagnostics purposes.
    if (!empty($_REQUEST['booking_id'])) {
      log_message('error', 'EGHL_QR_REPAY_CANCELLED | user_id=' . ($this->session->get('log_id_frend') ?? '')
        . ' | booking_id=' . $_REQUEST['booking_id']
        . ' | amount=' . ($_REQUEST['pay_amount'] ?? '')
        . ' | status=cancelled'
        . ' | datetime=' . date('Y-m-d H:i:s'));
    }
    echo json_encode(['status' => true]);
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
      $booking_id = $booked_pay_details[0]['prasadam_id'];
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

  public function send_mail_to_customer($id)
  {
    $prasadam = $this->db->table("prasadam")->where("id", $id)->get()->getRowArray();
    if (!empty($prasadam['email_id'])) {
      $prasadam_booking_details = $this->db->table('prasadam_booking_details')->select("prasadam_booking_details.*, prasadam_setting.name_eng as prasadam_eng, prasadam_setting.name_tamil as prasadam_tamil")->join('prasadam_setting', 'prasadam_booking_details.prasadam_id = prasadam_setting.id', 'left')->where('prasadam_booking_id', $id)->get()->getResultArray();
      $tmpid = 1;
      $temple_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
      $temple_title = "Temple " . $temple_details['name'];
      $qr_url = base_url() . "/prasadam/reg/";
      $mail_data['qr_image'] = qrcode_generation($id, $qr_url);
      $mail_data['don_id'] = $id;
      $mail_data['prasadam'] = $prasadam;
      $mail_data['prasadam_booking_details'] = $prasadam_booking_details;
      $mail_data['temple_details'] = $temple_details;
      $message = view('prasadam/mail_template', $mail_data);
      $to = $prasadam['email_id'];
      $subject = $temple_details['name'] . " Prasadam";
      $to_mail = array("prithivitest@gmail.com", $to);
      send_mail_with_content($to_mail, $message, $subject, $temple_title);
    }
  }
  public function payment_process($prsm_id)
  {
    $prasadam_booking = $this->db->table('prasadam')->where('id', $prsm_id)->get()->getRowArray();
    $prasadam_payment_gateway_datas = $this->db->table('prasadam_payment_gateway_datas')->where('prasadam_id', $prsm_id)->get()->getResultArray();
    if (count($prasadam_payment_gateway_datas) > 0) {
      if ($prasadam_payment_gateway_datas[0]['pay_method'] == 'adyen') {
        if (!empty($prasadam_payment_gateway_datas[0]['request_data'])) {
          $request_data = $prasadam_payment_gateway_datas[0]['request_data'];
          $response = json_decode($request_data, true);
        } else {
          $tmpid = 1;
          $temple_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
          $result = $this->initiatePayment($prasadam_booking['amount'], $prsm_id, $temple_details['address1'] . $temple_details['address2'], $temple_details['city'], $temple_details['email']);
          $response = json_decode($result, true);
          $payment_gateway_up_data = array();
          $payment_gateway_up_data['request_data'] = $result;
          $payment_gateway_up_data['reference_id'] = $response['id'];
          $this->db->table('prasadam_payment_gateway_datas')->where('id', $prasadam_payment_gateway_datas[0]['id'])->update($payment_gateway_up_data);
        }
        if (!empty($response['url']) && !empty($response['id'])) {
          header('Location: ' . $response['url']);
          exit;
        }
      } elseif ($prasadam_payment_gateway_datas[0]['pay_method'] == 'ipay_merch_qr') {
        //$view_file = 'frontend/ipay88/ipay_merch_qr';
        $view_file = 'frontend/ipay88/ipay_merch_qr_camera';
        $data['prsm_id'] = $prsm_id;
        $data['list'] = $this->db->table('payment_option')->where('status', 1)->get()->getResultArray();
        $data['submit_url'] = '/prasadam_online/initiate_ipay_merch_qr/' . $prsm_id;
        echo view($view_file, $data);
      } elseif ($prasadam_payment_gateway_datas[0]['pay_method'] == 'ipay_merch_online') {
        $view_file = 'frontend/ipay88/ipay_merch_online';
        $data['id'] = $prsm_id;
        $data['controller'] = 'prasadam_online';
        echo view($view_file, $data);
      } else {
        // $redirect_url = base_url() . '/prasadam_online/print_booking/' . $prsm_id;
        $redirect_url = base_url() . '/prasadam_online/print_booking_report_a5/' . $prsm_id;
        header('Location: ' . $redirect_url);
        exit;
      }
    } else {
      $tmpid = 1;
      $temple_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
      $result = $this->initiatePayment($prasadam_booking['amount'], $prsm_id, $temple_details['address1'] . $temple_details['address2'], $temple_details['city'], $temple_details['email']);
      $response = json_decode($result, true);
      if (!empty($response['url']) && !empty($response['id'])) {
        $payment_gateway_data = array();
        $payment_gateway_data['prasadam_id'] = $prsm_id;
        $payment_gateway_data['pay_method'] = 'adyen';
        $payment_gateway_data['request_data'] = $result;
        $payment_gateway_data['reference_id'] = $response['id'];
        $this->db->table('prasadam_payment_gateway_datas')->insert($payment_gateway_data);
        $prasadam_payment_gateway_id = $this->db->insertID();
        if (!empty($prasadam_payment_gateway_id)) {
          header('Location: ' . $response['url']);
          exit;
        }
      }
    }
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
      'returnUrl' => base_url() . '/prasadam_online/print_booking/' . $orderid,
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
  public function ipay88_online_response($prasadam_id)
  {
    include_once FCPATH . 'app/Libraries/ipay88-master/IPay88.class.php';
    $MerchantCode = 'M01236';
    $MerchantKey = 'HQgUUZLVzg';
    $ipay88 = new \IPay88($MerchantCode);
    $ipay88->setMerchantKey($MerchantKey);
    $response = $ipay88->getResponse();
    //print_r($response);
    if ($response['status']) {
      $prasadam_up_data = array();
      $prasadam_up_data['payment_status'] = 2;
      $this->db->table('prasadam')->where('id', $prasadam_id)->update($prasadam_up_data);
      $this->account_migration($prasadam_id);
      $this->session->setFlashdata('succ', 'Prasadam Successfully Completed');
      $redirect_url = base_url() . '/prasadam_online/print_booking/' . $prasadam_id;
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
  public function initiate_ipay_merch_online($prasadam_id)
  {
    include_once FCPATH . 'app/Libraries/ipay88-master/IPay88.class.php';
    $payment_id = !empty($_REQUEST['payment_id']) ? $_REQUEST['payment_id'] : '';
    $prasadam_booking = $this->db->table('prasadam')->where('id', $prasadam_id)->get()->getRowArray();
    $email = !empty($prasadam_booking['email']) ? $prasadam_booking['email'] : 'dd@ipay88.com.my';
    $name = !empty($prasadam_booking['customer_name']) ? $prasadam_booking['customer_name'] : 'Prithivi';
    $mobile_no = !empty($prasadam_booking['mobile_no']) ? $prasadam_booking['mobile_no'] : '9856734562';
    $description = 'Prasadam';
    $final_amt = $prasadam_booking['amount'];
    $final_amount = number_format($final_amt, '2', '.', '');
    $final_amt_str = (string) ($final_amt * 1000);
    $MerchantCode = 'M01236';
    $MerchantKey = 'HQgUUZLVzg';
    $ref_no = 'PRAS_' . $prasadam_id;
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
    $ipay88->setField('UserContact', (string) $mobile_no);
    $ipay88->setField('Remark', $description);
    $ipay88->setField('Lang', 'utf-8');
    $ipay88->setField('ResponseURL', base_url() . '/prasadam_online/ipay88_online_response/' . $prasadam_id);
    $ipay88->setField('BackendURL', base_url() . '/prasadam_online/ipay88_online_response/' . $prasadam_id);
    $ipay88->generateSignature();
    $ipay88_fields = $ipay88->getFields();
    $data['ipay88_fields'] = $ipay88_fields;
    $data['epayment_url'] = \Ipay88::$epayment_url;
    $view_file = 'frontend/ipay88/ipay_merch_online_process';
    echo view($view_file, $data);
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
    if (empty($number) && empty($number1))
      $num = 1;
    else
      $num = $number['number'] + 1;

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
      if (empty($number) && empty($number1))
        $num = 1;
      else
        $num = $number['number'] + 1;

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
      $discount_ledger_id = !empty($setting['discount_prasadam_ledger_id']) ? $setting['discount_prasadam_ledger_id'] : 447;
      $eitems_disc_ent['entry_id'] = $en_id2;
      $eitems_disc_ent['ledger_id'] = $discount_ledger_id;
      $eitems_disc_ent['amount'] = $data['discount_amount'];
      $eitems_disc_ent['is_discount'] = 1;
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
      if (empty($number) && empty($number1))
        $num = 1;
      else
        $num = $number['number'] + 1;

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

  public function print_booking_report($prsm_id)
  {

    $id = $prsm_id;
    $data['data'] = $this->db->table('prasadam')->select('prasadam.*')->where('prasadam.id', $id)->get()->getRowArray();
    $tmpid = $this->session->get('profile_id_frend');
    $data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
    $data['booking_details'] = $this->db->table('prasadam_booking_details')
      ->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
      ->join('prasadam_group', 'prasadam_group.id = prasadam_booking_details.group_id')
      ->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil, prasadam_group.name as groupname')
      ->where('prasadam_booking_details.prasadam_booking_id', $id)
      ->get()->getResultArray();
    $data['pay_details'] = $this->db->table("prasadam_booked_pay_details")->where("prasadam_id", $id)->get()->getResultArray();
    $data['prasadambooked'] = $this->db->table('prasadam_booking_details')
      ->select('total_amount')
      ->where('id', $id)
      ->get()
      ->getRowArray();
    // echo '<pre>';
    // print_r($data['booking_details']);
    // exit;
    echo view('frontend/prasadam/print_page_a4', $data);
  }
  public function print_booking_report_a5($prsm_id)
  {

    $id = $prsm_id;
    $data['data'] = $this->db->table('prasadam')->select('prasadam.*')->where('prasadam.id', $id)->get()->getRowArray();
    $tmpid = $this->session->get('profile_id_frend');
    $data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
    $data['booking_details'] = $this->db->table('prasadam_booking_details')
      ->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
      ->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil')
      ->where('prasadam_booking_details.prasadam_booking_id', $id)
      ->get()->getResultArray();
    $data['pay_details'] = $this->db->table("prasadam_booked_pay_details")->where("prasadam_id", $id)->get()->getResultArray();
    $data['prasadambooked'] = $this->db->table('prasadam_booking_details')
      ->select('total_amount')
      ->where('id', $id)
      ->get()
      ->getRowArray();
    $diety = $this->db->table('archanai_diety')->where('id', $data['data']['diety_id'])->get()->getRowArray();
    $data['data']['diety_name'] = $diety ? $diety['name'] : '';

    echo view('frontend/prasadam/print_page_a5', $data);
  }
  public function print_booking($prsm_id)
  {
    $id = $this->request->uri->getSegment(3);

    $data['qry1'] = $this->db->table('prasadam')
      ->select('prasadam.*, archanai_diety.name as diety_name_eng, archanai_diety.name_tamil as diety_name_tamil, payment_mode_table.name as payment_mode_name')
      ->join('archanai_diety', 'archanai_diety.id = prasadam.diety_id', 'left')
      ->join('payment_mode as payment_mode_table', 'payment_mode_table.id = prasadam.payment_mode', 'left')

      ->where('prasadam.id', $id)
      ->get()
      ->getRowArray();
    $data['qry1_payfor'] = $this->db->table('prasadam_booking_details')
      ->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
      ->join('prasadam_group', 'prasadam_group.id = prasadam_booking_details.group_id')

      ->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil, prasadam_group.name as groupname')
      ->where('prasadam_booking_details.prasadam_booking_id', $id)
      ->get()->getResultArray();
    $data['dieties'] = $this->db->table('archanai_diety')->get()->getResultArray();
    // $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
    // $data['qrcdoee'] = qrcode_generation($id, $url, 95, 95);
    if ($prasadam['sep_print'] == 1)
      $view_file = 'frontend/prasadam/print_sep';
    else
      // $view_file = 'frontend/prasadam/print_page';
      $view_file = 'frontend/prasadam/print_imin';
    // if ($prasadam['paid_through'] == 'COUNTER') {
    //   if ($prasadam['payment_status'] == '2') {
    //     $tmpid = $this->session->get('profile_id');
    //     $data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
    //     $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
    //     echo view($view_file, $data);
    //   } elseif ($prasadam['payment_status'] == '1') {
    //     $prasadam_payment_gateway_datas = $this->db->table('prasadam_payment_gateway_datas')->where('prasadam_id', $prsm_id)->get()->getRowArray();
    //     if (!empty($prasadam_payment_gateway_datas['reference_id'])) {
    //       $reference_id = $prasadam_payment_gateway_datas['reference_id'];
    //       $result_data = $this->initiatePayment_response($reference_id);
    //       $response_data = json_decode($result_data, true);
    //       $payment_gateway_up_data = array();
    //       $payment_gateway_up_data['response_data'] = $result_data;
    //       $this->db->table('prasadam_payment_gateway_datas')->where('id', $prasadam_payment_gateway_datas['id'])->update($payment_gateway_up_data);
    //       if (!empty($response_data['status'])) {
    //         if ($response_data['status'] == 'completed') {
    //           $prasadam_up_data = array();
    //           $prasadam_up_data['payment_status'] = 2;
    //           $this->db->table('prasadam')->where('id', $id)->update($prasadam_up_data);
    //           $this->account_migration($id);
    //           $tmpid = $this->session->get('profile_id');
    //           $data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
    //           $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
    //           echo view($view_file, $data);
    //         } else {
    //           $prasadam_up_data = array();
    //           $prasadam_up_data['payment_status'] = 3;
    //           $this->db->table('prasadam')->where('id', $id)->update($prasadam_up_data);
    //           redirect()->to("/cancelled_booking");
    //           exit;
    //         }
    //       }
    //     } else {
    //       redirect()->to("/cancelled_booking");
    //       exit;
    //     }
    //   }
    // } else {
    $tmpid = 1;
    $data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
    $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
    echo view($view_file, $data);
    //}
  }
  public function print_booking_sep($prsm_id)
  {
    $id = $this->request->uri->getSegment(3);
    $data['qry1'] = $this->db->table('prasadam')
      ->select('prasadam.*')
      ->where('prasadam.id', $id)
      ->get()->getRowArray();
    $tmpid = $this->session->get('profile_id');
    $data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
    $data['qry1_payfor'] = $this->db->table('prasadam_booking_details')
      ->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
      ->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil')
      ->where('prasadam_booking_details.prasadam_booking_id', $id)
      ->get()->getResultArray();
    // $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
    // $data['qrcdoee'] = qrcode_generation($id, $url, 95, 95);
    echo view('frontend/prasadam/print_sep', $data);
  }
  public function cancelled_booking()
  {
    echo view('frontend/layout/header');
    echo view('frontend/prasadam/cancelled_booking');
    echo view('frontend/layout/footer');
  }
  public function reprint_booking($id)
  {
    $data['qry1'] = $prasadam = $this->db->table('prasadam')
      ->select('prasadam.*, payment_mode.name as payment_mode_name')
      ->join('payment_mode', 'payment_mode.id = prasadam.payment_mode', 'left')
      ->where('prasadam.id', $id)
      ->get()
      ->getRowArray();
    $data['qry1_payfor'] = $this->db->table('prasadam_booking_details')
      ->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
      ->join('prasadam_group', 'prasadam_group.id = prasadam_booking_details.group_id')

      ->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil,prasadam_group.name as groupname')
      ->where('prasadam_booking_details.prasadam_booking_id', $id)
      ->get()->getResultArray();
    // $view_file = 'frontend/prasadam/print_page';
    $view_file = 'frontend/prasadam/print_imin';
    $tmpid = $this->session->get('profile_id');
    $data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
    $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
    // $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
    // $data['qrcdoee'] = qrcode_generation($id, $url, 95, 95);
    echo view($view_file, $data);
  }

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
    // $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
    // $data['qrcdoee'] = qrcode_generation($id, $url, 95, 95);
    $tmpid = 1;
    $data['temple_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
    if (!empty($prasadam['mobile_no'])) {
      $html = view('prasadam/pdf', $data);
      $options = new Options();
      $options->set('isHtml5ParserEnabled', true);
      $options->set(array('isRemoteEnabled' => true));
      $options->set('isPhpEnabled', true);
      // echo $html;
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

  public function print_imin($prsm_id)
  {
    $id = $this->request->uri->getSegment(3);
    $data['qry1'] = $prasadam = $this->db->table('prasadam')
      ->select('prasadam.*')
      ->where('prasadam.id', $id)
      ->get()->getRowArray();
    $data['qry1_payfor'] = $this->db->table('prasadam_booking_details')
      ->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
      ->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil')
      ->where('prasadam_booking_details.prasadam_booking_id', $id)
      ->get()->getResultArray();
    // $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
    // $data['qrcdoee'] = qrcode_generation($id, $url, 95, 95);
    if ($prasadam['sep_print'] == 1)
      $view_file = 'frontend/prasadam/print_imin_sep';
    else
      $view_file = 'frontend/prasadam/print_imin';
    if ($prasadam['paid_through'] == 'COUNTER') {
      if ($prasadam['payment_status'] == '2') {
        $tmpid = $this->session->get('profile_id');
        $data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
        $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
        echo view($view_file, $data);
      } elseif ($prasadam['payment_status'] == '1') {
        $prasadam_payment_gateway_datas = $this->db->table('prasadam_payment_gateway_datas')->where('prasadam_id', $prsm_id)->get()->getRowArray();
        if (!empty($prasadam_payment_gateway_datas['reference_id'])) {
          $reference_id = $prasadam_payment_gateway_datas['reference_id'];
          $result_data = $this->initiatePayment_response($reference_id);
          $response_data = json_decode($result_data, true);
          $payment_gateway_up_data = array();
          $payment_gateway_up_data['response_data'] = $result_data;
          $this->db->table('prasadam_payment_gateway_datas')->where('id', $prasadam_payment_gateway_datas['id'])->update($payment_gateway_up_data);
          if (!empty($response_data['status'])) {
            if ($response_data['status'] == 'completed') {
              $prasadam_up_data = array();
              $prasadam_up_data['payment_status'] = 2;
              $this->db->table('prasadam')->where('id', $id)->update($prasadam_up_data);
              $this->account_migration($id);
              $tmpid = $this->session->get('profile_id');
              $data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
              $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
              echo view($view_file, $data);
            } else {
              $prasadam_up_data = array();
              $prasadam_up_data['payment_status'] = 3;
              $this->db->table('prasadam')->where('id', $id)->update($prasadam_up_data);
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
      $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
      echo view($view_file, $data);
    }
  }

  public function payment_check()
  {
    $data = [];
    if (!empty($_REQUEST['booking_id'])) {
      $booking_id = $_REQUEST['booking_id'];
      $user_id = $_SESSION['log_id_frend'];

      // Check if booking exists
      $prasadam_cnt = $this->db->table('prasadam')
        ->where('id', $booking_id)
        ->countAllResults(false); // false to NOT reset query builder to allow reuse if needed

      if ($prasadam_cnt > 0) {
        try {
          // Get booking record
          $prasadam = $this->db->table('prasadam')
            ->where('id', $booking_id)
            ->get()
            ->getRow();

          // Get payment gateway data
          $payment_gateway_datas = $this->db->table('prasadam_payment_gateway_datas')->select('prasadam_payment_gateway_datas.*, payment_mode.pay_key')->join('payment_mode', 'payment_mode.id = prasadam_payment_gateway_datas.payment_mode', 'left')->where('prasadam_payment_gateway_datas.prasadam_id', $booking_id)->get()->getRowArray();
          if ($prasadam->payment_status == 1) {
            if ($payment_gateway_datas['pay_key'] == 'rhb_qr' || $payment_gateway_datas['pay_key'] == 'eghl_qr') {
              $rtn = $payment_gateway_datas['pay_key'] == 'eghl_qr'
                ? $this->initiate_eghl_qr($booking_id, $prasadam, $payment_gateway_datas)
                : $this->initiate_rhb_qr($booking_id, $prasadam, $payment_gateway_datas);
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
          } elseif ($prasadam->payment_status == 3) {
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
  public function initiate_rhb_qr($prasadam_id, $prasadam, $payment_gateway_datas)
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
      $this->db->table('prasadam_payment_gateway_datas')
        ->where('id', $payment_gateway_datas_id)
        ->update(['response_data' => $response]);
      $response_data = json_decode($response);

      if (!empty($response_data->paymentStatus)) {
        if ($response_data->paymentStatus === 'FOUND') {
          $rtn['status'] = 'success';

          // Update archanai_booking payment_status = 2
          $this->db->table('prasadam')
            ->where('id', $prasadam_id)
            ->update(['payment_status' => 2]);

          // Call migration functions
          $this->account_migration($prasadam_id);
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

  public function initiate_eghl_qr($prasadam_id, $prasadam, $payment_gateway_datas){
    $request_data = json_decode($payment_gateway_datas['request_data']);
    $payment_gateway_datas_id = $payment_gateway_datas['id'];
    $rtn = [];

    if (empty($request_data->msg->RetTxnRef) || empty($request_data->msg->TxnRef)) {
      $rtn['status'] = 'unidentify';
      $rtn['org_msg'] = 'Server Down';
      return $rtn;
    }

    try {
      $pay_amount = EGHL_TEST ? 1 : bcdiv($prasadam->total_amount, '1', 2);

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
          . ' | prasadam_id=' . $prasadam_id
          . ' | amount=' . $prasadam->total_amount
          . ' | status=unidentify'
          . ' | error_code=SIGNATURE'
          . ' | message=EGHL query response failed signature verification'
          . ' | datetime=' . date('Y-m-d H:i:s'));
        $rtn['status'] = 'unidentify';
        $rtn['org_msg'] = 'Server Down';
        return $rtn;
      }

      $this->db->table('prasadam_payment_gateway_datas')->where('id', $payment_gateway_datas_id)->update(['response_data' => $response]);
      $response_data = json_decode($response);

      if (isset($response_data->msg->OrgResponseCode) && isset($response_data->msg->OrgResponseMsg)) {
        if ($response_data->msg->OrgResponseCode != 'PN') {
          if ($response_data->msg->OrgResponseCode == '00') {
            $rtn['status'] = 'success';
            $new_payment_status = ($prasadam->paid_amount >= $prasadam->total_amount) ? 2 : (($prasadam->paid_amount > 0) ? 1 : 0);
            $this->db->table('prasadam')->where('id', $prasadam_id)->update(['payment_status' => $new_payment_status]);
            $this->account_migration($prasadam_id);
          } else {
            $this->db->table('prasadam')->where('id', $prasadam_id)->update(['payment_status' => 3]);
            $rtn['status'] = 'failed';
            log_message('error', 'EGHL_QR_QUERY_FAILED | user_id=' . ($this->session->get('log_id_frend') ?? '')
              . ' | prasadam_id=' . $prasadam_id
              . ' | amount=' . $prasadam->total_amount
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
        . ' | prasadam_id=' . $prasadam_id
        . ' | amount=' . $prasadam->total_amount
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

      // Check if booking exists
      $prasadam_cnt = $this->db->table('prasadam')
        ->where('id', $booking_id)
        ->countAllResults(false); // false to NOT reset query builder to allow reuse if needed

      if ($prasadam_cnt > 0) {
        try {
          // Get booking record
          $prasadam = $this->db->table('prasadam')
            ->where('id', $booking_id)
            ->get()
            ->getRow();

          // Get payment gateway data
          $payment_gateway_datas = $this->db->table('prasadam_payment_gateway_datas')
            ->where('prasadam_id', $booking_id)
            ->get()
            ->getRowArray();

          if ($prasadam->payment_status == 1) {
            if (!empty($payment_gateway_datas) && ($payment_gateway_datas['pay_method'] === 'RHB QR' || $payment_gateway_datas['pay_method'] === 'EGHL QR')) {
              $rtn = $payment_gateway_datas['pay_method'] === 'EGHL QR'
                ? $this->initiate_eghl_qr($booking_id, $prasadam, $payment_gateway_datas)
                : $this->initiate_rhb_qr($booking_id, $prasadam, $payment_gateway_datas);

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
                $this->db->table('prasadam')->where('id', $booking_id)->update(['payment_status' => 3]);

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
              $this->db->table('prasadam')->where('id', $booking_id)->update(['payment_status' => 3]);

              $data = [
                'status' => true,
                'pay_status' => false,
                'order_status' => 'failed',
                'org_msg' => 'Transaction Failed',
                'error_msg' => "We’re sorry! your payment is failed. Kindly try again.",
              ];
              return json_encode($data);
            }
          } elseif ($prasadam->payment_status == 3) {
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





  // ================================
  // 1. ADD TO Prasadam_online Controller
  // ================================

  // Add these methods to your Prasadam_online controller

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
      $html = view('frontend/prasadam/pdf_whatsapp_template', $data);

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
    $reminder_sent = $this->db->table('prasadam_whatsapp_log')
      ->where('prasadam_id', $prasadam_id)
      ->where('message_type', 'reminder')
      ->where('status', 'sent')
      ->get()->getRowArray();

    if ($reminder_sent) {
      return false; // Already sent
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
    $thankyou_sent = $this->db->table('prasadam_whatsapp_log')
      ->where('prasadam_id', $prasadam_id)
      ->where('message_type', 'thank_you')
      ->where('status', 'sent')
      ->get()->getRowArray();

    if ($thankyou_sent) {
      return false; // Already sent
    }

    $params = [
      ':devotee' => $prasadam['customer_name'],
      ':temple_name' => 'Temple Name' // Get from admin_profile if needed
    ];

    $numbers = [$prasadam['mobile_no']];
    $response = whatsapp_ultramsg($numbers, 'prasadam_thank_you', $params);

    $this->log_whatsapp_activity($prasadam_id, 'thank_you', $response);

    return $response;
  }

  /**
   * Log WhatsApp activity
   */
  private function log_whatsapp_activity($prasadam_id, $message_type, $response)
  {
    $log_data = [
      'prasadam_id' => $prasadam_id,
      'message_type' => $message_type,
      'response' => json_encode($response),
      'status' => isset($response['sent']) && $response['sent'] ? 'sent' : 'failed',
      'created_at' => date('Y-m-d H:i:s')
    ];

    $this->db->table('prasadam_whatsapp_log')->insert($log_data);
  }

  /**
   * Cron job to send reminders (run daily)
   */

  public function send_prasadam_reminders_cron()
  {
    // Security check - replace 'your_secret_key_here' with your actual secret key
    $secret_key = 'prasadam_cron_2024_secret'; // Change this to something secure
    $provided_key = $this->request->getGet('key') ?: $this->request->getPost('key');

    if ($provided_key !== $secret_key) {
      http_response_code(403);
      echo json_encode(['error' => 'Unauthorized access']);
      return;
    }

    $reminder_date = date('Y-m-d', strtotime('+3 days'));

    $prasadams = $this->db->table('prasadam')
      ->select('id')
      ->where('collection_date', $reminder_date)
      ->where('payment_status', 2)
      ->whereNotIn('id', function ($builder) {
        return $builder->select('prasadam_id')
          ->from('prasadam_whatsapp_log')
          ->where('message_type', 'reminder')
          ->where('status', 'sent');
      })
      ->get()->getResultArray();

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

    log_message('info', "Prasadam reminders cron: " . json_encode($response));

    // Return JSON response for OVI panel
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  /**
   * Send thank you messages - HTTP accessible with secret key
   */
  public function send_prasadam_thankyou_cron()
  {
    $secret_key = 'temple_prasadam_thankyou_2024';
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
    $prasadams = $this->db->table('prasadam')
      ->select('id, session, serve_time, customer_name')
      ->where('collection_date', $today)
      ->where('payment_status', 2)
      ->whereIn('session', $slots_to_process)
      ->whereNotIn('id', function ($builder) {
        return $builder->select('prasadam_id')
          ->from('prasadam_whatsapp_log')
          ->where('message_type', 'thank_you')
          ->where('status', 'sent');
      })
      ->get()->getResultArray();

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

    log_message('info', "Prasadam thank you cron: " . json_encode($response));

    header('Content-Type: application/json');
    echo json_encode($response);
  }





  /**
   * Test cron job connection
   */
  public function test_cron_connection()
  {
    $secret_key = 'temple_cron_2024_secure'; // Match your URL key
    $provided_key = $this->request->getGet('key') ?: $this->request->getPost('key');

    if ($provided_key !== $secret_key) {
      http_response_code(403);
      echo json_encode(['error' => 'Unauthorized access']);
      return;
    }

    $response = [
      'status' => 'success',
      'message' => 'Cron job connection working!',
      'server_time' => date('Y-m-d H:i:s'),
      'php_version' => PHP_VERSION
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
  }
}
