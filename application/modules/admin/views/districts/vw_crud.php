		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_districts" value="<?php echo isset($id_districts) ? $id_districts : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select=""; $id_cities = isset($id_cities) ? $id_cities : ''; $id_province = isset($id_province) ? $id_province : '';?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Districts Name </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Districts Name"  name="name" class="col-xs-10 col-sm-5" value="<?php echo isset($districts_name) ? $districts_name : ''?>" required/>
											</div>
										</div>										
										<div class="form-group required">
											<label class="col-sm-4 control-label"> province</label>
											<div class="col-sm-3">
											
												<select class="chosen-select form-control" name="province" data-placeholder="Choose a province" onchange="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/show_city/'.$id_cities)?>',this.value,'cities')" required>
													<option value="" />
													<?php foreach ($province as $row){
														if (!empty($id_province)){$select = ($id_province == $row->id_province) ? 'selected' : '';}
														echo '<option value="'.$row->id_province.'" '.$select.'>'.$row->province_name.'</option>';
													}?> 															
												</select>
											</div>
										</div>		
										<div class="form-group required">
											<label class="col-sm-4 control-label"> City</label>
											<div class="col-sm-3" id="cities">
												<?php echo $this->m_content->chosen_city($id_province,$id_cities);?>
											</div>
										</div>	
										<div class="form-group">
											<label class="col-sm-4 control-label">Active</label>
											<div class="col-sm-5">
												<label>
													<?php if (isset($active)){$select = ($active == 1) ? 'checked' : '';}?>
													<input class="ace ace-switch ace-switch-5" name="active" <?php echo $select?> type="checkbox">
													<span class="lbl"></span>
												</label>
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