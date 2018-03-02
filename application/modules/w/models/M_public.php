<?php if (!defined('BASEPATH')) exit('No direct script access allowed!');
class M_public extends CI_Model{
	var $isocode;
	var $symbol;
	var $rate;
	var $login_user;
	var $id_customer;
	var $id_order_cart;
	var $date_now;
	public function __construct(){
		parent::__construct();
		$this->login_user = $this->session->userdata('login_user');
		$this->id_customer = $this->session->userdata('id_customer');		
		$this->isocode = $this->session->userdata('iso_code_fo');
		$this->symbol = $this->session->userdata('symbol_fo');
		$this->rate = $this->session->userdata('exchange_rate_fo');
		$this->id_order_cart = $this->session->userdata('id_order_cart');	
		$this->date_now = date('Y-m-d H:i:s');
		$this->load->library('front_pagination');
	}
	function product_grid($sql,$div_class=""){
		$res='';
		/*
		 * jika product tersedia
		 */
		if (count($sql) > 0){
			$w=0;
			$res .= !empty($div_class) ? '<div class="row">' : '';	
			foreach ($sql as $row){
				$id_product = $row->id_product;
				$final_price = $row->final_price;
				$img1 = $this->m_client->get_image(array('id_product'=>$id_product,'sort'=>1));			
				$image1 = isset($img1[0]->image_name) ? image_fo($img1[0]->image_name) : 'no-image.jpg';
				$img2 = $this->m_client->get_image(array('id_product'=>$id_product,'sort'=>2));
				$image2 = isset($img2[0]->image_name) ? image_fo($img2[0]->image_name) : image($img1[0]->image_name);
				$badge='';
				if ($row->top_seller == 1){
					$badge = badge('best');
				}else if ($row->featured_product == 1){
					$badge = badge('best');
				}else if ($row->promo == 1){
					$badge = badge('promo');
				}else if ($row->new_product == 1){
					$badge = badge('new');
				}				
				/*
				 * mengambil data perhitungan harga sp
				 */
				$data = $this->price_product($id_product, $final_price);				
				$permalink = base_url(FOMODULE.'/'.$row->permalink.'-'.$row->id_product.'.'.$row->id_category_level1.'.html');
				$res .= !empty($div_class) ? '<div class="'.$div_class.'">' : '';
				$res .='<div class="item item-hover">
                                    <div class="item-image-wrapper">
                                        <figure class="item-image-container">
                                            <a href="'.$permalink.'">
                                                <img src="'.base_url().'assets/images/product/'.$image1.'" alt="'.$image1.'" class="item-image">
                                                <img src="'.base_url().'assets/images/product/'.$image2.'" alt="'.$image2.' hover" class="item-image-hover">
                                            </a>
                                        </figure>';
							 //jika mempunyai discount sp
                               $res .=$data['promodisc'];                               
                                 //lencana produk   
                                  $res.=$badge;                           
                            $res.='</div>
                                    <div class="item-meta-container"><div class="xs-margin"></div>';                          	  
                            		$res .=$data['price_old'];       		                                   
                            		$res .=$data['price_total'];                                           		                           	                                   	                                      	                                                                            
	                         $res.='<h3 class="item-name"><b>'.strtoupper($row->name_manufacture).'</b></h3>                          		                                     
                                    <h3 class="item-name"><a href="'.$permalink.'">'.$row->name.'</a></h3>                                        
                                    </div>
                          </div>';//end item hover
	                         
		        if (!empty($div_class)){
		        	$res .=  '</div>';//end col-md-4 col-sm-6 col-xs-12
		        	//jika sisa nilai dibagi 3 == 0
		        	if (($w + 1) % 3 == 0){
		        		$res .='</div>';
		        		$res .='<div class="row">';
		        	}
		        	$w++;
		        }			
			}//end foreach
	$res .= !empty($div_class) ? '</div>' : '';	//end row	
		}else{	
			$res .='<div class="alert alert-warning alert-dismissable">
                           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>'.lang('itemempty').'</strong> <br>'.lang('emptyitem').'
                      </div>';
		}		
		return $res;		
	}	
	function generate_product_slider($where){		
		$this->db->order_by('A.date_add','desc');
		$sql = array_slice($this->m_client->get_product($where),0,20);	
		return $this->product_grid($sql);
	}
	function generate_product_grid($where,$offset,$valkey=''){
		$limit_default = replace_freetext($this->session->userdata('limit_default'));
		$limit_select = $this->session->userdata('limit_select');
		$limit = (!$limit_select) ? $limit_default : $limit_select;		
		$segment_num = (empty($valkey)) ? 3 : 5;//isset segment pagination jika melakukan find product dan default
		
		//jika klik pagination
		if ($this->uri->segment($segment_num)){
			//for pagination default
			$offset = is_numeric($this->uri->segment($segment_num)) ? $this->uri->segment($segment_num) : 0;			
		}
		
		//value sort get by session		
		$name_by = $this->session->name_by_sort;
		$val_by = $this->session->val_by_sort;					
		(!$name_by) ? $this->db->order_by('A.date_add','desc') : $this->db->order_by($name_by,$val_by);
		$data = $this->m_client->get_product($where,$valkey);
		$config['div'] = 'produk';
		$config['base_url'] = base_url($this->session->userdata('permalink_sess'));
		$config['total_rows'] = count($data);
		$config['per_page'] = $limit;
		$config['uri_segment'] = $segment_num;
		$this->front_pagination->initialize($config);
		$sql = array_slice($data,$offset,$limit);//proses membagi item dari 0-jumlah limit yg ditentukan		
		$div_class='col-md-4 col-sm-6 col-xs-12';
		return $this->product_grid($sql,$div_class);
	}
	function generate_product_footer($where){
		$this->db->order_by('A.date_add','desc');
		$sql = array_slice($this->m_client->get_product($where),0,20);
		return $this->product_footer($sql);
	}	
	
	function convert_price($price){			
		if ($this->session->userdata('iso_code_fo') == 'IDR'){
			/*
			 * melakukan pemmotongan dan penambahan nilai ratusan
			 * 
			 */
			$total = $price * $this->rate;//harga USD * rate rupiah
			/*
			$ratusan = substr($total, -3);
			if ($ratusan < 500){
				//Ex : 18.300 = 18.000
				$total = $total - $ratusan;
			}else{
				//Ex : 18.700 = 19.000
				$total = $total + (1000 - $ratusan);
				
			}		
			*/	
			$result['total'] = $total;
			$result['format_total'] = number_format($total,0,'.','.');
		}else{
			$result['total'] = $price;
			$result['format_total'] = $price;
		}		
		return $result;
	}
	function convert_price_shipp($price,$iso_code){
		if ($iso_code == 'IDR'){
			/*
			 * melakukan pemmotongan dan penambahan nilai ratusan
			*
			*/
			$total = $price * $this->rate;//harga USD * rate rupiah
			
			$ratusan = substr($total, -3);
			if ($ratusan < 500){
				//Ex : 18.300 = 18.000
				$total = $total - $ratusan;
			}
			/*
			else{
			//Ex : 18.700 = 19.000
				$total = $total + (1000 - $ratusan);	
			}*/			
			$result['total'] = $total;
			$result['format_total'] = number_format($total,0,'.','.');
		}else{
			$result['total'] = $price;
			$result['format_total'] = $price;
		}
		return $result;
	}
	function generate_price($id_product,$price,$id_productattr=""){
		//jika melakukan select attribute 
		$where = (empty($id_productattr)) ? array('A.id_product'=>$id_product,'A.default'=>1) : array('A.id_product_attribute'=>$id_productattr);
	
		$attribute = $this->m_client->get_product_attribute($where);
		if (count($attribute) > 0){
			/*
			 * jika produk mempunyai attribute
			 */
			foreach ($attribute as $row){
				$disc_sp=0;
				$price_sp = 0;
				$sp=0;
				$amount_disc=0;				
				$impact_price = $row->price_impact;
				$id_product_attribute = $row->id_product_attribute;
				$total = $price + $impact_price;
				
				/*
				 * get data sp
				 * melakukan cek apakah ada harga sp, jika tidak ada maka id_customer = 0
				 */

									
				$cek = $this->m_client->get_specific_price(array('id_customer_sp'=>$this->id_customer,
																 'id_product'=>$id_product,
																 'id_product_attribute'=>$id_product_attribute),
														 		 $this->m_client->get_idgroup());
				
				$id_customer = (count($cek) > 0) ? $this->id_customer : 'all';
				$sql = $this->m_client->get_specific_price(array('id_customer_sp'=>$id_customer,
																'id_product'=>$id_product,
																'id_product_attribute'=>$id_product_attribute),
																$this->m_client->get_idgroup());
				/*
				 * jika mempunyai harga specific price
				 */
				if (count($sql) > 0){				
					foreach ($sql as $v){
						$price_sp = $v->price_sp;
						$disc_sp = $v->disc_sp;
						$sp = $price_sp + $impact_price;//harga sp + impact attribute												
						$amount_disc = ($sp * $disc_sp) / 100; 
						$total = $sp - $amount_disc;						
					}
				}				
			}			
			$data['total'] = $total;//total akumulasi harga sp + harga impact - discon;
			$data['disc'] = $disc_sp;
			$data['sp'] = $sp;//hanya harga sp + harga impact
			$data['id_product_attribute'] = $id_product_attribute;
			$data['impact_price'] = $impact_price;//hanya harga impact
			$data['amount_disc'] = $amount_disc;//hanya discon amount
			$data['price_sp'] = $price_sp;//hanya harga sp
			
		}else{
			/*
			 * jika produk tidak mempunyai attribute / default attribute belum ditentukan 
			 * maka hanya menampilkan harga basic tidak ada tambahan impact harga
			 */
			
			$total = $price;
			$disc_sp=0;
			$price_sp=0;
			$amount_disc=0;
			// get data sp								
			$where = array('id_customer_sp'=>$this->id_customer,'id_product'=>$id_product);
			$cek = $this->m_client->get_specific_price($where,$this->m_client->get_idgroup());																																		 
		
			$id_customer = (count($cek) > 0) ? $this->id_customer : 'all';			
			$sql = $this->m_client->get_specific_price(array('id_customer_sp'=>$id_customer,'id_product'=>$id_product),
														$this->m_client->get_idgroup());			
			
			/*
			 * jika mempunyai harga specific price
			*/
			if (count($sql) > 0){
				foreach ($sql as $v){
					$price_sp = $v->price_sp;//harga sp
					$disc_sp = $v->disc_sp;
					$amount_disc = ($price_sp * $disc_sp) / 100;
					$total = $price_sp - $amount_disc;			
				}
			}
			$data['total'] = $total;
			$data['disc'] = $disc_sp;
			$data['sp'] = $price_sp;
			$data['id_product_attribute'] = 0;
			$data['impact_price'] = 0;
			$data['amount_disc'] = $amount_disc;
			$data['price_sp'] = $price_sp;//hanya harga sp
		}
		return $data;		
	}
	function product_footer($sql){
		$res='';
		/*
		 * jika product tersedia
		*/
		if (count($sql) > 0){
			foreach ($sql as $row){
				$id_product = $row->id_product;
				$final_price = $row->final_price;
				$img1 = $this->m_client->get_image(array('id_product'=>$id_product,'sort'=>1));
				$image1 = isset($img1[0]->image_name) ? image_fo($img1[0]->image_name) : 'no-image.jpg';				
				/*
				 * mengambil data perhitungan harga sp
				*/
				$data = $this->generate_price($id_product, $final_price);
				$total_price = $data['total'];
				$sp = $data['sp'];
				$disc = $data['disc'];
				$permalink = base_url(FOMODULE.'/'.$row->permalink.'-'.$row->id_product.'.'.$row->id_category_level1.'.html');
				$res .='<li>
                             <div class="slide-item clearfix">
                                        <figure class="item-image-container">
                                            <a href="'.$permalink.'">
                                                <img src="'.base_url().'assets/images/product/'.$image1.'" alt="'.$image1.'" class="item-image">                                               
                                            </a>
                                        </figure>';
							 $res.='<p class="item-name"><b>'.strtoupper($row->name_manufacture).'</b></hp>
                                    <p class="item-name"><a href="'.$permalink.'">'.$row->name.'</a></p>';
							 $res .='<div class="xs-margin"></div>';
							 //jika mempunyai discount sp
							 if ($disc > 0){
							 	$res.='<p class="item-name">'.number_format($disc,0).'% OFF</p>';							 	
							 }else if ($sp > 0 && $disc < 1){
							 	$res.='<p class="item-name">PROMO</p>';				                                          
							 }
							 $res .='<div class="margin"></div>';
							 if ($sp > 0 && $disc > 0){
							 	/*
							 	 * jika ada harga sp dan ada discon sp
							 	*/
							 
							 	$res.='<div class="item-price-container inline">
	                                             	<span class="old-price">'.$this->symbol.' '.$this->convert_price($sp)['format_total'].'</span>
	                                        	</div>';
							 }else if ($sp > 0){
							 	/*
							 	 * jika hanya ada harga sp
							 	*/
							 
							 	$res.='<div class="item-price-container inline">
	                                             	<span class="old-price">'.$this->symbol.' '.$this->convert_price($final_price)['format_total'].'</span>
	                                        	</div>';
							 
							 }
							 $res .='<div class="margin"></div>';
							 $res.='<div class="item-price-container inline">
	                                            <span class="item-price">
	                                                '.$this->symbol.' '.$this->convert_price($total_price)['format_total'].'
	                                            </span>
	                                </div>';
							 	 
				
               $res.='</div></li>';
			}
				
		}else{
				
		}
		return $res;
	}
	
	function price_product($id_product, $final_price,$id_product_attribute=""){
		$data = $this->generate_price($id_product, $final_price,$id_product_attribute);
		$total_price = $data['total'];
		$sp = $data['sp'];
		$disc = $data['disc'];
		$price_old="";
		$price_total="";
		$promodisc="";
		$promocarosel="";
		if ($disc > 0){
			$promodisc .='<div class="item-price-container">
	                                          <span class="item-price">
	                                                '.number_format($disc,0).'% OFF
	                                           </span>
	                                    </div>';
		}else if ($sp > 0 && $disc < 1){
			$promodisc.='<div class="item-price-container">
	                                          <span class="item-price">
	                                                PROMO
	                                           </span>
	                                    </div>';
		}
		/*
		 * digunakan untuk halaman product carosel
		 */
		if ($disc > 0){
			$promocarosel.='<figcaption class="item-price-container">
												<span class="item-price id="disc">'.number_format($disc,0).'% OFF</span>
										  </figcaption>';
		}else if ($sp > 0 && $disc < 1){
			$promocarosel.='<figcaption class="item-price-container">
								<span class="item-price id="promo">PROMO</span>
						    </figcaption>';
		}
		
		if ($sp > 0 && $disc > 0){
			/*
			 * jika ada harga sp dan ada discon sp
			*/			 
			$price_old.='<div class="item-price-container inline">
	                    <span class="old-price">'.$this->symbol.' '.$this->convert_price($sp)['format_total'].'</span>
	                </div>';
		}else if ($sp > 0){
			/*
			 * jika hanya ada harga sp
			*/			 
			$price_old.='<div class="item-price-container inline">
	                    <span class="old-price">'.$this->symbol.' '.$this->convert_price($final_price)['format_total'].'</span>
	               </div>';
			 
		}		
		$price_total .='<div class="item-price-container inline">
	                  <span class="item-price" id="total-price">
	                       '.$this->symbol.' '.$this->convert_price($total_price)['format_total'].'
	                  </span>
	            </div>';
		
		/*
		 * cek stock
		 */		
		$id_warehouse = $this->m_client->id_warehouse();//multiple array by branch default
		$where = array('id_product'=>$id_product,'id_product_attribute'=>$data['id_product_attribute']);
		$x = $this->m_client->get_stock($where,$id_warehouse);
		$stok="";
		$btnorder ='';
		if (count($x) > 0)
		{
			$stok = ($x[0]->qty_available > 0) ? '<span class="validation-success">'.lang('readystok').' '.$x[0]->qty_available.'</span>' : '<span class="validation-error">'.lang('emptystok').'</span>';
			$btnname = ($x[0]->qty_available > 0) ? lang('addcart') : lang('preorder');
			$btnorder = '<input type="submit" class="btn btn-custom-2" id="btn-submit" data-toggle="modal" data-target="#MyModal" value="'.$btnname.'">';
		}else{
			$stok = '<span class="validation-error">Stock N/A || Branch not set</span>';
			
		}
		$data['promocarosel'] = $promocarosel;
		$data['promodisc'] = $promodisc;
		$data['price_old'] = $price_old;
		$data['price_total'] = $price_total;
		$data['stok'] = $stok;
		$data['btnorder'] = $btnorder;
		return $data;
	}
	
	function product_footer_list(){
		$data['new'] = $this->m_public->generate_product_footer(array('new_product'=>1));
		$data['featured'] = $this->m_public->generate_product_footer(array('featured_product'=>1));
		$data['top'] = $this->m_public->generate_product_footer(array('top_seller'=>1));
		return $data;
	}
	function top_list_cart(){
		$res='';
		$id_order_cart = $this->session->userdata('id_order_cart');//langsung update id jika ganti currency
		$x = $this->generate_sum_cart();		
		$total_qty = $x['total_qty'];
		$total_price = $x['total_price'];
		$res .='<div class="btn-group dropdown-cart">
                                <span class="cart-menu-icon"></span>
                                <button type="button" class="btn btn-custom dropdown-toggle" data-toggle="dropdown">
                                    '.$total_qty.' item(s) <span class="drop-price">- '.$this->symbol.''.$total_price.'</span>
                                </button>                                
                                <div class="dropdown-menu dropdown-cart-menu pull-right clearfix" role="menu">';                                        	
							$content = $this->m_client->get_cart_content(array('A.id_order_cart'=>$id_order_cart));
							if (count($content) > 0){
								$res .='<p class="dropdown-cart-description">Recently added item(s).</p>
	                                    	<ul class="dropdown-cart-product-list">';
							foreach ($content as $row){		
									$permalink = base_url(FOMODULE.'/'.$row->permalink.'-'.$row->id_product.'.'.$row->id_category_level1.'.html');
									$unit_price = ($this->isocode == 'IDR') ? number_format($row->unit_price,0,'.','.') : $row->unit_price;						
									$res .='<li class="item clearfix">
	                                            <a href="javascript:void(0)" title="Delete item" class="delete-item" onclick="ajaxDelete(\''.base_url(FOMODULE.'/product/delete_cart').'\',\''.$row->id_order_cart_detail.'\',\'list-cart\')"><i class="fa fa-times"></i></a>	                                            
	                                                <figure>
	                                                    <a href="'.$permalink.'"><img src="'.base_url().'assets/images/product/'.image($row->image_name).'" alt="'.$row->image_name.'"></a>
	                                                </figure>
	                                                <div class="dropdown-cart-details">
	                                                    <p class="item-name">
	                                                    	<a href="'.$permalink.'">'.$row->name.'</a><br>
	                                                    	'.$row->name_group.'<br>'.$row->name_attribute.'
	                                                    </p>
	                                                    <p>
	                                                        '.$row->product_qty.'x
	                                                        <span class="item-price">'.$this->symbol.''.$unit_price.'</span>
	                                                    </p>
	                                                </div>
	                                            </li>';
	                                           	
								}					                                 
                           $res .='</ul>';                                        
                               $res .='<ul class="dropdown-cart-total">                                            
                                            <li><span class="dropdown-cart-total-title">Total:</span>'.$this->symbol.''.$total_price.'</span></li>
                                        </ul>
                                        <div class="dropdown-cart-action">                                            
                                            <p><a href="'.base_url(FOMODULE.'/checkout/proses').'" class="btn btn-custom btn-block">'.lang('listcheckout').'</a></p>
                                        </div>';                               
							}else{
								//empty cart
								$res .='<p class="dropdown-cart-description">'.lang('emptycart').'</p>';
							}
                           $res .='</div>
                                    </div>';
                           
		return $res;
	}
	function error_page(){
		foreach ($this->m_public->product_footer_list() as $row=>$val)
			$data[$row] = $val;
		$name = '404 Page not found';
		$data['tab_title'] = $name;
		$data['meta_keywords'] = $name;
		$data['meta_description'] = $name;
		$data['content'] = 'v_404';
		$this->load->view('v_main_body',$data);
	}
	function chosen_province($id,$id_province='',$class=''){
		$res ="";
		$select ="";
		//jika bukan halaman districts		
		$sql = $this->m_client->get_table('tb_province',array('id_province','province_name'),array('id_country'=>$id,'deleted'=>0));		
		if (count($sql) > 0){
			$res .='<option value="" selected disabled>-'.lang('chsprovince').'-</option>';
			foreach ($sql as $row){
				if (!empty($id_province)){$select = ($id_province == $row->id_province) ? 'selected' : '';}
				$res .='<option value="'.$row->id_province.'" '.$select.'>'.$row->province_name.'</option>';
			}	
		}else{
			$res .='<option value="" selected disabled>-'.lang('chsprovince').'-</option>';
		}
		return $res;
	}
	function chosen_city($id,$id_cities='',$class=''){
		$res ="";
		$select ="";			
		$sql = $this->m_client->get_table('tb_cities',array('id_cities','cities_name'),array('id_province'=>$id,'deleted'=>0));
		if (count($sql) > 0){
			$res .='<option value="" selected disabled>-'.lang('chscity').'-</option>';
			foreach ($sql as $row){
				if (!empty($id_cities)){$select = ($id_cities == $row->id_cities) ? 'selected' : '';}
				$res .='<option value="'.$row->id_cities.'" '.$select.'>'.$row->cities_name.'</option>';
			}
		}else{
			$res .='<option value="" selected disabled>-'.lang('chscity').'-</option>';
		}				
		return $res;
	}
	function chosen_districts($id,$id_districts){
		$res ="";
		$select ="";
		$sql = $this->m_client->get_table('tb_districts',array('id_districts','districts_name'),array('id_cities'=>$id,'deleted'=>0));
		if (count($sql) > 0){
			$res .='<option value="" selected disabled>-'.lang('chsdis').'-</option>';
			foreach ($sql as $row){
				if (!empty($id_districts)){$select = ($id_districts == $row->id_districts) ? 'selected' : '';}
				$res .='<option value="'.$row->id_districts.'" '.$select.'>'.$row->districts_name.'</option>';
			}
		}else{
			$res .='<option value="" selected disabled>-'.lang('chsdis').'-</option>';
		}				
		return $res;
	}
	function delivery_address(){
		$res ='';		
		$sql = $this->m_client->get_customer_address(array('A.id_customer'=>$this->session->userdata('id_customer')));		
		//jika sudah ada alamat yg didaftarkan
		if (count($sql) > 0){			
			$res .='<div class="row">';
			$res .='<div class="col-md-12 col-sm-12 col-xs-12"><p>'.lang('sureaddress').'</p></div>';
			foreach ($sql as $row){
				if (!empty($row->name_received)){					
					$check = ($row->default == 1) ? 'checked' : '';
					$bgcolor = ($row->default == 1) ? 'style="background:#f4f4f4"' : '';
					$delete = ($row->default == 1) ? '' : '<a href="javascript:void(0)"  onclick="ajaxDelete(\''.base_url(FOMODULE.'/checkout/delete_address').'\',\''.$row->id_address.'\',\'deliveryaddr\')"  title="Edit item" class="edit-item"><i class="fa fa-times"></i> '.lang('delete').'</a>';	
					
					//set session jika sudah ada alamat yang difault
					if (!empty($check)){
						$this->session->set_userdata('id_districts',$row->id_districts);//set session jika input address pertama kali
						$this->session->set_userdata('id_country',$row->id_country);
					}
			$res .='<div class="col-md-6 col-sm-6 col-xs-12" >					 
					<fieldset class="half-margin">
						<div class="input-desc-box" '.$bgcolor.'>
						<div class="input-group custom-checkbox">
							<input type="checkbox" name="deliveryaddress" value="'.$row->id_address.'" '.$check.' onclick="ajaxcall(\''.base_url(FOMODULE.'/checkout/change_address').'\',\''.$row->id_address.'\',\'deliveryaddr\')"> <span class="checbox-container">
								<i class="fa fa-check"></i>
							</span																		 
						</div>
							'.$row->alias_name.'<br>
							<h6><b>'.$row->name_received.'</b></h6>
							<p>
							'.$row->address.'<br>';
						  if ($row->country_code == 'ID'){
						  	//jika negara indonesia
						  		$res .= $row->districts_name.', '.$row->cities_name.', '.$row->postcode.'<br>												
									   '.$row->province_name.', '.$row->country_name.'<br>';
						  }else{
						  	$res .=$row->postcode.', '.$row->country_name.'<br>';
						  }										
							$res .='Phone: '.$row->phone_addr.'</p>	
							<div class="xs-margin"></div>
								<a href="javascript:void(0)"  onclick="ajaxModal(\''.base_url(FOMODULE.'/checkout/add_address').'\',\''.$row->id_address.'\',\'MyModal\')"  data-toggle="modal" data-target="#MyModal" title="Edit item" class="edit-item"><i class="fa fa-pencil"></i> '.lang('edit').'</a>
								'.$delete.'
							</div>
					</fieldset>								   										   			
				</div>';
				}else{
					$res.= '<div class="col-md-12 col-sm-12 col-xs-12">'.alert_warning('Please add your shipping address before the next process.').'</div>';
				}
			}
			$res.='</div>';
			$res .='<a href="javascript:void(0)" class="btn btn-custom-2" onclick="ajaxModal(\''.base_url(FOMODULE.'/checkout/add_address').'\',\'\',\'MyModal\')"  data-toggle="modal" data-target="#MyModal"><span class="separator icon-box">+</span> Tambah Alamat Pengiriman</a>';
		}
		
		return $res;
	}
	function delivery_method(){
		$res ='';
		$id_districts = $this->session->userdata('id_districts');		
		$id_country = $this->session->userdata('id_country');				
		$sql = $this->m_client->get_courier_zone(array('A.id_districts'=>$id_districts,'A.id_country'=>$id_country));
		if (count($sql) > 0){
			foreach ($sql as $row){
				$where = array('id_districts_to'=>$row->id_districts,'id_courier'=>$row->id_courier,'id_country_to'=>$row->id_country);
				$x = $this->generate_shipp_cost($where,$row->fixed_cost);				
				$total_price_shipp= $x['total_price_format'];
				$res .='<tr>
							<td><label class="radio-inline">
									<input type="radio" name="courier" value="'.$row->id_courier.'" id="selectcourier.'.$row->id_courier_zone.'" onclick="ajaxcall(\''.base_url(FOMODULE.'/checkout/select_courier/'.$row->id_courier).'\',\''.$row->id_districts.'#'.$row->fixed_cost.'#'.$row->id_country.'\',\'costshipp\')"/>
									'.$row->name.' <img src="'.base_url('assets/images/courier/'.$row->image).'" style="width:100px" title="Click to select courier">
								</label> </td>
							
							<td>'.$this->symbol.' '.$total_price_shipp.'</td>									
						</tr>';
			}
		}else{
			$res .='<tr><td colspan=3">'.alert_warning('Area zones are not covered by shipping services.').'</td></tr>';
		}			
		return $res;
	}
	function payment_method($wherecod=''){		
		$res ='';					
		$sql = $this->m_client->get_payment($wherecod);
		if (count($sql) > 0){
			foreach ($sql as $row){				
				$res .='<tr>
							<td>
								<label class="radio-inline">
									<input type="radio" name="payment" value="'.$row->id_payment_method.'" id="payment'.$row->id_payment_method.'"/>
									 '.$row->method_name.' <img src="'.base_url('assets/images/payment/'.$row->logo).'" style="width:100px" title="Click to select payment">
								</label> 
						   </td>							
						</tr>';
			}
		}else{
			$res .='<tr><td colspan=3">'.alert_warning('No payment methods listed!.').'</td></tr>';
		}
		return $res;
	}
	function item_cart(){
		$res='';
		
		//summary item cart
		$x = $this->generate_sum_cart();		
		$total_qty = $x['total_qty'];
		$total_price = $x['total_price'];
		$total_price_num = $x['total_price_num'];
		
		//item cart detail
		$cart_content = $this->m_client->get_cart_content(array('id_order_cart'=>$this->id_order_cart));
		$num = count($cart_content);
		if ($num){
			$res .='<tbody>';
			$i=0;
			foreach ($cart_content as $row){
				$permalink = base_url(FOMODULE.'/'.$row->permalink.'-'.$row->id_product.'.'.$row->id_category_level1.'.html');
				$unit_price = ($this->isocode == 'IDR') ? formatnum($row->unit_price) : $row->unit_price;
				$subtotal_price = ($this->isocode == 'IDR') ? formatnum($row->total_price) : $row->total_price;
				$res .='<tr id="'.$row->id_order_cart_detail.'">';
					$res .='<td class="item-name-col">
								<figure>
								<a href="'.$permalink.'"><img src="'.base_url().'assets/images/product/'.image($row->image_name).'" alt="'.$row->name.'"></a>
								</figure>
								<header class="item-name"><a href="'.$permalink.'">'.$row->name.'</a></header>
								<ul>
									<li>'.$row->name_group.': '.$row->name_attribute.'</li>								
								</ul>
							</td>';
					$res .='<td class="item-price-col"><span class="item-price-special">'.$this->symbol.' '.$unit_price.'</span></td>';
					$res .='<td>
								<div class="custom-quantity-input">
									<input type="text" readonly name="quantity'.$i.'" id="item-qty'.$i.'" value="'.$row->product_qty.'">
									<a href="javascript:void(0)" class="quantity-btn quantity-input-up" id="'.$i.'" onclick="ajaxcall(\''.base_url(FOMODULE.'/checkout/edit_qty/'.$row->id_order_cart_detail.'.plus').'\',$(\'[name=quantity'.$i.']\').val(),this.id)"><i class="fa fa-angle-up"></i></a>
									<a href="javascript:void(0)" class="quantity-btn quantity-input-down" id="'.$i.'" onclick="ajaxcall(\''.base_url(FOMODULE.'/checkout/edit_qty/'.$row->id_order_cart_detail.'.min').'\',$(\'[name=quantity'.$i.']\').val(),this.id)"><i class="fa fa-angle-down"></i></a>
								</div>
							</td>';
					$res .='<td class="item-total-col"><span class="item-price-special">'.$this->symbol.' <span id="subtotal'.$row->id_order_cart_detail.'">'.$subtotal_price.'</span></span></td>';
					$res .='<td><a href="javascript:void(0)" class="close-button" onclick="ajaxDelete(\''.base_url(FOMODULE.'/checkout/delete_cart').'\',\''.$row->id_order_cart_detail.'\',\'list-cart\')"></a></td>';
				$res .='</tr>';
				$i++;
			}
			$res .='<tr>
						<td class="checkout-table-title" colspan="2">'.lang('totqty').':</td>
						<td class="checkout-table-price" id="totqty">'.$total_qty.'</td>
						<td class="checkout-table-title" colspan="1">'.lang('subtot').':</td>
						<td class="checkout-table-price">'.$this->symbol.' <span id="subtotal">'.$total_price.'</span></td>
					</tr>';
			//generate tax
			$tx = $this->generate_tax($total_price_num);
			if ($tx['rate_tax'] > 0){							
				$res .='<tr>
						<td class="checkout-table-title" colspan="4">'.lang('tax').' '.$tx['rate_tax'].'%:</td>
						<td class="checkout-table-price">'.$this->symbol.' <span id="amount_tax">'.$tx['amount_tax_format'].'</span></td>
					</tr>';
			}
			$res .='<tr>
						<td class="checkout-table-title" colspan="4">'.lang('costshipp').':</td>
						<td class="checkout-table-price" id="costshipp">0</td>
					</tr>';		
			$res .='</tbody>';
			$res .='<tfoot>
						<tr>
							<td class="checkout-total-title" colspan="4"><strong>'.lang('total').':</strong></td>
							<td class="checkout-total-price cart-total"><strong id="totalshopp">0</strong></td>
						</tr>
				  </tfoot>';
		}else{
			
		}
		return $res;
	}
	function generate_shipp_cost($where='',$fixed_cost){
		$x = $this->m_client->get_shipp_cost($where);
		if (count($x) > 0){
			$country_code = $x[0]->country_code;
			$p_kg = isset($x[0]->price) ? $x[0]->price : 0;//harga shipp perkilo dri JNE
			//$w_first = isset($x[0]->weight_first) ? $x[0]->weight_first : 0;//satuan berat minimal
			
			/*
			 * mendapatkan summary berat keranjang belanja
			*/
			$sum_shipp = $this->m_client->get_sum_shipp();//total berat,volume jumlah item cart
			$isocode = isset($sum_shipp[0]->iso_code) ? $sum_shipp[0]->iso_code : '';
			$total_volume = isset($sum_shipp[0]->total_volume) ? $sum_shipp[0]->total_volume : 0;//total volume
			$total_weight = isset($sum_shipp[0]->total_weight) ? $sum_shipp[0]->total_weight : 0;//total berat
			
			
			
			//jika courier disetting bukan harga fixed(bukan harga tetap)
			/*
			 * harga fixed digunakan untuk pengiriman cod / dengan gojek
			*/
			$total_price = $this->convert_price_shipp($p_kg,$isocode)['total'];
			if ($fixed_cost == 0){
				if ($country_code == 'ID'){
					/*
					 * mendapatkan total cost dalam negeri dengan perhitungan JNE
					*/
					$total = ($total_volume >= $total_weight) ? $total_volume : $total_weight;//jika berat volume lebih besar sama dengan total_weight maka total = total_volume
					$price_kg = $this->convert_price_shipp($p_kg,$isocode)['total'];//convert nilai usd ke IDR jika isocode IDR
					$totalkg = ceil($total);//berat total item cart dibulatkan keatas
					$total_price = $price_kg * $totalkg;//hitung harga shipp * Perkilo * total berat belanja
					
				}else{
					/*
					 * menghitung harga pengiriman luar negeri
					*/
					$total_price = 0;
				}
			}
			
			$data['total_price_num'] = $total_price;
			$data['total_price_format'] =  ($isocode == 'IDR') ? number_format($total_price,0,'.','.') : $total_price;
			$data['total_volume'] = $total_volume;
			$data['total_weight'] = $total_weight;
			return $data;
		}else{
			return false;
		}
		
	}
	function generate_sum_cart(){
		$x = $this->m_client->get_sum_cart(array('id_order_cart'=>$this->session->userdata('id_order_cart')));
		$totalprice = isset($x[0]->total_price) ? $x[0]->total_price : 0;
		$data['total_qty'] = isset($x[0]->total_qty) ? $x[0]->total_qty : 0;
		$data['total_price'] = ($this->isocode == 'IDR') ? number_format($totalprice,0,'.','.') : $totalprice;
		$data['total_price_num'] = $totalprice;
		return $data;
	}
	
	function generate_tax($val){
		$rate_tax = formatnum($this->session->userdata('rate_tax_fo'));
		$amount = ($val * $rate_tax) / 100;
		$data['amount_tax_format'] = ($this->isocode == 'IDR') ? formatnum($amount) : $amount;//format
		$data['amount_tax_num'] = $amount;
		$data['rate_tax'] = $rate_tax;
		return $data;
	}
	function review_order_paypal(){
		$res='';
		$cxt = $this->session->userdata('checkout');
		$id_address_delivery = $cxt['method']['id_address_delivery'];
		$id_courier = $cxt['method']['id_courier'];
		$id_payment =$cxt['method']['id_payment'];
		$id_branch = $cxt['method']['id_branch'];
		$notes = $cxt['method']['notes'];
		
		/*
		 * address delivery
		 */
		$sql = $this->m_client->get_customer_address(array('C.id_address'=>$id_address_delivery));
		$name_received = $sql[0]->name_received;
		$address = $sql[0]->address;
		$postcode = $sql[0]->postcode;
		$countrycode = $sql[0]->country_code;
		$country = $sql[0]->country_name;
		$phone = $sql[0]->phone_addr;
		
		/*
		 * shipping courier
		 */
		$xt = $this->m_client->get_courier_zone(array('B.id_courier'=>$id_courier));
		$total_cost_shipp = $this->session->userdata('cost_shipp');
		
		/*
		 * payment method
		 */
		$px = $this->m_client->get_payment(array('A.id_payment_method'=>$id_payment),true);
		$total_payment = $this->session->userdata('total_payment');		
		
		/*
		 * summary total
		 */
		$total_qty =  $this->session->userdata('total_qty');
		$amount_tax = $this->session->userdata('amount_tax');
		$rate_tax = $this->session->userdata('rate_tax_fo');
		$total_product = $this->session->userdata('total_product');
		
		$res.='<div class="xs-margin"></div>
					<div class="no-content-comment" style="text-align:center">						
						<h4>'.sprintf(lang('text2'),$this->session->userdata('company_name')).'</h4>
						<h5>'.lang('paypaltxtconf').'</h5>
						<h5>'.lang('papypalmakesure').'</h5>
					</div>
			<div class="xlg-margin"></div>
			  <h5>'.lang('text3').':</h5>';
		$res .='<div class="input-desc-box">
					<div class="row">
						<div class="col-md-3 col-sm-6 col-xs-6">
							<h5>'.lang('text5').' :</h5>
							<p>'.nama_hari($this->date_now).', '.tgl_indo($this->date_now).'</p>
							<h5>'.lang('currency').' :</h5>
							<p>'.$this->isocode.'</p>	
				 		  </div>
						<div class="col-md-3 col-sm-6 col-xs-6">
						   <h5>'.lang('delivaddr').' :</h5>
						    <p>
						     <b>'.$name_received.'</b><br>
						    	'.$address.'</br>
								'.$postcode.', '.$country.'<br>				
								Phone: '.$phone.'					    				
						    </p>				
						</div>
						<div class="col-md-3 col-sm-6 col-xs-6">
						   <h5>'.lang('couriershipp').' :</h5>
						    <p>'.$xt[0]->name.'</p>
						    <h5>'.lang('text9').' :</h5>
						    <p>'.$this->symbol.' '.$total_cost_shipp.'</p>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-6">
						    <h5>'.lang('paymethod').' :</h5>	
						    <p>'.$px[0]->method_name.'</p>	
						    <h5>Owner :</h5>
							<p>'.$px[0]->name_owner.'</p>
							<h5>'.lang('text6').' :</h5>
							<p>'.$this->symbol.' '.$total_payment.'</p>
						</div>
					</div>
				</div>';
		$res .=$this->m_public->list_product(array('A.id_order_cart'=>$this->id_order_cart),true);
		$x = array('symbol'=>$this->symbol,'total_product'=>$total_product,
				  'rate_tax'=>$rate_tax,'amount_tax'=>$amount_tax,
				  'total_cost_shipp'=>$total_cost_shipp,'total_payment'=>$total_payment,
				  'notes'=>$notes,'total_qty'=>$total_qty
		);
		$res .=$this->m_public->summary_product($x);
		return $res;
	}
	function finish_order(){
		$res='';
		$id_order = $this->session->userdata('id_order');
		$sql = $this->m_client->get_order(array('A.id_order'=>$id_order));
		if (count($sql) > 0){
			$total_payment = ($sql[0]->iso_code == 'IDR') ? formatnum($sql[0]->total_payment) : $sql[0]->total_payment;
			$total_cost_shipp = ($sql[0]->iso_code == 'IDR') ? formatnum($sql[0]->total_cost_shipping) : $sql[0]->total_cost_shipping;
			$total_product = ($sql[0]->iso_code == 'IDR') ? formatnum($sql[0]->total_product) : $sql[0]->total_product;
			$amount_tax = ($sql[0]->iso_code == 'IDR') ? formatnum($sql[0]->amount_tax) : $sql[0]->amount_tax;
			$res.='<div class="xs-margin"></div>
        					<div class="no-content-comment" style="text-align:center">
						          <h2><i class="fa fa-check"></i></h2>
						             <h1>'.lang('text1').'</h1>
						          <h4>'.sprintf(lang('text2'),$this->session->userdata('company_name')).'</h4>
						    </div>';
						$res.='<div class="md-margin"></div>';
						$res.=$this->payment_info($sql[0]->pay_code);							
			$res.='<h5>'.lang('text3').'</h5>
					<div class="input-desc-box">
						    <div class="row">
						    	<div class="col-md-3 col-sm-6 col-xs-6">
						    		<h5>'.lang('text4').' :</h5>
						    		<p>#'.$id_order.'</p>
						    		<h5>'.lang('text5').' :</h5>
						    		<p>'.nama_hari($sql[0]->date_add_order).', '.tgl_indo($sql[0]->date_add_order).'</p>		
						    		<h5>'.lang('currency').' :</h5>
									<p>'.$sql[0]->iso_code.'</p>	
						    	</div>
						    	<div class="col-md-3 col-sm-6 col-xs-6">
						    		<h5>'.lang('delivaddr').' :</h5>
						    		<p>
						    		<b>'.$sql[0]->name_received.'</b><br>
						    			'.$sql[0]->address.'</br>';
									if ($sql[0]->country_code == 'ID'){
						    		$res .=$sql[0]->districts_name.', '.$sql[0]->cities_name.', '.$sql[0]->postcode.'<br>
						    			 '.$sql[0]->province_name.', '.$sql[0]->country_name.'<br>';
									}else{
										$res .=$sql[0]->postcode.', '.$sql[0]->country_name.'<br>';
									}					
								$res .='Phone: '.$sql[0]->phone_addr.'							    				
						    		</p>			
						    	</div>								
						    	<div class="col-md-3 col-sm-6 col-xs-6">
						    		<h5>'.lang('couriershipp').' :</h5>
						    		<p>'.$sql[0]->name_courier.'</p>
						    		<h5>'.lang('text9').' :</h5>
						    		<p>'.$sql[0]->symbol.' '.$total_cost_shipp.'</p>
						    	</div>
						    	<div class="col-md-3 col-sm-6 col-xs-6">';			
									if ($sql[0]->pay_code == 8801){
										//transfer via bank rekening
									$res .='<h5>'.lang('paymethod').' :</h5>
											<p>'.$sql[0]->method_name.'</p>
											<h5>No. Rek :</h5>
											<p>'.$sql[0]->content.'</p>
											<h5>Owner :</h5>
											<p>'.$sql[0]->name_owner.'</p>';
									}else{									
									$res .='<h5>'.lang('paymethod').' :</h5>
											<p>'.$sql[0]->method_name.'</p>';
									}
						$res .= '<h5>'.lang('statepay').' :</h5>
									<p><span class="'.pay_status($sql[0]->payment_result).'">'.$sql[0]->payment_result.'</span></p>';
							$res .='<h5>'.lang('text6').' :</h5>
						    		 <p>'.$sql[0]->symbol.' '.$total_payment.'</p>';
						    	$res.='</div>			
						    </div>
						   </div>						   
						   <hr>';        						
						   $res .=$this->m_public->list_product(array('A.id_order'=>$id_order));
						   $x = array('symbol'=>$sql[0]->symbol,'total_product'=>$total_product,
						   		'rate_tax'=>$sql[0]->rate_tax,'amount_tax'=>$amount_tax,
						   		'total_cost_shipp'=>$total_cost_shipp,'total_payment'=>$total_payment,
						   		'notes'=>$sql[0]->notes,'total_qty'=>$sql[0]->total_qty
						   );
						   $res .=$this->m_public->summary_product($x);			
			$res .='<div class="xlg-margin"></div>
					<a href="'.base_url(FOMODULE).'" class="btn btn-custom-2">'.lang('continueshopp').'</a>';
					
		}			
		
		return $res;
	}
	function payment_info($code){
		$res='';
		if ($code == 8801){
			$res .='<div class="input-desc-box" style="background:#dcdcdc">
							   <div class="row">
							    	<div class="col-md-12">
							    	<div style="text-align:center">
							             <h3>'.lang('tf1').'</h3>
						    			 <h2>'.lang('tf2').'</h2>
							          	 <h5>'.lang('tf3').'</h5>
										<h5>'.lang('tf4').'</h5>
										<h5>'.lang('tf5').' <b>"'.lang('tf6').'"</b>.</h5>
						    		</div>
							    	</div>
							   </div>
						   </div>';
		}else if ($code == 8802){
			$res .='<div class="input-desc-box" style="background:#dcdcdc">
						<div class="row">
							<div class="col-md-12">
							   <div style="text-align:center">
							       <h3>'.lang('tf7').'</h3>	
									<h5>'.lang('tf3').'</h5>
									<div class="sm-margin"></div>
									<h3>'.lang('tf8').' : </h3>					    		
							        <h5>'.lang('tf9').'</h5>
									<h5>'.lang('tf10').'</h5>
									<h5>'.lang('tf11').'</h5>
						      </div>
							</div>
						</div>
					</div>';
		}
		return $res;
	}
	function maintenance(){
		if ($this->session->userdata('site_maintenance') != ''){
			redirect(FOMODULE.'/maintenance/index');			
		}
	}
	function list_product($where,$cart=false){
		$res='';
		$res .='<div class="row">
        			<div class="col-md-12">
						<header class="content-title">
							<h5>'.lang('text7').'</h5>
						</header>
        				<div class="xs-margin"></div>
        				<div class="row">
        					<div class="col-md-12 table-responsive">
        						<table class="table cart-table">
        						<thead>
        							<tr>
										<th class="table-title">'.lang('prodname').'</th>
										<th class="table-title">'.lang('unitprc').'</th>
										<th class="table-title">'.lang('prodqty').'</th>
										<th class="table-title">'.lang('subtot').'</th>
        							</tr>
        						</thead>
								<tbody>';
						$detail = $this->m_client->get_order_detail($where,$cart);
						if (count($detail) > 0){
							foreach ($detail as $row){
								$permalink = base_url(FOMODULE.'/'.$row->permalink.'-'.$row->id_product.'.'.$row->id_category_level1.'.html');
								$unit_price = ($row->iso_code == 'IDR') ? number_format($row->unit_price,0,'.','.') : $row->unit_price;
								$subtotal_price = ($row->iso_code == 'IDR') ? number_format($row->total_price,0,'.','.') : $row->total_price;
								$res .='<tr>';
								$res .='<td class="item-name-col">
											<figure>
												<a href="'.$permalink.'"><img src="'.base_url().'assets/images/product/'.image($row->image_name).'" alt="'.$row->name.'"></a>
											</figure>
											<header class="item-name"><a href="'.$permalink.'">'.$row->name.'</a></header>
											<ul>
												<li>'.$row->name_group.': '.$row->name_attribute.'</li>
											</ul>
										</td>';
								$res .='<td class="item-price-col"><span class="item-price-special">'.$row->symbol.' '.$unit_price.'</span></td>';
								$res .='<td class="item-price-col"><span class="item-price-special">'.$row->product_qty.'</span></td>';
								$res .='<td class="item-total-col"><span class="item-price-special">'.$row->symbol.' '.$subtotal_price.'</span></td>';
								$res.='</tr>';
							}			
						}
						$res.='</tbody>
							  </table>
        					</div>
        				</div>
        				<div class="lg-margin"></div>
					</div>
				</div>';	
		
		return $res;
	}
	function summary_product($x){
		$res='';
		$res .=' <div class="row">
						<div class="col-md-6 col-sm-12 col-xs-12">
			        		<table class="table total-table">
			        			<tbody>									
			        				<tr>
			        					<td class="total-table-title">'.lang('subtot').':</td>
			        					<td>'.$x['symbol'].' '.$x['total_product'].'</td>
			        				</tr>';
		
						if ($x['rate_tax'] > 0){
							$res.='<tr>
									<td class="total-table-title">'.lang('tax').' '.formatnum($x['rate_tax']).'%:</td>
									<td>'.$x['symbol'].' '.$x['amount_tax'].'</span></td>
								  </tr>';
						}
		
							$res.='<tr>
			        					<td class="total-table-title">'.lang('costshipp').':</td>
			        					<td>'.$x['symbol'].' '.$x['total_cost_shipp'].'</td>
			        				</tr>
			        			</tbody>
			       		 		<tfoot>
			        				<tr>
										<td>'.lang('total').':</td>
										<td>'.$x['symbol'].' '.$x['total_payment'].'</td>
			        				</tr>
			        			</tfoot>
			        		</table>
			       		 </div>';
							
				$res .='<div class="col-md-6 col-sm-12 col-xs-12">
							<div class="md-margin visible-xs"></div>
								<div class="input-desc-box">
									<h5>'.lang('totqty').': '.$x['total_qty'].'</h5>								
								</div>
								<div class="input-desc-box">
									<h5>'.lang('text8').' : </h5>
									<p>'.$x['notes'].'</p>
								</div>
						</div>';
		$res .='</div>';
		return $res;
	}
	function order_status_log($id_order){
		$res='';
		$sql = $this->m_client->get_status_log(array('A.id_order'=>$id_order));
		$res.='<div class="xs-margin"></div>
                 <h5>Status Log</h5>
                   <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
						<div class="list-group">';
						   if (count($sql) > 0){
						   		foreach ($sql as $row){
						   			$addby = ($row->add_by == 'Buyer' || $row->add_by == 'System') ? $row->add_by : 'Seller';
						   			$res .='<a href="javascript:void(0)" class="list-group-item">
						   						<h5>'.$addby.'</h5>
							   					<i class="fa fa-clock-o"></i> '.short_date_time($row->date_add_status).'
							   					<p>
							   						<span class="'.$row->label_color.'">'.$row->name_status.'</span>
							   					</p>
							   					<p>'.$row->notes.'</p>
						   					</a>';
						   		}
						   }
				 $res.='</div>                                   
					</div>
             </div>';
				 return $res;
	}
	
}
