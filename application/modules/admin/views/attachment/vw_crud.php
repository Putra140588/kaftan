		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_attachment" value="<?php echo isset($id_attachment) ? $id_attachment : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> File Name </label>
											<div class="col-sm-7">
												<input type="text" placeholder="File Name"  name="filename" class="col-xs-10 col-sm-5" value="<?php echo isset($file_name) ? $file_name : ''?>" required/>
											</div>
										</div>																		
										<div class="form-group" required>
											<label class="col-sm-4 control-label">File</label>
											<div class="col-sm-3">
												<input type="file" class="input-file" name="fileattch">
													<div class="space-6"></div>		
													<?php echo isset($file) ? $file : ''?>																						
											</div>											
										</div>												
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Manufacture </label>
											<div class="col-sm-3" id="city">
												<select class="chosen-select form-control" name="manufacture" data-placeholder="Choose a Manufacture" required>
													<option value="" />													
													<?php foreach ($manufacture as $row){
														if (isset($id_manufacture)){$select = ($id_manufacture == $row->id_manufacture) ? 'selected' : '';}
														echo '<option value="'.$row->id_manufacture.'" '.$select.'>'.$row->name.'</option>';
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