<?php if (!defined('BASEPATH')) exit ('No direct script access allowed!');
class Stockindentglobal extends CI_Controller{
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
		$this->table = 'tb_get_global_indent';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'STINDG';		
		$this->id_employee = $this->session->userdata('id_employee');
		$this->m_admin->maintenance();
	
	}
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');		
		$body= (empty($priv)) ? $this->class.'/vw_stockindentglobal' : $priv['error'];
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
				2 => 'A.name',
				3 => 'A.qty_buy_now',
				4 => 'A.qty_minus',
				5 => 'A.qty_buy_now',		
				6 => '',		
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_global_indent());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][6]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_global_indent('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_global_indent());
		$output['recordsFiltered'] = $total;
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){									
			
			if (!empty($row->name_group)){
				$product = $row->name.'<br><span class="label label-yellow arrowed-in arrowed-in-right">'.$row->name_group.': '.$row->name_attribute.'</span>';
			}else{$product = $row->name;}			
			$where = array('id_product'=>$row->id_product,'id_product_attribute'=>$row->id_product_attribute);
			
			$stockready = $this->m_admin->get_global_stock($where);
		
			$output['data'][] = array(
					$no,		
					$row->id_product,			
					image_product($row->image_name).' '.$product,
					'<center>'.$row->total_order.'</center>',
					'<center>'.$row->stock_last.'</center>',
					'<center>'.$row->total_request.'</center>',
					'<center>'.$stockready.'</center>');	
			$no++;
		}
		echo json_encode($output);
	}
	function status($stat){
		if ($stat == 1)
		{
			$status = 'Order';
			$label = 'label label-warning arrowed-in-right arrowed';
		}
		else if ($stat == 2)
		{
			$status = 'Received';
			$label = 'label label-success arrowed-in-right arrowed';
		}
		else if ($stat == 3)
		{
			$status = 'Canceled';
			$label = 'label label-important arrowed-in-right arrowed';
		}else if ($stat == 4){
			$status = 'ReadyStock';
			$label = 'label label-success arrowed-in-right arrowed';
		}else{
			$status = 'Indent';
			$label = 'label label-info arrowed-in-right arrowed';
		}
		return '<span class="'.$label.'">'.$status.'</span>';
	}
}
