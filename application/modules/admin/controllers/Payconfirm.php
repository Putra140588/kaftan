<?php if (!defined('BASEPATH')) exit ('No direct script access allowed!');
class Payconfirm extends CI_Controller{
	var $class;
	var $table;
	var $datenow;
	var $addby;
	var $access_code;
	var $id_employee;
	
	public function __construct(){
		parent::__construct();
		$this->load->model('m_content');
		$this->load->model('m_admin');		
		$this->class = strtolower(__CLASS__);
		$this->table = 'tb_payment_confirm';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'PYCONF';		
		$this->id_employee = $this->session->userdata('id_employee');
		$this->m_admin->maintenance();
	
	}
	function index($select=''){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		if (!empty($select)){
			$this->m_admin->editdata('tb_payment_confirm_notify',array('stat_read'=>1),array('id_employee'=>$this->id_employee));
		}
		$body= (empty($priv)) ? $this->class.'/vw_payconfirm' : $priv['error'];
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
				1 => 'A.id_order',
				2 => 'A.account_by',
				3 => 'A.amount_transfer',
				4 => 'A.bank_from',
				5 => 'B.method_name',
				6 => 'C.name_method_transfer',
				7 => 'A.date_transfer',
				8 => 'D.name_status',				
				9 => 'A.date_add'
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_data_confirm());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][9]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_data_confirm('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_data_confirm());
		$output['recordsFiltered'] = $total;
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){						
			$amount = ($row->iso_code == 'IDR') ? formatnum($row->amount_transfer) : $row->amount_transfer;			
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/salesorder/view/'.$row->id_order).'" title="View" data-rel="tooltip" data-placement="top">'.icon_action('view').'</a>';						
			$status = '<span class="'.$row->label_color.'">'.$row->name_status.'</span>';
			$output['data'][] = array(
					$no,
					$row->id_order,
					$row->account_by,
					$row->symbol.' '.$amount,
					$row->bank_from,
					$row->method_name,
					$row->name_method_transfer,
					short_date($row->date_transfer),
					$status,					
					long_date_time($row->date_add),
					$actions
			);
			$no++;
		}
		echo json_encode($output);
	}
}
