<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Stockmove extends CI_Controller{
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
		$this->table = 'tb_stock_movement';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'STMOV';		
		$this->m_admin->maintenance();
	}	
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? 'stock/vw_stock_move' : $priv['error'];
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
			1 => 'A.id_product',
			2 => 'J.name',
			3 => 'B.name_warehouse',
			4 => 'C.name_location',
			5 => 'I.name_branch',
			6 => 'D.name_movement',
			7 => 'A.qty_move',		
			8 => 'E.name_label',
			9 => 'A.id_order',
			10 => 'A.code_move',
			11 => 'A.add_by',
			12 => 'A.date_add'					
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_stock_movement());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][12]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_stock_movement('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_stock_movement());
		$output['recordsFiltered'] = $total;		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){				
			$movement = '<span class="'.$row->label.'">'.$row->name_movement.'</span>';
			$attribute='';
			if (isset($row->name_group)){
				$attribute = '<span class="label label-yellow arrowed-in arrowed-in-right">'.$row->name_group.' '.$row->name.'</span>';
			}
			$qty = (isset($row->qty_move)) ? $row->qty_move : 'N/A';
			$badge = $this->m_content->badge_qty($qty);
			$output['data'][] = array(
					$no,
					$row->id_product,
					image_product($row->image_name).' '.$row->product_name.'<br>'.$attribute,
					$row->name_warehouse,	
					$row->name_location,
					$row->name_branch,
					$movement,
					$badge,
					$row->name_label,
					$row->id_order,
					$row->code_move,
					$row->add_by,							
					long_date_time($row->date_add),									
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
			$sql = $this->m_admin->get_stock_movement(array('id_warehouse'=>$id));			
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}
			$data['city'] = $this->m_admin->get_table('tb_city',array('id_city','city_name'),array('id_state'=>$row->id_state,'deleted'=>0,'active'=>1));
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
		$data['employee'] = $this->m_admin->get_employee();	
		$data['state'] = $this->m_admin->get_table('tb_state',array('id_state','state_name'),array('deleted'=>0,'active'=>1));
		$data['branch'] = $this->m_admin->get_table('tb_branch',array('id_branch','name_branch'),array('deleted'=>0));
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();	
		$id_warehouse = $this->input->post('id_warehouse',true);
		$code = $this->input->post('code',true);		
		$post['name_warehouse']= $this->input->post('name',true);	
		$post['id_head_wh']= $this->input->post('head',true);
		$post['phone_wh']= $this->input->post('phone',true);
		$post['id_state']= $this->input->post('state',true);
		$post['id_city']= $this->input->post('city',true);
		$post['id_branch'] = $this->input->post('branch',true);
		$post['address']= $this->input->post('address',true);		
		$post['date_update']=$this->datenow;
		$post['update_by']=$this->addby;
		if (!empty($id_warehouse)){			
			$res = $this->m_admin->editdata($this->table,$post,array('id_warehouse'=>$id_warehouse));
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
			$res = $this->m_admin->editdata($this->table,array('deleted'=>1),array('id_warehouse'=>$id));
			if ($res > 0){
				$msg = 'Delete data '.__CLASS__.' successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'dt'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
}
