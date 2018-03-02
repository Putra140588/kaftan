<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Courier extends CI_Controller{
	var $class;
	var $table;
	var $datenow;
	var $addby;
	var $access_code;	
	var $sess_link;
	public function __construct(){
		parent::__construct();
		$this->load->model('m_admin');
		$this->class = strtolower(__CLASS__);
		$this->table = 'tb_courier';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'CORIR';		
		$this->sess_link = $this->session->userdata('links');
		$this->m_admin->maintenance();
	}	
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_courier' : $priv['error'];
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];
		links(MODULE.'/'.$this->class);
		url_sess(base_url(MODULE.'/'.$this->class));//link for menu active
		$data['page_title'] = 'Data '.__CLASS__;
		$data['body'] = $body;
		$data['class'] = $this->class;		
		$this->load->view('vw_header',$data);
	}
	function column(){
		$field_array = array(
			0 => 'date_add',
			1 => 'name',
			2 => 'tracking_url',
			3 => 'delay',
			4 => 'is_free',		
			5 => 'fixed_cost',
			6 => 'active',
			7 => 'display',
			8 => 'default_courier',
			9 => 'add_by',
			10 => 'date_add',										
		); 
		return $field_array;
	}
	
	function get_records(){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_table_dt($this->table));
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][10]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_table_dt($this->table,'','',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_table_dt($this->table));
		$output['recordsFiltered'] = $total;		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/form/'.$row->id_courier).'" title="Edit" data-rel="tooltip" data-placement="top">'.icon_action('edit').'</a>
						<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/delete').'\',\''.$row->id_courier.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a>
						<a class="btn btn-xs btn-success" href="'.base_url(MODULE.'/'.$this->class.'/zone/'.$row->id_courier).'" title="Zone Coverage">'.icon_action('zone').'</a>
						<a class="btn btn-xs btn-warning" href="'.base_url(MODULE.'/'.$this->class.'/cost/'.$row->id_courier).'" title="Shipping Cost">'.icon_action('cost').'</a>';			
					
			$check_act = ($row->active == 1) ? 'checked' : '';
			$active = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check_act.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_courier.'#active'.'\',this)">
							<span class="lbl"></span>
						</label>';
			$check_dsp = ($row->display == 1) ? 'checked' : '';
			$display = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check_dsp.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_courier.'#display'.'\',this)">
							<span class="lbl"></span>
						</label>';
			$check_dfl = ($row->default_courier == 1) ? 'checked' : '';
			$disabled = ($row->default_courier == 1) ? 'disabled' : '';
			$default = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check_dfl.' '.$disabled.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/defaults').'\',\''.$row->id_courier.'#default_courier'.'\',this)">
							<span class="lbl"></span>
						</label>';
			$free_act = ($row->is_free == 1) ? 'checked' : '';
			$is_free = '<label>
							<input class="ace ace-switch ace-switch-2" '.$free_act.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_courier.'#is_free'.'\',this)">
							<span class="lbl"></span>
						</label>';
			
			$fixed_act = ($row->fixed_cost == 1) ? 'checked' : '';
			$is_fixed = '<label>
							<input class="ace ace-switch ace-switch-2" '.$fixed_act.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_courier.'#fixed_cost'.'\',this)">
							<span class="lbl"></span>
						</label>';
			$logo = '<img src="'.base_url('assets/images/courier/'.image($row->image)).'" alt="'.$row->image.'" width="45px;" height="45px">';
			$output['data'][] = array(
					$no,					
					$logo.' '.$row->name,
					$row->tracking_url,
					$row->delay,	
					$is_free,		
					$is_fixed,			
					$active,	
					$display,
					$default,
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
		if (!empty($id)){
			links(MODULE.'/'.$this->class.'/form/'.$id);
			$action = 'edit';
			$data['page_title'] = 'Edit '.__CLASS__;
			$sql = $this->m_admin->get_table($this->table,'',array('id_courier'=>$id));			
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}			
			//menampilkan customer group
			$sq = $this->db->get_where('tb_courier_payment',array('id_courier'=>$id))->result();
			foreach($sq as $s =>$x)
				$data['idpaymenttype'][] = $x->id_payment_type;
		}else{
			links(MODULE.'/'.$this->class.'/form/');
			$data['page_title'] = 'Add New '.__CLASS__;
			$action = 'add';
		}
		$data['payment'] = $this->m_admin->get_table('tb_payment_type',array('id_payment_type','name_type'),array('deleted'=>0));
		$priv = $this->m_admin->get_priv($this->access_code,$action);
		$body= (empty($priv)) ? $this->class.'/vw_crud' : $priv['error'];
		$data['body'] = $body;
		$data['class'] = $this->class; 		
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];					
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();	
		$id_courier = $this->input->post('id_courier',true);	
		$post['name']= $this->input->post('name',true);	
		$post['tracking_url']= $this->input->post('url',true);
		$post['delay']= $this->input->post('delay',true);
		$post['is_free']= (empty($this->input->post('isfree',true))) ? 0 : 1;	
		$post['fixed_cost']= (empty($this->input->post('fixed',true))) ? 0 : 1;
		$post['active']= (empty($this->input->post('active',true))) ? 0 : 1;
		$post['display']= (empty($this->input->post('display',true))) ? 0 : 1;
		$post['date_update']=$this->datenow;
		$post['update_by']=$this->addby;
		$default = (empty($this->input->post('default',true))) ? 0 : 1;
		$post['default_courier'] = $default;		
		$payment = $this->input->post('payment',true);
		foreach ($payment as $id){
			$batch[] = array_merge(array('id_courier'=>$id_courier),array('id_payment_type'=>$id));
		}
		$filename = $_FILES['logo']['name'];
		if ($filename != '')
		{
			//proses upload gambar
			$setting['upload_path'] = './assets/images/courier/';
			$setting['file_name'] = $filename;
			$setting['var_name'] = 'logo';
			$this->m_admin->upload_image($setting);
			$post['image'] = $filename;
		}
		if ($default == 1){
			$res = $this->m_admin->editdata($this->table,array('default_courier'=>0));			
		}
		if (!empty($id_courier)){			
			$res = $this->m_admin->editdata($this->table,$post,array('id_courier'=>$id_courier));
			$res = $this->m_admin->deletedata('tb_courier_payment',array('id_courier'=>$id_courier));
			$alert = 'Edit data '.__CLASS__.' successfull';
		}else{
			//addnew				
			$post['date_add']=$this->datenow;
			$post['add_by']=$this->addby;
			$res = $this->m_admin->insertdata($this->table,$post);
			$alert = 'Add new data '.__CLASS__.' successfull';	
						
		}
		//insert new payment type
		if (!empty($payment)){
			$res = $this->db->insert_batch('tb_courier_payment',$batch);
		}
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
			if ($res > 0){
				$this->db->trans_complete();
				$this->session->set_flashdata('success',$alert);
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'save','redirect'=>base_url($this->session->userdata('links'))));
			}
		}		
	}
	
	function delete(){
		$priv = $this->m_admin->get_priv($this->access_code,'delete');
		if (empty($priv)){
			$id = $this->input->post('value',true);
			$res = $this->m_admin->editdata($this->table,array('deleted'=>1),array('id_courier'=>$id));
			if ($res > 0){
				$msg = 'Delete data '.__CLASS__.' successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'dt'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function active(){
		$expl = explode("#",$this->input->post('value',true));
		$id = $expl[0];
		$obj = $expl[1];
		$check = $this->input->post('check',true);
		$priv = $this->m_admin->get_priv($this->access_code,'edit');
		if (empty($priv)){
			$res = $this->m_admin->editdata($this->table,array($obj=>$check),array('id_courier'=>$id));
			if ($res){
				$msg = ($check == 1) ? 'Edit status '.__CLASS__.' to be '.$obj.' successfull' : 'Edit status '.__CLASS__.' to be not '.$obj.' succesfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function defaults(){
		$expl = explode("#",$this->input->post('value',true));
		$id = $expl[0];		
		$obj = $expl[1];
		$check = $this->input->post('check',true);
		$priv = $this->m_admin->get_priv($this->access_code,'edit');
		if (empty($priv)){
			$res = $this->m_admin->editdata($this->table,array($obj=>0));
			$res = $this->m_admin->editdata($this->table,array($obj=>$check),array('id_courier'=>$id));
			if ($res){
				$msg = ($check == 1) ? 'Edit status '.__CLASS__.' to be '.$obj.' successfull' : 'Edit status '.__CLASS__.' to be not '.$obj.' succesfull';
				$this->session->set_flashdata('success',$msg);
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'defaults'=>true,'error'=>0,'redirect'=>base_url($this->session->userdata('links')),'msg'=>$msg));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function zone($id){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_courier_zone' : $priv['error'];
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];
		links(MODULE.'/'.$this->class.'/zone/'.$id);		
		$sql = $this->m_admin->get_table($this->table,'',array('id_courier'=>$id));
		foreach ($sql as $row)
			foreach ($row as $key=>$val){
			$data[$key] = $val;
		}
		$data['ct'] = $this->m_admin->get_country(array('A.default'=>1));			
		$data['page_title'] = 'Data '.__CLASS__;
		$data['sub_title'] = 'Zone Area &amp; Coverages';
		$data['body'] = $body;
		$data['class'] = $this->class;		
		$this->load->view('vw_header',$data);
	}
	function show_province($id_courier,$page){
		$res='';		
		$x = explode("#", $this->input->post('value',true));
		$id_country = isset($x[0]) ? $x[0] : 'null';		
		$country_code = isset($x[1]) ? $x[1] : 'null';
		$data['class'] = $this->class;
		$data['id_courier'] = $id_courier;
		if ($country_code == 'ID'){			
			//jika yang dipilih adalah indonesia
			$sql = $this->m_admin->get_table('tb_province',array('id_province','province_name'),array('id_country'=>$id_country,'deleted'=>0));
			$res .= load_chosen();
			$res .='<select class="chosen-select form-control" name="province" data-placeholder="Choose a province" onchange="ajaxcall(\''.base_url(MODULE.'/'.$this->class.'/show_city/'.$id_courier.'/'.$page).'\',this.value,\'cities\')">';
			$res .='<option value="" />';
			foreach ($sql as $row){
				$res .= '<option value="'.$row->id_province.'">'.$row->province_name.'</option>';
			}
			$res .='</select>';
			$content = $this->load->view('courier/vw_district_cover',$data,true);		
		}else{
			//jika selain indonesia			
			$content = $this->load->view('courier/vw_country_area',$data,true);			
		}		
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'shippzone'=>true,'content'=>$content,'element'=>$res));
	}
	function show_city($id_courier,$page){
		$res='';
		$id = $this->input->post('value',true);
		$sql = $this->m_admin->get_table('tb_cities',array('id_cities','cities_name'),array('id_province'=>$id,'deleted'=>0,'active'=>1));
		$res .= load_chosen();
		$res .='<select class="chosen-select form-control" name="city" data-placeholder="Choose a city" onchange="ajaxcall(\''.base_url(MODULE.'/'.$this->class.'/show_zone').'\',this.value,\'tbl1\')">';
		$res .='<option value="" />';
		foreach ($sql as $row){
			$res .= '<option value="'.$row->id_cities.'#'.$id_courier.'#'.$page.'">'.$row->cities_name.'</option>';
		}
		$res .='</select>';
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$res));
	}
	function show_zone(){
		$expl = explode('#', $this->input->post('value'));
		$data['id_cities'] = $expl[0];
		$data['id_courier'] = $expl[1];
		$data['p'] = $expl[2];
		$data['class'] = $this->class;
		$tbl1='';
		$tbl2 = '';
		if ($expl[2] == 'zone'){
			//halaman add coverage area
			$tbl1 .= $this->load->view($this->class.'/table_zone_area',$data,true);
			$tbl2 .= $this->load->view($this->class.'/table_zone_coverage',$data,true);
		}else{
			//halaman add dan edit shipping cost
			$tbl1 .= $this->load->view($this->class.'/table_cost_coverage',$data,true);
			$tbl2 .= $this->load->view($this->class.'/table_shipp_cost',$data,true);
		}
		echo json_encode(array(
				'csrf_token'=>csrf_token()['hash'],
				'error'=>0,
				'zone'=>true,
				'tbl1'=>$tbl1,
				'tbl2'=>$tbl2,				
		));	
	}	
	function col_country_area(){
		$field_array = array(
				0 => 'A.id_country',//default order sort
				1 => 'A.id_country',
				2 => 'A.id_country',
				3 => 'A.country_name',
		);
		return $field_array;
	}
	function col_zone_area(){
		$field_array = array(
				0 => 'A.id_districts',//default order sort
				1 => 'A.id_districts',
				2 => 'A.id_districts',
				3 => 'A.districts_name',
		);
		return $field_array;
	}
	function get_zone($id_cities,$id_courier,$z){
		$output = array();
		//load datatable
		$this->m_admin->datatable();
		if ($z == 0){
			//zone-area
			$where = array('A.id_cities'=>$id_cities);
		}else{
			//coverage
			$where = array('A.id_cities'=>$id_cities,'B.id_courier'=>$id_courier,'B.id_courier_zone !='=>'');
		}
		$total = count($this->m_admin->get_zone_coverage($where));		
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];
		$output['recordsTotal']= $output['recordsFiltered'] = $total;
		//date filter value already set index row column		
		$this->m_admin->range_date($this->col_zone_area());
		$query = $this->m_admin->get_zone_coverage($where,$this->col_zone_area());
		$this->m_admin->range_date($this->col_zone_area());
		$total = count($this->m_admin->get_zone_coverage($where));
		$output['recordsFiltered'] = $total;
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){			
			$output['data'][] = array(
					$no,
					'<center><label><input type="checkbox" class="ace ace-checkbox-2 chkrow'.$z.'" name="chk'.$z.'[]" value="'.$row->id_districts.'"><span class="lbl"></span></label></center>',
					$row->id_districts,
					$row->districts_name,		
					'<input type="text" name="data['.$row->id_districts.'][weight_first]" class="input-mini wght" placeholder="kg" id="wf'.$no.'" onkeypress="return decimals(event,this.id)">',
					'<input type="text" name="data['.$row->id_districts.'][price]" class="input-mini prce" placeholder="'.$_SESSION['symbol'].'" id="prc'.$no.'" onkeypress="return decimals(event,this.id)">'
			);
			$no++;
		}
		echo json_encode($output);
	}
	function get_country_area($id_courier,$z){
		$output = array();
		//load datatable
		$this->m_admin->datatable();
		if ($z == 0){
			//zone-area
			$where = '';
		}else{
			//menampilkan negara yang tercover
			$where = array('B.id_courier'=>$id_courier,'B.id_courier_zone !='=>'');
		}
		$total = count($this->m_admin->get_country_coverage($where));
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];
		$output['recordsTotal']= $output['recordsFiltered'] = $total;
		//date filter value already set index row column
		$this->m_admin->range_date($this->col_country_area());
		$query = $this->m_admin->get_country_coverage($where,$this->col_country_area());
		$this->m_admin->range_date($this->col_country_area());
		$total = count($this->m_admin->get_country_coverage($where));
		$output['recordsFiltered'] = $total;
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$output['data'][] = array(
					$no,
					'<center><label><input type="checkbox" class="ace ace-checkbox-2 chkrow'.$z.'" name="chk'.$z.'[]" value="'.$row->id_country.'"><span class="lbl"></span></label></center>',
					$row->id_country,
					$row->country_name,
					'<input type="text" name="data['.$row->id_country.'][weight_first]" class="input-mini wght" placeholder="kg" id="wf'.$no.'" onkeypress="return decimals(event,this.id)">',
					'<input type="text" name="data['.$row->id_country.'][price]" class="input-mini prce" placeholder="'.$_SESSION['symbol'].'" id="prc'.$no.'" onkeypress="return decimals(event,this.id)">'
			);
			$no++;
		}
		echo json_encode($output);
	}
	function proses_zone(){		
		$chk0 = $this->input->post('chk0');//zone-area
		$chk1 = $this->input->post('chk1');//coverage
		$expl = explode('#', $this->input->post('province'));
		$id_courier = $this->input->post('id_courier',true);	
		$x = explode("#", $this->input->post('areacountry',true));
		$id_country = isset($x[0]) ? $x[0] : '';
		$country_code = isset($x[1]) ? $x[1] : '';			
		
			$data_cost = $this->input->post('data');
			//jika row zone area dicek
			if (!empty($chk0)){
				//insert new covereage area
				/*
				 * jika pengiriman zone country indonesia
				 */
				if (!empty($this->input->post('province')) && !empty($this->input->post('city'))){
					foreach ($chk0 as $val){
						//input cost shipping
						$datax = $data_cost[$val];
						$post['id_courier'] = $id_courier;
						$post['country_code'] = $country_code;
						$post['id_country_from'] = $id_country;
						$post['id_country_to'] = $id_country;
						$post['id_districts_from'] = '10';//default from jagakarsa
						$post['id_districts_to'] = $val;												
						$post['id_currency'] = $_SESSION['id_currency'];
						$post['date_add'] = $this->datenow;
						$post['add_by'] = $this->addby;
						$post['date_update'] = $this->datenow;
						$post['update_by'] = $this->addby;
						$insertcost = array_merge($datax,$post);			
				
						$where = array('id_districts'=>$val,'id_courier'=>$id_courier,'id_country'=>$id_country,);
						$check = $this->db->get_where('tb_courier_zone',$where);
						//jika belum ada
						if ($check->num_rows() < 1){
							$insert[] = $where;
							$insert_cost[] = $insertcost;
							$msg=array();
						}else{
							$msg[] = 'Zone area coverage ID '.$val.' already exist!';
						}
					}
				}else{
					//define save pengiriman by country
					foreach ($chk0 as $val){
						$datax = $data_cost[$val];
						$post['country_code'] = $country_code;
						$post['id_courier'] = $id_courier;
						$post['id_country_from'] = $id_country;//get by country set default
						$post['id_country_to'] = $val;						
						$post['id_currency'] = $_SESSION['id_currency'];
						$post['date_add'] = $this->datenow;
						$post['add_by'] = $this->addby;
						$post['date_update'] = $this->datenow;
						$post['update_by'] = $this->addby;
						$insertcost = array_merge($datax,$post);
						
						$where = array('id_courier'=>$id_courier,'id_country'=>$val,);
						$check = $this->db->get_where('tb_courier_zone',$where);
						//jika belum ada
						if ($check->num_rows() < 1){
							$insert[] = $where;
							$insert_cost[] = $insertcost;
							$msg=array();
						}else{
							$msg[] = 'Zone area coverage ID '.$val.' already exist!';
						}
					}
				}
				
				$priv = $this->m_admin->get_priv($this->access_code,'add');
				if (empty($priv)){
					if (empty($msg)){
						$res = $this->db->insert_batch('tb_courier_zone',$insert);
						$res = $this->db->insert_batch('tb_shipping_cost',$insert_cost);
						if ($res > 0){
							$notif = 'Save zone area coverage successfull';
							$this->session->set_flashdata('success',$notif);
							echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'type'=>'save','msg'=>$notif,'redirect'=>base_url($this->sess_link)));
						}
					}else{
						echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error_loop','msg'=>$msg));
					}
				}else{
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>$priv['notif']));
				}
			}else if (!empty($chk1)){
				//jika row coverage area dicheck
				$priv = $this->m_admin->get_priv($this->access_code,'delete');
				if (empty($priv)){
					//delete coverage area indonesia
					if (!empty($this->input->post('province')) && !empty($this->input->post('city'))){
						foreach ($chk1 as $val){
							$where = array('id_districts'=>$val,'id_courier'=>$id_courier,'id_country'=>$id_country);						
							$this->db->where($where);
							$res = $this->db->delete('tb_courier_zone');
							//delete shipping cost
							$whered = array('id_districts_to'=>$val,'id_courier'=>$id_courier,'id_country_to'=>$id_country);
							$this->db->where($whered);
							$res = $this->db->delete('tb_shipping_cost');
						}
					}else{
						//delete coverage area internatioanal
						foreach ($chk1 as $val){
							$where = array('id_courier'=>$id_courier,'id_country'=>$val);
							$this->db->where($where);
							$res = $this->db->delete('tb_courier_zone');
							//delete shipping cost
							$whered = array('id_courier'=>$id_courier,'id_country_to'=>$val);
							$this->db->where($whered);
							$res = $this->db->delete('tb_shipping_cost');
						}
					}
					if ($res > 0){
						$notif = 'Delete zone coverage successfull';
						$this->session->set_flashdata('success',$notif);
						echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'type'=>'save','msg'=>$notif,'redirect'=>base_url($this->sess_link)));
					}
				}else{
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>$priv['notif']));
				}
			}else{
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>'Zone area not checked!'));
			}		
		
	}
	
	function cost($id){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_shipping_cost' : $priv['error'];
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];
		links(MODULE.'/'.$this->class.'/cost/'.$id);
		$sql = $this->m_admin->get_table($this->table,'',array('id_courier'=>$id));
		foreach ($sql as $row)
			foreach ($row as $key=>$val){
			$data[$key] = $val;
		}
		$data['country'] = $this->m_admin->get_table('tb_country','',array('deleted'=>0));
		$data['page_title'] = 'Data '.__CLASS__;
		$data['sub_title'] = 'Shipping Cost';
		$data['body'] = $body;
		$data['class'] = $this->class;
		$this->load->view('vw_header',$data);
	}
	
	function get_zone_cost($id_cities,$id_courier,$z){
		$output = array();
		//load datatable
		$this->m_admin->datatable();
		$where = array('A.id_cities'=>$id_cities,'B.id_courier'=>$id_courier,'B.id_courier_zone !='=>'');
		$total = count($this->m_admin->get_zone_coverage($where));
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];
		$output['recordsTotal']= $output['recordsFiltered'] = $total;
		//date filter value already set index row column
		$this->m_admin->range_date($this->col_zone_area());
		$query = $this->m_admin->get_zone_coverage($where,$this->col_zone_area());
		$this->m_admin->range_date($this->col_zone_area());
		$total = count($this->m_admin->get_zone_coverage($where));
		$output['recordsFiltered'] = $total;
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$output['data'][] = array(
					$no,
					'<center><label><input type="checkbox" class="ace ace-checkbox-2 chkrow'.$z.'" name="chkadd[]" value="'.$row->id_districts.'"><span class="lbl"></span></label></center>',
					$row->id_districts,
					$row->districts_name,
					'<input type="text" name="data['.$row->id_districts.'][weight_first]" class="input-mini wght" placeholder="kg" id="wf'.$no.'" onkeypress="return decimals(event,this.id)">',
					'<input type="text" name="data['.$row->id_districts.'][price]" class="input-mini prce" placeholder="'.$_SESSION['symbol'].'" id="prc'.$no.'" onkeypress="return decimals(event,this.id)">'
			);
			$no++;
		}
		echo json_encode($output);
	}
	function col_cost(){
	
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'B.id_shipping_cost',//default order sort
				1 => 'B.id_shipping_cost',//default order sort
				2 => 'B.id_districts_to',
				3 => 'A.districts_name',
				4 => 'B.weight',
				5 => 'B.price'
	
		);
		return $column_array;
	}
	function get_shipp_cost($id_cities,$id_courier,$z){
		$output = array();
		//load datatable
		$this->m_admin->datatable();
		$where = array('A.id_cities'=>$id_cities,'B.id_courier'=>$id_courier);
		$total = count($this->m_admin->get_shipping_cost($where));
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];
		$output['recordsTotal']= $output['recordsFiltered'] = $total;
		//date filter value already set index row column
		$this->m_admin->range_date($this->col_cost());
		$query = $this->m_admin->get_shipping_cost($where,$this->col_cost());
		$this->m_admin->range_date($this->col_cost());
		$total = count($this->m_admin->get_shipping_cost($where));
		$output['recordsFiltered'] = $total;
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$output['data'][] = array(
					$no,
					'<center><label><input type="checkbox" class="ace ace-checkbox-2 rowcost'.$z.'" name="chkedit[]" value="'.$row->id_shipping_cost.'"><span class="lbl"></span></label></center>',
					$row->id_districts_to,
					$row->districts_name,
					'<input type="text" name="datax['.$row->id_shipping_cost.'][weight_first]" class="input-mini costwght" placeholder="kg" id="cwf'.$no.'" value="'.$row->weight_first.'" onkeypress="return decimals(event,this.id)">',
					'<input type="text" name="datax['.$row->id_shipping_cost.'][price]" class="input-mini costprce" placeholder="'.$_SESSION['symbol'].'" id="cprc'.$no.'" value="'.$row->price.'" onkeypress="return decimals(event,this.id)">'
			);
			$no++;
		}
		echo json_encode($output);
	}
	
	function proses_cost(){
		$chkadd = $this->input->post('chkadd');	//check for add cost shipping
		$chkedit = $this->input->post('chkedit');//check for edit and delete cost shipping
		$data_add = $this->input->post('data');
		$data_edit = $this->input->post('datax');		
		$id_courier = $this->input->post('id_courier',true);	
		if (!empty($chkadd)){			
			//save add shipping cost
			foreach ($chkadd as $val){
				$datax = $data_add[$val];
				$where = array('id_districts_to'=>$val,'id_courier'=>$id_courier);
				$post['id_districts_from'] = '35';//default from jatinegara				
				$post['id_currency'] = $_SESSION['id_currency'];
				$post['date_add'] = $this->datenow;
				$post['add_by'] = $this->addby;
				$post['date_update'] = $this->datenow;
				$post['update_by'] = $this->addby;
				$post_merge = array_merge($datax,$where,$post);
				$check = $this->db->get_where('tb_shipping_cost',$where);
				//jika belum ada
				if ($check->num_rows() < 1){
					$insert[] = $post_merge;
					$msg=array();
				}else{
					//notif looping
					$msg[] = 'Zone Area ID '.$val.' already exist!';
				}
			}
			$priv = $this->m_admin->get_priv($this->access_code,'add');
			if (empty($priv)){
				//insert new shipp cost
				if (empty($msg)){
					$res = $this->db->insert_batch('tb_shipping_cost',$insert);
					if ($res > 0){
						$notif = 'Save add shipping cost successfull!';
						$this->session->set_flashdata('success',$notif);
						echo json_encode(array('error'=>0,'type'=>'save','msg'=>$notif,'redirect'=>base_url($this->sess_link)));
					}
				}else{
					echo json_encode(array('error'=>1,'type'=>'error_loop','msg'=>$msg));
				}
			}else{
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>$priv['notif']));
			}
		}else if (!empty($chkedit)){
			$priv = $this->m_admin->get_priv($this->access_code,'edit');
			if (empty($priv)){
				//edit shipping cost
				foreach ($chkedit as $val){
					$datax = $data_edit[$val];					
					$post['id_currency'] = $_SESSION['id_currency'];					
					$post['date_update'] = $this->datenow;
					$post['update_by'] = $this->addby;
					$post_merge = array_merge($datax,$post);
					$where = array('id_shipping_cost'=>$val);
					$res = $this->m_admin->editdata('tb_shipping_cost',$post_merge,$where);
				}
				if ($res > 0){
					$notif = 'Edit shipping cost successfull!';
					$this->session->set_flashdata('success',$notif);
					echo json_encode(array('error'=>0,'type'=>'save','msg'=>$notif,'redirect'=>base_url($this->sess_link)));
				}
			}else{
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>$priv['notif']));
			}
		}
		else{
			echo json_encode(array('error'=>1,'type'=>'error','msg'=>'Zone area not checked!'));
		}
	}
}
