<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Settings extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		$this->model = new PermissionModel();
		if (($this->session->get('login')) == false && $this->session->get('role') != 1) {
			$data['dn_msg'] = 'Please Login';
			header('Location: ' . base_url() . '/login');
			exit;
		}
	}

	public function index()
	{
		if (!$this->model->permission_validate('temple_setting', 'edit')) {
			header('Location: ' . base_url() . '/dashboard');
		}

		$settings = $this->db->table('settings')->get()->getResultArray();
		$data['settings'] = [];
		foreach ($settings as $item) {
			$data['settings'][$item['type']][$item['setting_name']] = $item['setting_value'];
		}
		$data['discount_ledgers'] = $this->db->query("SELECT * FROM `ledgers` where group_id in (SELECT id FROM `groups` WHERE code in (6000) or parent_id in (SELECT id FROM `groups` WHERE code in (6000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (6000))))")->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('settings/add_settings', $data);
		echo view('template/footer');
	}

	public function save_settings()
	{
		try{
			if(count($_POST['settings']) > 0) {
				foreach ($_POST['settings'] as $type => $settings) {
					$allSettings = $this->db->table('settings')->where('type', $type)->get()->getResultArray();
											
					$existingSettings = [];
					foreach ($allSettings as $setting) {
						$existingSettings[$setting['setting_name']] = $setting['setting_value'];
					}

					foreach ($settings as $key => $value) {
						if (isset($existingSettings[$key])) {
							$data = [
								'setting_value' => $value,
								'updated_at' => date('Y-m-d H:i:s')
							];
							$this->db->table('settings')->where('type', $type)->where('setting_name', $key)->update($data);		
						} else {
							$data = [
								'type' => $type,
								'setting_name' => $key,
								'setting_value' => $value,
								'created_at' => date('Y-m-d H:i:s'),
								'updated_at' => date('Y-m-d H:i:s')
							];
							$this->db->table('settings')->insert($data);
						}
						unset($existingSettings[$key]);
					}

					foreach ($existingSettings as $missingKey => $missingValue) {
						$data = [
							'setting_value' => 0,
							'updated_at' => date('Y-m-d H:i:s')
						];
						$this->db->table('settings')->where('type', $type)->where('setting_name', $missingKey)->update($data);		
					}
				}
				$this->session->setFlashdata('succ', 'Settings Updated Successfully');
				return redirect()->to(base_url('/settings'));
			} 

		}catch (Exception $e) {
			$this->db->transRollback(); // Rollback the transaction if an error occurs
			$this->session->setFlashdata('fail', $e->getMessage());
			return redirect()->to("/dashboard");
		}
	}

	public function archanai_setting()
	{
		$data['archanai'] = $this->db->table('setting_archanai')->get()->getRowArray();

		echo view('template/header');
		echo view('template/sidebar');
		echo view('settings/archanai_setting', $data);
		echo view('template/footer');
	}

	public function save_archanai_setting()
	{
		$id = $_POST['id'];
		if (isset($_POST['no_print']))
			$data['no_print'] = 1;
		else
			$data['no_print'] = 0;
		if (isset($_POST['enable_print']))
			$data['enable_print'] = 1;
		else
			$data['enable_print'] = 0;
		if (isset($_POST['enable_sep_print']))
			$data['enable_sep_print'] = 1;
		else
			$data['enable_sep_print'] = 0;

		if (isset($_POST['enable_tender']))
			$data['enable_tender'] = 1;
		else
			$data['enable_tender'] = 0;

		$data['created_by'] = $this->session->get('log_id');

		if ($id == 0 || $id == '') {
			$data['created_at'] = date('Y-m-d H:i:s');
			$res = $this->db->table('setting_archanai')->insert($data);

			if ($res) {
				$this->session->setFlashdata('succ', 'Archanai settings Added Successfully');
				header("Location: " . base_url() . "/dashboard");
			} else {
				$this->session->setFlashdata('fail', 'Please Try Again');
				header("Location: " . base_url() . "/settings/archanai_setting");
			}

		} else {
			$data['modified_at'] = date('Y-m-d H:i:s');
			$res1 = $this->db->table('setting_archanai')->where('id', $id)->update($data);

			if ($res1) {
				$this->session->setFlashdata('succ', 'Archanai settings Updated Successfully');
				header("Location: " . base_url() . "/dashboard");
			} else {
				$this->session->setFlashdata('fail', 'Please Try Again');
				header("Location: " . base_url() . "/settings/archanai_setting");
			}
		}
	}


}


