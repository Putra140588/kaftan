<?php if (!defined('BASEPATH')) exit('No direct script acccess allowed');
class Maintenance extends CI_Controller{
	public function __construct(){
		parent::__construct();		
		$this->load->model('m_client');
	}
	function index(){
		($this->session->userdata('site_maintenance') == '') ? redirect($this->session->userdata('permalink_sess')) : '';
		$this->load->view('v_maintenance');
	}
}
