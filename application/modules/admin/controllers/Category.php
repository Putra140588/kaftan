<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Category extends CI_Controller{
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
		$this->table = 'tb_category';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'CATE';	
		$this->sess_link = $this->session->userdata('links');
		$this->m_admin->maintenance();
	}	
	function index($url=''){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_category' : $priv['error'];
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];
		links(MODULE.'/'.$this->class);
		url_sess(base_url(MODULE.'/'.$this->class));//link for menu active
		$data['url'] = (empty($url)) ? base_url(MODULE.'/'.$this->class.'/get_records') : $url;
		$data['header_page'] = (empty($url)) ? 'vw_header_index' : 'vw_header_form';
		$data['page_title'] = 'Data '.__CLASS__;
		$data['body'] = $body;
		$data['class'] = $this->class;		
		$this->load->view('vw_header',$data);
	}
	function column(){
		$field_array = array(
			0 => 'A.date_add',
			1 => 'A.image',
			2 => 'A.name_category',
			3 => 'A.description',
			4 => 'B.name_language',
			5 => 'A.active',
			6 => 'A.display',
			7 => 'A.add_by',
			8 => 'A.date_add'							
		); 
		return $field_array;
	}
	
	function get_records($id=''){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$where = (empty($id)) ? array('id_level'=>0) : array('id_parent'=>$id);
		$total = count($this->m_admin->get_category($where));
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][8]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_category($where,$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_category($where));
		$output['recordsFiltered'] = $total;		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-warning" href="'.base_url(MODULE.'/'.$this->class.'/form/view/'.$row->id_category).'" title="View Detail" data-rel="tooltip" data-placement="top">'.icon_action('view').'</a>
						<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/form/edit/'.$row->id_category).'" title="Edit" data-rel="tooltip" data-placement="top">'.icon_action('edit').'</a>
						<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/delete').'\',\''.$row->id_category.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a>';			
			$check_act = ($row->active == 1) ? 'checked' : '';
			$active = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check_act.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_category.'#active'.'\',this)">
							<span class="lbl"></span>
						</label>';
			$check_dspl = ($row->display == 1) ? 'checked' : '';
			$display = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check_dspl.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_category.'#display'.'\',this)">
							<span class="lbl"></span>
						</label>';
			$banner = '<img src="'.base_url('assets/images/category/'.image($row->image)).'" alt="'.$row->image.'" style="width:70px">';
			$name_category = $this->name_category($row->id_parent);
			$parent = (!empty($name_category)) ? $name_category.' <i class="fa fa-arrow-right"></i> ' : '';
			$output['data'][] = array(
					$no,
					$banner,
					$parent.$row->name_category,
					$row->description,		
					$row->name_language,	
					$active,
					$display,				
					$row->add_by,			
					long_date_time($row->date_add),						
					$actions
			);
			$no++;
		}
		echo json_encode($output);
	}
	function form($action='',$id=""){
		$this->m_admin->sess_login();
		links(MODULE.'/'.$this->class.'/form/'.$action.'/'.$id);
		if ($action == 'view'){								
			$url = base_url(MODULE.'/'.$this->class.'/get_records/'.$id);
			$this->index($url);
			return false;
		}
		elseif ($action == 'edit')
		{						
			$data['page_title'] = 'Edit '.__CLASS__;
			$sql = $this->m_admin->get_category(array('id_category'=>$id));			
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}			
		}else{
			links(MODULE.'/'.$this->class.'/form/');
			$data['page_title'] = 'Add New '.__CLASS__;
			$action = 'add';
		}
		$data['language'] = $this->m_admin->get_table('tb_language',array('id_language','name_language'),array('deleted'=>0));
		$priv = $this->m_admin->get_priv($this->access_code,$action);
		$body= (empty($priv)) ? $this->class.'/vw_crud' : $priv['error'];
		$data['body'] = $body;
		$data['class'] = $this->class; 		
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];					
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();	
		$id_category = $this->input->post('id_category',true);
		$code = $this->input->post('code',true);		
		$post['name_category']= $this->input->post('name',true);	
		$post['id_parent']= $this->input->post('parent',true);
		$post['id_level']= $this->input->post('level',true);
		$post['description']= replace_desc($this->input->post('desc',true));
		$post['active']= (empty($this->input->post('active',true))) ? 0 : 1;
		$post['display']= (empty($this->input->post('display',true))) ? 0 : 1;		
		$post['page_link']= $this->input->post('pagelink',true);
		$post['page_btn']= $this->input->post('buttonname',true);
		$post['page_description']= replace_desc($this->input->post('pagedesc',true));
		$post['meta_title']= $this->input->post('metatitle',true);
		$post['meta_description']= replace_desc($this->input->post('metadesc',true));
		$post['meta_keywords']= $this->input->post('metakeyword',true);
		$post['url']= $this->input->post('friendlyurl',true);
		$post['date_update']= $this->datenow;
		$post['update_by']= $this->addby;
		$post['id_language']= $this->input->post('language',true);
		$banner = $_FILES['banner']['name'];
		
		if ($banner != ""){
			$set['upload_path'] = './assets/images/category/';
			$set['file_name'] = $banner;
			$set['var_name']='banner';
			$this->m_admin->upload_image($set);
			$post['image'] = $banner;
		}
		if (!empty($id_category)){			
			$res = $this->m_admin->editdata($this->table,$post,array('id_category'=>$id_category));
			$alert = 'Edit data '.__CLASS__.' successfull';
		}else{
			//addnew				
			$post['date_add']=$this->datenow;
			$post['add_by']=$this->addby;
			$cek_url = $this->m_admin->get_category(array('url'=>$this->input->post('friendlyurl',true)));
			if (count($cek_url) > 0){			
				$alert = 'URL Friendly already exist, pleas input another!';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>$alert));
				return false;
			}else{
				$res = $this->m_admin->insertdata($this->table,$post);
				$alert = 'Add new data '.__CLASS__.' successfull';
			}
				
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
			$res = $this->m_admin->editdata($this->table,array('deleted'=>1),array('id_category'=>$id));
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
			$res = $this->m_admin->editdata($this->table,array($obj=>$check),array('id_category'=>$id));
			if ($res){
				$msg = ($check == 1) ? 'Edit status '.__CLASS__.' to be '.$obj.' successfull' : 'Edit status '.__CLASS__.' to be not '.$obj.' succesfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function name_category($id){
		$sql = $this->m_admin->get_table($this->table,'name_category',array('id_category'=>$id));
		return isset($sql[0]->name_category) ? $sql[0]->name_category : '';
	}
	function generate_url(){
		$result='';
		$name = $this->input->post('value',true);
		if (!empty($name)){			
			$result.= generate_url($name);
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$result));
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>'Category name not input!'));
		}	
	}
}
