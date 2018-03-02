		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_province" value="<?php echo isset($id_province) ? $id_province : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> province Name </label>
											<div class="col-sm-5">
												<input type="text" placeholder="province Name"  name="name" class="col-xs-10 col-sm-5" value="<?php echo isset($province_name) ? $province_name : ''?>" required/>
											</div>
										</div>									
										
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Country</label>
											<div class="col-sm-3">
												<select class="chosen-select form-control" name="country" data-placeholder="Choose a Country" required>
													<option value="" />
													<?php foreach ($country as $row){
														if (isset($id_country)){$select = ($id_country == $row->id_country) ? 'selected' : '';}
														echo '<option value="'.$row->id_country.'" '.$select.'>'.$row->country_name.'</option>';
													}?> 															
												</select>
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