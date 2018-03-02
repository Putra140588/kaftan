<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Product extends CI_Controller{
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
		
		$this->load->library('permalink');		
		$this->class = strtolower(__CLASS__);
		$this->sess_permalink = $this->session->userdata('permalink_sess');
		$this->date_now = date('Y-m-d H:i:s');		
		$this->id_order_cart = $this->m_client->id_order_cart();//create id_order_cart
		$this->id_customer = $this->session->userdata('id_customer');
		$this->isocode = $this->session->userdata('iso_code_fo');
		$this->m_public->maintenance();
	}
	function select_attribute($id_product){		
		$id_product_attribute = $this->input->post('value',true);
		$sql = $this->m_client->get_product(array('A.id_product'=>$id_product));
		$data = $this->m_public->price_product($id_product,$sql[0]->final_price,$id_product_attribute);				
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'select_attribute'=>true,'error'=>0,'element'=>$data));
	}
	function download_attach($file_upload)
	{
		$path = file_get_contents("assets/attachment/".$file_upload);
		force_download($file_upload,$path);	
	}
	function add_cart(){
		$this->db->trans_start();
		$content='';
		$list_cart='';
		$msg='';
		$id_product = $this->input->post('id_product',true);
		$id_product_attribute = ($this->input->post('productattr') != null) ? $this->input->post('productattr',true) : 0;
		$qty = $this->input->post('quantity',true);
		
		$id_warehouse = $this->m_client->id_warehouse();//multiple array by branch default
		$where = array('id_product'=>$id_product,'id_product_attribute'=>$id_product_attribute);
		$stock = $this->m_client->get_stock($where,$id_warehouse);
		if (count($stock) > 0){
			if ($qty > 0){
				if ($stock[0]->qty_available > 0){
					//order
				}else{
					//preorder
				}
				
				/*				
				$in['id_customer'] = $this->id_customer;
				$in['date_add'] = $this->date_now;
				$in['date_update'] = $this->date_now;
				$cek1 = $this->m_client->get_table('tb_order_cart','id_order_cart',array('id_order_cart'=>$this->id_order_cart));
				if (count($cek1) > 0){
					$res = $this->m_client->editdata('tb_order_cart',$in,array('id_order_cart'=>$this->id_order_cart));
				}else{
					$in['id_order_cart'] = $this->id_order_cart;
					$res = $this->m_client->insertdata('tb_order_cart',$in);
				}		
				*/								
				
					//get product desc
					$sql = $this->m_client->get_product(array('A.id_product'=>$id_product));					
					if (count($sql) > 0){
						$x = $this->m_public->generate_price($id_product,$sql[0]->final_price,$id_product_attribute);
						$unit_price = $this->m_public->convert_price($x['total'])['total'];
						$impact_price = $this->m_public->convert_price($x['impact_price'])['total'];
						$base_price =  $this->m_public->convert_price($sql[0]->base_price)['total'];
						$final_price =  $this->m_public->convert_price($sql[0]->final_price)['total'];
						$sp_price =  $this->m_public->convert_price($x['price_sp'])['total'];
						$disc_ampunt =  $this->m_public->convert_price($x['amount_disc'])['total'];
						$disc_value = $x['disc'];
						$total_price = $unit_price * $qty;
						$widht = $sql[0]->width;
						$weight = $sql[0]->weight;
						$height = $sql[0]->height;
						$length = $sql[0]->length;
						
						//rumus hitung berat JNE
						$total_weight = $weight * $qty;
						
						//rumus hitung volume jne
						$tot_volume = ($widht * $height * $length) / 6000;
						$total_volume = $tot_volume * $qty;										
						
						$where1 = array('A.id_order_cart'=>$this->id_order_cart,'A.id_product'=>$id_product,'A.id_product_attribute'=>$id_product_attribute);
						$cek2 = $this->m_client->get_cart_content($where1);
						//jika produk sudah ada dicart maka update qty
						if (count($cek2) > 0){
							$where2 = array('id_order_cart'=>$this->id_order_cart,'id_product'=>$id_product,'id_product_attribute'=>$id_product_attribute);
							$this->db->set('product_qty','product_qty+'.$qty,false);						
							$this->db->set('total_price','total_price+'.$total_price,false);
							$this->db->set('total_volume','total_volume+'.$total_volume,false);
							$this->db->set('total_weight','total_weight+'.$total_weight,false);
							$this->db->where($where2);
							$res = $this->db->update('tb_order_cart_detail');						
							//$res = $this->m_client->editdata('tb_order_cart_detail',$det,$where2);
						}else{
							$id_order_cart_det = $this->m_client->get_rand_id('D');
							$det['id_order_cart_detail'] = $id_order_cart_det;
							$det['id_order_cart'] = $this->id_order_cart;
							$det['id_product'] = $id_product;
							$det['id_product_attribute'] = $id_product_attribute;
							$det['product_qty'] = $qty;
							$det['unit_price'] = $unit_price;
							$det['impact_price'] = $impact_price;
							$det['total_price'] = $total_price;
							$det['base_price'] = $base_price;
							$det['final_price'] = $final_price;
							$det['sp_price'] = $sp_price;
							$det['disc_value'] = $disc_value;
							$det['disc_amount'] = $disc_ampunt;
							$det['weight'] = $weight;
							$det['width'] = $widht;
							$det['height'] = $height;
							$det['length'] = $length;
							$det['total_volume'] = $total_volume;
							$det['total_weight'] = $total_weight;
							$det['date_add'] = $this->date_now;
							$det['iso_code'] = $this->isocode;
							$res = $this->m_client->insertdata('tb_order_cart_detail',$det);
						}					
						if ($this->db->trans_status() == false){
							$this->db->trans_rollback();
						}else{
							$this->db->trans_complete();
							if ($res){
								//berhasil input ke cart
								$msg = lang('incart');
								$content.= $this->modal_success($msg);
							}
						}
										
					}else{
						//jika prdouk tidak ditemukan
						$content.=$this->modal_warning('Produk not found!');
					}				
			}else{	
				//jumlah yg dipesen kosong			
				$content.=$this->modal_warning(lang('noqty'));
			}						
		}else{						
			//belum input stok management
			$content.=$this->modal_warning(lang('stocknull'));
		}		
		$list_cart.= $this->m_public->top_list_cart();
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'add_cart'=>true,'content'=>$content,'list_cart'=>$list_cart));
	}
	function sess_destroy(){
		$this->session->sess_destroy();
	}
	function modal_warning($msg){
		$data['icon'] = 'fa fa-exclamation-circle';
		$data['notif'] = lang('warning');
		$data['coment'] = $msg;
		return $this->load->view('v_modal_notif',$data,true);
	}
	function modal_success($msg){
		$data['icon'] = 'fa fa-check-square-o';
		$data['notif'] = lang('success');
		$data['coment'] = $msg;
		return $this->load->view('v_modal_notif',$data,true);
	}
	function delete_cart(){
		$content='';
		$id_order_cart_detail = $this->input->post('value',true);
		$res = $this->m_client->deletedata('tb_order_cart_detail',array('id_order_cart_detail'=>$id_order_cart_detail,'id_order_cart'=>$this->id_order_cart));
		if ($res){
			$content.= $this->m_public->top_list_cart();
			$msg = 'Success delete item cart';
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'content'=>$content));
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>'Error delete!'));
		}
	}
}