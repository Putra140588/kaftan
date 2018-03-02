		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_label_movement_detail" value="<?php echo isset($id_label_movement_detail) ? $id_label_movement_detail : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>																												
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Movement</label>
											<div class="col-sm-3">
												<select class="chosen-select form-control" name="movement" data-placeholder="Choose a Movement..." required>
													<option value="" />
													<?php foreach ($movement as $row){
														if (isset($id_label_movement)){$select = ($id_label_movement == $row->id_label_movement) ? 'selected' : '';}
														echo '<option value="'.$row->id_label_movement.'" '.$select.'>'.$row->name_movement.'</option>';
													}?> 															
												</select>
											</div>
										</div>		
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Label Name </label>
											<div class="col-sm-8">
												<input type="text" placeholder="Label Name"  name="namelabel" class="col-xs-10 col-sm-5" value="<?php echo isset($name_label) ? $name_label : ''?>"/>
											</div>
										</div>	
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Move Code </label>
											<div class="col-sm-8">
												<?php $readonly = (isset($id_label_movement)) ? 'readonly' : ''?>
												<input type="text" <?php echo $readonly ?>placeholder="Move Code"  name="code" class="col-xs-10 col-sm-5" value="<?php echo isset($move_code) ? $move_code : ''?>"/>
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