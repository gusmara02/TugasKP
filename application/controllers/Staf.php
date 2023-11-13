<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Staf extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->helper('tglindo');
        $this->load->model('Staf_model', 'user_cuti');
        $this->load->model('Cuti_model', 'cuti');
    }
    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();
        $data['user_cuti'] = $this->db->get_where('form_cuti', ['id_user' => $this->session->userdata('id')])->row_array();
        $data['sisa_cuti'] = $this->user_cuti->getSisaCuti();
        $id = $this->session->userdata('id');
        $id_user = $this->session->userdata('id');
        $data['cuti_user'] = $this->user_cuti->getCutiUser($id_user);
        $data['cuti_lain_user'] = $this->user_cuti->getCutiLainUser($id_user);

        // hitung output Cuti count
        $query = $this->user_cuti->cuti_count($id);
        $data['count'] = $query->pending;
        $record = $this->db->get("form_cuti");
        $data['records'] = $record->result();

        // COunt History Cuti
        $query = $this->user_cuti->historyCutiCount($id);
        $data['history_count'] = $query->pending;
        $record = $this->db->get("form_cuti");
        $data['records'] = $record->result();

        // COunt History Cuti
        $query = $this->user_cuti->historyCutiLainCount($id);
        $data['history_countcutilain'] = $query->pending;
        $record = $this->db->get("formcuti_lain");
        $data['records'] = $record->result();

        $data["chartData"] = $this->cuti->getChartData($id_user);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('staf/index', $data);
        $this->load->view('templates/footer');
    }

    public function edit()
    {
        $upload_image = $_FILES['image']['name'];
        if ($upload_image) {
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']     = '2048';
            $config['upload_path'] = './assets/img/profile';
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('image')) {
                $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();
                $old_image = $data['user']['image'];
                if ($old_image != 'default.jpg') {
                    unlink(FCPATH . 'assets/img/profile/' . $old_image);
                }
                $new_image = $this->upload->data('file_name');
                $this->db->set('image', $new_image);
            } else {
                echo $this->upload->display_errors();
            }
        }

        $nama = $this->input->post('nama');
        $jabatan = $this->input->post('jabatan');
        $bagian = $this->input->post('bagian');
        $nik = $this->input->post('nik');
        $username = $this->input->post('username');

        $this->db->set('nama', $nama);
        $this->db->set('jabatan', $jabatan);
        $this->db->set('bagian', $bagian);
        $this->db->set('nik', $nik);
        $this->db->where('username', $username);
        $this->db->update('mst_user');

        $this->session->set_flashdata('message', 'Simpan Perubahan');
        redirect('staf/index');
    }

    public function changepassword()
    {
        $current_password = $this->input->post('current_password');
        $new_password = $this->input->post('new_password1');

        if ($current_password == $new_password) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger font-weight-bolder text-center" role="alert">Password baru tidak boleh sama dengan password lama</div>');
            redirect('staf/index');
        } else {
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $this->db->set('password', $password_hash);
            $this->db->where('username', $this->session->userdata('username'));
            $this->db->update('mst_user');
            $this->session->set_flashdata('message', 'Simpan Perubahan');
            redirect('staf/index');
        }
    }

    public function profile()
    {
        $data['title'] = 'My Profile';
        $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('staf/profile', $data);
        $this->load->view('templates/footer');
    }

    function _cuti_check()
    {
        if (strtotime($this->input->post('cuti')) <= strtotime(date("Y-m-d"))) {
            $this->form_validation->set_message('_cuti_check', '%s harus lebih dari hari ini.');
            return false;
        }

        if (strtotime($this->input->post('cuti2')) < strtotime($this->input->post('cuti'))) {
            $this->form_validation->set_message('_cuti_check', 'Tanggal cuti awal dan akhir tidak sesuai.');
            return false;
        }

        return true;
    }

    public function add_cuti()
    {
        $this->form_validation->set_rules('input', 'Tanggal', 'required|trim');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');
        $this->form_validation->set_rules('jml_cuti', 'Ambil cuti', 'required|trim|numeric|greater_than[0]');
        $this->form_validation->set_rules('sisa_cuti', 'Sisa Cuti', 'required|trim|greater_than[-1]');
        $this->form_validation->set_rules('cuti', 'Tanggal Cuti 1', 'required|trim|callback__cuti_check');
        $this->form_validation->set_rules('cuti2', 'Tanggal Cuti 2', 'required|trim');
        $this->form_validation->set_rules('masuk', 'Tanggal Masuk', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('telp', 'No Telp', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Cuti';
            $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();
            $data['user_cuti'] = $this->db->get_where('form_cuti', ['id_user' => $this->session->userdata('id')])->row_array();
            $data['kode_unik'] = $this->user_cuti->getKodeUnik();
            $data['kode_unik2'] = $this->user_cuti->getKodeUnik2();

            $id = $this->session->userdata('id');
            $data['null_cuti'] = $this->user_cuti->getNullCuti($id);
            $data['sisa_cuti'] = $this->user_cuti->getSisaCuti();

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('staf/add_cuti', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'id_user' => $this->input->post('id_user'),
                'kode_unik' => $this->input->post('kode_unik'),
                'role_id' => $this->input->post('role_id'),
                'input' => $this->input->post('input'),
                'nik' => $this->input->post('nik'),
                'nama' => $this->input->post('nama'),
                'bagian' => $this->input->post('bagian'),
                'jabatan' => $this->input->post('jabatan'),
                'jenis_cuti' => $this->input->post('jenis_cuti'),
                'keterangan' => $this->input->post('keterangan'),
                'jml_cuti' => $this->input->post('jml_cuti'),
                'sisa_cuti' => $this->input->post('sisa_cuti'),
                'cuti' => $this->input->post('cuti'),
                'cuti2' => $this->input->post('cuti2'),
                'masuk' => $this->input->post('masuk'),
                'alamat' => $this->input->post('alamat'),
                'telp' => $this->input->post('telp'),
                'is_approve' => 1
            ];
            $this->db->insert('form_cuti', $data);
            $this->session->set_flashdata('message', 'Simpan data');
            redirect('staf');
        }
    }

    public function edit_cuti()
    {
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');
        $this->form_validation->set_rules('jml_cuti', 'Ambil cuti', 'required|trim|numeric|greater_than[0]');
        $this->form_validation->set_rules('sisa_cuti', 'Sisa Cuti', 'required|trim|greater_than[-1]');
        $this->form_validation->set_rules('cuti', 'Tanggal Cuti 1', 'required|trim');
        $this->form_validation->set_rules('cuti2', 'Tanggal Cuti 2', 'required|trim');
        $this->form_validation->set_rules('masuk', 'Tanggal Masuk', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('telp', 'No Telp', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Edit Cuti';
            $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();
            $data['sisa_cuti'] = $this->user_cuti->getSisaCuti();

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('staf/edit_cuti', $data);
            $this->load->view('templates/footer');
        } else {

            $id = $this->input->post('id');
            $nama = $this->input->post('nama');
            $jabatan = $this->input->post('jabatan');
            $bagian = $this->input->post('bagian');
            $nik = $this->input->post('nik');
            $keterangan = $this->input->post('keterangan');
            $jenis_cuti = $this->input->post('jenis_cuti');
            $jml_cuti = $this->input->post('jml_cuti');
            $sisa_cuti = $this->input->post('sisa_cuti');
            $cuti = $this->input->post('cuti');
            $cuti2 = $this->input->post('cuti2');
            $masuk = $this->input->post('masuk');
            $alamat = $this->input->post('alamat');
            $telp = $this->input->post('telp');

            $this->db->set('nama', $nama);
            $this->db->set('jabatan', $jabatan);
            $this->db->set('bagian', $bagian);
            $this->db->set('nik', $nik);
            $this->db->set('keterangan', $keterangan);
            $this->db->set('jenis_cuti', $jenis_cuti);
            $this->db->set('jml_cuti', $jml_cuti);
            $this->db->set('sisa_cuti', $sisa_cuti);
            $this->db->set('cuti', $cuti);
            $this->db->set('cuti2', $cuti2);
            $this->db->set('masuk', $masuk);
            $this->db->set('alamat', $alamat);
            $this->db->set('telp', $telp);
            $this->db->where('id', $id);
            $this->db->update('form_cuti');

            $this->session->set_flashdata('message', 'Update data');
            redirect('staf');
        }
    }

    public function history()
    {
        $data['title'] = 'History Cuti';
        $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();

        $id_user = $this->session->userdata('id');
        $data['user_cuti'] = $this->user_cuti->getAllCuti($id_user);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('staf/history', $data);
        $this->load->view('templates/footer');
    }

    public function detail_staf()
    {
        $data['title'] = 'Detail';
        $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();
        $data['user_cuti'] = $this->db->get_where('form_cuti', ['id_user' => $this->session->userdata('id')])->result_array();

        $nik = $this->session->userdata('nik');
        $data['detail_staf'] = $this->user_cuti->getDetailStaf($nik);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('staf/detail_staf', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_data($id)
    {
        $this->load->library('Pdf');
        $pdf = new FPDF('p', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->Ln(10);
        $pdf->SetFont('Times', 'B', 11);
        $pdf->Cell(190, 5, 'PERMOHONAN CUTI / IJIN', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Times', '', 11);
        $pdf->Cell(10, 5, 'Kepada Yth :', 0, 1);
        $pdf->Cell(10, 5, 'Kabag ', 0, 1);
        $pdf->Cell(10, 5, 'Di tempat.', 0, 1);
        $pdf->Ln(6);

        $sisa_cuti = $this->db->get_where('form_cuti', ['id' => $id])->result_array();
        foreach ($sisa_cuti as $row) {
            $pdf->Cell(10, 5, 'Yang bertanda tangan di bawah ini, Saya :', 0, 1);
            $pdf->Cell(26, 5, 'Nama', 0, 0);
            $pdf->Cell(2, 5, ':', 0, 0);
            $pdf->Cell(10, 5, ucwords($row['nama']), 0, 1);
            $pdf->Cell(26, 5, 'NIP', 0, 0);
            $pdf->Cell(2, 5, ':', 0, 0);
            $pdf->Cell(10, 5, $row['nik'], 0, 1);
            $pdf->Cell(26, 5, 'Unit Kerja', 0, 0);
            $pdf->Cell(2, 5, ':', 0, 0);
            $pdf->Cell(20, 5, $row['bagian'], 0, 1);
            $pdf->Cell(26, 5, 'Jabatan', 0, 0);
            $pdf->Cell(2, 5, ':', 0, 0);
            $pdf->Cell(20, 5, $row['jabatan'], 0, 1);
            $pdf->Ln(3);
            $pdf->Cell(60, 5, 'Dengan ini mengajukan permohonan : ' . $row['jenis_cuti'] . ', Selama ' . $row['jml_cuti'] . ' hari', 0, 1);
            $pdf->Cell(100, 5, 'mulai tanggal '  . format_indo($row['cuti']) . ' sampai tanggal ' . format_indo($row['cuti2']) . ', dan bekerja kembali pada tanggal ' . format_indo($row['masuk']) . '.', 0, 1);
            $pdf->Cell(58, 5, 'Selama cuti/ijin Saya dapat dihubungi ke :', 0, 1);
            $pdf->Ln(3);
            $pdf->Cell(26, 5, 'Alamat', 0, 0);
            $pdf->Cell(2, 5, ':', 0, 0);
            $pdf->Cell(10, 5, ucwords($row['alamat']), 0, 1);
            $pdf->Cell(26, 5, 'No. Telp/HP', 0, 0);
            $pdf->Cell(2, 5, ':', 0, 0);
            $pdf->Cell(10, 5, $row['telp'], 0, 1);
            $pdf->Ln(8);
            $pdf->Cell(95, 5, '', 0, 0, 'C');
            $pdf->Cell(95, 5, 'Batujajar, ' . format_indo($row['input']), 0, 1, 'C');
            $pdf->Cell(45, 5, 'Menyetujui', 0, 0, 'C');
            $pdf->Cell(75, 5, '', 0, 0, 'C');
            $pdf->Cell(45, 5, 'Hormat saya,', 0, 1, 'C');
            $pdf->Cell(45, 5, 'Atasan Langsung', 0, 0, 'C');
            $pdf->Ln(30);
            $pdf->Cell(45, 5, ucwords($row['atasan']), 0, 0, 'C');
            $pdf->Cell(75, 5, '', 0, 0, 'C');
            $pdf->Cell(45, 5, ucwords($row['nama']), 0, 1, 'C');
            $pdf->Ln(10);
            $pdf->Cell(190, 5, 'Mengetahui,', 0, 1, 'C');
            $pdf->Cell(190, 5, 'Kepala Bidang / Unit Kerja / Instalasi Terkait,', 0, 1, 'C');
            $pdf->Ln(20);
            $pdf->Cell(190, 5, ucwords($row['nama_kabid']), 0, 1, 'C');
            $pdf->Ln(20);
            $pdf->Cell(10, 7, 'No', 1, 0, 'C');
            $pdf->Cell(33, 7, 'Jenis Cuti/Ijin', 1, 0, 'C');
            $pdf->Cell(20, 7, 'Total Cuti', 1, 0, 'C');
            $pdf->Cell(20, 7, 'Masih Ada', 1, 0, 'C');
            $pdf->Cell(20, 7, 'Diambil', 1, 0, 'C');
            $pdf->Cell(20, 7, 'Sisa Cuti', 1, 0, 'C');
            $pdf->Cell(65, 7, 'Keterangan', 1, 1, 'C');
            $pdf->Cell(10, 7, '1', 1, 0, 'C');
            $pdf->Cell(33, 7, ucwords($row['jenis_cuti']), 1, 0, 'C');
            $pdf->Cell(20, 7, '12', 1, 0, 'C');
            $pdf->Cell(20, 7, $row['sisa_cuti'] + $row['jml_cuti'], 1, 0, 'C');
            $pdf->Cell(20, 7, $row['jml_cuti'], 1, 0, 'C');
            $pdf->Cell(20, 7, $row['sisa_cuti'], 1, 0, 'C');
            $pdf->Cell(65, 7, $row['keterangan'], 1, 1, 'C');
            $pdf->Ln(8);
        }
        $pdf->Output();
    }

    public function add_cuti_lain()
    {
        $this->form_validation->set_rules('cuti', 'Tanggal Cuti 1', 'required|callback__cuti_check');
        $this->form_validation->set_rules('cuti2', 'Tanggal Cuti 2', 'required|callback__cuti_check');
        $this->form_validation->set_rules('masuk', 'Tanggal Masuk', 'required|callback__cuti_check');

        $data = [
            'id_user' => $this->input->post('id_user', true),
            'kode_unik2' => $this->input->post('kode_unik2'),
            'role_id' => $this->input->post('role_id', true),
            'tgl_input' => $this->input->post('tgl_input', true),
            'nik' => $this->input->post('nik', true),
            'nama' => $this->input->post('nama', true),
            'jabatan' => $this->input->post('jabatan', true),
            'bagian' => $this->input->post('bagian', true),
            'keterangan' => $this->input->post('keterangan', true),
            'alamat' => $this->input->post('alamat', true),
            'jenis_cuti' => $this->input->post('jenis_cuti', true),
            'telp' => $this->input->post('telp', true),
            'cuti' => $this->input->post('cuti', true),
            'cuti2' => $this->input->post('cuti2', true),
            'masuk' => $this->input->post('masuk', true),
            'is_approve' => 1
        ];
        $this->db->insert('formcuti_lain', $data);
        $this->session->set_flashdata('message', 'Simpan data');
        redirect('staf/history_cutilain');
    }

    public function history_cutilain()
    {
        $data['title'] = 'History Cuti Lain';
        $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();

        $id_user = $this->session->userdata('id');
        $data['user_cuti'] = $this->user_cuti->getAllCutiLain($id_user);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('staf/history_cutilain', $data);
        $this->load->view('templates/footer');
    }

    public function edit_cutilain($id)
    {

        $this->form_validation->set_rules('nama', 'nama', 'required');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Edit Cuti Lain';
            $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();
            $data['user_cuti'] = $this->db->get_where('formcuti_lain', ['id' => $id])->row_array();

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('staf/edit_cutilain', $data);
            $this->load->view('templates/footer');
        } else {

            $id = $this->input->post('id');
            $id_user = $this->input->post('id_user');
            $tgl_input = $this->input->post('tgl_input');
            $nik = $this->input->post('nik');
            $nama = $this->input->post('nama');
            $jabatan = $this->input->post('jabatan');
            $bagian = $this->input->post('bagian');
            $keterangan = $this->input->post('keterangan');
            $alamat = $this->input->post('alamat');
            $jenis_cuti = $this->input->post('jenis_cuti');
            $telp = $this->input->post('telp');
            $cuti = $this->input->post('cuti');
            $cuti2 = $this->input->post('cuti2');
            $masuk = $this->input->post('masuk');

            $this->db->set('id_user', $id_user);
            $this->db->set('tgl_input', $tgl_input);
            $this->db->set('nik', $nik);
            $this->db->set('nama', $nama);
            $this->db->set('jabatan', $jabatan);
            $this->db->set('bagian', $bagian);
            $this->db->set('keterangan', $keterangan);
            $this->db->set('alamat', $alamat);
            $this->db->set('jenis_cuti', $jenis_cuti);
            $this->db->set('telp', $telp);
            $this->db->set('cuti', $cuti);
            $this->db->set('cuti2', $cuti2);
            $this->db->set('masuk', $masuk);

            $this->db->where('id', $id);
            $this->db->update('formcuti_lain');

            $this->session->set_flashdata('message', 'Update data');
            redirect('staf/history_cutilain');
        }
    }

    public function cetak_cutilain($id)
    {
        $this->load->library('Pdf');
        $pdf = new FPDF('p', 'mm', 'A4');
        $sisa_cuti = $this->db->get_where('formcuti_lain', ['id' => $id])->result_array();
        foreach ($sisa_cuti as $row) {
            $pdf->AddPage();
            $pdf->Ln(10);
            $pdf->SetFont('Times', 'B', 11);
            $pdf->Cell(190, 5, 'PERMOHONAN ' . strtoupper($row['jenis_cuti']), 0, 1, 'C');
            $pdf->Ln(3);
            $pdf->SetFont('Times', '', 11);
            $pdf->Cell(10, 5, 'Kepada Yth :', 0, 1);
            $pdf->Cell(10, 5, 'Kabag ', 0, 1);
            $pdf->Cell(10, 5, 'Di tempat.', 0, 1);
            $pdf->Ln(6);
            $pdf->Cell(10, 5, 'Dengan hormat,', 0, 1);
            $pdf->Ln(6);
            $pdf->Cell(10, 5, 'Yang bertanda tangan di bawah ini, Saya :', 0, 1);
            $pdf->Ln(2);
            $pdf->Cell(55, 5, 'Nama', 0, 0);
            $pdf->Cell(2, 5, ':', 0, 0);
            $pdf->Cell(10, 5, ucwords($row['nama']), 0, 1);
            $pdf->Cell(55, 5, 'NIP', 0, 0);
            $pdf->Cell(2, 5, ':', 0, 0);
            $pdf->Cell(10, 5, $row['nik'], 0, 1);
            $pdf->Cell(55, 5, 'Jabatan', 0, 0);
            $pdf->Cell(2, 5, ':', 0, 0);
            $pdf->Cell(20, 5, ucwords($row['jabatan']), 0, 1);
            $pdf->Cell(55, 5, 'Unit Kerja', 0, 0);
            $pdf->Cell(2, 5, ':', 0, 0);
            $pdf->Cell(20, 5, $row['bagian'], 0, 1);
            $pdf->Cell(55, 5, 'Tanggal Mulai Bekerja', 0, 0);
            $pdf->Cell(2, 5, ':', 0, 0);
            $pdf->Cell(20, 5, format_indo($row['masuk']), 0, 1);
            $pdf->Ln(3);
            $pdf->Cell(0, 5, 'Dengan ini Saya mengajukan ' . $row['jenis_cuti'] . ' terhitung mulai tanggal ' . format_indo($row['cuti']) . ' sampai tanggal ' . format_indo($row['cuti2']), 0, 1);
            $pdf->Cell(0, 5, 'dan bekerja kembali pada tanggal ' . format_indo($row['masuk']) . '.', 0, 1);
            $pdf->Ln(3);
            $pdf->Cell(10, 5, 'Adapun yang mendasari permohonan Saya adalah ' . $row['keterangan'] . '.', 0, 1);
            $pdf->Ln(3);
            $pdf->Cell(10, 5, 'Selama ' . ucwords($row['jenis_cuti']) . ', Saya dapat dihubungi ke :', 0, 1);
            $pdf->Cell(30, 5, 'Alamat', 0, 0);
            $pdf->Cell(2, 5, ':', 0, 0);
            $pdf->Cell(10, 5, ucwords($row['alamat']), 0, 1);
            $pdf->Cell(30, 5, 'Telepon', 0, 0);
            $pdf->Cell(2, 5, ':', 0, 0);
            $pdf->Cell(10, 5, $row['telp'], 0, 1);
            $pdf->Ln(5);
            $pdf->Cell(62, 5, 'Menyetujui', 0, 0, 'C');
            $pdf->Cell(62, 5, '', 0, 0, 'C');
            $pdf->Cell(62, 5, 'Batujajar, ' . format_indo($row['tgl_input']), 0, 1, 'C');
            $pdf->Cell(62, 5, 'Atasan Langsung,', 0, 0, 'C');
            $pdf->Cell(62, 5, '', 0, 0, 'C');
            $pdf->Cell(62, 5, 'Pemohon', 0, 1, 'C');
            $pdf->Ln(15);
            $pdf->Cell(62, 5, ucwords($row['atasan']), 0, 0, 'C');
            $pdf->Cell(62, 5, '', 0, 0, 'C');
            $pdf->Cell(62, 5, ucwords($row['nama']), 0, 1, 'C');
            $pdf->Cell(186, 5, 'Mengetahui, ', 0, 1, 'C');
            $pdf->Cell(62, 5, 'Kepala ' . ucwords($row['kabag']), 0, 0, 'C');
            $pdf->Cell(62, 5, '', 0, 0, 'C');
            $pdf->Cell(62, 5, 'Kepala Bagian ' . ucwords($row['direktur']), 0, 1, 'C');
            $pdf->Ln(15);
            $pdf->Cell(62, 5, ucwords($row['nama_kabag']), 0, 0, 'C');
            $pdf->Cell(62, 5, '', 0, 0, 'C');
            $pdf->Cell(62, 5, ucwords($row['nama_direktur']), 0, 1, 'C');
        }

        $pdf->Output();
    }

    public function history_cutitahunan()
    {
        $data['title'] = 'View Cuti Pertahun';
        $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();
        $id_user = $this->session->userdata('id');
        $data['user_cuti'] = $this->user_cuti->getAllCuti($id_user);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('staf/history_cutitahunan', $data);
        $this->load->view('templates/footer');
    }

    public function view_cutitahunan()
    {
        $data['title'] = 'History Cuti Tahun Lalu';
        $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();
        $id_user = $this->session->userdata('id');
        $data['cuti_saya'] = $this->db->get_where('history_cuti', ['id_user' => $id_user])->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('staf/view_cutitahunan', $data);
        $this->load->view('templates/footer');
    }

    public function view_cutitahunan1()
    {
        $data['title'] = 'History Cuti Tahun Lalu';
        $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();
        $id_user = $this->session->userdata('id');
        $tahun = $this->input->post('tahun');
        $data['pertahun'] = $this->user_cuti->getHistoryCutiTahunan($tahun, $id_user);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('staf/data/view_cutitahunan', $data);
        $this->load->view('templates/footer');
    }
}
