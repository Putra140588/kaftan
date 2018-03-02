		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_language" value="<?php echo isset($id_language) ? $id_language : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Language Name </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Language Name"  name="name" class="col-xs-10 col-sm-5" value="<?php echo isset($name_language) ? $name_language : ''?>" required/>
											</div>
										</div>	
										<div class="form-group">
											<label class="col-sm-4 control-label">Flag</label>
											<div class="col-sm-3">
												<input type="file" class="input-file" name="flag">
												<div class="space-6"></div>
											<?php if (isset($flag)){?>
												<img style="width:50px" src="<?php echo base_url()?>assets/images/flag/<?php echo $flag?>">
											<?php }?>
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