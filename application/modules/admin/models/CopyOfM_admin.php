<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_admin extends CI_Model{
		var $draw;
		var $column;		
		var $sort;	
		var $length;
		var $start;
		var $keyword;
		var $date_from;
		var $date_to;
		var $req;
		var $code_dep;
		
	public function __construct(){
		parent::__construct();
		$this->code_dep = $this->session->userdata('code_dep');	
		//load currency default in backoffice
		$sql = $this->db->select('id_currency,iso_code,symbol,exchange_rate')
						->from('tb_currency')
						->where('used','bo')
						->where('default',1)
						->where('deleted',0)
						->get()->result();
		foreach ($sql as $row){
			foreach ($row as $key=>$val){
				$_SESSION[$key] = $val;
			}
		}			
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
	function sess_login(){
		if ($this->session->userdata('admin_login') == false){
			redirect(base_url('admin/login'));
		}
	}
	function cek_login($where){
		$this->db->select('A.*,B.name_departement,B.code_departement');
		$this->db->from('tb_employee as A');
		$this->db->join('tb_departement as B','A.id_departement = B.id_departement','left');
		$this->db->where('A.active',1);
		$this->db->where('A.deleted',0);
		$this->db->where($where);
		$sql = $this->db->get()->result();
		return $sql;
	}
	function get_table($table="",$field="",$where=""){
		$fields = (!empty($field) ? $field : '*');
		$this->db->select($fields);
		$this->db->from($table);
		(!empty($where)) ? $this->db->where($where) : '';
		return $this->db->get()->result();
	}
	function datatable(){
		$this->start = $_REQUEST['start'];
		$this->length = $_REQUEST['length'];
		$this->keyword = $_REQUEST['search']['value'];
		$this->draw = $_REQUEST['draw'];
		$this->sort = $_REQUEST['order'][0]['dir'];
		$this->column = $_REQUEST['order'][0]['column'];	
		$this->req = $_REQUEST;	
	}
	
	function range_date($columns,$date_from='',$date_to=''){
		$output = array();
		$column_count = count($columns);
		for($c=0; $c < $column_count; $c++){
			//value sesuai data yang diinput keyword
			$value_search = $this->req['columns'][$c]['search']['value'];//value
			$index_col = $this->req['columns'][$c]['search'];//index value
			//jika melakukan filter
			if (!empty($value_search)){
				if (empty($date_from) && empty($date_to)){
					//jika tidak melakukan filter tanggal
					$this->db->like($columns[$c],$value_search);
				}elseif (!empty($date_from) && empty($date_to)){
					//jika hanya filter tanggal from
					$this->db->like($columns[$c],$value_search);
				}elseif (!empty($date_from) && !empty($date_to)){
					//jika melakukan filter tanggal from dan to
					$this->db->where('DATE_FORMAT('.$columns[$c].',"%Y-%m-%d") >=',$date_from);
					$this->db->where('DATE_FORMAT('.$columns[$c].',"%Y-%m-%d") <=',$date_to);
				}
			}					
		}		
	}
	function get_sidebar($where){	
		$this->db->select('A.*,B.*,C.name_departement,C.code_departement');
		$this->db->from('tb_modul as A');
		$this->db->join('tb_priv as B','A.modul_code = B.modul_code','left');
		$this->db->join('tb_departement as C','B.code_departement = C.code_departement','left');
		$this->db->where('A.active',1);
		$this->db->where('A.deleted',0);
		//$this->db->where('B.view',1);
		$this->db->where('B.active',1);
		$this->db->where('B.code_departement',$this->code_dep);
		$this->db->where($where);
		$this->db->order_by('A.position','ASC');
		return $this->db->get()->result_array();
	}
	function get_priv($ac,$action){
		$notif='';
		$alias_array  = array('view'=>'View Page','add'=>'Add New',
							  'edit'=>'Edit Data','delete'=>'Delete Data','active'=>'Access Modul');	
		$this->db->select('A.*,B.name');
		$this->db->from('tb_priv as A');
		$this->db->join('tb_modul as B','A.modul_code = B.modul_code','left');
		$this->db->where('A.code_departement',$this->code_dep);
		$this->db->where('A.modul_code',$ac);
		$sql = $this->db->get();
		foreach ($sql->result() as $row){
			//jika action false
			if ($row->$action == 0 || $row->active == 0){
				$data['notif'] = 'You do not have permission to '.$alias_array[$action].' '.$row->name;
				$data['error'] = 'vw_access_denied';
				return $data;
			}
		}
	}
	function upload_image($setting)
	{
		$config['upload_path'] = $setting['upload_path'];
		//echo $config['upload_path'];die;
		$config['allowed_types'] = "jpg|png|jpeg|gif|mp4|wmv";
		$config['max_size']	= '150020';
		$config['max_width']  = '15020';
		$config['max_height']  = '15020';
		$config['file_name'] = str_replace(' ', '_', $setting['file_name']);
		$config['overwrite'] = TRUE;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload($setting['var_name']))
		{
			//menampilkan error upload
			echo  $this->upload->display_errors();
		}
		else
		{
			$zdata = array('upload_data'=>$this->upload->data());
			$zfile = $zdata['upload_data']['full_path'];
			chmod($zfile, 0777);
		}	
	}
	function get_employee($where='',$column=''){		
		$this->db->select('A.*,B.name_departement,B.code_departement,C.name_branch');
		$this->db->from('tb_employee as A');
		$this->db->join('tb_departement as B','A.id_departement = B.id_departement','left');
		$this->db->join('tb_branch as C','A.id_branch = C.id_branch','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';		
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/			
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_supplier($where='',$column=''){
		$this->db->select('*');
		$this->db->from('tb_supplier');		
		$this->db->where('deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_department($where='',$column=''){
		$this->db->select('*');
		$this->db->from('tb_departement');
		$this->db->where('deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_table_dt($table="",$field="",$where='',$column=''){
		$fields = (!empty($field) ? $field : '*');
		$this->db->select($fields);
		$this->db->from($table);
		$this->db->where('deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function upload_image_rowmultiple($config,$files,$i){
		$_FILES[$config['name_file']]['name']= $files['name'][$i];
		$_FILES[$config['name_file']]['type']= $files['type'][$i];
		$_FILES[$config['name_file']]['tmp_name']= $files['tmp_name'][$i];
		$_FILES[$config['name_file']]['error']= $files['error'][$i];
		$_FILES[$config['name_file']]['size']= $files['size'][$i];
	
		$config['upload_path'] = $config['upload_path'];
		$config['allowed_types'] = "jpg|png|jpeg|gif";
		$config['max_size']	= '6020';
		$config['max_width']  = '5020';
		$config['max_height']  = '5020';
		$config['overwrite'] = TRUE;
		$config['file_name'] = str_replace(' ', '_', $files['name'][$i]);
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
	
		//uploading proses
		if ($this->upload->do_upload($config['name_file'])) {
			$this->upload->data();
		} else {
			echo  $this->upload->display_errors();die;
		}
	}
	function upload_multiple($count,$set,$files){		
		for($i=0; $i < $count; $i++){
			$_FILES[$set['name_file']]['name']= $files['name'][$i];
			$_FILES[$set['name_file']]['type']= $files['type'][$i];
			$_FILES[$set['name_file']]['tmp_name']= $files['tmp_name'][$i];
			$_FILES[$set['name_file']]['error']= $files['error'][$i];
			$_FILES[$set['name_file']]['size']= $files['size'][$i];
			
			$config['upload_path'] = $set['upload_path'];
			$config['allowed_types'] = "jpg|png|jpeg|gif";
			$config['max_size']	= '6020';
			$config['max_width']  = '5020';
			$config['max_height']  = '5020';
			$config['overwrite'] = TRUE;
			$config['file_name'] = str_replace(' ', '_', $files['name'][$i]);
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			
			//uploading proses
			if ($this->upload->do_upload($set['name_file'])) {
				$this->upload->data();
			}
		}
	}
	function get_branch($where='',$column=''){
		$this->db->select('A.*,B.first_name,B.last_name,C.districts_name,D.id_cities,D.cities_name,E.id_province,E.province_name');
		$this->db->from('tb_branch as A');
		$this->db->join('tb_employee as B','A.head_branch = B.id_employee','left');
		$this->db->join('tb_districts as C','A.id_districts = C.id_districts','left');
		$this->db->join('tb_cities as D','C.id_cities = D.id_cities','left');
		$this->db->join('tb_province as E','D.id_province = E.id_province','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}	
	function get_warehouse($where='',$column=''){
		$this->db->select('A.*,B.first_name,B.last_name,C.name_branch,D.districts_name,
						   E.id_cities,E.cities_name,F.id_province,F.province_name');
		$this->db->from('tb_warehouse as A');
		$this->db->join('tb_employee as B','A.id_head_wh = B.id_employee','left');
		$this->db->join('tb_branch as C','A.id_branch = C.id_branch','left');
		$this->db->join('tb_districts as D','A.id_districts = D.id_districts','left');
		$this->db->join('tb_cities as E','D.id_cities = E.id_cities','left');
		$this->db->join('tb_province as F','E.id_province = F.id_province','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_province($where='',$column=''){
		$this->db->select('A.*,B.country_name');
		$this->db->from('tb_province as A');
		$this->db->join('tb_country as B','A.id_country = B.id_country','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_city($where='',$column=''){
		$this->db->select('A.*,B.province_name,C.country_name');
		$this->db->from('tb_cities as A');
		$this->db->join('tb_province as B','A.id_province = B.id_province','left');
		$this->db->join('tb_country as C','B.id_country = C.id_country','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_currency($where='',$column=''){
		$this->db->select('A.*,B.country_name');
		$this->db->from('tb_currency as A');		
		$this->db->join('tb_country as B','A.id_country = B.id_country','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_country($where='',$column=''){
		$this->db->select('A.*,B.name');
		$this->db->from('tb_country as A');
		$this->db->join('tb_currency as B','A.id_currency = B.id_currency','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_modul($where=''){
		$this->db->select('A.id_modul,A.id_modul_parent,A.name,A.icon,B.*,C.name_departement');
		$this->db->from('tb_modul as A');
		$this->db->join('tb_priv as B','A.modul_code = B.modul_code','inner');
		$this->db->join('tb_departement as C','C.code_departement = B.code_departement','inner');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('A.position','asc');
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function total_modul($where){
		$this->db->select('count(id_priv) as total');
		$this->db->from('tb_priv');
		$this->db->where($where);
		$this->db->where('deleted',0);
		$sql = $this->db->get()->result();
		return $sql[0]->total;	
	}
	function get_zone_coverage($where='',$column=''){		
		$this->db->select('A.*,B.id_courier_zone');
		$this->db->from('tb_districts as A');
		$this->db->join('tb_courier_zone as B','A.id_districts = B.id_districts','left');
		$this->db->where('A.deleted',0);
		$this->db->group_by('A.id_districts');
		//$this->db->having('count(*) < ',2);//tidak menampilkan data yg double
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();		
		return $sql;
	}
	function get_shipping_cost($where='',$column=''){
		$this->db->select('A.districts_name,B.*');
		$this->db->from('tb_districts as A');
		$this->db->join('tb_shipping_cost as B','A.id_districts = B.id_districts_to','left');
		$this->db->where('A.deleted',0);
		//$this->db->group_by('A.id_city');
		//$this->db->having('count(*) < ',2);//tidak menampilkan data yg double
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_category($where='',$column=''){
		$this->db->select('*');
		$this->db->from('tb_category');		
		$this->db->where('deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_product($where='',$column=''){
		$this->db->select('A.*,B.name_supplier,C.name as manufacture,D.name_category,E.rate,F.symbol');
		$this->db->from('tb_product as A');
		$this->db->join('tb_supplier as B','A.id_supplier = B.id_supplier','left');
		$this->db->join('tb_manufacture as C','A.id_manufacture = C.id_manufacture','left');
		$this->db->join('tb_category as D','A.id_parent_category = D.id_category','left');
		$this->db->join('tb_tax as E','A.id_tax = E.id_tax','left');
		$this->db->join('tb_currency as F','A.id_currency = F.id_currency','left');
		$this->db->where('A.deleted',0);
		//$this->db->group_by('A.id_city');
		//$this->db->having('count(*) < ',2);//tidak menampilkan data yg double
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_rand_id($pref){
		$rand = mt_rand(1, 1000000);
		$code = $pref.$rand;
		return $code;
	}
	function get_attachment($where='',$column=''){
		$this->db->select('A.*,B.name');
		$this->db->from('tb_attachment as A');
		$this->db->join('tb_manufacture as B','A.id_manufacture = B.id_manufacture','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_attribute($where='',$column=''){
		$this->db->select('A.*,B.name_group');
		$this->db->from('tb_attribute as A');
		$this->db->join('tb_attribute_group as B','A.id_attribute_group = B.id_attribute_group','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_product_attribute($where='',$column=''){
		$this->db->select('A.*,B.name,C.name_group,D.symbol');
		$this->db->from('tb_product_attribute as A');
		$this->db->join('tb_attribute as B','A.id_attribute = B.id_attribute','left');
		$this->db->join('tb_attribute_group as C','A.id_attribute_group = C.id_attribute_group','left');
		$this->db->join('tb_currency as D','A.id_currency = D.id_currency','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_location($where='',$column=''){
		$this->db->select('A.*,B.name_warehouse');
		$this->db->from('tb_warehouse_location as A');
		$this->db->join('tb_warehouse as B','A.id_warehouse = B.id_warehouse','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_stock_mgm($where='',$column=''){
		$this->db->select('A.id_product,A.name,A.date_add,A.add_by,sum(B.qty_available) as total');
		$this->db->from('tb_product as A');
		$this->db->join('tb_stock_available as B','A.id_product = B.id_product','left');
		$this->db->group_by('A.id_product');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_label_movement($where='',$column=''){
		$this->db->select('A.*,B.name_movement,B.label');
		$this->db->from('tb_label_movement_detail as A');
		$this->db->join('tb_label_movement as B','A.id_label_movement = B.id_label_movement','left');		
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function cek_stock_available($where){
		$this->db->select('*');
		$this->db->from('tb_stock_available');
		$this->db->where($where);
		$sql = $this->db->get()->result();
		return $sql;
	}
	function get_stock_available_attr($where='',$column=''){
		$this->db->select('A.*,B.name,C.name_group,D.symbol,E.name as name_product,E.final_price,
						  F.id_stock_available,F.qty_available,F.qty_default,F.qty_sold,G.name_warehouse,H.name_location');
		$this->db->from('tb_product_attribute as A');
		$this->db->join('tb_attribute as B','A.id_attribute = B.id_attribute','left');
		$this->db->join('tb_attribute_group as C','A.id_attribute_group = C.id_attribute_group','left');
		$this->db->join('tb_currency as D','A.id_currency = D.id_currency','left');
		$this->db->join('tb_product as E','A.id_product = E.id_product','left');
		$this->db->join('tb_stock_available as F','A.id_product_attribute = F.id_product_attribute','left');
		$this->db->join('tb_warehouse as G','F.id_warehouse = G.id_warehouse','left');
		$this->db->join('tb_warehouse_location as H','F.id_warehouse_location = H.id_warehouse_location','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_stock_available_nonattr($where)
	{
		//menampilkan stock available pada setiap warehouse dan location
		$sql = $this->db->select('A.name as name_product,B.id_stock_available,B.qty_available,B.qty_default,B.qty_sold,C.name_location,D.name_warehouse')
						->from  ('tb_product as A')
						->join	('tb_stock_available as B','A.id_product = B.id_product','left')
						->join	('tb_warehouse_location as C','B.id_warehouse_location = C.id_warehouse_location','left')
						->join	('tb_warehouse as D','C.id_warehouse = D.id_warehouse','left')
						->where	($where)
						->where	('A.deleted',0)						
						->get()->result();
		return $sql;
	}
	function get_stock_movement($where='',$column=''){
		$this->db->select('A.id_stock_movement,A.id_product,A.code_move,A.product_name,A.qty_move,A.date_add,A.add_by,
						   B.name_warehouse,C.name_location,D.name_movement,D.label,E.name_label,G.name_group,H.name');
		$this->db->from('tb_stock_movement as A');
		$this->db->join('tb_warehouse as B','A.id_warehouse = B.id_warehouse','left');
		$this->db->join('tb_warehouse_location as C','A.id_warehouse_location = C.id_warehouse_location','left');
		$this->db->join('tb_label_movement as D','A.id_label_movement = D.id_label_movement','left');
		$this->db->join('tb_label_movement_detail as E','A.id_label_movement_detail = E.id_label_movement_detail','left');
		$this->db->join('tb_product_attribute as F','A.id_product_attribute = F.id_product_attribute','left');
		$this->db->join('tb_attribute_group as G','F.id_attribute_group = G.id_attribute_group','left');
		$this->db->join('tb_attribute as H','F.id_attribute = H.id_attribute','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_group($where='',$column=''){
		$this->db->select('A.*,count(*) as total ');
		$this->db->from('tb_group as A');
		$this->db->join('tb_customer_group as B','A.id_group = B.id_group','left');
		$this->db->where('A.deleted',0);
		$this->db->group_by('id_group');
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_gender($where='',$column=''){
		$this->db->select('A.*,B.name_language');
		$this->db->from('tb_gender as A');
		$this->db->join('tb_language as B','A.id_language = B.id_language','left');
		$this->db->where('A.deleted',0);		
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_districts($where='',$column=''){
		$this->db->select('A.*,B.cities_name,C.id_province,C.province_name,D.country_name');
		$this->db->from('tb_districts as A');
		$this->db->join('tb_cities as B','A.id_cities = B.id_cities','left');
		$this->db->join('tb_province as C','B.id_province = C.id_province','left');
		$this->db->join('tb_country as D','C.id_country = D.id_country','left');
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_customer($where='',$column=''){
		$this->db->select('A.*,B.name as gender,C.name_received,
						   C.id_address,C.alias_name,C.postcode,C.address,C.phone_addr,C.id_districts,C.company,C.default,
						   D.districts_name,E.id_cities,E.cities_name,F.id_province,F.province_name,G.id_country,G.country_name');				
		$this->db->from('tb_customer as A');
		$this->db->join('tb_gender as B','A.id_gender = B.id_gender','left');
		$this->db->join('tb_customer_address as C','A.id_customer = C.id_customer','left');
		$this->db->join('tb_districts as D','C.id_districts = D.id_districts','left');
		$this->db->join('tb_cities as E','D.id_cities = E.id_cities','left');
		$this->db->join('tb_province as F','E.id_province = F.id_province','left');
		$this->db->join('tb_country as G','F.id_country = G.id_country','left');
		//$this->db->where('C.default',1);
		$this->db->where('A.deleted',0);
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_customer_group($where='',$column=''){
		$this->db->select('A.*,B.name as gender,C.name_received,
						   C.id_address,C.alias_name,C.postcode,C.address,C.phone_addr,C.id_districts,C.company,
						   D.districts_name,E.id_cities,E.cities_name,F.id_province,F.province_name,G.id_country,G.country_name,
						   I.name_group');
		$this->db->from('tb_customer as A');
		$this->db->join('tb_gender as B','A.id_gender = B.id_gender','left');
		$this->db->join('tb_customer_address as C','A.id_customer = C.id_customer','left');
		$this->db->join('tb_districts as D','C.id_districts = D.id_districts','left');
		$this->db->join('tb_cities as E','D.id_cities = E.id_cities','left');
		$this->db->join('tb_province as F','E.id_province = F.id_province','left');
		$this->db->join('tb_country as G','F.id_country = G.id_country','left');
		$this->db->join('tb_customer_group as H','A.id_customer = H.id_customer','left');
		$this->db->join('tb_group as I','H.id_group = I.id_group','left');
		//$this->db->where('C.default',1);
		$this->db->where('A.deleted',0);
		$this->db->group_by('A.id_customer');
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_name_group($id){
		$this->db->select('A.name_group');
		$this->db->from('tb_group as A');
		$this->db->join('tb_customer_group as B','A.id_group = B.id_group','right');
		$this->db->where('A.deleted',0);
		$this->db->where('B.id_customer',$id);
		$sql  = $this->db->get()->result();
		return $sql;
	}
	function get_specific_price($where){
		$this->db->select('A.*,B.name,C.price_impact,D.name,E.name_group as attr_group,F.symbol,G.name_group,H.first_name,H.last_name,H.email');
		$this->db->from('tb_specific_price as A');
		$this->db->join('tb_product as B','A.id_product = B.id_product','left');
		$this->db->join('tb_product_attribute as C','A.id_product_attribute = C.id_product_attribute','left');
		$this->db->join('tb_attribute as D','C.id_attribute = D.id_attribute','left');
		$this->db->join('tb_attribute_group as E','C.id_attribute_group = E.id_attribute_group','left');
		$this->db->join('tb_currency as F','A.id_currency = F.id_currency','left');
		$this->db->join('tb_group as G','A.id_group_sp = G.id_group','left');	
		$this->db->join('tb_customer as H','A.id_customer_sp = H.id_customer','left');	
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('A.date_add','desc');
		$sql = $this->db->get()->result();
		return $sql;
	}
	function get_purchase_order($where='',$column=''){
		$this->db->select('A.*,B.name_supplier,C.name as name_courier,D.name_branch,D.phone_branch,D.address_branch,
						   E.name as name_tax,F.iso_code,F.symbol ');
		$this->db->from('tb_purchase_order as A');
		$this->db->join('tb_supplier as B','A.id_supplier = B.id_supplier','left');
		$this->db->join('tb_courier as C','A.id_courier = C.id_courier','left');
		$this->db->join('tb_branch as D','A.id_branch = D.id_branch','left');
		$this->db->join('tb_tax as E','A.id_tax = E.id_tax','left');
		$this->db->join('tb_currency as F','A.id_currency = F.id_currency','left');
		$this->db->where('A.deleted',0);		
		($where !='') ? $this->db->where($where) : '';
		if ($column != ''){
			$columns = $column[$this->column];
			/*where digunakan untuk get by field*/
			$this->db->limit($this->length,$this->start);
			$this->db->order_by($columns,$this->sort);
		}
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_sum_po_cart($id){
		$this->db->select('sum(total_unit_price) as total_price,sum(unit_qty) as total_qty');
		$this->db->from('tb_purchase_cart_det');
		$this->db->where('id_purchase_cart',$id);
		$sql = $this->db->get()->result();
		return $sql;
	}
	function get_purchase_cart($where,$detail=''){
		if ($detail == true){
			$this->db->select('F.id_purchase_cart_det,F.id_purchase_cart,F.id_product,F.id_product_attribute,F.id_currency as currency_det,F.unit_qty,F.unit_price,F.total_unit_price,F.description_det,
							   N.name as name_product,L.name as name_attribute,M.name_group');
			$this->db->join('tb_purchase_cart_det as F','A.id_purchase_cart = F.id_purchase_cart','left');	
			$this->db->join('tb_product as N','F.id_product = N.id_product','left');
			$this->db->join('tb_product_attribute as K','F.id_product_attribute = K.id_product_attribute','left');
			$this->db->join('tb_attribute as L','K.id_attribute = L.id_attribute','left');
			$this->db->join('tb_attribute_group as M','L.id_attribute_group = M.id_attribute_group','left');
		}
		$this->db->select('A.*,B.name_supplier,B.phone,B.address,B.city,C.name as name_courier');
		$this->db->select('G.districts_name,H.id_cities,H.cities_name,I.id_province,I.province_name');
		$this->db->select('E.name as name_currency,E.symbol,E.iso_code');
		$this->db->select('D.name_branch,D.phone_branch,D.address_branch');
		$this->db->select('J.first_name,J.last_name');
		$this->db->from('tb_purchase_cart as A');
		$this->db->join('tb_supplier as B','A.id_supplier = B.id_supplier','left');
		$this->db->join('tb_courier as C','A.id_courier = C.id_courier','left');
		$this->db->join('tb_branch as D','A.id_branch = D.id_branch','left');
		$this->db->join('tb_currency as E','A.id_currency = E.id_currency','left');
		$this->db->join('tb_employee as J','D.head_branch = J.id_employee','left');
		
		$this->db->join('tb_districts as G','D.id_districts = G.id_districts','left');
		$this->db->join('tb_cities as H','G.id_cities = H.id_cities','left');
		$this->db->join('tb_province as I','H.id_province = I.id_province','left');
		$this->db->where($where);
		$sql = $this->db->get()->result();
		return $sql;		
	}
	function ref_increament($init)
	{
		$getmaxnum = $init['sql'];
		//jika nilai true make increament number
		if (count($getmaxnum) > 0)
		{
			//get max for increment
			$number = $getmaxnum[0]->ref;
				
		}else{
			//set default 0 + 1 if change year
			$number = 0;
		}
		$number++;//num + 1		
		$prefix = $init['pref'].$init['mid'];
		$unique = str_pad($number, 5, "0", STR_PAD_LEFT);
		$ref_number = $prefix.$unique;
		return $ref_number;
	}
	public function ref_po()
	{
		//9 = mengambil nilai yang ke 9
		//5 = panjang perhitungan 5 digit
	
		//mendapatkan id_order maks berdasarkan tahun sekarang
		$yearnow = date('Y');
		$sql = $this->db->query('SELECT MAX(substring(id_purchase_order,9,5)) AS ref FROM tb_purchase_order
								WHERE DATE_FORMAT(date_add,"%Y")="'.$yearnow.'" and deleted=0');
		$init['pref'] = 'PO';
		$init['mid'] = date('mdy');
		$init['sql'] = $sql->result();
		
		return $this->ref_increament($init);
	}
}
