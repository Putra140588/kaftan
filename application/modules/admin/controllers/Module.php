<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Module extends CI_Controller{
	var $class;
	var $table;
	var $datenow;
	var $addby;
	var $access_code;	
	public function __construct(){
		parent::__construct();
		$this->load->model('m_admin');
		$this->class = strtolower(__CLASS__);
		$this->table = 'tb_modul';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'MODL';	
		$this->m_admin->maintenance();
	}	
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_module' : $priv['error'];
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
			1 => 'modul_code',
			2 => 'name',
			3 => 'url_route',
			4 => 'id_level',
			5 => 'position',
			6 => 'icon',
			7 => 'add_by',
			8 => 'date_add',
			9 => 'active',							
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
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][8]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_table_dt($this->table,'','',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_table_dt($this->table));
		$output['recordsFiltered'] = $total;		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/form/'.$row->id_modul).'" title="Edit" data-rel="tooltip" data-placement="top">'.icon_action('edit').'</a>
						<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/delete').'\',\''.$row->modul_code.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a>';			
			$check_act = ($row->active == 1) ? 'checked' : '';
			$active = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check_act.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_modul.'#active'.'\',this)">
							<span class="lbl"></span>
						</label>';
			$output['data'][] = array(
					$no,
					$row->modul_code,
					$row->name,
					$row->url_route,
					$row->id_level,		
					$row->position,
					$row->icon,					
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
			$sql = $this->m_admin->get_table($this->table,'',array('id_modul'=>$id));			
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
		$data['parent'] = $this->m_admin->get_table($this->table,array('id_modul','name'),array('id_level'=>0,'id_modul_parent'=>0));
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();	
		$id_modul = $this->input->post('id_modul',true);
		$code = $this->input->post('code',true);		
		$post['name']= $this->input->post('name',true);
		$post['id_level']= $this->input->post('level',true);
		$post['id_modul_parent']= $this->input->post('parent',true);
		$post['position']= $this->input->post('position',true);
		$post['url_route']= $this->input->post('url',true);
		$post['icon']= $this->input->post('icon',true);
		$post['active']= (empty($this->input->post('active',true))) ? 0 : 1;
		$post['date_update']=$this->datenow;
		$post['update_by']=$this->addby;
		if (!empty($id_modul)){			
			$res = $this->m_admin->editdata($this->table,$post,array('id_modul'=>$id_modul));
			$alert = 'Edit data '.__CLASS__.' successfull';
		}else{
			//addnew				
			$code = $this->input->post('code',true);
			$post['modul_code']= $code;
			$post['date_add']=$this->datenow;
			$post['add_by']=$this->addby;
			if ($this->cek_code($code) == false){
				$res = $this->m_admin->insertdata($this->table,$post);			
				$sql = $this->m_admin->get_table('tb_departement','code_departement',array('deleted'=>0));
				foreach ($sql as $row){
					$in['code_departement'] = $row->code_departement;
					$in['modul_code'] = $code;
					$in['add_by'] = $this->addby;
					$in['date_add'] = $this->datenow;
					$batch[] = $in;
				}
				$res = $this->db->insert_batch('tb_priv',$batch);
				$alert = 'Add new data '.__CLASS__.' successfull';
			}else{
				$alert = 'Code '.__CLASS__.' already exist,please use other code';
				$res = 0;
			}
				
		}
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
			if ($res > 0){
				$this->db->trans_complete();
				$this->session->set_flashdata('success',$alert);
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'save','redirect'=>base_url($this->session->userdata('links'))));
			}else{
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$alert,'type'=>'error'));
			}
		}		
	}
	
	function delete(){
		$priv = $this->m_admin->get_priv($this->access_code,'delete');
		if (empty($priv)){
			$id = $this->input->post('value',true);
			$res = $this->m_admin->editdata($this->table,array('deleted'=>1),array('modul_code'=>$id));
			$res = $this->m_admin->editdata('tb_priv',array('deleted'=>1),array('modul_code'=>$id));
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
			$res = $this->m_admin->editdata($this->table,array($obj=>$check),array('id_modul'=>$id));
			if ($res){
				$msg = ($check == 1) ? 'Edit status '.__CLASS__.' to be '.$obj.' successfull' : 'Edit status '.__CLASS__.' to be not '.$obj.' succesfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function cek_code($code){
		$qr = $this->m_admin->get_table($this->table,'modul_code',array('modul_code'=>$code,'deleted'=>0));
		if (count($qr) > 0){
			return true;
		}else{
			return false;
		}
	}
}
