<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Manufacture extends CI_Controller{
	var $class;
	var $table;
	var $datenow;
	var $addby;
	var $access_code;
	public function __construct(){
		parent::__construct();
		$this->load->model('m_content');
		$this->load->model('m_admin');
		$this->class = strtolower(__CLASS__);
		$this->table = 'tb_manufacture';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'MNFT';
		$this->m_admin->maintenance();
	}	
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_manufacture' : $priv['error'];
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
			1 => 'A.name',
			2 => 'A.image_banner',
			3 => 'A.description',
			4 => 'B.name_language',
			5 => 'A.active',
			6 => 'A.displays',
			7 => 'A.add_by',
			8 => 'A.date_add',
						
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_manufacture());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][8]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_manufacture('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_manufacture());
		$output['recordsFiltered'] = $total;
		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/form/'.$row->id_manufacture).'" title="Edit" data-rel="tooltip" data-placement="top">'.icon_action('edit').'</a>
						<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/delete').'\',\''.$row->id_manufacture.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a>';
			$check_act = ($row->active == 1) ? 'checked' : '';
			$active = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check_act.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_manufacture.'#active'.'\',this)">
							<span class="lbl"></span>
						</label>';
			$check_dspl = ($row->displays == 1) ? 'checked' : '';
			$display = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check_dspl.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_manufacture.'#displays'.'\',this)">
							<span class="lbl"></span>
						</label>';
			$img_banner = ($row->image_banner != "") ? $row->image_banner : 'no-image.jpg';
			$banner = '<img src="'.base_url('assets/images/manufacture/'.$img_banner).'" style="width:50px">';
			$output['data'][] = array(
					$no,
					$row->name,
					$banner,
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
	function form($id=""){
		$this->m_admin->sess_login();
		if (!empty($id)){
			links(MODULE.'/'.$this->class.'/form/'.$id);
			$action = 'edit';
			$data['page_title'] = 'Edit '.__CLASS__;
			$sql = $this->m_admin->get_table($this->table,'*',array('id_manufacture'=>$id));			
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}
		}else{
			links(MODULE.'/'.$this->class.'/form/');
			$data['page_title'] = 'Add New '.__CLASS__;
			$action = 'add';
		}		
		$priv = $this->m_admin->get_priv($this->access_code,$action);
		$body= (empty($priv)) ? $this->class.'/vw_crud' : $priv['error'];
		$data['id_form'] = 'form-ajax';
		$data['body'] = $body;
		$data['class'] = $this->class; 		
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];		
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();			
		$id_manufacture = $this->input->post('id_manufacture',true);
		$post['name']=$this->input->post('name',true);
		$post['description']= replace_desc($this->input->post('description',true));		
		$post['sort'] = $this->input->post('sort',true);			
		$post['active']= (empty($this->input->post('active',true))) ? 0 : 1;
		$post['displays']=(empty($this->input->post('display',true))) ? 0 : 1;		
		$post['meta_title'] = $this->input->post('metatitle',true);	
		$post['meta_description'] = $this->input->post('metadesc',true);
		$post['meta_keywords'] = $this->input->post('metakey',true);
		$post['url'] = $this->input->post('friendlyurl',true);
		$post['update_by']=$this->addby;		
		$post['date_update']=$this->datenow;
		$post['id_language']= $this->input->post('language',true);
		$image = $_FILES['image']['name'];				
		$logo = $image[0];
		$banner = $image[1];		
		if ($logo != "" || $banner != ""){			
			$set['upload_path'] = './assets/images/Manufacture/';
			$set['name_file'] = $image[0];
			$set['var_name']='logo';
			$this->m_admin->upload_multiple(count($image),$set,$_FILES['image']);
			if ($logo != ""){
				$post['image'] = str_replace(" ", "_", $logo);					
			}
			if ($banner != ""){
				$post['image_banner'] = str_replace(" ", "_", $banner);	
			}			
		}
		
		if (!empty($id_manufacture)){			
			$res = $this->m_admin->editdata($this->table,$post,array('id_manufacture'=>$id_manufacture));
			$alert = 'Edit data Manufacture successfull';
		}else{
			//addnew			
			$post['date_add']=$this->datenow;
			$post['add_by']=$this->addby;
			$cek_url = $this->m_admin->get_manufacture(array('url'=>$this->input->post('friendlyurl',true)));
			if (count($cek_url) > 0){
				$alert = 'URL Friendly already exist, pleas input another!';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'type'=>'error','msg'=>$alert));
				return false;
			}else{
				$res = $this->m_admin->insertdata($this->table,$post);
				$alert = 'Add new data Manufacture successfull';
			}
			
		}
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
			if ($res > 0){
				$this->db->trans_complete();
				$id_form = $this->input->post('id_form',true);
				if ($id_form != 'formmodal'){		
					$this->session->set_flashdata('success',$alert);
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'save','redirect'=>base_url($this->session->userdata('links'))));
				}else{
					$content="";
					$content  .= $this->m_content->chosen_manufacture($this->input->post('id_manu_add'));
					$content  .= $this->m_content->load_choosen();
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'modal','content'=>$content));
				}
			}
		}
		
	}
	function active(){
		$expl = explode("#",$this->input->post('value',true));
		$id = $expl[0];
		$obj = $expl[1];
		$check = $this->input->post('check',true);
		$priv = $this->m_admin->get_priv($this->access_code,'edit');
		if (empty($priv)){
			$res = $this->m_admin->editdata($this->table,array($obj=>$check),array('id_manufacture'=>$id));
			if ($res){
				$msg = ($check == 1) ? 'Edit status '.__CLASS__.' to be '.$obj.' successfull' : 'Edit status '.__CLASS__.' to be not '.$obj.' succesfull';
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
			$res = $this->m_admin->editdata($this->table,array('deleted'=>1),array('id_manufacture'=>$id));
			if ($res > 0){
				$msg = 'Delete data Manufacture successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'dt'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function generate_url(){
		$result='';
		$name = $this->input->post('value',true);
		if (!empty($name)){
			$result.= generate_url($name);
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$result));
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>'Manufacture Name not input!'));
		}
	
	}
}
