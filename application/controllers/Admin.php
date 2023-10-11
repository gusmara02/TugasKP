<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->model('admin_model', 'admin');
        $this->load->model('Absensi_model', 'absensi');
        $this->load->model('GajiBerkala_model', 'gaji');
        $this->load->helper('tglindo');
    }

    public function index()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required|trim');
        $this->form_validation->set_rules('bagian', 'Bagian', 'required|trim');
        $this->form_validation->set_rules('nik', 'No NIK', 'required|trim|is_unique[mst_user.nik]', array(
            'is_unique' => 'No NIK sudah ada'
        ));
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[mst_user.username]', array(
            'is_unique' => 'Username sudah ada'
        ));
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', array(
            'matches' => 'Password tidak sama',
            'min_length' => 'password min 3 karakter'
        ));
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Beranda';
            $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();
            $data['user_perbulan'] = $this->admin->countUserPerbulan();
            $data['count_user'] = $this->admin->countJmlUser();
            $data['user_aktif'] = $this->admin->countUserAktif();
            $data['user_tak_aktif'] = $this->admin->countUserTakAktif();
            $data['bagian'] = $this->admin->ambilDataBagian();
            $data['usr'] = $this->db->get('mst_user')->result_array();
            $data['kode_nik'] = $this->admin->getKodeNik();

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('admin/index', $data);
            $this->load->view('templates/footer');
        } else {
            $data = array(
                'nama' => $this->input->post('nama', true),
                'jabatan' => $this->input->post('jabatan', true),
                'bagian' => $this->input->post('bagian', true),
                'nik' => $this->input->post('nik', true),
                'image' => 'default.jpg',
                'role_id' => $this->input->post('role_id', true),
                'username' => $this->input->post('username', true),
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'is_active' => 1
            );
            $this->db->insert('mst_user', $data);
            $this->session->set_flashdata('message', 'Simpan');
            redirect('admin/index');
        }
    }

    public function role()
    {
        $data['title'] = 'Role & Level';
        $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();
        $data['role'] = $this->db->get('user_role')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role', $data);
        $this->load->view('templates/footer');
    }

    public function roleAccess($role_id)
    {
        $data['title'] = 'Role Access';
        $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();

        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();
        $this->db->where('id !=', 1);
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates/footer');
    }

    public function changeAccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'role_id' => $role_id,
            'menu-id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);
        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Access Change !</div>');
    }

    public function edit()
    {
        $upload_image = $_FILES['image']['name'];
        if ($upload_image) {
            $config['allowed_types'] = 'gif|jpg|png';
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
        redirect('admin/index');
    }

    public function changePassword()
    {
        $current_password = $this->input->post('current_password');
        $new_password = $this->input->post('new_password1');

        if ($current_password == $new_password) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger font-weight-bolder text-center" role="alert">Password baru tidak boleh sama dengan password lama</div>');
            redirect('admin/index');
        } else {
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $this->db->set('password', $password_hash);
            $this->db->where('username', $this->session->userdata('username'));
            $this->db->update('mst_user');
            $this->session->set_flashdata('message', 'Simpan Perubahan');
            redirect('admin/index');
        }
    }

    public function get_user()
    {
        $id = $this->input->post('id');
        echo json_encode($this->db->get_where('mst_user', ['id' => $id])->row_array());
    }
    public function edit_user()
    {
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $jabatan = $this->input->post('jabatan');
        $nik = $this->input->post('nik');
        $bagian = $this->input->post('bagian');
        $is_active = $this->input->post('is_active');
        $role_id = $this->input->post('role_id');

        $this->db->set('nama', $nama);
        $this->db->set('jabatan', $jabatan);
        $this->db->set('nik', $nik);
        $this->db->set('bagian', $bagian);
        $this->db->set('is_active', $is_active);
        $this->db->set('role_id', $role_id);

        $this->db->where('id', $id);
        $this->db->update('mst_user');
        $this->session->set_flashdata('message', 'Simpan Perubahan');
        redirect('admin/index');
    }

    public function absensi()
    {
        $data['title'] = 'Riwayat Absensi User';
        $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();
        $data['user_cuti'] = $this->db->get_where('form_cuti', ['id_user' => $this->session->userdata('id')])->row_array();

        $data['absensi'] = $this->absensi->getAllAbsensi();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('absensi/riwayat_absensi', $data);
        $this->load->view('templates/footer');
    }

    public function gaji_berkala()
    {
        $data['title'] = 'Gaji Berkala';
        $data['user'] = $this->db->get_where('mst_user', ['username' => $this->session->userdata('username')])->row_array();
        $data['user_cuti'] = $this->db->get_where('form_cuti', ['id_user' => $this->session->userdata('id')])->row_array();

        $data['daftar_user'] = $this->admin->getAllUser();
        $data['daftar_gaji_berkala'] = $this->gaji->getAllGajiBerkala();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/gaji-berkala', $data);
        $this->load->view('templates/footer');

        $this->session->set_flashdata('message', null);
    }

    public function add_gaji_berkala()
    {
        $id = $this->input->post("id");
        $id_user = $this->input->post("id_user");
        $nama = $this->input->post("nama");
        $jabatan = $this->input->post("jabatan");
        $bagian = $this->input->post("bagian");
        $nik = $this->input->post("nik");
        $tgl_cetak = $this->input->post("tgl_cetak");
        if ($id) {
            $this->db->set('tgl_cetak', $tgl_cetak);

            $this->db->where('id', $id);
            $this->db->update('gaji_berkala');
            $this->session->set_flashdata('message', 'Simpan Perubahan');
        } else {


            $data = array(
                "id_user" => $id_user,
                "nama" => $nama,
                "jabatan" => $jabatan,
                "bagian" => $bagian,
                "nik" => $nik,
                "tgl_cetak" => $tgl_cetak,
            );
            $this->db->insert('gaji_berkala', $data);
            $this->session->set_flashdata('message', 'Simpan');
        }

        redirect('admin/gaji_berkala');
    }

    public function get_gaji_berkala()
    {
        $id = $this->input->post('id');
        echo json_encode($this->gaji->getGajiBerkalaById($id));
    }

    public function delete_gaji_berkala()
    {
        $id = $this->input->get('id');
        $this->db->where('id', $id)->delete("gaji_berkala");
        $this->session->set_flashdata('message', 'Data terhapus');
        redirect('admin/gaji_berkala');
    }

    public function cetak_gaji_berkala()
    {
        $id = $this->input->get('id');
        $gaji = $this->gaji->getGajiBerkalaById($id);

        $this->load->library('Pdf');
        $pdf = new FPDF('p', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->Ln(10);
        $pdf->SetFont('Times', 'B', 11);
        $pdf->Cell(190, 5, 'GAJI BERKALA', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Times', '', 11);
        $pdf->Cell(10, 5, 'Kepada Yth :', 0, 1);
        $pdf->Cell(10, 5, 'Kabag ', 0, 1);
        $pdf->Cell(10, 5, 'Di tempat.', 0, 1);
        $pdf->Ln(6);
        $pdf->Cell(10, 5, 'Nik : '.$gaji["nik"], 0, 1);
        $pdf->Cell(10, 5, 'Nama : '.$gaji["nama"], 0, 1);
        $pdf->Cell(10, 5, 'Jabatan : '.$gaji["jabatan"], 0, 1);
        $pdf->Cell(10, 5, 'Bagian : '.$gaji["bagian"], 0, 1);
        $pdf->Cell(10, 5, 'Tanggal cetak : '.$gaji["tgl_cetak"], 0, 1);
        $pdf->Output();
    }
}
