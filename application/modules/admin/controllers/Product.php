<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Product extends CI_Controller{
	var $class;
	var $table;
	var $datenow;
	var $addby;
	var $access_code;
	var $sess_link;
	var $id_currency;
	var $id_employee;
	
	public function __construct(){
		parent::__construct();
		$this->load->model('m_admin');
		$this->load->model('m_content');
		$this->class = strtolower(__CLASS__);
		$this->table = 'tb_product';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'PROD';
		$this->sess_link = $this->session->userdata('links');
		$this->id_currency = $this->session->userdata('id_currency');
		$this->id_employee = $this->session->userdata('id_employee');
		$this->m_admin->maintenance();
	}	
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? $this->class.'/vw_product' : $priv['error'];
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
			1 => 'A.id_product',
			2 => 'A.name',
			3 => 'D.name_category',
			4 => 'B.name_supplier',
			5 => 'C.manufacture',
			6 => 'A.base_price',
			7 => 'E.rate',
			8 => 'A.final_price',
			9 => 'G.name_language',
			10 => 'A.active',
			11 => 'A.display',
			12 => 'A.add_by',
			13 => 'A.date_add'						
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_product());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][13]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_product('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_product());
		$output['recordsFiltered'] = $total;
		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/form/'.$row->id_product).'" title="Edit" data-rel="tooltip" data-placement="top">'.icon_action('edit').'</a>
						<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/'.$this->class.'/delete').'\',\''.$row->id_product.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a>';
			$check_act = ($row->active == 1) ? 'checked' : '';
			$active = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check_act.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_product.'#active'.'\',this)">
							<span class="lbl"></span>
						</label>';
			$check_dspl = ($row->display == 1) ? 'checked' : '';
			$display = '<label>
							<input class="ace ace-switch ace-switch-2" '.$check_dspl.' type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/active').'\',\''.$row->id_product.'#display'.'\',this)">
							<span class="lbl"></span>
						</label>';		
			
			$output['data'][] = array(
					$no,
					$row->id_product,
					image_product($row->image_name).' '.$row->name,
					$row->name_category,
					$row->name_supplier,
					$row->manufacture,
					$row->symbol.' '.$row->base_price,
					$row->rate,
					$row->symbol.' '.$row->final_price,	
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
		$this->session->unset_userdata('tab_category',true);
		$this->session->unset_userdata('tab_price');		
		$data['id_form'] = 'form-ajax';
		if (!empty($id)){
			links(MODULE.'/'.$this->class.'/form/'.$id);
			$action = 'edit';
			$data['page_title'] = 'Edit '.__CLASS__;
			$sql = $this->m_admin->get_product(array('A.id_product'=>$id));			
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
		$data['groupattr'] = $this->m_admin->get_table('tb_attribute_group','*',array('deleted'=>0));
		$data['group'] = $this->m_admin->get_table('tb_group',array('id_group','name_group'),array('deleted'=>0));
		$data['language'] = $this->m_admin->get_table('tb_language',array('id_language','name_language'),array('deleted'=>0));
		/*
		 * melakukan cek apakah sudah mempunyai stock pada prodcut yg tidak punya attribute
		 */				
		$data['cekstock'] = $this->m_admin->cek_stock_available(array('id_product'=>$id,'id_product_attribute'=>0));					   
		$data['stock'] = false;
		if (count($data['cekstock']) > 0){
			$data['stock'] = true;
		}
		
		//melakukan cek apakah sudah ada Specific Price
		$data['ceksp'] = $this->m_admin->get_specific_price(array('A.id_product'=>$id));	
		$data['listsp'] = false;
		if (count($data['ceksp']) > 0){
			$data['listsp'] = true;
		}		
		$data['body'] = $body;
		$data['class'] = $this->class; 		
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];				
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();			
		$id = $this->input->post('id_product',true);
		$id_product = (!empty($id)) ? $id : $this->m_admin->get_rand_id(date('Hi'));
		
		//information
		$post['id_employee'] = $this->id_employee;
		$post['name']=$this->input->post('name',true);
		$post['id_manufacture'] = $this->input->post('manufacture',true);
		$post['id_supplier'] = $this->input->post('supplier',true);		
		$post['product_information']= replace_desc($this->input->post('information',true));
		$post['description_product']= replace_desc($this->input->post('description',true));		
		$post['specification']= replace_desc($this->input->post('specification',true));
		$post['active']= (empty($this->input->post('active',true))) ? 0 : 1;						
		$post['display']=(empty($this->input->post('display',true))) ? 0 : 1;	
		$post['promo']=(empty($this->input->post('promo',true))) ? 0 : 1;
		$post['featured_product']=(empty($this->input->post('featured_product',true))) ? 0 : 1;
		$post['product_recomend']=(empty($this->input->post('product_recomend',true))) ? 0 : 1;
		$post['new_product']=(empty($this->input->post('new_product',true))) ? 0 : 1;
		$post['top_seller']=(empty($this->input->post('top_seller',true))) ? 0 : 1;
		$post['show_price']=(empty($this->input->post('show_price',true))) ? 0 : 1;
		$post['id_language']= $this->input->post('language',true);
		//category
		$post['id_parent_category'] = $this->input->post('parent',true);
		$post['id_category_level1'] = $this->input->post('level1',true);
		$post['id_category_level2'] = $this->input->post('level2',true);
		$post['id_category_level3'] = $this->input->post('level3',true);

		//price
		$post['id_currency'] = $this->id_currency;
		$tax = explode("#", $this->input->post('tax',true));
		$post['id_tax'] = $tax[0];
		$post['base_price'] = $this->input->post('baseprice',true);
		$post['final_price'] = $this->input->post('finalprice',true);
		
		//shipping
		$post['length'] = $this->input->post('length',true);
		$post['width'] = $this->input->post('width',true);
		$post['height'] = $this->input->post('height',true);
		$post['weight'] = $this->input->post('weight',true);
		
		//seo
		$post['meta_title'] = $this->input->post('meta_title',true);	
		$post['meta_description'] = $this->input->post('metadesc',true);
		$post['meta_keywords'] = $this->input->post('metakey',true);
		$post['permalink'] = $this->input->post('friendlyurl',true);
				
		$post['update_by']=$this->addby;		
		$post['date_update']=$this->datenow;
		
		//image
		$image = isset($_FILES['rowimage']['name']) ? $_FILES['rowimage']['name'] : '';
		$data = $this->input->post('data',true);					
		$chek = $this->input->post('checkimg',true);			
		//jika gambar dicheked
		if (!empty($chek)){
			foreach ($chek as $ck){
				$image_file = $image[$ck];
				//jika gambar dipilih maka akan diupload/dirubah
				if ($image_file != ""){
						$set['upload_path'] = './assets/images/product/';
						$set['name_file'] = $image_file;
						//$set['var_name']='rowimage';
						$this->m_admin->upload_image_rowmultiple($set,$_FILES['rowimage'],$ck);
						$postimg['image_name'] = str_replace(" ", "_", $image_file);
				}
										
				$datarow = $data[$ck];
				$sort = $datarow['sort'];
				$active = (!empty($datarow['active'])) ? 1 : 0;					
					
				$postimg['sort'] = $sort;
				$postimg['active'] = $active;
				$tbl = 'tb_product_image';
				if (!empty($id)){
					//jika melakukan edit
					$where = array('id_product_image'=>$ck,'id_product'=>$id_product);					
					$exist = $this->db->where($where)->get($tbl);
					//melakukan cek apakah image tersedia di table
					if ($exist->num_rows() > 0){
						//jika tersedia melakukan update
						$res = $this->m_admin->editdata($tbl,$postimg,$where);
					}else{
						//melakukan insert baru/tambah gambar
						$postimg['id_product'] = $id_product;
						$res = $this->m_admin->insertdata($tbl,$postimg);
					}
					
				}else{
					//jika melakukan new product
					//melakukan insert baru/tambah gambar
					$postimg['id_product'] = $id_product;
					$res = $this->m_admin->insertdata($tbl,$postimg);
				}
					
			}				
		}
		
		//attachment
		$chkattach = $this->input->post('checkatch',true);		
		$tbls = 'tb_product_attachment';
		$res = $this->m_admin->deletedata($tbls,array('id_product'=>$id_product));
		if (!empty($chkattach)){			
			foreach ($chkattach as $x){
				$in= array_merge(array('id_product'=>$id_product),array('id_attachment'=>$x));					
				$res = $this->m_admin->insertdata($tbls,$in);
			}
		}
		
		//video
		$chkvid = $this->input->post('checkvid',true);
		$datavid = $this->input->post('datavid',true);		
		if (!empty($chkvid)){
			foreach ($chkvid as $ckx){
				$xrow = $datavid[$ckx];
				$postvid['video_title'] = $xrow['video_title'];
				$postvid['video_url'] = $xrow['video_url'];
				$postvid['sort'] = $xrow['sort'];
				$postvid['active'] = (!empty($xrow['active'])) ? 1 : 0;
				$tbl = 'tb_product_video';
				if (!empty($id)){
					//jika melakukan edit
					$where = array('id_product_video'=>$ckx,'id_product'=>$id_product);					
					$exist = $this->db->where($where)->get($tbl);
					//melakukan cek apakah image tersedia di table
					if ($exist->num_rows() > 0){
						//jika tersedia melakukan update
						$res = $this->m_admin->editdata($tbl,$postvid,$where);
					}else{
						//melakukan insert baru/tambah gambar
						$postvid['id_product'] = $id_product;
						$res = $this->m_admin->insertdata($tbl,$postvid);
					}
				}
				else{
					//jika melakukan new video
					//melakukan insert baru/tambah video
					$postvid['id_product'] = $id_product;
					$res = $this->m_admin->insertdata($tbl,$postvid);
				}
			}
		}
		
		//product attribute
		$id_attribute_group = $this->input->post('groupattr',true);
		$chkattr = $this->input->post('checkattr',true);
		$dataattr = $this->input->post('dataattr',true);		
		if (!empty($chkattr)){			
			foreach ($chkattr as $ckx){				
				$trow = $dataattr[$ckx];
				$tactive = (!empty($trow['active'])) ? 1 : 0;
				$tdefault = (!empty($trow['default'])) ? 1 : 0;
				$tpost['id_product'] = $id_product;
				$tpost['id_attribute_group'] = $id_attribute_group;
				$tpost['id_currency'] = $this->id_currency;
				$tpost['date_add'] = $this->datenow;
				$tpost['add_by'] = $this->addby;
				$tpost['id_attribute'] = $ckx;
				$tpost['price_impact'] = $trow['price_impact'];
				$tpost['sort'] = $trow['sort'];
				$tpost['active'] = $tactive;	
				$tpost['default'] = $tdefault;
				$tpostmerge[] = $tpost;
			}
			$res = $this->db->insert_batch('tb_product_attribute',$tpostmerge);
		}
		
		if (!empty($id)){	
			//jika melakukan edit
			$res = $this->m_admin->editdata($this->table,$post,array('id_product'=>$id_product));
			$alert = 'Edit data product successfull';
		}else{
			//addnew			
			$post['id_product']= $id_product;
			$post['date_add']=$this->datenow;
			$post['add_by']=$this->addby;
			$res = $this->m_admin->insertdata($this->table,$post);
			$alert = 'Add new data product successfull';
		}
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_complete();
			if ($res > 0){				
				$this->session->set_flashdata('success',$alert);
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'save','redirect'=>base_url($this->session->userdata('links'))));
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
			$res = $this->m_admin->editdata($this->table,array($obj=>$check),array('id_product'=>$id));
			if ($res){
				$msg = ($check == 1) ? 'Edit status '.__CLASS__.' to be '.$obj.' successfull' : 'Edit status '.__CLASS__.' to be not '.$obj.' succesfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg));
			}
		}else{			
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}		
	}
	function active_attr(){
		$expl = explode("#",$this->input->post('value',true));
		$id = $expl[0];
		$obj = $expl[1];
		$check = $this->input->post('check',true);
		$priv = $this->m_admin->get_priv($this->access_code,'edit');
		if (empty($priv)){
			$res = $this->m_admin->editdata('tb_product_attribute',array($obj=>$check),array('id_product_attribute'=>$id));
			if ($res){
				$msg = ($check == 1) ? 'Edit status product attribute to be '.$obj.' successfull' : 'Edit status product attribute to be not '.$obj.' succesfull';
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
			$res = $this->m_admin->editdata($this->table,array('deleted'=>1),array('id_product'=>$id));
			if ($res > 0){
				$msg = 'Delete data product successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'dt'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function add_manufacture(){
		$modal="";
		$data['id_manu_add'] = $this->input->post('value',true);
		$data['page_title']='Add New Manufacture';		
		$data['id_form'] = 'formmodal';
		$data['class'] = $this->class;
		$modal .=$this->load->view($this->class.'/vw_modal_manufacture',$data,true);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'modal'=>$modal));
	}
	function add_supplier(){
		$modal="";
		$data['id_spl_add'] = $this->input->post('value',true);
		$data['page_title']='Add New Supplier';
		$data['id_form'] = 'formmodal';
		$data['class'] = $this->class;
		$modal .=$this->load->view($this->class.'/vw_modal_supplier',$data,true);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'modal'=>$modal));
	}	
	function chose_category(){		
		$element="";
		$id = $this->input->post('value',true);
		$div = $this->input->post('div');
		$element .= $this->m_content->load_choosen();
		$name='parent';
		if ($div == 'parent'){
			$name = 'level1';
		}elseif ($div == 'level1'){
			$name = 'level2';
		}elseif ($div == 'level2'){
			$name = 'level3';
		}
		$where = array('A.id_parent'=>$id);
		$element .=$this->m_content->chosen_category('',$where,$name);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$element));
	}
	function add_tax(){				
		$val = explode("#", $this->input->post('value'));
		$id_tax = isset($val[0]) ? $val[0] : '0';
		if ($id_tax == 0){
			$final_price = $val[1];
		}else{
			$rate = $val[1];
			$base_price = $val[2];
			$hitung = ($base_price * $rate) / 100;
			$final_price = $base_price + $hitung;
		}		
		
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$final_price));
	}
	function image_count($id=""){
		$element="";
		$val = $this->input->post('value',true);
		$element = $this->m_content->table_image($id,$val);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$element));
	}
	function video_count($id=""){
		$element="";
		$val = $this->input->post('value',true);
		$element = $this->m_content->table_video($id,$val);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$element));
	}	
	function show_attachment($id_product=''){
		$id_manufacture = $this->input->post('value',true);
		$element = $this->m_content->table_attachment($id_manufacture,$id_product);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$element));
	}
	function delete_image(){
		$priv = $this->m_admin->get_priv($this->access_code,'delete');
		if (empty($priv)){
			$id = $this->input->post('value',true);
			$res = $this->m_admin->editdata('tb_product_image',array('deleted'=>1),array('id_product_image'=>$id));
			if ($res > 0){
				$msg = 'Delete image product successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'df'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function delete_video(){
		$priv = $this->m_admin->get_priv($this->access_code,'delete');
		if (empty($priv)){
			$id = $this->input->post('value',true);
			$res = $this->m_admin->editdata('tb_product_video',array('deleted'=>1),array('id_product_video'=>$id));
			if ($res > 0){
				$msg = 'Delete product video successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'df'));
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
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>'Product name not input!'));
		}
		
	}
	function show_attribute($id_product=""){
		$id_attribute_group = $this->input->post('value',true);
		$element = $this->m_content->table_attribute($id_attribute_group,$id_product);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$element));
	}
	function change_sort($id){
		$val = $this->input->post('value',true);
		$res = $this->m_admin->editdata('tb_product_attribute',array('sort'=>$val),array('id_product_attribute'=>$id));
		if ($res){
			$msg = 'Edit sort product attribute successfull';
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'edit'=>true,'msg'=>$msg));
		}
	}
	function default_attr($id_product){
		$expl = explode("#",$this->input->post('value',true));
		$id = $expl[0];		
		$obj = $expl[1];
		$check = $this->input->post('check',true);
		$priv = $this->m_admin->get_priv($this->access_code,'edit');
		if (empty($priv)){
			$res = $this->m_admin->editdata('tb_product_attribute',array($obj=>0),array('id_product'=>$id_product));
			$res = $this->m_admin->editdata('tb_product_attribute',array($obj=>$check),array('id_product_attribute'=>$id));
			if ($res){
				$msg = ($check == 1) ? 'Edit status product attribute to be '.$obj.' successfull' : 'Edit status product attribute to be not '.$obj.' succesfull';
				$this->session->set_flashdata('success',$msg);
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'defaults'=>true,'error'=>0,'redirect'=>base_url($this->session->userdata('links')),'msg'=>$msg));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function delete_prod_attr(){
		$priv = $this->m_admin->get_priv($this->access_code,'delete');
		if (empty($priv)){
			$id = $this->input->post('value',true);
			$res = $this->m_admin->deletedata('tb_product_attribute',array('id_product_attribute'=>$id));
			$res = $this->m_admin->deletedata('tb_stock_available',array('id_product_attribute'=>$id));
			$res = $this->m_admin->deletedata('tb_specific_price',array('id_product_attribute'=>$id));
			if ($res > 0){
				$msg = 'Delete data product attribute and stock successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'df'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function delete_sp(){
		$priv = $this->m_admin->get_priv($this->access_code,'delete');
		if (empty($priv)){
			$id = $this->input->post('value',true);
			$res = $this->m_admin->deletedata('tb_specific_price',array('id_specific_price'=>$id));			
			if ($res > 0){
				$msg = 'Delete Specific Price successfull';
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'df'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
	function show_customer(){
		$element='';
		$id = $this->input->post('value',true);
		$element .=$this->m_content->chosen_customer($id);
		$element .=$this->m_content->load_choosen();
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$element));
	}
	function save_specific_price(){
		$this->db->trans_start();
		$post = $this->input->post();		
		//print_r($post);die;
		$id_product = $post['id_product'];
		$attribute = $post['attribute'];
		$id_group = isset($post['spgroup']) ? $post['spgroup'] : '';
		$id_customer = isset($post['customer']) ? $post['customer'] : '';
		if ($id_group != "" && $id_customer != ""){
			$posted['id_customer_sp'] = $id_customer;
			$posted['id_group_sp'] = $id_group;
			$posted['id_product'] = $id_product;
			$posted['date_add'] = $this->datenow;
			$posted['date_update'] = $this->datenow;
			$posted['update_by'] = $this->addby;
			$posted['add_by'] = $this->addby;
			$posted['id_currency'] = $this->id_currency;
			if ($attribute == 0){
				//jika tidak punya attribute
				$date_from = ($post['spfrom'] != '') ? date('Y-m-d',strtotime($post['spfrom'])) : '';
				$date_to = ($post['spto'] != '') ? date('Y-m-d',strtotime($post['spto'])) : '';
				$sp_prize = $post['specificprice'];
				$sp_disc = $post['specificdisc'];				
				if ($date_from != "" && $sp_prize != ""){
					$posted['price_sp'] = $post['specificprice'];
					$posted['disc_sp'] = $post['specificdisc'];
					$posted['date_from'] = $date_from;
					$posted['date_to'] = $date_to;					
					$res = $this->m_admin->insertdata('tb_specific_price',$posted);		
					$alert = 'Insert new specific price successfull';
				}else{
					$alert = 'Date From || Specific Price not input!';
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$alert,'type'=>'error'));
					return false;
				}
				if ($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
				}else{
					$this->db->trans_complete();
					if ($res > 0){
						$this->session->set_flashdata('success',$alert);
						echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'save','redirect'=>base_url($this->session->userdata('links'))));
					}
				}
			}else{
				//jika punya attribute
				$ceksp = $this->input->post('checsp');
				$datasp = $this->input->post('datasp');
				if (!empty($ceksp)){
					foreach ($ceksp as $row){
						$attr = $this->m_admin->get_product_attribute(array('A.id_product_attribute'=>$row));
						$dx = $datasp[$row];
						$date_from = ($dx['date_from'] != '') ? date('Y-m-d',strtotime($dx['date_from'])) : '';
						$date_to = ($dx['date_to'] != '') ? date('Y-m-d',strtotime($dx['date_to'])) : '';						
						$price_sp = $dx['price_sp'];
						$disc_sp = $dx['disc_sp'];
						if (!empty($date_from) && !empty($price_sp)){
							$posted['id_product_attribute'] = $row;
							$posted['price_sp'] = $price_sp;
							$posted['disc_sp'] = $disc_sp;
							$posted['date_from'] = $date_from;
							$posted['date_to'] = $date_to;
							$res = $this->m_admin->insertdata('tb_specific_price',$posted);
							$alert = 'Insert new specific price successfull';
							$error = array();
						}else{
							$error[] = 'Date From Or Specific price in Attribute '.$attr[0]->name_group.' '.$attr[0]->name.' No insert!';
						}
					}
					//result proses
					if (empty($error)){
						if ($this->db->trans_status() === false){
							$this->db->trans_rollback();
						}else{
							$this->db->trans_complete();
							if ($res > 0){
								//deklare insert stock move
								$this->session->set_flashdata('success',$alert);
								echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'save','redirect'=>base_url($this->session->userdata('links'))));
								return false;
							}
						}
							
					}else{
						echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$error,'type'=>'error_loop'));
						return false;
					}
				}else{
					$alert = 'Product Attribute not checked!';
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$alert,'type'=>'error'));
					return false;
				}				
			}
						
		}else{
			$alert = 'Group || Customer not choose!';
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$alert,'type'=>'error'));
		}
	}
	function edit_sp(){
		$modal="";
		$id = $this->input->post('value',true);
		$data['class'] = $this->class;
		$data['id_form'] = 'formmodal';
		$sql = $this->m_admin->get_specific_price(array('A.id_specific_price'=>$id));
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}
		$data['page_title']='Edit Specific Price';		
		$modal .=$this->load->view($this->class.'/vw_modal_sp',$data,true);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'modal'=>$modal));
	}
	function save_edit_sp(){
		$content="";
		$id_sp = $this->input->post('id_specific_price',true);	
		$id_product = $this->input->post('id_product',true);	
		$post['price_sp'] = $this->input->post('specificprice',true);
		$post['disc_sp'] = $this->input->post('specificdisc',true);
		$date_from = ($this->input->post('spfrom') != '') ? date('Y-m-d',strtotime($this->input->post('spfrom'))) : '';
		$date_to = ($this->input->post('spto') != '') ? date('Y-m-d',strtotime($this->input->post('spto'))) : '';
		$post['date_from'] = $date_from;
		$post['date_to'] = $date_to;
		$post['update_by']= $this->addby;
		$post['date_update']=$this->datenow;
		$post['id_currency'] = $this->id_currency;
		$res = $this->m_admin->editdata('tb_specific_price',$post,array('id_specific_price'=>$id_sp));
		$alert = 'Edit Specific Price successfull!';
		$sql = $this->m_admin->get_specific_price(array('A.id_product'=>$id_product));	
		$content .= $this->m_content->table_list_sp($sql);
		if ($res > 0){
			$this->session->set_flashdata('success',$alert);
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'modal','content'=>$content));
		}
	}
	function save_edit_proattribute(){
		$content="";
		$id_sp = $this->input->post('id_product_attribute',true);
		$id_product = $this->input->post('id_product',true);
		$post['price_impact'] = $this->input->post('impactprice',true);	
		$post['id_currency'] = $this->id_currency;
		$res = $this->m_admin->editdata('tb_product_attribute',$post,array('id_product_attribute'=>$id_sp));
		$alert = 'Edit Specific Price successfull!';		
		$content .= $this->m_content->table_product_attribute($id_product);
		if ($res > 0){
			$this->session->set_flashdata('success',$alert);
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$alert,'type'=>'modal','content'=>$content));
		}
	}
	function edit_proattribute(){
		$modal="";
		$id = $this->input->post('value',true);
		$data['class'] = $this->class;
		$data['id_form'] = 'formmodal';
		$sql = $this->m_admin->get_product_attribute(array('A.id_product_attribute'=>$id));
		foreach ($sql as $row)
			foreach ($row as $key=>$val){
			$data[$key] = $val;
		}
		$data['page_title']='Edit Product Attribute';
		$modal .=$this->load->view($this->class.'/vw_modal_proattribute',$data,true);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'modal'=>$modal));
	}
}
