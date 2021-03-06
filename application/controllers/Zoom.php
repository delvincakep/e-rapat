<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Zoom extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->helper(array('alert'));
        $this->load->model('Account_model');
        $this->load->model('Zoom_model');
        $this->load->model('Meeting_model');
    }

    public function index()
    {
        $data['title'] = 'Master Data Zoom';
        $data['user'] = $this->Account_model->get_admin($this->session->userdata('email'));
        $data['zoom'] = $this->Zoom_model->getzoom_where_active();
        $data['users'] = $this->Account_model->get_all_users();
        $data['meeting'] = $this->Meeting_model->get_all_meeting_by_sesi($this->session->userdata('email'));

        $this->load->library('form_validation');
        $this->form_validation->set_rules('zoom_id', 'Zoom ID', 'required|trim|xss_clean');
        $this->form_validation->set_rules('user_id', 'Nama Pengguna', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('layout/sidebar', $data);
            $this->load->view('layout/topbar', $data);
            $this->load->view('zoom/index', $data);
            $this->load->view('layout/footer');
        } else {

            $data = [
                'idzoom'   => htmlspecialchars($this->input->post('idzoom', true)),
                'user_id'   => intval($this->input->post('user_id', true)),
                'is_active' => intval($this->input->post('is_active', true)),
            ];

            $this->Zoom_model->insert_zoom($data);
            $this->db->set('zoomid', $data['idzoom']);
            $this->db->where('id', $data['user_id']);
            $this->db->update('meeting_users');
            $this->session->set_flashdata('messages', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Selamat!</strong> Zoom ID baru telah ditambahkan!</div>');
            redirect('zoom');
        }
    }

    public function updatezoom()
    {
        if ($this->input->post('id')) {
            $data = array(
                'idzoom'   => htmlspecialchars($this->input->post('idzoom', true)),
                'user_id'   => intval($this->input->post('user_id', true)),
                'is_active' => intval($this->input->post('is_active', true)),
            );

            $this->Zoom_model->update_zoom($data, $this->input->post('id', true));

            $this->db->set('zoomid', $data['idzoom']);
            $this->db->where('id', $data['user_id']);
            $this->db->update('meeting_users');
            $this->session->set_flashdata('messages', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Selamat!</strong> Zoom ID baru telah diubah!</div>');
            redirect('zoom');
        }
    }

    public function deletezoom()
    {
        $id = $this->input->post('id');

        $this->Zoom_model->delete_zoom($id);
        $this->session->set_flashdata('messages', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Selamat!</strong> Zoom ID baru telah dihapus!</div>');
        redirect('zoom');
    }
}
