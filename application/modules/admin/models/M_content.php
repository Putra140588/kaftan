<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_content extends CI_Model{
	function load_choosen(){
		$res ='';
		$res .='<script>load_choosen();</script>';
		return $res;
	}
	function chosen_helper($id_helper,$id_language=''){
		$res ="";
		$select ="";
		$sql = $this->m_admin->get_helper(array('A.id_language'=>$id_language));
		$res .='<select class="chosen-select form-control" name="namehelper" data-placeholder="Choose a name helper.." required>
					<option value=""/>';
		foreach ($sql as $row){
			if (!empty($id_helper)){$select = ($id_helper == $row->id_helper) ? 'selected' : '';}
			$res .='<option value="'.$row->id_helper.'" '.$select.'>'.$row->name_helper.'</option>';
		}
		$res .='</select>';
		return $res;
	}
	function chosen_manufacture($id_manufacture,$id_product=''){		
		$res ="";
		$select ="";
		$manufacture = $this->m_admin->get_manufacture();		
		$res .='<select class="form-control" name="manufacture" data-placeholder="Choose a manufacture" onchange="ajaxcall(\''.base_url(MODULE.'/product/show_attachment/'.$id_product).'\',this.value,\'attachments\')"  required>
					<option value="" selected disabled>Choose a manufacture</option>';
					foreach ($manufacture as $row){
						if (!empty($id_manufacture)){$select = ($id_manufacture == $row->id_manufacture) ? 'selected' : '';}
							$res .='<option value="'.$row->id_manufacture.'" '.$select.'>'.$row->name.' | '.$row->name_language.'</option>';
					}
		$res .='</select>';
		return $res;
	}
	function chosen_supplier($id_supplier){
		$res ="";
		$select ="";
		$supplier = $this->m_admin->get_table('tb_supplier',array('id_supplier','name_supplier'),array('deleted'=>0));
		$res .='<select class="form-control" name="supplier" data-placeholder="Choose a supplier" required>
					<option value="" selected disabled>Choose a supplier</option>';
		foreach ($supplier as $row){
			if (!empty($id_supplier)){$select = ($id_supplier == $row->id_supplier) ? 'selected' : '';}
			$res .='<option value="'.$row->id_supplier.'" '.$select.'>'.$row->name_supplier.'</option>';
		}
		$res .='</select>';
		return $res;
	}
	function chosen_customer($id_group){
		$res ="";
		$select ="";
		$sql = $this->m_admin->get_customer_group(array('H.id_group'=>$id_group));
		$res .='<select class="chosen-select form-control" name="customer" data-placeholder="Choose a customer" required>
					<option value="all">--ALL CUSTOMER--</option>';
		foreach ($sql as $row){		
			$res .='<option value="'.$row->id_customer.'">'.$row->first_name.' '.$row->last_name.'</option>';
		}
		$res .='</select>';
		return $res;
	}
	function chosen_province($id,$id_province='',$class='',$id_form){
		$res ="";
		$select ="";
		//jika bukan halaman districts
		$onchange = ($class != 'districts') ? 'onchange="ajaxcall(\''.base_url(MODULE.'/'.$class.'/show_city/'.$id_form).'\',this.value,\'city'.$id_form.'\')"' : '';
		$sql = $this->m_admin->get_table('tb_province',array('id_province','province_name'),array('id_country'=>$id,'deleted'=>0));
		$res .='<select class="chosen-select form-control" name="province" data-placeholder="Choose a province..."  '.$onchange.' required>
					<option value="" />';
		foreach ($sql as $row){
			if (!empty($id_province)){$select = ($id_province == $row->id_province) ? 'selected' : '';}
			$res .='<option value="'.$row->id_province.'" '.$select.'>'.$row->province_name.'</option>';
		}
		$res .='</select>';
		
		return $res;
	}
	function chosen_city($id,$id_cities='',$class='',$id_form){
		$res ="";
		$select ="";
		//jika bukan halaman districts
		$onchange = ($class != 'districts') ? 'onchange="ajaxcall(\''.base_url(MODULE.'/'.$class.'/show_districts/'.$id_form).'\',this.value,\'districts'.$id_form.'\')"' : '';
		$sql = $this->m_admin->get_table('tb_cities',array('id_cities','cities_name'),array('id_province'=>$id,'deleted'=>0));
		$res .='<select class="chosen-select form-control" name="city" data-placeholder="Choose a city..."  '.$onchange.' required>
					<option value="" />';
		foreach ($sql as $row){
			if (!empty($id_cities)){$select = ($id_cities == $row->id_cities) ? 'selected' : '';}
			$res .='<option value="'.$row->id_cities.'" '.$select.'>'.$row->cities_name.'</option>';
		}
		$res .='</select>';		
		return $res;
	}
	function chosen_districts($id,$id_districts){
		$res ="";
		$select ="";
		$sql = $this->m_admin->get_table('tb_districts',array('id_districts','districts_name'),array('id_cities'=>$id,'deleted'=>0));
		$res .='<select class="chosen-select form-control" name="districts" data-placeholder="Choose a districts" required>
					<option value="" />';
		foreach ($sql as $row){
			if (!empty($id_districts)){$select = ($id_districts == $row->id_districts) ? 'selected' : '';}
			$res .='<option value="'.$row->id_districts.'" '.$select.'>'.$row->districts_name.'</option>';
		}
		$res .='</select>';
		
		return $res;
	}
	function chosen_warehouse($id){
		$res ="";		
		$wh = $this->m_admin->get_table('tb_warehouse_location','*',array('id_warehouse'=>$id,'deleted'=>0));		
		foreach ($wh as $row){			
			$res .='<option value="'.$row->id_warehouse_location.'">'.$row->name_location.'</option>';
		}		
		return $res;
	}
	function check_button($name,$val=''){
		$res = "";
		$select="";		
		$postname = strtolower(str_replace(" ", "_", $name));
		$res .= '<label class="col-sm-4 control-label">'.$name.'</label>
					<div class="col-sm-5">
						<label>';
							$select = ($val == 1) ? 'checked' : '';
							$res .='<input class="ace ace-switch ace-switch-5" name="'.$postname.'" '.$select.' type="checkbox">
							<span class="lbl"></span>
						</label>
					</div>';
		return $res;
	}
	function chosen_category($id='',$where='',$name='',$required=""){
		$res ="";
		$select ="";
		$category = $this->m_admin->get_category($where);
		$res .='<select class="form-control parent" name="'.$name.'" onchange="ajaxcall(\''.base_url(MODULE.'/product/chose_category').'\',this.value,\''.$name.'\')"  '.$required.'>
					<option value="0">Choose category..</option>';
		foreach ($category as $row){
			if (!empty($id)){$select = ($id == $row->id_category) ? 'selected' : '';}
			$res .='<option value="'.$row->id_category.'" '.$select.'>'.$row->name_category.' | '.$row->name_language.'</option>';
		}
		$res .='</select>';
		return $res;
	}
	function table_attachment($id_manufacture,$id_product){
		$res="";
		$active="";
		$sql = $this->m_admin->get_table('tb_attachment','*',array('id_manufacture'=>$id_manufacture,'deleted'=>0));
		if (count($sql) > 0){
			$no=1;
			foreach ($sql as $row){
				$x=array();
				if ($id_product != ''){
					$att = $this->m_admin->get_table('tb_product_attachment','*',array('id_product'=>$id_product));
					foreach ($att as $v){						
						$x[] = $v->id_attachment;
					}
					$active = in_array($row->id_attachment,$x) ? 'checked' : '';
				}
				$res .='<tr><td>'.$no++.'</td>
							<td><center><label><input type="checkbox" '.$active.' class="ace ace-checkbox-2 rowattch" name="checkatch[]" value="'.$row->id_attachment.'"><span class="lbl"></span></label></center></td>
							<td>'.$row->file_name.'</td>
							<td>'.$row->file.'</td>
						</tr>';
			}
		}else{
			$res .='<tr><td colspan=4"><div class="alert alert-warning">Attachments is empty</div></td></tr>';
		}
		return $res;
	}
	function table_video($id="",$val=""){
		$element="";
		$sql = $this->m_admin->get_table('tb_product_video','*',array('id_product'=>$id,'deleted'=>0));
		//jika sudah ada video
		if (count($sql) > 0){
			$i=1;
			foreach ($sql as $row){				
				$element .='<tr><td>'.$i++.'</td>';
				$element .='<td><center><label><input type="checkbox" class="ace ace-checkbox-2 rowvid" name="checkvid[]" value="'.$row->id_product_video.'"><span class="lbl"></span></label></center></td>';
				$element .='<td><input type="text" name="datavid['.$row->id_product_video.'][video_title]" placeholder="Video Title" class="col-sm-12" value="'.$row->video_title.'"></td>						
							<td><input type="text" name="datavid['.$row->id_product_video.'][video_url]" placeholder="Video URL" class="col-sm-12" value="'.$row->video_url.'"></td>						
							<td><select name="datavid['.$row->id_product_video.'][sort]">';
				for ($s=1; $s <= count($sql); $s++){
					$select = ($row->sort == $s) ? 'selected' : '';
					$element .= '<option value="'.$s.'" '.$select.'>'.$s.'</option>';
				}
				$element .='</select></td>';
				$active = ($row->active == 1) ? 'checked' : '';
				$element .='<td>
									 <label>
										 <input name="datavid['.$row->id_product_video.'][active]" '.$active.' class="ace ace-switch ace-switch-2" type="checkbox"/>
										 <span class="lbl"></span>
								     </label>
								   </td>';
				$element .= '<td><a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/product/delete_video').'\',\''.$row->id_product_video.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a></td>';
				$element .='</tr>';
			}
			//jika melakukan penambahan video
			if (!empty($val)){
				$element .= $this->table_video_blank($val,count($sql));
			}
		}else{
			$element .=$this->table_video_blank($val);
		}
		return $element;
	}
	function table_video_blank($val,$count=""){
		$element="";
		if (!empty($val)){
			for ($i=1; $i <= $val; $i++){
				//jika count kosong maka nomor urut dari 1, jika tidak kosong no urut akan meneruskan
				$num = (!empty($count)) ? bcadd($count, $i) : $i;
				$randnum = rand($i,10000000000);//id random untuk memastikan tidak terdaftar di product_image
				$element .='<tr><td>'.$num.'</td>';
				$element .='<td><center><label><input type="checkbox" class="ace ace-checkbox-2 rowvid" name="checkvid[]" value="'.$randnum.'"><span class="lbl"></span></label></center></td>';
				$element .='<td><input type="text" name="datavid['.$randnum.'][video_title]" placeholder="Video Title" class="col-sm-12"></td>						
							<td><input type="text" name="datavid['.$randnum.'][video_url]" placeholder="Video URL" class="col-sm-12"></td>						
							<td><select name="datavid['.$randnum.'][sort]">';
				for ($s=1; $s <= 10; $s++){					
					$element .= '<option value="'.$s.'">'.$s.'</option>';
				}
				$element .='</select></td>';
				
				$element .='<td>
								<label>
									<input name="datavid['.$randnum.'][active]" class="ace ace-switch ace-switch-2" type="checkbox"/>
									<span class="lbl"></span>
								</label>
							</td>';
				$element .= '<td></td>';
				$element .='</tr>';
			}
		}else{
			$element .='<tr><td colspan=7><div class="alert alert-warning">Video Product is empty</div></td>';
		}
		return $element;
	}
	function table_image($id='',$val=''){
		$element="";		
		$img = $this->m_admin->get_table('tb_product_image','*',array('id_product'=>$id,'deleted'=>0));	
		//jika sudah ada image
		if (count($img) > 0){
			$i=1;
			foreach ($img as $row){
				$image = ($row->image_name != "") ? $row->image_name : 'no-image.jpg';
				$element .='<tr><td>'.$i++.'</td>';
				$element .='<td><center><label><input type="checkbox" class="ace ace-checkbox-2 rowimg" name="checkimg[]" value="'.$row->id_product_image.'"><span class="lbl"></span></label></center></td>';
				$element .='<td>			
							<input type="file" class="input-file" name="rowimage['.$row->id_product_image.']"><br>
							<ul class="ace-thumbnails clearfix">
								<li>
								<a href="'.base_url('assets/images/product/'.$image).'" data-rel="colorbox">
									<img src="'.base_url('assets/images/product/'.$image).'" alt="'.$image.'" width="150" height="150">
									<div class="text">
										<div class="inner">'.$image.'</div>
									</div>
								</a>
								</li>
							</ul>			
							</td>
							<td><select name="data['.$row->id_product_image.'][sort]">';
				for ($s=1; $s <=count($img); $s++){
					$select = ($row->sort == $s) ? 'selected' : '';
					$element .= '<option value="'.$s.'" '.$select.'>'.$s.'</option>';
				}
				$element .='</select></td>';
				$active = ($row->active == 1) ? 'checked' : '';
				$element .='<td>
									 <label>
										 <input name="data['.$row->id_product_image.'][active]" '.$active.' class="ace ace-switch ace-switch-2" type="checkbox"/>
										 <span class="lbl"></span>
								     </label>
								   </td>';
				$element .= '<td><a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/product/delete_image').'\',\''.$row->id_product_image.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a></td>';
				$element .='</tr>';
			}
			//jika melakukan penambahan gambar
			if (!empty($val)){
				$element .=$this->table_image_blank($val,count($img));
			}
		}else{
			$element .=$this->table_image_blank($val);
		}
		
		return $element;
	}
	function table_image_blank($val,$count=""){
		$element="";
		if (!empty($val)){
			for ($i=1; $i <= $val; $i++){
				//jika count kosong maka nomor urut dari 1, jika tidak kosong no urut akan meneruskan
				$num = (!empty($count)) ? bcadd($count, $i) : $i;
				$randnum = rand($i,10000000000);//id random untuk memastikan tidak terdaftar di product_image
				$element .='<tr><td>'.$num.'</td>';
				$element .='<td><center><label><input type="checkbox" class="ace ace-checkbox-2 rowimg" name="checkimg[]" value="'.$randnum.'"><span class="lbl"></span></label></center></td>';
				$element .='<td><input type="file" class="input-file" name="rowimage['.$randnum.']"></td>
								<td><select name="data['.$randnum.'][sort]">';
								for ($s=1; $s <=10; $s++){
									$select = ($i == $s) ? 'selected' : '';
									$element .= '<option value="'.$s.'" '.$select.'>'.$s.'</option>';
								}
				$element .='</select></td>';
				$element .='<td>
								<label>
									<input name="data['.$randnum.'][active]" checked class="ace ace-switch ace-switch-2" type="checkbox"/>
									<span class="lbl"></span>
								</label>
							</td><td></td>';
				$element .='</tr>';
			}
		}else{
			$element .='<tr><td colspan=6><div class="alert alert-warning">Image Product is empty</div></td>';
		}
		return $element;
	}
	function table_attribute($id="",$val=""){
		$element="";
		$sql = $this->m_admin->get_table('tb_attribute','*',array('id_attribute_group'=>$id,'deleted'=>0));
		//jika sudah ada video
		if (count($sql) > 0){
			$i=1;
			foreach ($sql as $row){							
				$element .='<tr><td>'.$i.'</td>';
				$element .='<td><center><label><input type="checkbox" class="ace ace-checkbox-2 rowattr" name="checkattr[]" value="'.$row->id_attribute.'"><span class="lbl"></span></label></center></td>';
				$element .='<td><input type="text" name="dataattr['.$row->id_attribute.'][id_attribute]" placeholder="Attribute" class="col-sm-8" value="'.$row->name.'" readonly></td>
							<td><input type="text" name="dataattr['.$row->id_attribute.'][price_impact]" placeholder="'.$_SESSION['symbol'].'" id="impactprice'.$row->id_attribute.'" class="col-sm-8" onkeypress="return decimals(event,this.id)"></td>
							<td><select name="dataattr['.$row->id_attribute.'][sort]">';
				for ($s=1; $s <= count($sql); $s++){
					$select = ($i == $s) ? 'selected' : '';
					$element .= '<option value="'.$s.'" '.$select.'>'.$s.'</option>';
				}
				$element .='</select></td>';				
				$element .='<td>
								<label>
									<input name="dataattr['.$row->id_attribute.'][active]" checked class="ace ace-switch ace-switch-2" type="checkbox"/>
									<span class="lbl"></span>
								</label>
							</td>';
				
			
				$element .='<td>
								<label>
									<input name="dataattr['.$row->id_attribute.'][default]" class="ace ace-switch ace-switch-2" type="checkbox"/>
									<span class="lbl"></span>
								</label>
							</td>';
				
				$element .='</tr>';
				$i++;
			}
			
		}else{
			$element .='<tr><td colspan=7><div class="alert alert-warning">Attribute is empty</div></td>';
		}
		return $element;
	}
	function table_product_attribute($id){
		$element="";
		$sql = $this->m_admin->get_product_attribute(array('A.id_product'=>$id));
		
		//jika sudah ada video
		if (count($sql) > 0){
			$i=1;
			foreach ($sql as $row){
				$element .='<tr><td>'.$i++.'</td>';
				$element .='<td>'.$row->name_group.'</td>';
				$element .='<td>'.$row->name.'</td>
							<td>'.$row->symbol.' '.$row->price_impact.'</td>
							<td><select class="form-control" onchange="ajaxcall(\''.base_url(MODULE.'/product/change_sort/'.$row->id_product_attribute).'\',this.value,\'\')">';
							for ($s=1; $s <= count($sql); $s++){
								$select = ($row->sort == $s) ? 'selected' : '';
							$element .= '<option value="'.$s.'" '.$select.'>'.$s.'</option>';
							}
				$element .='</select></td>';
				$active = ($row->active == 1) ? 'checked' : '';
				$element .='<td>
								<label>
									<input '.$active.' class="ace ace-switch ace-switch-2" type="checkbox" onchange="ajaxcheck(\''.base_url(MODULE.'/product/active_attr').'\',\''.$row->id_product_attribute.'#active'.'\',this)">
									<span class="lbl"></span>
								</label>
							</td>';
				$default = ($row->default == 1) ? 'checked' : '';
				$disabled = ($row->default == 1) ? 'disabled' : '';
				$element .='<td>
								<label>
									<input '.$default.' class="ace ace-switch ace-switch-2" type="checkbox" '.$disabled.' onchange="ajaxcheck(\''.base_url(MODULE.'/'.$this->class.'/default_attr/'.$row->id_product).'\',\''.$row->id_product_attribute.'#default'.'\',this)">
									<span class="lbl"></span>
								</label>
							</td>';
				$element .='<td>'.$row->add_by.'</td>';
				$element .='<td>'.long_date_time($row->date_add).'</td>';
				$element .='<td><a class="btn btn-xs btn-info" href="#modal-form" data-toggle="modal" role="button" title="Edit Product Attribute" onclick="ajaxModal(\''.base_url(MODULE.'/product/edit_proattribute/'.$row->id_product).'\',\''.$row->id_product_attribute.'\',\'modal-form\')">'.icon_action('edit').'</a>
								<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/product/delete_prod_attr').'\',\''.$row->id_product_attribute.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a></td>';
		
				$element .='</tr>';
			}			
		}else{
			$element .='<tr><td colspan=10><div class="alert alert-warning">Product attribute is empty</div></td>';
		}
		return $element;
	}
	function table_list_sp($sql){
		$element="";			
		//jika sudah sp
		if (count($sql) > 0){
			$i=1;
			foreach ($sql as $row){
				$customer = (isset($row->first_name)) ? $row->first_name.' '.$row->last_name : 'ALL CUSTOMER';
				$email = (isset($row->email)) ? $row->email : 'ALL CUSTOMER';
				$date_from = long_date($row->date_from);
				$date_to = ($row->date_to != '0000-00-00') ? long_date($row->date_to) : 'UNLIMITED';
				$attribute = (isset($row->attr_group)) ? $row->attr_group.' '.$row->name : 'N/A';
				$element .='<tr><td>'.$i++.'</td>';
				$element .='<td>'.$row->name_group.'</td>';
				$element .='<td>'.$customer.'</td>';
				$element .='<td>'.$email.'</td>
							<td>'.$row->symbol.' '.number_format($row->price_sp,2).'</td>
							<td>'.$row->disc_sp.'</td>
							<td>'.$attribute.'</td>
							<td>'.$row->symbol.' '.number_format($row->price_impact,2).'</td>
							<td>'.$date_from.' - '.$date_to.'</td>
							<td>'.$row->add_by.'</td>
							<td>'.$row->date_add.'</td>';		
				
				$element .='<td>
								<a class="btn btn-xs btn-info" href="#modal-form" data-toggle="modal" role="button" title="Edit Price" onclick="ajaxModal(\''.base_url(MODULE.'/product/edit_sp/'.$row->id_product).'\',\''.$row->id_specific_price.'\',\'modal-form\')">'.icon_action('edit').'</a>
								<a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/product/delete_sp').'\',\''.$row->id_specific_price.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a>
							</td>';
	
				$element .='</tr>';
			}
		}else{
			$element .='<tr><td colspan=12><div class="alert alert-warning">Specific Price is empty</div></td>';
		}
		return $element;
	}
	function chosen_label_movement($id){
		$res ="";		
		$label = $this->m_admin->get_table('tb_label_movement_detail','*',array('id_label_movement'=>$id,'deleted'=>0));
		$res .='<select class="chosen-select form-control clear" name="labelmove" data-placeholder="Choose a label move..." required>
					<option value="" />';
		foreach ($label as $row){			
			$res .='<option value="'.$row->move_code.'">'.$row->name_label.'</option>';
		}
		$res .='</select>';
		$res .= $this->load_choosen();
		return $res;
	}
	function table_stock_attribute($sql,$id_label_movement){
		$element="";		
		//jika sudah ada video
		$wh = $this->m_admin->get_warehouse();
		if (count($sql) > 0){
			$i=1;
			foreach ($sql as $row){
				$name_group = isset($row->name_group) ? $row->name_group : 'N/A';
				$attribute = isset($row->name) ? $row->name : 'N/A';
				$element .='<tr><td>'.$i.'</td>';
				$element .='<td><center><label><input type="checkbox" class="ace ace-checkbox-2 rowlbl clear" name="checkattr[]" value="'.$row->id_product_attribute.'"><span class="lbl"></span></label></center></td>';
				$element .='<td><input type="text" name="dataattr['.$row->id_product_attribute.'][qty_available]" placeholder="Qty" id="qty'.$i.'" class="col-sm-8 clear" onkeypress="return decimals(event,this.id)"></td>
							<td>'.$name_group.'</td>
							<td>'.$attribute.'</td>';
				$element .='<td>';						
					$element .='<select name="dataattr['.$row->id_product_attribute.'][id_warehouse]" class="form-control clear" onchange="ajaxcall(\''.base_url(MODULE.'/stockmgm/choose_warehouse').'\',this.value,\'whlocfrom'.$i.'\')">
										<option value="" selected disabled>--</option>';
							foreach ($wh as $v){
									$element .='<option value="'.$v->id_warehouse.'">'.$v->name_warehouse.'</option>';
							}
					$element .='</select>';	
					if ($id_label_movement == 3){
						//jika melakuan transfer
						$element .='From <select name="dataattr['.$row->id_product_attribute.'][id_warehouse_to]" class="form-control clear" onchange="ajaxcall(\''.base_url(MODULE.'/stockmgm/choose_warehouse').'\',this.value,\'whlocto'.$i.'\')">
											<option value="" selected disabled>--</option>';
						foreach ($wh as $v){
							$element .='<option value="'.$v->id_warehouse.'">'.$v->name_warehouse.'</option>';
						}
						$element .='</select> To';
					}
				$element .='</td>';
					$element .='<td>';							
					$element .='<select name="dataattr['.$row->id_product_attribute.'][id_warehouse_location]" class="form-control clear" id="whlocfrom'.$i.'">
									<option value="" selected disabled>--</option>
								</select>';
					if ($id_label_movement == 3){
					$element .='From <select name="dataattr['.$row->id_product_attribute.'][id_warehouse_location_to]" class="form-control clear" id="whlocto'.$i.'">
									<option value="" selected disabled>--</option>
								</select> To';
					$element .='</td>';		
					}		
				$element .='</tr>';
				$i++;
			}
				
		}else{
			$element .='<tr><td colspan=7><div class="alert alert-warning">Product Attribute is empty</div></td>';
		}
		return $element;
	}
	function table_stock_available($sql){
		$element="";
		//jika sudah ada video		
		if (count($sql) > 0){
			$i=1;
			foreach ($sql as $row){
				$na = '<span class="badge badge-danger">N/A</span>';
				$name_group = isset($row->name_group) ? $row->name_group : $na;
				$attribute = isset($row->name) ? $row->name : $na;
				$qty_default = isset($row->qty_default) ? $row->qty_default : $na;
				$qty_sold = isset($row->qty_sold) ? $row->qty_sold : $na;
				$qty_available = isset($row->qty_available) ? $row->qty_available : $na;
				$name_warehouse = isset($row->name_warehouse) ? $row->name_warehouse : $na;
				$name_location= isset($row->name_location) ? $row->name_location : $na;
				$element .='<tr><td>'.$i.'</td>';		
				$element .='
							<td>'.$name_group.'</td>
							<td>'.$attribute.'</td>
							<td>'.$qty_default.'</td>
							<td>'.$qty_sold.'</td>
							<td>'.$qty_available.'</td>
							<td>'.$name_warehouse.'</td>
							<td>'.$name_location.'</td>
							<td><a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/stockmgm/delete_stock').'\',\''.$row->id_stock_available.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a></td>
							</tr>';
				
				$i++;
			}
	
		}else{
			$element .='<tr><td colspan=9><div class="alert alert-warning center">-- Stock is Not Available --</div></td>';
		}
		return $element;
	}
	function badge_qty($val){
		$badge = '<span class="badge badge-danger">N/A</span>';
		if ($val != 'N/A') {
			$badge = ($val > 0) ? '<span class="badge badge-success">'.$val.'</span>' : '<span class="badge badge-warning">'.$val.'</span>';
		}
		return $badge;
	}
	function table_po_cart(){
		$res='';
		$sql = $this->db->select('A.id_purchase_cart_det,A.id_product,B.name')
						->from('tb_purchase_cart_det as A')
						->join('tb_product as B','A.id_product = B.id_product','left')
						->where('A.saved',0)
						->get()->result();		
		$no=1;
		if (count($sql) > 0){
			foreach ($sql as $row){
				$attr = $this->m_admin->get_product_attribute(array('id_product'=>$row->id_product));
				$res .= '<input type="hidden" name="datax['.$no.'][id_purchase_cart_det]" value="'.$row->id_purchase_cart_det.'">';
				$res .= '<tr><td>'.$no.'</td>
							 <td>'.$row->id_product.'</td>
							 <td>'.$row->name.'</td>';	
						if (count($attr) > 0){			
							$res .='<td><select name="datax['.$no.'][id_product_attribute]" class="form-control">
										  <option value="" selected disabled>Choose a attribute</option>';
							foreach ($attr as $v){
								$res .='<option value="'.$v->id_product_attribute.'">'.$v->name_group.' '.$v->name.'</option>';
							}							
							$res .= '	</select>
							</td>';			
						}else{
							$res .='<td class="center">N/A</td>';
						}
				$res .='<td><input type="text" name="datax['.$no.'][unit_qty]" placeholder="Qty" id="qty'.$no.'" class="col-sm-8" onkeypress="return decimals(event,this.id)"></td>';
				$res .='<td><input type="text" name="datax['.$no.'][unit_price]" placeholder="Price" id="price'.$no.'" class="col-sm-8" onkeypress="return decimals(event,this.id)"></td>';
				$res .='<td><input type="text" name="datax['.$no.'][description_det]" placeholder="Description" class="col-sm-8"></td>';
				$res .='<td><a class="btn btn-xs btn-danger" onclick="DeleteConfirm(\''.base_url(MODULE.'/purchaseorder/delete_cart').'\',\''.$row->id_purchase_cart_det.'\')" title="Delete" data-rel="tooltip" data-placement="top">'.icon_action('delete').'</a></td>';
				$res .= '</tr>';
				$no++;
			}
		}
		else{
			$res .='<tr><td colspan=8 class="center">Please choose product for add to cart</td></tr>';
		}
		return $res;
	}
	function recent_activities($where){
		$res='';
		$sql = $this->m_admin->get_status_log($where);		
		if (count($sql) > 0){
			foreach ($sql as $row){
				if ($row->add_by == 'Buyer'){$btn='btn-info';}elseif($row->add_by == 'System'){$btn = 'btn-danger';}else{$btn = 'btn-success';}				
				$res .='<div class="profile-activity clearfix">
							<div class="row">
								<div class="col-md-3">
									<i class="pull-left thumbicon fa fa-user '.$btn.' no-hover"></i>
									<a class="user" href="javascript:void(0)">'.$row->add_by.'</a>																
									<div class="time">
									<i class="ace-icon fa fa-clock-o bigger-110"></i> '.long_date_time($row->date_add_status).'</div>	
								</div>
								<div class="col-md-9">
									<span class="'.$row->label_color.'">'.$row->name_status.'</span> - '.short_date($row->m_date_add_status).'
									<div>
									'.$row->notes.'
									</div>
									<div class="tools action-buttons">
										<a href="#" class="blue">
											<i class="ace-icon fa fa-pencil bigger-125"></i>
										</a>
										<a href="#" class="red">
											<i class="ace-icon fa fa-times bigger-125"></i>
										</a>
									</div>
								</div>
							</div>
																
						</div>';
			}
		}
		return $res;
	}
}