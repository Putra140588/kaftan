<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Attachment extends CI_Controller{
	var $class;
	var $table;
	var $datenow;
	var $addby;
	var $access_code;
	public function __construct(){
		parent::__construct();
		//$this->load->model('m_content');
		$this->load->model('m_admin');
		$this->class = strtolower(__CLASS__);
		$this->table = 'tb_attachment';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'ATTCH';
		$this->m_admin->maintenance();
	}	
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_attachment' : $priv['error'];
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
			1 => 'A.file_name',
			2 => 'A.file',
			3 => 'B.name',			
			4 => 'A.add_by',
			5 => 'A.date_add',						
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_attachment());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][5]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_attachment('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_attachment());
		$output['recordsFiltered'] = $total;
		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/form/'.$row->id_attachment).'" title="Edit" data-rel="tooltip" data-placement="top">'.icon_action('edit').'</a>
						<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/delete').'\',\''.$row->id_attachment.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a>';
			
			$output['data'][] = array(
					$no,
					$row->file_name,
					$row->file,
					$row->name,							
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
			$sql = $this->m_admin->get_table($this->table,'*',array('id_attachment'=>$id));			
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
		$data['manufacture'] = $this->m_admin->get_table('tb_manufacture',array('id_manufacture','name'),array('deleted'=>0));	
		$data['body'] = $body;
		$data['class'] = $this->class; 		
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];		
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();			
		$id_attachment = $this->input->post('id_attachment',true);
		$post['file_name']=$this->input->post('filename',true);
		$post['id_manufacture']=$this->input->post('manufacture',true);		
		$post['update_by']=$this->addby;		
		$post['date_update']=$this->datenow;		
		$file = $_FILES['fileattch']['name'];
		$size = $_FILES['fileattch']['size'];	
		$max_size = 26246026 * 2; //26246026 Bytes = 25MB
		if ($size > $max_size){
			$msg = 'File too large, max size 50MB';
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>$msg));
		}else{
			if ($file != ""){
				$set['upload_path'] = './assets/attachment/';
				$set['file_name'] = $file;
				$set['var_name']='fileattch';
				$res = $this->m_admin->upload_image($set);
				if ($res['error'] == true){
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>$res['msg']));
					return false;
				}
				$post['file'] = replace_character(str_replace(" ", "_", $file));
			}
			
			if (!empty($id_attachment)){
				$res = $this->m_admin->editdata($this->table,$post,array('id_attachment'=>$id_attachment));
				$alert = 'Edit data attachment successfull';
			}else{
				//addnew
				$post['date_add']=$this->datenow;
				$post['add_by']=$this->addby;
				$res = $this->m_admin->insertdata($this->table,$post);
				$alert = 'Add new data attachment successfull';
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
		
		
	}	
	
	function delete(){
		$priv = $this->m_admin->get_priv($this->access_code,'delete');
		if (empty($priv)){
			$id = $this->input->post('value',true);
			$res = $this->m_admin->editdata($this->table,array('deleted'=>1),array('id_attachment'=>$id));
			if ($res > 0){
				$msg = 'Delete data attachment successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'dt'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
}
