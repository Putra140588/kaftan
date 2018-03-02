<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Supplier extends CI_Controller{
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
		$this->table = 'tb_supplier';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'SPPL';
		$this->m_admin->maintenance();
	}	
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_supplier' : $priv['error'];
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
			0 => 'date_add',
			1 => 'name_supplier',
			2 => 'address',
			3 => 'phone',
			4 => 'email',
			5 => 'add_by',
			6 => 'date_add',
			7 => 'active',				
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_supplier());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][6]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_supplier('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_supplier());
		$output['recordsFiltered'] = $total;
		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/form/'.$row->id_supplier).'" title="Edit" data-rel="tooltip" data-placement="top">'.icon_action('edit').'</a>
						<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/delete').'\',\''.$row->id_supplier.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a>';
			$check = ($row->active == 1) ? 'checked' : '';
			$active = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_supplier.'\',this)">
							<span class="lbl"></span>
						</label>';
			$output['data'][] = array(
					$no,
					$row->name_supplier,
					$row->address,
					$row->phone,
					$row->email,				
					$row->add_by,
					long_date_time($row->date_add),
					$active,
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
			$sql = $this->m_admin->get_table($this->table,'*',array('id_supplier'=>$id));			
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
		$data['id_form'] = 'form-ajax';
		$data['body'] = $body;
		$data['class'] = $this->class; 		
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];		
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();	
		$id_supplier = $this->input->post('id_supplier',true);		
		$post['name_supplier']=$this->input->post('name',true);
		$post['address']= replace_freetext($this->input->post('address',true));		
		$post['phone']=$this->input->post('phone',true);
		$post['email']=$this->input->post('email',true);		
		$post['active']= (empty($this->input->post('active',true))) ? 0 : 1;
		$post['city']=$this->input->post('city',true);
		$image=$_FILES['image']['name'];	
		$post['update_by']=$this->addby;		
		$post['date_update']=$this->datenow;
		if ($image != ""){
			$set['upload_path'] = './assets/images/supplier/';
			$set['file_name'] = $image;
			$set['var_name']='image';
			$this->m_admin->upload_image($set);
			$post['image'] = $image;
		}
		if (!empty($id_supplier)){			
			$res = $this->m_admin->editdata($this->table,$post,array('id_supplier'=>$id_supplier));
			$alert = 'Edit data Supplier successfull';
		}else{
			//addnew			
			$post['date_add']=$this->datenow;
			$post['add_by']=$this->addby;
			$res = $this->m_admin->insertdata($this->table,$post);
			$alert = 'Add new data Supplier successfull';
		}
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
			if ($res > 0){
				$this->db->trans_complete();
				$id_form = $this->input->post('id_form',true);
				if ($id_form != 'formmodal'){				
					$this->session->set_flashdata('success',$alert);
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'save','redirect'=>base_url($this->session->userdata('links'))));
				}else{
					$content="";
					$content  .= $this->m_content->chosen_supplier($this->input->post('id_spl_add'));
					$content  .= $this->m_content->load_choosen();
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'modal','content'=>$content));
				}
			}
		}
		
	}
	function active(){
		$id = $this->input->post('value',true);
		$check = $this->input->post('check',true);
		$priv = $this->m_admin->get_priv($this->access_code,'edit');
		if (empty($priv)){
			$res = $this->m_admin->editdata($this->table,array('active'=>$check),array('id_supplier'=>$id));
			if ($res){
				$msg = ($check == 1) ? 'Edit status '.__CLASS__.' to be active successfull' : 'Edit status '.__CLASS__.' to be not active succesfull';
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
			$res = $this->m_admin->editdata($this->table,array('deleted'=>1),array('id_supplier'=>$id));
			if ($res > 0){
				$msg = 'Delete data '.__CLASS__.' successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'dt'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
}
