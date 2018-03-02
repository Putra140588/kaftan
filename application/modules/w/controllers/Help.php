<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Help extends CI_Controller{
	var $code;
	var $name;	
	var $class;
	var $sess_permalink;
	var $date_now;
	var $id_customer;
	public function __construct(){
		parent::__construct();
		$this->load->model('m_client');
		$this->load->helper('security');
		$this->load->model('m_public');		
		$this->class = strtolower(__CLASS__);				
		$this->sess_permalink = $this->session->userdata('permalink_sess');
		$this->date_now = date('Y-m-d H:i:s');
		$this->id_customer = $this->session->userdata('id_customer');
		$this->m_public->maintenance();
	}
	function index(){
		//permalink_sess(base_url());		
	}	
	function show_content(){
		$element='';
		$id = $this->input->post('value',true);
		$sql = $this->m_client->get_helper_detail(array('B.id_helper_detail'=>$id));
		if (count($sql) > 0){			
			$element .= '<h3>'.$sql[0]->title_helper_detail.'</h3><p>'.$sql[0]->content.'</p>';
		}		
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$element));
	}
}