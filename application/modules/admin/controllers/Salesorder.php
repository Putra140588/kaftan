<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Salesorder extends CI_Controller{
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
		$this->load->model('w/m_client');
		$this->class = strtolower(__CLASS__);
		$this->table = 'tb_order';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'ORDSLE';
		$this->id_purchase_cart = $this->session->userdata('id_purchase_cart');
		$this->id_employee = $this->session->userdata('id_employee');
		$this->m_admin->maintenance();			
		
	}	
	function index($select=''){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		if (!empty($select)){
			$this->m_admin->editdata('tb_order_notify',array('stat_read'=>1),array('id_employee'=>$this->id_employee));
		}
		$body= (empty($priv)) ? $this->class.'/vw_sales_order' : $priv['error'];
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
			0 => 'A.date_add_order',
			1 => 'A.id_order',
			2 => 'B.first_name',
			3 => 'A.total_payment',
			4 => 'A.total_balance',
			5 => 'F.method_name',
			6 => 'A.payment_result',
			7 => 'M.name_status',		
			8 => 'A.add_by',			
			9 => 'A.date_add_order'					
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_order());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][9]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_order('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_order());
		$output['recordsFiltered'] = $total;		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/view/'.$row->id_order).'" title="View" data-rel="tooltip" data-placement="top">'.icon_action('view').'</a>
						<a class="btn btn-xs btn-success" href="'.base_url(MODULE.'/letter/orderslip/inv/'.$row->id_order).'" title="Generate Invoice" target="_blank" data-rel="tooltip" data-placement="top"><i class="fa fa-file"></i></a>
						<a class="btn btn-xs btn-warning" href="'.base_url(MODULE.'/letter/orderslip/deliv/'.$row->id_order).'" title="Generate Delivery Slip" target="_blank" data-rel="tooltip" data-placement="top"><i class="fa fa-road"></i></a>
					    <a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/cancel').'\',\''.$row->id_order.'\')" title="Cancel" data-rel="tooltip" data-placement="top">'.icon_action('cancel').'</a>';			
			$status = '<span class="'.$row->label_color.'">'.$row->name_status.'</span>';
			$total = ($row->iso_code == 'IDR') ? formatnum($row->total_payment) : $row->total_payment;	
			$balance = ($row->iso_code == 'IDR') ? formatnum($row->total_balance) : $row->total_balance;
			$pay_status = '<span class="'.pay_status($row->payment_result).'">'.$row->payment_result.'</span>';
			$output['data'][] = array(
					$no,
					$row->id_order,
					$row->first_name.' '.$row->last_name,
					$row->symbol.' '.$total,
					$row->symbol.' '.$balance,
					$row->method_name,
					$pay_status,
					$status,					
					$row->add_by,			
					long_date_time($row->date_add_order),					
					$actions
			);
			$no++;
		}
		echo json_encode($output);
	}
	function view($id='',$select='',$adjustindent=''){
		$res="";
		//jika melakukan adjust qty order indent, maka value diparsing dari function save_adjust_indent
		$tab = (empty($adjustindent)) ? $this->input->post('value',true) : $adjustindent;
		$sql = $this->m_client->get_order(array('A.id_order'=>$id));
		foreach ($sql as $row)
			foreach ($row as $key=>$val){
			$data[$key] = $val;
		}
		$gr = array();
		$group = $this->m_admin->get_name_group($row->id_customer);
		foreach ($group as $g){
			$gr[] = $g->name_group;
		}
		$data['name_group'] = implode(", ", $gr);
		$data['rowaddress'] =$this->m_admin->get_customer(array('C.id_customer'=>$row->id_customer));		
		$data['class'] = $this->class;					
		switch ($tab){
			case 'cust':
				$res .= $this->load->view($this->class.'/detail/vw_tab_customer',$data,true);
				break;
			case 'ordet':					
				$data['detail'] = $this->m_client->get_order_detail(array('A.id_order'=>$id));
				$res .= $this->load->view($this->class.'/detail/vw_tab_order',$data,true);
				break;
			case 'status':
				$data['statuses'] = $this->m_admin->get_statuses();
				$res .= $this->load->view($this->class.'/detail/vw_tab_statuses',$data,true);
				break;
			case 'pay':
				$res .= $this->load->view($this->class.'/detail/vw_tab_payment',$data,true);
				break;
			case 'inpay':
				$res .= $this->load->view($this->class.'/detail/vw_input_payment',$data,true);
				break;
			case 'refpay':
				$res .= $this->load->view($this->class.'/detail/vw_refund_payment',$data,true);
				break;
			default:
				//menampilkan halaman default direct url non ajax
					$this->m_admin->sess_login();
					//digunakan untuk select notifikasi order baru
					if (!empty($select)){
						$this->m_admin->editdata('tb_order_notify',array('stat_read'=>1),array('id_order'=>$id,'id_employee'=>$this->id_employee));
					}
					links(MODULE.'/'.$this->class.'/view/'.$id);
					$data['page_title'] = 'View Sales Order';
					$data['name_title'] = 'Sales Order';
					$action = 'view';
					$priv = $this->m_admin->get_priv($this->access_code,$action);
					$body= (empty($priv)) ? $this->class.'/detail/vw_detail' : $priv['error'];
					$data['body'] = $body;
					$data['notif']= (empty($priv)) ? '' : $priv['notif'];
					$this->load->view('vw_header',$data);
				break;
		}			
		//jika ajax select tab dijalankan
		if (!empty($tab)){
			//jika tidak melakukan adjust qty indent
			if (empty($adjustindent)){
			//select tab ajax
			$res .= $this->m_content->load_choosen();
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$res));
			}else{
				return $res;
			}
		}
		
	}
	function save_status(){
		$x = explode("#", $this->input->post('codestatus',true));
		$code_status = isset($x[0]) ? $x[0] : '' ;
		$name_status = isset($x[1]) ? $x[1] : '' ;
		$id_order_hitory = $this->m_admin->get_rand_id('H');
		$id_order = $this->input->post('id_order',true);
		$id_branch = $this->input->post('id_branch',true);
		$m_date_add = date('Y-m-d',strtotime($this->input->post('dateadd')));
		$id_employee = $this->session->userdata('id_employee');
		$note = $this->input->post('notes',true);		
		if (!empty($code_status)){		
			$this->db->trans_start();
			$where = array('id_order'=>$id_order);
			$post['id_order_history'] = $id_order_hitory;
			$post['id_employee'] = $id_employee;
			$post['id_order'] = $id_order;	
			$post['code_status'] = $code_status;
			$post['date_add_status'] = $this->datenow;
			$post['m_date_add_status'] = $m_date_add;
			$post['notes'] = $note;
			$post['add_by'] = $this->addby;
			
			/*
			 * melakukan cek apakah status sudah diinput
			 */
			$cek = $this->m_admin->get_table('tb_order_history','*',array('id_order'=>$id_order,'code_status'=>$code_status));
			if (count($cek) < 1){
				$mail['id'] = $id_order;
				$mail['function_name'] = $code_status;
				//if not ready
				$edit['code_status_order'] = $code_status;
				$res=0;
				$get = $this->m_admin->get_table('tb_order','cancel,active,payment_receive',$where);
				//jika status belum dicancel
				if ($get[0]->cancel == 0 && $get[0]->active == 1){	
					if ($code_status == 'CNCL'){
						$edit['cancel']=1;
						$edit['active']=0;
						//jika pembayaran belum diterima
						if ($get[0]->payment_receive < 1){
							//input order cancel
							$res = $this->m_admin->editdata('tb_order',$edit,$where);
							$res = $this->m_admin->editdata('tb_order_detail',array('active'=>0),$where);
							$res = $this->m_admin->insertdata('tb_order_history',$post);
							
							#rollbackstock
							$res = $this->m_admin->roll_back_stock($id_order,$id_branch);
							//insert to email log													
							$res = $this->m_client->insertdata('tb_sending_email',$mail);
							$notif = 'Add status '.$name_status.' successfull';
						}else{					
							$notif = 'Payment already received';
						}
					}elseif ($code_status == 'RCVP' || $code_status == 'PRCVT' || $code_status == 'RCVOP' || $code_status == 'RCVUP' || $code_status == 'RCVTF' || $code_status == 'RCVPPL'){
						//payment Receipe
						if ($get[0]->payment_receive > 0){						
							//jika sudah input transaksi payment
							$res = $this->m_admin->editdata('tb_order',$edit,$where);
							$res = $this->m_admin->insertdata('tb_order_history',$post);
							
							//insert to email log
							$res = $this->m_client->insertdata('tb_sending_email',$mail);
							$notif = 'Add status '.$name_status.' successfull';
							
						}else{
							$notif = 'please input payment transaction first!';
						}
					}else{				
						//jika status bukan order diaktifkan kembali
						if ($code_status != 'ORDAC'){							
							if ($code_status == 'RCVB' || $code_status == 'DLVR' || $code_status == 'BSPC'){
								//insert to email log													
								$res = $this->m_client->insertdata('tb_sending_email',$mail);	
								if ($code_status == 'DLVR'){
									//jika status barang proses pengiriman
									$res = $this->m_admin->editdata('tb_order',array('on_delivery'=>1),$where);
								}								
							}
							$res = $this->m_admin->editdata('tb_order',$edit,$where);
							$res = $this->m_admin->insertdata('tb_order_history',$post);
							$notif = 'Add status '.$name_status.' successfull';
						}else{
							$notif = 'Order is still active';
						}													
				   }														
				}else{			
					//jika mengaktifkan order kembali		
					if ($code_status == 'ORDAC'){
						$edit['cancel'] = 0;
						$edit['active'] = 1;
						$res = $this->m_admin->editdata('tb_order',$edit,$where);
						$res = $this->m_admin->editdata('tb_order_detail',array('active'=>1),$where);
						$res = $this->m_admin->insertdata('tb_order_history',$post);
						$notif = $name_status.' sucessfull';
						//memotong stock kembali
						$ex = $this->m_client->get_order_detail(array('A.id_order'=>$id_order));
						if (count($ex) > 0){
							foreach ($ex as $row){
								$data = array(
									 'id_order'=>$row->id_order,
									 'id_product'=>$row->id_product,
									 'id_product_attribute'=>$row->id_product_attribute,
									 'qty_buy'=>$row->product_qty
								);
								$move_code='REACTV';//order diaktivkan kembali
								$res = $this->m_client->stock_reduction($data,$move_code);
							}
						}
						/*
						 * kirim email
						 */
					}else{
						$notif = 'order has been cancel!, please reactivate order';
					}					
				}				
				if ($res > 0){
					$content= $this->m_content->recent_activities(array('A.id_order'=>$id_order));
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'type'=>'summary','error'=>0,'content'=>$content,'msg'=>$notif));
				}else{
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'type'=>'error','error'=>1,'msg'=>'Can not add status '.$name_status.', '.$notif));
				}
				
			}else{
				$notif = 'Status already input';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'type'=>'error','error'=>1,'msg'=>'Can not add status '.$name_status.', '.$notif));
			}			
			if ($this->db->trans_status() === false){
				$this->db->trans_rollback();
			}else{
				$this->db->trans_complete();
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'type'=>'error','error'=>1,'msg'=>sprintf(lang('required'),'Statuses')));
		}
		
	}
	
	function save_pay(){
		$this->db->trans_start();
		$id_order_pay = $this->m_admin->get_rand_id('PY');
		$id = $this->input->post('id_order',true);
		$amount = $this->input->post('amount',true);
		$balance = $this->input->post('balance',true);
		$amountpay = $this->input->post('amountpay',true);
		$date_pay = $this->input->post('datepay',true);
		$datepay = date('Y-m-d',strtotime($date_pay));
		$note = $this->input->post('paynote',true);
		$date_add = $this->datenow;
		$add_by = $this->addby;
		if ($amountpay == '' || $amountpay == '0'){
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'type'=>'error','error'=>1,'msg'=>sprintf(lang('required'),'Amount Pay')));
		}elseif ($date_pay == ''){
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'type'=>'error','error'=>1,'msg'=>sprintf(lang('required'),'Date of Pay')));
		}else{
			$balance_tot = $balance + $amountpay;
			if ($balance_tot > 0){
				$status = 'OverPaid';
			}elseif ($balance_tot < 0){
				$status = 'UnderPaid';
			}else{
				$status = 'Paid';
			}
			$input['id_order_pay'] = $id_order_pay;
			$input['id_order'] = $id;
			$input['id_employee_pay'] = $this->id_employee;
			$input['add_by_pay'] = $this->addby;
			$input['iso_code'] = $this->input->post('iso_code');
			$input['date_add_pay'] = $this->datenow;
			$input['payment_method'] = $this->input->post('pay_method');
			$input['status_pay'] = $status;			
			$input['total_amount'] = $amount;
			$input['total_pay'] = $amountpay;
			$input['total_balance'] = $balance_tot;
			$input['notes'] = $note;
			$input['m_date_add_pay'] = $datepay;
			//insert to web_order_pay
			$res = $this->m_admin->insertdata('tb_order_pay',$input);
			/*
			 * insert order pay notify
			*/
			$res = $this->m_client->insert_notify('tb_order_pay_notify',array('id_order'=>$id,'id_order_pay'=>$id_order_pay));
			
			//edit order
			$this->db->set('total_amount_pay','total_amount_pay+'.$amountpay,false);
			$this->db->set('total_balance','total_balance+'.$amountpay,false);
			$this->db->set('payment_result',$status);
			$this->db->set('payment_receive',1);
			$this->db->where('id_order',$id);
			$res = $this->db->update('tb_order');
			
			if ($this->db->trans_status() === false){
				$this->db->trans_rollback();
			}else{
				$this->db->trans_complete();
				if ($res > 0){
					$sql = $this->m_client->get_order(array('A.id_order'=>$id));
					foreach ($sql as $row)
						foreach ($row as $key=>$val){
						$data[$key] = $val;
					}
					$data['class'] = $this->class;
					$res = $this->load->view($this->class.'/detail/vw_tab_payment',$data,true);
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'type'=>'summary','error'=>0,'content'=>$res,'msg'=>'Save Payment Transaction successfull!'));
				}
			}			
			
		}
		
	}
	function adjustindent(){
		$val = $this->input->post('value',true);
		$ex = explode(",", $val);
		$id_order = isset($ex[0]) ? $ex[0] : 0;
		$id_product = isset($ex[1]) ? $ex[1] : 0;
		$id_product_attribute = isset($ex[2]) ? $ex[2] : 0;
		$data = array('id_order'=>$id_order,'id_product'=>$id_product,'id_product_attribute'=>$id_product_attribute);
		$sql = $this->m_admin->get_stock_indent($data);
		if (count($sql) > 0){
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}
			$data['class'] = $this->class;
			$data['id_form'] = 'formmodal';
			$data['page_title']='Adjust Qty Order Indent';
			$view = $this->load->view($this->class.'/detail/vw_modal_adjustindent',$data,true);
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'modal'=>$view));
		}
	}
	function save_adjust_indent(){
		$this->db->trans_start();
		$id_stock_indent = $this->input->post('id_stock_indent',true);
		$id_warehouse = $this->input->post('warehouse',true);
		$id_location = $this->input->post('location',true);
		$sql = $this->m_admin->get_stock_indent(array('id_stock_indent'=>$id_stock_indent));
		//jika stock indent
		if (count($sql) > 0){
			$id_order = $sql[0]->id_order;
			$id_product = $sql[0]->id_product;
			$id_product_attribute = $sql[0]->id_product_attribute;			
			$qty_adjust = $this->input->post('qtyadjust',true);
			$where = array('id_product'=>$id_product,'id_product_attribute'=>$id_product_attribute,
						   'id_warehouse'=>$id_warehouse,'id_warehouse_location'=>$id_location);			
			$cek_available = $this->m_admin->cek_stock_available($where);
			//jika ada stock di warehouse stock available
			if (count($cek_available) > 0){
				$qty_available = $cek_available[0]->qty_available;
				if ($qty_adjust <= $qty_available){			
					//update qty available and sold in stock		
					$this->db->set('qty_sold','qty_sold+'.$qty_adjust,false);
					$this->db->set('qty_available','qty_available-'.$qty_adjust,false);
					$this->db->where($where);//aray 1 dimensi					
					$res = $this->db->update('tb_stock_available');
					
					//update status order detail to ready
					$this->db->set('preorder',0);
					$this->db->where('id_order',$id_order);
					$this->db->where('id_product',$id_product);
					$this->db->where('id_product_attribute',$id_product_attribute);
					$res = $this->db->update('tb_order_detail');
					
					//update status stock indent to ready 
					$this->db->set('status',1);
					$this->db->where('id_stock_indent',$id_stock_indent);					
					$res = $this->db->update('tb_stock_indent');
					
					//insert web_stock_reduction for history
					$insert['id_order'] = $id_order;
					$insert['id_product'] = $id_product;
					$insert['id_product_attribute'] = $id_product_attribute;
					$insert['qty_available'] = $qty_available;
					$insert['qty_reduction'] = $qty_adjust;
					$insert['stock'] = (int)$qty_available - (int)$qty_adjust;
					$insert['date_add_reduction'] = $this->datenow;
					$res = $this->m_client->insertdata('tb_stock_reduction',$insert);
					
					//deklare insert web_stock_movement
					$insertmove['id_product'] = $id_product;
					$insertmove['id_order'] = $id_order;
					$insertmove['id_product_attribute'] = $id_product_attribute;
					$insertmove['code_move'] = 'ADJIND';//Penyesuaian Pesanan Qty Indent
					$insertmove['id_employee'] = $this->id_employee;
					$insertmove['date_add'] = $this->datenow;
					$insertmove['add_by'] = $this->addby;
					$insertmove['qty_move'] = $qty_adjust;//qty yang dimove
					$insertmove['id_warehouse'] = $id_warehouse;
					$insertmove['id_warehouse_location'] = $id_location;
					$res = $this->m_client->insertdata('tb_stock_movement',$insertmove);
					if ($this->db->trans_status() === FALSE){
						$this->db->trans_rollback();
					}else{
						$this->db->trans_complete();
						if ($res > 0){
							$view_ordet = $this->view($id_order,'','ordet');
							$msg = 'Adjust qty order indent successfull';
							echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'type'=>'modal','msg'=>$msg,'content'=>$view_ordet));
						}
					}
					
					
				}else{
					$msg = 'Qty adjust more than qty available in stock warehouse';
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>$msg));
				}
				
			}else{
				$msg = 'Stock not available in warehouse';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>$msg));
			}
			/*
			 * ptong stock available baseon warehouse,wh location
			 * ubah status preorder di orderdetail
			 * ubah status tb_stock_indent menjadi 1
			 */
		}
		
	}
}
