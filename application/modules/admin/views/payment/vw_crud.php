		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_payment_method" value="<?php echo isset($id_payment_method) ? $id_payment_method : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Method Name </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Method Name" name="name" class="col-xs-10 col-sm-5" value="<?php echo isset($method_name) ? $method_name : ''?>" required/>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Method Code </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Method Code" name="code" class="col-xs-10 col-sm-5" value="<?php echo isset($method_code) ? $method_code : ''?>" required/>
											</div>
										</div>	
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Owner Name </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Owner Name" name="owner" class="col-xs-10 col-sm-5" value="<?php echo isset($name_owner) ? $name_owner : ''?>" required/>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Content </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Content" name="content" class="col-xs-10 col-sm-5" value="<?php echo isset($content) ? $content : ''?>" required/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label"> Description </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Description" name="description" class="col-xs-10 col-sm-10" value="<?php echo isset($description) ? $description : ''?>"/>
											</div>
										</div>		
										<div class="form-group">
											<label class="col-sm-4 control-label"> Sortir </label>
											<div class="col-sm-2">
												<select class="chosen-select form-control" name="sort" data-placeholder="Choose a sortir..">
													<option value="" />
													<?php for($i=1; $i <= 100; $i++ ){
														if (isset($sort)){$select = ($sort == $i) ? 'selected' : '';}
														echo '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
													}?>															
												</select>
											</div>
									</div>	
										<div class="form-group">
											<label class="col-sm-4 control-label"> Address </label>
											<div class="col-sm-5">
												<textarea name="address" class="textareas"><?php echo isset($address) ? $address : ''?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label"> Type of Payment </label>
											<div class="col-sm-2">
												<select class="chosen-select form-control" name="type" data-placeholder="Choose a type..">
													<option value="" />
													<?php foreach ($paytype as $row){
														if (isset($id_payment_type)){$select = ($id_payment_type == $row->id_payment_type) ? 'selected' : '';}
														echo '<option value="'.$row->id_payment_type.'" '.$select.'>'.$row->name_type.'</option>';
													}?>															
												</select>
											</div>
									</div>	
										<div class="form-group">
											<label class="col-sm-4 control-label">Logo</label>
											<div class="col-sm-3">
												<input type="file" class="input-file" name="logo">
												<div class="space-6"></div>
												<?php if (isset($logo)){?>
													<img style="width:150px" src="<?php echo base_url()?>assets/images/payment/<?php echo $logo?>">
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
									<div class="form-group">
											<label class="col-sm-4 control-label">Display</label>
											<div class="col-sm-5">
												<label>
													<?php if (isset($display)){$select = ($display == 1) ? 'checked' : '';}?>
													<input class="ace ace-switch ace-switch-5" name="display" <?php echo $select?> type="checkbox">
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