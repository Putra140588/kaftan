		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_order_status" value="<?php echo isset($id_order_status) ? $id_order_status : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Status Name </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Status Name"  name="name" class="col-xs-10 col-sm-10" value="<?php echo isset($name_status) ? $name_status : ''?>" required/>
											</div>
										</div>	
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Status Code </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Status Code"  name="code" class="col-xs-10 col-sm-5" value="<?php echo isset($code_status) ? $code_status : ''?>" required/>
											</div>
										</div>					
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Label Color</label>
											<div class="col-sm-2">
												<select class="chosen-select form-control" name="label" data-placeholder="Choose a label color" onchange="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/select_label')?>',this.value,'labelcolor')" required>
													<option value="" />
													<?php foreach ($labelcolor as $row){
														if (isset($id_label_color)){$select = ($id_label_color == $row->id_label_color) ? 'selected' : '';}
														echo '<option value="'.$row->id_label_color.'" '.$select.'>'.$row->label_color.'</option>';
													}?> 															
												</select>
												<div class="space-6"></div>	
												<div id="labelcolor">
													<?php if (isset($id_label_color)){echo '<span class="'.$label_color.'">'.$label_color.'</span>';}?>
												</div>											
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