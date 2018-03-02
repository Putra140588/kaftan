<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Currency extends CI_Controller{
	var $class;
	var $table;
	var $datenow;
	var $addby;
	var $access_code;	
	public function __construct(){
		parent::__construct();
		$this->load->model('m_admin');
		$this->class = strtolower(__CLASS__);
		$this->table = 'tb_currency';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'CRNC';		
		$this->m_admin->maintenance();
	}	
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_currency' : $priv['error'];
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
			1 => 'name',
			2 => 'iso_code',
			3 => 'iso_code_number',
			4 => 'symbol',
			5 => 'exchange_rate',			
			6 => 'used',		
			7 => 'add_by',
			8 => 'date_add',
			9 => 'default'					
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
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/form/'.$row->id_currency).'" title="Edit" data-rel="tooltip" data-placement="top">'.icon_action('edit').'</a>
						<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/delete').'\',\''.$row->id_currency.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a>';			
			$check_dfl = ($row->default == 1) ? 'checked' : '';
			$default = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check_dfl.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/defaults').'\',\''.$row->id_currency.'#'.$row->used.'#default'.'\',this)">
							<span class="lbl"></span>
						</label>';
			$used = ($row->used == 'fo') ? '<span class="label label-info arrowed-in-right arrowed">'.$row->used.'</span>' : '<span class="label label-lg label-pink arrowed-right">'.$row->used.'</span>';
			$output['data'][] = array(
					$no,
					$row->name,
					$row->iso_code,
					$row->iso_code_number,
					$row->symbol,	
					$row->exchange_rate,					
					$used,						
					$row->add_by,			
					long_date_time($row->date_add),	
					$default,
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
			$sql = $this->m_admin->get_table($this->table,'',array('id_currency'=>$id));			
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
		$data['country'] = $this->m_admin->get_table('tb_country',array('id_country','country_name'),array('deleted'=>0));		
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();	
		$id_currency = $this->input->post('id_currency',true);
		$code = $this->input->post('code',true);		
		$post['name']= $this->input->post('name',true);			
		$post['iso_code']= $this->input->post('isocode',true);
		$post['iso_code_number']= $this->input->post('isonum',true);
		$post['symbol']= $this->input->post('symbol',true);
		$post['exchange_rate']= $this->input->post('rate',true);
		$post['used'] =  $this->input->post('used',true);
		$post['date_update']=$this->datenow;
		$post['update_by']=$this->addby;
		if (!empty($id_currency)){			
			$res = $this->m_admin->editdata($this->table,$post,array('id_currency'=>$id_currency));
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
			$res = $this->m_admin->editdata($this->table,array('deleted'=>1),array('id_currency'=>$id));
			if ($res > 0){
				$msg = 'Delete data '.__CLASS__.' successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'dt'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function defaults(){
		$expl = explode("#",$this->input->post('value',true));
		$id = $expl[0];
		$used = $expl[1];
		$obj = $expl[2];
		$check = $this->input->post('check',true);
		$priv = $this->m_admin->get_priv($this->access_code,'edit');
		if (empty($priv)){
			$res = $this->m_admin->editdata($this->table,array($obj=>0),array('used'=>$used));
			$res = $this->m_admin->editdata($this->table,array($obj=>$check),array('id_currency'=>$id,'used'=>$used));
			if ($res){
				$msg = ($check == 1) ? 'Edit status '.__CLASS__.' to be '.$obj.' successfull' : 'Edit status '.__CLASS__.' to be not '.$obj.' succesfull';
				$this->session->set_flashdata('success',$msg);
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'defaults'=>true,'error'=>0,'redirect'=>base_url($this->session->userdata('links')),'msg'=>$msg));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
}
