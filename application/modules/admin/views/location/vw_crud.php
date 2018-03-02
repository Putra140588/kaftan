		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_warehouse_location" value="<?php echo isset($id_warehouse_location) ? $id_warehouse_location : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Name Location </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Name Location"  name="name" class="col-xs-10 col-sm-5" value="<?php echo isset($name_location) ? $name_location : ''?>" required/>
											</div>
										</div>									
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Code Location </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Code Location"  name="code" class="col-xs-10 col-sm-5" value="<?php echo isset($code_location) ? $code_location : ''?>" required/>
											</div>
										</div>	
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Warehouse </label>
											<div class="col-sm-3">
												<select class="chosen-select form-control" name="wh" data-placeholder="Choose a Warehouse..." required>
													<option value="" />
													<?php foreach ($wh as $row){
														if (isset($id_warehouse)){$select = ($id_warehouse == $row->id_warehouse) ? 'selected' : '';}
														echo '<option value="'.$row->id_warehouse.'" '.$select.'>'.$row->name_warehouse.'</option>';
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