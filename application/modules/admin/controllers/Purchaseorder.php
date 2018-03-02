<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Purchaseorder extends CI_Controller{
	var $class;
	var $table;
	var $datenow;
	var $addby;
	var $access_code;
	var $id_purchase_cart;
	var $id_employee;
	
	public function __construct(){
		parent::__construct();
		$this->load->model('m_content');
		$this->load->model('m_admin');
		$this->class = strtolower(__CLASS__);
		$this->table = 'tb_purchase_order';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'PRCHO';
		$this->id_purchase_cart = $this->session->userdata('id_purchase_cart');
		$this->id_employee = $this->session->userdata('id_employee');
		$this->m_admin->maintenance();
		
	}	
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_purchase_order' : $priv['error'];
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
			1 => 'A.id_purchase_order',
			2 => 'A.purchase_date',
			3 => 'B.name_supplier',
			4 => 'A.total_qty',
			5 => 'A.total_price_inctax',
			6 => 'D.address_branch',
			7 => 'C.name_courier',		
			8 => 'A.received',
			9 => 'A.deleted',
			10 => 'A.add_by',
			11 => 'A.date_add'					
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_purchase_order());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][11]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_purchase_order('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_purchase_order());
		$output['recordsFiltered'] = $total;		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/view_po/'.$row->id_purchase_order).'" title="View" data-rel="tooltip" data-placement="top">'.icon_action('view').'</a>
					   <a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/cancel').'\',\''.$row->id_purchase_order.'\')" title="Cancel" data-rel="tooltip" data-placement="top">'.icon_action('cancel').'</a>';			
			$status = ($row->active == 0) ? '<span class="label label-important arrowed-in-right arrowed">Cancel</span>' : '<span class="label label-info arrowed-in-right arrowed">Active</span>';
			$received = ($row->received == 1) ? '<span class="label label-success arrowed-in-right arrowed">Complete</span>' : '<span class="label label-warning arrowed-in-right arrowed">NotComplete</span>';
			$output['data'][] = array(
					$no,
					$row->id_purchase_order,
					long_date($row->purchase_date),
					$row->name_supplier,	
					$row->total_qty,
					$row->symbol.' '.number_format($row->total_price_inctax,2,'.','.'),
					$row->address_branch.'<br>'.$row->phone_branch,
					$row->name_courier,
					$received,
					$status,
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
		$this->session->unset_userdata('id_purchase_cart');
		links(MODULE.'/'.$this->class.'/form/');
		$data['page_title'] = 'Add New Purchase Order';
		$action = 'add';
		$priv = $this->m_admin->get_priv($this->access_code,$action);
		$body= (empty($priv)) ? $this->class.'/vw_crud' : $priv['error'];
		$data['supplier'] = $this->m_admin->get_supplier();
		$data['courier'] = $this->m_admin->get_table('tb_courier',array('id_courier','name'),array('deleted'=>0));
		$data['branch'] = $this->m_admin->get_table('tb_branch',array('id_branch','name_branch'),array('deleted'=>0));
		$data['tax'] = $this->m_admin->get_table('tb_tax',array('id_tax','name','rate'),array('deleted'=>0));
		$data['currency'] = $this->m_admin->get_table('tb_currency',array('id_currency','name'),array('deleted'=>0,'used'=>'bo'));
		$data['body'] = $body;
		$data['class'] = $this->class; 		
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];			
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();	
		$cek = $this->cek_cart_po();
		if (count($cek)){
			$id_purchase_order = $this->m_admin->ref_po();			
			$post['id_purchase_order']= $id_purchase_order;
			$post['id_purchase_cart']= $cek[0]->id_purchase_cart;
			$post['id_supplier']= $cek[0]->id_supplier;
			$post['id_employee']= $cek[0]->id_employee;
			$post['id_courier'] =$cek[0]->id_courier;
			$post['id_branch'] =$cek[0]->id_branch;
			$post['id_tax']= $cek[0]->id_tax;
			$post['id_currency']= $cek[0]->id_currency;
			$post['purchase_date']= date('Y-m-d',strtotime($cek[0]->purchase_date));
			$post['total_price_extax']= $cek[0]->total_price_extax;
			$post['total_price_inctax']= $cek[0]->total_price_inctax;
			$post['price_tax']= $cek[0]->price_tax;
			$post['rate_tax']= $cek[0]->rate_tax;
			$post['total_qty']= $cek[0]->total_qty;
			$post['description']= $cek[0]->description;
			$post['date_add']=$this->datenow;
			$post['add_by']=$this->addby;
			$post['date_update']=$this->datenow;
			$post['update_by']=$this->addby;
						
			$sql = $this->m_admin->get_purchase_cart(array('A.id_purchase_cart'=>$cek[0]->id_purchase_cart),true);
			if (count($sql) > 0){
				$res = $this->m_admin->insertdata($this->table,$post);
				foreach ($sql as $row){
					$id_purchase_cart_det = $row->id_purchase_cart_det;
					$det['id_purchase_cart_det'] = $id_purchase_cart_det;
					$det['id_purchase_order'] = $id_purchase_order;
					$det['id_product'] = $row->id_product;
					$det['id_product_attribute'] = $row->id_product_attribute;
					$det['id_currency'] = $row->currency_det;
					$det['unit_qty'] = $row->unit_qty;
					$det['unit_price'] = $row->unit_price;
					$det['total_unit_price'] = $row->total_unit_price;
					$det['description_det'] = $row->description_det;
					$res = $this->m_admin->insertdata('tb_purchase_order_det',$det);
					
					$res = $this->m_admin->editdata('tb_purchase_cart_det',array('saved'=>1),array('id_purchase_cart_det'=>$id_purchase_cart_det));
				}
				if ($res > 0){
					$this->db->trans_complete();
					$alert = 'Save purchase order successfull';
					$this->session->set_flashdata('success',$alert);
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'save','redirect'=>base_url($this->session->userdata('links'))));
				}
			}else{
				$alert = 'Cart product order empty!';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$alert,'type'=>'error'));
			}	
			
		}else{
			$alert = 'Create summary first, before save purchase order!';
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$alert,'type'=>'error'));
		}
		
		
	}
	
	function show_city($id_cities=''){
		$res='';
		$id = $this->input->post('value',true);
		$res .=$this->m_content->chosen_city($id,$id_cities,$this->class);
		$res .=$this->m_content->load_choosen();
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$res));
	}
	function show_districts($id_districts=''){
		$res='';
		$id = $this->input->post('value',true);
		$res .=$this->m_content->chosen_districts($id,$id_districts);
		$res .=$this->m_content->load_choosen();
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$res));
	}
	function cancel(){
		$priv = $this->m_admin->get_priv($this->access_code,'delete');
		if (empty($priv)){
			$id = $this->input->post('value',true);
			$res = $this->m_admin->editdata($this->table,array('active'=>0),array('id_purchase_order'=>$id));
			$res = $this->m_admin->editdata('tb_purchase_order_det',array('active'=>0),array('id_purchase_order'=>$id));
			if ($res > 0){
				$msg = 'Cancel purchase order successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'dt'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>'You do not have permission cancel purchase order!'));
		}
	}
	function column_prod(){
		$field_array = array(
				0 => 'A.date_add',
				1 => 'A.id_product',
				2 => 'A.name',
				3 => 'D.name_category',
				4 => 'B.name_supplier',
				5 => 'C.manufacture',
				
		);
		return $field_array;
	}
	
	function get_product(){
		$output = array();
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_product());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;		
		$this->m_admin->range_date($this->column_prod());
		$query = $this->m_admin->get_product('',$this->column_prod());
		$this->m_admin->range_date($this->column_prod());
		$total = count($this->m_admin->get_product());
		$output['recordsFiltered'] = $total;
	
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-success" onclick="ajaxcall(\''.base_url(MODULE.'/'.$this->class.'/add_cart').'\',\''.$row->id_product.'\',\'pocart\')" title="Add to Cart" data-rel="tooltip" data-placement="top">'.icon_action('cart').'</a>';									
			$output['data'][] = array(
					$no,
					$row->id_product,
					$row->name,
					$row->name_category,
					$row->name_supplier,
					$row->manufacture,					
					$actions
			);
			$no++;
		}
		echo json_encode($output);
	}
	function create_sess_cart(){		
		if ($this->id_purchase_cart === null){
			$res =$this->session->set_userdata('id_purchase_cart',$this->m_admin->get_rand_id('PC'));
		}		
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$this->id_purchase_cart));
	}
	function add_cart(){
		$element="";
		$id_product = $this->input->post('value');
		$res = $this->m_admin->insertdata('tb_purchase_cart_det',array('id_product'=>$id_product));
		$element .= $this->m_content->table_po_cart();
		if ($res){
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$element));
		}
	}
	function delete_cart(){
		$priv = $this->m_admin->get_priv($this->access_code,'delete');
		if (empty($priv)){
			$id = $this->input->post('value',true);
			$res = $this->m_admin->deletedata('tb_purchase_cart_det',array('id_purchase_cart_det'=>$id));			
			if ($res > 0){
				$msg = 'Delete Product Cart successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'df'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function create_summary(){
		$content="";
		$post = $this->input->post();
		$id_supplier = $post['supplier'];
		$id_courier = $post['courier'];
		$id_branch = $post['branch'];
		$tax = explode("#", $post['tax']);
		$id_tax = isset($tax[0]) ? $tax[0] : '';
		$rate_tax =  isset($tax[1]) ? $tax[1] : '';
		$id_currency = $post['currency'];
		$purchase_date = $post['date'];
		$datax = isset($post['datax']) ? $post['datax'] : '';
		if ($id_supplier != "" && $id_courier != "" && $id_branch != "" && $id_tax != "" && $id_currency != "" && $purchase_date != "" && $datax != ""){
					
			foreach ($datax as $row){
				$id_purchase_cart_det = $row['id_purchase_cart_det'];
				$id_product_attribute = isset($row['id_product_attribute']) ? $row['id_product_attribute'] : '';
				$unit_qty = $row['unit_qty'];
				$unit_price = $row['unit_price'];
				$description_det = $row['description_det'];
				
				$update['id_purchase_cart'] = $this->id_purchase_cart;
				$update['id_product_attribute'] = $id_product_attribute;
				$update['id_currency'] = $id_currency;
				$update['unit_qty'] = $unit_qty;
				$update['unit_price'] = $unit_price;				
				$total_price = $unit_price * $unit_qty;
				$update['total_unit_price'] = $total_price;
				$update['description_det'] = $description_det;		
				//update cart detail		
				$res = $this->m_admin->editdata('tb_purchase_cart_det',$update,array('id_purchase_cart_det'=>$id_purchase_cart_det));
				
			}
			
			$cart['id_purchase_cart'] = $this->id_purchase_cart;
			$cart['id_supplier'] = $id_supplier;
			$cart['id_employee'] = $this->id_employee;
			$cart['id_courier'] = $id_courier;
			$cart['id_branch'] = $id_branch;
			$cart['id_tax'] = $id_tax;
			$cart['id_currency'] = $id_currency;
			$cart['purchase_date'] = date('Y-m-d',strtotime($purchase_date));
			$sum = $this->m_admin->get_sum_po_cart($this->id_purchase_cart);
			$total_price = $sum[0]->total_price;
			$cart['total_price_extax'] = $total_price;
			
			$tax_price = ($total_price * $rate_tax) / 100;
			$cart['total_price_inctax'] = $total_price + $tax_price;
			$cart['price_tax'] = $tax_price;
			$cart['rate_tax'] = $rate_tax;
			$cart['total_qty'] = $sum[0]->total_qty;
			$cart['description'] = replace_desc($post['description']);
			
			$cek = $this->cek_cart_po();
			if (count($cek) > 0){
				//jika sudah ada dicart				
				$res = $this->m_admin->editdata('tb_purchase_cart',$cart,array('id_purchase_cart'=>$this->id_purchase_cart));
			}else{
				//jika belum ada dicart
				$res = $this->m_admin->insertdata('tb_purchase_cart',$cart);
			}			
			
			if ($res){
				$alert = 'Create Summary successfull';
				$content .= $this->template_po_cart();
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'modal','content'=>$content));
			}
		}else{
			$alert = 'Data your insert not complete!';
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$alert,'type'=>'error'));
			
		}
	}
	function template_po_cart(){
		$res="";
		$data['name_title'] = 'Purchase Order Summary';
		$sql = $this->m_admin->get_purchase_cart(array('A.id_purchase_cart'=>$this->id_purchase_cart));
		foreach ($sql as $row)
			foreach ($row as $key=>$val){
				$data[$key] = $val;
			}		
		$data['podetail'] = $this->m_admin->get_purchase_cart(array('A.id_purchase_cart'=>$this->id_purchase_cart),true);
		$res .=$this->load->view($this->class.'/vw_summary',$data,true);
		return $res;
	}
	function cek_cart_po(){
		$cek = $this->m_admin->get_table('tb_purchase_cart','*',array('id_purchase_cart'=>$this->id_purchase_cart));
		return $cek;
	}
	function view_po($id){
		$res="";		
		$this->m_admin->sess_login();		
		links(MODULE.'/'.$this->class.'/view_po/'.$id);
		$data['page_title'] = 'View Purchase Order';
		$data['name_title'] = 'Purchase Order';
		$action = 'view';
		$priv = $this->m_admin->get_priv($this->access_code,$action);
		$body= (empty($priv)) ? $this->class.'/vw_view_po' : $priv['error'];
		
		$sql = $this->m_admin->get_po(array('A.id_purchase_order'=>$id));
		foreach ($sql as $row)
			foreach ($row as $key=>$val){
			$data[$key] = $val;
		}
		$data['podetail'] = $this->m_admin->get_po(array('A.id_purchase_order'=>$id),true);
		$data['body'] = $body;
		$data['class'] = $this->class;
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];
		$this->load->view('vw_header',$data);
	}
}
