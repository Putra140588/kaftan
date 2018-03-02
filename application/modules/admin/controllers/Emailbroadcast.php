<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Emailbroadcast extends CI_Controller{
	var $class;
	var $table;
	var $datenow;
	var $addby;
	var $access_code;	
	public function __construct(){
		parent::__construct();
		$this->load->model('m_admin');
		$this->class = strtolower(__CLASS__);
		$this->table = 'tb_email_modul';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'EMBRO';		
		$this->m_admin->maintenance();
	}	
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_emailbroadcast' : $priv['error'];
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];
		links(MODULE.'/'.$this->class);
		url_sess(base_url(MODULE.'/'.$this->class));//link for menu active
		$data['page_title'] = 'Data '.__CLASS__;
		$data['body'] = $body;
		$data['class'] = $this->class;		
		$this->load->view('vw_header',$data);
	}
	function column(){
		$field_array = array(
			0 => 'id_email_modul',
			1 => 'email_modul_code',
			2 => 'email_modul_name',	
			3 => '',						
		); 
		return $field_array;
	}
	
	function get_records(){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_table_dt($this->table));
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column		
		$this->m_admin->range_date($this->column());
		$query = $this->m_admin->get_table_dt($this->table,'','',$this->column());
		$this->m_admin->range_date($this->column());
		$total = count($this->m_admin->get_table_dt($this->table));
		$output['recordsFiltered'] = $total;		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-success" href="'.base_url(MODULE.'/'.$this->class.'/access/'.$row->email_modul_code).'" title="Access Email Modul" data-rel="tooltip" data-placement="top">'.icon_action('access').'</a>
						<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/form/'.$row->id_email_modul).'" title="Edit" data-rel="tooltip" data-placement="top">'.icon_action('edit').'</a>
						<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/delete').'\',\''.$row->email_modul_code.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a>';			
			
			$output['data'][] = array(
					$no,
					$row->email_modul_code,
					$row->email_modul_name,
					$this->m_admin->email_access($row->email_modul_code),					
					$actions
			);
			$no++;
		}
		echo json_encode($output);
	}
	function form($id=""){
		$this->m_admin->sess_login();
		if (!empty($id)){
			links(MODULE.'/'.$this->class.'/form/'.$id);
			$action = 'edit';
			$data['page_title'] = 'Edit '.__CLASS__;
			$sql = $this->m_admin->get_table($this->table,'',array('id_email_modul'=>$id));			
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}			
		}else{
			links(MODULE.'/'.$this->class.'/form/');
			$data['page_title'] = 'Add New '.__CLASS__;
			$action = 'add';
		}
		$priv = $this->m_admin->get_priv($this->access_code,$action);
		$body= (empty($priv)) ? $this->class.'/vw_crud' : $priv['error'];
		$data['body'] = $body;
		$data['class'] = $this->class; 		
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];							
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();	
		$id_email_modul = $this->input->post('id_email_modul',true);
		$code = $this->input->post('code',true);		
		$post['email_modul_name']= $this->input->post('name',true);					
		if (!empty($id_email_modul)){			
			$res = $this->m_admin->editdata($this->table,$post,array('id_email_modul'=>$id_email_modul));
			$alert = 'Edit data '.__CLASS__.' successfull';
		}else{
			//addnew				
			$post['email_modul_code']= $this->input->post('code',true);	
			$res = $this->m_admin->insertdata($this->table,$post);
			$alert = 'Add new data '.__CLASS__.' successfull';	
		}
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
			if ($res > 0){
				$this->db->trans_complete();
				$this->session->set_flashdata('success',$alert);
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'save','redirect'=>base_url($this->session->userdata('links'))));
			}
		}		
	}
	
	function delete(){
		$priv = $this->m_admin->get_priv($this->access_code,'delete');
		if (empty($priv)){
			$id = $this->input->post('value',true);
			$res = $this->m_admin->editdata($this->table,array('deleted'=>1),array('email_modul_code'=>$id));
			if ($res > 0){
				$msg = 'Delete data '.__CLASS__.' successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'dt'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function access($code=''){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_access' : $priv['error'];
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];
		links(MODULE.'/'.$this->class);
		$data['emp'] = $this->m_admin->get_table('tb_email_modul','email_modul_name',array('email_modul_code'=>$code));
		$sub_title = $data['emp'][0]->email_modul_name;
		$data['email_modul_code'] = $code;
		$data['page_title'] = 'Data '.__CLASS__;
		$data['sub_title'] = $sub_title;
		$data['body'] = $body;
		$data['class'] = $this->class;
		$this->load->view('vw_header',$data);
	}
	function column_emp(){
		$field_array = array(
				0 => 'date_add',
				1 => 'first_name',
				2 => 'last_name',
		);
		return $field_array;
	}
	function get_emp($code=''){
		$output = array();
		//load datatable
		$this->m_admin->datatable();
		$tb = 'tb_employee';
		$total = count($this->m_admin->get_table_dt($tb));
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;
		//date filter value already set index row column
		$this->m_admin->range_date($this->column_emp());
		$query = $this->m_admin->get_table_dt($tb,'','',$this->column_emp());
		$this->m_admin->range_date($this->column_emp());
		$total = count($this->m_admin->get_table_dt($tb));
		$output['recordsFiltered'] = $total;
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){			
			$where = array('email_modul_code'=>$code,'id_employee'=>$row->id_employee);
			$mod = $this->m_admin->get_employee_broadcast($where);
			$chek =  (count($mod) > 0) ? 'checked' : '';
			$output['data'][] = array(
					$no,
					'<center><label><input type="checkbox" class="ace active-row" '.$chek.' value="'.$code.'#'.$row->id_employee.'" onclick="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active/0').'\',this.value,this)"><span class="lbl"></span></label></center>',
					$row->first_name.' '.$row->last_name,
					$row->email
			);
			$no++;
		}
		echo json_encode($output);
	}
	function active($all=''){
		$priv = $this->m_admin->get_priv($this->access_code,'edit');
		if (empty($priv)){
			$val = $this->input->post('value');
			$expl = explode('#', $val);
			$email_code = isset($expl[0]) ? $expl[0] : '0';
			$id_employee = isset($expl[1]) ? $expl[1] : '0';
			$check = $this->input->post('check');
			$table = 'tb_email_broadcast';
			if ($check == 1){
				//check
				$insert['email_modul_code'] = $email_code;
				$insert['add_by'] = $this->addby;
				$insert['date_add'] = $this->datenow;
				if ($all == '0'){
					//jika check by row
					$insert['id_employee'] = $id_employee;
					$res = $this->m_admin->insertdata($table,$insert);
					$msg = 'Set active '.$email_code.' successfull';
				}else{
					//jika checkk all row
					$sql = $this->m_admin->get_table('tb_employee','id_employee',array('deleted'=>0));
					foreach ($sql as $row){
						$insert['id_employee'] = $row->id_employee;
						$insertbatch = array_merge($insert);
						$cek = $this->m_admin->get_employee_broadcast(array('id_employee'=>$row->id_employee,'email_modul_code'=>$email_code));
						//jika belum ada
						if (count($cek) < 1){
							$res = $this->m_admin->insertdata($table,$insertbatch);
						}
					}
					$msg = 'Set active '.$email_code.' all employee successfull';
				}
			}else{
				//uncheck
				if ($all == 0){
					//check by row
					$where = array('id_employee'=>$id_employee,'email_modul_code'=>$email_code);
					$msg = 'Set Non active '.$email_code.' successfull';
				}else{
					//check all employee
					$where = array('email_modul_code'=>$email_code);
					$msg = 'Set Non active '.$email_code.' all employee successfull';
				}
				$res = $this->m_admin->deletedata($table,$where);
			}
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg));
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
}
