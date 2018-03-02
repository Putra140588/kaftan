<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Checkout extends CI_Controller{
	var $code;
	var $name;	
	var $class;	
	var $date_now;
	var $sess_permalink;
	var $id_order_cart;
	var $symbol;
	var $isocode;
	var $id_customer;
	
	public function __construct(){
		parent::__construct();
		$this->load->model('m_client');
		$this->load->model('m_public');
		$this->load->helper('security');		
		$this->id_order_cart = $this->session->userdata('id_order_cart');
		$this->class = strtolower(__CLASS__);			
		$this->load->library('Encryption');		
		$this->date_now = date('Y-m-d H:i:s');
		$this->symbol = $this->session->userdata('symbol_fo');
		$this->isocode = $this->session->userdata('iso_code_fo');
		$this->id_customer = $this->session->userdata('id_customer');	
		$this->config->load('paypal');
		$this->paypal_config();	
		$this->m_public->maintenance();
		
	}
	function index(){
		//permalink_sess(base_url());		
	}	
	function proses(){		
		$this->session->set_userdata('checkout',true);
		($this->session->userdata('login_user') == false) ? redirect(FOMODULE.'/account/login') : '';		
		$cart_content = $this->m_client->get_cart_content(array('id_order_cart'=>$this->id_order_cart));			
		permalink_sess(FOMODULE.'/'.$this->class.'/proses');
		$data['tab_title'] = lang('checkout');
		$data['meta_keywords'] = lang('checkout');
		$data['meta_description'] = lang('checkout');
			
		foreach ($this->m_public->product_footer_list() as $row=>$val)
			$data[$row] = $val;
		$content = (count($cart_content) > 0) ? 'v_checkout' : 'v_empty_cart';
		//$content = (count($cart_content) > 0) ? 'v_checkout' : 'v_empty_cart';
		$data['content'] = 'checkout/'.$content;
		$this->load->view('w/v_main_body',$data);
		
	}
	function add_address(){
		$res='';	
		$id_address = $this->input->post('value',true);
		if (!empty($id_address)){
			$sql = $this->m_client->get_customer_address(array('C.id_address'=>$id_address));
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}
		}else{
			$data['country_code']='';
		}		
		$data['class'] = $this->class;
		$data['country'] = $this->m_client->get_table('tb_country',array('id_country','country_name','country_code'),array('deleted'=>0));
		$res .=$this->load->view('checkout/v_add_address',$data,true);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0, 'modal'=>$res));
	}
	function show_province($id_province=''){
		$res='';
		$ex = explode("#", $this->input->post('value',true));
		$id = isset($ex[0]) ? $ex[0] : '';
		$country_code = isset($ex[1]) ? $ex[1] : '';
		$content='';
		if ($country_code == 'ID'){
			//jika memilih negara indonesia maka akan ditampilkan wilayah,kecamatan
			$data['id_country'] = $id;
			$data['id_province'] = $id_province;
			$data['class'] = $this->class;
			$content=$this->load->view($this->class.'/v_districts',$data,true);		
		}		
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'districtarea'=>true,'content'=>$content));
	}
	function show_city($id_cities=''){
		$res='';
		$id = $this->input->post('value',true);
		$res .=$this->m_public->chosen_city($id,$id_cities,$this->class);		
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$res));
	}
	function show_districts($id_districts=''){
		$res='';
		$id = $this->input->post('value',true);
		$res .=$this->m_public->chosen_districts($id,$id_districts);		
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$res));
	}
	function save_address(){
		$ex = explode("#", $this->input->post('country',true));
		$default = $this->input->post('default');
		$address = $this->input->post('address',true);
		$alias = $this->input->post('alias',true);
		$name_receiver = $this->input->post('receiver',true);
		$id_address = $this->input->post('id_address',true);//get by select for edit
		$phone = $this->input->post('phone',true);
		$id_country = isset($ex[0]) ? $ex[0] : '';
		$country_code = isset($ex[1]) ? $ex[1] : '';
		$post_code = $this->input->post('postcode',true);
		
		
		$id_districts = 0;
		$id_province = 0;
		$id_city = 0;
		if ($country_code == 'ID'){
			$id_districts = $this->input->post('districts',true);
			$id_province = $this->input->post('province',true);
			$id_city = $this->input->post('city',true);
			$error=true;
			if ($id_province == ''){
				$msg = sprintf(lang('required'),lang('province'));
			}elseif ($id_city == ''){
				$msg = sprintf(lang('required'),lang('city'));
			}elseif ($id_districts == ''){
				$msg = sprintf(lang('required'),lang('distr'));
			}else{
				$error=false;
			}
			if ($error == true){
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>$msg));
				return false;
			}
		}	
		$content = '';
		if ($alias == ''){
			$msg = sprintf(lang('required'),lang('aliasaddr'));	
		}elseif ($name_receiver == ''){
			$msg = sprintf(lang('required'),lang('recvname'));	
		}elseif($phone == ''){
			$msg = sprintf(lang('required'),lang('phone'));
		}elseif ($address == ''){
			$msg = sprintf(lang('required'),lang('fulladdr'));
		}elseif ($id_country == ''){
			$msg = sprintf(lang('required'),lang('country'));
		}else{
			$id_customer = $this->session->userdata('id_customer');
			$post['id_country'] = $id_country;
			$post['id_districts'] = $id_districts;
			$post['name_received'] = $name_receiver;
			$post['address'] = $address;
			$post['alias_name'] = $alias;
			$post['postcode'] = $post_code;
			$post['phone_addr'] = $phone;			
			$post['date_update'] = $this->date_now;			
			$post['update_by'] = $this->session->userdata('first_name',true);		
			$post['country_code'] = $country_code;									
			if (!empty($id_address)){
				//edit by address select
				$res = $this->m_client->editdata('tb_customer_address',$post,array('id_address'=>$id_address,'id_customer'=>$id_customer));
				
				//jika alamat yg diubah adalah alamat default
				if ($default == 1){
					$this->session->set_userdata('id_districts',$id_districts);
					$this->session->set_userdata('id_country',$id_country);
				}
				$msg = lang('editaddr');
			}else{
				//add new
				/*
				 * jika alamat default masih kosong/belum diinput maka melakukan proses update
				* jika sudah diinput maka tambah alamat baru
				*/
				$msg = lang('addnewaddr');
				$post['id_customer'] = $id_customer;
				$post['date_add'] = $this->date_now;
				$post['add_by'] = $this->session->userdata('first_name_fo',true);
				$id_address_sess = $this->session->userdata('id_address');
				$where = array('C.id_customer'=>$id_customer,'id_address'=>$id_address_sess,'C.default'=>1);
				$sql = $this->m_client->get_customer_address($where);
				
				//jika belum ada alamat yang diinput
				if (empty($sql[0]->name_received)){								
					$res = $this->m_client->editdata('tb_customer_address',$post,array('id_address'=>$id_address_sess,'id_customer'=>$id_customer));
					$this->session->set_userdata('id_districts',$id_districts);//set session jika input address pertama kali
					$this->session->set_userdata('id_country',$id_country);
				}else{					
					/*
					 * input address baru jika address pertama sudah diinput
					 */
					$post['id_address'] = $this->m_client->get_rand_id(date('Hi'));
					$res = $this->m_client->insertdata('tb_customer_address',$post);					
				}				
			}						
			if ($res){
				$address = $this->m_public->delivery_address();
				$metod_shipp = $this->m_public->delivery_method();
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'change_address'=>true,'msg'=>$msg,'metod_shipp'=>$metod_shipp,'address'=>$address));				
				return false;
			}
			
		}
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>$msg));
	}
	function change_address(){
		$address='';
		$metod_shipp='';
		$id_address = $this->input->post('value',true);
		$sql = $this->m_client->get_table('tb_customer_address','id_districts',array('id_address'=>$id_address));
		$this->session->set_userdata('id_districts',$sql[0]->id_districts);
		$id_customer = $this->session->userdata('id_customer');
		$res = $this->m_client->editdata('tb_customer_address',array('default'=>0),array('id_customer'=>$id_customer));
		$res = $this->m_client->editdata('tb_customer_address',array('default'=>1),array('id_address'=>$id_address));
		if ($res){
			$msg = lang('chaddress');
			$address = $this->m_public->delivery_address();
			$metod_shipp = $this->m_public->delivery_method();
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'change_address'=>true,'msg'=>$msg,'metod_shipp'=>$metod_shipp,'address'=>$address));
		}		
	}
	function delete_address(){
		$content='';
		$id_address = $this->input->post('value',true);			
		$id_customer = $this->session->userdata('id_customer');
		$res = $this->m_client->deletedata('tb_customer_address',array('id_address'=>$id_address,'id_customer'=>$id_customer));
		if ($res){
			$msg = lang('delladdress');
			$content.= $this->m_public->delivery_address();
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'content'=>$content));
		}	
	}
	function select_courier($id_courier=''){		
		$xpl = explode("#", $this->input->post('value',true));
		$id_districts = $xpl[0];	
		$fixed_cost = $xpl[1];	
		$id_country = $xpl[2];
		$where = array('id_districts_to'=>$id_districts,'id_courier'=>$id_courier,'id_country_to'=>$id_country);
		$x = $this->m_public->generate_shipp_cost($where,$fixed_cost);				
		$cost_shipp_format = $this->symbol.' '.$x['total_price_format'];//format
		$cost_shipp = $x['total_price_num'];//number
		
		
		$z = $this->m_client->get_sum_cart(array('id_order_cart'=>$this->id_order_cart));
		$total_qty = isset($z[0]->total_qty) ? $z[0]->total_qty : 0;
		$totalprice_cart = isset($z[0]->total_price) ? $z[0]->total_price : 0;
		
		//generate tax
		$tx = $this->m_public->generate_tax($totalprice_cart);						
		$total_product_tax = $totalprice_cart + $tx['amount_tax_num'];//total_product + tax
		$total = $total_product_tax + $cost_shipp;//total_product + tax + cost shipping
		$total_shopp = ($this->isocode == 'IDR') ? formatnum($total) : $total;//format
		
		$ex = ($total * 2);
		$total_balance = $total - $ex;
		//make session cost shipp by select
		$sess_data['cost_shipp'] = $cost_shipp;
		$sess_data['total_volume'] = $x['total_volume'];
		$sess_data['total_weight'] = $x['total_weight'];
		$sess_data['total_product'] = $totalprice_cart;
		$sess_data['total_product_tax'] = $total_product_tax;
		$sess_data['total_qty'] = $total_qty;
		$sess_data['total_payment'] = $total;
		$sess_data['amount_tax'] = $tx['amount_tax_num'];
		$sess_data['total_balance'] = $total_balance;
		$this->session->set_userdata($sess_data);	
			
		//get kurir payment can COD
		$wherecod = array('C.id_courier'=>$id_courier);
		$paymentlist = $this->m_public->payment_method($wherecod);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],
							   'error'=>0,
							   'change_courier'=>true,
							   'costshipp'=>$cost_shipp_format,
							   'totalshopp'=>$this->symbol.' '.$total_shopp,
							   'paymentlist'=>$paymentlist				   
		));
	}
	function delete_cart(){
		$content='';
		$id_order_cart_detail = $this->input->post('value',true);
		$res = $this->m_client->deletedata('tb_order_cart_detail',array('id_order_cart_detail'=>$id_order_cart_detail,'id_order_cart'=>$this->id_order_cart));
		if ($res){			
			$msg = 'Success delete item cart';
			$metod_shipp = $this->m_public->delivery_method();
			$x = $this->m_public->generate_sum_cart();	
			$total_qty = $x['total_qty'];
			$total_price = $x['total_price'];
			//top cart
			$listcart = $this->m_public->top_list_cart();
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,
									'msg'=>$msg,'rowcart'=>true,
									'rowid'=>$id_order_cart_detail,
									'metod_shipp'=>$metod_shipp,
									'subtotal'=>$total_price,
									'totqty'=>$total_qty,
									'listcart'=>$listcart
			));
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>'Error delete!'));
		}
	}
	function confirm(){
		// Clear PayPalResult from session userdata
		$this->session->unset_userdata('PayPalResult');
		$id_address_delivery = $this->input->post('deliveryaddress',true);
		$id_courier = $this->input->post('courier',true);
		$id_payment = $this->input->post('payment',true);
		$id_branch = $this->m_client->id_branch();
		$set_sess['method'] = array(
			'id_address_delivery'=>$id_address_delivery,
			'id_courier'=>$id_courier,
			'id_payment'=>$id_payment,
			'id_branch'=>$id_branch,
			'notes'=>$this->input->post('notes',true)
		);
		$this->session->set_userdata('checkout', $set_sess);		
		$x = $this->m_client->get_payment(array('A.id_payment_method'=>$id_payment));
		$pay_code = isset($x[0]->pay_code) ? $x[0]->pay_code : '';
		if ($pay_code == '8809'){
			if ($this->isocode != 'IDR'){
				//paypal payment
				$url = base_url(FOMODULE.'/'.$this->class.'/SetExpressCheckout');
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'paypal'=>true,'error'=>0,'redirect'=>$url));
				return false;
			}else{
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'type'=>'error','error'=>1,'msg'=>'Paypal can only be payment using USD currency, please Change currency first in Top Header'));
			}
		}else{
			$this->confirm_order();
		}
		
	}
	function confirm_order(){
		$this->session->unset_userdata('id_order');
		$cxt = $this->session->userdata('checkout');		
		$id_address_delivery = $cxt['method']['id_address_delivery'];
		$id_courier = $cxt['method']['id_courier'];
		$id_payment = $cxt['method']['id_payment'];
		$id_branch = $cxt['method']['id_branch'];
		$notes = $cxt['method']['notes'];		
		$paypalresult = $this->session->userdata('PayPalResult');
		
		if ($id_address_delivery == ''){
			$msg = lang('noselectaddr');
		}elseif($id_courier == ''){
			$msg = lang('noselectcourier');
		}elseif($id_payment == ''){
			$msg = lang('noselectpay');
		}else{
			$this->db->trans_start();
			$id_order = $this->m_client->id_order('CL');
			$this->session->set_userdata('id_order',$id_order);
			$pay  = array();
			if (!empty($paypalresult)){
				//payment method use paypal
				$pay = $this->DoExpressCheckoutPayment();
				//jika terjadi error maka proses dihentikan
				if ($pay == false){
					return false;
				}				
			}
			$id_order_delivery = $this->m_client->get_rand_id('DLV');
			$post['id_order'] = $id_order;
			$post['id_order_delivery'] = $id_order_delivery;
			$post['id_order_cart'] = $this->id_order_cart;
			$post['id_customer'] = $this->id_customer;
			$post['id_courier'] = $id_courier;
			$post['id_branch'] = $id_branch;
			$post['id_address_delivery'] = $id_address_delivery;
			$post['id_currency'] = $this->session->userdata('id_currency_fo');
			$post['id_payment_method'] = $id_payment;
			$post['id_language'] = $this->session->userdata('id_language');
			$post['iso_code'] = $this->isocode;
			$post['exchange_rate'] = $this->session->userdata('exchange_rate_fo');
			$post['m_date_add'] = $this->date_now;
			$post['date_add_order'] = $this->date_now;
			$post['date_update'] = $this->date_now;
			$post['add_by'] = 'Buyer';
			$post['aggree_terms']=1;
			$post['code_status_order'] = 'WPTF';//Waiting for payment confirmation
			$post['notes'] = $notes;			
			
			$post['total_cost_shipping'] = $this->session->userdata('cost_shipp');
			$post['total_volume'] = $this->session->userdata('total_volume');
			$post['total_weight'] = $this->session->userdata('total_weight');
			$post['total_product'] = $this->session->userdata('total_product');
			$post['total_product_tax'] = $this->session->userdata('total_product_tax');
			$post['total_qty'] = $this->session->userdata('total_qty');
			$post['total_payment'] = $this->session->userdata('total_payment');
			$post['amount_tax'] = $this->session->userdata('amount_tax');
			$post['rate_tax'] = $this->session->userdata('rate_tax_fo');
			$post['total_balance'] = $this->session->userdata('total_balance');
			$post['payment_result'] = 'UnPaid';		

			$intervalday = (int)replace_freetext($this->session->userdata('cancel_interval'));
			$getdateend = $this->db->query('select date_add(curdate(),interval "'.$intervalday.'" day) as day_over')->result();
			$end = date('Y-m-d',strtotime($getdateend[0]->day_over));
			$date_end =  strtotime($end);
			$post['date_order_end'] = $date_end;
			$post['date_order_start'] = strtotime(date('Y-m-d'));			
			
			$sql = $this->m_client->get_table('tb_order_cart_detail','*',array('id_order_cart'=>$this->id_order_cart));
			//jika content cart tersedia			
			
			if (count($sql) > 0){		
				$num=0;									
				foreach ($sql as $row){			
					$id_product = $row->id_product;
					$id_product_attribute = $row->id_product_attribute; 	
					$qty_buy = 	$row->product_qty;			
					$posted['id_order'] = $id_order;	
					$posted['id_order_cart_detail'] = $row->id_order_cart_detail;
					$posted['id_order_cart'] = $row->id_order_cart;
					$posted['id_product'] = $id_product;
					$posted['id_product_attribute'] = $id_product_attribute;
					$posted['iso_code'] = $row->iso_code;
					$posted['product_qty'] = $qty_buy;
					$posted['base_price'] = $row->base_price;
					$posted['final_price'] = $row->final_price;
					$posted['sp_price'] = $row->sp_price;
					$posted['impact_price'] = $row->impact_price;
					$posted['disc_value'] = $row->disc_value;
					$posted['disc_amount'] = $row->disc_amount;
					$posted['unit_price'] = $row->unit_price;
					$posted['total_price'] = $row->total_price;
					$posted['weight'] = $row->weight;
					$posted['width'] = $row->width;
					$posted['height'] = $row->height;
					$posted['length'] = $row->length;
					$posted['total_volume'] = $row->total_volume;
					$posted['total_weight'] = $row->total_weight;
					$posted['date_add'] = $row->date_add;					
					
					/*
					 * stock reduction
					 */
					$data = array('id_order'=>$id_order,'id_product'=>$id_product,
								  'id_product_attribute'=>$id_product_attribute,'qty_buy'=>$qty_buy);
					$move_code='ORDCT';//order dari customer
					$reduce = $this->m_client->stock_reduction($data,$move_code);	
					$posted['preorder']= 0;
					//jika nilai balikan tidak false
					if (!empty($reduce)){
						//jika stock empty, dan melakukan indent
						if ($reduce['preorder'] == true){
							$posted['preorder']=1;//indent status add
						}
						$posted['stock_last'] = $reduce['last_stock'];						
					}
				$batch[] = $posted;
			}
				
				//insert order history status
				$id_order_history = $this->m_client->get_rand_id('H');
				$history['id_order_history'] = $id_order_history;
				$history['id_order'] = $id_order;
				$history['code_status'] = 'WPTF';
				$history['date_add_status'] = $this->date_now;
				$history['m_date_add_status'] = $this->date_now;
				$history['add_by'] = 'Buyer';
				
					$res = $this->db->insert_batch('tb_order_detail',$batch);
					$res = $this->m_client->insertdata('tb_order',$post);
					$res = $this->m_client->insertdata('tb_order_history',$history);
					$res = $this->m_client->insert_notify('tb_order_notify',array('id_order'=>$id_order));
					if ($this->db->trans_status() === false){
						$this->db->trans_rollback();
					}else{
						$this->db->trans_complete();
						if ($res){																											
							$emailparam['subjek'] = sprintf(lang('eorder'),$id_order);
							//jika paypal berhasil transaksi
							if (!empty($pay)){
								$this->save_payment_paypal($pay);
								$emailparam['subjek'] = sprintf(lang('eorderpaypal'),$id_order);
							}							
							/*
							 * email confirm order
							*/
							$mailcode = 'NWORD';
							$data['name'] = $this->session->userdata('first_name_fo');
							$sql = $this->m_client->get_order(array('A.id_order'=>$id_order));
							foreach ($sql as $row)
								foreach ($row as $key=>$val){
								$data[$key] = $val;
							}
							
							$emailparam['email_to'] = $this->session->userdata('email');
							$emailparam['bcc'] = $this->m_client->get_mail_emp(array('A.email_modul_code'=>$mailcode));//multiple array
							$emailparam['content'] = $this->load->view('email_temp/v_confirm_order',$data,true);
							$send = email_send($emailparam);
								
							//input log email
							$status = ($send == true) ? 1 : 0;
							$mail['id'] = $id_order;
							$mail['function_name'] = $mailcode;
							$mail['date_send'] = $this->date_now;
							$mail['status_send'] = $status;
							$res = $this->m_client->insertdata('tb_sending_email',$mail);
							$finish =$this->load->view('checkout/v_finish_order','',true);
							echo json_encode(array('csrf_token'=>csrf_token()['hash'],'konfirm_order'=>true,'error'=>0,'finish_page'=>$finish));
							$this->destroy_cart();
							return false;
							
						}
					}
						
			}
			
			
		}
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'type'=>'error','error'=>1,'msg'=>$msg));
	}
	function edit_qty($var=''){		
		$oldValue = $this->input->post('value');
		$expl = explode(".",$var);
		$id_order_cart_detail = isset($expl[0]) ? $expl[0] : 0;
		$x = isset($expl[1]) ? $expl[1] : 'null';
		if ($x == 'plus'){
			$newvalue = $oldValue + 1;			
			$this->db->set('product_qty','product_qty+1',false);			
		}else if ($x == 'min'){
			if ($oldValue > 0){
				$newvalue = floatval($oldValue) - 1;
			}else{
				$newvalue=0;
			}
			$this->db->set('product_qty','product_qty-1',false);			
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],
					'error'=>1,					
					'msg'=>'Error edit qty'
			));
			return false;
		}
		//update cart item
		$this->db->set('total_price','unit_price*'.$newvalue,false);
		$this->db->set('total_volume','width*height*length/6000*'.$newvalue,false);
		$this->db->set('total_weight','weight*'.$newvalue,false);
		$this->db->where('id_order_cart_detail',$id_order_cart_detail);
		$res = $this->db->update('tb_order_cart_detail');
		if ($res){
			$z = $this->m_client->get_cart_content(array('id_order_cart_detail'=>$id_order_cart_detail));
			$subtotal_price = ($this->isocode == 'IDR') ? number_format($z[0]->total_price,0,'.','.') : $z[0]->total_price;
			
			$x = $this->m_public->generate_sum_cart();
			$total_qty = $x['total_qty'];
			$total_price = $x['total_price'];
			$total_price_num = $x['total_price_num'];
		
			//generate tax
			$tx = $this->m_public->generate_tax($total_price_num);
			
			$listcart = $this->m_public->top_list_cart();
			$metod_shipp = $this->m_public->delivery_method();
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],
					'error'=>0,
					'edit_qty_cart'=>true,
					'newvalue'=>$newvalue,
					'subid'=>$id_order_cart_detail,
					'subtotal'=>$subtotal_price,//row by select
					'totqty'=>$total_qty,
					'subtotprice'=>$total_price,//summary all 
					'listcart'=>$listcart,
					'metod_shipp'=>$metod_shipp,
					'amount_tax'=>$tx['amount_tax_format']
			));
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],
					'error'=>1,
					'msg'=>'Error edit qty'
			));
		}
		
	}
	function destroy_cart(){
		//cart
		$this->session->unset_userdata('id_order_cart');
		$this->session->unset_userdata('cost_shipp');
		$this->session->unset_userdata('total_volume');
		$this->session->unset_userdata('total_weight');
		$this->session->unset_userdata('total_product');
		$this->session->unset_userdata('total_product_tax');
		$this->session->unset_userdata('total_qty');
		$this->session->unset_userdata('total_payment');
		$this->session->unset_userdata('amount_tax');
		$this->session->unset_userdata('total_balance');
		$this->session->unset_userdata('checkout');

		//paypal
		$this->session->unset_userdata('PayPalResult');
		$this->session->unset_userdata('paypal_cart');
	}
	function email_order(){
		$id_order = 'CL17121500019';
		$data['name'] = $this->session->userdata('first_name');
		$sql = $this->m_client->get_order(array('A.id_order'=>$id_order));							
							foreach ($sql as $row)
								foreach ($row as $key=>$val){
								$data[$key] = $val;
							}
							$emailparam['subjek'] = sprintf(lang('eorder'),$id_order);
							$emailparam['email_to'] = $this->session->userdata('email');
							$emailparam['content'] = $this->load->view('email_temp/v_confirm_order',$data,true);
							$emailparam['bcc'] = '';
							$send = email_send($emailparam);
		if ($send){
			$this->load->view('email_temp/v_confirm_order',$data);
		}
	}
	function send_email(){				
		$emailparam['subjek'] = 'Kirim Email';
		$emailparam['email_to'] = 'putrasaputra.sp@gmail.com';
		$emailparam['content'] = 'success sending email';
		$send = email_send($emailparam);
		
		if ($send == true){
			echo 'sending success';
		}
	}
	function paypalform(){		
		$this->m_client->req_paypal('12345');
	}
	function pay_confirm(){
		($this->session->userdata('login_user') == false) ? redirect(FOMODULE.'/account/login') : '';
		permalink_sess(FOMODULE.'/'.$this->class.'/pay_confirm');
		$data['tab_title'] = lang('checkout');
		$data['meta_keywords'] = lang('checkout');
		$data['meta_description'] = lang('checkout');
		$data['content'] = $this->class.'/v_paypal_confirm';
		$this->load->view('w/v_main_body',$data);
	}
	
	function paypal_config(){
		$config = array(
				'Sandbox' => $this->config->item('Sandbox'),            // Sandbox / testing mode option.
				'APIUsername' => $this->config->item('APIUsername'),    // PayPal API username of the API caller
				'APIPassword' => $this->config->item('APIPassword'),    // PayPal API password of the API caller
				'APISignature' => $this->config->item('APISignature'),    // PayPal API signature of the API caller
				'APISubject' => '',                                    // PayPal API subject (email address of 3rd party user that has granted API permission for your app)
				'APIVersion' => $this->config->item('APIVersion'),        // API version you'd like to use for your call.  You can set a default version in the class and leave this blank if you want.
				'DeviceID' => $this->config->item('DeviceID'),
				'ApplicationID' => $this->config->item('ApplicationID'),
				'DeveloperEmailAccount' => $this->config->item('DeveloperEmailAccount')
		);
		
		// Show Errors
		if ($config['Sandbox']) {
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		}
		// Load PayPal library
		$this->load->library('paypal/paypal_pro', $config);
	}
	function SetExpressCheckout()
	{
			
		/**
		 * Here we are setting up the parameters for a basic Express Checkout flow.
		 *
		 * The template provided at /vendor/angelleye/paypal-php-library/templates/SetExpressCheckout.php
		 * contains a lot more parameters that we aren't using here, so I've removed them to keep this clean.
		 *
		 * $domain used here is set in the config file.
		*/
		$totalpayment = $this->session->userdata('total_payment');
		$SECFields = array(
				'maxamt' => $totalpayment, 					// The expected maximum total amount the order will be, including S&H and sales tax.
				'returnurl' => site_url(FOMODULE.'/checkout/GetExpressCheckoutDetails'), 							    // Required.  URL to which the customer will be returned after returning from PayPal.  2048 char max.
				'cancelurl' => site_url(FOMODULE.'/checkout/proses'), 							    // Required.  URL to which the customer will be returned if they cancel payment on PayPal's site.
				'hdrimg' => '', // URL for the image displayed as the header during checkout.  Max size of 750x90.  Should be stored on an https:// server or you'll get a warning message in the browser.
				'logoimg' => 'https://lh3.googleusercontent.com/UzeI3o79pRTW_FZdJc1PUwTzuzrLpe1L706NPCkABeeNczglkjm-ezmpGRCooqbdSEvvQlUkiUYgKIX2OZ-ouitLcMlQObPZUsdVb4gxqJxH7eofSY7t-mesq3zLwxKHxeNgykks-gtlKOnk08y3b6IL_APEd6F67oAvwRU9l59zBY6cXV6N6PqFgwkOTeCVaXSf92OXj5WyjKlY-CNs_o67F0TMU82aV0Rr-F4AV_xalXw89SQd_j4Nfy-_TjI69mtYFRyL0rc_5_W4iPLI8g8Mbnd5-xJ5KOs5NO-ukYDmNVMEa048BbhzNA2PsSocVIXNgs2zWu4SeryooyY8GBEFhjiGZPchwwGuvvqK5PmSvf7Eg_wqniIQArk4NkHNX_bEZA0FzMfP0MFN5_UH5Njvrq1yM74Aq_sMzOAZvZUYw9kVmG3ac68J0Ts27wf2B0xGNjo4ypJZHYr1u0GXII3tgc4-gbXs9QnVjbNIO86b7VCittWT28D2tVVwkNKd0qQcIQ62CrAVc36BEC9l4bLYt4GoTaahpU1_QsMI92fT9-Ds4mQj7Md6Vs-gg835G5Zwk87IUPPYKKutdCTpvnlD4qbrFTCyeFue3BkT=w218-h56-no', // A URL to your logo image.  Formats:  .gif, .jpg, .png.  190x60.  PayPal places your logo image at the top of the cart review area.  This logo needs to be stored on a https:// server.
				'brandname' => $this->session->userdata('company_name'), // A label that overrides the business name in the PayPal account on the PayPal hosted checkout pages.  127 char max.
				'customerservicenumber' => $this->session->userdata('mobilephone_company'),// Merchant Customer Service number displayed on the PayPal Review page. 16 char max.
		);
	
		/**
		 * Now we begin setting up our payment(s).
		 *
		 * Express Checkout includes the ability to setup parallel payments,
		 * so we have to populate our $Payments array here accordingly.
		 *
		 * For this sample (and in most use cases) we only need a single payment,
		 * but we still have to populate $Payments with a single $Payment array.
		 *
		 * Once again, the template file includes a lot more available parameters,
		 * but for this basic sample we've removed everything that we're not using,
		 * so all we have is an amount.
		*/
		$Payments = array();
		$Payment = array(
				'amt' => $totalpayment, 	// Required.  The total cost of the transaction to the customer.  If shipping cost and tax charges are known, include them in this value.  If not, this value should be the current sub-total of the order.
		);
	
		/**
		 * Here we push our single $Payment into our $Payments array.
		*/
		array_push($Payments, $Payment);
	
		/**
		 * Now we gather all of the arrays above into a single array.
		*/
		$PayPalRequestData = array(
				'SECFields' => $SECFields,
				'Payments' => $Payments,
		);
	
		/**
		 * Here we are making the call to the SetExpressCheckout function in the library,
		 * and we're passing in our $PayPalRequestData that we just set above.
		*/
		$PayPalResult = $this->paypal_pro->SetExpressCheckout($PayPalRequestData);	
		/**
		 * Now we'll check for any errors returned by PayPal, and if we get an error,
		 * we'll save the error details to a session and redirect the user to an
		 * error page to display it accordingly.
		 *
		 * If all goes well, we save our token in a session variable so that it's
		 * readily available for us later, and then redirect the user to PayPal
		 * using the REDIRECTURL returned by the SetExpressCheckout() function.
		*/
		if(!$this->paypal_pro->APICallSuccessful($PayPalResult['ACK']))
		{
			$errors = array('Errors'=>$PayPalResult['ERRORS']);	
			$this->error($errors);
		}
		else
		{
			// Successful call.
	
			// Set PayPalResult into session userdata (so we can grab data from it later on a 'payment complete' page)
			$this->session->set_userdata('PayPalResult', $PayPalResult);
	
			// In most cases you would automatically redirect to the returned 'RedirectURL' by using: redirect($PayPalResult['REDIRECTURL'],'Location');
			// Move to PayPal checkout
			redirect($PayPalResult['REDIRECTURL'], 'Location');
		}
	}
	function GetExpressCheckoutDetails()
	{
		($this->session->userdata('login_user') == false) ? redirect(FOMODULE.'/account/login') : '';
		permalink_sess(FOMODULE.'/'.$this->class.'/GetExpressCheckoutDetails');
	
		// Get PayPal data from session userdata
		$SetExpressCheckoutPayPalResult = $this->session->userdata('PayPalResult');
		$PayPal_Token = $SetExpressCheckoutPayPalResult['TOKEN'];
		/**
		 * Now we pass the PayPal token that we saved to a session variable
		 * in the SetExpressCheckout.php file into the GetExpressCheckoutDetails
		 * request.
		*/
		$PayPalResult = $this->paypal_pro->GetExpressCheckoutDetails($PayPal_Token);
		
	
		/**
		 * Now we'll check for any errors returned by PayPal, and if we get an error,
		 * we'll save the error details to a session and redirect the user to an
		 * error page to display it accordingly.
		 *
		 * If the call is successful, we'll save some data we might want to use
		 * later into session variables.
		*/
		if(!$this->paypal_pro->APICallSuccessful($PayPalResult['ACK']))
		{
			$errors = array('Errors'=>$PayPalResult['ERRORS']);
			$this->error($errors);
		}else{
						
			$data['tab_title'] = lang('checkout');
			$data['meta_keywords'] = lang('checkout');
			$data['meta_description'] = lang('checkout');
			foreach ($this->m_public->product_footer_list() as $row=>$val)
				$data[$row] = $val;
			$data['content'] = 'paypal_payment/v_complete_confirm';
			$this->load->view('w/v_main_body',$data);
		}
	
	}
	function DoExpressCheckoutPayment()
	{
		/**
		 * Now we'll setup the request params for the final call in the Express Checkout flow.
		 * This is very similar to SetExpressCheckout except that now we can include values
		 * for the shipping, handling, and tax amounts, as well as the buyer's name and
		 * shipping address that we obtained in the GetExpressCheckoutDetails step.
		 *
		 * If this information is not included in this final call, it will not be
		 * available in PayPal's transaction details data.
		 *
		 * Once again, the template for DoExpressCheckoutPayment provides
		 * many more params that are available, but we've stripped everything
		 * we are not using in this basic demo out.
		 */
	
	
		// Get cart data from session userdata
		$SetExpressCheckoutPayPalResult = $this->session->userdata('PayPalResult');
		$PayPal_Token = $SetExpressCheckoutPayPalResult['TOKEN'];
		$PayPalResult_ = $this->paypal_pro->GetExpressCheckoutDetails($PayPal_Token);
		
	
		/**
		 * Just like with SetExpressCheckout, we need to gather our $Payment
		 * data to pass into our $Payments array.  This time we can include
		 * the shipping, handling, tax, and shipping address details that we
		 * now have.
		*/
		
		$totalpayment = $this->session->userdata('total_payment');
		$total_product = $this->session->userdata('total_product');
		$total_cost_shipp = $this->session->userdata('cost_shipp');
		$amount_tax = $this->session->userdata('amount_tax');
		$paypal_payer_id = isset($PayPalResult_['PAYERID']) ? $PayPalResult_['PAYERID'] : '';
		$phone_number = isset($PayPalResult_['PHONENUM']) ? $PayPalResult_['PHONENUM'] : '';
		$email = isset($PayPalResult_['EMAIL']) ? $PayPalResult_['EMAIL'] : '';
		$first_name = isset($PayPalResult_['FIRSTNAME']) ? $PayPalResult_['FIRSTNAME'] : '';
		$last_name = isset($PayPalResult_['LASTNAME']) ? $PayPalResult_['LASTNAME'] : '';
		$payerstatus = isset($PayPalResult_['PAYERSTATUS']) ? $PayPalResult_['PAYERSTATUS'] : '';
		$rawrequest = isset($PayPalResult_['RAWREQUEST']) ? $PayPalResult_['RAWREQUEST'] : '';
		$rawresponse = isset($PayPalResult_['RAWRESPONSE']) ? $PayPalResult_['RAWRESPONSE'] : '';
		$shipp = $PayPalResult_['PAYMENTS'][0];
		$shipping_name = isset($shipp['SHIPTONAME']) ? $shipp['SHIPTONAME'] : '';
		$shipping_street = isset($shipp['SHIPTOSTREET']) ? $shipp['SHIPTOSTREET'] : '';
		$shipping_city = isset($shipp['SHIPTOCITY']) ? $shipp['SHIPTOCITY'] : '';
		$shipping_state = isset($shipp['SHIPTOSTATE']) ? $shipp['SHIPTOSTATE'] : '';
		$shipping_zip = isset($shipp['SHIPTOZIP']) ? $shipp['SHIPTOZIP'] : '';
		$shipping_country_code = isset($shipp['SHIPTOCOUNTRYCODE']) ? $shipp['SHIPTOCOUNTRYCODE'] : '';
		$shipping_country_name = isset($shipp['SHIPTOCOUNTRYNAME']) ? $shipp['SHIPTOCOUNTRYNAME'] : '';
		
		$Payments = array();
		$Payment = array(
				'amt' => $totalpayment, 	    // Required.  The total cost of the transaction to the customer.  If shipping cost and tax charges are known, include them in this value.  If not, this value should be the current sub-total of the order.
				'itemamt' => $total_product,       // Subtotal of items only.
				'currencycode' => $this->isocode, 					                                // A three-character currency code.  Default is USD.
				'shippingamt' => $total_cost_shipp, 	// Total shipping costs for this order.  If you specify SHIPPINGAMT you mut also specify a value for ITEMAMT.
				'handlingamt' => 0, 	// Total handling costs for this order.  If you specify HANDLINGAMT you mut also specify a value for ITEMAMT.
				'taxamt' => $amount_tax, 			// Required if you specify itemized L_TAXAMT fields.  Sum of all tax items in this order.
				'shiptoname' => $shipping_name, 					            // Required if shipping is included.  Person's name associated with this address.  32 char max.
				'shiptostreet' => $shipping_street, 					        // Required if shipping is included.  First street address.  100 char max.
				'shiptocity' => $shipping_city, 					            // Required if shipping is included.  Name of city.  40 char max.
				'shiptostate' => $shipping_state, 					            // Required if shipping is included.  Name of state or province.  40 char max.
				'shiptozip' => $shipping_zip, 						            // Required if shipping is included.  Postal code of shipping address.  20 char max.
				'shiptocountrycode' => $shipping_country_code, 				    // Required if shipping is included.  Country code of shipping address.  2 char max.
				'shiptophonenum' => $phone_number,  				            // Phone number for shipping address.  20 char max.
				'paymentaction' => 'Sale', 					                                // How you want to obtain the payment.  When implementing parallel payments, this field is required and must be set to Order.
		);
		/**
		 * Here we push our single $Payment into our $Payments array.
		*/
		array_push($Payments, $Payment);
	
		/**
		 * Now we gather all of the arrays above into a single array.
		*/
		$DECPFields = array(
				'token' => $PayPal_Token, 								// Required.  A timestamped token, the value of which was returned by a previous SetExpressCheckout call.
				'payerid' => $paypal_payer_id, 							// Required.  Unique PayPal customer id of the payer.  Returned by GetExpressCheckoutDetails, or if you used SKIPDETAILS it's returned in the URL back to your RETURNURL.
		);
		$PayPalRequestData = array(
				'DECPFields' => $DECPFields,
				'Payments' => $Payments,
		);
	
		/**
		 * Here we are making the call to the DoExpressCheckoutPayment function in the library,
		 * and we're passing in our $PayPalRequestData that we just set above.
		*/
		$PayPalResult = $this->paypal_pro->DoExpressCheckoutPayment($PayPalRequestData);
	
		/**
		 * Now we'll check for any errors returned by PayPal, and if we get an error,
		 * we'll save the error details to a session and redirect the user to an
		 * error page to display it accordingly.
		 *
		 * If the call is successful, we'll save some data we might want to use
		 * later into session variables, and then redirect to our final
		 * thank you / receipt page.
		*/
		if(!$this->paypal_pro->APICallSuccessful($PayPalResult['ACK']))
		{
			$errors = $PayPalResult['ERRORS'];	
			$msg = $errors[0]['L_SEVERITYCODE'].' '.$errors[0]['L_ERRORCODE'].' -> '.$errors[0]['L_LONGMESSAGE'];			
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'type'=>'error','error'=>1,'msg'=>$msg));
			return false;
		}
		else
		{
			// Successful call.
			/**
			 * Once again, since Express Checkout allows for multiple payments in a single transaction,
			 * the DoExpressCheckoutPayment response is setup to provide data for each potential payment.
			 * As such, we need to loop through all the payment info in the response.
			 *
			 * The library helps us do this using the GetExpressCheckoutPaymentInfo() method.  We'll
			 * load our $payments_info using that method, and then loop through the results to pull
			 * out our details for the transaction.
			 *
			 * Again, in this case we are you only working with a single payment, but we'll still
			 * loop through the results accordingly.
			 *
			 * Here, we're only pulling out the PayPal transaction ID and fee amount, but you may
			 * refer to the API reference for all the additional parameters you have available at
			 * this point.
			 *
			 * https://developer.paypal.com/docs/classic/api/merchant/DoExpressCheckoutPayment_API_Operation_NVP/
			 */				
			// Successful order in paypal
			$id_order  = $this->session->userdata('id_order');
			$post['id_order_cart'] = $this->id_order_cart;
			$post['id_order'] = $id_order;
			$post['token'] = $PayPal_Token;
			$post['paypal_payer_id'] = $paypal_payer_id;
			$post['phone_number'] = $phone_number;
			$post['email'] = $email;
			$post['first_name'] = $first_name;
			$post['last_name'] = $last_name;
			$post['payerstatus'] = $payerstatus;
			$post['rawrequest'] = $rawrequest;
			$post['rawresponse'] = $rawresponse;
			$post['date_add'] = $this->date_now;
			$res = $this->m_client->insertdata('tb_paypal_response',$post);
			
			$shpost['id_order_cart'] = $this->id_order_cart;
			$shpost['id_order'] =  $id_order;
			$shpost['shipping_name'] = $shipping_name;
			$shpost['shipping_street'] = $shipping_street;
			$shpost['shipping_city'] = $shipping_city;
			$shpost['shipping_state'] = $shipping_state;
			$shpost['shipping_zip'] = $shipping_zip;
			$shpost['shipping_country_code'] = $shipping_country_code;
			$shpost['shipping_country_name'] = $shipping_country_name;
			$shpost['date_add'] = $this->date_now;
			$res = $this->m_client->insertdata('tb_paypal_shipping',$shpost);
			return $PayPalResult['PAYMENTS'][0];						
		}
	}
	
	
	function save_payment_paypal($pay){
		$this->db->trans_start();
		$id_order_pay = $this->m_client->get_rand_id('PY');
		$id = $this->session->userdata('id_order');
		$trx_paypal_id = isset($pay['TRANSACTIONID']) ? $pay['TRANSACTIONID'] : '';
		$trx_type = isset($pay['TRANSACTIONTYPE']) ? $pay['TRANSACTIONTYPE'] : '';
		$pay_type = isset($pay['PAYMENTTYPE']) ? $pay['PAYMENTTYPE'] : '';
		$amountpay = isset($pay['AMT']) ? $pay['AMT'] : 0;
		$feeamount = isset($pay['FEEAMT']) ? $pay['FEEAMT'] : 0;
		$trx_time = isset($pay['ORDERTIME']) ? $pay['ORDERTIME'] : 0;
		$pay_status = isset($pay['PAYMENTSTATUS']) ? $pay['PAYMENTSTATUS'] : 0;
		$pend_reason = isset($pay['PENDINGREASON']) ? $pay['PENDINGREASON'] : 0;
		$pend_reason_code = isset($pay['REASONCODE']) ? $pay['REASONCODE'] : 0;
		$currencycode = isset($pay['CURRENCYCODE']) ? $pay['CURRENCYCODE'] : 0;
				
		$date_pay = $this->date_now;
		$datepay = date('Y-m-d',strtotime($date_pay));		
		$date_add = $this->date_now;
		$add_by = 'System';
		$note = 'Paypal transaction '.$pay_status;
				
		$status = 'Paid';
		$input['id_order_pay'] = $id_order_pay;
		$input['id_order'] = $id;
		$input['id_employee_pay'] = 0;
		$input['add_by_pay'] = $add_by;
		$input['iso_code'] = $currencycode;
		$input['date_add_pay'] = $this->date_now;
		$input['payment_method'] = 'PAYPAL';
		$input['status_pay'] = $status;
		$input['total_amount'] = $amountpay;
		$input['total_pay'] = $amountpay;
		$input['total_balance'] = 0;
		$input['notes'] = $note;
		$input['m_date_add_pay'] = $datepay;
		
		$input['paypal_trans_id'] = $trx_paypal_id;
		$input['paypal_trans_type'] = $trx_type;
		$input['paypal_pay_type'] = $pay_type;
		$input['paypal_ordertime'] = $trx_time;
		$input['paypal_fee_amount'] = $feeamount;
		$input['paypal_pay_status'] = $pay_status;
		$input['paypal_pend_reason'] = $pend_reason;
		$input['paypal_reason_code'] = $pend_reason_code;
		//insert to web_order_pay
		$res = $this->m_client->insertdata('tb_order_pay',$input);
		/*
		 * insert order pay notify
		*/
		$res = $this->m_client->insert_notify('tb_order_pay_notify',array('id_order'=>$id,'id_order_pay'=>$id_order_pay));
			
		//edit order
		$code_status = 'RCVPPL';//payment accept via paypal
		$this->db->set('paypal_trans_id',$trx_paypal_id);
		$this->db->set('total_amount_pay','total_amount_pay+'.$amountpay,false);
		$this->db->set('total_balance','total_balance+'.$amountpay,false);
		$this->db->set('payment_result',$status);
		$this->db->set('code_status_order',$code_status);
		$this->db->set('payment_receive',1);
		$this->db->where('id_order',$id);
		$res = $this->db->update('tb_order');	

		//order history
		$id_order_hitory = $this->m_client->get_rand_id('H');
		$post['id_order_history'] = $id_order_hitory;
		$post['id_employee'] = 0;
		$post['id_order'] = $id;
		$post['code_status'] = $code_status;
		$post['date_add_status'] = $this->date_now;
		$post['m_date_add_status'] = $datepay;
		$post['notes'] = $note;
		$post['add_by'] = $add_by;
		$res = $this->m_client->insertdata('tb_order_history',$post);
		
		//input send email
		$mail['id'] = $id;
		$mail['function_name'] = $code_status;
		$res = $this->m_client->insertdata('tb_sending_email',$mail);
		if ($this->db->trans_status() === false){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_complete();			
			return true;
		}
	}
	function error($errors){
		$err = $errors['Errors'];
		($this->session->userdata('login_user') == false) ? redirect(FOMODULE.'/account/login') : '';
		permalink_sess(FOMODULE.'/'.$this->class.'/error');
		$data['tab_title'] = $err[0]['L_LONGMESSAGE'];
		$data['meta_keywords'] = $err[0]['L_LONGMESSAGE'];
		$data['meta_description'] = $err[0]['L_LONGMESSAGE'];
		foreach ($this->m_public->product_footer_list() as $row=>$val)
			$data[$row] = $val;
		$this->load->vars('errors', $errors);
		$data['content'] = 'paypal_payment/v_error';
		$this->load->view('w/v_main_body',$data);
	}
}