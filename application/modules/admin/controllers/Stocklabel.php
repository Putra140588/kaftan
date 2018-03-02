<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Stocklabel extends CI_Controller{
	var $class;
	var $table;
	var $datenow;
	var $addby;
	var $access_code;
	
	public function __construct(){
		parent::__construct();
		$this->load->model('m_admin');
		$this->class = strtolower(__CLASS__);
		$this->table = 'tb_label_movement_detail';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'STLBL';
		$this->m_admin->maintenance();
		
	}	
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_stocklabel' : $priv['error'];
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];
		links(MODULE.'/'.$this->class);
		url_sess(base_url(MODULE.'/'.$this->class));//link for menu active
		$data['page_title'] = 'Data '.__CLASS__;
		$data['body'] =  $body;
		$data['class'] = $this->class;		
		$this->load->view('vw_header',$data);
	}
	function column(){
		$field_array = array(
			0 => 'A.date_add',
			1 => 'B.name_movement',
			2 => 'A.name_label',			
			3 => 'A.add_by',
			4 => 'A.date_add'					
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_label_movement());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][4]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_label_movement('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_label_movement());
		$output['recordsFiltered'] = $total;		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/form/'.$row->id_label_movement_detail).'" title="Edit" data-rel="tooltip" data-placement="top">'.icon_action('edit').'</a>
						<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/delete').'\',\''.$row->id_label_movement_detail.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a>';			
			
			$output['data'][] = array(
					$no,
					'<span class="'.$row->label.'">'.$row->name_movement.'</span>',
					$row->name_label,					
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
			$sql = $this->m_admin->get_label_movement(array('A.id_label_movement_detail'=>$id));			
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
		$data['movement'] = $this->m_admin->get_table('tb_label_movement');		
		$this->load->view('vw_header',$data);
	}
	
	function proses(){
		$this->db->trans_start();	
		$id_label_movement_detail = $this->input->post('id_label_movement_detail',true);		
		$code = $this->input->post('code',true);		
		$post['id_label_movement']= $this->input->post('movement',true);	
		$post['name_label']= $this->input->post('namelabel',true);		
		$post['date_update']=$this->datenow;
		$post['update_by']=$this->addby;
		if (!empty($id_label_movement_detail)){			
			$res = $this->m_admin->editdata($this->table,$post,array('id_label_movement_detail'=>$id_label_movement_detail));
			$alert = 'Edit data '.__CLASS__.' successfull';
		}else{
			//addnew				
			$post['date_add']=$this->datenow;
			$post['add_by']=$this->addby;
			$post['move_code'] = $code;
			$cek = $this->m_admin->get_label_movement(array('A.move_code'=>$code));
			if (count($cek) < 1){
				$res = $this->m_admin->insertdata($this->table,$post);
				$alert = 'Add new data '.__CLASS__.' successfull';
			}else{
				$alert = 'Move Code already exist, please input other code';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$alert,'type'=>'error'));
				return false;
			}
			
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
			$res = $this->m_admin->editdata($this->table,array('deleted'=>1),array('id_label_movement_detail'=>$id));
			if ($res > 0){
				$msg = 'Delete data '.__CLASS__.' successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'dt'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
}
