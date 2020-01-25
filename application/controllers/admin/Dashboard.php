<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends MY_Controller {
	public $data = [];
	public function __construct(){
		parent::__construct();	
		$this->pageAccess('administrator');	
	}
	public function index(){
		$this->data['page_heading'] = "Dashboard";
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->load->view('admin/dashboard/dashboard_view', $this->data);
		$this->load->view('admin/includes/footer');
	}

}
