<?php if (!defined('BASEPATH')) exit ('No direct script access allowed!');
class Transaction extends CI_Controller{
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
		$this->table = 'tb_order_pay';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'PYTRX';		
		$this->id_employee = $this->session->userdata('id_employee');
		$this->m_admin->maintenance();
	
	}
	function index($select=''){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		if (!empty($select)){
			$this->m_admin->editdata('tb_order_pay_notify',array('stat_read'=>1),array('id_employee'=>$this->id_employee));
		}
		$body= (empty($priv)) ? $this->class.'/vw_transaction' : $priv['error'];
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
				0 => 'A.date_add_pay',
				1 => 'A.id_order',
				2 => 'C.first_name',
				3 => 'A.total_amount',
				4 => 'A.total_balance',
				5 => 'A.total_pay',
				6 => 'A.payment_method',
				7 => 'A.status_pay',
				8 => 'A.m_date_add_pay',
				9 => 'A.add_by_pay',
				10 => 'A.date_add_pay'
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_data_payment());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][10]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_data_payment('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_data_payment());
		$output['recordsFiltered'] = $total;
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){						
			$total = ($row->iso_code == 'IDR') ? formatnum($row->total_amount) : $row->total_amount;
			$balance = ($row->iso_code == 'IDR') ? formatnum($row->total_balance) : $row->total_balance;
			$total_pay = ($row->iso_code == 'IDR') ? formatnum($row->total_pay) : $row->total_pay;
			$pay_status = '<span class="'.pay_status($row->status_pay).'">'.$row->status_pay.'</span>';
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/salesorder/view/'.$row->id_order).'" title="View" data-rel="tooltip" data-placement="top">'.icon_action('view').'</a>';						
			$output['data'][] = array(
					$no,
					$row->id_order,
					$row->first_name.' '.$row->last_name,
					$row->symbol.' '.$total,
					$row->symbol.' '.$balance,
					$row->symbol.' '.$total_pay,
					$row->payment_method,
					$pay_status,
					long_date($row->m_date_add_pay),
					$row->add_by_pay,
					long_date_time($row->date_add_pay),
					$actions
			);
			$no++;
		}
		echo json_encode($output);
	}
}
