		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_customer" value="<?php echo isset($id_customer) ? $id_customer : ''?>">
					<input type="hidden" name="id_address" value="<?php echo isset($id_address) ? $id_address : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";							
							$id_country = isset($id_country) ? $id_country : '';
							$id_province = isset($id_province) ? $id_province : '';
						?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="row">
											<div class="col-md-6">
												<div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
													<i class="glyphicon glyphicon-user"></i> Profile
												</div>
												<div class="space-6"></div>
												<div class="form-group required">
													<label class="col-sm-4 control-label">Gender</label>													
													<div class="col-sm-3">
														<select name="gender" class="form-control" required>
															<option value="" selected disabled>Choose a gender</option>
															<?php 
															foreach ($gender as $row){
																if (isset($id_gender)){$select = ($row->id_gender == $id_gender) ? 'selected' : '';}
																echo '<option value="'.$row->id_gender.'" '.$select.'>'.$row->name.'</option>';
}															?>
														</select>
													</div>
												</div>
												<div class="form-group required">
													<label class="col-sm-4 control-label"> First Name </label>
													<div class="col-sm-8">
														<input type="text" placeholder="First Name"  name="firstname" class="col-xs-10 col-sm-5" value="<?php echo isset($first_name) ? $first_name : ''?>" required/>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label"> Last Name </label>
													<div class="col-sm-8">
														<input type="text" placeholder="Last Name"  name="lastname" class="col-xs-10 col-sm-5" value="<?php echo isset($last_name) ? $last_name : ''?>"/>
													</div>
												</div>
												<div class="form-group required">
													<label class="col-sm-4 control-label"> Email </label>
													<div class="col-sm-8">
														<input type="email" placeholder="Email"  autocomplete="off" name="email" class="col-xs-10 col-sm-5" value="<?php echo isset($email) ? $email : ''?>"/>
													</div>
												</div>
												<?php $required = (isset($id_customer)) ? '' : 'required';?>
												<div class="form-group <?php echo $required?>">
													<label class="col-sm-4 control-label"> Password </label>
													<div class="col-sm-8">														
														<input type="password" placeholder="Password"  name="password" class="col-xs-10 col-sm-5" <?php echo $required?>/>
													</div>
												</div>
												<div class="form-group required">
													<label class="control-label col-sm-4 no-padding-right">Group</label>			
													<div class="col-sm-8">
														<select id="food" class="multiselect" multiple="" name="group[]">
														<?php foreach ($group as $g){
															$idgroups = isset($idgroup) ? $idgroup : array();
															$checkgroup = in_array($g->id_group, $idgroups) ? "selected" : "";//used for post data															
															echo '<option value="'.$g->id_group.'" '.$checkgroup.'>'.$g->name_group.'</option>';
														}?>															
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label"> Birthdate </label>
													<div class="col-sm-3">
														<div class="input-group">
															<input type="text" class="date-picker input-sm form-control" name="birthdate" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" value="<?php echo isset($birthdate) ? $birthdate : ''?>"/>
															<span class="input-group-addon">
															<i class="fa fa-calendar bigger-110"></i>
															</span>
														</div>														
													</div>													
												</div>
												<div class="form-group required">
													<label class="col-sm-4 control-label"> Phone </label>
													<div class="col-sm-8">
														<input type="text" placeholder="Phone"  name="phone" class="col-xs-10 col-sm-5" value="<?php echo isset($phone) ? $phone : ''?>" required/>
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
													<label class="col-sm-4 control-label">Photo</label>
													<div class="col-sm-3">
														<input type="file" class="input-file" name="photo">														
													</div>	
													<div class="col-sm-3">
													<?php if (isset($photo)){
													$image = (!empty($photo)) ? $photo : 'no-found.jpg';?>
															<img style="width:150px" src="<?php echo base_url()?>assets/fo/images/profile/<?php echo $image?>">
														<?php }?>
													</div>										
												</div>	
											</div>
											<div class="col-md-6">
												<div class="width-80 label label-success label-xlg arrowed-in arrowed-in-right">
													<i class="fa fa-briefcase"></i> Address
												</div>
												<div class="space-6"></div>
												<div class="form-group required">
													<label class="col-sm-4 control-label"> Alias Address Name </label>
													<div class="col-sm-8">
														<input type="text" placeholder="Alias Address Name"  name="alias" class="col-xs-10 col-sm-5" value="<?php echo isset($alias_name) ? $alias_name : ''?>" required/>
													</div>
												</div>
												<div class="form-group required">
													<label class="col-sm-4 control-label">Receiver Name</label>
													<div class="col-sm-8">
														<input type="text" placeholder="Receiver Name"  name="namereceived" class="col-xs-10 col-sm-5" value="<?php echo isset($name_received) ? $name_received : ''?>" required/>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label"> Company </label>
													<div class="col-sm-8">
														<input type="text" placeholder="Company"  name="company" class="col-xs-10 col-sm-5" value="<?php echo isset($company) ? $company : ''?>"/>
													</div>
												</div>
												<div class="form-group required">
													<label class="col-sm-4 control-label"> Address </label>
													<div class="col-sm-8">
														<textarea name="address" id="address" class="textareas"><?php echo isset($address) ? $address : ''?></textarea>
													</div>
												</div>
												<div class="form-group required">
													<label class="col-sm-4 control-label"> Country </label>
													<div class="col-sm-3">
														<select class="chosen-select form-control col-xs-10 col-sm-5" name="country" data-placeholder="Choose a country..." onchange="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/show_province/'.$id_form.'/'.$id_province)?>',this.value,'province<?php echo $id_form?>')" required>
															<option value="" />
															<?php foreach ($country as $row){
																if (isset($id_country)){$select = ($id_country == $row->id_country) ? 'selected' : '';}
																echo '<option value="'.$row->id_country.'#'.$row->country_code.'" '.$select.'>'.$row->country_name.'</option>';
															}?> 															
														</select>
													</div>
												</div>
												<div id="districtarea<?php echo $id_form?>">
													<?php if ($country_code == 'ID'){
															//show for edit data
															$this->load->view($class.'/vw_districtarea');
														}?>
												</div>
												<div class="form-group required">
													<label class="col-sm-4 control-label"> Post Code </label>
													<div class="col-sm-8">
														<input type="text" placeholder="Post Code"  name="postcode" class="col-xs-10 col-sm-5" value="<?php echo isset($postcode) ? $postcode : ''?>" required/>
													</div>
												</div>
												<div class="form-group required">
													<label class="col-sm-4 control-label"> Phone </label>
													<div class="col-sm-8">
														<input type="text" placeholder="Phone"  name="phoneaddr" class="col-xs-10 col-sm-5" value="<?php echo isset($phone) ? $phone : ''?>" required/>
													</div>
												</div>
											</div>
										</div>					
									</div>
								</div>
						</div>																	
				  </form>
			 </div>
		  </div>
		  <div id="listaddress">
			   <?php 
			  if (isset($id_customer)){
			  $this->load->view($class.'/vw_table_address');}?>
		  </div>
		 
	  </div>
	</div>
</div>