<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class W extends CI_Controller{
	var $code;
	var $name;
	var $suff_blank;
	var $class;
	var $sess_permalink;
	var $symbol;
	public function __construct(){
		parent::__construct();				
		$this->load->model('m_client');
		$this->load->model('m_public');		
		$this->load->library('permalink');		
		$this->load->library('front_pagination');
		$this->suff = $this->config->config['suff_blank'];
		$this->class = strtolower(__CLASS__);
		$this->symbol = $this->session->userdata('symbol_fo');
		$this->m_public->maintenance();			
			
	}
	function index(){
		permalink_sess(FOMODULE);
		$data['tab_title'] = tab_title('home_title');
		$data['meta_keywords'] = tab_title('home_meta_keywords');
		$data['meta_description'] = tab_title('home_meta_desc');
		$data['class'] = $this->class;
		$data['recomend'] = $this->m_public->generate_product_slider(array('product_recomend'=>1));
		$data['top_seller'] = $this->m_public->generate_product_slider(array('top_seller'=>1));
		$data['brands'] = $this->m_client->get_manufacture();
		$data['promotion'] = $this->m_client->get_promotion();
		foreach ($this->m_public->product_footer_list() as $row=>$val)
			$data[$row] = $val;	
		$data['content'] = 'home/v_home_content';
		$this->load->view('w/v_main_body',$data);
	}
	function blank_page($row){
		$data='';		
		foreach ($row as $val){
			$data .='<h1>Halaman profile '.$val->first_name.'</h2><p>';
			$data .='email : '.$val->email;
		}
		return $data;		
	}
	function read($param,$offset=0){					
		if ($param == 'change_lang'){
			$this->change_lang($offset);
		}else if ($param == 'change_currency'){
			$this->change_currency($offset);
		}else if ($param == 'change_limit'){
			$this->change_limit($offset);
		}else if ($param == 'sort_by'){
			$this->sort_by();
		}else if ($param == 'help'){
			$this->help($param);
		}else if ($param == 'aboutus' || $param == 'privacy-policy' || $param == 'term-and-conditions'){
			$this->other_page($param);
		}else{
			$expl = explode("-", $param);
			$end = explode(".", end($expl));//memecah nilai paling akhir
			$code = $end[0];//code category,brands
			$ctg = isset($end[1]) ? $end[1] : null;//parent,level1,level2
			if ($code == 1 || $code == 2){
				//show category product or brand product
				$this->category($param,$code,$ctg,$offset);
			}else if ($code == 3){
				//show helper detail
				$this->help($param,$ctg);
			}else{
				$this->product($param);
			}
		}								
	}
	function change_lang($id=0){
		$this->session->set_userdata('sess_lang',true);
		$sql = $this->m_client->load_sess_language(array('id_language'=>$id));			
		$expl = explode("/",$this->session->userdata('permalink_sess'));
		/*
		 * jika mengganti bahasa pada halaman produk detail == 2, maka akan redirect ke halaman home
		 */
		if (count($expl) == 2){
			redirect(FOMODULE);
		}else{
			redirect($this->session->userdata('permalink_sess'));
		}				
	}
	function change_currency($id=0){
		$this->session->set_userdata('sess_currency',true);
		$this->session->unset_userdata('id_order_cart');
		$sql = $this->m_client->load_sess_currency(array('id_currency'=>$id));		
		redirect($this->session->userdata('permalink_sess'));
	}
	function product($param){		
		permalink_sess(FOMODULE.'/'.$param);
		$expl1 = explode("-", $param);		
		$expl2 = explode(".", end($expl1));
		if (isset($expl2[0]) && isset($expl2[1])){
			$id_product = $expl2[0];
			$id_category_level1 = $expl2[1];			
			$where = array('A.id_product <>'=>$id_product,'A.id_category_level1'=>$id_category_level1);
			$data['related'] = $this->m_public->generate_product_slider($where);
			$sql = $this->m_client->get_product(array('A.id_product'=>$id_product));
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}
			foreach ($this->m_public->product_footer_list() as $row=>$val)
				$data[$row] = $val;
			$data['image'] = $this->m_client->get_image(array('id_product'=>$id_product));
			/*
			 * get video product
			 */
			$this->db->order_by('sort','asc');
			$data['video'] = $this->m_client->get_table('tb_product_video',array('video_title','video_url'),array('id_product'=>$id_product,'active=>1','deleted'=>0));
			
			/*
			 * get attachment
			 */
			$data['attachment'] = $this->m_client->get_attachment(array('id_product'=>$id_product));
			
			//select attribute choose				
			$attrdefault = $this->m_client->get_product_attribute(array('A.id_product'=>$id_product,'A.default'=>1));	
			$data['id_attribute_group_default'] = isset($attrdefault[0]->id_attribute_group) ? $attrdefault[0]->id_attribute_group : '';
			$data['id_attribute_default'] = isset($attrdefault[0]->id_attribute) ? $attrdefault[0]->id_attribute : '';
			/*
			 * get price sp,attribute
			*/
			$x = $this->m_public->price_product($id_product, $sql[0]->final_price);
			$data['promocarosel'] = $x['promocarosel'];	
			$data['price_old'] = $x['price_old'];
			$data['price_total'] = $x['price_total'];
			$data['stok'] = $x['stok'];
			$data['btnorder'] = $x['btnorder'];
			
			$data['tab_title'] = $sql[0]->meta_title;
			$data['meta_image_name'] = 'product/'.$sql[0]->image_name;//fb shared	
			$data['class'] = $this->class;
			$data['content'] = 'product/v_product_content';
			$this->load->view('w/v_main_body',$data);
		}else{
			$this->m_public->error_page();
		}
	}
	function category($param,$code,$ctg,$offset,$sortby=''){				
		permalink_sess(FOMODULE.'/'.$param);				
		$url = implode("-", explode("-", $param,-1));// mengambil url dan mengilangkan param terakhir		
		//jika yg dipilih adalah produk category
		if ($code == 1){
			//get category
			$sql = $this->m_client->get_category(array('A.url'=>$url));
			$data['image_link'] = base_url('assets/images/category/'.image($sql[0]->image));			
			$data['name_page'] = $sql[0]->name_category;
			$data['name_parent'] = $sql[0]->name_parent;
			$data['page_description'] = $sql[0]->description;
			if ($ctg == 0){
				//menampilkan parent category
				$where = array('A.id_parent_category'=>$sql[0]->id_category);
			}elseif ($ctg == 1){
				//menampilkan category level1
				$where = array('A.id_category_level1'=>$sql[0]->id_category);
			}elseif ($ctg == 2){
				//menampilkan category level2
				$where = array('A.id_category_level2'=>$sql[0]->id_category);
			}
			$image_shared = 'category/'.$sql[0]->image;
		}else{
			//jika yg dipilih adalah brand/manufacture
			$sql = $this->m_client->get_category_brand(array('A.url'=>$url));			
			$data['image_link'] = base_url('assets/images/manufacture/'.image($sql[0]->image_banner));
			$data['name_page'] = $sql[0]->name;
			$data['page_description'] = $sql[0]->description;
			$where = array('B.url'=>$url);
			$image_shared = 'manufacture/'.$sql[0]->image;		
		}					
		$data['tab_title'] = $sql[0]->meta_title;
		$data['meta_keywords'] = $sql[0]->meta_keywords;
		$data['meta_description'] = $sql[0]->meta_description;
		$data['class'] = $this->class;		
		$data['meta_image_name'] = $image_shared;
		$data['product_category'] = $this->m_public->generate_product_grid($where,$offset);	
		$data['rowsql'] = $this->m_client->get_product($where);	
		$data['permalink'] = $param;
		foreach ($this->m_public->product_footer_list() as $row=>$val)
			$data[$row] = $val;
		$data['content'] = 'category/v_category';		
		if (!empty($sortby)){			
			/*
			 * use form sort by ajax
			 */
			$x = explode("-", $sortby);				
			$orderby = array('name_by_sort'=>$x[0],'val_by_sort'=>$x[1]);
			$this->session->unset_userdata('name_by_sort');
			$this->session->unset_userdata('val_by_sort');
			$this->session->set_userdata($orderby);			
			return $this->m_public->generate_product_grid($where,$offset);		
		}else{			
			$this->load->view('w/v_main_body',$data);
		}
		
	}
	
	function page_not_found(){		
		$this->m_public->error_page();
	}
	function change_limit($val){
		//$val = $this->input->post('value',true);
		$this->session->set_userdata('limit_select',$val);					
		redirect($this->session->userdata('permalink_sess'));		
	}
	function sort_by(){
		$res='';
		$val = explode("#",$this->input->post('value',true));
		$permalink = $val[0];
		$sortby = $val[1];//asc-desc name
		$expl = explode("-", $permalink);
		$end = explode(".", end($expl));
		$code = $end[0];//code category,brands,keyword search
		$ctg = isset($end[1]) ? $end[1] : null;//parent,level1,level2
		$res .= $this->category($permalink, $code, $ctg, 0,$sortby);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$res));
	}
	function help($param='',$id_helper=''){
		permalink_sess(FOMODULE.'/'.$param);			
		$data['querydetail'] = $this->m_client->get_helper_detail(array('A.id_helper'=>$id_helper));		
		$data['tab_title'] = $param;
		$data['meta_keywords'] = $param;
		$data['meta_description'] = $param;
		$data['class'] = $this->class;
		$data['sql'] = $this->m_client->get_helper();
		foreach ($this->m_public->product_footer_list() as $row=>$val)
			$data[$row] = $val;
		$data['content'] = 'help/v_help';
		$this->load->view('w/v_main_body',$data);
	}
	
	function other_page($param,$id_helper=""){
		permalink_sess(FOMODULE.'/'.$param);
		$x = array('aboutus'=>array('sql'=>'',
									 'nametitle'=>lang('aboutus').' '.$this->session->userdata('company_name'),
									 'view'=>'v_about_us',									
			
			),
					  'privacy-policy'=>array('sql'=>$this->m_client->get_privacy_policy(),
									 'nametitle'=>lang('privacy'),
									 'view'=>'v_privacy_policy',									
			
			),
					   'term-and-conditions'=>array('sql'=>$this->m_client->get_term(),
									 'nametitle'=>lang('term'),
									 'view'=>'v_terms_conditions',									 
			
			)
		);				
		$nametitle = $x[$param]['nametitle'];		
		$data['tab_title'] = $nametitle;
		$data['meta_keywords'] = $nametitle;
		$data['meta_description'] = $nametitle;
		$data['class'] = $this->class;		
		$data['sql'] = $x[$param]['sql'];
		foreach ($this->m_public->product_footer_list() as $row=>$val)
			$data[$row] = $val;
		$data['content'] = $x[$param]['view'];
		$this->load->view('w/v_main_body',$data);
	}
}
