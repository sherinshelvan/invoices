<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ChangePassword extends MY_Controller {
	public $data = [];
	public function __construct(){
		parent::__construct();	
		$this->pageAccess('administrator');	
		$this->table_name      = TBL."users";
		$this->page_url        = 'change-password';
		$this->toast_msg_title = "Tax";
	}
	public function index(){
		$this->data['page_heading'] = "Change Password";
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->form_validation->set_rules('old', "Old Password", 'required');
		$this->form_validation->set_rules('new', "New Password", 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', "Confirm Password", 'required');

		if ($this->form_validation->run() === TRUE && $this->_valid_csrf_nonce() === TRUE){
			$identity = $this->session->userdata('identity');
			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));
			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('toast_message', $this->ion_auth->messages());
				$this->ion_auth->logout();
			}
			else
			{
				$this->session->set_flashdata('toast_message', $this->ion_auth->errors());
			}
			redirect(basename('change-password'), 'refresh');

		}
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->buildForm();
		$this->load->view('auth/change_password_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	private function buildForm($exist_details = []){
		$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
		$this->data['old'] = [
			'name'     => 'old',
			'id'       => 'old',
			'class'    => 'validate',
			'required' => true,
			'type'     => 'password',
			'value'    => $this->form_validation->set_value('old')
		];
		$this->data['new'] = [
			'name'     => 'new',
			'id'       => 'new',
			'class'    => 'validate',
			'required' => true,
			'type'     => 'password',
			'value'    => $this->form_validation->set_value('new'),
		];
		$this->data['new_confirm'] = [
			'name'     => 'new_confirm',
			'id'       => 'new_confirm',
			'class'    => 'validate',
			'required' => true,
			'type'     => 'password',
			'value'    => $this->form_validation->set_value('new_confirm'),
		];
		$this->data['csrf'] = $this->_get_csrf_nonce();
		$this->data['submit'] = [
			'name'    => 'submit',
			'value'   => 'submit',
			'type'    => 'submit',
			'content' => 'Save<i class="material-icons right">send</i>',
			'class'   => "waves-effect waves-light btn deep-purple darken-2",
		];
	}
}
