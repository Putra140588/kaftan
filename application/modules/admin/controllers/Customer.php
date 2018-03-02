<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Customer extends CI_Controller{
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
		$this->table = 'tb_customer';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'CUSTM';
		$this->id_employee = $this->session->userdata('id_employee');
		$this->m_admin->maintenance();
	}	
	function index($select=''){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		if (!empty($select)){
			$this->m_admin->editdata('tb_customer_notify',array('stat_read'=>1),array('id_employee'=>$this->id_employee));
		}
		$body= (empty($priv)) ? $this->class.'/vw_customer' : $priv['error'];
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
			0 => 'A.date_add',
			1 => 'A.id_customer',
			2 => 'B.name',
			3 => 'A.first_name',
			4 => 'A.email',
			5 => 'I.name_group',
			6 => 'A.phone',
			7 => 'C.address',
			8 => 'A.active',	
			9 => 'A.approval',
			10 => 'A.add_by',
			11 => 'A.date_add'						
		);
		return $field_array;
	}
	function get_query($field5,$total){
		$where = array('C.default'=>1);//default address
		//jika search by name group		
		if (!empty($field5)){
			if ($total == true){
				$sql = count($this->m_admin->get_customer_group($where));
			}else{
				$sql = $this->m_admin->get_customer_group($where,$this->column());				
			}
		}else{
			if ($total == true){
				$sql = count($this->m_admin->get_customer($where));
			}else{
				$sql = $this->m_admin->get_customer($where,$this->column());
			}
		}
		return $sql;
	}
	function get_records(){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$field5 = $_REQUEST['columns'][5]['search']['value'];
		$total = $this->get_query($field5,true);
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][11]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->get_query($field5, false);
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = $this->get_query($field5,true);
		$output['recordsFiltered'] = $total;		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-warning" href="'.base_url(MODULE.'/'.$this->class.'/view/'.$row->id_customer).'" title="View" data-rel="tooltip" data-placement="top">'.icon_action('view').'</a>
						<a class="btn btn-xs btn-success" href="#modal-form" data-toggle="modal" role="button" title="Add New Address" onclick="ajaxModal(\''.base_url(MODULE.'/'.$this->class.'/form_address/add').'\',\''.$row->id_customer.'\',\'modal-form\')"><i class="glyphicon glyphicon-plus"></i></a>
						<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/form/'.$row->id_customer).'" title="Edit" data-rel="tooltip" data-placement="top">'.icon_action('edit').'</a>
						<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/delete').'\',\''.$row->id_customer.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a>';
			$check = ($row->active == 1) ? 'checked' : '';
			$active = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_customer.'\',this)">
							<span class="lbl"></span>
						</label>';
			$address ='N/A';
			if ($row->address != ''){
				if ($row->country_code == 'ID'){
					$address = $row->alias_name.': <br>'.$row->name_received.'<br>'.$row->phone_addr.'<br>
					   		  '.$row->address.'<br>'.$row->districts_name.', '.$row->cities_name.', '.$row->province_name.'<br>'.$row->postcode.', '.$row->country_name;
				}else{
					$address = $row->alias_name.': <br>'.$row->name_received.'<br>'.$row->phone_addr.'<br>
					   		  '.$row->address.'<br>'.$row->postcode.', '.$row->country_name;
				}
			
			}
			$gr = array();
			$group = $this->m_admin->get_name_group($row->id_customer);
			foreach ($group as $g){
				$gr[] = $g->name_group;
			}
			$name_group = implode(", ", $gr);
			$approve = ($row->approval == 1) ? '<span class="label label-info arrowed-in-right arrowed">Approved</span>' : '<span class="label label-warning arrowed-in">Pending</span>';
			$output['data'][] = array(
					$no,
					$row->id_customer,
					$row->gender,
					$row->first_name.' '.$row->last_name,
					$row->email,
					$name_group,
					$row->phone,
					$address,	
					$active,
					$approve,
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
		$data['id_form'] = 'form-ajax';
		if (!empty($id)){
			links(MODULE.'/'.$this->class.'/form/'.$id);
			$action = 'edit';
			$data['page_title'] = 'Edit '.__CLASS__;
			$where = array('A.id_customer'=>$id,'C.default'=>1);
			$sql = $this->m_admin->get_customer($where);			
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}
			//menampilkan customer group
			$sq = $this->db->get_where('tb_customer_group',array('id_customer'=>$id))->result();
			foreach($sq as $s =>$x)
				$data['idgroup'][] = $x->id_group;			
			$data['rowaddress'] =$this->m_admin->get_customer(array('C.id_customer'=>$id));
		}else{
			links(MODULE.'/'.$this->class.'/form/');
			$data['page_title'] = 'Add New '.__CLASS__;
			$action = 'add';
			$data['country_code']='';
		}
		$priv = $this->m_admin->get_priv($this->access_code,$action);
		$body= (empty($priv)) ? $this->class.'/vw_crud' : $priv['error'];	
		$data['gender'] = $this->m_admin->get_table('tb_gender','*',array('deleted'=>0));	
		$data['group'] = $this->m_admin->get_table('tb_group',array('id_group','name_group'),array('deleted'=>0));
		$data['country'] = $this->m_admin->get_table('tb_country',array('id_country','country_name','country_code'),array('deleted'=>0));
		$data['body'] = $body;
		$data['class'] = $this->class; 		
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];		
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();	
		$id = $this->input->post('id_customer',true);		
		$pref = 'B'.date('Hi');
		$id_customer= (!empty($id)) ? $id : $this->m_admin->get_rand_id($pref);
		
		/*
		 * define tb_customer
		 */
		$email = $this->input->post('email',true);
		$encode  = md5($email.date('YmdHis'));//for token aktivasi konfirm in email
		$post['id_employee']= $this->id_employee;
		$post['id_gender']=$this->input->post('gender',true);
		$post['first_name'] = $this->input->post('firstname',true);
		$post['last_name'] = $this->input->post('lastname',true);		
		$post['email']= $email;
		$birtdate = $this->input->post('birthdate',true);
		$post['birthdate']= (!empty($birtdate)) ? date('Y-m-d',strtotime($birtdate)) : '';
		$post['active']= (empty($this->input->post('active',true))) ? 0 : 1;
		$post['phone']=$this->input->post('phone',true);
		$post['encode'] = $encode;
		
		$password = $this->input->post('password');
		$photo=$_FILES['photo']['name'];
		$group = $this->input->post('group');
		if ($photo != ""){
			$set['upload_path'] = './assets/fo/images/profile/';
			$set['file_name'] = $photo;
			$set['var_name']='photo';
			$this->m_admin->upload_image($set);
			$post['photo'] = str_replace(" ", "_", $photo);
		}
		foreach ($group as $ids){
			$batch_group[] = array_merge(array('id_customer'=>$id_customer),array('id_group'=>$ids));
		}
		
		$post['update_by']=$this->addby;		
		$post['date_update']=$this->datenow;
		
		/*
		 * define tb_customer_address
		 */
		
		$idaddr = $this->input->post('id_address');
		$id_address= (!empty($idaddr)) ? $idaddr : $this->m_admin->get_rand_id(date('Hi'));
		$ex = explode("#", $this->input->post('country',true));
		$id_country = isset($ex[0]) ? $ex[0] : '';
		$country_code = isset($ex[1]) ? $ex[1] : '';
		$id_districts = ($this->input->post('districts',true) != '') ? $this->input->post('districts',true) : 0;
		$postadd['id_country'] = $id_country;
		$postadd['country_code'] = $country_code;
		$postadd['id_districts'] = $id_districts;
		$postadd['name_received'] = $this->input->post('namereceived',true);
		$postadd['address'] = replace_freetext($this->input->post('address',true));
		$postadd['company'] = $this->input->post('company',true);
		$postadd['alias_name'] = $this->input->post('alias',true);
		$postadd['phone_addr'] = $this->input->post('phoneaddr',true);
		$postadd['postcode'] = $this->input->post('postcode',true);
		$postadd['update_by']= $this->addby;
		$postadd['date_update']=$this->datenow;
		
		if (!empty($id)){		
			//edit data
			if ($password != ""){
				$post['password'] = hash_password($password);
			}	
			$em = $this->get_email_exist($id_customer);
			foreach ($em as $v){
				$m = $v->email;
				if ($m == $email){
					//jika email masih sama dengan didatabase
					$execute = true;
				}else{
					
					//jika email diganti tidak sama dengan database, maka cek kembali apakah sudah ada email yg mau diganti
					if ($this->cek_email($email) == false){		
						//jika tidak ada, maka update email
						$execute = true;
					}else{
						//jika email sudah ada, tidak dapat update email 
						$alert = 'Email already exist,please use other email';	
						$execute = false;				
						$res = 0;
					}
				}
				
				if ($execute == true){
					$res = $this->m_admin->editdata($this->table,$post,array('id_customer'=>$id_customer));
					$res = $this->m_admin->editdata('tb_customer_address',$postadd,array('id_address'=>$id_address));
					$res = $this->m_admin->deletedata('tb_customer_group',array('id_customer'=>$id_customer));
						
					//insert batch customer group
					if (!empty($group)){
						$res = $this->db->insert_batch('tb_customer_group',$batch_group);
					}
					$alert = 'Edit data customer successfull';
				}
			}
			
		}else{
			//addnew customer
			if ($this->cek_email($email) == false){				
				$post['id_customer'] = $id_customer;
				$post['password']= hash_password($password);;
				$post['date_add']=$this->datenow;
				$post['add_by']=$this->addby;
				$post['approval']=1;
				$post['approval_by'] = $this->addby;
				$res = $this->m_admin->insertdata($this->table,$post);
				
				//insert customer address
				$postadd['id_address'] = $id_address;
				$postadd['id_customer'] = $id_customer;
				$postadd['default'] = 1;
				$postadd['date_add']=$this->datenow;
				$postadd['add_by']=$this->addby;
				$res = $this->m_admin->insertdata('tb_customer_address',$postadd);
				
				//insert batch customer group
				if (!empty($group)){
					$res = $this->db->insert_batch('tb_customer_group',$batch_group);
				}
				
				/*
				 * insert customer new notify to employees
				*/
				$res = $this->m_admin->insert_notify('tb_customer_notify',array('id_customer'=>$id_customer));
				$alert = 'Add new data customer successfull';
			}else{
				$alert = 'Email already exist,please use other email';
				$res = 0;
			}
			
		}
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_complete();
			if ($res > 0){				
				$this->session->set_flashdata('success',$alert);
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'save','redirect'=>base_url($this->session->userdata('links'))));
			}else{
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$alert,'type'=>'error'));
			}
		}
		
	}
	function active(){
		$id = $this->input->post('value',true);
		$check = $this->input->post('check',true);
		$priv = $this->m_admin->get_priv($this->access_code,'edit');
		if (empty($priv)){
			$res = $this->m_admin->editdata($this->table,array('active'=>$check),array('id_customer'=>$id));
			if ($res){
				$msg = ($check == 1) ? 'Edit status '.__CLASS__.' to be active successfull' : 'Edit status '.__CLASS__.' to be not active succesfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg));
			}
		}else{			
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}		
	}
	
	function delete(){
		$priv = $this->m_admin->get_priv($this->access_code,'delete');
		if (empty($priv)){
			$id = $this->input->post('value',true);
			$res = $this->m_admin->editdata($this->table,array('deleted'=>1),array('id_customer'=>$id));
			if ($res > 0){
				$msg = 'Delete data '.__CLASS__.' successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'dt'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function delete_address(){
		$priv = $this->m_admin->get_priv($this->access_code,'delete');
		if (empty($priv)){
			$id = $this->input->post('value',true);
			$res = $this->m_admin->deletedata('tb_customer_address',array('id_address'=>$id));
			if ($res > 0){
				$msg = 'Delete data address successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'df'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function show_province($id_form,$id_province=''){
		$res='';
		$ex = explode("#", $this->input->post('value',true));
		$id = isset($ex[0]) ? $ex[0] : '';
		$country_code = isset($ex[1]) ? $ex[1] : '';
		$content_id = 'districtarea'.$id_form;
		$content='';
		if ($country_code == 'ID'){
			//jika memilih negara indonesia maka akan ditampilkan wilayah,kecamatan
			$data['id_country'] = $id;
			$data['id_province'] = $id_province;
			$data['class'] = $this->class;
			$data['id_form'] = $id_form;
			$content .=$this->load->view($this->class.'/vw_districtarea',$data,true);
			$content .=$this->m_content->load_choosen();
		}		
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'districtarea'=>true,'content'=>$content,'contentid'=>$content_id));
	}
	function show_city($id_form){
		$res='';		
		$id = $this->input->post('value',true);
		$res .=$this->m_content->chosen_city($id,$id_cities='',$this->class,$id_form);
		$res .=$this->m_content->load_choosen();
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$res));
	}
	function show_districts(){
		$res='';		
		$id = $this->input->post('value',true);
		$res .=$this->m_content->chosen_districts($id,$id_districts='');
		$res .=$this->m_content->load_choosen();
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$res));
	}
	function cek_email($email){
		$qr = $this->m_admin->get_table($this->table,'email',array('email'=>$email,'deleted'=>0));
		if (count($qr) > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_email_exist($id){
		$qr = $this->m_admin->get_table($this->table,'email',array('id_customer'=>$id,'deleted'=>0));
		return $qr;
	}
	function form_address($action){
		$modal="";		
		$id = $this->input->post('value',true);
		$data['class'] = $this->class;		
		$data['id_form'] = 'formmodal';
		if ($action == 'edit'){
			$sql = $this->m_admin->get_customer(array('C.id_address'=>$id));
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}
			$data['page_title']='Edit Address';
		}else{
			$data['id_customer'] = $id;
			$data['page_title']='Add New Address';
			$data['country_code']='';
		}
		$data['country'] = $this->m_admin->get_table('tb_country',array('id_country','country_name','country_code'),array('deleted'=>0));
		$modal .=$this->load->view($this->class.'/vw_modal_address',$data,true);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'modal'=>$modal));
	}
	function save_address(){
		/*
		 * define tb_customer_address
		*/	
		$content="";
		$id_customer = $this->input->post('id_customer',true);
		$idaddr = $this->input->post('id_address');
		$id_address= (!empty($idaddr)) ? $idaddr : $this->m_admin->get_rand_id(date('Hi'));
		$ex = explode("#", $this->input->post('country',true));
		$id_country = isset($ex[0]) ? $ex[0] : '';
		$country_code = isset($ex[1]) ? $ex[1] : '';
		$id_districts = ($this->input->post('districts',true) != '') ? $this->input->post('districts',true) : 0;
			
		$postadd['id_country'] = $id_country;
		$postadd['country_code'] = $country_code;
		$postadd['id_districts'] = $id_districts;
		$postadd['name_received'] = $this->input->post('namereceived',true);
		$postadd['address'] = replace_freetext($this->input->post('address',true));
		$postadd['company'] = $this->input->post('company',true);
		$postadd['alias_name'] = $this->input->post('alias',true);
		$postadd['phone_addr'] = $this->input->post('phoneaddr',true);
		$postadd['postcode'] = $this->input->post('postcode',true);
		$postadd['update_by']= $this->addby;
		$postadd['date_update']=$this->datenow;
		
		if (!empty($idaddr)){
			$res = $this->m_admin->editdata('tb_customer_address',$postadd,array('id_address'=>$id_address));
			$alert = 'Edit address successfull!';
			$data['rowaddress'] =$this->m_admin->get_customer(array('C.id_customer'=>$id_customer));
			$content .= $this->load->view($this->class.'/vw_table_address',$data,true);
		}else{
			//insert customer address
			$postadd['id_address'] = $id_address;
			$postadd['id_customer'] = $id_customer;
			$postadd['date_add']=$this->datenow;
			$postadd['add_by']=$this->addby;
			$res = $this->m_admin->insertdata('tb_customer_address',$postadd);
			$alert = 'Save new address successfull!';
		}		
		if ($res > 0){			
			$this->session->set_flashdata('success',$alert);
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'modal','content'=>$content));
		}
	}
	function defaults($id_customer){
		$expl = explode("#",$this->input->post('value',true));
		$id = $expl[0];
		$obj = $expl[1];
		$check = $this->input->post('check',true);
		$priv = $this->m_admin->get_priv($this->access_code,'edit');
		if (empty($priv)){
			$res = $this->m_admin->editdata('tb_customer_address',array($obj=>0),array('id_customer'=>$id_customer));
			$res = $this->m_admin->editdata('tb_customer_address',array($obj=>$check),array('id_address'=>$id));
			if ($res){
				$msg = ($check == 1) ? 'Edit status '.__CLASS__.' to be '.$obj.' successfull' : 'Edit status '.__CLASS__.' to be not '.$obj.' succesfull';
				$this->session->set_flashdata('success',$msg);
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'defaults'=>true,'error'=>0,'redirect'=>base_url($this->session->userdata('links')),'msg'=>$msg));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function view($id='',$select=''){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		if (!empty($select)){
			$this->m_admin->editdata('tb_customer_notify',array('stat_read'=>1),array('id_customer'=>$id,'id_employee'=>$this->id_employee));
		}
		$body= (empty($priv)) ? $this->class.'/vw_customer_detail' : $priv['error'];
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];
		links(MODULE.'/'.$this->class);
		url_sess(base_url(MODULE.'/'.$this->class));//link for menu active
		$sql = $this->m_admin->get_customer(array('C.id_customer'=>$id,'C.default'=>1));
		foreach ($sql as $row)
			foreach ($row as $key=>$val){
			$data[$key] = $val;
		}
		$gr = array();
		$group = $this->m_admin->get_name_group($id);
		foreach ($group as $g){
			$gr[] = $g->name_group;
		}
		$data['name_group'] = implode(", ", $gr);
		$data['rowaddress'] =$this->m_admin->get_customer(array('C.id_customer'=>$id));
		$data['page_title'] = 'Data '.__CLASS__;
		$data['body'] = $body;
		$data['class'] = $this->class;
		$this->load->view('vw_header',$data);
	}
}
