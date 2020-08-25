<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	private $_layout = 'layout';
	private $_layout_login = 'login';

	function __construct(){
		parent::__construct();
		$this->load->config('ion_auth', TRUE);
		$this->tables  = $this->config->item('tables', 'ion_auth');
		$this->join    = $this->config->item('join', 'ion_auth');

		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('url');

		$this->load->database();

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	
		$site_config = $this->config->load('system');
        $this->data['nome_site'] = $site_config['nome_site'];

        $this->load->model('Auth_model');
	}

	/**
	 * Autenticação
	 */
	public function login(){
		$this->data['titulo'] = "Login de Usuário";
		$this->data['pagina'] = 'auth/auth/login';

		$this->form_validation->set_rules('identity', 'Login', 'required|xss_clean');
		$this->form_validation->set_rules('password', 'Senha', 'required|xss_clean');

		if ($this->form_validation->run()){ 
			$username = (string) $this->input->post('identity');
			$password = (string) $this->input->post('password');

			if($this->ion_auth->login($username, $password)){ 
				$url_redirect = 'contacts';
				$this->session->set_userdata('url_default', $url_redirect);
				$this->visual_sistema();

				redirect($this->session->userdata('url_default'), 'refresh');

			}else{ 
				$alert = array('message' => $this->ion_auth->errors(), 'return' => 'alert-danger');
				$this->session->set_flashdata('alert', $alert);
				redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}

		}else{  
			$alert = array('message' => $this->ion_auth->errors(), 'return' => 'alert-danger');

			if(validation_errors()){
				$this->data['alert'] = array('message'=>validation_errors(),'return'=>'alert-danger');
			}

			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'class' => 'form-control',
				'required' => 'required',
				'placeholder' => 'Login',
				'value' => $this->form_validation->set_value('identity')
			);

			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'required' => 'required',
				'placeholder' => 'Senha',
				'class' => 'form-control'
			);

 			$this->load->view('auth/login', $this->data);
		}
	}

	

	/**
	 * Criando sessão com os visuais do sistema
	 */
	private function visual_sistema(){
		$cor = '#3e6edf';
		$cor_hover = '#4268c2';
		$logo = 'logo.png';
		$logo_topo = 'logo_topo.png';

		$this->session->set_userdata('cor_topo', $cor);
		$this->session->set_userdata('cor_topo_hover', $cor_hover);
		$this->session->set_userdata('logo', $logo);
		$this->session->set_userdata('logo_topo', $logo_topo);
	}

	/**
	 * Sair do sistema
	 */
	public function logout() {
		$this->data['title'] = "Logout";

		$logout = $this->ion_auth->logout();

		redirect('auth/login', 'refresh');
	}

	
}
