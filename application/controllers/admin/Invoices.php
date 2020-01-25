<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'libraries/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
class Invoices extends MY_Controller {
	public $data = [];
	public function __construct(){
		parent::__construct();	
		$this->pageAccess('administrator');	
		$this->table_name      = TBL."invoices";
		$this->tbl_clients     = TBL."clients";
		$this->tbl_currency    = TBL."currency";
		$this->tbl_taxes       = TBL."taxes";
		$this->tbl_settings    = TBL."settings";
		$this->tbl_users    = TBL."users";
		$this->page_url        = 'invoices';
		$this->toast_msg_title = "Invoice";
	}
	public function index(){
		$this->data['page_heading'] = "Invoices";
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->load->view('admin/invoices/list_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	public function view_invoice($id = ""){
		$edit_condition = [
			'table_name' => $this->table_name,
			'condition'  => "invoice_no = ".(int)$id
		];
		$exist_details = $this->editPageValidate((object)$edit_condition);
		$dompdf = new Dompdf();
		$this->data["invoice_address"] = $this->_settings("invoice_address");
		$this->data["invoice_thanks_msg"] = $this->_settings("invoice_thanks_msg");
		$this->data["invoice_bank_account"] = $this->_settings("invoice_bank_account");
		$logo = file_get_contents(APPPATH."assets/images/invoice_logo.png");
		$this->data["company_logo"] = 'data:image/png;base64,' . base64_encode($logo);
		$join_array[]   = array("{$this->tbl_clients} b", "a.client_id = b.id", "left");
		$join_array[]   = array("{$this->tbl_currency} c", "a.currency = c.code", "left");
		$invoice         = $this->common_model->getJoinQueryResult("a.*, b.name, b.email, b.phone, b.address, b.gstin_no, c.symbol as currency_symbol", "$this->table_name a", $join_array, "a.invoice_no = {$id}", null, null, null, "row");
		$this->data['invoice'] = $invoice;
		$pdf_content = $this->load->view('admin/invoices/pdf_download_view', $this->data, true);
		$dompdf->load_html(html_entity_decode($pdf_content));
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream("invoice-{$id}.pdf", array("Attachment" => false));
		
	}
	public function invoice_no_exist($invoice_no, $id=""){
		$invoice_details = $this->common_model->fetchExistingDetails("*", $this->table_name, "invoice_no = '{$invoice_no}' AND id != {$id}");
		if($invoice_details){
			 $this->form_validation->set_message('invoice_no_exist', 'The {field} already exist');
			return FALSE;
		}
		return TRUE;
	}
	public function edit($edit = ""){
		$this->data['page_heading'] = "Edit Invoices";
		$this->data['action_url'] = "{$this->page_url}/edit/{$edit}";
		$edit_condition = [
			'table_name' => $this->table_name,
			'condition'  => "id = ".(int)$edit
		];
		$exist_details = $this->editPageValidate((object)$edit_condition);
		$join_array[]   = array("{$this->tbl_clients} b", "a.client_id = b.id", "left");
		$join_array[]   = array("{$this->tbl_currency} c", "a.currency = c.code", "left");
		$result         = $this->common_model->getJoinQueryResult("a.*, b.name, b.email, b.phone, b.address, b.gstin_no, c.symbol as currency_symbol", "$this->table_name a", $join_array, "a.id = {$edit}", null, null, null, "row");
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->form_validation->set_rules('client_id', 'Client', "trim|required|xss_clean");
		$this->form_validation->set_rules('invoice_no', 'Invoice Number', "trim|required|callback_invoice_no_exist[{$edit}]|xss_clean");
		$this->form_validation->set_rules('invoice_date', 'Invoice Date', "trim|required|xss_clean");
		$this->form_validation->set_rules('tax_id', 'Tax', "trim|xss_clean");
		$this->form_validation->set_rules('currency', 'Currency', "trim|required|xss_clean");
		$this->form_validation->set_rules('note', 'Note', "trim|xss_clean");
		if ($this->form_validation->run() === TRUE /*&& $this->_valid_csrf_nonce() === TRUE*/){
			$items        = "";
			$extra_items  = "";
			$total_amount = 0;
			$tax_details = $this->common_model->fetchExistingDetails("*", $this->tbl_taxes, "id = {$this->input->post("tax_id")}");
			if($this->input->post("items")){
				$items = json_encode($this->input->post("items"));
				foreach ($this->input->post("items") as $key => $row) {
					$row['price'] = is_numeric($row['price'])?$row['price']:0;
					$row['unit']  = is_numeric($row['unit'])?$row['unit']:0;
					$amount       = $row['price'] * $row['unit'];
					$total_amount += $amount;
				}
			}
			if($this->input->post("extra_items")){
				$extra_items  = $this->input->post("extra_items");
				$total_amount += array_sum(array_column($extra_items, "amount"));
				$extra_items  = json_encode($extra_items);
			}
			$update_data = [
				"invoice_no"   => $this->input->post("invoice_no", true),
				"invoice_date" => date("Y-m-d", strtotime($this->input->post("invoice_date"))),
				"client_id"    => $this->input->post("client_id", true),
				"tax_id"       => $this->input->post("tax_id", true),
				"tax_details"  => json_encode($tax_details),
				"currency"     => $this->input->post("currency", true),
				"note"     => $this->input->post("note", true),
				"total_amount" => $total_amount,
				"items"        => $items,
				"extra_items"  => $extra_items,
				"created_by"   => $this->user->id		
			];
			$this->common_model->update_data($this->table_name, $update_data, "id = {$edit}");
			 $this->session->set_flashdata('toast_message', sprintf('%s', "{$this->toast_msg_title} successfully updated."));
			redirect($this->page_url, 'refresh');
		}
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->data['result'] = $result;
		$this->buildForm($exist_details);
		$this->load->view('admin/invoices/edit_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	public function add(){
		$this->data['page_heading'] = "Add Invoices";
		$this->data['action_url'] = base_url($this->page_url)."/add";
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->form_validation->set_rules('client_id', 'Client', "trim|required|xss_clean");
		$this->form_validation->set_rules('invoice_no', 'Invoice Number', "trim|required|is_unique[{$this->table_name}.invoice_no]|xss_clean");
		$this->form_validation->set_rules('invoice_date', 'Invoice Date', "trim|required|xss_clean");
		$this->form_validation->set_rules('tax_id', 'Tax', "trim|xss_clean");
		$this->form_validation->set_rules('currency', 'Currency', "trim|required|xss_clean");
		$this->form_validation->set_rules('note', 'Note', "trim|xss_clean");
		if ($this->form_validation->run() === TRUE /*&& $this->_valid_csrf_nonce() === TRUE*/){
			$tax_details = $this->common_model->fetchExistingDetails("*", $this->tbl_taxes, "id = {$this->input->post("tax_id")}");
			$items        = "";
			$extra_items  = "";
			$total_amount = 0;
			if($this->input->post("items")){
				$items = json_encode($this->input->post("items"));
				foreach ($this->input->post("items") as $key => $row) {
					$row['price'] = is_numeric($row['price'])?$row['price']:0;
					$row['unit']  = is_numeric($row['unit'])?$row['unit']:0;
					$amount       = $row['price'] * $row['unit'];
					$total_amount += $amount;
				}
			}
			if($this->input->post("extra_items")){
				$extra_items  = $this->input->post("extra_items");
				$total_amount += array_sum(array_column($extra_items, "amount"));
				$extra_items  = json_encode($extra_items);
			}
			$insert_data = [
				"invoice_no"   => $this->input->post("invoice_no", true),
				"invoice_date" => date("Y-m-d", strtotime($this->input->post("invoice_date"))),
				"client_id"    => $this->input->post("client_id", true),
				"tax_id"       => $this->input->post("tax_id", true),
				"tax_details"  => json_encode($tax_details),
				"currency"     => $this->input->post("currency", true),
				"note"     => $this->input->post("note", true),
				"total_amount" => $total_amount,
				"items"        => $items,
				"extra_items"  => $extra_items,
				"created_by"   => $this->user->id	
			];
			$this->common_model->insert_data($this->table_name, $insert_data);
			 $this->session->set_flashdata('toast_message', sprintf('%s', "{$this->toast_msg_title} successfully created."));
			redirect($this->page_url, 'refresh');
		}
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->buildForm();
		$this->load->view('admin/invoices/edit_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	public function alter_table(){
		$form_data = $this->input->post();
		$form_data['currency'] = (!isset($form_data['currency']) || !$form_data['currency'])?$this->_settings('invoice_currency') : $form_data['currency'];
		$currency = $this->common_model->fetchExistingDetails("*", $this->tbl_currency, "code='{$form_data['currency']}'");
		$form_data['currency'] = $currency->symbol;
		if(isset($form_data['tax_id']) && $form_data['tax_id']){
			$form_data['tax_details'] = $this->common_model->fetchExistingDetails("*", $this->tbl_taxes, "id='{$form_data['tax_id']}'");
		}
		$this->load->view('admin/invoices/edit_invoice_table_body_view', $form_data);
		// echo json_encode($form_data);
	}
	public function settings(){
		$allowed_fields   = array("invoice_address", "invoice_currency", "invoice_bank_account", "invoice_thanks_msg");
		$settings = $this->_settings($allowed_fields);
		
		$this->data['page_heading'] = "Invoice Settings";
		$this->data['action_url'] = "{$this->page_url}/settings";
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->form_validation->set_rules('invoice_currency', 'Currency', "trim|required|xss_clean");
		$this->form_validation->set_rules('invoice_address', 'Address', "trim|required|xss_clean");
		if ($this->form_validation->run() === TRUE && $this->_valid_csrf_nonce() === TRUE){
			$form_data        = (object)$this->input->post();
			foreach ($form_data as $key => $value) {
				if(in_array($key, $allowed_fields)){
					$update_array[] = array(
						"field_name" => $key,
						"value"      => $value
					);
				}				
			}
			$this->common_model->update_batch($this->tbl_settings, $update_array, 'field_name'); 
			$this->session->set_flashdata('toast_message', sprintf('%s', "{$this->toast_msg_title} settings successfully updated."));
			redirect("{$this->page_url}/settings", 'refresh');
		}
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		
		$currency   = $this->common_model->fetAllResults("*, CONCAT_WS('-', country, currency, symbol) AS name", $this->tbl_currency);
		$options   = [];
		$options[''] = "--Select Currency--";
		if(count($currency) > 0){
			$options += array_combine(array_column($currency, "code"), array_column($currency, "name"));
		}
		$this->data['invoice_currency'] = [
			'name'     => 'invoice_currency',
			'id'       => 'invoice_currency',
			'required' => true,
			'class'    => 'invoice_currency',
			'options'  => $options,
			'selected'       => ( $this->form_validation->set_value('invoice_currency')?: (!count($_POST) && isset($settings->invoice_currency)? $settings->invoice_currency : '') ) 
		];
		$this->data['invoice_address'] = [
			'name'     => 'invoice_address',
			'id'       => 'invoice_address',
			'class'    => 'materialize-textarea',
			'style'    => 'min-height: 150px;',
 			'value'       => ( $this->form_validation->set_value('invoice_address')?: (!count($_POST) && isset($settings->invoice_address)? $settings->invoice_address : '') ) 
		];
		$this->data['invoice_bank_account'] = [
			'name'     => 'invoice_bank_account',
			'id'       => 'invoice_bank_account',
			'class'    => 'materialize-textarea',
			'style'    => 'min-height: 150px;',
 			'value'       => ( $this->form_validation->set_value('invoice_bank_account')?: (!count($_POST) && isset($settings->invoice_bank_account)? $settings->invoice_bank_account : '') ) 
		];
		$this->data['invoice_thanks_msg'] = [
			'name'     => 'invoice_thanks_msg',
			'id'       => 'invoice_thanks_msg',
			'class'    => 'materialize-textarea',
			'style'    => 'min-height: 150px;',
 			'value'       => ( $this->form_validation->set_value('invoice_thanks_msg')?: (!count($_POST) && isset($settings->invoice_thanks_msg)? $settings->invoice_thanks_msg : '') ) 
		];
		$this->data['csrf'] = $this->_get_csrf_nonce();
		$this->data['submit'] = [
			'name'    => 'submit',
			'value'   => 'submit',
			'type'    => 'submit',
			'content' => 'Save<i class="material-icons right">send</i>',
			'class'   => "waves-effect waves-light btn deep-purple darken-2",
		];
		$this->data['settings'] = $settings;
		$this->load->view('admin/invoices/settings_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	public function client_details(){
		$return = "Please Select Client.";
		$client_id = $this->input->post('client_id');
		$client = $this->common_model->fetchExistingDetails("*", $this->tbl_clients, "id = {$client_id}");
		if($client){
			$return = "<p>{$client->name}</p>";
			$return .= "<p>{$client->email}</p>";
			$return .= "<p>{$client->phone}</p>";
			$return .= "<p>".nl2br($client->address)."</p>";
			if($client->gstin_no){
				$return .= "<p>GSTIN NO : {$client->gstin_no}</p>";
			}
		}
		echo $return; exit();
	}
	private function buildForm($exist_details = []){
		$currency_code = $this->_settings("invoice_currency");
		$currency = $this->common_model->fetchExistingDetails("*", $this->tbl_currency, "code='{$currency_code}'");
		$this->data['default_currency'] = $currency->symbol;
		$this->data['invoice_address'] = $this->_settings('invoice_address');
		$options[''] = "--Select Client--";
		$clients   = $this->common_model->fetAllResults("*", $this->tbl_clients, "active = '1'");
		if(count($clients) > 0){
			$options += array_combine(array_column($clients, "id"), array_column($clients, "name"));
		}
		$this->data['client_id'] = [
			'name'     => 'client_id',
			'id'       => 'client_id',
			'required' => true,
			'class'    => 'client_id',
			'options'  => $options,
			'selected'       => ( $this->form_validation->set_value('client_id') ?: (!count($_POST) && isset($exist_details->client_id)? $exist_details->client_id : '') )
		];
		$this->data['invoice_no'] = [
			'name'     => 'invoice_no',
			'id'       => 'invoice_no',
			'class'    => 'validate',
			'required' => true,
			'type'     => 'text',
			'value'    => ( $this->form_validation->set_value('invoice_no') ?: (!count($_POST) && isset($exist_details->invoice_no)? $exist_details->invoice_no : '') )
		];
		$this->data['invoice_date'] = [
			'name'     => 'invoice_date',
			'id'       => 'invoice_date',
			'class'    => 'validate invoice-date',
			'required' => true,
			'type'     => 'text',
			'value'    => ( $this->form_validation->set_value('invoice_date') ?: (!count($_POST) && isset($exist_details->invoice_date)? date("d-m-Y", strtotime($exist_details->invoice_date)) : date("d-m-Y")) )
		];
		$options   = [];
		$options[''] = "--Select Currency--";
		$currency   = $this->common_model->fetAllResults("*, CONCAT_WS('-', country, currency, symbol) AS name", $this->tbl_currency);
		if(count($currency) > 0){
			$options += array_combine(array_column($currency, "code"), array_column($currency, "name"));
		}
		$this->data['currency'] = [
			'name'     => 'currency',
			'id'       => 'currency',
			'required' => true,
			'class'    => 'currency',
			'options'  => $options,
			'selected'       => ( $this->form_validation->set_value('currency') ?: (!count($_POST) && isset($exist_details->currency)? $exist_details->currency : $this->_settings('invoice_currency')) )
		];
		$options   = [];
		$options[''] = "--Select Tax--";
		$taxes = $this->common_model->fetAllResults("*", $this->tbl_taxes, "active='1'");
		if(count($taxes) > 0){
			$options += array_combine(array_column($taxes, "id"), array_column($taxes, "name"));
		}
		$this->data['tax_id'] = [
			'name'     => 'tax_id',
			'id'       => 'tax_id',
			'class'    => 'tax_id',
			'options'  => $options,
			'selected'       => ( $this->form_validation->set_value('tax_id') ?: (!count($_POST) && isset($exist_details->tax_id)? $exist_details->tax_id : "" ))
		];
		$this->data['note'] = [
			'name'     => 'note',
			'id'       => 'note',
			'class'     => 'materialize-textarea',
			'value'    => ( $this->form_validation->set_value('note') ?: (!count($_POST) && isset($exist_details->note)? $exist_details->note : '') )
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
	public function delete($id = ''){
		$edit_condition = [
			'table_name' => $this->table_name,
			'condition'  => "id = ".(int)$id
		];
		$exist_details = $this->editPageValidate((object)$edit_condition);
		$this->common_model->delete_data($this->table_name, "id = {$id}");
		$this->session->set_flashdata('toast_message', sprintf('%s', "{$this->toast_msg_title} successfully deleted."));
		redirect($this->page_url, 'refresh');
	}
	public function ajax_table(){
		## Read value
		$datatable = $this->getDatatablePost();
		$condition 			 = null;
		if($datatable->searchValue != ''){
			$search_by = ["a.invoice_no", "a.invoice_date", "a.total_amount", "b.name", "c.first_name"];
			$condition .= "(a.id like '%{$datatable->searchValue}%'";
			foreach ($search_by as $key => $value) {
				$condition .= " OR {$value} like '%{$datatable->searchValue}%'";
			}
			$condition .= ")";
		}		
		$order_by = "{$datatable->columnName} {$datatable->columnSortOrder}";
		$join_array[]   = array("{$this->tbl_clients} b", "a.client_id = b.id", "left");
		$join_array[]   = array("{$this->tbl_users} c", "a.created_by = c.id", "left");
		$result         = $this->common_model->getJoinQueryResult("a.*, b.name as client_name, b.email, c.first_name, c.last_name", "$this->table_name a", $join_array, $condition, $order_by, $datatable->row, $datatable->rowperpage);

		// $result        = $this->common_model->fetAllResults("*", $this->table_name, $condition, $order_by, $datatable->row, $datatable->rowperpage);
		$total_records = $this->common_model->getCountOfAllResult($this->table_name);
		$filter_records = count($this->common_model->getJoinQueryResult("a.*", "$this->table_name a", $join_array, $condition));
		$data = [];
		foreach ($result as $key => $value) {
			$actions = sprintf('<a href="%s" target="_blank" title="View"> <i class="material-icons">remove_red_eye</i></a>', base_url($this->page_url."/view-invoice/{$value->invoice_no}"));
			$actions .= sprintf('<a href="%s" title="Edit"> <i class="material-icons">edit</i></a>', base_url($this->page_url."/edit/{$value->id}"));
			$actions .= sprintf('<a href="%s" title="Delete" data-id="%d" class="modal-trigger delete row-delete"> <i class="material-icons">delete</i></a>', "#deleteModal", $value->id);
			$data[] = [
				"invoice_no"   => "{$value->invoice_no}",
				"invoice_date" => date("d-m-Y", strtotime($value->invoice_date)),
				"client_id"       => "{$value->client_name} ({$value->email})",
				"total_amount" => $value->total_amount,
				"created_by"   => "{$value->first_name} {$value->last_name}",
				"created_date" => $value->created_date,
				"actions"      => $actions,
			];
		}
		## Response
		$response = array(
			"draw"                 => intval($datatable->draw),
			"iTotalRecords"        => $total_records,
			"iTotalDisplayRecords" => $filter_records,
			"aaData"               => $data
		);
		echo json_encode($response);
	}
}
