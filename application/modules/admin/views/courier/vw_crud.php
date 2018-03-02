		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_courier" value="<?php echo isset($id_courier) ? $id_courier : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Courier Name </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Courier Name"  name="name" class="col-xs-10 col-sm-5" value="<?php echo isset($name) ? $name : ''?>" required/>
											</div>
										</div>										
										<div class="form-group">
											<label class="col-sm-4 control-label"> URL Tracking</label>
											<div class="col-sm-8">
												<input type="text" placeholder="URL Tracking"  name="url" class="col-xs-10 col-sm-5" value="<?php echo isset($tracking_url) ? $tracking_url : ''?>"/>
											</div>
										</div>		
										<div class="form-group requried">
											<label class="col-sm-4 control-label"> Delay</label>
											<div class="col-sm-3">
												<input type="text" placeholder="Delay"  name="delay" class="col-xs-10 col-sm-5" value="<?php echo isset($delay) ? $delay : ''?>" required/>
											</div>
										</div>	
										<div class="form-group">
											<label class="col-sm-4 control-label">Is Free</label>
											<div class="col-sm-5">
												<label>
													<?php if (isset($is_free)){$select = ($is_free == 1) ? 'checked' : '';}?>
													<input class="ace ace-switch ace-switch-5" name="isfree" <?php echo $select?> type="checkbox">
													<span class="lbl"></span>
												</label>
											</div>
										</div>	
										<div class="form-group">
											<label class="col-sm-4 control-label">Fixed Cost</label>
											<div class="col-sm-5">
												<label>
													<?php if (isset($fixed_cost)){$select = ($fixed_cost == 1) ? 'checked' : '';}?>
													<input class="ace ace-switch ace-switch-5" name="fixedcost" <?php echo $select?> type="checkbox">
													<span class="lbl"></span>
												</label>
											</div>
										</div>		
										<div class="form-group required">
											<label class="control-label col-sm-4 no-padding-right">Can be Payment</label>			
											<div class="col-sm-8">
												<select id="pay" class="multiselect" multiple="" name="payment[]">
													<?php foreach ($payment as $g){
														$idpay = isset($idpaymenttype) ? $idpaymenttype : array();
														$checkgroup = in_array($g->id_payment_type, $idpaymenttype) ? "selected" : "";//used for post data															
														echo '<option value="'.$g->id_payment_type.'" '.$checkgroup.'>'.$g->name_type.'</option>';
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
										<div class="form-group">
											<label class="col-sm-4 control-label">Display</label>
											<div class="col-sm-5">
												<label>
													<?php if (isset($display)){$select = ($display == 1) ? 'checked' : '';}?>
													<input class="ace ace-switch ace-switch-5" name="display" <?php echo $select?> type="checkbox">
													<span class="lbl"></span>
												</label>
											</div>
										</div>	
										<div class="form-group">
											<label class="col-sm-4 control-label">Default Courier</label>
											<div class="col-sm-5">
												<label>
													<?php if (isset($default_courier)){$select = ($default_courier == 1) ? 'checked' : '';}?>
													<input class="ace ace-switch ace-switch-5" name="default" <?php echo $select?> type="checkbox">
													<span class="lbl"></span>
												</label>
											</div>
										</div>				
										<div class="form-group">
											<label class="col-sm-4 control-label">Logo</label>
											<div class="col-sm-3">
												<input type="file" class="input-file" name="logo">
												<div class="space-6"></div>
											<?php if (isset($image)){?>
												<img style="width:150px" src="<?php echo base_url()?>assets/images/courier/<?php echo image($image)?>">
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