<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Customer extends CI_Controller{
	var $code;
	var $name;	
	var $class;
	var $sess_permalink;
	var $date_now;
	var $id_customer;
	var $limit=5;//pagination limit my order
	public function __construct(){
		parent::__construct();
		$this->load->model('m_client');
		$this->load->helper('security');
		$this->load->model('m_public');		
		$this->load->library('ajax_pagination');
		$this->class = strtolower(__CLASS__);				
		$this->sess_permalink = $this->session->userdata('permalink_sess');
		$this->date_now = date('Y-m-d H:i:s');
		$this->id_customer = $this->session->userdata('id_customer');
		$this->m_public->maintenance();
		
	}
	function index(){
		//permalink_sess(base_url());		
	}		
	function pay_confirm(){
		permalink_sess(FOMODULE.'/'.$this->class.'/pay_confirm');
		$this->set_form(array('id_order',lang('text4'),'required|xss_clean|callback_check_order'));
		$this->set_form(array('bankdest',lang('bankdest'),'required|xss_clean'));
		$this->set_form(array('bankfrom',lang('bankfrom'),'required|xss_clean'));
		$this->set_form(array('rekeningfrom',lang('rekeningfrom'),'required|xss_clean'));
		$this->set_form(array('transfmethod',lang('transfmethod'),'required|xss_clean'));
		$this->set_form(array('amountrans',lang('amountrans'),'trim|required|xss_clean'));
		$this->set_form(array('transdate',lang('transdate'),'required|xss_clean'));
		if ($this->form_validation->run() == false){
			$data['tab_title'] = lang('tf6');
			$data['meta_keywords'] = lang('tf6');
			$data['meta_description'] = lang('tf6');
			foreach ($this->m_public->product_footer_list() as $row=>$val)
				$data[$row] = $val;
			$data['paymethod'] = $this->m_client->get_payment(array('B.pay_code'=>8801),true);//only transfer
			$data['transmethod'] = $this->m_client->get_table('tb_method_transfer','*');
			$data['content'] = 'customer/payconfirm/v_pay_confirm';
			$this->load->view('w/v_main_body',$data);
		}else{
			$this->save_pay_confirm();
		}
	}
	function set_form($param){
		return $this->form_validation->set_rules($param[0],$param[1],$param[2]);
	}
	function check_order($str){
		$id_order = str_replace("#", '', $str);
		$chek = $this->m_client->get_table('tb_order','id_order,iso_code',array('id_order'=>$id_order,'deleted'=>0,'active'=>1));
		if (count($chek) > 0){
			//cek apakah pembayaran sesuai dengan mata uang yang dipesan
			if ($chek[0]->iso_code == $this->session->userdata('iso_code_fo')){
				return true;
			}else{
				$this->form_validation->set_message('check_order',sprintf(lang('chncurrency'),$chek[0]->iso_code));
				return false;
			}
			
		}else{
			$this->form_validation->set_message('check_order',lang('ordernotready'));
			return false;
		}
	}
	function save_pay_confirm(){	
		$id_pay= $this->m_client->get_rand_id('PY');
		$id_order = str_replace("#", "", $this->input->post('id_order',true));
		$status_code = 'WTPVR';
		$post['id_payment_confirm'] = $id_pay;
		$post['id_order'] = $id_order;
		$post['id_payment_method'] = $this->input->post('bankdest',true);
		$post['bank_from'] = $this->input->post('bankfrom',true);
		$post['account_by'] = $this->input->post('rekeningfrom',true);
		$post['id_method_transfer'] = $this->input->post('transfmethod',true);
		$post['amount_transfer'] = $this->input->post('amountrans',true);
		$post['date_transfer'] = put_short_date($this->input->post('transdate',true));
		$post['status_code'] = $status_code;//waiting for payment verification
		$post['id_currency'] = $this->session->userdata('id_currency_fo');
		$post['date_add'] = $this->date_now;
		$post['date_update'] = $this->date_now;
		$res = $this->m_client->insertdata('tb_payment_confirm',$post);		
		
		$res = $this->m_client->editdata('tb_order',array('code_status_order'=>$status_code),array('id_order'=>$id_order));		
		//insert order history status
		$id_order_history = $this->m_client->get_rand_id('H');
		$history['id_order_history'] = $id_order_history;
		$history['id_order'] = $id_order;
		$history['code_status'] = $status_code;
		$history['date_add_status'] = $this->date_now;
		$history['m_date_add_status'] = $this->date_now;
		$history['add_by'] = 'Buyer';
		$res = $this->m_client->insertdata('tb_order_history',$history);
		
		/*
		 * insert pay notify
		*/
		$res = $this->m_client->insert_notify('tb_payment_confirm_notify',array('id_order'=>$id_order,'id_payment_confirm'=>$id_pay));
		
		//email batch to back office
		$mail['id'] = $id_pay;
		$mail['function_name'] = 'PAYCONF';
		$res = $this->m_client->insertdata('tb_sending_email',$mail);
		if ($res > 0){
			$this->session->set_flashdata('success',lang('successpay'));
			redirect($this->session->userdata('permalink_sess'));
		}
	}
	
	function member_area($param,$id=""){			
		if ($param == 'pay_confirm'){
			$this->pay_confirm();
		}elseif ($param == 'edit_password'){
			$this->edit_password();
		}elseif ($param == 'save_edit_password'){
			$this->save_edit_password();
		}elseif ($param == 'select_tab_order'){
			$this->select_tab_order();
		}elseif ($param == 'cancel_order'){
			$this->cancel_order();
		}elseif ($param == 'received'){
			$this->received();
		}elseif ($param == 'save_cancel'){
			$this->save_cancel();
		}elseif ($param == 'save_received'){
			$this->save_received();
		}elseif ($param == 'confirm_received'){
			$this->confirm_received($id);
		}elseif ($param == 'transaction_list'){
			$this->transaction_list($id);
		}elseif ($param == 'view'){
			if ($id == true){
				$this->confirm_received(0);
			}else{
				$this->transaction_list(0);
			}
		}else{
			($this->session->userdata('login_user') == false) ? redirect(FOMODULE.'/account/login') : '';
			permalink_sess(FOMODULE.'/'.$this->class.'/'.$param);
			$this->content_tab($param,$id);
		}
	}
	function content_tab($param,$id){		
		$page = array('my_account'=>'myaccount/v_my_account','my_order'=>'myorder/v_my_order');
		if (isset($page[$param])){
			$titles  = array('my_account'=>lang('account'),'my_order'=>lang('myorder'));
			$this->set_form(array('gender',lang('gender'),'required|xss_clean'));
			$this->set_form(array('firstname',lang('firstname'),'required|xss_clean'));		
			$this->set_form(array('phone',lang('phone'),'required|xss_clean'));		
			$this->set_form(array('tgl',lang('date'),'required|xss_clean'));
			$this->set_form(array('bln',lang('month'),'required|xss_clean'));
			$this->set_form(array('thn',lang('year'),'required|xss_clean'));
			if ($this->form_validation->run() == false){
				$data['page'] = $page[$param];
			}else{			
				$this->save_edit_account();
				return false;
			}		
			$title = $titles[$param];
			$data['tab_title'] = $title;
			$data['meta_keywords'] = $title;
			$data['meta_description'] = $title;
			$data['id'] = $id;//use for edit akun && detail order
			foreach ($this->m_public->product_footer_list() as $row=>$val)
				$data[$row] = $val;
			
			$data['content'] = $this->class.'/v_member_area';
			$this->load->view('w/v_main_body',$data);
		}else{
			$this->m_public->error_page();
		}
	}
	function save_edit_account(){
		$gen = explode("#", $this->input->post('gender',true));
		$id_gender = $gen[0];
		$name_gender = $gen[1];
		$first_name = $this->input->post('firstname',true);
		$last_name = $this->input->post('lastname',true);
		$phone = $this->input->post('phone',true);
		$tgl = $this->input->post('tgl',true);
		$bln = $this->input->post('bln',true);
		$thn = $this->input->post('thn',true);
		$birthdate = date('Y-m-d',strtotime($thn.'-'.$bln.'-'.$tgl));
		$post['id_gender'] = $id_gender;
		$post['first_name'] = $first_name;
		$post['last_name'] = $last_name;		
		$post['phone'] = $this->input->post('phone',true);
		$post['birthdate'] = $birthdate;
		$post['date_update'] = $this->date_now;
		$post['update_by'] = 'System';
		$res = $this->m_client->editdata('tb_customer',$post,array('id_customer'=>$this->id_customer));
		if ($res > 0){
			//buat session		
			$sess_data['first_name_fo']= $first_name;
			$sess_data['last_name_fo']= $last_name;			
			$sess_data['birthdate'] = $birthdate;			
			$sess_data['phone'] = $phone;			
			$sess_data['name_gender'] = $name_gender;
			$sess_data['id_gender'] = $id_gender;
			$this->session->set_userdata($sess_data);
			
			$this->session->set_flashdata('success',lang('editacc'));
			redirect(FOMODULE.'/'.$this->class.'/my_account');
		}
	}
	function edit_password(){
		$res='';
		$res .=$this->load->view($this->class.'/myaccount/v_edit_password','',true);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0, 'modal'=>$res));
	}
	function save_edit_password(){
		$repass = trim($this->input->post('repeatpass',true));//menghapus spasi
		$newpass = trim($this->input->post('password',true));//menghapus spasi		
		if ($newpass == ""){
			$msg = sprintf(lang('required'),lang('newpass'));
		}elseif ($repass == ''){
			$msg = sprintf(lang('required'),lang('repeatpass'));
		}elseif (strlen($newpass) < 5){
			$msg = sprintf(lang('min_length'),lang('newpass'),'5');
		}elseif (strlen($repass) < 5){
			$msg = sprintf(lang('min_length'),lang('repeatpass'),'5');
		}else{
			//jika reconfirm pass is true
			if ($newpass == $repass){
				$post['password'] = hash_password($newpass);
				$res = $this->m_client->editdata('tb_customer',$post,array('id_customer'=>$this->id_customer));
				if ($res > 0){
					$msg = lang('succeditpass');
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg));
					return false;
				}
			}else{
				$msg = lang('matchpass');				
			}
		}
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>$msg));
	}
	function select_tab_order(){
		$element='';
		$indexval = $this->input->post('value',true);
		$tab = array(
				1=>array('view'=>'v_tab_statepay',
						 'title'=>lang('statepay'),						 
				),
				2=>array('view'=>'v_tab_stateorder',
						 'title'=>lang('stateord')
				),
				3=>array('view'=>'v_tab_conf_received',
						 'title'=>lang('confrcvd')
				),
				4=>array('view'=>'v_list_order',
						 'title'=>lang('listord')
				),
		);
		$data['uri']=0;
		$data['limit'] = $this->limit;
		$data['title'] = isset($tab[$indexval]['title']) ? $tab[$indexval]['title'] : '';		
		$view = isset($tab[$indexval]['view']) ? $tab[$indexval]['view'] : 'v_404';
		$element .= $this->load->view($this->class.'/myorder/'.$view,$data,true);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$element));
	}
	function cancel_order(){
		$element='';
		$data['id_order']= $this->input->post('value',true);
		$element .=$this->load->view($this->class.'/myorder/v_modal_confirm',$data,true);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'modal'=>$element));
	}
	function save_cancel(){
		$this->db->trans_start();
		$id_order = $this->input->post('id_order',true);
		$where = array('A.id_order'=>$id_order,'A.id_customer'=>$this->id_customer);
		$mailcode = 'CNCL';
		$cek = $this->m_client->get_order($where);
		if (count($cek) > 0){
			$post['cancel'] = 1;
			$post['active'] = 0;
			$post['code_status_order'] = $mailcode;
			$res = $this->m_client->editdata('tb_order',$post,array('id_order'=>$id_order,'id_customer'=>$this->id_customer));
			$res = $this->m_client->editdata('tb_order_detail',array('active'=>0),array('id_order'=>$id_order));
			
			//insert order history status
			$id_order_history = $this->m_client->get_rand_id('H');
			$history['id_order_history'] = $id_order_history;
			$history['id_order'] = $id_order;
			$history['code_status'] = $mailcode;
			$history['date_add_status'] = $this->date_now;
			$history['m_date_add_status'] = $this->date_now;
			$history['add_by'] = 'Buyer';			
			$res = $this->m_client->insertdata('tb_order_history',$history);
			
			/*
			 * email order cancel
			 */
			$data['name'] = $this->session->userdata('first_name_fo');
			$data['id_order'] = $id_order;
			$emailparam['subjek'] = sprintf(lang('eordcancel'),$id_order);
			$emailparam['email_to'] = $this->session->userdata('email');
			$emailparam['bcc'] = $this->m_client->get_mail_emp(array('A.email_modul_code'=>$mailcode));
			$emailparam['content'] = $this->load->view('email_temp/v_cancel_order',$data,true);
			$send = email_send($emailparam);
			
			//input log email
			$status = ($send == true) ? 1 : 0;
			$mail['id'] = $id_order;
			$mail['function_name'] = $mailcode;
			$mail['date_send'] = $this->date_now;
			$mail['status_send'] = $status;
			$res = $this->m_client->insertdata('tb_sending_email',$mail);
			if ($this->db->trans_status() === false){
				$this->db->trans_rollback();
			}else{
				$this->db->trans_complete();
				if ($res > 0){
					$element='';					
					$element.=$this->load->view($this->class.'/myorder/v_tab_statepay','',true);
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'cancel_order'=>true,'statepay'=>$element,'msg'=>lang('succescancel')));
				}
			}			
			
		}else{			
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>lang('cantcancel')));
		}
	}
	function received(){
		$element='';
		$data['id_order']= $this->input->post('value',true);
		$element .=$this->load->view($this->class.'/myorder/v_modal_received',$data,true);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'modal'=>$element));
	}
	function save_received(){
		$this->db->trans_start();
		$id_order = $this->input->post('id_order',true);
		$where = array('A.id_order'=>$id_order,'A.id_customer'=>$this->id_customer);
		$mailcode = 'RCVB';
		$cek = $this->m_client->get_order($where);
		if (count($cek) > 0){		
			$post['receive_date'] = $this->date_now;	
			$post['order_received'] = 1;
			$post['code_status_order'] = $mailcode;
			$res = $this->m_client->editdata('tb_order',$post,array('id_order'=>$id_order,'id_customer'=>$this->id_customer));
							
			//insert order history status
			$id_order_history = $this->m_client->get_rand_id('RC');
			$history['id_order_history'] = $id_order_history;
			$history['id_order'] = $id_order;
			$history['code_status'] = $mailcode;
			$history['date_add_status'] = $this->date_now;
			$history['m_date_add_status'] = $this->date_now;
			$history['add_by'] = 'Buyer';
			$res = $this->m_client->insertdata('tb_order_history',$history);
				
			
				
			//input log email
			
			$mail['id'] = $id_order;
			$mail['function_name'] = $mailcode;			
			$res = $this->m_client->insertdata('tb_sending_email',$mail);
			if ($this->db->trans_status() === false){
				$this->db->trans_rollback();
			}else{
				$this->db->trans_complete();
				if ($res > 0){
					$element='';
					$data['sql'] = $this->m_client->get_order(array('A.order_received'=>0,'A.id_customer'=>$this->session->userdata('id_customer')));
					$data['received_confirm'] = true;
					$data['limit'] = $this->limit;
					$data['uri']=0;
					$element.=$this->load->view($this->class.'/myorder/v_data_order',$data,true);
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'receive'=>true,'dataorder'=>$element,'msg'=>lang('msgrecv')));
				}
			}
				
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>'Confirm is error!'));
		}
	}
	function transaction_list($uri){	
		//halaman daftar transaksi	
		$data['uri'] = $uri;
		$data['limit'] = ($this->input->post('value',true) == '') ? $this->limit : $this->input->post('value',true);
		$data['where'] =array('A.id_customer'=>$this->session->userdata('id_customer'));
		$data['received_confirm'] = false;
		$this->show_data_transaction($data);
	}
	function confirm_received($uri){
		//halaman konfirmasi barang diterima
		$data['uri'] = $uri;
		$data['limit'] = ($this->input->post('value',true) == '') ? $this->limit : $this->input->post('value',true); 
		$data['where'] = array('A.order_received'=>0,'A.on_delivery'=>1,'A.id_customer'=>$this->session->userdata('id_customer'));
		$data['received_confirm'] = true;
		$this->show_data_transaction($data);
	}
	function show_data_transaction($data){
		$data['sql'] = $this->m_client->get_order($data['where']);
		$element =  $this->load->view($this->class.'/myorder/v_data_order',$data,true);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$element));
	}
}