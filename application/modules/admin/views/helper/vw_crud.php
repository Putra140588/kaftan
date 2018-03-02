		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_helper" value="<?php echo isset($id_helper) ? $id_helper : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Name Helper </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Name Helper"  name="helper" class="col-xs-10 col-sm-10" value="<?php echo isset($name_helper) ? $name_helper : ''?>" required/>
											</div>
										</div>	
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Language </label>
											<div class="col-sm-3">
												<select class="chosen-select form-control" name="lang" data-placeholder="Choose a language..." required>
													<option value="" />
													<?php foreach ($lang as $row){
														if (isset($id_language)){$select = ($id_language == $row->id_language) ? 'selected' : '';}
														echo '<option value="'.$row->id_language.'" '.$select.'>'.$row->name_language.'</option>';
													}?> 															
												</select>
											</div>
										</div>	
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Sort Position </label>
											<div class="col-sm-2">
												<select class="chosen-select form-control" name="sort" data-placeholder="Choose a sort..." required>
													<option value="" />
													<?php for($i=1; $i <= 100; $i++ ){
														if (isset($sort_helper)){$select = ($sort_helper == $i) ? 'selected' : '';}
														echo '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
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
																																													
									 </div>
								</div>
						</div>																	
				  </form>
			 </div>
		  </div>
	  </div>
	</div>
</div>