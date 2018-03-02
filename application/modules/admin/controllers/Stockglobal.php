<?php if (!defined('BASEPATH')) exit ('No direct script access allowed!');
class Stockglobal extends CI_Controller{
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
		$this->table = 'tb_stock_available';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'STKGLOB';		
		$this->id_employee = $this->session->userdata('id_employee');
		$this->m_admin->maintenance();
	
	}
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');		
		$body= (empty($priv)) ? $this->class.'/vw_stockglobal' : $priv['error'];
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
				0 => 'B.date_add',
				1 => 'A.id_product',
				2 => 'B.name',
				3 => 'H.name_category',
				4 => 'A.qty_default',
				5 => 'A.qty_sold',
				6 => 'A.qty_available',
				7 => '',
				8 => 'F.name_warehouse',				
				9 => 'G.name_location',
				10 => 'I.name_branch'
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_global_stock_wh());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][10]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_global_stock_wh('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_global_stock_wh());
		$output['recordsFiltered'] = $total;
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){								
			$na = '<span class="badge badge-danger">N/A</span>';		
			
			$qty_default = isset($row->qty_default) ? $row->qty_default : $na;
			$qty_sold = isset($row->qty_sold) ? $row->qty_sold : $na;
			$qty_available = isset($row->qty_available) ? $row->qty_available : $na;
			$name_warehouse = isset($row->name_warehouse) ? $row->name_warehouse : $na;
			$name_location= isset($row->name_location) ? $row->name_location : $na;
			$product = $row->name_product;
			if (isset($row->name_group)){
				$product = $row->name_product.'<br><span class="label label-yellow arrowed-in arrowed-in-right">'.$row->name_group.': '.$row->name.'</span>';		
			}	
			if ($row->qty_available > 0){
				$status = '<span class="label label-info arrowed-in-right arrowed">Ready</span>';
			}else{
				$status = '<span class="label label-warning arrowed-in-right arrowed">Empty</span>';
			}
			$output['data'][] = array(
					$no,
					$row->id_product,
					image_product($row->image_name).' '.$product,
					$row->name_category,					
					'<center>'.$qty_default.'</center>',
					'<center>'.$qty_sold.'</center>',
					'<center>'.$qty_available.'</center>',													
					$status,
					$row->name_warehouse,
					$row->name_location,
					$row->name_branch
					
			);
			$no++;
		}
		echo json_encode($output);
	}
}
