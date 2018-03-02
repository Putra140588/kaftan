<?php if (!defined('BASEPATH')) exit('No direct script acccess allowed');
class Maintenance extends CI_Controller{
	public function __construct(){
		parent::__construct();		
		$this->load->model('m_admin');
	}
	function index(){
		(MAINTENANCE == false) ? redirect($this->session->userdata('links')) : '';
		$this->load->view('vw_maintenance');
	}
}
