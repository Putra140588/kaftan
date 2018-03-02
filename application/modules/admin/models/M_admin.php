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
		var $id_employee;
		var $datenow;
		var $addby;
	public function __construct(){
		parent::__construct();
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
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
		//load session company
		$this->create_session($this->get_company());
		
		$this->id_employee = $this->session->userdata('id_employee');
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
		$config['allowed_types'] = "jpg|png|jpeg|gif|mp4|wmv|zip|pdf";
		$config['max_size']	= '150020';
		$config['max_width']  = '15020';
		$config['max_height']  = '15020';
		$config['file_name'] = str_replace(' ', '_', $setting['file_name']);
		$config['overwrite'] = TRUE;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload($setting['var_name']))
		{
			//menampilkan error upload
			$res = array('error'=>true,'msg'=> $this->upload->display_errors());
			return $res;
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
	function get_country_coverage($where='',$column=''){
		$this->db->select('A.*,B.id_courier_zone');
		$this->db->from('tb_country as A');
		$this->db->join('tb_courier_zone as B','A.id_country = B.id_country','left');
		$this->db->where('A.deleted',0);
		$this->db->where('A.default',0);//tidak menampilkan yang difault
		$this->db->group_by('A.id_country');
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
		$this->db->select('A.id_districts,A.districts_name,B.*');
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
	function get_shipping_cost_country($where='',$column=''){
		$this->db->select('A.id_country,A.country_name,B.*');
		$this->db->from('tb_country as A');
		$this->db->join('tb_shipping_cost as B','A.id_country = B.id_country_to','left');
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
		$this->db->select('A.*,B.name_language');
		$this->db->from('tb_category as A');		
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
	function get_manufacture($where='',$column=''){
		$this->db->select('A.*,B.name_language');
		$this->db->from('tb_manufacture as A');
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
	function get_product($where='',$column=''){
		$this->db->select('A.*,B.name_supplier,C.name as manufacture,D.name_category,E.rate,F.symbol,G.name_language');
		$this->db->select('H.image_name');
		$this->db->from('tb_product as A');
		$this->db->join('tb_supplier as B','A.id_supplier = B.id_supplier','left');
		$this->db->join('tb_manufacture as C','A.id_manufacture = C.id_manufacture','left');
		$this->db->join('tb_category as D','A.id_parent_category = D.id_category','left');
		$this->db->join('tb_tax as E','A.id_tax = E.id_tax','left');
		$this->db->join('tb_currency as F','A.id_currency = F.id_currency','left');
		$this->db->join('tb_language as G','A.id_language = G.id_language','left');
		$this->db->join('tb_product_image as H','A.id_product = H.id_product','left');
		$this->db->where('H.sort',1);
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
		$this->db->select('D.image_name');
		$this->db->select('C.name_category');
		$this->db->from('tb_product as A');
		$this->db->join('tb_stock_available as B','A.id_product = B.id_product','left');
		$this->db->join('tb_category as C','A.id_parent_category = C.id_category','left');
		$this->db->join('tb_product_image as D','A.id_product = D.id_product','left');
		$this->db->group_by('A.id_product');
		$this->db->where('D.sort',1);
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
	function get_global_stock_wh($where='',$column=''){
			
		$this->db->select('A.id_product,A.qty_available,A.qty_default,A.qty_sold');
		$this->db->select('B.name as name_product');
		$this->db->select('D.name,E.name_group');
		$this->db->select('F.name_warehouse,G.name_location');
		$this->db->select('H.name_category,I.name_branch');
		$this->db->select('J.image_name');
		
		$this->db->from('tb_stock_available as A');
		$this->db->join('tb_product as B','A.id_product = B.id_product','left');
		$this->db->join('tb_product_attribute as C','A.id_product_attribute = C.id_product_attribute','left');
		$this->db->join('tb_attribute as D','C.id_attribute = D.id_attribute','left');
		$this->db->join('tb_attribute_group as E','D.id_attribute_group = E.id_attribute_group','left');		
		$this->db->join('tb_warehouse as F','A.id_warehouse = F.id_warehouse','left');
		$this->db->join('tb_warehouse_location as G','A.id_warehouse_location = G.id_warehouse_location','left');
		$this->db->join('tb_category as H','B.id_parent_category = H.id_category','left');
		$this->db->join('tb_branch as I','F.id_branch = I.id_branch','left');		
		$this->db->join('tb_product_image as J','A.id_product = J.id_product','left');
		$this->db->where('J.sort',1);
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
		$sql = $this->db->select('A.name as name_product,
								 B.id_stock_available,B.qty_available,B.qty_default,
								 B.qty_sold,C.name_location,D.name_warehouse')
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
		$this->db->select('A.id_stock_movement,A.id_product,A.code_move,A.qty_move,A.date_add,A.add_by,A.id_order,
						   B.name_warehouse,C.name_location,D.name_movement,D.label,E.name_label,G.name_group,H.name');
		$this->db->select('I.name_branch,J.name as product_name');
		$this->db->select('K.image_name');
		$this->db->from('tb_stock_movement as A');
		$this->db->join('tb_warehouse as B','A.id_warehouse = B.id_warehouse','left');
		$this->db->join('tb_warehouse_location as C','A.id_warehouse_location = C.id_warehouse_location','left');
		$this->db->join('tb_label_movement_detail as E','A.code_move = E.move_code','left');
		$this->db->join('tb_label_movement as D','E.id_label_movement = D.id_label_movement','left');		
		$this->db->join('tb_product_attribute as F','A.id_product_attribute = F.id_product_attribute','left');
		$this->db->join('tb_attribute_group as G','F.id_attribute_group = G.id_attribute_group','left');
		$this->db->join('tb_attribute as H','F.id_attribute = H.id_attribute','left');
		$this->db->join('tb_branch as I','B.id_branch = I.id_branch','left');
		$this->db->join('tb_product as J','A.id_product = J.id_product','left');
		$this->db->join('tb_product_image as K','A.id_product = K.id_product','left');
		$this->db->where('K.sort',1);
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
		$this->db->select('A.*,B.name as gender,B.criteria,C.name_received,
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
						   C.id_address,C.alias_name,C.postcode,C.address,C.phone_addr,C.id_districts,C.company,C.default,C.country_code,
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
		$this->db->select('A.*,B.name as name_product,C.price_impact,D.name,E.name_group as attr_group,F.symbol,G.name_group,H.first_name,H.last_name,H.email');
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
	function get_po($where,$detail=''){
		if ($detail == true){
			$this->db->select('F.id_purchase_order_det,F.id_product,F.id_product_attribute,F.id_currency as currency_det,F.unit_qty,F.unit_price,F.total_unit_price,F.description_det,
							   N.name as name_product,L.name as name_attribute,M.name_group');
			$this->db->join('tb_purchase_order_det as F','A.id_purchase_order = F.id_purchase_order','left');
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
		$this->db->from('tb_purchase_order as A');
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
	function get_banner($where='',$column=''){
		$this->db->select('A.*,B.name_position');
		$this->db->from('tb_banner as A');
		$this->db->join('tb_banner_position as B','A.id_banner_position = B.id_banner_position','left');
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
	function get_promotion($where='',$column=''){
		$this->db->select('A.*,B.name_language');
		$this->db->from('tb_promotion as A');
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
	function get_payment_method($where='',$column=''){
		$this->db->select('A.*,B.pay_code,B.name_type');
		$this->db->from('tb_payment_method as A');
		$this->db->join('tb_payment_type as B','A.id_payment_type = B.id_payment_type','left');
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
	function get_statuses($where='',$column=''){
		$this->db->select('A.*,B.label_color');
		$this->db->from('tb_order_status as A');
		$this->db->join('tb_label_color as B','A.id_label_color = B.id_label_color','left');
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
	function get_order($where='',$column=''){
		$this->db->select('A.*,B.*');
		$this->db->select('C.id_address,C.name_received,C.address,C.company,C.alias_name,C.postcode,C.phone_addr');
		$this->db->select('D.name as name_courier,D.tracking_url,D.image,D.delay,D.is_free,');
		$this->db->select('E.iso_code,E.symbol');
		$this->db->select('F.method_name,F.method_code,F.name_owner,F.content,F.logo,F.address as address_pay,F.description');
		$this->db->select('G.name_language,G.flag');
		$this->db->select('H.districts_name,I.id_cities,I.cities_name,J.id_province,J.province_name,K.id_country,K.country_name');
		$this->db->select('L.pay_code,M.name_status,N.label_color');
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
		$this->db->join('tb_country as K','J.id_country = K.id_country','left');
		$this->db->join('tb_payment_type as L','F.id_payment_type = L.id_payment_type','left');
		$this->db->join('tb_order_status as M','A.code_status_order = M.code_status','left');
		$this->db->join('tb_label_color as N','M.id_label_color = N.id_label_color','left');
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
	function maintenance(){
		if (MAINTENANCE == true){
			redirect(MODULE.'/maintenance/index');
		}
	}
	function get_helper($where='',$column=''){
		$this->db->select('A.*,B.name_language');
		$this->db->from('tb_helper as A');
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
	function get_helper_detail($where='',$column=''){
		$this->db->select('A.*,B.name_helper,B.id_language,C.name_language');
		$this->db->from('tb_helper_detail as A');
		$this->db->join('tb_helper as B','A.id_helper = B.id_helper','left');
		$this->db->join('tb_language as C','B.id_language = C.id_language','left');
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
	function get_privacy_policy($where='',$column=''){
		$this->db->select('A.*,B.name_language');
		$this->db->from('tb_privacy_policy as A');
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
	function get_term_condition($where='',$column=''){
		$this->db->select('A.*,B.name_language');
		$this->db->from('tb_term_conditions as A');
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
	function get_status_log($where=''){
		$this->db->select('A.date_add_status,A.m_date_add_status,A.add_by,A.notes,B.name_status,C.label_color');
		$this->db->from('tb_order_history as A');
		$this->db->join('tb_order_status as B','A.code_status = B.code_status','left');
		$this->db->join('tb_label_color as C','B.id_label_color = C.id_label_color','left');
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('A.id_incr','desc');
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
	function get_employee_broadcast($where=''){
		$this->db->select('*');		
		$this->db->from('tb_email_broadcast');					
		($where !='') ? $this->db->where($where) : '';			
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
	function get_order_indent(){
		$this->db->select('A.*,B.id_branch,C.first_name,C.last_name,C.email,E.name,G.name_group,H.name as name_attribute');
		$this->db->from('tb_stock_indent as A');
		$this->db->join('tb_stock_indent_notify as X','A.id_stock_indent = X.id_stock_indent','left');
		$this->db->join('tb_order as B','A.id_order = B.id_order','left');
		$this->db->join('tb_customer as C','B.id_customer = C.id_customer','left');		
		$this->db->join('tb_product as E','A.id_product = E.id_product','left');
		$this->db->join('tb_product_attribute as F','A.id_product_attribute = F.id_product_attribute','left');
		$this->db->join('tb_attribute_group as G','F.id_attribute_group = G.id_attribute_group','left');
		$this->db->join('tb_attribute as H','F.id_attribute = H.id_attribute','left');
		$this->db->where('X.id_employee',$this->id_employee);
		$this->db->where('X.stat_read',0);
		$this->db->order_by('A.date_add','desc');
		return $this->db->get()->result();
	}
	function get_order_notif(){
		$this->db->select('A.id_order,A.date_add_order,A.total_qty,A.total_payment,A.iso_code,C.first_name,C.last_name,D.symbol');
		$this->db->from('tb_order as A');
		$this->db->join('tb_order_notify as B','A.id_order = B.id_order','left');
		$this->db->join('tb_customer as C','A.id_customer = C.id_customer','left');
		$this->db->join('tb_currency as D','A.id_currency = D.id_currency','left');
		$this->db->where('B.id_employee',$this->id_employee);
		$this->db->where('B.stat_read',0);
		$this->db->order_by('A.date_add_order','desc');
		return $this->db->get()->result();		
	}
	function get_customer_notif(){
		$this->db->select('A.id_customer,A.first_name,A.last_name,A.date_add,C.criteria');
		$this->db->from('tb_customer as A');
		$this->db->join('tb_customer_notify as B','A.id_customer = B.id_customer','left');		
		$this->db->join('tb_gender as C','A.id_gender = C.id_gender','left');
		$this->db->where('B.id_employee',$this->id_employee);
		$this->db->where('B.stat_read',0);
		$this->db->order_by('A.date_add','desc');
		return $this->db->get()->result();
	}
	function get_payment_notif(){
		$this->db->select('A.id_order,A.total_pay,A.status_pay,A.payment_method,A.iso_code,A.date_add_pay,
						  D.first_name,D.last_name,E.symbol');
		$this->db->from('tb_order_pay as A');		
		$this->db->join('tb_order_pay_notify as B','A.id_order_pay = B.id_order_pay','left');
		$this->db->join('tb_order as C','A.id_order = C.id_order','left');
		$this->db->join('tb_customer as D','C.id_customer = D.id_customer','left');
		$this->db->join('tb_currency as E','C.id_currency = E.id_currency','left');
		$this->db->where('B.id_employee',$this->id_employee);
		$this->db->where('B.stat_read',0);
		$this->db->order_by('A.date_add_pay','desc');
		return $this->db->get()->result();
	}
	function get_confirm_notif(){
		$this->db->select('A.*,C.iso_code,C.symbol,D.name_method_transfer');						 
		$this->db->from('tb_payment_confirm as A');
		$this->db->join('tb_payment_confirm_notify as B','A.id_payment_confirm = B.id_payment_confirm','left');		
		$this->db->join('tb_currency as C','A.id_currency = C.id_currency','left');
		$this->db->join('tb_method_transfer as D','A.id_method_transfer = D.id_method_transfer','left');
		$this->db->where('B.id_employee',$this->id_employee);
		$this->db->where('B.stat_read',0);
		$this->db->order_by('A.date_add','desc');
		return $this->db->get()->result();
	}
	function get_data_payment($where='',$column=''){
		$this->db->select('A.*,C.first_name,C.last_name,C.email,D.symbol');
		$this->db->from('tb_order_pay as A');
		$this->db->join('tb_order as B','A.id_order = B.id_order','left');
		$this->db->join('tb_customer as C','B.id_customer = C.id_customer','left');
		$this->db->join('tb_currency as D','B.id_currency = D.id_currency','left');
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
	function get_data_confirm($where='',$column=''){
		$this->db->select('A.*,B.method_name,C.name_method_transfer,D.name_status,E.iso_code,E.symbol,F.label_color');
		$this->db->from('tb_payment_confirm as A');
		$this->db->join('tb_payment_method as B','A.id_payment_method = B.id_payment_method','left');
		$this->db->join('tb_method_transfer as C','A.id_method_transfer = C.id_method_transfer','left');
		$this->db->join('tb_order_status as D','A.status_code = D.code_status','left');
		$this->db->join('tb_currency as E','A.id_currency = E.id_currency','left');
		$this->db->join('tb_label_color as F','D.id_label_color = F.id_label_color','left');
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
	function stock_reduction($where='',$column=''){
		$this->db->select('A.*,C.first_name,C.last_name,C.email,
						   E.name,G.name_group,H.name as name_attribute');
		$this->db->select('I.image_name');
		$this->db->from('tb_stock_reduction as A');
		$this->db->join('tb_order as B','A.id_order = B.id_order','left');
		$this->db->join('tb_customer as C','B.id_customer = C.id_customer','left');		
		$this->db->join('tb_product as E','A.id_product = E.id_product','left');
		$this->db->join('tb_product_attribute as F','A.id_product_attribute = F.id_product_attribute','left');
		$this->db->join('tb_attribute_group as G','F.id_attribute_group = G.id_attribute_group','left');
		$this->db->join('tb_attribute as H','F.id_attribute = H.id_attribute','left');
		$this->db->join('tb_product_image as I','A.id_product = I.id_product','left');
		$this->db->where('I.sort',1);
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
	function get_qty_indent($where){
		$this->db->select('qty_minus,qty_available_last,qty_buy_now');
		$this->db->from('tb_stock_indent');
		$this->db->where($where);
		$sql = $this->db->get()->result();
		return $sql;
	}
	function stock_indent($where='',$column=''){
		$this->db->select('A.*,B.id_branch,C.first_name,C.last_name,C.email,E.name,G.name_group,H.name as name_attribute');
		$this->db->select('I.image_name');
		$this->db->from('tb_stock_indent as A');
		$this->db->join('tb_order as B','A.id_order = B.id_order','left');
		$this->db->join('tb_customer as C','B.id_customer = C.id_customer','left');		
		$this->db->join('tb_product as E','A.id_product = E.id_product','left');
		$this->db->join('tb_product_attribute as F','A.id_product_attribute = F.id_product_attribute','left');
		$this->db->join('tb_attribute_group as G','F.id_attribute_group = G.id_attribute_group','left');
		$this->db->join('tb_attribute as H','F.id_attribute = H.id_attribute','left');
		$this->db->join('tb_product_image as I','A.id_product = I.id_product','left');
		$this->db->where('I.sort',1);
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
	function get_qty_available_attribute($where)
	{
		
		//mendapatkan stock available sesuai branch walau beda lokasi
		$sql = $this->db->select	('A.id_stock_available,sum(A.qty_available) as qty_available,A.qty_sold,
									 B.id_branch')
										 ->from		('tb_stock_available as A')
										 ->join		('tb_warehouse as B','A.id_warehouse = B.id_warehouse','left')										 
										 ->where($where)
										 ->where	('A.deleted',0)
										 ->group_by	('A.id_product_attribute')
										 ->group_by	('A.id_product')
										 ->group_by	('B.id_branch')
										 ->get();
		if ($sql->num_rows() > 0)
		{
			foreach ($sql->result() as $row)
			{
				$qty = $row->qty_available;
			}
		}
		else
		{
			$qty = 'N/A';
		}
	
		return $qty;
	
	}
	function get_global_indent($where='',$column=''){
		$this->db->select('A.id_product, A.id_product_attribute,
						   sum(A.qty_minus) as total_request,sum(A.qty_buy_now) as total_order,
						   sum(A.qty_available_last) as stock_last,
						   B.name,						  
						   D.name as name_attribute,E.name_group');
		$this->db->select('F.image_name');
		$this->db->from('tb_stock_indent as A');
		$this->db->join('tb_product as B','A.id_product = B.id_product','left');
		$this->db->join('tb_product_attribute as C','A.id_product_attribute = C.id_product_attribute','left');
		$this->db->join('tb_attribute as D','C.id_attribute = D.id_attribute','left');
		$this->db->join('tb_attribute_group as E','C.id_attribute_group = E.id_attribute_group','left');
		$this->db->join('tb_product_image as F','A.id_product = F.id_product','left');
		$this->db->where('F.sort',1);
		$this->db->where('A.deleted',0);
		$this->db->where('A.status',0);//hanya menampilkan dan menhitung status yang indent
		$this->db->group_by('A.id_product,A.id_product_attribute');
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
	function get_global_stock($where){
		$sql = $this->db->select	('sum(qty_available) as stock')
						->from		('tb_stock_available')
						->where 	($where)						
						->where		('deleted',0)
						->group_by	('id_product')
						->group_by	('id_product_attribute')
						->get()->result();
		return $sql[0]->stock;
	}
	function roll_back_stock($id_order,$id_branch){
		$this->db->trans_start();
		$wh = $this->get_warehouse_bybranch($id_branch);	
		$result = false;
		$id_warehouse_location_aray = array('');
		$id_warehouse_aray = array('');
		foreach ($wh as $w)
		{
			$id_warehouse_aray[] = $w->id_warehouse;
			$id_warehouse_location_aray[] = $w->id_warehouse_location;
		}
		//get order detail
		$sql = $this->db->select('id_product,id_product_attribute,product_qty,preorder')
						->from('tb_order_detail')
						->where('id_order',$id_order)
						->get()->result();
		if (count($sql) > 0){
			foreach ($sql as $row){
				$where = array('id_product'=>$row->id_product,'id_product_attribute'=>$row->id_product_attribute);
				$status_indent = $row->preorder;
				//jika status order masih indent			
				if ($status_indent > 0){
					//jika ada order yang indent
					$ind = $this->db->select('qty_minus,qty_available_last,qty_buy_now')
									->from('tb_stock_indent')
									->where($where)									
									->where('deleted',0)
									->get()->result();
					if (count($ind) > 0){	
						$qty = $ind[0]->qty_available_last;	//qty stock sebelumnya
						if ($qty > 0){
							//jika ada stock yang dibooking pada saat ingin indent sisa barang yang tidak tersedia
							/*
							 * ex : stock_ready = 10
							* qty_buy =15
							* qty_indent = 5
							* qty_booking = 10 //rollback
							* qty_ready = 0
							*/
											
							$result = true;							
						}
						//update status cancel di stock_indent
						$res = $this->editdata('tb_stock_indent', array('status'=>2),array('id_order'=>$id_order));						
					}
				}else{
					//jika tidak ada order indent
					$qty = $row->product_qty;					
					$result=true;
				}			
					
				if ($result == true){
					//melakukan cek apakah barang ada di tb_stock_available
					$cekstock = $this->db->select('*')->from('tb_stock_available')
									->where($where)
									->where_in('id_warehouse',$id_warehouse_aray)
									->where_in('id_warehouse_location',$id_warehouse_location_aray)
									->get()->result();
					if (count($cekstock) > 0){
						foreach ($cekstock as $x){
							$this->db->set('qty_sold','qty_sold-'.$qty,false);
							$this->db->set('qty_available','qty_available+'.$qty,false);
							$this->db->where($where);//aray 1 dimensi
							$this->db->where('id_warehouse',$x->id_warehouse);//aray 1 dimensi
							$this->db->where('id_warehouse_location',$x->id_warehouse_location);//aray 1 dimensi
							$res = $this->db->update('tb_stock_available');
								
							//input log tb_order_rollback
							$inputrollback['id_order'] = $id_order;
							$inputrollback['id_product'] = $row->id_product;
							$inputrollback['id_product_attribute'] = $row->id_product_attribute;
							$inputrollback['qty_sold'] = $qty;
							$inputrollback['qty_rollback'] = $qty;
							$inputrollback['date_cancel'] = $this->datenow;
							$inputrollback['date_rollback'] = $this->datenow;
							$inputrollback['id_branch'] = $id_branch;
							$inputrollback['id_warehouse'] = $x->id_warehouse;
							$inputrollback['id_warehouse_location'] = $x->id_warehouse_location;
							$inputrollback['status'] = 1;//rolback done complete
							$res = $this->insertdata('tb_order_rollback',$inputrollback);
							
							//deklare insert web_stock_movement
							$insertmove['id_product'] = $row->id_product;
							$insertmove['id_order'] = $id_order;
							$insertmove['id_product_attribute'] = $row->id_product_attribute;
							$insertmove['code_move'] = 'RESTOCK';//Pengembalian stock / Order Cancel
							$insertmove['date_add'] = $this->datenow;
							$insertmove['add_by'] = $this->addby;
							$insertmove['id_employee'] = $this->id_employee;
							$insertmove['qty_move'] = $qty;//qty yang dimove
							$insertmove['id_warehouse'] = $x->id_warehouse;
							$insertmove['id_warehouse_location'] = $x->id_warehouse_location;
							$res = $this->m_client->insertdata('tb_stock_movement',$insertmove);
						}
						
					}
					
				}
				
			}
			if ($this->db->trans_status() == FALSE){
				$this->db->trans_rollback();
			}else{
				$this->db->trans_complete();
				return $res;
			}
		}
	}
	function get_warehouse_bybranch($id_branch)
	{
		$sql = $this->db->select('A.id_warehouse,B.id_warehouse_location')
						->from	('tb_warehouse as A')
						->join	('tb_warehouse_location as B','A.id_warehouse = B.id_warehouse','left')
						->where	('A.id_branch',$id_branch)
						->where	('A.deleted',0)
						->get()->result();
	
		return $sql;
	
	}
	function stock_rollback($where='',$column=''){
		$this->db->select('A.*,C.first_name,C.last_name,C.email,
						   E.name,G.name_group,H.name as name_attribute');
		$this->db->select('I.name_branch,J.name_warehouse,K.name_location,L.image_name');
		$this->db->from('tb_order_rollback as A');
		$this->db->join('tb_order as B','A.id_order = B.id_order','left');
		$this->db->join('tb_customer as C','B.id_customer = C.id_customer','left');		
		$this->db->join('tb_product as E','A.id_product = E.id_product','left');
		$this->db->join('tb_product_attribute as F','A.id_product_attribute = F.id_product_attribute','left');
		$this->db->join('tb_attribute_group as G','F.id_attribute_group = G.id_attribute_group','left');
		$this->db->join('tb_attribute as H','F.id_attribute = H.id_attribute','left');
		$this->db->join('tb_branch as I','A.id_branch = I.id_branch','left');
		$this->db->join('tb_warehouse as J','A.id_warehouse = J.id_warehouse','left');
		$this->db->join('tb_warehouse_location as K','A.id_warehouse_location = K.id_warehouse_location','left');
		$this->db->join('tb_product_image as L','A.id_product = L.id_product','left');
		$this->db->where('L.sort',1);
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
	function email_access($code){
		$sql = $this->db->select('B.first_name,B.last_name')
						->from('tb_email_broadcast as A')
						->join('tb_employee as B','A.id_employee = B.id_employee','left')
						->where('A.email_modul_code',$code)
						->where('B.deleted',0)
						->get()->result();
		
		if (count($sql) > 0){
			foreach ($sql as $row){
				$data[] = $row->first_name;
			}
		}else{
			$data = array('N/A');
		}
		return implode(", ", array_filter($data));
	}
	
	function get_stock_indent($where){
		$sql = $this->db->select('*')
						->from('tb_stock_indent')
						->where($where)
						->where('status',0)//masih indent
						->get()->result();
		return $sql;
	}	
	function id_country_def(){
		$sql = $this->db->select('id_country')->from('tb_country')->where('default',1)->where('deleted',0)->get()->result();
		return isset($sql[0]->id_country) ? $sql[0]->id_country : 0;
	}
}
