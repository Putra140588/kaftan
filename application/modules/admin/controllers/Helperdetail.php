<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Helperdetail extends CI_Controller{
	var $class;
	var $table;
	var $datenow;
	var $addby;
	var $access_code;	
	public function __construct(){
		parent::__construct();
		$this->load->model('m_admin');
		$this->load->model('m_content');
		$this->class = strtolower(__CLASS__);
		$this->table = 'tb_helper_detail';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'HELPD';	
		$this->m_admin->maintenance();
	}	
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_helper_detail' : $priv['error'];
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
			1 => 'C.name_language',
			2 => 'B.name_helper',
			3 => 'A.title_helper_detail',			
			4 => 'A.content',
			5 => 'A.sort',	
			6 => 'A.active',
			7 => 'A.add_by',
			8 => 'A.date_add'						
		); 
		return $field_array;
	}
	
	function get_records(){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_helper_detail());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][8]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_helper_detail('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_helper_detail());
		$output['recordsFiltered'] = $total;		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/form/'.$row->id_helper_detail).'" title="Edit" data-rel="tooltip" data-placement="top">'.icon_action('edit').'</a>
						<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/delete').'\',\''.$row->id_helper_detail.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a>';			
			
			$check_act = ($row->active == 1) ? 'checked' : '';
			$active = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check_act.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_helper_detail.'#active'.'\',this)">
							<span class="lbl"></span>
						</label>';
			$output['data'][] = array(
					$no,					
					$row->name_language,	
					$row->name_helper,				
					$row->title_helper_detail,
					$row->content,
					$row->sort,
					$active,							
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
			$sql = $this->m_admin->get_helper_detail(array('id_helper_detail'=>$id));			
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
		$data['lang'] = $this->m_admin->get_table('tb_language',array('id_language','name_language'));
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();	
		$id_helper_detail = $this->input->post('id_helper_detail',true);
		$code = $this->input->post('code',true);		
		$post['id_helper']= $this->input->post('namehelper',true);	
		$post['title_helper_detail']= $this->input->post('title',true);
		$post['content']= replace_desc($this->input->post('content',true));
		$post['sort']= $this->input->post('sort',true);
		$post['active']= (empty($this->input->post('active',true))) ? 0 : 1;
		$post['date_update']=$this->datenow;
		$post['update_by']=$this->addby;
		if (!empty($id_helper_detail)){			
			$res = $this->m_admin->editdata($this->table,$post,array('id_helper_detail'=>$id_helper_detail));
			$alert = 'Edit data '.__CLASS__.' successfull';
		}else{
			//addnew				
			$post['date_add']=$this->datenow;
			$post['add_by']=$this->addby;
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
			$res = $this->m_admin->editdata($this->table,array('deleted'=>1),array('id_helper_detail'=>$id));
			if ($res > 0){
				$msg = 'Delete data '.__CLASS__.' successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'dt'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function active(){
		$expl = explode("#",$this->input->post('value',true));
		$id = $expl[0];
		$obj = $expl[1];
		$check = $this->input->post('check',true);
		$priv = $this->m_admin->get_priv($this->access_code,'edit');
		if (empty($priv)){
			$res = $this->m_admin->editdata($this->table,array($obj=>$check),array('id_helper_detail'=>$id));
			if ($res){
				$msg = ($check == 1) ? 'Edit status '.__CLASS__.' to be '.$obj.' successfull' : 'Edit status '.__CLASS__.' to be not '.$obj.' succesfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function show_helper($id_helper=''){
		$res='';
		$id = $this->input->post('value',true);
		$res .= $this->m_content->load_choosen();
		$res .= $this->m_content->chosen_helper($id_helper,$id);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$res));
	}
}
