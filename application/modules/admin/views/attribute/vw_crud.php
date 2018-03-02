		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_attribute" value="<?php echo isset($id_attribute) ? $id_attribute : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Attribute Name </label>
											<div class="col-sm-7">
												<input type="text" placeholder="Attribute Name"  name="name" class="col-xs-10 col-sm-5" value="<?php echo isset($name) ? $name : ''?>" required/>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label">Group</label>
											<div class="col-sm-3">
												<select name="group" class="form-control" required>
													<option value="" selected disabled>Choose group</option>
													<?php foreach ($group as $row){
														if (isset($id_attribute_group)){$select=($id_attribute_group == $row->id_attribute_group) ? 'selected' : '';}
														echo '<option value="'.$row->id_attribute_group.'" '.$select.'>'.$row->name_group.'</option>';
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