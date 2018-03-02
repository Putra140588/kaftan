<div class="main-content">
	<div class="main-content-inner">
		<?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses_zone')?>">
					<input type="hidden" name="id_courier" value="<?php echo isset($id_courier) ? $id_courier : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<input type="hidden" name="edtbtn">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_header_title')?>																
							<div class="widget-body">
								<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="clearfix"></div>
										<div class="form-group">
											<label class="col-sm-4 control-label"> Courier Name </label>
											<div class="col-sm-5">
												<input type="text" readonly  name="name" class="col-xs-10 col-sm-5" value="<?php echo isset($name) ? $name : ''?>"/>
											</div>
										</div>	
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Country Zone </label>
											<div class="col-sm-8">
												<div class="radio">
													<label>
														<input class="ace" name="areacountry" value="<?php echo $ct[0]->id_country.'#'.$ct[0]->country_code?>" onclick="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/show_province/'.$id_courier.'/zone')?>',this.value,'province')" type="radio">
														<span class="lbl"> <?php echo $ct[0]->country_name?></span>														
													</label>
												</div>
												<div class="radio">
													<label>
														<input class="ace" name="areacountry" value="<?php echo $ct[0]->id_country?>#OTHER" onclick="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/show_province/'.$id_courier.'/zone')?>',this.value,'province')" type="radio">
														<span class="lbl"> International</span>
													</label>
												</div>												
											</div>
										</div>										
									<div id="districtcover"></div>																																							
								</div>
							</div>
						</div>																	
				  </form>
			 </div>
		  </div>
	  </div>
	</div>
</div>
