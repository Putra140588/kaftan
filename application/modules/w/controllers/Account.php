<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Account extends CI_Controller{
	var $code;
	var $name;	
	var $class;
	var $sess_permalink;
	var $date_now;
	public function __construct(){
		parent::__construct();
		$this->load->model('m_client');
		$this->load->helper('security');
		$this->load->model('m_public');
		
		$this->class = strtolower(__CLASS__);
		$this->load->library('permalink');		
		$this->load->library('Encryption');
		$this->sess_permalink = $this->session->userdata('permalink_sess');
		$this->date_now = date('Y-m-d H:i:s');
		$this->m_public->maintenance();
		
	}
	function index(){
		//permalink_sess(base_url());		
	}		
	function register(){		
		if ($this->session->userdata('login_user') == false){
			permalink_sess(FOMODULE.'/'.$this->class.'/register');				
			$this->set_form(array('gender',lang('gender'),'required|xss_clean'));
			$this->set_form(array('firstname',lang('firstname'),'required|xss_clean'));
			$this->set_form(array('email',lang('email'),'trim|required|valid_email|xss_clean|callback_cek_email'));//trim menghilangkan karaketer spasi diawal dan diakhir
			$this->set_form(array('phone',lang('phone'),'required|xss_clean'));
			$this->set_form(array('policy',lang('privacy'),'required|xss_clean'));
			$this->set_form(array('tgl',lang('date'),'required|xss_clean'));
			$this->set_form(array('bln',lang('month'),'required|xss_clean'));
			$this->set_form(array('thn',lang('year'),'required|xss_clean'));
			$this->set_form(array('password',lang('password'),'trim|required|min_length[5]|xss_clean'));
			$this->set_form(array('repeatpass',lang('repeatpass'),'trim|required|min_length[5]|xss_clean|callback_match_pass'));		
			if ($this->form_validation->run() == false){
				$data['tab_title'] = lang('reg_acc');
				$data['meta_keywords'] = lang('reg_acc');
				$data['meta_description'] = lang('reg_acc');
				foreach ($this->m_public->product_footer_list() as $row=>$val)
					$data[$row] = $val;
				$data['gender'] = $this->m_client->get_gender();
				$data['content'] = 'account/v_register';
				$this->load->view('w/v_main_body',$data);
			}
		}else{
			redirect($this->session->userdata('permalink_sess'));
		}
		
	}
	function login(){
		if ($this->session->userdata('login_user') == false){
			permalink_sess(FOMODULE.'/'.$this->class.'/login');		
			$this->set_form(array('password',lang('password'),'trim|required|min_length[5]|xss_clean'));
			$this->set_form(array('email',lang('email'),'trim|required|valid_email|xss_clean|callback_cek_login'));//trim menghilangkan karaketer spasi diawal dan diakhir
			if ($this->form_validation->run() == false){	
				$data['tab_title'] = lang('logacc');
				$data['meta_keywords'] = lang('logacc');
				$data['meta_description'] = lang('logacc');
				foreach ($this->m_public->product_footer_list() as $row=>$val)
					$data[$row] = $val;				
				$data['class'] = $this->class;
				$data['content'] = 'account/v_login';
				$this->load->view('w/v_main_body',$data);
			}
		}else{
			redirect($this->session->userdata('permalink_sess'));
		}		
	}
	function set_form($param){		
		return $this->form_validation->set_rules($param[0],$param[1],$param[2]);		
	}
	function cek_login($str){
		$cek = $this->m_client->cek_account(array('email'=>$str));
		if (count($cek) > 0)
		{		
			$password = trim($this->input->post('password',true));
			$pass = $cek[0]->password;
			$active = $cek[0]->active;
			$approval = $cek[0]->approval;
			if ($active == 1 && $approval == 1){
				if (password_verify($password, $pass)){
					//buat session
					$sess_data['login_user'] = true;
					$sess_data['id_customer']= $cek[0]->id_customer;
					$sess_data['id_address'] = $cek[0]->id_address;
					$sess_data['id_districts'] = $cek[0]->id_districts;
					$sess_data['first_name_fo']= $cek[0]->first_name;
					$sess_data['last_name_fo']= $cek[0]->last_name;
					$sess_data['email']= $cek[0]->email;
					$sess_data['birthdate'] = $cek[0]->birthdate;
					$sess_data['active']= $cek[0]->active;
					$sess_data['phone'] = $cek[0]->phone;
					$sess_data['name_gender'] = $cek[0]->name_gender;
					$sess_data['id_gender'] = $cek[0]->id_gender;
					$this->session->set_userdata($sess_data);					
					if ($this->session->userdata('checkout') == true){
						redirect(FOMODULE.'/checkout/proses');
					}else{
						redirect(FOMODULE);
					}
					
				}else{
					//password tidak valid
					$this->form_validation->set_message('cek_login',lang('passinval'));
					return false;
				}
			}else{
				//akun belum diaktivasi
				$this->form_validation->set_message('cek_login',lang('notapprov'));
				return false;
			}			
		}else{
			//email tidak tersedia
			$this->form_validation->set_message('cek_login',lang('readyemail'));
			return false;
		}
	}
	
	function cek_email($str)
	{		
		$cekemail = $this->m_client->cek_account(array('email'=>$str));
		if (count($cekemail) > 0)
		{
			$this->form_validation->set_message('cek_email',lang('cekemail'));
			return false;
		}else{
			return true;
		}
	}
	function save_register(){
		$this->db->trans_start();
		$pref = 'F'.date('Hi');
		$id_customer= $this->m_client->get_rand_id($pref);
		$first_name = $this->input->post('firstname',true);
		$last_name = $this->input->post('lastname',true);
		$email = $this->input->post('email',true);		
		$encode  = md5($email.date('YmdHis'));//for token aktivasi konfirm in email
		$post['id_customer'] = $id_customer;
		$post['id_gender'] = $this->input->post('gender',true); 
		$post['first_name'] = $first_name;
		$post['last_name'] = $last_name;
		$post['email'] = $email;
		$post['phone'] = $this->input->post('phone',true);
		$post['encode'] = $encode;
		$tgl = $this->input->post('tgl',true);
		$bln = $this->input->post('bln',true);
		$thn = $this->input->post('thn',true);
		$post['birthdate'] = date('Y-m-d',strtotime($thn.'-'.$bln.'-'.$tgl));
		$post['password'] = hash_password($this->input->post('password',true));		
		$post['date_add'] = $this->date_now;
		$post['date_update'] = $this->date_now;
		$post['add_by'] = 'Buyer';
		$post['update_by'] = 'Buyer';
		$privacy = (!empty($this->input->post('policy',true)) ? 1 : 0);
		$post['privacy_policy'] =$privacy;
			
		/*
		 * define tb_customer_address
		*/		
		$id_address= $this->m_client->get_rand_id(date('Hi'));
		$postadd['id_address'] = $id_address;
		$postadd['id_customer'] = $id_customer;
		$postadd['default'] = 1;
				
		//insert customer group
		$id_group = $this->m_client->get_idgroup();
		$insertgroup['id_customer'] = $id_customer;
		$insertgroup['id_group'] = $id_group;		
		
		//parse param to email helper
		$mailcode = 'NWREG';
		$data['name'] = $first_name.' '.$last_name;
		$data['encode'] = $encode;
		$data['id_customer'] = $id_customer;
		$emailparam['subjek'] = sprintf(lang('econfirm'),$id_customer);		
		$emailparam['email_to'] = $email;	
		$emailparam['bcc'] = $this->m_client->get_mail_emp(array('A.email_modul_code'=>$mailcode));
		$emailparam['content'] = $this->load->view('email_temp/v_aktivasi_akun',$data,true);
		$send = email_send($emailparam);
		if ($send == true){
			//input data customer
			$res = $this->m_client->insertdata('tb_customer',$post);
			$res = $this->m_client->insertdata('tb_customer_address',$postadd);
			$res = $this->m_client->insertdata('tb_customer_group',$insertgroup);
			
			/*
			 * insert customer new notify to employees
			*/
			$res = $this->m_client->insert_notify('tb_customer_notify',array('id_customer'=>$id_customer));
			
			//input log email
			$status = ($send == true) ? 1 : 0;
			$mail['id'] = $id_customer;
			$mail['function_name'] = $mailcode;
			$mail['date_send'] = $this->date_now;
			$mail['status_send'] = $status;
			$res = $this->m_client->insertdata('tb_sending_email',$mail);
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_complete();
				//jika simpan berhasil
				if ($res > 0)
				{
					return true;					
				}
			}
		}else{			
			//error send email
			return false;
		}
		
		
	}	
	function match_pass($str){
		$pass = $this->input->post('password',true);
		//jika reconfirm pass is true
		if ($pass == $str){
			$res = $this->save_register();			
			if ($res == true){
				$this->session->set_flashdata('success',lang('successreg'));
				redirect($this->session->userdata('permalink_sess'));
			}else{
				$this->form_validation->set_message('match_pass','there is a problem while saving data,please save try again!');
				return false;
			}					
		}else{
			$this->form_validation->set_message('match_pass',lang('matchpass'));
			return false;
		}
	}
	function send_email(){
		$data['name'] = 'Martha Saputra';
		$data['id_customer'] = '123';
		$data['encode'] = '321';
		$emailparam['subjek'] = 'Konfirmasi Pendaftaran 123';
		$emailparam['email_from'] = 'putrasaputra.sp@gmail.com';
		$emailparam['name_from'] = 'Kaftan.com';
		$emailparam['email_to'] = 'msaputra.app88@gmail.com';
		$emailparam['email_bcc'] = array('putra_m@barcodemart.co.id');
		$emailparam['content'] = $this->load->view('email_temp/v_aktivasi_akun',$data,true);
		$send = email_send($emailparam);
		if ($send){
			echo 'succcess kirim';
		}else{
			echo 'gagal kirim';
		}
	}
	function activasi($id_customer='',$encode=''){
		$where = array('id_customer'=>$id_customer,'encode'=>$encode);
		$cek = $this->m_client->get_customer($where);
		if (count($cek) > 0){
			//jika approval dan aktivasi status masih false
			if ($cek[0]->approval == 0 && $cek[0]->active == 0){
				$res = $this->m_client->editdata('tb_customer',array('approval'=>1,'approval_by'=>'System','active'=>1),$where);
				
				/*
				 * email untuk dikirim ke backend mengetahui customer sudah melakukan konfirmasi aktivasi
				 * email dijalankan batch
				 */
				$post['id'] = $cek[0]->id_customer;
				$post['function_name'] = 'ACTREG';
				$res = $this->m_client->insertdata('tb_sending_email',$post);
				if ($res){
					$this->session->set_flashdata('success',lang('succesact'));
					redirect(FOMODULE.'/account/login');
				}
			}else{
				$this->session->set_flashdata('warning',lang('existact'));
				redirect(FOMODULE);
			}
		}else{
			$this->session->set_flashdata('danger',lang('notregist'));
			redirect(FOMODULE);
		}
	}
	function logout(){		
		//melakukan destroy session login data customer
		$this->session->unset_userdata('login_user');
		$this->session->unset_userdata('id_customer');
		$this->session->unset_userdata('first_name_fo');
		$this->session->unset_userdata('last_name_fo');
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('active');	
		$this->session->unset_userdata('phone');
		$this->session->unset_userdata('id_address');
		$this->session->unset_userdata('id_districts');	
		$this->session->unset_userdata('name_gender');
		$this->session->unset_userdata('id_gender');
		$this->session->sess_destroy();
		redirect(FOMODULE);
	}
	
}