<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absensi extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->library('form_validation');
		$this->load->helper('tglindo');
		$this->load->model('Absensi_model', 'absensi');
	}

	public function index()
	{
		$data['title'] = 'Riwayat Absensi';
		$data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();
		$data['user_cuti'] = $this->db->get_where('form_cuti', ['id_user' => $this->session->userdata('id')])->row_array();

		$data['absensi'] = $this->absensi->getAbsensiUserId($this->session->userdata('id'));

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('absensi/riwayat_absensi', $data);
		$this->load->view('templates/footer');
	}


	public function check()
	{
		if (!getHoliday("Y-m-d")) {
			redirect('auth/blocked');
		}

		$user = $this->session->get_userdata();

		$this->absensi->check($user);

		if ($user['role_id'] == 1) {
			redirect('admin');
		} elseif ($user['role_id'] == 2) {
			redirect('sdm');
		} elseif ($user['role_id'] == 3) {
			redirect('kaur');
		} else {
			redirect('staf');
		}
	}
}
