		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_country" value="<?php echo isset($id_country) ? $id_country : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Country Name </label>
											<div class="col-sm-5">
												<input type="text" placeholder=" Country Name"  name="name" class="col-xs-10 col-sm-5" value="<?php echo isset($country_name) ? $country_name : ''?>" required/>
											</div>
										</div>		
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Country Code </label>
											<div class="col-sm-5">
												<input type="text" placeholder=" Country Code"  name="code" class="col-xs-10 col-sm-5" value="<?php echo isset($country_code) ? $country_code : ''?>" required/>
											</div>
										</div>								
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Currency</label>
											<div class="col-sm-2">
												<select class="chosen-select form-control" name="currency" data-placeholder="Choose a Currency" required>
													<option value="" />
													<?php foreach ($currency as $row){
														if (isset($id_currency)){$select = ($id_currency == $row->id_currency) ? 'selected' : '';}
														echo '<option value="'.$row->id_currency.'" '.$select.'>'.$row->name.'</option>';
													}?> 															
												</select>
											</div>
										</div>												
										<div class="form-group">
											<label class="col-sm-4 control-label">Flag</label>
											<div class="col-sm-3">
												<input type="file" class="input-file" name="flag">
												<div class="space-6"></div>
											<?php if (isset($flag)){?>
												<img style="width:150px" src="<?php echo base_url()?>assets/images/flag/<?php echo $flag?>">
											<?php }?>
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