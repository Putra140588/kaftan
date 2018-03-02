		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_email_modul" value="<?php echo isset($id_email_modul) ? $id_email_modul : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";$disable = isset($id_email_modul) ? 'disabled' : '';?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label">Function Name</label>
											<div class="col-sm-5">
												<input type="text" placeholder="Function Name"  name="name" class="col-xs-10 col-sm-11" value="<?php echo isset($email_modul_name) ? $email_modul_name : ''?>" required/>
											</div>
										</div>																
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Function Code </label>
											<div class="col-sm-5">
												<input type="text" <?php echo $disable?> placeholder="Function Code"  name="code" class="col-xs-10 col-sm-5" value="<?php echo isset($email_modul_code) ? $email_modul_code : ''?>" required/>
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