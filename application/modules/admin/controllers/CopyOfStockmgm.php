<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Stockmgm extends CI_Controller{
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
		$this->table = 'tb_stock_available';
		$this->datenow = date('Y-m-d H:i:s');
		$this->addby = $this->session->userdata('first_name');
		$this->access_code = 'STMGM';
		$this->id_employee = $this->session->userdata('id_employee');
		
	}	
	function index(){
		$this->m_admin->sess_login();
		$priv = $this->m_admin->get_priv($this->access_code,'view');
		$body= (empty($priv)) ? 'stock/vw_stock_management' : $priv['error'];
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];
		links(MODULE.'/'.$this->class);
		url_sess(base_url(MODULE.'/'.$this->class));//link for menu active
		$data['page_title'] = 'Data '.__CLASS__;
		$data['body'] =  $body;
		$data['class'] = $this->class;		
		$this->load->view('vw_header',$data);
	}
	function column(){
		$field_array = array(
			0 => 'A.date_add',
			1 => 'A.id_product',
			2 => 'A.name',
			3 => 'B.qty_available',			
			4 => 'A.add_by',
			5 => 'A.date_add'					
		);
		return $field_array;
	}
	
	function get_records(){
		$output = array();		
		//load datatable
		$this->m_admin->datatable();
		$total = count($this->m_admin->get_stock_mgm());
		$output['draw'] = $_REQUEST['draw'];
		$output['csrf_token'] = csrf_token()['hash'];//reload hash token diferent
		$output['recordsTotal']= $output['recordsFiltered'] = $total;	
		//date filter value already set index row column
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		$date_to = $_REQUEST['columns'][5]['search']['value'];
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$query = $this->m_admin->get_stock_mgm('',$this->column());
		$this->m_admin->range_date($this->column(),$date_from,$date_to);
		$total = count($this->m_admin->get_stock_mgm());
		$output['recordsFiltered'] = $total;		
		$output['data'] = array();
		$no = $_REQUEST['start'] + 1;
		foreach ($query as $row){
			$actions = '<a class="btn btn-xs btn-info" href="'.base_url(MODULE.'/'.$this->class.'/form/'.$row->id_product).'" title="Management Stock" data-rel="tooltip" data-placement="top"><i class="glyphicon glyphicon-move"></i></a>';						
			$total = '<span class="badge badge-danger">N/A</span>';
			if (isset($row->total)) {
				$total = ($row->total > 0) ? '<span class="badge badge-success">'.$row->total.'</span>' : '<span class="badge badge-warning">'.$row->total.'</span>';
			}
			$output['data'][] = array(
					$no,
					$row->id_product,
					$row->name,
					$total,						
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
		links(MODULE.'/'.$this->class.'/form/'.$id);
			$action = 'edit';
			$data['page_title'] = 'Stock Management';
			$sql = $this->m_admin->get_stock_mgm(array('A.id_product'=>$id));			
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}
		$priv = $this->m_admin->get_priv($this->access_code,$action);
		$body= (empty($priv)) ? 'stock/vw_crud_management' : $priv['error'];
		$cek = $this->m_admin->get_product_attribute(array('id_product'=>$id));
		if (count($cek) > 0){
			//jika ada attribute
			$data['sql'] = $this->m_admin->get_stock_available_attr(array('A.id_product'=>$id));
		}else{
			//jika tidak ada attribute
			$data['sql'] = $this->m_admin->get_stock_available_nonattr(array('B.id_product'=>$id));
		}
		
		$data['rowimage'] = $this->m_admin->get_table('tb_product_image','image_name',array('id_product'=>$id,'deleted'=>0));
		$data['movement'] = $this->m_admin->get_table('tb_label_movement');
		$data['body'] = $body;
		$data['class'] = $this->class; 		
		$data['notif']= (empty($priv)) ? '' : $priv['notif'];			
		$this->load->view('vw_header',$data);
	}
	function proses(){
		$this->db->trans_start();	
		$id_label_movement = $this->input->post('movement',true);
		$id_label_movement_detail = $this->input->post('labelmove',true);
		$id_product = $this->input->post('id_product',true);
		$nameproduct = $this->input->post('productname',true);
		$attribute = $this->input->post('attribute',true);
		
		//deklare insert web_stock_movement
		$insertmove['id_product'] = $id_product;
		$insertmove['product_name'] = $nameproduct;
		$insertmove['id_label_movement']  = $id_label_movement;
		$insertmove['id_label_movement_detail'] = $id_label_movement_detail;
		$insertmove['id_employee'] = $this->id_employee;
		$insertmove['date_add'] = $this->datenow;
		$insertmove['add_by'] = $this->addby;
		$insertmove['code_move'] = 'MGM';
		
		if ($attribute == 1){
			//jika mempunyai attribute
			$chx = $this->input->post('checkattr',true);
			if ($chx != null){
				$datax = $this->input->post('dataattr',true);
				foreach ($chx as $cx){
					$table = $datax[$cx];
					$qty_insert = isset($table['qty_available']) ? $table['qty_available'] : '';
					$id_warehouse = isset($table['id_warehouse']) ? $table['id_warehouse'] : '';
					$id_warehouse_location = isset($table['id_warehouse_location']) ? $table['id_warehouse_location'] : '';
					$id_product_attribute = $cx;
					$where  = array('id_product'=>$id_product,
									'id_product_attribute'=>$id_product_attribute,
									'id_warehouse'=>$id_warehouse,
									'id_warehouse_location'=>$id_warehouse_location);
					//cek stock sesuai warehose & location
					$cek = $this->m_admin->cek_stock_available($where);
					
					//define stock movement
					$insertmove['qty_move'] = $qty_insert;//qty yang dimove
					$insertmove['id_product_attribute'] = $id_product_attribute;
									
					//mendatpatkan nilai attribute
					$get_attribute = $this->m_admin->get_product_attribute(array('A.id_product_attribute'=>$id_product_attribute));
					if ($qty_insert != "" && $id_warehouse != "" && $id_warehouse_location != ""){						
						if ($id_label_movement == 2)//mengurangi stock
						{
							$insertmove['id_warehouse'] = $id_warehouse;
							$insertmove['id_warehouse_location'] = $id_warehouse_location;
							//jika barang sudah input di tb_stock_available
							if (count($cek) > 0){		
								//qty_stock - qty_insert
									$this->db->set('qty_available','qty_available-'.$qty_insert,false);
									//qty default dikurang jika hanya ada kesalahan input & Penyesuain Stok
									if ($id_label_movement_detail == 9 || $id_label_movement_detail == 16)
									{
										$this->db->set('qty_default','qty_default-'.$qty_insert,false);
									}
									$this->db->where($where);
									$res = $this->db->update('tb_stock_available');
									$res = $this->m_admin->insertdata('tb_stock_movement',$insertmove);
									$alert = 'Reduce stock available successfull!';
									$error = array();
							}else{
								//jika barang N/A / belum input tb_stock_available							
								$error[] = 'Product '.$nameproduct.' '.$get_attribute[0]->name_group.' '.$get_attribute[0]->name.' NOT AVAILABLE IN WAREHOUSE!';							
							}
							
						}else if ($id_label_movement == 1){
							//tambah stock
							$insertmove['id_warehouse'] = $id_warehouse;
							$insertmove['id_warehouse_location'] = $id_warehouse_location;
							if (count($cek) > 0){
								//jika sudah ada distock available
								$this->db->set('qty_available','qty_available+'.$qty_insert,false);
								//kesalahan input OR Penyesuaian Stock OR Tambah Stock
								if ($id_label_movement_detail == 17 || $id_label_movement_detail == 10 || $id_label_movement_detail == 1)
								{
									$this->db->set('qty_default','qty_default+'.$qty_insert,false);
								}
								$this->db->where($where);
								$res = $this->db->update('tb_stock_available');
								$res = $this->m_admin->insertdata('tb_stock_movement',$insertmove);
								$alert = 'Add stock available successfull!';
								$error = array();							
							}else{
								//jika belum ada stock
								//cek apakah stock tersimpan dalam warehouse yg sama
								$wherecekwh  = array('id_product'=>$id_product,
													 'id_product_attribute'=>$id_product_attribute,
													 'id_warehouse'=>$id_warehouse);
								$cekwh = $this->m_admin->cek_stock_available($wherecekwh);
								if (count($cekwh) < 1){
									//insert new web_stock_available and new warehouse									
									$insertstock['id_product'] = $id_product;
									$insertstock['id_product_attribute'] = $id_product_attribute;
									$insertstock['qty_default'] = $qty_insert;
									$insertstock['qty_available'] = $qty_insert;
									$insertstock['id_warehouse'] = $id_warehouse;
									$insertstock['id_warehouse_location'] = $id_warehouse_location;																		
									$res = $this->m_admin->insertdata('tb_stock_available',$insertstock);
									$res = $this->m_admin->insertdata('tb_stock_movement',$insertmove);
									$alert = 'Insert new stock available successfull';
									$error = array();
								}else{
									/*
									 * tidak dapat input stok
									 * jika produk diinput pada warehouse yg sama dan lokasi berbeda
									 */
									$error[] = 'Product '.$nameproduct.' '.$get_attribute[0]->name_group.' '.$get_attribute[0]->name.' can not insert stock in same warehouse and diferent warehouse location!';
								}
							}
							
						}else{
							//transfer stock							
							//jika stock tersedia di warehoue yg mentransfer
							if (count($cek) > 0){
								//melakukan cek warehouse yg akan ditransfer
								$id_warehouse_to = isset($table['id_warehouse_to']) ? $table['id_warehouse_to'] : '';
								$id_warehouse_location_to = isset($table['id_warehouse_location_to']) ? $table['id_warehouse_location_to'] : '';
								$wheretransfer = array('id_product'=>$id_product,
													   'id_product_attribute'=>$id_product_attribute,
														'id_warehouse'=>$id_warehouse_to,
														'id_warehouse_location'=>$id_warehouse_location_to);
								$cek = $this->m_admin->cek_stock_available($wheretransfer);
								
								//define stock movement
								$insertmove['id_warehouse'] = $id_warehouse_to;
								$insertmove['id_warehouse_location'] = $id_warehouse_location_to;							
								
								//jika sudah ada stock di warehouse To, maka melakukan update tambah qty
								if (count($cek) > 0){
									//mengurangi stock warehouse yang mentransfer
									$this->db->set('qty_available','qty_available-'.$qty_insert,false);
									$this->db->set('qty_default','qty_default-'.$qty_insert,false);
									$this->db->where($where);
									$res = $this->db->update('tb_stock_available');
									
									//menambah stock warehouse yang ditransfer
									$this->db->set('qty_available','qty_available+'.$qty_insert,false);
									$this->db->set('qty_default','qty_default+'.$qty_insert,false);
									$this->db->where($wheretransfer);
									$res = $this->db->update('tb_stock_available');
									$res = $this->m_admin->insertdata('tb_stock_movement',$insertmove);
									$alert = 'Add stock available from transfer successfull!';
									$msg = array();
								}else{
									//jika belum ada di warehouse To, maka insert stock baru									
									$wherecekwh = array('id_product'=>$id_product,
														'id_product_attribute'=>$id_product_attribute,
														'id_warehouse'=>$id_warehouse_to);
									$cek = $this->m_admin->cek_stock_available($wherecekwh);
									if (count($cek) < 1){
										//jika product tidak ada diwarehouse to dan location yg berbeda
										//mengurangi stock warehouse yang mentransfer
										$this->db->set('qty_available','qty_available-'.$qty_insert,false);
										$this->db->set('qty_default','qty_default-'.$qty_insert,false);
										$this->db->where($where);
										$res = $this->db->update('tb_stock_available');
										
										//insert stock baru di web_stock_available dengan warehouse dan location yang berbeda
										$insertstock['id_product'] = $id_product;
										$insertstock['id_product_attribute'] = $id_product_attribute;
										$insertstock['qty_default'] = $qty_insert;
										$insertstock['qty_available'] = $qty_insert;
										$insertstock['id_warehouse'] = $id_warehouse_to;
										$insertstock['id_warehouse_location'] = $id_warehouse_location_to;
										$res = $this->m_admin->insertdata('tb_stock_available',$insertstock);
										$res = $this->m_admin->insertdata('tb_stock_movement',$insertmove);
										$alert = 'Insert stock new available from transfer successfull!';
										$msg = array();
									}else{
										/*
										 * tidak dapat transfer stok
										* jika produk diinput pada warehouse yg sama dan lokasi berbeda
										*/
										$error[] = 'Product '.$nameproduct.' '.$get_attribute[0]->name_group.' '.$get_attribute[0]->name.' can not transfer stock in same warehouse and diferent warehouse location!';
									}
								}
							}else{
								//jika stock tidak tersedia di warehouse yg dipilih
								$error[] = 'Product '.$nameproduct.' '.$get_attribute[0]->name_group.' '.$get_attribute[0]->name.' NOT AVAILABLE IN WAREHOUSE!';
							}
						}
					}else{
						//jika data not complete
						//data not complete
						$error[] = 'Product '.$nameproduct.' '.$get_attribute[0]->name_group.' '.$get_attribute[0]->name.' data not complete!';
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
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>'Table Stock Not Checked!','type'=>'error'));
				return false;
			}			
			
		}else{
			//jika tidak mempunyai attribute			
			$id_warehouse = $this->input->post('warehouse',true);
			$id_warehouse_location = $this->input->post('location',true);
			$qty_insert = $this->input->post('qty_input',true);		
			//cek stock
			$where = array('id_product'=>$id_product,
						   'id_warehouse'=>$id_warehouse,
						   'id_warehouse_location'=>$id_warehouse_location);
			$cek = $this->m_admin->cek_stock_available($where);
			
			
			if ($id_label_movement == 2){		
				//mengurangi stock
				if (count($cek) > 0)
				{
					//jika stok ada
					//qty_stock - qty_insert
					$this->db->set('qty_available','qty_available-'.$qty_insert,false);
					//qty default dikurang jika hanya ada kesalahan input & Penyesuain Stok
					if ($id_label_movement_detail == 9 || $id_label_movement_detail == 16)
					{
						$this->db->set('qty_default','qty_default-'.$qty_insert,false);
					}
					//update stock available
					$this->db->where($where);
					$res = $this->db->update('tb_stock_available');
					$alert = 'Reduce stock available successfull!';
				}else{
					//jika barang N/A / belum input tb_stock_available
					$error = 'Product '.$nameproduct.' NOT AVAILABLE IN STOCK!';
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$error,'type'=>'error'));
					return false;
				}
			}else if ($id_label_movement == 1){
				//jika tambah stock
				if (count($cek) > 0)
				{
					//qty_stock + qty_insert
					$this->db->set('qty_available','qty_available+'.$qty_insert,false);
					//kesalahan input OR Penyesuaian Stock OR Tambah Stock
					if ($id_label_movement_detail == 17 || $id_label_movement_detail == 10 || $id_label_movement_detail == 1)
					{
						//$update['qty_default'] = $qty_default + $qty_insert;
						$this->db->set('qty_default','qty_default+'.$qty_insert,false);
					}
					//update stock available
					$this->db->where($where);
					$res = $this->db->update('tb_stock_available');
					$alert = 'Add stock available successfull!';
					
				}else{
					$wherecekwh  = array('id_product'=>$id_product,										 
										 'id_warehouse'=>$id_warehouse);
					$cekwh = $this->m_admin->cek_stock_available($wherecekwh);
					if (count($cekwh) < 1){
						//deklare insert web_stock_available
						$insertstock['id_product'] = $id_product;
						$insertstock['id_warehouse'] = $id_warehouse;
						$insertstock['id_warehouse_location'] = $id_warehouse_location;
						$insertstock['qty_available'] = $qty_insert;
						$insertstock['qty_default'] = $qty_insert;
						//insert new web_stock_available and new warehouse
						$res = $this->m_admin->insertdata('tb_stock_available',$insertstock);
						$alert = 'Insert new stock available successfull';
					}else{
						$error = 'Product '.$nameproduct.' Can not insert stock in same warehouse and diferent warehouse location!';
						echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$error,'type'=>'error'));
						return false;
					}
				}
			}else{
				//transfer stock
			}
			
			//deklare insert stock move
			$insertmove['qty_move'] = $qty_insert;
			$insertmove['id_warehouse'] = $id_warehouse;
			$insertmove['id_warehouse_location'] = $id_warehouse_location;
			$res = $this->m_admin->insertdata('tb_stock_movement',$insertmove);
			
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
		}		
	}
	function show_label($id_product){
		$element="";
		$id = $this->input->post('value',true);
		$cek = $this->m_admin->get_product_attribute(array('id_product'=>$id_product));
		$data['id_label_movement'] = $id;
		if (count($cek) > 0){
			//jika attribute tersedia
			$data['sql'] = $cek;			
			$element .= $this->load->view('stock/vw_stock_attribute',$data,true);
		}else{
			//jika tidak tersedia						
			$element .= $this->load->view('stock/vw_stock_nonattribute',$data,true);
		}
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$element));
	}
	function choose_warehouse(){
		$id = $this->input->post('value',true);
		$element = $this->m_content->chosen_warehouse($id);
		echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'element'=>$element));
	}
	function delete_stock(){
		$priv = $this->m_admin->get_priv($this->access_code,'delete');
		if (empty($priv)){
			$id = $this->input->post('value',true);
			$cek = $this->m_admin->cek_stock_available(array('id_stock_available'=>$id));
			if (count($cek) > 0){
				$res = $this->m_admin->deletedata('tb_stock_available',array('id_stock_available'=>$id));
				if ($res > 0){
					$msg = 'Delete Stock successfull';
					echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>0,'msg'=>$msg,'table'=>'df'));
				}
			}else{
				echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>'Can not delete, stock not available!'));
			}
		}else{
			echo json_encode(array('csrf_token'=>csrf_token()['hash'],'error'=>1,'msg'=>$priv['notif']));
		}
	}
}
