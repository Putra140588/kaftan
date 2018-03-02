<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Payment extends CI_Controller{
	var $class;
	var $table;
	var $datenow;
	var $addby;
	var $access_code;
	public function __construct(){
		parent::__construct();
		$this->load->model('m_content');
		$this->load->model('m_admin');
		$this->class = strtolower(__CLASS__);
		$this->table = 'tb_payment_method';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'MTDP';
		$this->m_admin->maintenance();
	}	
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_payment' : $priv['error'];
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
			0 => 'A.date_add',
			1 => 'A.logo',
			2 => 'A.method_name',
			3 => 'A.name_owner',
			4 => 'A.content',
			5 => 'A.description',
			6 => 'B.name_type',
			7 => 'A.active',
			8 => 'A.display',
			9 => 'A.add_by',
			10 => 'A.date_add'						
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_payment_method());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][10]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_payment_method('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_payment_method());
		$output['recordsFiltered'] = $total;
		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/form/'.$row->id_payment_method).'" title="Edit" data-rel="tooltip" data-placement="top">'.icon_action('edit').'</a>
						<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/delete').'\',\''.$row->id_payment_method.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a>';
			$check_act = ($row->active == 1) ? 'checked' : '';
			$active = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check_act.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_payment_method.'#active'.'\',this)">
							<span class="lbl"></span>
						</label>';
			$check_dspl = ($row->display == 1) ? 'checked' : '';
			$display = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check_dspl.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_payment_method.'#display'.'\',this)">
							<span class="lbl"></span>
						</label>';		
			$logo = '<img src="'.base_url('assets/images/payment/'.image($row->logo)).'" style="width:50px">';
			$output['data'][] = array(
					$no,
					$logo,
					$row->method_name,
					$row->name_owner,
					$row->content,
					$row->description,
					$row->name_type,
					$active,
					$display,			
					$row->add_by,
					long_date_time($row->date_add),					
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
			$sql = $this->m_admin->get_table($this->table,'*',array('id_payment_method'=>$id));			
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}
		}else{
			links(MODULE.'/'.$this->class.'/form/');
			$data['page_title'] = 'Add New '.__CLASS__;
			$action = 'add';
		}		
		$data['paytype'] = $this->m_admin->get_table('tb_payment_type','*',array('deleted'=>0));
		$priv = $this->m_admin->get_priv($this->access_code,$action);
		$body= (empty($priv)) ? $this->class.'/vw_crud' : $priv['error'];
		$data['id_form'] = 'form-ajax';
		$data['body'] = $body;
		$data['class'] = $this->class; 		
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];		
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();			
		$id_payment_method = $this->input->post('id_payment_method',true);
		$post['id_payment_type']=$this->input->post('type',true);
		$post['method_name'] = $this->input->post('name',true);
		$post['method_code'] = $this->input->post('code',true);
		$post['name_owner'] = $this->input->post('owner',true);
		$post['content'] = $this->input->post('content',true);
		$post['description'] = $this->input->post('description',true);
		$post['address']= replace_desc($this->input->post('address',true));		
		$post['sort'] = $this->input->post('sort',true);			
		$post['active']= (empty($this->input->post('active',true))) ? 0 : 1;
		$post['display']=(empty($this->input->post('display',true))) ? 0 : 1;			
		$post['update_by']=$this->addby;		
		$post['date_update']=$this->datenow;
		
		$logo = $_FILES['logo']['name'];						
		if ($logo != ""){			
			$set['upload_path'] = './assets/images/payment/';
			$set['file_name'] = $logo;
			$set['var_name']='logo';
			$this->m_admin->upload_image($set);
			$post['logo'] = str_replace(" ", "_", $logo);							
		}
		
		if (!empty($id_payment_method)){			
			$res = $this->m_admin->editdata($this->table,$post,array('id_payment_method'=>$id_payment_method));
			$alert = 'Edit data method payment successfull';
		}else{
			//addnew			
			$post['date_add']=$this->datenow;
			$post['add_by']=$this->addby;
			$res = $this->m_admin->insertdata($this->table,$post);
			$alert = 'Add new data method payment successfull';
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
	function active(){
		$expl = explode("#",$this->input->post('value',true));
		$id = $expl[0];
		$obj = $expl[1];
		$check = $this->input->post('check',true);
		$priv = $this->m_admin->get_priv($this->access_code,'edit');
		if (empty($priv)){
			$res = $this->m_admin->editdata($this->table,array($obj=>$check),array('id_payment_method'=>$id));
			if ($res){
				$msg = ($check == 1) ? 'Edit status '.__CLASS__.' to be '.$obj.' successfull' : 'Edit status '.__CLASS__.' to be not '.$obj.' succesfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg));
			}
		}else{			
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}		
	}
	
	function delete(){
		$priv = $this->m_admin->get_priv($this->access_code,'delete');
		if (empty($priv)){
			$id = $this->input->post('value',true);
			$res = $this->m_admin->editdata($this->table,array('deleted'=>1),array('id_payment_method'=>$id));
			if ($res > 0){
				$msg = 'Delete data method payment successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'dt'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	
}
