<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_client extends CI_Model{
	var $login_user;
	var $id_customer;
	var $date_now;
	var $id_language;
	var $id_order_cart;	
	var $id_employee;
	var $addby;
	public function __construct(){
		parent::__construct();
		//get country code by country
		$this->load->library('getgeoip');
		$ip = $this->input->ip_address();
		$cek= $this->getgeoip->getGeoIP($ip);
		$country_code = isset($cek->country_code) ? $cek->country_code : 'ID';//isset undifined
		$countrycode = ($country_code == 'ID') ? $country_code : 'ENG';//2 option select country only ID&ENG
		
		//jika tidak merubah language / default
		if ($this->session->userdata('sess_lang') == null){
			//mengganti language sesuai negara 				
			$this->load_sess_language(array('country_code'=>$countrycode));
		}
		
		//jika tidak merubah currency
		if ($this->session->userdata('sess_currency') == null){
			$this->load_sess_currency(array('country_code'=>$countrycode));
		}
		
		//load session tax
		$this->load_sess_tax();
		
		//load session company 
		$this->create_session($this->get_company());
		
		/*
		 * create session setting
		*/
		$i=1;
		foreach ($this->get_setting() as $row){
			$content = ($row->active == 1) ? $row->content_setting : '';//jika tidak aktive maka empty
			$site_tipe = isset($row->site_tipe) ? $row->site_tipe : 'null';
			$this->session->set_userdata('konfig_app_'.$i,$content);
			$this->session->set_userdata($site_tipe,$this->session->userdata('konfig_app_'.$i));
			$i++;
		}
		
		$this->login_user = $this->session->userdata('login_user');
		$this->id_customer = $this->session->userdata('id_customer');
		$this->id_language = $this->session->userdata('id_language');
		$this->date_now = date('Y-m-d H:i:s');
		$this->id_order_cart = $this->session->userdata('id_order_cart');
		
		//only use in server side/backend
		$this->id_employee = $this->session->userdata('id_employee');
		$this->addby = $this->session->userdata('first_name');
		
		//load lib language
		$language = strtolower($this->session->userdata('name_language'));
		$this->load->language('other_lang',$language);
		$this->load->language('form_validation_lang',$language);
		$this->load->language('text_email_lang',$language);
		
	}
	function insertdata($table,$post){
		$res = $this->db->insert($table,$post);
		return $res;
	}
	function editdata($table,$data,$where=''){
		(!empty($where)) ? $this->db->where($where) : '';
		$res = $this->db->update($table,$data);
		return $res;
	}
	function deletedata($table,$where=''){
		(!empty($where)) ? $this->db->where($where) : '';
		$res = $this->db->delete($table);
		return $res;
	}
	function get_table($table="",$field="",$where=""){
		$fields = (!empty($field) ? $field : '*');
		$this->db->select($fields);
		$this->db->from($table);
		(!empty($where)) ? $this->db->where($where) : '';
		return $this->db->get()->result();
	}
	function load_sess_language($where=''){
		(!empty($where)) ? $this->db->where($where) : '';
		$this->db->select('id_language,name_language,flag,country_code');
		$this->db->from('tb_language');
		$this->db->where('active',1);
		$this->db->where('deleted',0);		
		$sql = $this->db->get()->result();		
		foreach ($sql as $row){
			foreach ($row as $key=>$val){
				$this->session->set_userdata($key,$val);				
			}
		}			
	}	
	function id_branch(){
		$sql = $this->db->select('id_branch')->from('tb_branch')->where('default',1)->where('deleted',0)->get()->result();
		return isset($sql[0]->id_branch) ? $sql[0]->id_branch : 0;
	}
	function id_warehouse(){		
		$id = $this->id_branch();
		$sql = $this->db->select('id_warehouse')
				->from('tb_warehouse')
				->where('id_branch',$id)
				->where('deleted',0)->get()->result();
		$data=array();
		if (count($sql) > 0){
			foreach ($sql as $row){
				$data[] = $row->id_warehouse;
			}
		}	
		return $data;
	}
	function load_sess_currency($where=''){
		(!empty($where)) ? $this->db->where($where) : '';
		$this->db->select('id_currency as id_currency_fo,name as name_fo,iso_code as iso_code_fo,
						   symbol as symbol_fo,exchange_rate as exchange_rate_fo');
		$this->db->from('tb_currency');
		$this->db->where('used','fo');
		$this->db->where('deleted',0);
		$sql = $this->db->get()->result();
		foreach ($sql as $row){
			foreach ($row as $key=>$val){
				$this->session->set_userdata($key,$val);
			}
		}
	}
	function load_sess_tax($where=''){
		(!empty($where)) ? $this->db->where($where) : '';
		$this->db->select('id_tax as id_tax_fo,name as name_tax_fo,rate as rate_tax_fo');						   
		$this->db->from('tb_tax');
		$this->db->where('default',1);
		$this->db->where('deleted',0);
		$sql = $this->db->get()->result();
		foreach ($sql as $row){
			foreach ($row as $key=>$val){
				$this->session->set_userdata($key,$val);
			}
		}
	}
	function get_menu_header($where=''){		
		$this->db->select('*');
		$this->db->from('tb_category');
		(!empty($where)) ? $this->db->where($where) : '';
		$this->db->where('display',1);
		$this->db->where('active',1);
		$this->db->where('deleted',0);
		$this->db->where('id_language',$this->id_language);
		$this->db->order_by('sort','asc');
		$sql = $this->db->get()->result();
		return $sql;
	}
	function get_category_brand($where=''){
		$this->db->select('A.*');
		$this->db->from('tb_manufacture as A');
		$this->db->join('tb_product as B','A.id_manufacture = B.id_manufacture','left');
		(!empty($where)) ? $this->db->where($where) : '';
		$this->db->where('A.active',1);
		$this->db->where('A.deleted',0);
		$this->db->where('B.active',1);
		$this->db->where('B.deleted',0);
		$this->db->where('A.id_language',$this->id_language);
		$this->db->group_by('A.id_manufacture');
		$sql = $this->db->get()->result();
		return $sql;
		
	}
	function get_company(){
		$this->db->select('id_company,company_name,address_company,
							homephone_company,mobilephone_company,email_company,logo_company');
		$this->db->from('tb_company');
		$this->db->where('deleted',0);
		$sql = $this->db->get()->result();
		return $sql;
	}
	function create_session($sql){
		foreach ($sql as $row)
			foreach ($row as $key=>$val){
			$this->session->set_userdata($key,$val);
		}
	}
	function search_like($valkey){
		$column = $valkey[0];
		$keyword = $valkey[1];
		//melakukan pengulangan column
		$this->db->group_start();
		foreach ($column as $val=>$row){
			foreach ($keyword as $key=>$k){
				$this->db->or_like($row,$k);
			}			
		}
		$this->db->group_end();
	}
	function get_product($where='',$keyword=''){
		//jika melakukan searching data
		if (!empty($keyword)){
			$this->search_like($keyword);
		}
		$this->db->select('A.*,B.name as name_manufacture,B.id_manufacture,B.description,C.image_name');
		$this->db->from('tb_product as A');
		$this->db->join('tb_manufacture as B','A.id_manufacture = B.id_manufacture','left');
		$this->db->join('tb_product_image as C','A.id_product = C.id_product','left');
		(!empty($where)) ? $this->db->where($where) : '';
		$this->db->where('A.active',1);
		$this->db->where('A.deleted',0);
		$this->db->where('A.display',1);
		$this->db->where('C.sort',1);
		$this->db->where('A.id_language',$this->id_language);			
		$sql = $this->db->get()->result();
		return $sql;
	}
	function get_image($where=''){		
		$this->db->select('image_name,active,sort');
		$this->db->from('tb_product_image');
		(!empty($where)) ? $this->db->where($where) : '';
		$this->db->where('active',1);
		$this->db->where('deleted',0);
		$this->db->order_by('sort','asc');
		$sql = $this->db->get()->result();		
		return $sql;
	}
	function get_specific_price($where,$id_group){				
		$this->db->select('id_product,id_currency,price_sp,disc_sp,UNIX_TIMESTAMP(date_from) as date_from,date_to,id_customer_sp');		
		$this->db->from  ('tb_specific_price');		
		/*
		 * ditampilkan jika tanggal from sudah sesuai atau lewat tanggal sekarang
		 */
		$this->db->where('UNIX_TIMESTAMP(date_from) <=',strtotime($this->date_now));
		$this->db->where_in('id_group_sp',$id_group);
		$this->db->where($where);
		$this->db->order_by('id_specific_price','DESC');
		$this->db->limit(1);//get id last
		$sql = $this->db->get()->result();
		return $sql;
	}
	function get_idgroup(){
		if ($this->login_user == null){
			/*
			 * jika customer tidak login
			 */
			$sql = $this->db->select('id_group')->from('tb_group')->where('default',1)->get()->result();
			return $sql[0]->id_group;
		}else{
			/*
			 * jika login
			 */
			$sql = $this->db->select('id_group')->from('tb_customer_group')->where('id_customer',$this->id_customer)->get()->result();
			
			$id = array();
			foreach ($sql as $row){
				$id[] = $row->id_group;
			}			
			return $id;
		}
	}
	function get_product_attribute($where=''){
		$this->db->select('A.id_product_attribute,A.id_currency,A.price_impact,A.id_attribute,A.id_attribute_group,
						  B.name,C.name_group,D.symbol');
		$this->db->from('tb_product_attribute as A');
		$this->db->join('tb_attribute as B','A.id_attribute = B.id_attribute','left');
		$this->db->join('tb_attribute_group as C','A.id_attribute_group = C.id_attribute_group','left');
		$this->db->join('tb_currency as D','A.id_currency = D.id_currency','left');
		$this->db->where('A.deleted',0);
		$this->db->order_by('A.sort','asc');
		($where !='') ? $this->db->where($where) : '';		
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_gender(){
		$this->db->select('id_gender,name');
		$this->db->from('tb_gender');
		$this->db->where('id_language',$this->id_language);
		$this->db->where('active',1);
		$this->db->where('deleted',0);
		$sql = $this->db->get()->result();
		return $sql;
		
	}
	function cek_account($where){
		$this->db->select('A.*,B.id_address,B.id_districts,C.id_gender,C.name as name_gender');
		$this->db->from('tb_customer as A');
		$this->db->join('tb_customer_address as B','A.id_customer = B.id_customer','left');
		$this->db->join('tb_gender as C','A.id_gender = C.id_gender','left');
		$this->db->where('B.default',1);
		($where !='') ? $this->db->where($where) : '';
		$this->db->where('A.deleted',0);
		//$this->db->where('active',1);
		$sql =  $this->db->get()->result();
		return $sql;
	}	
	function get_setting($where=''){
		$this->db->select('*');
		$this->db->from('tb_setting');
		($where !='') ? $this->db->where($where) : '';
		$this->db->where('deleted',0);
		//$this->db->where('active',1);		
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_customer($where=''){
		$this->db->select('A.*,B.name as name_gender');
		$this->db->from('tb_customer as A');
		$this->db->join('tb_gender as B','A.id_gender = B.id_gender','left');
		$this->db->where('A.deleted',0);		
	    ($where !='') ? $this->db->where($where) : '';
	    $sql =  $this->db->get()->result();
	    return $sql;
	}
	function get_manufacture($where=''){
		$this->db->select('*');
		$this->db->from('tb_manufacture');
		$this->db->where('id_language',$this->id_language);
		$this->db->where('active',1);
		$this->db->where('deleted',0);
		$this->db->where('displays',1);
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('sort','asc');
		$sql = $this->db->get()->result();
		return $sql;	
	}
	function get_promotion($where=''){
		$this->db->select('*');
		$this->db->from('tb_promotion');
		$this->db->where('id_language',$this->id_language);
		$this->db->where('active',1);
		$this->db->where('deleted',0);		
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('sort','asc');
		$sql = $this->db->get()->result();
		return $sql;
	}
	function get_attachment($where){
		$this->db->select('A.*,B.file_name,B.file');
		$this->db->from('tb_product_attachment as A');
		$this->db->join('tb_attachment as B','A.id_attachment = B.id_attachment','left');		
		($where !='') ? $this->db->where($where) : '';
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_stock($where='',$id_warehouse){
		$this->db->select('*');
		$this->db->from('tb_stock_available');
		($where !='') ? $this->db->where($where) : '';
		$this->db->where_in('id_warehouse',$id_warehouse);
		$this->db->where('deleted',0);		
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_rand_id($pref=''){
		$rand = mt_rand(1, 1000000);
		$code = $pref.$rand;
		return $code;
	}
	
	function id_order_cart(){
		if ($this->id_order_cart == null){
			$this->session->set_userdata('id_order_cart',$this->get_rand_id('FO'));
			$id = $this->session->userdata('id_order_cart');
			return $id;				
		}else{
			return $this->id_order_cart;
		}
	}
	function get_cart_content($where){
		$this->db->select('A.*,B.name,B.permalink,B.id_category_level1,C.image_name,E.name as name_attribute,F.name_group');
		$this->db->from('tb_order_cart_detail as A');
		$this->db->join('tb_product as B','A.id_product = B.id_product','left');
		$this->db->join('tb_product_image as C','A.id_product = C.id_product','left');
		$this->db->join('tb_product_attribute as D','A.id_product_attribute = D.id_product_attribute','left');
		$this->db->join('tb_attribute as E','D.id_attribute = E.id_attribute','left');
		$this->db->join('tb_attribute_group as F','D.id_attribute_group = F.id_attribute_group','left');
		$this->db->where('C.sort',1);
		$this->db->order_by('A.date_add','desc');
		$this->db->where('B.id_language',$this->id_language);
		($where !='') ? $this->db->where($where) : '';		
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_sum_cart($where){
		$this->db->select('sum(A.product_qty) as total_qty, sum(A.total_price) as total_price');
		$this->db->from('tb_order_cart_detail as A');
		$this->db->join('tb_product as B','A.id_product = B.id_product','left');
		$this->db->group_by('A.id_order_cart');
		$this->db->where('B.id_language',$this->id_language);
		($where !='') ? $this->db->where($where) : '';		
		$sql =  $this->db->get()->result();
		return $sql;		
	}
	function get_category($where){
		$this->db->select('A.*,B.name_category as name_parent');
		$this->db->from('tb_category as A');
		$this->db->join('tb_category as B','A.id_parent = B.id_category','left');		
		$this->db->where('A.id_language',$this->id_language);
		($where !='') ? $this->db->where($where) : '';		
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_customer_address($where){
		$this->db->select('A.*,B.name as gender,C.name_received,
						   C.id_address,C.alias_name,C.postcode,C.address,C.phone_addr,C.id_districts,C.company,C.default,C.country_code,
						   D.districts_name,E.id_cities,E.cities_name,F.id_province,F.province_name,G.id_country,G.country_name');
		$this->db->from('tb_customer as A');
		$this->db->join('tb_gender as B','A.id_gender = B.id_gender','left');
		$this->db->join('tb_customer_address as C','A.id_customer = C.id_customer','left');
		$this->db->join('tb_districts as D','C.id_districts = D.id_districts','left');
		$this->db->join('tb_cities as E','D.id_cities = E.id_cities','left');
		$this->db->join('tb_province as F','E.id_province = F.id_province','left');
		$this->db->join('tb_country as G','C.id_country = G.id_country','left');
		//$this->db->where('C.default',1);
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('C.default','desc');
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_courier_zone($where){
		$this->db->select('A.*,B.name,B.tracking_url,B.image,B.delay,B.is_free,B.fixed_cost,B.default_courier');
		$this->db->from('tb_courier_zone as A');
		$this->db->join('tb_courier as B','A.id_courier = B.id_courier','left');
		$this->db->where('B.deleted',0);
		$this->db->where('B.active',1);
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('B.date_add','desc');
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_shipp_cost($where){		
		$this->db->select('*');
		$this->db->from('tb_shipping_cost');		
		($where !='') ? $this->db->where($where) : '';
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_sum_shipp(){
		$this->db->select('sum(total_volume) as total_volume,sum(total_weight) as total_weight,iso_code');
		$this->db->from('tb_order_cart_detail');
		$this->db->where('id_order_cart',$this->id_order_cart);
		$this->db->group_by('id_order_cart');
		return $this->db->get()->result();
	}
	function get_payment($where='',$payconfirm=''){		
		$this->db->select('A.*,B.name_type,B.pay_code');
		$this->db->from('tb_payment_method as A');
		$this->db->join('tb_payment_type as B','A.id_payment_type = B.id_payment_type','left');
		if (!empty($where) && empty($payconfirm)){
			//menampilkan kurir coverage payment (kurir yang tercover metode pembayaran)
			$this->db->join('tb_courier_payment as C','B.id_payment_type = C.id_payment_type','left');
			$this->db->where($where);
		}elseif (!empty($where) && !empty($payconfirm)){
			//menampilkan bank tujuan transfer method untuk konfirmasi pembayaran
			$this->db->where($where);
		}
		$this->db->where('A.deleted',0);
		$this->db->where('A.active',1);
		$this->db->where('A.display',1);		
		$this->db->order_by('A.sort','asc');
		$sql =  $this->db->get()->result();
		return $sql;
	}
	
	function id_order($pref)
	{
		$getmaxnum = $this->getMaxId_order();
		//jika nilai true make increament number
		if (count($getmaxnum) > 0)
		{
			//get max for increment
			$number = $getmaxnum[0]->id_order;
				
		}else{
			//set default 0 + 1 if change year
			$number = 0;
		}
		$number++;//num + 1
		$datenow = date('ymd');
		$prefix = $pref.$datenow;
		$unique = str_pad($number, 5, "0", STR_PAD_LEFT);
		$ref_order = $prefix.$unique;
		return $ref_order;
	}
	public function getMaxId_order()
	{
		//9 = mengambil nilai yang ke 9
		//5 = panjang perhitungan 5 digit
	
		//mendapatkan id_order maks berdasarkan tahun sekarang
		$yearnow = date('Y');
		$sql = $this->db->query('SELECT MAX(substring(id_order,9,5))AS id_order FROM tb_order
								WHERE DATE_FORMAT(date_add_order,"%Y")="'.$yearnow.'" and deleted=0');
		return $sql->result();
	}
	function get_order_detail($where='',$cart=false){
		$this->db->select('A.*,B.name,B.permalink,B.id_category_level1,C.image_name,
						   E.name as name_attribute,F.name_group,G.symbol,H.rate as tax,H.name as name_tax');
		if (!$cart){
			//view by order
			$this->db->select('I.id_branch');
			$this->db->from('tb_order_detail as A');
			$this->db->join('tb_order as I','A.id_order = I.id_order','left');
		}else{
			//view by cart
			$this->db->from('tb_order_cart_detail as A');
		}
		
		$this->db->join('tb_product as B','A.id_product = B.id_product','left');
		$this->db->join('tb_product_image as C','A.id_product = C.id_product','left');
		$this->db->join('tb_product_attribute as D','A.id_product_attribute = D.id_product_attribute','left');
		$this->db->join('tb_attribute as E','D.id_attribute = E.id_attribute','left');
		$this->db->join('tb_attribute_group as F','D.id_attribute_group = F.id_attribute_group','left');
		$this->db->join('tb_currency as G','A.iso_code = G.iso_code','left');
		$this->db->join('tb_tax as H','B.id_tax = H.id_tax','left');		
		$this->db->where('C.sort',1);//only image sort 1
		$this->db->where('G.used','fo');
		$this->db->order_by('A.date_add','desc');		
		($where !='') ? $this->db->where($where) : '';		
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_order($where=''){
		$this->db->select('A.*,B.*');
		$this->db->select('C.id_address,C.name_received,C.address,C.company,C.alias_name,C.postcode,C.phone_addr,C.country_code');
		$this->db->select('D.name as name_courier,D.tracking_url,D.image,D.delay,D.is_free,');
		$this->db->select('E.iso_code,E.symbol');
		$this->db->select('F.method_name,F.method_code,F.name_owner,F.content,F.logo,F.address as address_pay,F.description');
		$this->db->select('G.name_language,G.flag');
		$this->db->select('H.districts_name,I.id_cities,I.cities_name,J.id_province,J.province_name,K.id_country,K.country_name');
		$this->db->select('L.pay_code,M.name_status,N.label_color,O.name as gender,O.criteria');
		$this->db->from('tb_order as A');
		$this->db->join('tb_customer as B','A.id_customer = B.id_customer','left');
		$this->db->join('tb_customer_address as C','A.id_address_delivery = C.id_address','left');
		$this->db->join('tb_courier as D','A.id_courier = D.id_courier','left');
		$this->db->join('tb_currency as E','A.id_currency = E.id_currency','left');
		$this->db->join('tb_payment_method as F','A.id_payment_method = F.id_payment_method','left');
		$this->db->join('tb_language as G','A.id_language = G.id_language','left');		
		$this->db->join('tb_districts as H','C.id_districts = H.id_districts','left');
		$this->db->join('tb_cities as I','H.id_cities = I.id_cities','left');
		$this->db->join('tb_province as J','I.id_province = J.id_province','left');
		$this->db->join('tb_country as K','C.id_country = K.id_country','left');
		$this->db->join('tb_payment_type as L','F.id_payment_type = L.id_payment_type','left');
		$this->db->join('tb_order_status as M','A.code_status_order = M.code_status','lefft');
		$this->db->join('tb_label_color as N','M.id_label_color = N.id_label_color','left');
		$this->db->join('tb_gender as O','B.id_gender = O.id_gender','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('A.date_add_order','desc');
		$sql =  $this->db->get()->result();
		return $sql;
	}	
	function insert_notify($table,$emp_post){
		$emp = $this->get_table('tb_employee',array('id_employee'),array('deleted'=>0));
		$res=0;
		if (count($emp)){
			foreach ($emp as $id_emp)
			{
				$emp_post['id_employee'] = $id_emp->id_employee;
				$embatch[] = $emp_post;
			}
			$res =  $this->db->insert_batch($table,$embatch);
		}
		return $res;
	}
	function get_banner($where=''){
		$this->db->select('banner_name,image_banner,id_banner_position,link_url');
		$this->db->from('tb_banner');
		$this->db->where('active',1);
		$this->db->where('deleted',0);
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('sort','asc');
		$sql =  $this->db->get()->result();
		return $sql;
	}	
	function get_helper($where=''){
		$this->db->select('id_helper,name_helper,sort_helper');
		$this->db->from('tb_helper');
		$this->db->where('active',1);
		$this->db->where('deleted',0);		
		$this->db->where('id_language',$this->id_language);
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('sort_helper','asc');
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_helper_detail($where=''){
		$this->db->select('A.id_helper,A.name_helper,A.sort_helper');
		$this->db->select('B.id_helper_detail,B.title_helper_detail,B.content,B.sort');
		$this->db->from('tb_helper as A');
		$this->db->join('tb_helper_detail as B','A.id_helper = B.id_helper');
		$this->db->where('B.active',1);
		$this->db->where('B.deleted',0);
		$this->db->where('A.id_language',$this->id_language);
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('B.sort','asc');
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_privacy_policy($where=''){
		$this->db->select('id_privacy_policy,name,content,sort');
		$this->db->from('tb_privacy_policy');
		$this->db->where('active',1);
		$this->db->where('deleted',0);
		$this->db->where('id_language',$this->id_language);
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('sort','asc');
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_term($where=''){
		$this->db->select('id_terms_conditions,name,content,sort');
		$this->db->from('tb_term_conditions');
		$this->db->where('active',1);
		$this->db->where('deleted',0);
		$this->db->where('id_language',$this->id_language);
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('sort','asc');
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_status_log($where=''){
		$this->db->select('A.date_add_status,A.add_by,A.notes,B.name_status,C.label_color');
		$this->db->from('tb_order_history as A');
		$this->db->join('tb_order_status as B','A.code_status = B.code_status','left');
		$this->db->join('tb_label_color as C','B.id_label_color = C.id_label_color','left');		
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('A.id_incr','desc');
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_mail_emp($where=''){
		$this->db->select('B.first_name,B.last_name,B.email');
		$this->db->from('tb_email_broadcast as A');
		$this->db->join('tb_employee as B','A.id_employee = B.id_employee','left');
		$this->db->where('B.active',1);
		($where !='') ? $this->db->where($where) : '';
		$sql =  $this->db->get()->result();
		$mail = array();
		if (count($sql) > 0){
			foreach ($sql as $row){
				$mail[] = $row->email;
			}
		}
		return $mail;
	}
	function get_wh_branch(){
		$sql = $this->db->select('A.id_warehouse,B.id_warehouse_location')
		->from	('tb_warehouse as A')
		->join	('tb_warehouse_location as B','A.id_warehouse = B.id_warehouse','left')
		->where	('A.id_branch',1)//default branch jkarta
		->where	('A.deleted',0)
		->get()->result();		
		return $sql;
	}
	function stock_reduction($data,$move_code){
		$id_order = $data['id_order'];
		$id_product = $data['id_product'];
		$id_product_attribute = $data['id_product_attribute'];
		$qty_buy = $data['qty_buy'];
		$add_by = (!$this->addby) ? 'Buyer' : $this->addby;
		//mendapatkan id warehouse dan id_warehouse_location sesuai care center
		$get_sql = $this->m_client->get_wh_branch();
		foreach ($get_sql as $v)
		{
			$id_warehouse_aray[] = $v->id_warehouse;
			$id_warehouse_location_aray[] = $v->id_warehouse_location;
		}
		//get stock available for check
		$whr = array('id_product'=>$id_product,'id_product_attribute'=>$id_product_attribute);
		$cekstock = $this->db->select('*')
						->from('tb_stock_available')
						->where($whr)
						->where_in('id_warehouse',$id_warehouse_aray)//multiple array 
						->where_in('id_warehouse_location',$id_warehouse_location_aray)//multiple array 
						->get();
		if ($cekstock->num_rows() > 0){
			foreach ($cekstock->result() as $st){
				//jika melakukan order indent qty > stock
				if ($qty_buy > $st->qty_available){
					$id_indent = $this->m_client->get_rand_id('PRE');
					$insertindent['id_stock_indent'] = $id_indent;
					$insertindent['id_order'] = $id_order;
					$insertindent['id_product'] = $id_product;
					$insertindent['id_product_attribute'] = $id_product_attribute;
					$insertindent['qty_minus'] = (int)$st->qty_available - (int)$qty_buy;//stock - qty buy (qty request)
					$insertindent['qty_buy_now'] = $qty_buy;
					$insertindent['qty_available_last'] = $st->qty_available; //stock available
					$insertindent['date_add'] = $this->date_now;
					$res = $this->m_client->insertdata('tb_stock_indent',$insertindent);
					$res = $this->m_client->insert_notify('tb_stock_indent_notify',array('id_stock_indent'=>$id_indent));
					
					/*
					 * jika stock masih ada maka akan memotong yg tersisa, jika tidak ada stock maka tidak ada yang dipotong
					* pastikan buyer mau menunggu barang sisa yang indent, dan mau dikirim barang yg ready dlu
					*/
					if ($st->qty_available > 0){
						$this->db->set('qty_sold','qty_sold+'.$st->qty_available,false);
						$this->db->set('qty_available','qty_available-'.$st->qty_available,false);//stock yg dikurang dengan nilainya sendiri
						$this->db->where($whr);//aray 1 dimensi
						$this->db->where('id_warehouse',$st->id_warehouse);//aray 1 dimensi
						$this->db->where('id_warehouse_location',$st->id_warehouse_location);//aray 1 dimensi
						$res = $this->db->update('tb_stock_available');
		
						//insert web_stock_reduction for history
						$insert['id_order'] = $id_order;
						$insert['id_product'] = $id_product;
						$insert['id_product_attribute'] = $id_product_attribute;
						$insert['qty_available'] = $st->qty_available;
						$insert['qty_reduction'] = $qty_buy;
						$insert['stock'] = (int)$st->qty_available - (int)$qty_buy;//sisa stock
						$insert['date_add_reduction'] = $this->date_now;
						$res = $this->m_client->insertdata('tb_stock_reduction',$insert);
						
						//deklare insert web_stock_movement
						$insertmove['id_product'] = $id_product;
						$insertmove['id_order'] = $id_order;
						$insertmove['id_product_attribute'] = $id_product_attribute;
						$insertmove['code_move'] = $move_code;					
						$insertmove['date_add'] = $this->date_now;
						$insertmove['add_by'] = $add_by;
						$insertmove['qty_move'] = $qty_buy;//qty yang dimove
						$insertmove['id_warehouse'] = $st->id_warehouse;
						$insertmove['id_warehouse_location'] = $st->id_warehouse_location;
						$res = $this->m_client->insertdata('tb_stock_movement',$insertmove);
					}
					$param['preorder'] = true;
				}else{
					/*
					 * jika stock tersedia
					*/
					//mengurangi stock available
					$this->db->set('qty_sold','qty_sold+'.$qty_buy,false);
					$this->db->set('qty_available','qty_available-'.$qty_buy,false);
					$this->db->where($whr);//aray 1 dimensi
					$this->db->where('id_warehouse',$st->id_warehouse);//aray 1 dimensi
					$this->db->where('id_warehouse_location',$st->id_warehouse_location);//aray 1 dimensi
					$res = $this->db->update('tb_stock_available');
		
					//insert web_stock_reduction for history
					$insert['id_order'] = $id_order;
					$insert['id_product'] = $id_product;
					$insert['id_product_attribute'] = $id_product_attribute;
					$insert['qty_available'] = $st->qty_available;
					$insert['qty_reduction'] = $qty_buy;
					$insert['stock'] = (int)$st->qty_available - (int)$qty_buy;
					$insert['date_add_reduction'] = $this->date_now;
					$res = $this->m_client->insertdata('tb_stock_reduction',$insert);	

					//deklare insert web_stock_movement
					$insertmove['id_product'] = $id_product;
					$insertmove['id_order'] = $id_order;
					$insertmove['id_product_attribute'] = $id_product_attribute;
					$insertmove['code_move'] = $move_code;
					$insertmove['date_add'] = $this->date_now;
					$insertmove['add_by'] = $add_by;
					$insertmove['qty_move'] = $qty_buy;//qty yang dimove
					$insertmove['id_warehouse'] = $st->id_warehouse;
					$insertmove['id_warehouse_location'] = $st->id_warehouse_location;
					$insertmove['id_employee'] = $this->id_employee;
					$res = $this->m_client->insertdata('tb_stock_movement',$insertmove);
					$param['preorder'] = false;					
				}
				$param['last_stock'] = $st->qty_available;//stock terakhir
				if ($res > 0){
					return $param;
				}else{
					return false;
				}				
			}
		}else{
			return false;
		}
	}
	function req_paypal($id_order){
		$url = 'https://api-3t.sandbox.paypal.com/nvp'; // UNTUK PRODUCTION, GANTI DENGAN URL PRODUCTION
		$gatepaypal = 'https://www.sandbox.paypal.com/cgi-bin/webscr?'; // UNTUK PRODUCTION, GANTI DENGAN URL PRODUCTION
		$data = array(
            'USER' => 'zahra.ameliasiti-facilitator_api1.gmail.com',
            'PWD' => 'admin123',
            'SIGNATURE' => 'ASivmyRTfGC2cXar4BNq8pzXQ0WnAukAR1TfZrlGlmnwUdzi2tS7fTxQ-wiQ9ZxX',
            'METHOD' => 'SetExpressCheckout',
            'VERSION' => '89',
            'cancelUrl' => 'http://localhost/paypalcancel.php',
            'returnUrl' => base_url(FOMODULE.'/checkout/pay_confirm'),
            'BRANDNAME' => $this->session->userdata('tab_title'),
            'HDRIMG' => 'http://kaftan.dev/assets/images/logo/logo-kaftwholesale2.png',
            'PAYMENTREQUEST_0_CURRENCYCODE' => 'USD',
            'PAYMENTREQUEST_0_PAYMENTACTION' => '#'.$id_order,
            'PAYMENTREQUEST_0_DESC' => 'Belanja Hemat kaftan',
            'PAYMENTREQUEST_0_AMT' => 5,
            'PAYMENTREQUEST_0_QTY' => '1',
            'PAYMENTREQUEST_0_ITEMAMT' => 5,
        );
		$req = http_build_query($data);		
		$response_paypal = $this->do_post_request($url,$req);
		print_r($response_paypal);die;
		$arr_response = explode('&',$response_paypal);
		if (isset($arr_response[3]) && $arr_response[3] == 'ACK=Success') {
			// redirect ketika kita mendapatkan lampu hijau dari Paypal
			$token = str_replace('TOKEN=', '', $arr_response[0]);
			$target = $gatepaypal."cmd=_express-checkout&token={$token}";
			header('Location: ' . $target);
			die();
		} else {
			// TERJADI ERROR KETIKA MENGONTAK PAYAPAL
			echo "Error ketika menghubungi paypal:".var_dump($arr_response);die;
		}
		
	}
	// FUNGSI UNTUK MENGHUBUNGI API PAYPAL
	function do_post_request($url, $data, $optional_headers = null)
	{
		$php_errormsg = '';
		$params = array('http' => array(
				'method' => 'POST',
				'content' => $data
		));
		if ($optional_headers !== null) {
			$params['http']['header'] = $optional_headers;
		}
		$ctx = stream_context_create($params);
		$fp = @fopen($url, 'rb', false, $ctx);
		print_r($ctx);die;
		if (!$fp) {
			throw new Exception("Problem with $url, $php_errormsg");
		}
		$response = @stream_get_contents($fp);
		if ($response === false) {
			throw new Exception("Problem reading data from $url, $php_errormsg");
		}
		return $response;
	}
}
