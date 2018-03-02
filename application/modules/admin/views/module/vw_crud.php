		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_modul" value="<?php echo isset($id_modul) ? $id_modul : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<?php $disabled = isset($modul_code) ? 'disabled' : '';$required = isset($modul_code) ? '' : 'required';?>
										<div class="form-group <?php echo $required?>">
											<label class="col-sm-4 control-label"> Modul Code </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Modul Code" <?php echo $disabled?> name="code" class="col-xs-10 col-sm-5" value="<?php echo isset($modul_code) ? $modul_code : ''?>" <?php echo $required?>/>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Modul Name </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Modul Name"  name="name" class="col-xs-10 col-sm-5" value="<?php echo isset($name) ? $name : ''?>" required/>
											</div>
										</div>										
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Level </label>
											<div class="col-sm-1">
												<select name="level" class="chosen-select form-control" data-placeholder="Choose a Level" required>
													<option value=""/>
													<?php for($i=0; $i <= 1; $i++){
														if (isset($id_level)){$select = ($id_level == $i) ? 'selected' : '';}
														echo '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
													}?>
												</select>												
											</div>
										</div>	
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Modul Parent </label>
											<div class="col-sm-3">
												<select name="parent" class="chosen-select form-control" data-placeholder="Choose a Modul Parent" required>
													<option value=""/>
													<?php if (isset($id_modul_parent)){$select = ($id_modul_parent == 0) ? 'selected' : '';}?>
													<option value="0" <?php echo $select?>>PARENT</option>
													<?php foreach ($parent as $row){
														if (isset($id_modul)){$select = ($id_modul_parent == $row->id_modul) ? 'selected' : '';}
														echo '<option value="'.$row->id_modul.'" '.$select.'>'.$row->name.'</option>';
													}?>
												</select>												
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Position </label>
											<div class="col-sm-1">
												<select name="position" class="chosen-select form-control" data-placeholder="Choose a Position" required>
													<option value=""/>
													<?php for($i=1; $i <= 50; $i++){
														if (isset($position)){$select = ($position == $i) ? 'selected' : '';}
														echo '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
													}?>
												</select>												
											</div>
										</div>	
										<div class="form-group required">
											<label class="col-sm-4 control-label"> URL Route </label>
											<div class="col-sm-5">
												<input type="text" placeholder="URL Route"  name="url" class="col-xs-10 col-sm-5" value="<?php echo isset($url_route) ? $url_route : ''?>" required/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label"> Icon Bootstrap </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Icon Bootstrap"  name="icon" class="col-xs-10 col-sm-5" value="<?php echo isset($icon) ? $icon : ''?>"/>
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