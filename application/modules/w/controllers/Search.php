<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Search extends CI_Controller{
	var $code;
	var $name;
	var $suff_blank;
	var $class;
	var $sess_permalink;
	var $date_now;
	var $id_order_cart;
	var $id_customer;
	var $isocode;
	public function __construct(){
		parent::__construct();
		$this->load->model('m_client');
		$this->load->model('m_public');
		$this->class = strtolower(__CLASS__);
		$this->m_public->maintenance();		
	}
	function sortby_search(){
		$res='';
		$val = explode("#",$this->input->post('value',true));
		$keyword = $val[0];		
		$sortby = $val[1];//asc-desc name			
		$this->product($keyword,0,$sortby);		
	}
	function product($keyword='',$offset=0,$sortby=''){					
		$key_ajx = str_ireplace(" ", "-",$this->input->post('search'));//replace all space to strip(-) if use ajax
		$param = (empty($key_ajx)) ? $keyword : $key_ajx;//isset param empty
		$title = str_replace("-", " ", $param);//replace all strip to space
		$param_exp = explode("-", $param);//explode string to array		
		
		$field = array(0=>'A.name',1=>'A.product_information',2=>'A.description_product',
					  3=>'A.specification',4=>'A.permalink',5=>'A.meta_description',6=>'A.meta_keywords',7=>'A.meta_title',
					  8=>'B.name',9=>'B.description',10=>'B.meta_description',11=>'B.meta_keywords',12=>'B.meta_title');
		$valkey = array(0=>$field,1=>$param_exp);//grouping array to multiple			
		permalink_sess(FOMODULE.'/'.$this->class.'/product/'.$param);
		$res='';
		$data['tab_title'] = $title;
		$data['meta_keywords'] = $title;
		$data['meta_description'] = $title;
		$data['class'] = $this->class;		
		$data['product_category'] = $this->m_public->generate_product_grid('',$offset,$valkey);
		$data['rowsql'] = $this->m_client->get_product('',$valkey);
		$data['permalink'] = $param;
		$view_content = 'search/v_find_product';
		if (!empty($key_ajx)){	
			//for ajax search proses
			$res .=$this->load->view($view_content,$data,true);	
			$res .=$this->load_btnsort();			
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'content'=>$res,'keyword'=>$param));
		}elseif (!empty($sortby)){
			$x = explode("-", $sortby);
			$orderby = array('name_by_sort'=>$x[0],'val_by_sort'=>$x[1]);
			$this->session->unset_userdata('name_by_sort');
			$this->session->unset_userdata('val_by_sort');
			$this->session->set_userdata($orderby);			
			$res .= $this->m_public->generate_product_grid('',$offset,$valkey);				
			//ajaxcall proses
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$res));
		}
		else{
			//direct page
			foreach ($this->m_public->product_footer_list() as $row=>$val)
				$data[$row] = $val;
			$data['content'] = $view_content;
			$this->load->view('w/v_main_body',$data);
		}			
	}
	function load_btnsort(){
		return '<script>sortby_grid();</script>';
	}
}