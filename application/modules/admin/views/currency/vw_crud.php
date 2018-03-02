		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_currency" value="<?php echo isset($id_currency) ? $id_currency : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Currency Name </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Currency Name"  name="name" class="col-xs-10 col-sm-5" value="<?php echo isset($name) ? $name : ''?>" required/>
											</div>
										</div>																
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Iso Code </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Iso Code"  name="isocode" class="col-xs-10 col-sm-5" value="<?php echo isset($iso_code) ? $iso_code : ''?>" required/>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Iso Number </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Iso Number"  name="isonum" class="col-xs-10 col-sm-5" value="<?php echo isset($iso_code_number) ? $iso_code_number : ''?>" required/>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Symbol </label>
											<div class="col-sm-2">
											<select class="chosen-select form-control" name="symbol" data-placeholder="Choose a symbol" required>
													<option value="" />
													<?php $sym = array('Rp','¤','$','¢','£','¥','₣',
																	   '₤','₧','€','₹','₩','₴','₯','₮','₰','₲','₱','₳','₵','₭','₪','₫','%');
													foreach ($sym as $row){
														if (isset($symbol)){$select = ($symbol == $row) ? 'selected' : '';}
															echo '<option value="'.$row.'" '.$select.'>'.$row.'</option>';
													}?>											
												</select>												
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Exchange Rate </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Exchange Rate" id="rate" name="rate" class="col-xs-10 col-sm-5" onkeypress="return decimals(event,this.id)" value="<?php echo isset($exchange_rate) ? $exchange_rate : ''?>" required/>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Used</label>
											<div class="col-sm-2">
												<select class="chosen-select form-control" name="used" data-placeholder="Choose a Used" required>
													<option value="" />
													<?php $us = array('fo','bo');
													foreach ($us as $row){
														if (isset($used)){$select = ($used == $row) ? 'selected' : '';}
															echo '<option value="'.$row.'" '.$select.'>'.$row.'</option>';
													}?>											
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