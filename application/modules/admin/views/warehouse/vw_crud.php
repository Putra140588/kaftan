		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="<?php echo $id_form?>" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_warehouse" value="<?php echo isset($id_warehouse) ? $id_warehouse : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";$id_districts = isset($id_districts) ? $id_districts : '';$id_cities = isset($id_cities) ? $id_cities : '';$id_province = isset($id_province) ? $id_province : '';?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Warehouse Name </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Warehouse Name"  name="name" class="col-xs-10 col-sm-5" value="<?php echo isset($name_warehouse) ? $name_warehouse : ''?>" required/>
											</div>
										</div>									
										
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Warehouse Head </label>
											<div class="col-sm-3">
												<select class="chosen-select form-control" name="head" data-placeholder="Choose a Warehouse Head..." required>
													<option value="" />
													<?php foreach ($employee as $row){
														if (isset($id_head_wh)){$select = ($id_head_wh == $row->id_employee) ? 'selected' : '';}
														echo '<option value="'.$row->id_employee.'" '.$select.'>'.$row->first_name.' '.$row->last_name.'</option>';
													}?> 															
												</select>
											</div>
										</div>		
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Phone </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Phone"  name="phone" class="col-xs-10 col-sm-5" value="<?php echo isset($phone_wh) ? $phone_wh : ''?>"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label"> Address </label>
											<div class="col-sm-5">
												<textarea name="address" id="address" class="textareas"><?php echo isset($address) ? $address : ''?></textarea>
											</div>
										</div>	
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Province </label>
											<div class="col-sm-3">
												<select class="chosen-select form-control" name="province" data-placeholder="Choose a province..." onchange="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/show_city/'.$id_cities)?>',this.value,'city<?php echo $id_form?>')" required>
													<option value="" />
													<?php foreach ($province as $row){
														if (isset($id_province)){$select = ($id_province == $row->id_province) ? 'selected' : '';}
														echo '<option value="'.$row->id_province.'" '.$select.'>'.$row->province_name.'</option>';
													}?> 															
												</select>
											</div>
										</div>	
										<div class="form-group required">
											<label class="col-sm-4 control-label"> City </label>
											<div class="col-sm-3" id="city<?php echo $id_form?>">
												<?php echo $this->m_content->chosen_city($id_province,$id_cities,$class,$id_form);?>
											</div>
										</div>	
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Districts </label>
											<div class="col-sm-3" id="districts<?php echo $id_form?>">
												<?php echo $this->m_content->chosen_districts($id_cities,$id_districts,$class,$id_form);?>
											
											</div>
										</div>	
										
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Branch </label>
											<div class="col-sm-3" id="city">
												<select class="chosen-select form-control" name="branch" data-placeholder="Choose a Branch..." required>
													<option value="" />													
													<?php 													
														foreach ($branch as $row){
															if (isset($id_branch)){$select = ($id_branch == $row->id_branch) ? 'selected' : '';}
															echo '<option value="'.$row->id_branch.'" '.$select.'>'.$row->name_branch.'</option>';
														}
													?> 															
												</select>
											</div>
										</div>	
																	
									 </div>
								</div>
						</div>																	
				  </form>
			 </div>
		  </div>
	  </div>
	</div>
</div>