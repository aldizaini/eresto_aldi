<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
     {
        if ($this->session->userdata('login')) {
            if ($this->session->userdata('id_level') == 1) {
                redirect('admin');
            }
        }
        $data = [
            'title' => 'Halaman Login'
        ];

        $this->form_validation->set_rules('username', 'username', 'required');
        $this->form_validation->set_rules('password', 'password', 'required');

        if ( $this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('login/index');
            $this->load->view('template/footer');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->db->get_where('user', ['username' => $username])->row_array();

            // jika username ada 
            if ($user) {
                if ($password == $user['password']) {
                    $data = [
                        'username' => $user['username'],
                        'id_level' => $user['id_level'],
                        'login' => 'login'
                    ];
                    $this->session->set_userdata($data);

                    if ($user['id_level'] == 1) {
                        redirect('admin');
                    } elseif ($user['id_level'] == 2) {
                        redirect('kasir');
                    } elseif ($user['id_level'] == 3) {
                        redirect('waiter');
                    } elseif ($user['id_level'] == 4) {
                        redirect('owner');
                    } elseif ($user['id_level'] == 5) {
                        redirect('pelanggan');
                    }
                } else {
                    $this->session->set_flashdata('message', 'password salah');
                    redirect('login/index');
                }
            } else {
                $this->session->set_flashdata('message', 'username tidak ada');
                redirect('login/index');
            }
        }
    }
    public function logout()
    {
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('id_level');

        $this->session->set_flashdata('message', '<div class="alert alert-dismissible alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> You have been logged out!</div>');
        redirect('login');
    }






}

	