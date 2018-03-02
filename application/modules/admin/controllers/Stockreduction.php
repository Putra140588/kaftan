<?php if (!defined('BASEPATH')) exit ('No direct script access allowed!');
class Stockreduction extends CI_Controller{
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
		$this->table = 'tb_stock_reduction';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'STRDC';		
		$this->id_employee = $this->session->userdata('id_employee');
		$this->m_admin->maintenance();
	
	}
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');		
		$body= (empty($priv)) ? $this->class.'/vw_stockreduction' : $priv['error'];
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
				0 => 'A.date_add_reduction',
				1 => 'A.id_order',
				2 => 'C.first_name',
				3 => 'C.email',
				4 => 'E.name',
				5 => 'A.qty_available',
				6 => 'A.qty_reduction',
				7 => 'A.stock',				
				8 => 'A.date_add_reduction'
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->stock_reduction());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][8]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->stock_reduction('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->stock_reduction());
		$output['recordsFiltered'] = $total;
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){									
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/salesorder/view/'.$row->id_order).'" title="View" data-rel="tooltip" data-placement="top">'.icon_action('view').'</a>';						
		$product = $row->name;
			if (isset($row->name_group)){
				$product = $row->name.'<br><span class="label label-yellow arrowed-in arrowed-in-right">'.$row->name_group.': '.$row->name_attribute.'</span>';		
			}	
			$output['data'][] = array(
					$no,
					$row->id_order,
					$row->first_name.' '.$row->last_name,
					$row->email,
					image_product($row->image_name).' '.$product,
					'<center>'.$row->qty_available.'</center>',
					'<center>'.$row->qty_reduction.'</center>',
					'<center>'.$row->stock.'</center>',									
					long_date_time($row->date_add_reduction),
					$actions
			);
			$no++;
		}
		echo json_encode($output);
	}
}
