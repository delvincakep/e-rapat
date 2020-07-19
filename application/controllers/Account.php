<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        // load the menu model
        $this->load->model('Account_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title']   = 'Account';
        $data['acc'] = $this->db->get('view_user_department', ['email' => $this->session->userdata('email')])->row_array();
        $data['user'] = $this->db->get_where('meeting_users', ['email' => $this->session->userdata('email')])->row_array();
        $data['account'] = $this->db->get('view_user_department')->result_array();
        $data['department'] = $this->db->get('meeting_department')->result_array();

        // var_dump($data['department']);
        // die;

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('account/index', $data);
        $this->load->view('layout/footer');
    }

    public function registration()
    {
        $data['title'] = 'Account';
        $data['acc'] = $this->db->get_where('view_user_department', ['email' => $this->session->userdata('email')])->row_array();
        $data['user'] = $this->db->get_where('meeting_users', ['email' => $this->session->userdata('email')])->row_array();
        $data['account'] = $this->db->get('view_user_department')->result_array();
        $data['department'] = $this->db->get('meeting_department')->result_array();


        // set Rules for menu
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[meeting_users.email]', [
            "is_unique" => "This Email already registered!"
        ]);


        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header', $data);
            $this->load->view('layout/sidebar', $data);
            $this->load->view('layout/topbar', $data);
            $this->load->view('account/index', $data);
            $this->load->view('layout/footer');
        } else {

            $uniqueid = uniqid();
            $password = "admin"; // $2y$10$rlSQG0XGwZnCtqv61NLKkONCAL1SUJdVeJ/95FFWOxSEeGJ9rqLwW
            $data = [
                'uniqueid' => $uniqueid,
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'image' => "default-avatar.jpg",
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => intval($this->input->post('is_active', true)),
                'department_id' => intval($this->input->post('department_id')),
                'date_created' => time()
            ];

            $this->db->insert('meeting_users', $data);
            $this->session->set_flashdata('messages', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Account has been Added!.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>');
            redirect('account/index');
        }
    }

    public function edituser($id)
    {
        $data['title']   = 'Account';
        $data['acc'] = $this->db->get_where('view_user_department', ['email' => $this->session->userdata('email')])->row_array();
        $data['user'] = $this->db->get_where('meeting_users', ['email' => $this->session->userdata('email')])->row_array();
        $data['account'] = $this->db->get('view_user_department')->result_array();
        $data['edit_acc'] = $this->db->get_where('view_user_department', ['id' => $id])->row_array();
        $data['department'] = $this->db->get('meeting_department')->result_array();



        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('account/edituser', $data);
        $this->load->view('layout/footer');
    }

    public function updateuser()
    {
        // set Rules for menu
        $this->form_validation->set_rules('name', 'Name', 'required|trim');

        if ($this->form_validation->run() == true) {

            $data = [
                'id' => intval($this->input->post('id')),
                'name' => htmlspecialchars($this->input->post('name')),
                'role_id' => $this->input->post('role_id', true),
                'is_active' => intval($this->input->post('is_active')),
                'department_id' => intval($this->input->post('department_id')),
                'date_updated' => time()
            ];

            $this->db->where('id', $data['id']);
            $this->db->update('meeting_users', $data);
            $this->session->set_flashdata('messages', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Account has beed Updated!.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>');
            redirect('account/index');
        }
    }

    public function delete($id)
    {
        // load the menu model
        $this->load->model('Account_model');

        // var_dump($where);
        // die;
        $this->Account_model->delete_account($id);

        $this->session->set_flashdata('messages', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Account has beed deleted!.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
        redirect('account/index');

        // echo 'Berhasil menghapus ' . $id;
    }
}