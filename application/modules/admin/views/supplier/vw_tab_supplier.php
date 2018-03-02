<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="<?php echo $id_form?>" value="<?php echo base_url(MODULE.'/supplier/proses#loadspl')?>">
					<input type="hidden" name="id_supplier" value="<?php echo isset($id_supplier) ? $id_supplier : ''?>">
					<input type="hidden" name="id_form" value="<?php echo isset($id_form) ? $id_form: ''?>">
					<input type="hidden" name="id_spl_add" value="<?php echo isset($id_spl_add) ? $id_spl_add : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Supplier Name </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Supplier Name" name="name" class="col-xs-10 col-sm-5" value="<?php echo isset($name_supplier) ? $name_supplier : ''?>" required/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label">Address</label>
											<div class="col-sm-5">
												<textarea id="address" name="address" class="textareas"><?php echo isset($address) ? $address : ''?></textarea>
											</div>
										</div>										
										<div class="form-group">
											<label class="col-sm-4 control-label">City</label>
											<div class="col-sm-5">
												<input type="text" placeholder="City" name="city" class="col-xs-10 col-sm-5" value="<?php echo isset($city) ? $city : ''?>">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label">Email</label>
											<div class="col-sm-5">
												<input type="email" placeholder="Email" name="email" class="col-xs-10 col-sm-5" value="<?php echo isset($email) ? $email : ''?>">
											</div>
										</div>										
										<div class="form-group required">
											<label class="col-sm-4 control-label">Phone</label>
											<div class="col-sm-5">
												<input type="text" placeholder="Phone" name="phone" class="col-xs-10 col-sm-5" value="<?php echo isset($phone) ? $phone : ''?>" required>
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
											<label class="col-sm-4 control-label">Image</label>
											<div class="col-sm-3">
												<input type="file" class="input-file" name="image">
												<div class="space-6"></div>
												<?php if (isset($image)){?>
													<img style="width:100px" src="<?php echo base_url()?>assets/images/supplier/<?php echo $image?>">
												<?php }?>
											</div>											
										</div>	
									 </div>
								</div>
						</div>																	
				  </form>